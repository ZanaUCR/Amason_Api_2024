<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    private function validateCardNumber($cardNumber)
    {
        // Remove any non-digit characters (like spaces or hyphens) to ensure a clean number
        $cardNumber = preg_replace('/\D/', '', $cardNumber);

        // Check that the number contains only digits and is between 13 and 19 digits long
        if (!ctype_digit($cardNumber) || strlen($cardNumber) < 13 || strlen($cardNumber) > 19) {
            return false;
        }

        // Luhn Algorithm implementation
        $sum = 0;
        $isSecond = false;

        // Traverse the card number from right to left
        for ($i = strlen($cardNumber) - 1; $i >= 0; $i--) {
            $digit = $cardNumber[$i];

            if ($isSecond) {
                // Double every second digit
                $digit *= 2;

                // If the result is greater than 9, subtract 9
                if ($digit > 9) {
                    $digit -= 9;
                }
            }

            // Add the digit (adjusted if it was doubled) to the sum
            $sum += $digit;

            // Toggle the flag for every second digit
            $isSecond = !$isSecond;
        }

        // If the sum is a multiple of 10, the card number is valid
        return ($sum % 10) == 0;
    }
}
