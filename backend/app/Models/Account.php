<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Account extends Model
{
  use HasFactory;

  protected $fillable = [
    'user_id',
    'account_number',
    'balance',
  ];

  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class);
  }

  public function transactionsFrom(): HasMany
  {
    return $this->hasMany(Transaction::class, 'from_account_id');
  }

  public function transactionsTo(): HasMany
  {
    return $this->hasMany(Transaction::class, 'to_account_id');
  }
}


