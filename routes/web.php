<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/posts');
});


Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister']);
    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});


Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth');


Route::resource('posts', PostController::class)
    ->middleware('auth');


Route::post('/posts/{post}/like', [PostController::class, 'like'])
    ->middleware('auth')
    ->name('posts.like');