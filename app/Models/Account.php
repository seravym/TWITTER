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
        'status_text',
        'status_expires_at',
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

    public function sentMessages()
    {
        return $this->hasMany(DirectMessage::class, 'sender_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(DirectMessage::class, 'receiver_id');
    }

    public function following()
    {
        return $this->hasMany(Follow::class, 'follower_id');
    }

    public function isFollowing($accountId)
    {
        return $this->following()->where('following_id', $accountId)->exists();
    }

    public function followers()
    {
        return $this->hasMany(Follow::class, 'following_id');
    }

    public function isFollowedBy($accountId)
    {
        return $this->followers()->where('follower_id', $accountId)->exists();
    }

    public function isMutual($accountId)
    {
    $iFollowHim = $this->following()->where('following_id', $accountId)->where('status', 'accepted')->exists();
    $heFollowsMe = $this->followers()->where('follower_id', $accountId)->where('status', 'accepted')->exists();
    return $iFollowHim && $heFollowsMe;
    }

    /**
     * Relasi One-to-Many ke tabel comments.
     * Satu akun bisa memiliki banyak komentar.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class, 'account_id');
    }

    public function likes()
    {
        return $this->hasMany(Like::class, 'account_id');
    }

    public function reposts()
    {
        return $this->hasMany(Repost::class, 'account_id');
    }

    public function getActiveStatusAttribute()
    {
        if ($this->status_text && $this->status_expires_at && now()->lessThan($this->status_expires_at)) {
            return $this->status_text;
        }
        return null;
    }

    public function stories()
    {
        return $this->hasMany(Story::class);
    }

    /**
     * Relasi one-to-many ke Bookmark
     */
    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class, 'account_id');
    }

    /**
     * Setting akun (one-to-one)
     */
    public function setting()
    {
        return $this->hasOne(Setting::class, 'account_id');
    }

    /**
     * Cek apakah akun ini mem-block $targetId
     */
    public function isBlocking(int $targetId): bool
    {
        $setting = $this->setting;
        if (!$setting || !$setting->blocked_accounts) {
            return false;
        }
        return in_array($targetId, $setting->blocked_accounts);
    }

    /**
     * Daftar akun yang dimasukkan ke close friend list oleh akun ini
     */
    public function closeFriends()
    {
        return $this->hasMany(CloseFriend::class, 'account_id');
    }

    /**
     * Cek apakah $friendId ada di daftar close friend akun ini
     */
    public function isCloseFriendOf(int $friendId): bool
    {
        return $this->closeFriends()->where('friend_id', $friendId)->exists();
    }
}