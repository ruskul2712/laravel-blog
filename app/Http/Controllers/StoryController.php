<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStoryRequest;
use App\Models\Story;
use App\Services\StoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class StoryController extends Controller
{
    /**
     * Store a newly created story for the current user.
     */
    public function store(StoreStoryRequest $request, StoryService $stories): RedirectResponse
    {
        $stories->createStory($request->user(), $request->file('image'));

        return redirect()->route('post.feed')->with('status', 'История опубликована 🎉');
    }

    /**
     * Record that the current user has viewed the given story.
     */
    public function markViewed(Request $request, Story $story, StoryService $stories): JsonResponse
    {
        $stories->markViewed($story, $request->user()->id);

        return response()->json(['viewed' => true]);
    }
}
