<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Account extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'avatar',
        'bio',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function ownedCommunity()
    {
        return $this->hasMany(Community::class, 'creator_id');
    }

    public function joinedCommunities()
    {
        return $this->belongsToMany(Community::class, 'community_user', 'account_id', 'community_id')
                    ->withPivot('role')
                    ->withTimestamps();
    }

    public function sentMessages()
    {
        return $this->hasMany(DirectMessage::class, 'sender_id');
    }

    // Pesan yang diterima oleh akun ini
    public function receivedMessages()
    {
        return $this->hasMany(DirectMessage::class, 'receiver_id');
    }
}