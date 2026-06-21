<?php

namespace App\Http\Controllers;

use App\Models\Hashtag;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HashtagController extends Controller
{
    /**
     * Tampilkan semua post yang mengandung hashtag tertentu.
     * Route: GET /hashtags/{name}
     */
    public function show(string $name)
    {
        $hashtag = Hashtag::where('name', strtolower($name))->firstOrFail();

        $currentUserId = Auth::id();
        $acceptedFollowingIds = Auth::check()
            ? Auth::user()->following()->where('status', 'accepted')->pluck('following_id')->toArray()
            : [];

        // Filter private account — logika sama dengan PostController@index
        $privateAccountIds = \App\Models\Setting::where('isPrivateAccount', true)
            ->pluck('account_id')->toArray();

        $posts = $hashtag->posts()
            ->with(['account', 'likes', 'comments', 'hashtags', 'bookmarks'])
            ->where(function ($query) use ($currentUserId, $acceptedFollowingIds, $privateAccountIds) {
                $query->whereNotIn('account_id', $privateAccountIds)
                      ->orWhere('account_id', $currentUserId)
                      ->orWhereIn('account_id', $acceptedFollowingIds);
            })
            ->latest()
            ->get();

        return view('hashtags.show', compact('hashtag', 'posts'));
    }

    /**
     * Tampilkan daftar hashtag trending (berdasarkan post_count terbanyak).
     * Route: GET /hashtags
     */
    public function index()
    {
        $hashtags = Hashtag::orderBy('post_count', 'desc')
            ->take(20)
            ->get();

        return view('hashtags.index', compact('hashtags'));
    }
}