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
        Schema::create('signatures', function (Blueprint $table) {
            $table->bigIncrements('sigid');
            $table->string('cardnumber', 12)->nullable();
            $table->dateTime('sigdate')->nullable();
            $table->text('sigimage')->nullable();
            $table->integer('sigform')->nullable();
            $table->bigInteger('clientid')->nullable();
            $table->string('terminalid', 50)->nullable();
            $table->bigInteger('userid')->nullable();
            $table->integer('recordstatus')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('signatures');
    }
};