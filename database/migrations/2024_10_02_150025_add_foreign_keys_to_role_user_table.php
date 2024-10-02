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
        Schema::table('role_user', function (Blueprint $table) {
            $table->foreign(['user_id'], 'role_user_ibfk_1')->references(['user_id'])->on('user')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['role_id'], 'role_user_ibfk_2')->references(['role_id'])->on('role')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('role_user', function (Blueprint $table) {
            $table->dropForeign('role_user_ibfk_1');
            $table->dropForeign('role_user_ibfk_2');
        });
    }
};
