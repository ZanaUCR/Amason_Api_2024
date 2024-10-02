<?php

namespace App\Http\Controllers;
use App\Models\User; // Importa el modelo User
use App\Models\Role; // Importa el modelo Role
use Illuminate\Support\Facades\Auth; // Importa Auth
use Illuminate\Support\Facades\Hash; // Importa Hash
use Illuminate\Http\Request;

class AuthController extends Controller
{
     // Registro de usuarios
     public function register(Request $request)
     {
         $request->validate([
             'name' => 'required|string|max:255',
             'email' => 'required|string|email|max:255|unique:users',
             'password' => 'required|string|min:8|confirmed',
             'role' => 'required|string|exists:roles,name', // Validar que el rol existe
         ]);
 
         $user = User::create([
             'name' => $request->name,
             'email' => $request->email,
             'password' => Hash::make($request->password),
         ]);
 
         // Asignar rol al usuario
         $role = Role::where('name', $request->role)->first();
         $user->roles()->attach($role);
 
         return response()->json(['message' => 'User registered successfully!', 'user' => $user], 201);
     }
 
     // Inicio de sesión
     public function login(Request $request)
     {
         $request->validate([
             'email' => 'required|string|email',
             'password' => 'required|string|min:8',
         ]);
 
         if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
             $user = Auth::user();
             $token = $user->createToken('API Token')->plainTextToken;
 
             return response()->json(['token' => $token, 'roles' => $user->roles], 200);
         }
 
         return response()->json(['message' => 'Unauthorized'], 401);
     }
 
     // Cierre de sesión
     public function logout(Request $request)
     {
         Auth::user()->tokens()->delete();
 
         return response()->json(['message' => 'Logged out successfully']);
     }
}
