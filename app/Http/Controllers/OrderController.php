<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    // processOrder()
    // validatePaymentMethod()
    // finishOrder()
    public function processOrder(Request $request)
    {
        // Asignar los valores de la solicitud a variables locales
        $amount = $request->input('amount'); 
        $paymentMethod = $request->input('paymentMethod');
        $cardNumber = $request->input('cardNumber', null); // El valor por defecto es null si no se proporciona
        $securityCode = $request->input('securityCode', null); // El valor por defecto es null si no se proporciona
        $cardHolderName = $request->input('cardHolderName', null); // El valor por defecto es null si no se proporciona
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
