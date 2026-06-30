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
        Schema::create('dragracescores', function (Blueprint $table) {
            $table->bigIncrements('scoreid');
            $table->bigInteger('cardnumber')->nullable();
            $table->decimal('score', 6, 3)->nullable()->default(0.000);
            $table->bigInteger('eventid')->nullable();
            $table->dateTime('datetime')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dragracescores');
    }
};