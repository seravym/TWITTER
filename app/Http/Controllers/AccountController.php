<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    public function index()
    {
        $accounts = Account::all();
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

    public function create() {}
    public function store(Request $request) {}
    public function destroy(Account $account) {}
}
