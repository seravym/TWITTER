<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Account;
use App\Models\Post;

class Repost extends Model
{
    protected $fillable = [
        'account_id',
        'post_id'
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}