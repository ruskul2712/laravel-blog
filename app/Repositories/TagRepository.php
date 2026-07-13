<?php

namespace App\Repositories;

use App\Models\Tag;

class TagRepository
{
    /**
     * @param  string[]  $names
     * @return int[] tag ids, creating any tags that don't exist yet
     */
    public function findOrCreateIdsByNames(array $names): array
    {
        return collect($names)
            ->map(fn (string $name) => Tag::firstOrCreate(['name' => $name])->id)
            ->all();
    }
}
