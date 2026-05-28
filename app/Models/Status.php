<?php

namespace App\Models;

use App\Models\Account;
use App\Models\Post;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    protected $fillable = [
        'account_id',
        'post_id',
        'content',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id');
    }
}
