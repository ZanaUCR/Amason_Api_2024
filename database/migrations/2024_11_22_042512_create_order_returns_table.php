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
        Schema::create('order_returns', function (Blueprint $table) {
            $table->id();
            $table->date('date')->unique();
            $table->date('return_date')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'completed']);
            $table->text('reason');
            $table->text('admin_notes')->nullable();
            $table->foreignId('order_id')->constrained('orders', 'order_id');
            $table->foreignId('user_id')->constrained('users', 'id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_returns');
    }
};
