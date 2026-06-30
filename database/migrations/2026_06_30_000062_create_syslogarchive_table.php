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
        Schema::create('syslogarchive', function (Blueprint $table) {
            $table->bigIncrements('logid');
            $table->dateTime('logdate')->nullable();
            $table->longText('logmsg')->nullable();
            $table->boolean('recordstatus')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('syslogarchive');
    }
};