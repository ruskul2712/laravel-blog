<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use App\Repositories\SearchRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SearchController extends Controller
{
    /**
     * Full search results page.
     */
    public function index(Request $request, SearchRepository $search): View
    {
        $query = trim((string) $request->query('q', ''));

        $users = collect();
        $posts = collect();
        $comments = collect();

        if ($query !== '') {
            $users = $search->users($query, 20);
            $posts = $search->posts($query, 20);
            $comments = $search->comments($query, 20);
        }

        return view('posts.search', compact('query', 'users', 'posts', 'comments'));
    }

    /**
     * Lightweight JSON results for the live header dropdown.
     */
    public function suggest(Request $request, SearchRepository $search): JsonResponse
    {
        $query = trim((string) $request->query('q', ''));

        if ($query === '') {
            return response()->json(['users' => [], 'posts' => [], 'comments' => []]);
        }

        $users = $search->users($query, 5)
            ->map(fn (User $user) => [
                'id' => $user->id,
                'name' => $user->name,
                'initial' => mb_strtoupper(mb_substr($user->name, 0, 1)),
                'avatarUrl' => $user->avatar_url,
                'href' => route('users.show', $user),
            ]);

        $posts = $search->posts($query, 5)
            ->map(fn (Post $post) => [
                'id' => $post->id,
                'title' => $post->title,
                'snippet' => Str::limit($post->description, 60),
                'href' => route('post.feed').'#post-'.$post->id,
            ]);

        $comments = $search->comments($query, 5)
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
