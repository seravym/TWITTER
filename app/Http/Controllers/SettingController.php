<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;

class SettingController extends Controller
{
    public function show()
    {
        $accountId = Auth::id();
        
        $setting = Setting::firstOrCreate(
            ['account_id' => $accountId],
            [
                'isPrivateAccount' => false,
                'allowDmFrom' => 'everyone',
                'showOnlineStatus' => true,
                'notificationMessage' => true,
                'notificationFollow' => true,
                'notificationLike' => true,
                'theme' => 'light',
                'language' => 'id',
            ]
        );

        return view('settings.show', compact('setting'));
    }

    public function update(Request $request)
    {
        $accountId = Auth::id();

        $validated = $request->validate([
            'isPrivateAccount' => 'boolean',
            'allowDmFrom' => 'string|in:everyone,following',
            'showOnlineStatus' => 'boolean',
            'notificationMessage' => 'boolean',
            'notificationFollow' => 'boolean',
            'notificationLike' => 'boolean',
            'theme' => 'string|in:light,dark,system',
            'language' => 'string|in:id,en',
        ]);

        $setting = Setting::firstOrCreate(['account_id' => $accountId]);
        $setting->update($validated);

        return redirect()->back()->with('success', 'Pengaturan berhasil disimpan!');
    }
}