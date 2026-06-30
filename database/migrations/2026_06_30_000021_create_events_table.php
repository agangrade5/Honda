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
        Schema::create('events', function (Blueprint $table) {
            $table->bigIncrements('eventid');
            $table->string('eventname', 45)->nullable();
            $table->string('eventcountry', 15)->nullable();
            $table->bigInteger('templateid')->nullable();
            $table->longText('eventaddress')->nullable();
            $table->string('eventphone', 25)->nullable();
            $table->string('eventmanager', 30)->nullable();
            $table->bigInteger('eventassetlogo')->nullable();
            $table->bigInteger('clientid')->nullable();
            $table->string('eventwebsite', 100)->nullable();
            $table->bigInteger('eventsocial')->nullable();
            $table->string('eventcampaigncode', 30)->nullable();
            $table->longText('eventdealers')->nullable();
            $table->longText('eventspointsblob')->nullable();
            $table->longText('eventusersblob')->nullable();
            $table->string('legalid', 100)->nullable();
            $table->longText('eventstrucksblob')->nullable();
            $table->dateTime('eventstart')->nullable();
            $table->dateTime('eventend')->nullable();
            $table->boolean('eventjumpstart')->nullable();
            $table->boolean('eventdemo')->nullable();
            $table->boolean('eventleadgen')->nullable();
            $table->boolean('eventprsurvey')->nullable();
            $table->bigInteger('eventjumpstartwaiver')->nullable();
            $table->bigInteger('eventdemowaiver')->nullable();
            $table->bigInteger('eventleadgenwaiver')->nullable();
            $table->bigInteger('eventjumpstartwaiverunderage')->nullable();
            $table->boolean('eventtrike')->nullable();
            $table->bigInteger('trikewaiver')->nullable();
            $table->bigInteger('eventwelcomeemail')->nullable();
            $table->bigInteger('eventscheduledemail')->nullable();
            $table->bigInteger('eventtyemail')->nullable();
            $table->bigInteger('eventpremail')->nullable()->comment('		');
            $table->bigInteger('eventsalesemail')->nullable();
            $table->bigInteger('eventjumpstartemail')->nullable();
            $table->bigInteger('demopassengerwaiver')->nullable();
            $table->bigInteger('trikepassengerwaiver')->nullable();
            $table->bigInteger('eventlivewirelg')->nullable();
            $table->bigInteger('eventlivewirejs')->nullable();
            $table->bigInteger('eventlivewirejsunderage')->nullable();
            $table->boolean('livewireleadgen')->nullable();
            $table->boolean('livewirejumpstart')->nullable();
            $table->string('democc', 255);
            $table->string('prsurveycc', 255);
            $table->string('leadgencc', 255);
            $table->string('jumpstartcc', 255);
            $table->string('livewirejumpstartcc', 255);
            $table->string('livewireleadgencc', 255);
            $table->boolean('photoapp')->nullable();
            $table->bigInteger('photoappemail')->nullable();
            $table->string('photoappcc', 255)->nullable();
            $table->bigInteger('eventguardianwaiver')->nullable();
            $table->bigInteger('eventdemowaiver2')->nullable();
            $table->bigInteger('eventpassengerwaiver2')->nullable();
            $table->integer('trikewait')->nullable();
            $table->bigInteger('leadgensurvey')->nullable();
            $table->bigInteger('demosurvey')->nullable();
            $table->bigInteger('postridesurvey')->nullable();
            $table->bigInteger('jumpstartsurvey')->nullable();
            $table->boolean('eventbikesandtimes')->nullable()->default(0);
            $table->integer('registrationsuccessfulemailtemplate')->nullable()->default(0);
            $table->integer('registrationsurveyid')->nullable()->default(0);
            $table->boolean('alloweventpreregistrations')->nullable()->default(0);
            $table->integer('remindertemplateemailtemplate')->nullable()->default(0);
            $table->integer('remindertemplate2emailtemplate')->nullable()->default(0);
            $table->string('additionaldetails', 255)->nullable();
            $table->date('eventreminderdate1')->nullable();
            $table->date('eventreminderdate2')->nullable();
            $table->dateTime('eventregistrationdeadlinePST')->nullable();
            $table->boolean('enablesms')->nullable();
            $table->integer('eventsmstemplateid')->nullable();
            $table->integer('eventdemowaiver2_copy1')->nullable();
            $table->integer('waitlisttemplateemailtemplate')->nullable()->default(0);
            $table->integer('eventpreregistrationsuccessemailqty')->nullable()->default(0);
            $table->dateTime('eventpreregistrationsuccessemailqtydate')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};