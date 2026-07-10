<?php

namespace App\Http\Controllers;

use App\Models\Story;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class StoryController extends Controller
{
    /**
     * Store a newly created story for the current user.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:5120'],
        ], [
            'image.required' => 'Выберите фото для истории.',
            'image.image' => 'Файл должен быть изображением.',
            'image.mimes' => 'Поддерживаются форматы: JPG, PNG, WEBP, GIF.',
            'image.max' => 'Максимальный размер файла — 5 МБ.',
        ]);

        $request->user()->stories()->create([
            'image' => $request->file('image')->store('stories', 'public'),
        ]);

        return redirect()->route('post.feed')->with('status', 'История опубликована 🎉');
    }

    /**
     * Record that the current user has viewed the given story.
     */
    public function markViewed(Request $request, Story $story): JsonResponse
    {
        $story->viewers()->syncWithoutDetaching([$request->user()->id]);

        return response()->json(['viewed' => true]);
    }
}
