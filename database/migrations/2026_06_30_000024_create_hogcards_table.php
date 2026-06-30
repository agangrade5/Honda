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
        Schema::create('hogcards', function (Blueprint $table) {
            $table->bigIncrements('hogid');
            $table->string('membershipcountry', 50)->nullable();
            $table->string('membershipnumber', 50)->nullable();
            $table->string('membershiptypecode', 50)->nullable();
            $table->string('membershiptypedescription', 50)->nullable();
            $table->string('membershipexpirationdate', 50)->nullable();
            $table->string('membershipstatus', 50)->nullable();
            $table->string('memberlastname', 50)->nullable();
            $table->string('memberfirstname', 50)->nullable();
            $table->string('memberinitial', 50)->nullable();
            $table->string('membertitle', 50)->nullable();
            $table->string('membersuffix', 50)->nullable();
            $table->string('memberaddress1', 255);
            $table->string('memberaddress2', 50)->nullable();
            $table->string('memberaddress3', 50)->nullable();
            $table->string('memberaddress4', 50)->nullable();
            $table->string('membercity', 50)->nullable();
            $table->string('memberstate', 50)->nullable();
            $table->string('memberpostalcode', 50)->nullable();
            $table->string('memberaddresscountry', 50)->nullable();
            $table->string('hogidentifier', 100)->nullable();
            $table->index(['hogidentifier'], 'hogidentifier_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hogcards');
    }
};