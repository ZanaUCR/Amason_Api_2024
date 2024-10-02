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
        Schema::create('user', function (Blueprint $table) {
            $table->integer('user_id')->primary();
            $table->integer('login_id')->index('login_id');
            $table->string('name');
            $table->string('second_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('second_last_name')->nullable();
            $table->integer('role_id')->nullable();
            $table->date('registration_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user');
    }
};
