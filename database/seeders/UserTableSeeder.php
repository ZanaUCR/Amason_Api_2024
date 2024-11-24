<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // Asegúrate de importar DB
use Illuminate\Support\Facades\Hash; // Importa la clase Hash

class UserTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Vendedor',
             'email' => 'vendedor@example.com',
             'password' => Hash::make('12345678'), // Asegúrate de usar un hash para la contraseña
             'created_at' => now(),
             'updated_at' => now(),
         ]);

         DB::table('users')->insert([
            'name' => 'Pruebin',
             'email' => 'prueba@gmail.com',
             'password' => Hash::make('12345678'), // Asegúrate de usar un hash para la contraseña
             'created_at' => now(),
             'updated_at' => now(),
         ]);

        DB::table('role_user')->insert([
            'role_id' => 3,
            'user_id' => 1,
        ]);

        DB::table('role_user')->insert([
            'role_id' => 2,
            'user_id' => 2,
        ]);
    }
   
}
