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
        Schema::create('rawlicensedata', function (Blueprint $table) {
            $table->bigIncrements('rawlicid');
            $table->longText('rawscan')->nullable();
            $table->longText('rawmagswipe')->nullable();
            $table->bigInteger('custid')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rawlicensedata');
    }
};