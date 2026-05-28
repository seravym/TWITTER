<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Account;
use App\Models\Like;

class Post extends Model
{
    protected $fillable = ['user_id', 'content'];

    public function user()
    {
        return $this->belongsTo(Account::class, 'user_id');
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function isLikedBy($accountId)
    {
        return $this->likes()->where('account_id', $accountId)->exists();
    }
}
