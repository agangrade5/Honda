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
        Schema::create('printhistory', function (Blueprint $table) {
            $table->bigIncrements('printid');
            $table->bigInteger('cardnumber')->nullable();
            $table->dateTime('printdatetime')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('printhistory');
    }
};