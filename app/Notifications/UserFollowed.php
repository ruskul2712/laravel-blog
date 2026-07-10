<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Notifications\Notification;

class UserFollowed extends Notification
{
    public function __construct(public User $follower)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'follow',
            'follower_id' => $this->follower->id,
            'follower_name' => $this->follower->name,
            'follower_avatar_url' => $this->follower->avatar_url,
        ];
    }
}
