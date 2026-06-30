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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->bigIncrements('vehicleid');
            $table->string('vehiclevin', 100)->nullable();
            $table->string('vehiclenickname', 100)->nullable();
            $table->bigInteger('groupid')->nullable();
            $table->longText('vehiclestatus')->nullable();
            $table->bigInteger('modelid')->nullable();
            $table->bigInteger('truckid')->nullable();
            $table->dateTime('vehicleduein')->nullable();
            $table->string('currentrider', 100)->nullable();
            $table->string('currentpassenger', 100)->nullable();
            $table->string('vehiclelicplate', 45)->nullable();
            $table->string('vehiclecolor', 45)->nullable();
            $table->string('clientid', 45);
            $table->boolean('recordstatus');
            $table->string('cov', 100)->nullable();
            $table->string('vehicletype', 45)->nullable()->default('demo');
            $table->boolean('checkinflag')->nullable()->default(0);
            $table->boolean('archive')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};