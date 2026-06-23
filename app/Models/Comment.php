<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'account_id', 
        'post_id', 
        'parent_id', 
        'content', 
        'is_toxic'
    ];

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }

    public function reports()
    {
        return $this->morphMany(Report::class, 'reportable');
    }
}
