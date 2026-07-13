<?php

namespace App\Repositories;

use App\Models\Comment;
use App\Models\Post;

class CommentRepository
{
    public function createForPost(Post $post, int $userId, string $body): Comment
    {
        $comment = $post->comments()->create([
            'user_id' => $userId,
            'body' => $body,
        ]);

        $comment->load('user');

        return $comment;
    }

    public function update(Comment $comment, array $data): Comment
    {
        $comment->update($data);

        return $comment;
    }

    public function delete(Comment $comment): void
    {
        $comment->delete();
    }
}
