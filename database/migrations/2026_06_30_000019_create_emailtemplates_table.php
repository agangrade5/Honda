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
        Schema::create('emailtemplates', function (Blueprint $table) {
            $table->bigIncrements('templateid');
            $table->bigInteger('clientid')->nullable();
            $table->longText('templateblob')->nullable();
            $table->longText('emailtemplatesubj')->nullable();
            $table->longText('emailsubj');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emailtemplates');
    }
};