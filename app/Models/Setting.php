<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_id',
        'isPrivateAccount',
        'allowDmFrom',
        'showOnlineStatus',
        'notificationMessage',
        'notificationFollow',
        'notificationLike',
        'theme',
        'language',
    ];

    protected $casts = [
        'isPrivateAccount' => 'boolean',
        'showOnlineStatus' => 'boolean',
        'notificationMessage' => 'boolean',
        'notificationFollow' => 'boolean',
        'notificationLike' => 'boolean',
        'blocked_accounts' => 'array',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}