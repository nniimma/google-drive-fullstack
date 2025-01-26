<?php

namespace App\Http\Controllers;

use App\Http\Requests\ActionFileRequest;
use App\Http\Requests\StoreFileRequest;
use App\Http\Requests\StoreFolderRequest;
use App\Http\Requests\TrashFilesRequest;
use App\Http\Resources\FileResource;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Illuminate\Support\Str;

class FileController extends Controller
{
    public function index(string $folder = null)
    {
        if ($folder) {
            $folder = File::query()->where('created_by', Auth::id())
                ->where('path', $folder)
                ->firstOrFail();
        }

        if (!$folder) {
            $folder = $this->getRoot();
        }

        $files = File::query()->where('parent_id', $folder->id)
            ->where('created_by', Auth::id())
            ->orderBy('is_folder', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        $files = FileResource::collection($files);

        $ancestors = FileResource::collection([...$folder->ancestors, $folder]);

        $folder = new FileResource($folder);

        return Inertia::render('Files/Index', compact('files', 'folder', 'ancestors'));
    }

    public function storeFolder(StoreFolderRequest $request)
    {
        $data = $request->validated();
        $parent = $request->parent;

        if (!$parent) {
            $parent = $this->getRoot();
        }

        $file = new File();
        $file->is_folder = 1;
        $file->name = $data['name'];

        $parent->appendNode($file);
    }

    public function storeFile(StoreFileRequest $request)
    {
        $data = $request->validated();
        $parent = $request->parent;
        $user = $request->user();
        $fileTree = $request->file_tree;

        if (!$parent) {
            $parent = $this->getRoot();
        }

        if (!empty($fileTree)) {
            $this->saveFileTree($fileTree, $parent, $user);
        } else {
            foreach ($data['files'] as $file) {
                $this->saveFile($file, $user, $parent);
            }
        }
    }

    public function destroy(ActionFileRequest $request)
    {
        $data = $request->validated();
        $parent = $request->parent;

        if ($data['all']) {
            $children = $parent->children;

            foreach ($children as $child) {
                $child->moveToTrash();
            }
        } else {
            foreach ($data['ids'] ?? [] as $id) {
                $file = File::find($id);
                $file->moveToTrash();
            }
        }

        return to_route('files.index', ['folder' => $parent->path]);
    }

    public function download(ActionFileRequest $request)
    {
        //need this code to link the storage to the public folder
        // php artisan storage:link
        $data = $request->validated();
        $parent = $request->parent;

        $all = $data['all'] ?? false;
        $ids = $data['ids'] ?? [];

        if ($all) {
            $url = $this->createZip($parent->children);
            $fileName = $parent->name . '.zip';
        } else {
            [$url, $fileName] = $this->getDownloadUrl($ids, $parent->name);
        }

        return [
            'url' => $url,
            'fileName' => $fileName,
        ];
    }

    public function createZip($files): string
    {
        $zipPath = 'zip/' . Str::random() . '.zip';
        $publicPath = "public/$zipPath";

        if (!is_dir(dirname($publicPath))) {
            Storage::makeDirectory(dirname($publicPath));
        }

        $zipFile = Storage::path($publicPath);
        //! to use zip we need to uncomment the ;extension=zip inside php.ini
        $zip = new \ZipArchive();
        if ($zip->open($zipFile, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) == true) {
            $this->addFilesToZip($zip, $files);
        }

        $zip->close();

        return asset(Storage::url($zipPath));
    }

    private function addFilesToZip($zip, $files, $ancestors = '')
    {
        foreach ($files as $file) {
            if ($file->is_folder) {
                $this->addFilesToZip($zip, $file->children, $ancestors . $file->name . '/');
            } else {
                $zip->addFile(Storage::path($file->storage_path), $ancestors . $file->name);
            }
        }
    }

    private function getRoot()
    {
        return File::query()->whereIsRoot()->where('created_by', Auth::id())->firstOrFail();
    }

    private function saveFileTree($fileTree, $parent, $user)
    {
        foreach ($fileTree as $name => $file) {
            if (is_array($file)) {
                $folder = new File();
                $folder->is_folder = 1;
                $folder->name = $name;

                $parent->appendNode($folder);
                $this->saveFileTree($file, $folder, $user);
            } else {
                $this->saveFile($file, $user, $parent);
            }
        }
    }

    private function saveFile($file, $user, $parent)
    {
        $path = $file->store('/files/' . $user->id);
        $model = new File();
        $model->storage_path = $path;
        $model->is_folder = false;
        $model->name = $file->getClientOriginalName();
        $model->mime = $file->getMimeType();
        $model->size = $file->getSize();
        $parent->appendNode($model);
    }

    private function getDownloadUrl(array $ids, $zipName)
    {
        if (count($ids) === 1) {
            $file = File::find($ids[0]);
            if ($file->is_folder) {
                if ($file->children->count() === 0) {
                    throw new \Exception('The folder is empty.');
                }
                $url = $this->createZip($file->children);
                $fileName = $file->name . '.zip';
            } else {
                $destination = 'public/' . pathinfo($file->storage_path, PATHINFO_BASENAME);
                Storage::copy($file->storage_path, $destination);

                $url = asset(Storage::url($destination));
                $fileName = $file->name;
            }
        } else {
            $files = File::query()->whereIn('id', $ids)->get();
            $url = $this->createZip($files);

            $fileName = $zipName . '.zip';
        }

        return [$url, $fileName];
    }

    public function trash(Request $request)
    {
        $files = File::onlyTrashed()->where('created_by', Auth::id())
            ->orderBy('is_folder', 'DESC')
            ->orderBy('deleted_at', 'DESC')
            ->paginate(10);

        $files = FileResource::collection($files);

        return Inertia::render('Files/Trash', compact('files'));
    }

    public function restore(TrashFilesRequest $request)
    {
        $data = $request->validated();

        if ($data['all']) {
            $children = File::onlyTrashed()->get();

            foreach ($children as $child) {
                $child->restore();
            }
        } else {
            $ids = $data['ids'] ?? [];
            $children = File::onlyTrashed()->whereIn('id', $ids)->get();

            foreach ($children as $child) {
                $child->restore();
            }
        }

        return to_route('files.trash');
    }

    public function destroyPermanently(TrashFilesRequest $request)
    {
        $data = $request->validated();

        if ($data['all']) {
            $children = File::onlyTrashed()->get();

            foreach ($children as $child) {
                $child->DeletePermanently();
            }
        } else {
            $ids = $data['ids'] ?? [];
            $children = File::onlyTrashed()->whereIn('id', $ids)->get();

            foreach ($children as $child) {
                $child->DeletePermanently();
            }
        }

        return to_route('files.trash');
    }
}
