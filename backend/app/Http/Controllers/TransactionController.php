<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\DepositRequest;
use App\Http\Requests\TransferRequest;
use App\Models\Account;
use App\Services\TransactionService;
use DomainException;

class TransactionController extends Controller
{
    public function deposit(DepositRequest $request, TransactionService $service)
    {
        $amount = number_format((float) $request->validated()['amount'], 2, '.', '');
        $account = $request->user()->account;
        $tx = $service->deposit($account, $amount);

        return response()->json([
            'success' => true,
            'data' => $tx,
            'error' => null,
        ], 201);
    }

    public function transfer(TransferRequest $request, TransactionService $service)
    {
        $data = $request->validated();
        $amount = number_format((float) $data['amount'], 2, '.', '');
        $from = $request->user()->account;
        $to = Account::where('account_number', $data['to_account_number'])->firstOrFail();
        if ($from->id === $to->id) {
            return response()->json([
                'success' => false,
                'data' => null,
                'error' => 'Cannot transfer to the same account',
            ], 422);
        }
        $tx = $service->transfer($from, $to, $amount);

        return response()->json([
            'success' => true,
            'data' => $tx,
            'error' => null,
        ], 201);
    }
}
