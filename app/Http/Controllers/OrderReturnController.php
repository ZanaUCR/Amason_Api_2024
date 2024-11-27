<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrderReturn;
use App\Models\Order;

class OrderReturnController extends Controller
{
    public function index()
    {
        // Obtener todas las devoluciones del usuario autenticado
        $returns = OrderReturn::where('user_id', auth()->user()->id)->get();

        return response()->json([
            'status' => 'success',
            'returns' => $returns
        ]);
    }

    public function show($id)
    {
        try {
            // Obtener una devolución específica
            $return = OrderReturn::where('user_id', auth()->user()->id)
                ->where('id', $id)
                ->firstOrFail();

            return response()->json([
                'status' => 'success',
                'return' => $return
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Devolución no encontrada: ' . $e->getMessage()
            ], 404);
        }
    }
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'order_id' => 'required|exists:orders,order_id',
                'reason' => 'required|string|min:10'
            ]);
    
            $order = Order::where('order_id', $validated['order_id'])
                ->where('user_id', auth()->user()->id)
                ->first();
    
            if (!$order || $order->status != 2) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Orden no válida para devolución.'
                ], 400);
            }
    
            $return = OrderReturn::create([
                'order_id' => $order->order_id,
                'user_id' => auth()->user()->id,
                'status' => 'pendiente',
                'reason' => $validated['reason'],
                'date' => now()
            ]);
    
            $order->status = 4; // Cambiar el estado de la orden a "en proceso de devolución"
            $order->save();
    
            return response()->json([
                'status' => 'success',
                'message' => 'Solicitud de devolución creada.',
                'return' => $return
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al procesar la solicitud de devolución: ' . $e->getMessage()
            ], 500);
        }
    }
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:aprobado,rechazado,completado',
            'admin_notes' => 'nullable|string'
        ]);

        $return = OrderReturn::findOrFail($id);

        $return->status = $validated['status'];
        $return->admin_notes = $validated['admin_notes'] ?? $return->admin_notes;
        $return->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Devolución actualizada.',
            'return' => $return
        ]);
    }

    public function destroy($id)
    {
        $return = OrderReturn::findOrFail($id);
        $order = $return->order;
        $return->delete();

        if ($order) {
            $order->status = 2; // Cambiar el estado de la orden a "finalizado"
            $order->save();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Devolución eliminada y estado de la orden actualizado.'
        ]);
    }

public function getAllReturns()
{
    try {
        $returns = OrderReturn::all();

        return response()->json([
            'status' => 'success',
            'returns' => $returns
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Error al obtener las devoluciones: ' . $e->getMessage()
        ], 500);
    }
}

public function getReturnsByUser($userId)
{
    $returns = OrderReturn::where('user_id', $userId)->get();
    return response()->json($returns);
}

}
