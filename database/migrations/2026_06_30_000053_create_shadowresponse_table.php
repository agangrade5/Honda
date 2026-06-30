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
        Schema::create('shadowresponse', function (Blueprint $table) {
            $table->increments('respid');
            $table->longText('message')->nullable();
            $table->dateTime('datetime')->nullable();
            $table->longText('postdata')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shadowresponse');
    }
};