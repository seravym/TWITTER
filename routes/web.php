<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DirectMessageController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FollowController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\StoryController;

Route::get('/', [PostController::class, 'index'])->middleware('auth');

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    
    Route::post('/logout', [AuthController::class, 'logout']);

    // --- Route Accounts ---
    Route::get('/accounts', [AccountController::class, 'index']);
    
    // --- Fitur Status ---
    Route::post('/accounts/status', [AccountController::class, 'updateStatus'])->name('accounts.status');
    
    // --- Route Profil ---
    Route::get('/accounts/{account}', [AccountController::class, 'show']);
    Route::get('/accounts/{account}/edit', [AccountController::class, 'edit']);
    Route::put('/accounts/{account}', [AccountController::class, 'update']);
    
    Route::get('/accounts/{username}', [AccountController::class, 'show']);

    // --- Route Stories ---
    Route::post('/stories', [StoryController::class, 'store'])->name('stories.store');
    Route::get('/stories/{account}', [StoryController::class, 'show'])->name('stories.show');

    // --- Route Posts ---
    Route::resource('posts', PostController::class);
    Route::post('/posts/{post}/like', [LikeController::class, 'toggle'])->name('posts.like');

    // --- Fitur Komentar ---
    Route::get('/comments', [CommentController::class, 'index']);
    Route::post('/comments', [CommentController::class, 'store']);
    Route::put('/comments/{comment}', [CommentController::class, 'update']);
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy']);

    // --- Fitur Follow & Request System ---
    Route::get('/follows', [FollowController::class, 'index']);
    Route::post('/follows', [FollowController::class, 'store']);
    Route::delete('/follows/{id}', [FollowController::class, 'destroy']);
    Route::post('/follows/accept/{followerId}', [FollowController::class, 'accept']);
    Route::post('/follows/reject/{followerId}', [FollowController::class, 'reject']);

    // --- Fitur Pesan Langsung (DM) ---
    Route::prefix('messages')->group(function () {
        Route::get('/', [DirectMessageController::class, 'index'])->name('messages.index');
        Route::get('/{username}', [DirectMessageController::class, 'show'])->name('messages.show');
        Route::post('/{username}', [DirectMessageController::class, 'store'])->name('messages.store');
        Route::delete('/{id}', [DirectMessageController::class, 'destroy'])->name('messages.destroy');
    });

    // --- Fitur Komunitas ---
    Route::get('/communities', [CommunityController::class, 'index']);
    Route::get('/communities/create', [CommunityController::class, 'create']);
    Route::post('/communities', [CommunityController::class, 'store']);
    Route::get('/communities/{community}', [CommunityController::class, 'show']);
    Route::post('/communities/{community}/join', [CommunityController::class, 'join']);
    Route::post('/communities/{community}/leave', [CommunityController::class, 'leave']);

    // --- Fitur Settings ---
    Route::get('/settings', [SettingController::class, 'show'])->name('settings.show');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
});