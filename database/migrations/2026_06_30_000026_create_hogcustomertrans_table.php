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
        Schema::create('hogcustomertrans', function (Blueprint $table) {
            $table->bigIncrements('hogtransid');
            $table->bigInteger('eventid')->nullable();
            $table->bigInteger('transtype')->nullable();
            $table->string('hogidentifier', 100)->nullable();
            $table->dateTime('transdate')->nullable();
            $table->dateTime('servertime')->nullable();
            $table->string('terminalid', 100)->nullable();
            $table->bigInteger('transpoints')->nullable();
            $table->longText('transdescriptionblob')->nullable();
            $table->string('apiprocessed', 45)->nullable()->default(0);
            $table->index(['hogidentifier'], 'hogidentifier_idx');
            $table->foreign(['hogidentifier'], 'hogidentifier')->references(['hogidentifier'])->on('hogcards')->onDelete('no action')->onUpdate('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hogcustomertrans');
    }
};