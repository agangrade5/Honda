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
        Schema::create('hogcountries', function (Blueprint $table) {
            $table->bigIncrements('hogcountryid');
            $table->string('countryname', 75)->nullable();
            $table->string('countrycode', 10)->nullable();
            $table->bigInteger('hogregionid')->nullable();
            $table->boolean('recordstatus');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hogcountries');
    }
};