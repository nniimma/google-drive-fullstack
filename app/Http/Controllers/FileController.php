<?php

namespace App\Http\Controllers;

use App\Http\Requests\ActionFileRequest;
use App\Http\Requests\ShareFileRequest;
use App\Http\Requests\StoreFileRequest;
use App\Http\Requests\StoreFolderRequest;
use App\Http\Requests\TrashFilesRequest;
use App\Http\Requests\UpdateFavoriteRequest;
use App\Http\Resources\FileResource;
use App\Mail\ShareFilesMail;
use App\Models\File;
use App\Models\FileShare;
use App\Models\StarredFile;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Notifications\Action;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Mail;

class FileController extends Controller
{
    public function index(Request $request, string $folder = null)
    {
        $search = $request->get('search');

        if ($folder) {
            $folder = File::query()->where('created_by', Auth::id())
                ->where('path', $folder)
                ->firstOrFail();
        }

        if (!$folder) {
            $folder = $this->getRoot();
        }

        $favorites = (int) $request->get('favorites');

        $query = File::query()
            ->select('files.*')
            ->with('starred')
            ->where('created_by', Auth::id())
            ->where('_lft', '!=', 1)
            ->orderBy('is_folder', 'desc')
            ->orderBy('files.created_at', 'desc')
            ->orderBy('files.id', 'desc');

        if ($search) {
            $query->where('name', 'like', "%$search%");
        } else {
            $query->where('parent_id', $folder->id);
        }

        if ($favorites === 1) {
            $query->join('starred_files', 'starred_files.file_id', '=', 'files.id')
                ->where('starred_files.user_id', Auth::id());
        }

        $files = $query->get();

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
        $search = $request->get('search');

        $query = File::onlyTrashed()->where('created_by', Auth::id())
            ->orderBy('is_folder', 'DESC')
            ->orderBy('deleted_at', 'DESC');

        if ($search) {
            $query->where('name', 'like', "%$search%");
        }

        $files = $query->get();

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
                StarredFile::where('file_id', $child->id)->delete();
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

    public function addToFavorites(UpdateFavoriteRequest $request)
    {
        $parent = File::query()
            ->where('id', $request->parent_id)
            ->first();;
        $data = $request->validated();
        $id = $data['id'];

        if (empty($id)) {
            return ['message' => 'Please select files to add to favorite.'];
        }

        $file = File::find($id);
        $user_id = Auth::id();
        $starredFile = StarredFile::query()
            ->where('file_id', $file->id)
            ->where('user_id', $user_id)
            ->first();

        if ($starredFile) {
            $starredFile->delete();
            $stared = 0;
        } else {
            StarredFile::create([
                'file_id' => $file->id,
                'user_id' => $user_id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            $stared = 1;
        }
        return to_route('files.index', [
            'folder' => $parent->path,
            'stared' => $stared
        ]);
    }

    public function share(ShareFileRequest $request)
    {
        $data = $request->validated();
        $parent = $request->parent;

        $all = $data['all'] ?? false;
        $ids = $data['ids'] ?? [];
        $email = $data['email'] ?? [];
        $user = User::query()->where('email', $email)->first();

        if ($all) {
            $files = $parent->children;
        } else {
            $files = File::find($ids);
        }

        $data = [];
        $ids = Arr::pluck($files, 'id');
        $existingFileIds = FileShare::query()->whereIn('file_id', $ids)->where('user_id', $user->id)->get()->keyBy('file_id');
        foreach ($files as $file) {
            if ($existingFileIds->has($file->id)) {
                continue;
            }
            $data[] = [
                'file_id' => $file->id,
                'user_id' => $user->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        FileShare::insert($data);

        Mail::to($user)->send(new ShareFilesMail($user, Auth::user(), $files));

        return redirect()->back();
    }

    public function sharedWithMe(Request $request)
    {
        $search = $request->get('search');
        $query = File::getSharedWithMe();

        if ($search) {
            $query->where('name', 'like', "%$search%");
        }

        $files = $query->get();

        $files = FileResource::collection($files);

        if ($request->wantsJson()) {
            return $files;
        }

        return Inertia::render('Files/SharedWithMe', compact('files'));
    }

    public function sharedByMe(Request $request)
    {
        $search = $request->get('search');
        $query = File::getSharedByMe();

        if ($search) {
            $query->where('name', 'like', "%$search%");
        }

        $files = $query->get();

        $files = FileResource::collection($files);

        if ($request->wantsJson()) {
            return $files;
        }

        return Inertia::render('Files/SharedByMe', compact('files'));
    }

    public function downloadSharedWithMe(ActionFileRequest $request)
    {
        $data = $request->validated();

        $all = $data['all'] ?? false;
        $ids = $data['ids'] ?? [];

        if (!$all && empty($ids)) {
            return [
                'message' => 'Please select files to download'
            ];
        }

        $zipName = 'shared_with_me';
        if ($all) {
            $files = File::getSharedWithMe()->get();
            $url = $this->createZip($files);
            $fileName = $zipName . '.zip';
        } else {
            [$url, $fileName] = $this->getDownloadUrl($ids, $zipName);
        }

        return [
            'url' => $url,
            'fileName' => $fileName
        ];
    }

    public function downloadSharedByMe(ActionFileRequest $request)
    {
        $data = $request->validated();

        $all = $data['all'] ?? false;
        $ids = $data['ids'] ?? [];

        if (!$all && empty($ids)) {
            return [
                'message' => 'Please select files to download'
            ];
        }

        $zipName = 'shared_by_me';
        if ($all) {
            $files = File::getSharedByMe()->get();
            $url = $this->createZip($files);
            $fileName = $zipName . '.zip';
        } else {
            [$url, $fileName] = $this->getDownloadUrl($ids, $zipName);
        }

        return [
            'url' => $url,
            'fileName' => $fileName
        ];
    }
}
