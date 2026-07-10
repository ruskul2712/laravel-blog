<?php

namespace App\Http\Controllers;

use App\Models\Bookmark;
use App\Models\Like;
use App\Models\Post;
use App\Models\Repost;
use App\Notifications\PostLiked;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PostInteractionController extends Controller
{
    /**
     * Toggle a like on the post for the current user.
     */
    public function toggleLike(Request $request, Post $post): JsonResponse
    {
        $userId = $request->user()->id;
        $like = Like::where('post_id', $post->id)->where('user_id', $userId)->first();

        if ($like) {
            $like->delete();
            $active = false;
        } else {
            Like::create(['post_id' => $post->id, 'user_id' => $userId]);
            $active = true;

            if ($post->user && $post->user->id !== $userId) {
                $post->user->notify(new PostLiked($request->user(), $post));
            }
        }

        return response()->json([
            'active' => $active,
            'count' => $post->likes()->count(),
        ]);
    }

    /**
     * Toggle a bookmark (save) on the post for the current user.
     */
    public function toggleBookmark(Request $request, Post $post): JsonResponse
    {
        $userId = $request->user()->id;
        $bookmark = Bookmark::where('post_id', $post->id)->where('user_id', $userId)->first();

        if ($bookmark) {
            $bookmark->delete();
            $active = false;
        } else {
            Bookmark::create(['post_id' => $post->id, 'user_id' => $userId]);
            $active = true;
        }

        return response()->json([
            'active' => $active,
            'count' => $post->bookmarks()->count(),
        ]);
    }

    /**
     * Toggle a repost (share) on the post for the current user.
     */
    public function toggleRepost(Request $request, Post $post): JsonResponse
    {
        $userId = $request->user()->id;
        $repost = Repost::where('post_id', $post->id)->where('user_id', $userId)->first();

        if ($repost) {
            $repost->delete();
            $active = false;
        } else {
            Repost::create(['post_id' => $post->id, 'user_id' => $userId]);
            $active = true;
        }

        return response()->json([
            'active' => $active,
            'count' => $post->reposts()->count(),
        ]);
    }
}
