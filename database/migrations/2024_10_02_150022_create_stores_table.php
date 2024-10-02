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
        Schema::create('stores', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('seller_id')->nullable()->index('seller_id');
            $table->integer('location_id')->nullable();
            $table->string('store_name');
            $table->string('description')->nullable();
            $table->string('email');
            $table->binary('logo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stores');
    }
};
