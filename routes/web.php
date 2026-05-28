<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DirectMessageController;
use App\Http\Controllers\AccountController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PostController::class, 'index'])->middleware('auth');

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/accounts', [AccountController::class, 'index']);
    Route::get('/accounts/{account}', [AccountController::class, 'show']);
    Route::get('/accounts/{account}/edit', [AccountController::class, 'edit']);
    Route::put('/accounts/{account}', [AccountController::class, 'update']);

    Route::resource('posts', PostController::class);
    Route::post('/posts/{post}/like', [PostController::class, 'like'])->name('posts.like');

    Route::prefix('messages')->group(function () {
        Route::get('/', [DirectMessageController::class, 'index'])->name('messages.index');
        Route::get('/{username}', [DirectMessageController::class, 'show'])->name('messages.show');
        Route::post('/{username}', [DirectMessageController::class, 'store'])->name('messages.store');
        Route::delete('/{id}', [DirectMessageController::class, 'destroy'])->name('messages.destroy');
    });
});