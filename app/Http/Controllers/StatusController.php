<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Post;
use App\Models\Status;
use Illuminate\Http\Request;

class StatusController extends Controller
{
    public function index()
    {
        $statuses = Status::with(['account', 'post'])->get();

        return view('statuses.index', compact('statuses'));
    }

    public function create()
    {
        $posts = Post::all();

        return view('statuses.create', compact('posts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'post_id' => 'required|exists:posts,id',
            'content' => 'required|string',
        ]);

        Status::create([
            'account_id' => auth()->id(),
            'post_id' => $request->post_id,
            'content' => $request->content,
        ]);

        return redirect()->route('statuses.index');
    }

    public function show(Status $status)
    {
        $status->load(['account', 'post']);

        return view('statuses.show', compact('status'));
    }

    public function edit(Status $status)
    {
        $posts = Post::all();

        return view('statuses.edit', compact('status', 'posts'));
    }

    public function update(Request $request, Status $status)
    {
        $request->validate([
            'post_id' => 'required|exists:posts,id',
            'content' => 'required|string',
        ]);

        $status->update([
            'post_id' => $request->post_id,
            'content' => $request->content,
        ]);

        return redirect()->route('statuses.index');
    }

    public function destroy(Status $status)
    {
        $status->delete();

        return redirect()->route('statuses.index');
    }
}
