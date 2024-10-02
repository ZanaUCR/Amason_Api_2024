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
        Schema::table('payment_method', function (Blueprint $table) {
            $table->foreign(['id'], 'payment_method_ibfk_1')->references(['transaction_id'])->on('transaction')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment_method', function (Blueprint $table) {
            $table->dropForeign('payment_method_ibfk_1');
        });
    }
};
