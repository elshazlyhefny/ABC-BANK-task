<?php

namespace Tests\Feature;

use App\Models\Account;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransactionTest extends TestCase
{
  use RefreshDatabase;

  private function authHeaders(string $token): array
  {
    return ['Authorization' => 'Bearer ' . $token];
  }

  public function test_deposit_increases_balance(): void
  {
    $reg = $this->postJson('/api/auth/register', [
      'name' => 'Alice',
      'email' => 'alice@example.com',
      'password' => 'password123',
      'password_confirmation' => 'password123',
    ])->json('data');

    $token = $reg['token'];
    $this->postJson('/api/accounts/deposit', ['amount' => 50], $this->authHeaders($token))
      ->assertCreated();

    $acc = auth()->user()?->account ?? User::where('email', 'alice@example.com')->first()->account;
    $this->assertEquals('50.00', $acc->fresh()->balance);
  }

  public function test_transfer_cannot_overdraw(): void
  {
    $reg1 = $this->postJson('/api/auth/register', [
      'name' => 'Bob',
      'email' => 'bob@example.com',
      'password' => 'password123',
      'password_confirmation' => 'password123',
    ])->json('data');
    $token1 = $reg1['token'];
    $sender = User::where('email', 'bob@example.com')->first();
    $senderAcc = $sender->account;

    $reg2 = $this->postJson('/api/auth/register', [
      'name' => 'Carol',
      'email' => 'carol@example.com',
      'password' => 'password123',
      'password_confirmation' => 'password123',
    ])->json('data');
    $recipient = User::where('email', 'carol@example.com')->first();
    $recipientAcc = $recipient->account;

    $this->postJson('/api/accounts/transfer', [
      'to_account_number' => $recipientAcc->account_number,
      'amount' => 10,
    ], $this->authHeaders($token1))->assertStatus(409);

    $this->assertEquals('0.00', $senderAcc->fresh()->balance);
    $this->assertEquals('0.00', $recipientAcc->fresh()->balance);
  }
}


