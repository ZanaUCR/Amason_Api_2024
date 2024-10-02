<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->foreign(['product_id'], 'products_ibfk_1')->references(['id'])->on('stores')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['category_id'], 'products_ibfk_2')->references(['category_id'])->on('categories')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign('products_ibfk_1');
            $table->dropForeign('products_ibfk_2');
        });
    }
};
