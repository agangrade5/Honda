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
        Schema::create('smstemplates', function (Blueprint $table) {
            $table->bigIncrements('templateid');
            $table->bigInteger('clientid')->nullable();
            $table->longText('templateblob')->nullable();
            $table->longText('smstemplatesubj')->nullable();
            $table->longText('smssubj');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('smstemplates');
    }
};