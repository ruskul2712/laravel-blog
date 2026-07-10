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

        $followersCount = $user->followers()->count();
        $followingCount = $user->following()->count();
        $isFollowing = $request->user()?->isFollowing($user) ?? false;

        return view('posts.public-profile', compact('user', 'posts', 'followersCount', 'followingCount', 'isFollowing'));
    }

    /**
     * List the users who follow the given user.
     */
    public function followers(Request $request, User $user)
    {
        $users = $user->followers()->orderByPivot('created_at', 'desc')->get();
        $followingIds = $request->user()?->following()->pluck('users.id')->all() ?? [];

        return view('posts.connections', [
            'user' => $user,
            'users' => $users,
            'followingIds' => $followingIds,
            'title' => 'Подписчики',
        ]);
    }

    /**
     * List the users the given user follows.
     */
    public function following(Request $request, User $user)
    {
        $users = $user->following()->orderByPivot('created_at', 'desc')->get();
        $followingIds = $request->user()?->following()->pluck('users.id')->all() ?? [];

        return view('posts.connections', [
            'user' => $user,
            'users' => $users,
            'followingIds' => $followingIds,
            'title' => 'Подписки',
        ]);
    }
}
