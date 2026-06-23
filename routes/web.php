<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DirectMessageController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FollowController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\CommunityPostController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\StoryController;
use App\Http\Controllers\HashtagController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\CloseFriendController;
use App\Http\Controllers\MenfessController;
use App\Http\Controllers\RepostController;
use App\Http\Controllers\PollController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ArticleController;

Route::get('/', [PostController::class, 'index'])->middleware('auth');

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister']);
    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'processForgotPassword'])->name('password.update');
});

Route::middleware('auth')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);

    // --- Route Accounts ---
    Route::get('/accounts', [AccountController::class, 'index']);

    // --- Fitur Status ---
    Route::post('/accounts/status', [AccountController::class, 'updateStatus'])->name('accounts.status');

    // --- Fitur Report Akun ---
    Route::post('/accounts/{account}/report', [ReportController::class, 'storeAccount'])->name('reports.accounts.store');

    // --- Route Profil ---
    Route::get('/accounts/{account}', [AccountController::class, 'show']);
    Route::get('/accounts/{account}/edit', [AccountController::class, 'edit']);
    Route::put('/accounts/{account}', [AccountController::class, 'update']);

    Route::get('/accounts/{username}', [AccountController::class, 'show']);

    // --- Route Stories ---
    Route::post('/stories', [StoryController::class, 'store'])->name('stories.store');
    Route::get('/stories/{account}', [StoryController::class, 'show'])->name('stories.show');

    // --- Route Articles ---
    Route::resource('articles', ArticleController::class);

    // --- Route Posts ---
    Route::get('/posts/archive', [PostController::class, 'archiveIndex'])->name('posts.archive.index');
    Route::post('/posts/{post}/archive', [PostController::class, 'archive'])->name('posts.archive');
    Route::post('/posts/{post}/restore', [PostController::class, 'restore'])->name('posts.restore');
    Route::get('/posts/{post}/download-media', [PostController::class, 'downloadMedia'])->name('posts.downloadMedia');
    Route::post('/posts/{post}/report', [ReportController::class, 'storePost'])->name('reports.posts.store');
    Route::resource('posts', PostController::class);
    Route::post('/posts/{post}/like', [LikeController::class, 'toggle'])->name('posts.like');
    Route::post('/posts/{post}/pin', [PostController::class, 'pin'])->name('posts.pin');
    Route::post('/posts/{post}/repost', [RepostController::class, 'toggle'])->name('posts.repost');
    Route::get('/posts/{post}/quote', [PostController::class, 'quote'])->name('posts.quote');

    // --- Route Polls ---
    Route::post('/polls/{poll}/vote', [PollController::class, 'vote'])->name('polls.vote')->middleware('auth');

    // --- Fitur Komentar ---
    Route::get('/comments', [CommentController::class, 'index']);
    Route::post('/comments', [CommentController::class, 'store']);
    Route::put('/comments/{comment}', [CommentController::class, 'update']);
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy']);
    Route::post('/comments/{comment}/report', [ReportController::class, 'storeComment'])->name('reports.comments.store');

    // --- Fitur Follow & Request System ---
    Route::get('/follows', [FollowController::class, 'index']);
    Route::post('/follows', [FollowController::class, 'store']);
    Route::delete('/follows/{id}', [FollowController::class, 'destroy']);
    Route::post('/follows/accept/{followerId}', [FollowController::class, 'accept'])->name('follows.accept');
    Route::post('/follows/reject/{followerId}', [FollowController::class, 'reject'])->name('follows.reject');

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
    Route::post('/communities/{community}/posts', [CommunityPostController::class, 'store'])->name('communities.posts.store');
    Route::get('/communities/{community}', [CommunityController::class, 'show'])->name('communities.show');
    Route::post('/communities/{community}/join', [CommunityController::class, 'join']);
    Route::post('/communities/{community}/leave', [CommunityController::class, 'leave']);

    // --- Fitur Settings ---
    Route::get('/settings', [SettingController::class, 'show'])->name('settings.show');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
    Route::post('/settings/block/{account}', [SettingController::class, 'block'])->name('settings.block');
    Route::delete('/settings/block/{account}', [SettingController::class, 'unblock'])->name('settings.unblock');
    Route::post('/settings/block-by-username', [SettingController::class, 'blockByUsername'])->name('settings.block-by-username');

    // --- Fitur Hashtag ---
    Route::get('/hashtags', [HashtagController::class, 'index'])->name('hashtags.index');
    Route::get('/hashtags/{name}', [HashtagController::class, 'show'])->name('hashtags.show');

    // --- Fitur Bookmark ---
    Route::get('/bookmarks', [BookmarkController::class, 'index'])->name('bookmarks.index');
    Route::post('/bookmarks/{post}', [BookmarkController::class, 'toggle'])->name('bookmarks.toggle');
    Route::delete('/bookmarks/{bookmark}', [BookmarkController::class, 'destroy'])->name('bookmarks.destroy');

    // --- Fitur Close Friend ---
    Route::get('/close-friends', [CloseFriendController::class, 'index'])->name('close-friends.index');
    Route::post('/close-friends/{friendId}', [CloseFriendController::class, 'store'])->name('close-friends.store');
    Route::delete('/close-friends/{friendId}', [CloseFriendController::class, 'destroy'])->name('close-friends.destroy');

    // --- Fitur Menfess ---
    Route::get('/menfess/create', [MenfessController::class, 'create'])->name('menfess.create');
    Route::post('/menfess', [MenfessController::class, 'store'])->name('menfess.store');

    Route::get('/menfess', [MenfessController::class, 'index'])->name('menfess.index');
    Route::post('/menfess/{id}/approve', [MenfessController::class, 'approve'])->name('menfess.approve');

    // --- Fitur Notifikasi ---
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::delete('/notifications/{notification}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllRead'])->name('notifications.markAllRead');
});
