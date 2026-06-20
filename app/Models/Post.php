<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Account;
use App\Models\Like;
use App\Models\Hashtag;
use App\Models\Bookmark;

class Post extends Model
{
    protected $fillable = ['account_id', 'content'];

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
}