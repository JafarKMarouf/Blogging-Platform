<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use function Pest\Laravel\postJson;

uses(TestCase::class, RefreshDatabase::class);

describe('Login Flow', function () {
    test('a user can be login with valid credentials', function () {
        User::factory()->create([
            'email' => 'user@example.com',
            'password' => 'password123',
        ]);

        $response = postJson('/api/auth/login', [
            'email' => 'user@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'status',
            'message',
            'data' => ['accessToken', 'refreshToken']
        ]);
    });

    test('a user cannot login with incorrect email credential', function () {
        User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('correct-password'),
        ]);

        $response = postJson('/api/auth/login', [
            'email' => 'wrong-email@example.com',
            'password' => 'correct-password'
        ]);

        $response->assertStatus(401)
            ->assertJson(['message' => 'Invalid credentials']);
    });
    test('a user cannot login with incorrect password credential', function () {
        User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('correct-password'),
        ]);

        $response = postJson('/api/auth/login', [
            'email' => 'test@example.com',
            'password' => 'wrong-password'
        ]);

        $response->assertStatus(401)
            ->assertJson(['message' => 'Invalid credentials']);
    });

    test('user cannot login with invalid data', function ($payload, $errorFragment) {
        $response = postJson('/api/auth/login', $payload);
        $response->assertStatus(422)
            ->assertJsonFragment([$errorFragment]);
    })->with([
        'missing email' => [
            ['password' => 'pass123'],
            'The email field is required.'
        ],
        'missing password' => [
            ['email' => 'a@b.com'],
            'The password field is required.'
        ],
        'invalid email format' => [
            ['email' => 'not-an-email', 'password' => 'pass123'],
            'The email field must be a valid email address.'
        ],
        'short password' => [
            ['email' => 'a@b.com', 'password' => '123'],
            'The password field must be at least 8 characters.'
        ],
    ]);
});
