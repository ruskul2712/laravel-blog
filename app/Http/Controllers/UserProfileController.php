<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Repositories\FollowRepository;
use App\Repositories\PostRepository;
use Illuminate\Http\Request;

class UserProfileController extends Controller
{
    public function show(Request $request, User $user, PostRepository $posts, FollowRepository $follows)
    {
        if ($request->user()?->is($user)) {
            return redirect()->route('profile.show');
        }

        return view('posts.public-profile', [
            'user' => $user,
            'posts' => $posts->forUser($user),
            'followersCount' => $follows->followersCount($user),
            'followingCount' => $follows->followingCount($user),
            'isFollowing' => $request->user()?->isFollowing($user) ?? false,
        ]);
    }

    /**
     * List the users who follow the given user.
     */
    public function followers(Request $request, User $user, FollowRepository $follows)
    {
        return view('posts.connections', [
            'user' => $user,
            'users' => $follows->followersOf($user),
            'followingIds' => $request->user()?->following()->pluck('users.id')->all() ?? [],
            'title' => 'Подписчики',
        ]);
    }

    /**
     * List the users the given user follows.
     */
    public function following(Request $request, User $user, FollowRepository $follows)
    {
        return view('posts.connections', [
            'user' => $user,
            'users' => $follows->followingOf($user),
            'followingIds' => $request->user()?->following()->pluck('users.id')->all() ?? [],
            'title' => 'Подписки',
        ]);
    }
}
