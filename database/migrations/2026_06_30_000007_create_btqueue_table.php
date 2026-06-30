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
        Schema::create('btqueue', function (Blueprint $table) {
            $table->bigIncrements('btq_id');
            $table->bigInteger('btq_cardnumber')->nullable();
            $table->bigInteger('btq_btmodelid')->nullable();
            $table->text('btq_time')->nullable();
            $table->bigInteger('btq_eventid')->nullable();
            $table->boolean('btq_status')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('btqueue');
    }
};