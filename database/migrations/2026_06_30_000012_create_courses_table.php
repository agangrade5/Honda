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
        Schema::create('courses', function (Blueprint $table) {
            $table->bigIncrements('courseid');
            $table->bigInteger('eventid')->nullable();
            $table->integer('courseestimatedtime')->nullable();
            $table->integer('courseactualtime')->nullable();
            $table->string('coursename', 50)->nullable();
            $table->boolean('recordstatus');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};