<?php

use App\Http\Controllers\FileController;
use App\Http\Controllers\FolderController;
use App\Http\Controllers\ProfileController;
use GuzzleHttp\Cookie\FileCookieJar;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/files{folder?}', [FileController::class, 'index'])
        ->where('folder', '(.*)')->name('files.index');
    Route::post('/folders', [FileController::class, 'storeFolder'])->name('folders.store');
    Route::post('/files', [FileController::class, 'storeFile'])->name('files.store');
    Route::delete('/files', [FileController::class, 'destroy'])->name('files.destroy');
    Route::get('/file/download', [FileController::class, 'download'])->name('files.download');
    Route::get('/trash', [FileController::class, 'trash'])->name('files.trash');
    Route::post('/restore', [FileController::class, 'restore'])->name('files.restore');
    Route::delete('/files/delete', [FileController::class, 'destroyPermanently'])->name('files.destroyPermanently');
    Route::post('/favorites', [FileController::class, 'addToFavorites'])->name('files.favorites');
    Route::post('/files/share', [FileController::class, 'share'])->name('files.share');
    Route::get('/file/shared/with', [FileController::class, 'sharedWithMe'])->name('files.sharedWithMe');
    Route::get('/file/shared/by', [FileController::class, 'sharedByMe'])->name('files.sharedByMe');
});

require __DIR__ . '/auth.php';
