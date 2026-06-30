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
        Schema::create('clients', function (Blueprint $table) {
            $table->bigIncrements('clientid');
            $table->bigInteger('userid')->nullable();
            $table->string('clientname', 100)->nullable();
            $table->string('clientaddress', 100)->nullable();
            $table->bigInteger('clienttimezone')->nullable();
            $table->integer('createdby')->nullable();
            $table->timestamp('createddate')->nullable();
            $table->integer('updatedby')->nullable();
            $table->timestamp('updateddate')->nullable();
            $table->char('status', 1)->nullable()->default(1);
            $table->boolean('recordstatus');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};