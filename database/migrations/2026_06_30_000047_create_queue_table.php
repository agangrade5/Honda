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
        Schema::create('queue', function (Blueprint $table) {
            $table->bigIncrements('queueid');
            $table->bigInteger('custid')->nullable();
            $table->dateTime('checkintime')->nullable();
            $table->dateTime('servertime')->nullable();
            $table->bigInteger('modelid')->nullable();
            $table->bigInteger('clientid')->nullable();
            $table->bigInteger('eventid')->nullable();
            $table->dateTime('estimatedridetime')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('queue');
    }
};