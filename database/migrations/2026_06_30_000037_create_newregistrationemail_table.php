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
        Schema::create('newregistrationemail', function (Blueprint $table) {
            $table->increments('id');
            $table->string('card_number', 150)->nullable();
            $table->integer('event_id')->nullable();
            $table->integer('customer_id')->nullable();
            $table->dateTime('current_date')->nullable();
            $table->string('customer_email', 150)->nullable();
            $table->string('email_subject', 250)->nullable();
            $table->longText('email_html')->nullable();
            $table->boolean('is_sent')->nullable()->default(0);
            $table->boolean('is_under_cron')->nullable()->default(0);
            $table->boolean('is_passenger')->nullable()->default(0);
            $table->string('slot_booking', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('newregistrationemail');
    }
};