<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->post('/register', [
            'name' => 'Rustam',
            'email' => 'rustam@test.com',
            'password' => '123456',
            'password_confirmation' => '123456',
        ]);
        $this->assertDatabaseHas('users', [
            'email' => 'rustam@test.com',
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticated();
    }
}
