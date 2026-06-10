<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Post;

class LikeController extends Controller
{
    public function toggle(Post $post)
    {
        $accountId = auth()->id();

        $like = Like::where('account_id', $accountId)
                    ->where('post_id', $post->id)
                    ->first();

        if ($like) {
            $like->delete();
        } else {
            Like::create([
                'account_id' => $accountId,
                'post_id' => $post->id
            ]);
        }

        return back();
    }
}