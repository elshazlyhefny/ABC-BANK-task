<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Models\Account;
use App\Services\AccountNumberGenerator;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(RegisterRequest $request, AccountNumberGenerator $generator)
    {
        $data = $request->validated();
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
        ]);

        Account::create([
            'user_id' => $user->id,
            'account_number' => $generator->generateUnique(12),
            'balance' => '0.00',
        ]);

        $token = $user->createToken('api')->plainTextToken;

        return response()->json([
            'success' => true,
            'data' => ['token' => $token],
            'error' => null,
        ]);
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();
        if (!Auth::attempt($credentials)) {
            return response()->json([
                'success' => false,
                'data' => null,
                'error' => 'Invalid credentials',
            ], 401);
        }

        $user = User::where('email', $credentials['email'])->firstOrFail();
        $token = $user->createToken('api')->plainTextToken;

        return response()->json([
            'success' => true,
            'data' => ['token' => $token],
            'error' => null,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['success' => true, 'data' => null, 'error' => null]);
    }

    public function me(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => $request->user()->only(['id', 'name', 'email']),
            'error' => null,
        ]);
    }
}
