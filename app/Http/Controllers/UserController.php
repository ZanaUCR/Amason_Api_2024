<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    
    public function getDeliveryInformation()
    {
        $user = auth()->user(); 
    
        // Verificar si el usuario tiene la información de entrega necesaria
        if (!$user->address || !$user->city || !$user->postal_code || !$user->country) {
            return response()->json(['error' => 'Información de entrega incompleta'], 400);
        }
    
        // Obtener la información de la dirección de entrega directamente desde el modelo User
        $deliveryInfo = [
            'name' => $user->name,
            'address' => $user->address,
            'city' => $user->city,
            'postal_code' => $user->postal_code,
            // 'number' => $user->number,   // No se encuentra en la tabla users(?)
            'country' => $user->country
        ];
    
        return response()->json($deliveryInfo, 200);
    }

  
    public function updateDeliveryInformation(Request $request)
    {
        // Obtener al usuario autenticado
        $user = auth()->user();

        // Validar los datos de la dirección de entrega
        $validatedData = $request->validate([
            'name' => 'required|string',
            'address' => 'required|string',
            'city' => 'required|string',
            'postal_code' => 'required|string',
            // 'number' => 'required|string',
            'country' => 'required|string'
        ]);

        // Actualizar los campos directamente en la tabla users
        $user->update($validatedData);

        // Responder con un mensaje de éxito
        return response()->json(['message' => 'Información de entrega actualizada exitosamente.']);
    }
}
