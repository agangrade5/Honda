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
        Schema::create('nhraphotos', function (Blueprint $table) {
            $table->bigIncrements('photoid');
            $table->bigInteger('custid')->nullable();
            $table->longText('photolocation')->nullable();
            $table->dateTime('phototimetaken')->nullable();
            $table->dateTime('servertime')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nhraphotos');
    }
};