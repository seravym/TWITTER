<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function toggle(Post $post)
    {
        $accountId = Auth::id();

        $like = Like::where('account_id', $accountId)
            ->where('post_id', $post->id)
            ->first();

        if ($like) {
            // unlike
            $like->delete();
        } else {
            // like
            Like::create([
                'account_id' => $accountId,
                'post_id' => $post->id
            ]);
        }

        return back();
    }
}