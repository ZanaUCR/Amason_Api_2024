<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
class OrderController extends Controller
{
    // processOrder()
    // validatePaymentMethod()
    // finishOrder()
    public function processOrder($amount, $paymentMethod, $cardNumber = null, $securityCode = null, $cardHolderName = null)
    {
        // Validar el método de pago
        if ($paymentMethod === 'card') {
            // Llamada a validateCardNumber para verificar la tarjeta
            if ($this->validateCardNumber($cardNumber)) {
                // Procesar el pago con tarjeta
                // Aquí iría la lógica de procesamiento de pago, por ejemplo, llamando a un API de pasarela de pagos.
                return response()->json(['status' => 'success', 'message' => 'Order processed with card payment.']);
            } else {
                // Respuesta en caso de que la tarjeta sea inválida
                return response()->json(['status' => 'failed', 'message' => 'Invalid card number.'], 400);
            }
        } elseif ($paymentMethod === 'paypal') {
            // Aquí iría la lógica de procesamiento de pago con PayPal
            return response()->json(['status' => 'success', 'message' => 'Order processed with PayPal payment.']);
        }

        // Respuesta en caso de que el método de pago no sea reconocido
        return response()->json(['status' => 'failed', 'message' => 'Unsupported payment method.'], 400);
    }

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
    public function finishOrder(Request $request)
{
    $order_id = $request->input('order_id');

    try {
        $order = $this->searchOrder($order_id);
        if (!$order) {
            return response()->json(['status' => 'failed', 'message' => 'Order not found.'], 404);
        }
        
        $order->status = 3;
        $order->save();

        return response()->json(['status' => 'success', 'message' => 'Order finished.']);

    } catch (\Exception $e) {
        return response()->json(['status' => 'failed', 'message' => $e->getMessage()], 500);
    }
}


    public function searchOrder($order_id)
    {
       
        // $order = Order::where('user_id', auth()->user()->id)->where('order_id', $order_id)->first();
        $order = Order::where('user_id', 1)->where('order_id', $order_id)->first();
        return $order;
    }



}
