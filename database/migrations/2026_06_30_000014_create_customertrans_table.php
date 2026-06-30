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
        Schema::create('customertrans', function (Blueprint $table) {
            $table->bigIncrements('transid');
            $table->bigInteger('eventid')->nullable();
            $table->bigInteger('transtype')->nullable();
            $table->bigInteger('custid')->nullable();
            $table->dateTime('transdate')->nullable();
            $table->dateTime('servertime')->nullable();
            $table->string('terminalid', 100)->nullable();
            $table->bigInteger('transpoints')->nullable();
            $table->longText('transdescriptionblob')->nullable();
            $table->string('apiprocessed', 45)->nullable()->default(0);
            $table->string('campaigntrackingcode', 100)->nullable();
            $table->boolean('recordstatus')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customertrans');
    }
};