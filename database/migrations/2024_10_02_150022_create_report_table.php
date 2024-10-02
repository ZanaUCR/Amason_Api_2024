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
        Schema::create('report', function (Blueprint $table) {
            $table->integer('report_id', true);
            $table->integer('seller_id');
            $table->string('report_type')->nullable();
            $table->timestamp('first_date')->nullable();
            $table->timestamp('last_date')->nullable();
            $table->string('information')->nullable();
            $table->date('generation_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report');
    }
};
