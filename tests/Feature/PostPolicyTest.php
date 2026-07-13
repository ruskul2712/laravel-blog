<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostPolicyTest extends TestCase
{
    use RefreshDatabase;

    public function test_other_user_cannot_open_edit_page_for_someone_elses_post(): void
    {
        $author = User::factory()->create();
        $otherUser = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $author->id]);

        $response = $this->actingAs($otherUser)->get("/posts/{$post->id}/edit");

        $response->assertForbidden();
    }

    public function test_author_can_update_own_post(): void
    {
        $author = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $author->id]);

        $response = $this->actingAs($author)->putJson("/posts/{$post->id}", [
            'title' => 'Updated title',
            'description' => 'Updated description',
        ]);

        $response->assertOk();
        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'title' => 'Updated title',
        ]);
    }

    public function test_other_user_cannot_update_someone_elses_post(): void
    {
        $author = User::factory()->create();
        $otherUser = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $author->id]);

        $response = $this->actingAs($otherUser)->putJson("/posts/{$post->id}", [
            'title' => 'Hacked title',
            'description' => 'Hacked description',
        ]);

        $response->assertForbidden();
        $this->assertDatabaseMissing('posts', [
            'id' => $post->id,
            'title' => 'Hacked title',
        ]);
    }

    public function test_other_user_cannot_delete_someone_elses_post(): void
    {
        $author = User::factory()->create();
        $otherUser = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $author->id]);

        $response = $this->actingAs($otherUser)->delete("/posts/{$post->id}");

        $response->assertForbidden();
        $this->assertDatabaseHas('posts', ['id' => $post->id]);
    }
}
