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
        Schema::create('smtpcredentials', function (Blueprint $table) {
            $table->bigIncrements('credentialid');
            $table->bigInteger('clientid')->nullable();
            $table->longText('credentialblob')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('smtpcredentials');
    }
};