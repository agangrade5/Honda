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
        Schema::create('proxyeventconfig', function (Blueprint $table) {
            $table->bigInteger('configid');
            $table->bigInteger('eventid')->nullable();
            $table->primary('configid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proxyeventconfig');
    }
};