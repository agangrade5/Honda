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
        Schema::create('postridesurvey', function (Blueprint $table) {
            $table->bigIncrements('prsurveyid');
            $table->bigInteger('clientid')->nullable();
            $table->bigInteger('custid')->nullable();
            $table->dateTime('checkintime')->nullable();
            $table->longText('ridephoto')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('postridesurvey');
    }
};