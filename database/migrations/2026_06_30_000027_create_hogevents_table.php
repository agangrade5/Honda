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
        Schema::create('hogevents', function (Blueprint $table) {
            $table->bigIncrements('hogeventid');
            $table->string('eventname', 45)->nullable();
            $table->string('eventcountry', 15)->nullable();
            $table->longText('eventaddress')->nullable();
            $table->string('eventphone', 25)->nullable();
            $table->string('eventmanager', 30)->nullable();
            $table->bigInteger('clientid')->nullable();
            $table->string('eventwebsite', 100)->nullable();
            $table->longText('eventusersblob')->nullable();
            $table->dateTime('eventstart')->nullable();
            $table->dateTime('eventend')->nullable();
            $table->bigInteger('eventsurveyid')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hogevents');
    }
};