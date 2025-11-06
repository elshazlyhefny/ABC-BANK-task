<?php

namespace App\Services;

use App\Models\Account;
use App\Models\Transaction;
use DomainException;
use Illuminate\Support\Facades\DB;

class TransactionService
{
    public function deposit(Account $toAccount, string $amount): Transaction
    {
        return DB::transaction(function () use ($toAccount, $amount) {
            $locked = Account::whereKey($toAccount->id)->lockForUpdate()->first();
            $locked->balance = bcadd($locked->balance, $amount, 2);
            $locked->save();

            return Transaction::create([
                'from_account_id' => null,
                'to_account_id' => $locked->id,
                'amount' => $amount,
                'type' => 'deposit',
            ]);
        });
    }

    public function transfer(Account $fromAccount, Account $toAccount, string $amount): Transaction
    {
        return DB::transaction(function () use ($fromAccount, $toAccount, $amount) {
            // lock in deterministic order to minimize deadlocks
            [$a, $b] = $fromAccount->id < $toAccount->id ? [$fromAccount, $toAccount] : [$toAccount, $fromAccount];
            $a = Account::whereKey($a->id)->lockForUpdate()->first();
            $b = Account::whereKey($b->id)->lockForUpdate()->first();
            $lockedFrom = $a->id === $fromAccount->id ? $a : $b;
            $lockedTo   = $a->id === $toAccount->id ? $a : $b;

            if (bccomp($lockedFrom->balance, $amount, 2) < 0) {
                throw new DomainException('Insufficient balance');
            }

            $lockedFrom->balance = bcsub($lockedFrom->balance, $amount, 2);
            $lockedTo->balance   = bcadd($lockedTo->balance, $amount, 2);
            $lockedFrom->save();
            $lockedTo->save();

            return Transaction::create([
                'from_account_id' => $lockedFrom->id,
                'to_account_id' => $lockedTo->id,
                'amount' => $amount,
                'type' => 'transfer',
            ]);
        });
    }
}


