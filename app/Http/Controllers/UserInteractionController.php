<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\UserFollowed;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserInteractionController extends Controller
{
    /**
     * Toggle a follow relationship from the current user onto the given user.
     */
    public function toggleFollow(Request $request, User $user): JsonResponse
    {
        $currentUser = $request->user();

        if ($currentUser->is($user)) {
            return response()->json(['message' => 'Нельзя подписаться на самого себя.'], 422);
        }

        if ($currentUser->isFollowing($user)) {
            $currentUser->following()->detach($user->id);
            $active = false;
        } else {
            $currentUser->following()->attach($user->id);
            $user->notify(new UserFollowed($currentUser));
            $active = true;
        }

        return response()->json([
            'active' => $active,
            'followers_count' => $user->followers()->count(),
            'following_count' => $currentUser->following()->count(),
        ]);
    }
}
