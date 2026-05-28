<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/posts');
});

/*
| GUEST (belum login)
*/
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister']);
    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

/*
| LOGOUT (harus login)
*/
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth');

/*
| POSTS (harus login semua)
*/
Route::resource('posts', PostController::class)
    ->middleware('auth');