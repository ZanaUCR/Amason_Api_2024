<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    
    public function getDeliveryInformation()
    {
        // Obtener al usuario autenticado
        $user = auth()->user(); 

        // Obtener la información de la dirección de entrega directamente desde el modelo User
        $deliveryInfo = [
            'address' => $user->address,
            'city' => $user->city,
            'postal_code' => $user->postal_code,
            'number' => $user->number,
            'country' => $user->country
        ];

        return response()->json($deliveryInfo);
    }

  
    public function updateDeliveryInformation(Request $request)
    {
        // Obtener al usuario autenticado
        $user = auth()->user();

        // Validar los datos de la dirección de entrega
        $validatedData = $request->validate([
            'address' => 'required|string',
            'city' => 'required|string',
            'postal_code' => 'required|string',
            'number' => 'required|string',
            'country' => 'required|string'
        ]);

        // Actualizar los campos directamente en la tabla users
        $user->update($validatedData);

        // Responder con un mensaje de éxito
        return response()->json(['message' => 'Información de entrega actualizada exitosamente.']);
    }
}
