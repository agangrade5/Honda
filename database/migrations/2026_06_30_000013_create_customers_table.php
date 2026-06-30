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
        Schema::create('customers', function (Blueprint $table) {
            $table->bigIncrements('custid');
            $table->bigInteger('cardnumber')->nullable();
            $table->string('custfname', 30)->nullable();
            $table->string('custlname', 30)->nullable();
            $table->longText('custaddress')->nullable();
            $table->bigInteger('custcountry')->nullable();
            $table->string('custemail', 100)->nullable();
            $table->string('custphone', 25)->nullable();
            $table->string('custgender', 10)->nullable();
            $table->date('custbirthday')->nullable();
            $table->string('custdriverslicense', 50)->nullable();
            $table->bigInteger('custethnicity')->nullable();
            $table->boolean('custmotorcyclelic')->nullable();
            $table->date('custlicexpire')->nullable();
            $table->bigInteger('custlang')->nullable();
            $table->bigInteger('clientid')->nullable();
            $table->boolean('custoptin')->nullable();
            $table->dateTime('custlastupdated')->nullable();
            $table->boolean('apiprocessed')->nullable()->default(0);
            $table->boolean('recordstatus')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};