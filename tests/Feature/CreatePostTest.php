<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreatePostTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_create_a_post(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/posts', [
            'title' => 'My first post',
            'description' => 'Post body text',
        ]);

        $response->assertRedirect(route('post.feed'));

        $this->assertDatabaseHas('posts', [
            'title' => 'My first post',
            'user_id' => $user->id,
        ]);
    }

    public function test_guest_cannot_create_a_post(): void
    {
        $response = $this->post('/posts', [
            'title' => 'My first post',
            'description' => 'Post body text',
        ]);

        $response->assertRedirect('/login');

        $this->assertDatabaseMissing('posts', [
            'title' => 'My first post',
        ]);
    }
}
