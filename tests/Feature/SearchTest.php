<?php

namespace Tests\Feature;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_search_finds_matching_post(): void
    {
        Post::factory()->create(['title' => 'Laravel testing tips']);
        Post::factory()->create(['title' => 'Something unrelated']);

        $response = $this->get('/search?q=Laravel');

        $response->assertOk();
        $response->assertSee('Laravel testing tips');
        $response->assertDontSee('Something unrelated');
    }
}
