<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserProfileController extends Controller
{
    public function show(Request $request, User $user)
    {
        if ($request->user()?->is($user)) {
            return redirect()->route('profile.show');
        }

        $posts = $user->posts()
            ->withCount(['likes', 'comments'])
            ->latest()
            ->get();

        return view('posts.public-profile', compact('user', 'posts'));
    }
}
