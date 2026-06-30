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
        Schema::create('dealers', function (Blueprint $table) {
            $table->bigIncrements('dealerid');
            $table->string('dealernumber', 30)->nullable();
            $table->string('dealername', 50)->nullable();
            $table->string('dealerlocation', 50)->nullable();
            $table->string('dealerregion', 50)->nullable();
            $table->string('dealerdistrict', 50)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dealers');
    }
};