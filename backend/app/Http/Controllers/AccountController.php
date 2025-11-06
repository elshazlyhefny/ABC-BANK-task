<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;

class AccountController extends Controller
{
    public function show(Request $request)
    {
        $account = $request->user()->account;
        return response()->json([
            'success' => true,
            'data' => [
                'account_number' => $account->account_number,
                'balance' => $account->balance,
            ],
            'error' => null,
        ]);
    }

    public function transactions(Request $request)
    {
        $account = $request->user()->account;
        $items = Transaction::with(['fromAccount:id,account_number', 'toAccount:id,account_number'])
            ->where(function ($q) use ($account) {
                $q->where('from_account_id', $account->id)
                  ->orWhere('to_account_id', $account->id);
            })
            ->orderByDesc('created_at')
            ->limit(50)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $items,
            'error' => null,
        ]);
    }
}
