<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $author = User::first() ?? User::factory()->create();

        Post::factory()->count(10)->create(['user_id' => $author->id]);
    }
}
