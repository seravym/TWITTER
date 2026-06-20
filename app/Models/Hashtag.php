<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hashtag extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'post_count'];

    /**
     * Relasi many-to-many ke Post melalui pivot hashtag_post
     */
    public function posts()
    {
        return $this->belongsToMany(Post::class, 'hashtag_post')->withTimestamps();
    }

    /**
     * Increment post_count saat hashtag digunakan di post baru
     */
    public function incrementPostCount(): void
    {
        $this->increment('post_count');
    }

    /**
     * Decrement post_count saat post dihapus
     */
    public function decrementPostCount(): void
    {
        if ($this->post_count > 0) {
            $this->decrement('post_count');
        }
    }
}