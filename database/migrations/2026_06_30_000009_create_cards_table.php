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
        Schema::create('cards', function (Blueprint $table) {
            $table->bigIncrements('cardid');
            $table->bigInteger('cardnumber')->nullable();
            $table->bigInteger('eventid')->nullable();
            $table->string('cardtype', 10)->nullable();
            $table->bigInteger('cardtotalpoints')->nullable();
            $table->bigInteger('cardbatch')->nullable();
            $table->bigInteger('clientid')->nullable();
            $table->boolean('recordstatus')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cards');
    }
};