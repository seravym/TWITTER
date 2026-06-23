<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Repost;
use Illuminate\Support\Facades\Auth;

class RepostController extends Controller
{
    public function toggle(Post $post)
    {
        $accountId = Auth::id();

        $repost = Repost::where('account_id', $accountId)
            ->where('post_id', $post->id)
            ->first();

        if ($repost) {
            $repost->delete();
        } else {
            Repost::create([
                'account_id' => $accountId,
                'post_id' => $post->id
            ]);
        }

        return back();
    }
}
