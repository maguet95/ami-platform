<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\PublicProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function editPublic(Request $request): View
    {
        return view('profile.edit-public', [
            'user' => $request->user(),
        ]);
    }

    public function updatePublic(PublicProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        $data = $request->validated();

        if ($request->hasFile('avatar')) {
            $disk = config('filesystems.default') === 'r2' ? 'r2' : 'public';
            $data['avatar'] = $request->file('avatar')->store('avatars', $disk);
        }

        $data['is_profile_public'] = $request->boolean('is_profile_public');
        $data['share_manual_journal'] = $request->boolean('share_manual_journal');
        $data['share_automatic_journal'] = $request->boolean('share_automatic_journal');
        $data['automatic_journal_account_type'] = $request->input('automatic_journal_account_type');

        $user->update($data);

        return Redirect::route('profile.edit-public')->with('status', 'public-profile-updated');
    }
}
