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
        Schema::table('assigned_user_ticket', function (Blueprint $table) {
            $table->foreign(['ticket_id'], 'assigned_user_ticket_ibfk_1')->references(['ticket_id'])->on('ticket')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['user_id'], 'assigned_user_ticket_ibfk_2')->references(['user_id'])->on('user')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assigned_user_ticket', function (Blueprint $table) {
            $table->dropForeign('assigned_user_ticket_ibfk_1');
            $table->dropForeign('assigned_user_ticket_ibfk_2');
        });
    }
};
