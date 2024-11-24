<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\product;
use App\Models\OrderItem;
use App\Http\Controllers\PaymentMethodController;

class OrderController extends Controller
{
    protected $cartProductsController;
    protected $paymentMethodController;

    public function __construct(CartProductsController $cartProductsController, PaymentMethodController $paymentMethodController)
    {
        $this->cartProductsController = $cartProductsController;
        $this->paymentMethodController = $paymentMethodController;
    }

    // processOrder()
    // validatePaymentMethod()
    // finishOrder()
    public function processOrder(Request $request)
    {
        $paymentMethod = $request->input('paymentMethod');
        $paymentMethodId = $paymentMethod === 'card' ? 1 : ($paymentMethod === 'paypal' ? 2 : null);

        if ($paymentMethod === 'card' && !$this->validateCardNumber($request->input('cardNumber', null))) {
            return response()->json(['status' => 'failed', 'message' => 'Invalid card number.'], 400);
        }

        $deliveryInfoRequest = new Request();
        $deliveryInfoResponse = app(UserController::class)->getDeliveryInformation($deliveryInfoRequest);
        $deliveryInfo = $deliveryInfoResponse->getData();

        $orderInProgress = $this->searchPendingOrderByUser();
        $finishOrderRequest = new Request([
            'order_id' => $orderInProgress->order_id,
            'payment_method_id' => $paymentMethodId,
        ]);




        return $this->finishOrder($finishOrderRequest);
    }


    public function createOrder(Request $request)
    {

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'status' => 'required|integer' // 1 proceso 2 finalizado 3 cancelado
        ]);


        $cartProducts = $this->searchProductInCartByuser_id();

        if ($cartProducts->isEmpty()) {
            return response()->json(['status' => 'failed', 'message' => 'No products in the cart.'], 400);
        }

        $totalAmount = 0;
        foreach ($cartProducts as $cartProduct) {
            $product = $this->searchProduct($cartProduct->product_id);
            $totalAmount += $product->price * $cartProduct->quantity;
        }

        $order = new Order(
            [
                'user_id' => $validated['user_id'],
                'status' => $validated['status'],
                'total_amount' => $totalAmount,
            ]
        );

        $order->save();

        foreach ($cartProducts as $cartProduct) {
            $product = $this->searchProduct($cartProduct->product_id);


            $orderItem = new OrderItem([
                'product_id' => $product->product_id,
                'order_id' => $order->order_id,
                'quantity' => $cartProduct->quantity,
                'price_at_purchase' => $product->price
            ]);

            $orderItem->save();
            //$cartProduct->delete();
        }

        return response()->json(['status' => 'success', 'order_id' => $order->order_id, 'total_amount' => $totalAmount]);
    }


    public function searchProduct($product_id)
    {
        $product = Product::where('product_id', $product_id)->firstOrFail();
        return $product;
    }

    public function searchProductInCartByuser_id()
    {
        return $this->cartProductsController->searchProductInCartByuser_id();
    }


    public function validateCardNumber($cardNumber)
    {
        $paymentMethodRequest = new Request(['cardNumber' => $cardNumber]);
        return $this->paymentMethodController->validateCardNumber($paymentMethodRequest);
    }


    public function finishOrder(Request $request)
    {
        $order_id = $request->input('order_id');

        try {
            $order = $this->searchOrder($order_id);
            if (!$order) {
                return response()->json(['status' => 'failed', 'message' => 'Order not found.'], 404);
            }

            $order->status = 2;
            $order->payment_method_id = $request->input('payment_method_id');
            $order->save();

            return response()->json(['status' => 'success', 'message' => 'Order finished.'], 200);

        } catch (\Exception $e) {
            return response()->json(['status' => 'failed', 'message' => $e->getMessage()], 500);
        }
    }

    public function cancelOrder(Request $request)
    {
        $order_id = $request->input('order_id');
        $user_id = $request->input('user_id');
        try {
            $order = $this->searchOrderCancel($order_id, $user_id);
            if (!$order) {
                return response()->json(['status' => 'failed', 'message' => 'Order not found.'], 404);
            }

            $order->status = 3;
            $order->save();

            return response()->json(['status' => 'success', 'message' => 'Order cancelled.'], 200);

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

    public function searchOrderCancel($order_id, $user_id)
    {
        $order = Order::where('user_id', $user_id)->where('order_id', $order_id)->first();
        // $order = Order::where('user_id', 1)->where('order_id', $order_id)->first();
        return $order;
    }

    public function getAllOrdersByUser()
    {
        $orders = Order::where('user_id', auth()->user()->id)->get();
        return $orders;
    }


    public function searchPendingOrderByUser()
    {
        $userId = auth()->user()->id;

        // Buscar una orden con el user_id del usuario autenticado y con status igual a 1
        $order = Order::where('user_id', $userId)
            ->where('status', 1)
            ->first();

        // Verificar si se encontró la orden
        if ($order) {
            return $order;
        } else {
            return null;
        }
    }


}
