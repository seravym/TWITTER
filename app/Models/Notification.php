<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_id',
        'sender_id',
        'type',
        'message',
        'reference_id',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    /**
     * Penerima notifikasi
     */
    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    /**
     * Pengirim aksi (yang trigger notifikasi)
     */
    public function sender()
    {
        return $this->belongsTo(Account::class, 'sender_id');
    }
}