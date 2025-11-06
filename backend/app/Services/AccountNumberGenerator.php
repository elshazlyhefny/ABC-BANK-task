<?php

namespace App\Services;

use App\Models\Account;
use Illuminate\Support\Str;

class AccountNumberGenerator
{
    public function generateUnique(int $length = 12): string
    {
        do {
            $number = $this->generateNumeric($length);
        } while (Account::where('account_number', $number)->exists());

        return $number;
    }

    private function generateNumeric(int $length): string
    {
        $digits = '';
        while (strlen($digits) < $length) {
            $digits .= (string) random_int(0, 9);
        }
        // ensure first digit is not zero
        if ($digits[0] === '0') {
            $digits[0] = (string) random_int(1, 9);
        }
        return $digits;
    }
}
