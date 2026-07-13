<?php

namespace App\Repositories;

use App\Models\Story;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class StoryRepository
{
    /**
     * Active (< 24h old) stories, eager-loaded with their author.
     */
    public function activeWithUsers(): Collection
    {
        return Story::with('user')->active()->orderBy('created_at')->get();
    }

    public function createForUser(User $user, string $imagePath): Story
    {
        return $user->stories()->create(['image' => $imagePath]);
    }

    public function markViewed(Story $story, int $userId): void
    {
        $story->viewers()->syncWithoutDetaching([$userId]);
    }
}
