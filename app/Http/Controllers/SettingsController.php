<?php

namespace App\Http\Controllers;

use App\Http\Requests\SettingsAppearanceUpdateRequest;
use App\Http\Requests\SettingsAvatarUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SettingsController extends Controller
{
    /**
     * Display the settings page.
     */
    public function edit(Request $request): View
    {
        return view('settings_profile', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update appearance settings.
     */
    public function updateAppearance(SettingsAppearanceUpdateRequest $request): RedirectResponse
    {
        $request->user()->update($request->validated());

        return redirect()
            ->route('settings.profile')
            ->with('settings_status', 'appearance-updated');
    }

    /**
     * Upload or replace the user's avatar.
     */
    public function updateAvatar(SettingsAvatarUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $previousAvatar = $user->avatar_path;
        $newAvatarPath = $request->file('avatar')->store('avatars', 'public');

        $user->update([
            'avatar_path' => $newAvatarPath,
        ]);

        if ($previousAvatar) {
            Storage::disk('public')->delete($previousAvatar);
        }

        return redirect()
            ->route('settings.profile')
            ->with('settings_status', 'avatar-updated');
    }

    /**
     * Remove the user's avatar.
     */
    public function destroyAvatar(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->avatar_path) {
            Storage::disk('public')->delete($user->avatar_path);
        }

        $user->update([
            'avatar_path' => null,
        ]);

        return redirect()
            ->route('settings.profile')
            ->with('settings_status', 'avatar-removed');
    }
}
