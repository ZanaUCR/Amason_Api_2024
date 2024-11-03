<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CategoriesGroupSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            UserTableSeeder::class,
            CategoriesTableSeeder::class,
            StoresTableSeeder::class,
            ProductsTableSeeder::class,
            ProductImagesTableSeeder::class,
        ]);
    }
}
