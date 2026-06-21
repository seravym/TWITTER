<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Story extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_id',
        'media_path',
        'media_type',
        'expires_at',
    ];

    // Beritahu Laravel kalau expires_at itu format waktu/tanggal
    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}