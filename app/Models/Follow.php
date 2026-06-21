<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{
    protected $fillable = ['follower_id', 'following_id'];

    public function follower()
    {
        return $this->belongsTo(Account::class, 'follower_id');
    }

    public function following()
    {
        return $this->belongsTo(Account::class, 'following_id');
    }
}