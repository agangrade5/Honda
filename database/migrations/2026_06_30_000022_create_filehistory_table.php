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
        Schema::create('filehistory', function (Blueprint $table) {
            $table->bigIncrements('historyid');
            $table->string('historyfilepath', 100);
            $table->timestamp('historyfiledate')->useCurrent();
            $table->bigInteger('historycardbatch')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('filehistory');
    }
};