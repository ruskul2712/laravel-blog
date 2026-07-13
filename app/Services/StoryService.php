<?php

namespace App\Services;

use App\Models\Story;
use App\Models\User;
use App\Repositories\StoryRepository;
use Illuminate\Http\UploadedFile;

class StoryService
{
    public function __construct(private StoryRepository $stories) {}

    public function createStory(User $user, UploadedFile $image): Story
    {
        $path = $image->store('stories', 'public');

        return $this->stories->createForUser($user, $path);
    }

    public function markViewed(Story $story, int $userId): void
    {
        $this->stories->markViewed($story, $userId);
    }
}
