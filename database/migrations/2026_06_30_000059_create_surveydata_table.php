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
        Schema::create('surveydata', function (Blueprint $table) {
            $table->bigIncrements('surveydataid');
            $table->bigInteger('custid')->nullable();
            $table->bigInteger('surveyid')->nullable();
            $table->longText('surveydatablob')->nullable();
            $table->dateTime('surveydatetime')->nullable();
            $table->dateTime('servertime')->nullable();
            $table->bigInteger('eventid')->nullable();
            $table->string('surveydatacol', 45)->nullable();
            $table->boolean('apiprocessed')->nullable()->default(0);
            $table->boolean('recordstatus')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surveydata');
    }
};