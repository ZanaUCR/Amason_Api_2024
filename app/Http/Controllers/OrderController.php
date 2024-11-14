<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Http\Controllers\PaymentMethodController;

class OrderController extends Controller
{
    // processOrder()
    // validatePaymentMethod()
    // finishOrder()
    public function processOrder(Request $request)
    {
        
        $order_id = $request->input('order_id');
        $amount = $request->input('amount');

        $paymentMethod = $request->input('paymentMethod');
        $cardNumber = $request->input('cardNumber', null);
        $securityCode = $request->input('securityCode', null);
        $cardHolderName = $request->input('cardHolderName', null);

        $deliveryInfoRequest = new Request();
        $deliveryInfoResponse = app(UserController::class)->getDeliveryInformation($deliveryInfoRequest);
        $deliveryInfo = $deliveryInfoResponse->getData();

        if (!$this->validateCardNumber($cardNumber)) {
            return response()->json(['status' => 'failed', 'message' => 'Invalid card number.'], 400);
        } else {
            $finishOrderRequest = new Request(['order_id' => $order_id]);
            return $this->finishOrder($finishOrderRequest);
        }
    }


    public function validateCardNumber($cardNumber)
    {
        $paymentMethodController = new PaymentMethodController();
        $paymentMethodRequest = new Request([
            'cardNumber' => $cardNumber
        ]);
        $paymentMethodController->validateCardNumber($paymentMethodRequest);
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
         $order = Order::where('user_id', auth()->user()->id)->where('order_id', $order_id)->first();
       // $order = Order::where('user_id', 1)->where('order_id', $order_id)->first();
        return $order;
    }



}
