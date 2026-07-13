<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use App\Services\ProfileService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Show the authenticated user's own profile page.
     */
    public function show(Request $request, ProfileService $profiles): View
    {
        return view('posts.about', $profiles->buildProfileData($request->user()));
    }

    /**
     * Update the profile (name, bio, avatar) of the authenticated user.
     */
    public function update(UpdateProfileRequest $request, ProfileService $profiles): RedirectResponse
    {
        $profiles->updateProfile($request->user(), $request->validated(), $request->file('avatar'));

        return redirect()->route('profile.show')->with('status', 'Профиль обновлён ✅');
    }
}
