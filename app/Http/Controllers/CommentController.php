<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CommentController extends Controller
{
    /**
     * Store a new comment on a post.
     */
    public function store(Request $request, Post $post): JsonResponse
    {
        $validated = $request->validate([
            'body' => 'required|string|max:2000',
        ]);

        $comment = $post->comments()->create([
            'user_id' => $request->user()->id,
            'body' => $validated['body'],
        ]);

        $comment->load('user');

        return response()->json([
            'comment' => [
                'id' => $comment->id,
                'body' => $comment->body,
                'username' => $comment->user->name,
                'initial' => mb_strtoupper(mb_substr($comment->user->name, 0, 1)),
                'created_at' => $comment->created_at->diffForHumans(),
            ],
        ]);
    }

    /**
     * Update an existing comment.
     */
    public function update(Request $request, Comment $comment): JsonResponse
    {
        $validated = $request->validate([
            'body' => 'required|string|max:2000',
        ]);

        $comment->update($validated);

        return response()->json([
            'comment' => [
                'id' => $comment->id,
                'body' => $comment->body,
            ],
        ]);
    }

    /**
     * Delete a comment.
     */
    public function destroy(Comment $comment): JsonResponse
    {
        $comment->delete();

        return response()->json(['deleted' => true]);
    }
}
