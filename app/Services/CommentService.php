<?php

namespace App\Services;

use App\Models\Comment;
use App\Models\Post;
use App\Repositories\CommentRepository;

class CommentService
{
    public function __construct(private CommentRepository $comments) {}

    public function addComment(Post $post, int $userId, string $body): Comment
    {
        return $this->comments->createForPost($post, $userId, $body);
    }

    public function updateComment(Comment $comment, string $body): Comment
    {
        return $this->comments->update($comment, ['body' => $body]);
    }

    public function deleteComment(Comment $comment): void
    {
        $this->comments->delete($comment);
    }
}
