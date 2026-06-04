<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LiveController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PostController::class, 'index'])->name('home');

Route::resource('posts', PostController::class)
    ->except(['index', 'show']);

Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');

Route::post('/comments', [CommentController::class, 'store'])
    ->middleware('auth')->name('comments.store');

Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])
    ->middleware('auth')->name('comments.destroy');

// Lives
Route::middleware('auth')->group(function () {
    Route::get('/lives', [LiveController::class, 'index'])->name('lives.index');
    Route::get('/lives/create', [LiveController::class, 'create'])->name('lives.create');
    Route::post('/lives', [LiveController::class, 'store'])->name('lives.store');
    Route::patch('/lives/{live}/toggle', [LiveController::class, 'toggle'])->name('lives.toggle');
    Route::delete('/lives/{live}', [LiveController::class, 'destroy'])->name('lives.destroy');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';