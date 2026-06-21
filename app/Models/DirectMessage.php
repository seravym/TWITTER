<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DirectMessage extends Model
{
    protected $fillable = [
        'sender_id',
        'receiver_id',
        'body',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    // Relasi: siapa yang mengirim pesan ini
    public function sender()
    {
        return $this->belongsTo(Account::class, 'sender_id');
    }

    // Relasi: siapa yang menerima pesan ini
    public function receiver()
    {
        return $this->belongsTo(Account::class, 'receiver_id');
    }

    // Scope: ambil semua pesan antara dua akun (percakapan)
    public function scopeConversation($query, $accountA, $accountB)
    {
        return $query->where(function ($q) use ($accountA, $accountB) {
            $q->where('sender_id', $accountA)
              ->where('receiver_id', $accountB);
        })->orWhere(function ($q) use ($accountA, $accountB) {
            $q->where('sender_id', $accountB)
              ->where('receiver_id', $accountA);
        });
    }

    // Helper: cek apakah pesan sudah dibaca
    public function isRead(): bool
    {
        return $this->read_at !== null;
    }
}