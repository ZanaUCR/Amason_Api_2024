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
        Schema::table('user', function (Blueprint $table) {
            $table->foreign(['login_id'], 'user_ibfk_1')->references(['login_id'])->on('login')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['user_id'], 'user_ibfk_2')->references(['login_id'])->on('login')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user', function (Blueprint $table) {
            $table->dropForeign('user_ibfk_1');
            $table->dropForeign('user_ibfk_2');
        });
    }
};
