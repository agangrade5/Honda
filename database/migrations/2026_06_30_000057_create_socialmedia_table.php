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
        Schema::create('socialmedia', function (Blueprint $table) {
            $table->bigIncrements('socialid');
            $table->longText('socialblob')->nullable();
            $table->bigInteger('clientid')->nullable();
            $table->string('socialname', 100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('socialmedia');
    }
};