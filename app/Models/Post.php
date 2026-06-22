<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Account;
use App\Models\Like;
use App\Models\Hashtag;
use App\Models\Bookmark;
use App\Models\CloseFriend;

class Post extends Model
{
    protected $fillable = [
        'account_id',
        'content',
        'media_path',
        'media_type',
        'is_pinned',
        'visibility',
    ];

    protected $casts = [
        'is_pinned' => 'boolean',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function isLikedBy($accountId)
    {
        return $this->likes()->where('account_id', $accountId)->exists();
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Relasi many-to-many ke Hashtag melalui pivot hashtag_post
     */
    public function hashtags()
    {
        return $this->belongsToMany(Hashtag::class, 'hashtag_post')->withTimestamps();
    }

    /**
     * Relasi one-to-many ke Bookmark
     */
    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class);
    }

    /**
     * Cek apakah post sudah di-bookmark oleh account tertentu
     */
    public function isBookmarkedBy($accountId): bool
    {
        return $this->bookmarks()->where('account_id', $accountId)->exists();
    }

    /**
     * Cek apakah $viewerId boleh melihat post ini.
     * - Post public: semua orang boleh lihat
     * - Post close_friend: hanya owner dan yang ada di close friend list owner
     */
    public function isVisibleTo(?int $viewerId): bool
    {
        if ($this->visibility === 'public') {
            return true;
        }

        // close_friend: cek apakah viewer ada di daftar close friend si pembuat post
        if ($viewerId === null) {
            return false;
        }

        if ($viewerId === $this->account_id) {
            return true;
        }

        return CloseFriend::where('account_id', $this->account_id)
            ->where('friend_id', $viewerId)
            ->exists();
    }
}