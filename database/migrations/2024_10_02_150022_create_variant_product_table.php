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
        Schema::create('variant_product', function (Blueprint $table) {
            $table->integer('variant_id')->primary();
            $table->string('brand')->nullable();
            $table->string('state')->nullable();
            $table->integer('stock')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('variant_product');
    }
};
