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
        Schema::table('ticket_messages', function (Blueprint $table) {
            $table->foreign(['ticket_id'], 'ticket_messages_ibfk_1')->references(['ticket_id'])->on('ticket')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ticket_messages', function (Blueprint $table) {
            $table->dropForeign('ticket_messages_ibfk_1');
        });
    }
};
