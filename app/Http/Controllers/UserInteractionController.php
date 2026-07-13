<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\FollowService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserInteractionController extends Controller
{
    /**
     * Toggle a follow relationship from the current user onto the given user.
     */
    public function toggleFollow(Request $request, User $user, FollowService $follows): JsonResponse
    {
        $result = $follows->toggleFollow($request->user(), $user);

        if ($result === null) {
            return response()->json(['message' => 'Нельзя подписаться на самого себя.'], 422);
        }

        return response()->json($result);
    }
}
