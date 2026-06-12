<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    public function index(Request $request)
    {
    $query = \App\Models\Account::orderBy('name', 'asc');

    if ($request->has('search') && $request->search != '') {
        $search = $request->search;
        $query->where('name', 'like', "%{$search}%")
              ->orWhere('username', 'like', "%{$search}%");
    }
    $accounts = $query->get();

    return view('accounts.index', compact('accounts'));
    }

    public function show(Account $account)
    {
        $isLocked = false;
        if (Auth::id() !== $account->id) {
            $setting = \App\Models\Setting::where('account_id', $account->id)->first();
            if ($setting && $setting->isPrivateAccount) {
                if (!Auth::check() || !Auth::user()->isFollowing($account->id)) {
                    $isLocked = true;
                }
            }
        }
        return view('accounts.show', compact('account', 'isLocked'));
    }

    public function edit(Account $account)
    {
        if (Auth::id() !== $account->id) {
            abort(403, 'Anda tidak memiliki akses untuk mengubah profil ini.');
        }

        return view('accounts.edit', compact('account'));
    }

    public function update(Request $request, Account $account)
    {
        if (Auth::id() !== $account->id) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:accounts,username,' . $account->id,
            'bio' => 'nullable|string|max:500',
        ]);

        $account->update([
            'name' => $request->name,
            'username' => $request->username,
            'bio' => $request->bio,
        ]);

        return redirect('/accounts/' . $account->id)->with('success', 'Profil berhasil diperbarui!');
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'status_text' => 'required|string|max:50',
            'duration' => 'required|in:24_hours,3_days,1_week',
        ]);

        $account = \Illuminate\Support\Facades\Auth::user();
        
        $expiresAt = now();
        if ($request->duration === '24_hours') {
            $expiresAt = now()->addHours(24);
        } elseif ($request->duration === '3_days') {
            $expiresAt = now()->addDays(3);
        } elseif ($request->duration === '1_week') {
            $expiresAt = now()->addWeeks(1);
        }

        $account->update([
            'status_text' => $request->status_text,
            'status_expires_at' => $expiresAt,
        ]);

        return back()->with('success', 'Status berhasil diperbarui!');
    }

    public function create() {}
    public function store(Request $request) {}
    public function destroy(Account $account) {}
}
