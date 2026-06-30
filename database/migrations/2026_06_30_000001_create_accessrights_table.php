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
        Schema::create('accessrights', function (Blueprint $table) {
            $table->bigIncrements('rightsid');
            $table->bigInteger('eventid')->nullable();
            $table->bigInteger('userid')->nullable();
            $table->boolean('recordstatus');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accessrights');
    }
};