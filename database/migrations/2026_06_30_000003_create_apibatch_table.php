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
        Schema::create('apibatch', function (Blueprint $table) {
            $table->increments('batchid');
            $table->longText('batchcustomers')->nullable();
            $table->integer('batchprocessed')->nullable()->default(0);
            $table->string('apikey', 100)->nullable();
            $table->boolean('recordstatus');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apibatch');
    }
};