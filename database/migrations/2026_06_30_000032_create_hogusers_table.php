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
        Schema::create('hogusers', function (Blueprint $table) {
            $table->bigIncrements('hoguserid');
            $table->bigInteger('clientid')->nullable();
            $table->string('username', 25)->nullable();
            $table->string('userpass', 25)->nullable();
            $table->bigInteger('userlevel')->nullable();
            $table->longText('allowevents')->nullable();
            $table->string('firstname', 50)->nullable();
            $table->string('lastname', 50)->nullable();
            $table->string('emailid', 45)->nullable();
            $table->longText('allowcountry')->nullable();
            $table->string('userphone', 45)->nullable();
            $table->longText('allowregion')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hogusers');
    }
};