<?php

namespace App\Models;

use App\Models\Account;
use App\Models\Post;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class Explore extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'explores';

    /**
     * Search across posts and accounts.
     *
     * @param  string  $term
     * @return Collection
     */
    public static function search(string $term): Collection
    {
        $term = trim($term);

        if ($term === '') {
            return collect([
                'posts' => collect(),
                'accounts' => collect(),
            ]);
        }

        return collect([
            'posts' => static::searchPosts($term),
            'accounts' => static::searchAccounts($term),
        ]);
    }

    public static function searchPosts(string $term)
    {
        return Post::query()
            ->where('content', 'like', "%{$term}%")
            ->orWhereHas('user', function ($query) use ($term) {
                $query->where('name', 'like', "%{$term}%")
                      ->orWhere('username', 'like', "%{$term}%");
            })
            ->latest()
            ->get();
    }

    public static function searchAccounts(string $term)
    {
        return Account::query()
            ->where('name', 'like', "%{$term}%")
            ->orWhere('username', 'like', "%{$term}%")
            ->orWhere('bio', 'like', "%{$term}%")
            ->get();
    }
}
