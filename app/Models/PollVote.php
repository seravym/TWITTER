<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PollVote extends Model
{
    protected $fillable = [
        'poll_option_id',
        'account_id',
    ];

    public function option()
    {
        return $this->belongsTo(PollOption::class, 'poll_option_id');
    }
}
