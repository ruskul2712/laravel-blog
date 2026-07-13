<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Services\PostInteractionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PostInteractionController extends Controller
{
    /**
     * Toggle a like on the post for the current user.
     */
    public function toggleLike(Request $request, Post $post, PostInteractionService $interactions): JsonResponse
    {
        return response()->json($interactions->toggleLike($post, $request->user()));
    }

    /**
     * Toggle a bookmark (save) on the post for the current user.
     */
    public function toggleBookmark(Request $request, Post $post, PostInteractionService $interactions): JsonResponse
    {
        return response()->json($interactions->toggleBookmark($post, $request->user()));
    }

    /**
     * Toggle a repost (share) on the post for the current user.
     */
    public function toggleRepost(Request $request, Post $post, PostInteractionService $interactions): JsonResponse
    {
        return response()->json($interactions->toggleRepost($post, $request->user()));
    }
}
