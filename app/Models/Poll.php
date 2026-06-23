<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Poll extends Model
{
    protected $fillable = [
        'post_id',
        'question',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function options()
    {
        return $this->hasMany(PollOption::class);
    }

    public function isExpired(): bool
    {
        return $this->expires_at !== null && now()->greaterThan($this->expires_at);
    }

    public function hasVoted(?int $accountId): bool
    {
        if (!$accountId) {
            return false;
        }

        return PollVote::where('account_id', $accountId)
            ->whereIn('poll_option_id', $this->options->pluck('id'))
            ->exists();
    }

    public function userVote(?int $accountId): ?PollVote
    {
        if (!$accountId) {
            return null;
        }

        return PollVote::where('account_id', $accountId)
            ->whereIn('poll_option_id', $this->options->pluck('id'))
            ->first();
    }

    public function totalVotes(): int
    {
        return $this->options->sum(fn ($option) => $option->votes->count());
    }
}
