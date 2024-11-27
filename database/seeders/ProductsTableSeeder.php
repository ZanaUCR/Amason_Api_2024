<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('products')->insert([
            // Electronics
            [
                'name' => 'Laptop Thunderbolt 15',
                'description' => 'Laptop with retina display and 16GB RAM, perfect for gaming.',
                'price' => 499000,
                'stock' => 30,
                'category_id' => 1,
                'id_store' => 1,
                'variation' => json_encode([['type' => 'Configuration', 'options' => ['16GB RAM / 512GB SSD', '32GB RAM / 1TB SSD']]]),
            ],
            [
                'name' => 'Smartwatch FitPro',
                'description' => 'Smartwatch with GPS and health monitoring features.',
                'price' => 154000,
                'stock' => 100,
                'category_id' => 1,
                'id_store' => 1,
                'variation' => json_encode([['type' => 'Color', 'options' => ['Black', 'Silver', 'Rose Gold']]]),
            ],
            [
                'name' => 'Wireless Headphones X',
                'description' => 'Bluetooth headphones with noise cancellation.',
                'price' => 102999,
                'stock' => 80,
                'category_id' => 1,
                'id_store' => 1,
                'variation' => json_encode([['type' => 'Color', 'options' => ['Black', 'White', 'Blue']]]),
            ],

            // Clothing
            [
                'name' => 'Classic Denim Jacket',
                'description' => 'Classic denim jacket for men and women.',
                'price' => 41999,
                'stock' => 60,
                'category_id' => 2,
                'id_store' => 1,
                'variation' => json_encode([['type' => 'Size', 'options' => ['S', 'M', 'L', 'XL']]]),
            ],
            [
                'name' => 'Floral Summer Dress',
                'description' => 'Summer dress with floral print.',
                'price' => 307000,
                'stock' => 100,
                'category_id' => 2,
                'id_store' => 1,
                'variation' => json_encode([['type' => 'Size', 'options' => ['S', 'M', 'L']]]),
            ],

            // Books
            [
                'name' => 'The Mystery of the Lost Island',
                'description' => 'A mystery novel about a deserted island and its secrets.',
                'price' => 6600,
                'stock' => 120,
                'category_id' => 3,
                'id_store' => 1,
                'variation' => json_encode([['type' => 'Format', 'options' => ['Paperback', 'Hardcover', 'E-book']]]),
            ],
            [
                'name' => 'Cooking Like a Pro',
                'description' => 'A cookbook for beginners and advanced chefs.',
                'price' => 12814,
                'stock' => 90,
                'category_id' => 3,
                'id_store' => 1,
                'variation' => json_encode([['type' => 'Language', 'options' => ['English', 'Spanish']]]),
            ],
            [
                'name' => 'Learning JavaScript',
                'description' => 'Comprehensive guide to learning JavaScript from scratch.',
                'price' => 2999,
                'stock' => 60,
                'category_id' => 3,
                'id_store' => 1,
                'variation' => json_encode([['type' => 'Edition', 'options' => ['First Edition', 'Second Edition']]]),
            ],
            [
                'name' => 'Space Explorers',
                'description' => 'An exciting science fiction novel.',
                'price' => 15378,
                'stock' => 70,
                'category_id' => 3,
                'id_store' => 1,
                'variation' => json_encode([['type' => 'Language', 'options' => ['English', 'French']]]),
            ],

            // Toys & Games
            [
                'name' => 'Robotic Building Set',
                'description' => 'Kit to build a robot with interchangeable parts.',
                'price' => 46500,
                'stock' => 40,
                'category_id' => 4,
                'id_store' => 1,
                'variation' => json_encode([['type' => 'Difficulty Level', 'options' => ['Beginner', 'Intermediate', 'Expert']]]),
            ],
            [
                'name' => 'Forest Adventure Board Game',
                'description' => 'A family board game with adventure dynamics.',
                'price' => 17900,
                'stock' => 70,
                'category_id' => 4,
                'id_store' => 1,
                'variation' => json_encode([['type' => 'Number of Players', 'options' => ['2-4', '4-6']]]),
            ],
            [
                'name' => 'Polar Bear XL Plush',
                'description' => 'Giant polar bear plush, soft and cuddly.',
                'price' => 307000,
                'stock' => 50,
                'category_id' => 4,
                'id_store' => 1,
                'variation' => json_encode([['type' => 'Size', 'options' => ['Medium', 'Large']]]),
            ],
            [
                'name' => '3D Eiffel Tower Puzzle',
                'description' => '3D puzzle of the iconic Eiffel Tower with 500 pieces.',
                'price' => 12900,
                'stock' => 60,
                'category_id' => 4,
                'id_store' => 1,
                'variation' => json_encode([['type' => 'Piece Count', 'options' => ['500 pieces', '1000 pieces']]]),
            ],

            // Automotive
            [
                'name' => '75-Piece Car Tool Kit',
                'description' => 'Complete tool kit for car repairs.',
                'price' => 66500,
                'stock' => 20,
                'category_id' => 5,
                'id_store' => 1,
                'variation' => json_encode([['type' => 'Tool Type', 'options' => ['Metric', 'Imperial']]]),
            ],
            [
                'name' => 'Energix Pro Car Battery',
                'description' => 'Long-lasting battery for sports cars.',
                'price' => 82000,
                'stock' => 25,
                'category_id' => 5,
                'id_store' => 1,
                'variation' => json_encode([['type' => 'Battery Capacity', 'options' => ['65Ah', '75Ah']]]),
            ],
            [
                'name' => 'Leather Seat Cleaner',
                'description' => 'Product for cleaning leather car seats.',
                'price' => 10250,
                'stock' => 100,
                'category_id' => 5,
                'id_store' => 1,
                'variation' => json_encode([['type' => 'Size', 'options' => ['250ml', '500ml']]]),
            ],
            [
                'name' => 'Ultra Power 5W30 Motor Oil',
                'description' => 'Synthetic high-performance motor oil.',
                'price' => 25600,
                'stock' => 150,
                'category_id' => 5,
                'id_store' => 1,
                'variation' => json_encode([['type' => 'Quantity', 'options' => ['1L', '5L']]]),
            ],

            // Beauty & Personal Care
            [
                'name' => 'HydraGlow Moisturizer',
                'description' => 'Facial moisturizer for dry skin.',
                'price' => 15300,
                'stock' => 150,
                'category_id' => 6,
                'id_store' => 1,
                'variation' => json_encode([['type' => 'Skin Type', 'options' => ['Dry', 'Normal', 'Combination']]]),
            ],
            [
                'name' => 'Natural HairPlus Shampoo',
                'description' => 'Strengthening shampoo with natural ingredients.',
                'price' => 10250,
                'stock' => 120,
                'category_id' => 6,
                'id_store' => 1,
                'variation' => json_encode([['type' => 'Size', 'options' => ['250ml', '500ml']]]),
            ],
        ]);
    }
}