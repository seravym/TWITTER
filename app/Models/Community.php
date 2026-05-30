<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Community extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'creator_id',
    ];

    public function creator()
    {
        return $this->belongsTo(Account::class, 'creator_id');
    }

    public function members()
    {
        return $this->belongsToMany(Account::class, 'community_user', 'community_id', 'account_id')
                    ->withPivot('role')
                    ->withTimestamps();
    }
}