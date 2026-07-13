<?php

namespace App\Repositories;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class SearchRepository
{
    public function users(string $query, int $limit): Collection
    {
        return User::where('name', 'ilike', "%{$query}%")
            ->orWhere('email', 'ilike', "%{$query}%")
            ->limit($limit)
            ->get();
    }

    public function posts(string $query, int $limit): Collection
    {
        return Post::with('user')
            ->where('title', 'ilike', "%{$query}%")
            ->orWhere('description', 'ilike', "%{$query}%")
            ->latest()
            ->limit($limit)
            ->get();
    }

    public function comments(string $query, int $limit): Collection
    {
        return Comment::with(['user', 'post'])
            ->where('body', 'ilike', "%{$query}%")
            ->latest()
            ->limit($limit)
            ->get();
    }
}
