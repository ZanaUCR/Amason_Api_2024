<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    private function validateCardNumber($cardNumber)
    {
        $cardNumber = preg_replace('/\D/', '', $cardNumber);

        if (!ctype_digit($cardNumber) || strlen($cardNumber) < 13 || strlen($cardNumber) > 19) {
            return false;
        }

        $sum = 0;
        $isSecond = false;

        for ($i = strlen($cardNumber) - 1; $i >= 0; $i--) {
            $digit = $cardNumber[$i];

            if ($isSecond) {
                $digit *= 2;

                if ($digit > 9) {
                    $digit -= 9;
                }
            }

            $sum += $digit;

            $isSecond = !$isSecond;
        }

        return ($sum % 10) == 0;
    }

    
    public function validateExpirationDate($expirationDate)
    {
        $expirationDate = preg_replace('/\D/', '', $expirationDate);

        if (!ctype_digit($expirationDate) || strlen($expirationDate) != 4) {
            return false;
        }

        $today = date('Ym');
        return $expirationDate > $today;
    }
}

