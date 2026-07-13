<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Models\Comment;
use App\Models\Post;
use App\Services\CommentService;
use Illuminate\Http\JsonResponse;

class CommentController extends Controller
{
    /**
     * Store a new comment on a post.
     */
    public function store(StoreCommentRequest $request, Post $post, CommentService $commentService): JsonResponse
    {
        $comment = $commentService->addComment($post, $request->user()->id, $request->validated('body'));

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
    public function update(UpdateCommentRequest $request, Comment $comment, CommentService $commentService): JsonResponse
    {
        $this->authorize('update', $comment);

        $comment = $commentService->updateComment($comment, $request->validated('body'));

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
    public function destroy(Comment $comment, CommentService $commentService): JsonResponse
    {
        $this->authorize('delete', $comment);

        $commentService->deleteComment($comment);

        return response()->json(['deleted' => true]);
    }
}
