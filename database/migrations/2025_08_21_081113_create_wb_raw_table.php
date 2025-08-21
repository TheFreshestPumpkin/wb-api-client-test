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
        Schema::create('wb_raw', function (Blueprint $table) {
            $table->id();
            $table->string('endpoint');
            $table->unsignedBigInteger('page');
            $table->date('date_from')->nullable();
            $table->date('date_to')->nullable();
            $table->json('payload');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wb_raw');
    }
};
