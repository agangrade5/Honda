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
        Schema::create('preregistrationcustomerparents', function (Blueprint $table) {
            $table->bigIncrements('parentid');
            $table->bigInteger('custid')->nullable();
            $table->string('parentfname', 30)->nullable();
            $table->string('parentlname', 30)->nullable();
            $table->string('parentemail', 100)->nullable();
            $table->string('parentphone', 25)->nullable();
            $table->date('parentbirthday')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('preregistrationcustomerparents');
    }
};