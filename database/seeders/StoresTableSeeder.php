<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StoresTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('stores')->insert([
            'seller_id' => 1,
            'location_id' => null,
            'store_name' => 'TechWorld Store',
            'description' => 'A store that sells high-quality electronics and gadgets.',
            'email' => 'techworld@example.com',
            'logo' => 'https://i.imgur.com/TechWorldLogo.png',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
