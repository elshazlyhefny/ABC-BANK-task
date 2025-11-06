<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
  use RefreshDatabase;

  public function test_register_returns_token(): void
  {
    $resp = $this->postJson('/api/auth/register', [
      'name' => 'Jane',
      'email' => 'jane@example.com',
      'password' => 'password123',
      'password_confirmation' => 'password123',
    ]);

    $resp->assertStatus(200)->assertJsonStructure(['data' => ['token']]);
  }

  public function test_login_returns_token(): void
  {
    $this->postJson('/api/auth/register', [
      'name' => 'John',
      'email' => 'john@example.com',
      'password' => 'password123',
      'password_confirmation' => 'password123',
    ])->assertOk();

    $resp = $this->postJson('/api/auth/login', [
      'email' => 'john@example.com',
      'password' => 'password123',
    ]);

    $resp->assertOk()->assertJsonStructure(['data' => ['token']]);
  }
}
