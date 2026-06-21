<?php

namespace App\Http\Controllers;

use App\Models\Community;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommunityController extends Controller
{
    public function index()
    {
        $communities = Community::with('creator')->get();
        return view('communities.index', compact('communities'));
    }

    public function create()
    {
        return view('communities.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $community = Community::create([
            'name' => $request->name,
            'description' => $request->description,
            'creator_id' => Auth::id(),
        ]);

        $community->members()->attach(Auth::id(), ['role' => 'admin']);

        return redirect('/communities')->with('success', 'Komunitas berhasil dibuat!');
    }

    public function show(Community $community)
    {
        // Load relasi members dan creator
        $community->load(['creator', 'members']);
        return view('communities.show', compact('community'));
    }

    public function join(Community $community)
    {
        if (!$community->members()->where('account_id', Auth::id())->exists()) {
            $community->members()->attach(Auth::id(), ['role' => 'member']);
        }

        return back()->with('success', 'Berhasil bergabung dengan komunitas!');
    }

    public function leave(Community $community)
    {
        if ($community->creator_id == Auth::id()) {
            return back()->withErrors(['error' => 'Pembuat komunitas tidak bisa keluar!']);
        }

        $community->members()->detach(Auth::id());
        return back()->with('success', 'Berhasil keluar dari komunitas.');
    }
}