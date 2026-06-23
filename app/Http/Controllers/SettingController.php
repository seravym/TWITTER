<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\Account;
use Illuminate\Support\Facades\Auth;

class SettingController extends Controller
{
    /**
     * Tampilkan halaman pengaturan.
     * Route: GET /settings
     */
    public function show()
    {
        $accountId = Auth::id();

        $setting = Setting::firstOrCreate(
            ['account_id' => $accountId],
            [
                'isPrivateAccount'    => false,
                'allowDmFrom'         => 'everyone',
                'showOnlineStatus'    => true,
                'notificationMessage' => true,
                'notificationFollow'  => true,
                'notificationLike'    => true,
                'theme'               => 'light',
                'language'            => 'id',
                'blocked_accounts'    => [],
            ]
        );

        // Ambil detail akun yang sudah di-block untuk ditampilkan di view
        $blockedAccountIds = $setting->blocked_accounts ?? [];
        $blockedAccounts   = Account::whereIn('id', $blockedAccountIds)->get();

        return view('settings.show', compact('setting', 'blockedAccounts'));
    }

    /**
     * Simpan perubahan pengaturan umum (tema, privasi, notifikasi, dll).
     * Route: POST /settings
     */
    public function update(Request $request)
    {
        $accountId = Auth::id();

        $validated = $request->validate([
            'isPrivateAccount'    => 'nullable|boolean',
            'allowDmFrom'         => 'nullable|string|in:everyone,following',
            'showOnlineStatus'    => 'nullable|boolean',
            'notificationMessage' => 'nullable|boolean',
            'notificationFollow'  => 'nullable|boolean',
            'notificationLike'    => 'nullable|boolean',
            'theme'               => 'nullable|string|in:light,dark',
            'language'            => 'nullable|string|in:id,en',
        ]);

        // Checkbox yang tidak dicentang tidak dikirim oleh browser — set default false
        $validated['isPrivateAccount']    = $request->has('isPrivateAccount');
        $validated['showOnlineStatus']    = $request->has('showOnlineStatus');
        $validated['notificationMessage'] = $request->has('notificationMessage');
        $validated['notificationFollow']  = $request->has('notificationFollow');
        $validated['notificationLike']    = $request->has('notificationLike');

        $setting = Setting::firstOrCreate(['account_id' => $accountId]);
        $setting->update($validated);

        return redirect()->route('settings.show')->with('success', 'Pengaturan berhasil disimpan!');
    }

    /**
     * Block sebuah akun — tambahkan account_id ke JSON blocked_accounts.
     * Route: POST /settings/block/{account}
     */
    public function block(Account $account)
    {
        $myId = Auth::id();

        // Tidak bisa block diri sendiri
        if ($account->id === $myId) {
            return back()->withErrors(['error' => 'Anda tidak bisa memblokir diri sendiri.']);
        }

        $setting = Setting::firstOrCreate(
            ['account_id' => $myId],
            ['blocked_accounts' => []]
        );

        $blocked = $setting->blocked_accounts ?? [];

        if (!in_array($account->id, $blocked)) {
            $blocked[] = $account->id;
            $setting->update(['blocked_accounts' => $blocked]);
        }

        return back()->with('success', "Akun @{$account->username} berhasil diblokir.");
    }

    /**
     * Unblock sebuah akun — hapus account_id dari JSON blocked_accounts.
     * Route: DELETE /settings/block/{account}
     */
    public function unblock(Account $account)
    {
        $myId    = Auth::id();
        $setting = Setting::where('account_id', $myId)->first();

        if ($setting) {
            $blocked = array_filter(
                $setting->blocked_accounts ?? [],
                fn($id) => $id !== $account->id
            );
            $setting->update(['blocked_accounts' => array_values($blocked)]);
        }

        return back()->with('success', "Blokir untuk @{$account->username} dicabut.");
    }

    /**
     * Block akun berdasarkan username (dari form pencarian di settings).
     * Route: POST /settings/block-by-username
     */
    public function blockByUsername(Request $request)
    {
        $request->validate(['username' => 'required|string']);

        $account = Account::where('username', $request->username)->first();

        if (!$account) {
            return back()->withErrors(['error' => "Akun dengan username @{$request->username} tidak ditemukan."]);
        }

        return $this->block($account);
    }
}