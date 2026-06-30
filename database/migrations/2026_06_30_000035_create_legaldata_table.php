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
        Schema::create('legaldata', function (Blueprint $table) {
            $table->bigIncrements('legaldataid');
            $table->longText('legalsignature')->nullable();
            $table->bigInteger('legalid')->nullable();
            $table->bigInteger('custid')->nullable();
            $table->dateTime('legalsignaturetime')->nullable();
            $table->dateTime('servertime')->nullable();
            $table->dateTime('processedtime')->nullable();
            $table->longText('legaldoclocation')->nullable();
            $table->bigInteger('eventid')->nullable();
            $table->boolean('recordstatus')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('legaldata');
    }
};