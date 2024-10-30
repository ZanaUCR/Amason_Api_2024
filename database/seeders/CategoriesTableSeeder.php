<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('categories')->insert([
            ['id' => null, 'name' => 'Electronics'],
            ['id' => null, 'name' => 'Clothing'],
            ['id' => null, 'name' => 'Books'],
            ['id' => null, 'name' => 'Toys & Games'],
            ['id' => null, 'name' => 'Automotive'],
            ['id' => null, 'name' => 'Beauty & Personal Care'],
        ]);
    }
}
