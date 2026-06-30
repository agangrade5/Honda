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
        Schema::create('preregisterhtml', function (Blueprint $table) {
            $table->increments('id');
            $table->string('eventid', 20)->nullable();
            $table->integer('quantity')->default(1);
            $table->text('quantityform');
            $table->text('infoform');
            $table->text('completeform');
            $table->text('htmlcontent');
            $table->text('errorhtml');
            $table->index(['eventid'], 'eventid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('preregisterhtml');
    }
};