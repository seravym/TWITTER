<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bookmark extends Model
{
    use HasFactory;

    protected $fillable = ['account_id', 'post_id'];

    /**
     * Bookmark dimiliki oleh satu Account
     */
    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    /**
     * Bookmark mengarah ke satu Post
     */
    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id');
    }
}