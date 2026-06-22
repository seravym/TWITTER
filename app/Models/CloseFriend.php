<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CloseFriend extends Model
{
    use HasFactory;

    protected $table = 'close_friends';

    protected $fillable = ['account_id', 'friend_id'];

    /**
     * Pemilik daftar close friend
     */
    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    /**
     * Akun yang ada di dalam daftar close friend
     */
    public function friend()
    {
        return $this->belongsTo(Account::class, 'friend_id');
    }
}