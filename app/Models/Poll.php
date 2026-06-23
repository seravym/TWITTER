<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Poll extends Model
{
    protected $fillable = [
        'post_id',
        'question',
        'allow_multiple',
        'ends_at',
        'is_active',
    ];

    protected $casts = [
        'allow_multiple' => 'boolean',
        'is_active' => 'boolean',
        'ends_at' => 'datetime',
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function options()
    {
        return $this->hasMany(PollOption::class)->orderBy('order');
    }

    public function votes()
    {
        return $this->hasMany(PollVote::class);
    }

    public function isClosed(): bool
    {
        return !$this->is_active || ($this->ends_at && now()->greaterThanOrEqualTo($this->ends_at));
    }
}
