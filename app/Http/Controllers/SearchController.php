<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SearchController extends Controller
{
    /**
     * Full search results page.
     */
    public function index(Request $request): View
    {
        $query = trim((string) $request->query('q', ''));

        $users = collect();
        $posts = collect();
        $comments = collect();

        if ($query !== '') {
            $users = User::where('name', 'ilike', "%{$query}%")
                ->orWhere('email', 'ilike', "%{$query}%")
                ->limit(20)
                ->get();

            $posts = Post::with('user')
                ->where('title', 'ilike', "%{$query}%")
                ->orWhere('description', 'ilike', "%{$query}%")
                ->latest()
                ->limit(20)
                ->get();

            $comments = Comment::with(['user', 'post'])
                ->where('body', 'ilike', "%{$query}%")
                ->latest()
                ->limit(20)
                ->get();
        }

        return view('posts.search', compact('query', 'users', 'posts', 'comments'));
    }

    /**
     * Lightweight JSON results for the live header dropdown.
     */
    public function suggest(Request $request): JsonResponse
    {
        $query = trim((string) $request->query('q', ''));

        if ($query === '') {
            return response()->json(['users' => [], 'posts' => [], 'comments' => []]);
        }

        $users = User::where('name', 'ilike', "%{$query}%")
            ->limit(5)
            ->get()
            ->map(fn (User $user) => [
                'id' => $user->id,
                'name' => $user->name,
                'initial' => mb_strtoupper(mb_substr($user->name, 0, 1)),
                'avatarUrl' => $user->avatar_url,
                'href' => route('users.show', $user),
            ]);

        $posts = Post::where('title', 'ilike', "%{$query}%")
            ->orWhere('description', 'ilike', "%{$query}%")
            ->latest()
            ->limit(5)
            ->get()
            ->map(fn (Post $post) => [
                'id' => $post->id,
                'title' => $post->title,
                'snippet' => Str::limit($post->description, 60),
                'href' => route('post.feed').'#post-'.$post->id,
            ]);

        $comments = Comment::with(['user'])
            ->where('body', 'ilike', "%{$query}%")
            ->latest()
            ->limit(5)
            ->get()
            ->map(fn (Comment $comment) => [
                'id' => $comment->id,
                'body' => Str::limit($comment->body, 60),
                'username' => $comment->user->name ?? 'Пользователь',
                'href' => route('post.feed').'#post-'.$comment->post_id,
            ]);

        return response()->json([
            'users' => $users,
            'posts' => $posts,
            'comments' => $comments,
        ]);
    }
}
