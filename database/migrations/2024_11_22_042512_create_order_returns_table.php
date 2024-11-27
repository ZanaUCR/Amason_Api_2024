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
            $table->date('date');
            $table->date('return_date')->nullable();
            $table->enum('status', ['pendiente', 'aprobado', 'rechazado', 'completado']);
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
