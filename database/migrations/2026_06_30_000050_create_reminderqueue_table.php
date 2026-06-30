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
        Schema::create('reminderqueue', function (Blueprint $table) {
            $table->increments('idreminderqueue');
            $table->integer('eventid')->nullable();
            $table->integer('custid')->nullable();
            $table->integer('emailtmpid')->nullable();
            $table->boolean('type')->nullable()->default(0);
            $table->boolean('reminder1_sent')->nullable()->default(0);
            $table->dateTime('regdate')->nullable();
            $table->boolean('reminder2_sent')->nullable()->default(0);
            $table->string('slot_booking', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reminderqueue');
    }
};