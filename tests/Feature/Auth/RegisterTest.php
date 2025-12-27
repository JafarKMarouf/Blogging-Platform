<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use function Pest\Laravel\postJson;

uses(TestCase::class, RefreshDatabase::class);

describe('Register Flow', function () {
    test('a user can be register with valid credentials', function () {
        $payload = [
            'name' => 'User',
            'email' => 'user@example.com',
            'password' => 'password123',
        ];

        $response = $this->postJson('/api/auth/register', $payload);
        $response->assertStatus(201);

        $this->assertDatabaseHas('users', ['email' => $payload['email']]);

        $response->assertJsonStructure([
            'status',
            'message',
            'data' => ['accessToken', 'refreshToken']
        ]);
        $user = User::where('email', $payload['email'])->first();
        expect($user)->not->toBeNull()
            ->and($user->password)->not->toBe($payload['password'])
            ->and(Hash::check($payload['password'], $user->password))->toBeTrue();
    });
    test('a user cannot register with existing email', function () {
        User::factory()->create([
            'name' => 'User',
            'email' => 'user@example.com',
        ]);
        $payload = [
            'name' => 'User',
            'email' => 'user@example.com',
            'password' => 'password123',
        ];

        $response = $this->postJson('/api/auth/register', $payload);

        $response->assertStatus(422)
            ->assertJsonPath('message', 'The email has already been taken.');
    });

    test('user cannot register with invalid data', function ($payload, $errorFragment) {
        $response = postJson('/api/auth/register', $payload);
        $response->assertStatus(422)
            ->assertJsonFragment([$errorFragment]);
    })->with([
        'missing name' => [
            ['email' => 'a@b.com', 'password' => 'pass123'],
            'The name field is required.'
        ],
        'missing email' => [
            ['name' => 'User', 'password' => 'pass123'],
            'The email field is required.'
        ],
        'missing password' => [
            ['name' => 'User', 'email' => 'a@b.com'],
            'The password field is required.'
        ],
        'invalid email format' => [
            ['name' => 'User', 'email' => 'not-an-email', 'password' => 'pass123'],
            'The email field must be a valid email address.'
        ],
        'short password' => [
            ['name' => 'User', 'email' => 'a@b.com', 'password' => '123'],
            'The password field must be at least 8 characters.'
        ],

    ]);
});

