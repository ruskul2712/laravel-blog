<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class UserRepository
{
    /**
     * Users to suggest following: not the current user, not already followed.
     */
    public function suggestedFor(User $currentUser, array $excludeIds, int $limit = 3): Collection
    {
        return User::where('id', '!=', $currentUser->id)
            ->whereNotIn('id', $excludeIds)
            ->latest()
            ->limit($limit)
            ->get();
    }

    public function update(User $user, array $data): User
    {
        $user->update($data);

        return $user;
    }
}
