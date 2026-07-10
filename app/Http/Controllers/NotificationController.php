<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Latest notifications for the dropdown, plus the unread count.
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $notifications = $user->notifications()
            ->latest()
            ->limit(20)
            ->get()
            ->map(fn ($n) => [
                'id' => $n->id,
                'data' => $n->data,
                'read' => ! is_null($n->read_at),
                'created_at' => $n->created_at->diffForHumans(),
            ]);

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $user->unreadNotifications()->count(),
        ]);
    }

    /**
     * Mark a single notification as read.
     */
    public function markRead(Request $request, string $notification): JsonResponse
    {
        $user = $request->user();
        $model = $user->notifications()->where('id', $notification)->firstOrFail();
        $model->markAsRead();

        return response()->json([
            'unread_count' => $user->unreadNotifications()->count(),
        ]);
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllRead(Request $request): JsonResponse
    {
        $request->user()->unreadNotifications()->update(['read_at' => now()]);

        return response()->json(['unread_count' => 0]);
    }
}
