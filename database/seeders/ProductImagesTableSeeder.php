<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductImagesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('product_images')->insert([
            // Electronics Images
            ['product_id' => 1, 'image_path' => 'https://i.imgur.com/ILA7fxR.jpeg'],
            ['product_id' => 2, 'image_path' => 'https://i.imgur.com/BUtw9dK.jpeg'],
            ['product_id' => 3, 'image_path' => 'https://i.imgur.com/VjULQ3K.jpeg'],

            // Clothing Images
            ['product_id' => 4, 'image_path' => 'https://i.imgur.com/JR6Fs3C.jpeg'],
            ['product_id' => 5, 'image_path' => 'https://i.imgur.com/JR6Fs3C.jpeg'],

            // Books Images
            ['product_id' => 6, 'image_path' => 'https://i.imgur.com/6Ruw7zY.jpeg'],
            ['product_id' => 7, 'image_path' => 'https://i.imgur.com/muMFPLC.jpeg'],
            ['product_id' => 8, 'image_path' => 'https://i.imgur.com/pJjgKff.jpeg'],
            ['product_id' => 9, 'image_path' => 'https://i.imgur.com/OAycTVA.jpeg'],

            // Toys & Games Images
            ['product_id' => 10, 'image_path' => 'https://i.imgur.com/RdOlhea.jpeg'],
            ['product_id' => 11, 'image_path' => 'https://i.imgur.com/6Nn3nBe.jpeg'],
            ['product_id' => 12, 'image_path' => 'https://i.imgur.com/iAgkcCI.jpeg'],
            ['product_id' => 13, 'image_path' => 'https://i.imgur.com/iz2QCjH.jpeg'],

            // Automotive Images
            ['product_id' => 14, 'image_path' => 'https://i.imgur.com/TeKMFjp.jpeg'],
            ['product_id' => 15, 'image_path' => 'https://i.imgur.com/p5O0v36.jpeg'],
            ['product_id' => 16, 'image_path' => 'https://i.imgur.com/NJ2SHyR.jpeg'],
            ['product_id' => 17, 'image_path' => 'https://i.imgur.com/YIo1mFl.jpeg'],

            // Beauty & Personal Care Images
            ['product_id' => 18, 'image_path' => 'https://i.imgur.com/AKgnV2k.jpeg'],
            ['product_id' => 19, 'image_path' => 'https://i.imgur.com/qepyaZK.jpeg'],
        ]);
    }
}
