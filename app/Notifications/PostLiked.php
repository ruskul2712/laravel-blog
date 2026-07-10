<?php

namespace App\Notifications;

use App\Models\Post;
use App\Models\User;
use Illuminate\Notifications\Notification;

class PostLiked extends Notification
{
    public function __construct(public User $liker, public Post $post)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'like',
            'liker_id' => $this->liker->id,
            'liker_name' => $this->liker->name,
            'liker_avatar_url' => $this->liker->avatar_url,
            'post_id' => $this->post->id,
            'post_title' => $this->post->title,
        ];
    }
}
