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
        Schema::create('restrictedriders', function (Blueprint $table) {
            $table->bigIncrements('restrictid');
            $table->string('restrictlic', 100)->nullable();
            $table->dateTime('servertime')->nullable();
            $table->dateTime('restricttime')->nullable();
            $table->string('restrictcomment', 50)->nullable();
            $table->bigInteger('clientid')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restrictedriders');
    }
};