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
        Schema::create('btmodels', function (Blueprint $table) {
            $table->bigIncrements('bt_modelid');
            $table->string('bt_modelname', 100)->nullable();
            $table->integer('bt_qty')->nullable();
            $table->text('bt_times')->nullable();
            $table->bigInteger('bt_setid')->nullable();
            $table->integer('bt_position')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('btmodels');
    }
};