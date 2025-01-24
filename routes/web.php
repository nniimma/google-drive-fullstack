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
});

require __DIR__ . '/auth.php';
