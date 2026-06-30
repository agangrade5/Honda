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
        Schema::create('trucks', function (Blueprint $table) {
            $table->bigIncrements('truckid');
            $table->string('truckname', 50)->nullable();
            $table->bigInteger('countryid')->nullable();
            $table->bigInteger('clientid')->nullable();
            $table->bigInteger('bt_setid')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trucks');
    }
};