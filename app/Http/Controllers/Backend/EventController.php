<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\EventRequest;
use App\Models\Event;
use App\Models\User;
use App\Models\Country;
use App\Models\Dealer;
use App\Models\Legal;
use App\Models\Truck;
use App\Models\Survey;
use App\Models\EmailTemplate;
use App\Models\SmsTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Get Year list from Event
        $years = Event::selectRaw('YEAR(eventend) as Year')
            ->groupByRaw('YEAR(eventend)')
            ->orderByRaw('YEAR(eventend) DESC')
            ->get()
            ->map(function ($row) {
                $year = $row->Year ?? 'Uncategorized';
                return (object)[
                    'YearID' => $year,
                    'YearName' => $year
                ];
            });

        $selectedYear = $request->input('EventYear', date('Y'));

        // Query events with country & region join, applying level filters
        $user = auth()->user();
        $userLevel = $user ? $user->userlevel : 1;

        $allowRegion = ($user && is_string($user->allowregion)) ? @unserialize($user->allowregion) : [];
        $allowEvents = ($user && is_string($user->allowevents)) ? @unserialize($user->allowevents) : [];
        $allowCountry = ($user && is_string($user->allowcountry)) ? @unserialize($user->allowcountry) : [];

        if (!is_array($allowRegion)) $allowRegion = [];
        if (!is_array($allowEvents)) $allowEvents = [];
        if (!is_array($allowCountry)) $allowCountry = [];

        $query = DB::table('events as e')
            ->leftJoin('countries as c', 'e.eventcountry', '=', 'c.countryid')
            ->leftJoin('reportregions as r', 'c.regionid', '=', 'r.regionid')
            ->select('e.*', 'r.regionid', 'c.countryname', 'r.regionname');

        if ($selectedYear === 'Uncategorized') {
            $query->whereNull('e.eventend');
        } else {
            $query->whereYear('e.eventend', $selectedYear);
        }

        if ($userLevel == 4) {
            if (!empty($allowRegion)) {
                $query->whereIn('r.regionid', $allowRegion);
            } else {
                $query->whereRaw('1=0');
            }
        } elseif ($userLevel == 6 || $userLevel == 7) {
            if (!empty($allowEvents)) {
                $query->whereIn('e.eventid', $allowEvents);
            } else {
                $query->whereRaw('1=0');
            }
        } elseif ($userLevel == 8 || $userLevel == 9) {
            if (!empty($allowCountry)) {
                $query->whereIn('e.eventcountry', $allowCountry);
            } else {
                $query->whereRaw('1=0');
            }
        }

        $dbEvents = $query->get();

        // Prefetch trucks to avoid N+1 query when listing comma-separated trucks
        $allTrucks = Truck::all()->keyBy('truckid');

        $mappedEvents = [];
        foreach ($dbEvents as $event) {
            $regionName = $event->regionname ?? 'Uncategorized';
            
            // Truck name logic
            $truckName = '';
            $truckIds = !empty($event->eventstrucksblob) ? @unserialize($event->eventstrucksblob) : [];
            if (is_array($truckIds)) {
                $names = [];
                foreach ($truckIds as $tid) {
                    if (isset($allTrucks[$tid])) {
                        $names[] = $allTrucks[$tid]->truckname;
                    }
                }
                $truckName = implode(',', $names);
            }

            $eventDeadline = '';
            if (!empty($event->eventregistrationdeadlinePST)) {
                $eventDeadline = date('Y-m-d H:i', strtotime($event->eventregistrationdeadlinePST));
            }

            // Convert EventTruckBlob and EventDealers to JSON strings
            $truckBlobJson = '';
            if (!empty($event->eventstrucksblob)) {
                $u = @unserialize($event->eventstrucksblob);
                $truckBlobJson = is_array($u) ? json_encode($u) : json_encode([]);
            } else {
                $truckBlobJson = json_encode([]);
            }

            $dealersJson = '';
            if (!empty($event->eventdealers)) {
                $u = @unserialize($event->eventdealers);
                $dealersJson = is_array($u) ? json_encode($u) : json_encode([]);
            } else {
                $dealersJson = json_encode([]);
            }

            $mappedEvents[$regionName][] = (object)[
                'EventID' => $event->eventid,
                'EventName' => $event->eventname,
                'EventCountry' => $event->eventcountry,
                'EventPhone' => $event->eventphone,
                'EventManager' => $event->eventmanager,
                'EventWebsite' => $event->eventwebsite,
                'EventStartDate' => $event->eventstart,
                'EventEndDate' => $event->eventend,
                'EventDealers' => $dealersJson,
                'EventAddress' => $event->eventaddress,
                'EventSocials' => $event->eventsocial,
                'EventTruckBlob' => $truckBlobJson,
                'EventUserBlob' => $event->eventusersblob,
                'EventTemplateID' => $event->templateid,
                'EventWaiverID' => $event->legalid,
                'EventRegionName' => $regionName,
                'EventRegionID' => $event->regionid,
                'EventTruck' => $truckName,
                'EventCountryName' => $event->countryname,
                'EventCampaignCode' => $event->eventcampaigncode,
                'EventWelcomeEmail' => $event->eventwelcomeemail,
                'EventScheduledEmail' => $event->eventscheduledemail,
                'EventTyEmail' => $event->eventtyemail,
                'EventPrEmail' => $event->eventpremail,
                'EventSalesEmail' => $event->eventsalesemail,
                'EventJumpStart' => $event->eventjumpstart,
                'EventLeadGen' => $event->eventleadgen,
                'EnableSms' => $event->enablesms,
                'EventDemo' => $event->eventdemo,
                'EventPRSurvey' => $event->eventprsurvey,
                'EventTrike' => $event->eventtrike,
                'EventJumpStartWaiver' => $event->eventjumpstartwaiver,
                'EventJumpStartWaiverUnderAge' => $event->eventjumpstartwaiverunderage,
                'EventLeadGenWaiver' => $event->eventleadgenwaiver,
                'EventSmsTemplateId' => $event->eventsmstemplateid,
                'EventDemoWaiver' => $event->eventdemowaiver,
                'TrikeWaiver' => $event->trikewaiver,
                'EventDemoPassengerWaiver' => $event->demopassengerwaiver,
                'TrikePassengerWaiver' => $event->trikepassengerwaiver,
                'EventLiveWireJumpStartWaiver' => $event->eventlivewirejs,
                'EventLiveWireLeadGenWaiver' => $event->eventlivewirelg,
                'EventLiveWireJumpStart' => $event->livewirejumpstart,
                'EventLiveWireJumpStartUnderAgeWaiver' => $event->eventlivewirejsunderage,
                'EventLivewireLeadGen' => $event->livewireleadgen,
                'EventDemoCC' => $event->democc,
                'EventPrSurveyCC' => $event->prsurveycc,
                'EventLeadGenCC' => $event->leadgencc,
                'EventJumpStartCC' => $event->jumpstartcc,
                'EventLiveWireJumpStartCC' => $event->livewirejumpstartcc,
                'EventLiveWireLeadGenCC' => $event->livewireleadgencc,
                'TrikeTrainingTime' => $event->trikewait,
                'EventDemoPassengerWaiver2' => $event->eventpassengerwaiver2,
                'EventDemoWaiver2' => $event->eventdemowaiver2,
                'EventPhotoApp' => $event->photoapp,
                'EventPhotoAppEmail' => $event->photoappemail,
                'EventPhotoAppCC' => $event->photoappcc,
                'EventLeadGenSurvey' => $event->leadgensurvey,
                'EventDemoSurvey' => $event->demosurvey,
                'EventPostRideSurvey' => $event->postridesurvey,
                'EventJumpStartSurvey' => $event->jumpstartsurvey,
                'EventBikesAndTimes' => $event->eventbikesandtimes,
                'registrationsurveyid' => $event->registrationsurveyid,
                'registrationsuccessfulemailtemplate' => $event->registrationsuccessfulemailtemplate,
                'alloweventpreregistrations' => $event->alloweventpreregistrations,
                'remindertemplateemailtemplate' => $event->remindertemplateemailtemplate,
                'remindertemplate2emailtemplate' => $event->remindertemplate2emailtemplate,
                'additionaldetails' => $event->additionaldetails,
                'eventregistrationdeadlinePST' => $eventDeadline,
                'eventreminderdate1' => $event->eventreminderdate1,
                'eventreminderdate2' => $event->eventreminderdate2,
                'EventGuardianWaiver' => $event->eventguardianwaiver,
                'Eventwaitlisttemplateemailtemplate' => $event->waitlisttemplateemailtemplate,
                'EventPreRegistrationEmailQty' => $event->eventpreregistrationsuccessemailqty,
            ];
        }

        // Pass lists mapped to correct property names for select inputs
        $users = User::orderBy('username')->get()->map(function ($u) {
            return (object)[
                'UserID' => $u->userid,
                'UserName' => $u->username
            ];
        });

        $countries = Country::orderBy('countryname')->get()->map(function ($c) {
            return (object)[
                'CountryID' => $c->countryid,
                'CountryName' => $c->countryname
            ];
        });

        $dealers = Dealer::orderBy('dealername')->get()->map(function ($d) {
            return (object)[
                'DealerID' => $d->dealerid,
                'DealerName' => $d->dealername
            ];
        });

        $waivers = Legal::orderBy('legalname')->get()->map(function ($l) {
            return (object)[
                'WaiverID' => $l->legalid,
                'WaiverName' => $l->legalname
            ];
        });

        $trucks = Truck::orderBy('truckname')->get()->map(function ($t) {
            return (object)[
                'TruckID' => $t->truckid,
                'TruckName' => $t->truckname
            ];
        });

        $surveys = Survey::orderBy('surveyname')->get()->map(function ($s) {
            return (object)[
                'SurveyID' => $s->surveyid,
                'SurveyName' => $s->surveyname
            ];
        });

        $emailTemplates = EmailTemplate::orderBy('emailsubj')->get()->map(function ($e) {
            return (object)[
                'TemplateID' => $e->templateid,
                'TemplateName' => $e->emailsubj
            ];
        });

        $smstemplates = SmsTemplate::orderBy('smssubj')->get()->map(function ($s) {
            return (object)[
                'TemplateID' => $s->templateid,
                'TemplateName' => $s->smssubj
            ];
        });

        return view('backend.manage-events.index', [
            'title' => 'Manage Events',
            'years' => $years,
            'selectedYear' => $selectedYear,
            'events' => (object)[
                'Success' => 1,
                'Events' => $mappedEvents
            ],
            'users' => $users,
            'countries' => $countries,
            'dealers' => $dealers,
            'waivers' => $waivers,
            'trucks' => $trucks,
            'surveys' => $surveys,
            'emailTemplates' => $emailTemplates,
            'smstemplates' => $smstemplates,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::orderBy('username')->get()->map(function ($u) {
            return (object)[
                'UserID' => $u->userid,
                'UserName' => $u->username,
                'UserFullName' => trim($u->firstname . ' ' . $u->lastname) ?: $u->username
            ];
        });

        $countries = Country::orderBy('countryname')->get()->map(function ($c) {
            return (object)[
                'CountryID' => $c->countryid,
                'CountryName' => $c->countryname
            ];
        });

        $dealers = Dealer::orderBy('dealername')->get()->map(function ($d) {
            return (object)[
                'DealerID' => $d->dealerid,
                'DealerName' => $d->dealername,
                'DealerNumber' => $d->dealernumber
            ];
        });

        $waivers = Legal::orderBy('legalname')->get()->map(function ($l) {
            return (object)[
                'WaiverID' => $l->legalid,
                'WaiverName' => $l->legalname
            ];
        });

        $trucks = Truck::orderBy('truckname')->get()->map(function ($t) {
            return (object)[
                'TruckID' => $t->truckid,
                'TruckName' => $t->truckname
            ];
        });

        $surveys = Survey::orderBy('surveyname')->get()->map(function ($s) {
            return (object)[
                'SurveyID' => $s->surveyid,
                'SurveyName' => $s->surveyname
            ];
        });

        $emailTemplates = EmailTemplate::orderBy('emailsubj')->get()->map(function ($e) {
            return (object)[
                'TemplateID' => $e->templateid,
                'TemplateName' => $e->emailsubj
            ];
        });

        return view('backend.manage-events.create', [
            'title' => 'Create New Event',
            'users' => $users,
            'countries' => $countries,
            'dealers' => $dealers,
            'waivers' => $waivers,
            'trucks' => $trucks,
            'surveys' => $surveys,
            'emailTemplates' => $emailTemplates,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EventRequest $request)
    {
        $eventTrike = $request->has('EventTrike') ? 1 : 0;
        $eventPRSurvey = $request->has('EventPRSurvey') ? 1 : 0;
        $eventDemo = $request->has('EventDemo') ? 1 : 0;
        $eventLeadGen = $request->has('EventLeadGen') ? 1 : 0;
        $eventJumpStart = $request->has('EventJumpStart') ? 1 : 0;
        $eventPhotoApp = $request->has('EventPhotoApp') ? 1 : 0;
        $eventBikesAndTimes = $request->has('eventbikesandtimes') ? 1 : 0;
        $enableSms = $request->has('EnableSms') ? 1 : 0;
        $eventLiveWireJumpStart = $request->has('EventLiveWireJumpStart') ? 1 : 0;
        $eventLivewireLeadGen = $request->has('EventLivewireLeadGen') ? 1 : 0;

        $eventStart = !empty($request->input('EventStartDate')) ? date('Y-m-d H:i:s', strtotime($request->input('EventStartDate'))) : null;
        $eventEnd = !empty($request->input('EventEndDate')) ? date('Y-m-d H:i:s', strtotime($request->input('EventEndDate'))) : null;

        $dealers = $request->input('DealerID');
        $eventDealers = is_array($dealers) ? serialize($dealers) : 0;

        $trucks = $request->input('TruckID');
        $eventTrucks = is_array($trucks) ? serialize($trucks) : null;

        $users = $request->input('UserID');
        $eventUsers = is_array($users) ? serialize($users) : null;

        $event = Event::create([
            'eventname' => $request->input('EventName'),
            'eventcountry' => $request->input('EventCountry'),
            'clientid' => 1,
            'eventaddress' => '',
            'eventbikesandtimes' => $eventBikesAndTimes,
            'eventstart' => $eventStart,
            'eventend' => $eventEnd,
            'eventwelcomeemail' => $request->input('EventWelcomeEmail') ?: null,
            'eventscheduledemail' => $request->input('EventScheduledEmail') ?: null,
            'eventtyemail' => $request->input('EventTyEmail') ?: null,
            'eventpremail' => $request->input('EventPrEmail') ?: null,
            'eventsalesemail' => $request->input('EventSalesEmail') ?: null,
            'eventcampaigncode' => $request->input('EventCampaignCode'),
            'eventwebsite' => $request->input('EventWebsite'),
            'eventdealers' => $eventDealers,
            'eventusersblob' => $eventUsers,
            'eventstrucksblob' => $eventTrucks,
            'demopassengerwaiver' => $request->input('EventDemoPassengerWaiver') ?: null,
            'trikepassengerwaiver' => $request->input('TrikePassengerWaiver') ?: null,
            'democc' => '',
            'prsurveycc' => '',
            'leadgencc' => '',
            'jumpstartcc' => '',
            'livewirejumpstartcc' => '',
            'livewireleadgencc' => '',
            'eventpreregistrationsuccessemailqty' => 0,
            'eventpreregistrationsuccessemailqtydate' => null,
            'eventpassengerwaiver2' => $request->input('EventDemoPassengerWaiver2') ?: null,
            'eventguardianwaiver' => $request->input('EventGuardianWaiver') ?: null,
            'eventdemowaiver2' => $request->input('EventDemoWaiver2') ?: null,
            'eventsocial' => null,
            'eventphone' => null,
            'eventtrike' => $eventTrike,
            'eventprsurvey' => $eventPRSurvey,
            'eventdemo' => $eventDemo,
            'eventleadgen' => $eventLeadGen,
            'enablesms' => $enableSms,
            'eventjumpstart' => $eventJumpStart,
            'photoapp' => $eventPhotoApp,
            'photoappemail' => $request->input('EventPhotoAppEmail') ?: null,
            'eventjumpstartwaiver' => $request->input('EventJumpStartWaiver') ?: null,
            'eventjumpstartwaiverunderage' => $request->input('EventJumpStartWaiverUnderAge') ?: null,
            'eventleadgenwaiver' => $request->input('EventLeadGenWaiver') ?: null,
            'eventsmstemplateid' => $request->input('EventSmsTemplateId') ?: null,
            'eventdemowaiver' => $request->input('EventDemoWaiver') ?: null,
            'trikewaiver' => $request->input('TrikeWaiver') ?: null,
            'eventlivewirejs' => $request->input('EventLiveWireJumpStartWaiver') ?: null,
            'livewireleadgen' => $eventLivewireLeadGen,
            'eventlivewirejsunderage' => $request->input('EventLiveWireJumpStartUnderAgeWaiver') ?: null,
            'eventlivewirelg' => $request->input('EventLiveWireLeadGenWaiver') ?: null,
            'eventmanager' => '',
            'photoappcc' => null,
            'leadgensurvey' => $request->input('leadgensurvey') ?: null,
            'demosurvey' => $request->input('demosurvey') ?: null,
            'postridesurvey' => $request->input('postridesurvey') ?: null,
            'jumpstartsurvey' => $request->input('jumpstartsurvey') ?: null,
            'registrationsuccessfulemailtemplate' => $request->input('registrationsuccessfulemailtemplate') ?: 0,
            'waitlisttemplateemailtemplate' => $request->input('waitlisttemplateemailtemplate') ?: 0,
            'additionaldetails' => $request->input('additionaldetails'),
            'remindertemplateemailtemplate' => $request->input('remindertemplateemailtemplate') ?: 0,
            'remindertemplate2emailtemplate' => $request->input('remindertemplate2emailtemplate') ?: 0,
        ]);

        if (is_array($users)) {
            $userModels = User::whereIn('userid', $users)->get();
            foreach ($userModels as $u) {
                $userIDs = [];
                if (!empty($u->allowevents)) {
                    $unserialized = @unserialize($u->allowevents);
                    if (is_array($unserialized)) {
                        $userIDs = $unserialized;
                    }
                }
                $userIDs[] = $event->eventid;
                $u->update(['allowevents' => serialize($userIDs)]);
            }
        }

        return redirect()->route('manage-events.index')->with('msg', 'The Event has been created successfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EventRequest $request, $id)
    {
        $event = Event::findOrFail($id);

        $eventTrike = $request->has('EventTrike') ? 1 : 0;
        $eventPRSurvey = $request->has('EventPRSurvey') ? 1 : 0;
        $eventDemo = $request->has('EventDemo') ? 1 : 0;
        $eventLeadGen = $request->has('EventLeadGen') ? 1 : 0;
        $eventJumpStart = $request->has('EventJumpStart') ? 1 : 0;
        $eventPhotoApp = $request->has('EventPhotoApp') ? 1 : 0;
        $eventBikesAndTimes = $request->has('eventbikesandtimes') ? 1 : 0;
        $enableSms = $request->has('EnableSms') ? 1 : 0;
        $eventLiveWireJumpStart = $request->has('EventLiveWireJumpStart') ? 1 : 0;
        $eventLivewireLeadGen = $request->has('EventLivewireLeadGen') ? 1 : 0;

        $eventStart = !empty($request->input('EventStartDate')) ? date('Y-m-d H:i:s', strtotime($request->input('EventStartDate'))) : null;
        $eventEnd = !empty($request->input('EventEndDate')) ? date('Y-m-d H:i:s', strtotime($request->input('EventEndDate'))) : null;

        $eventReminderTemp1 = !empty($request->input('EventReminderTemp1')) ? date('Y-m-d', strtotime($request->input('EventReminderTemp1'))) : null;
        $eventReminderTemp2 = !empty($request->input('EventReminderTemp2')) ? date('Y-m-d', strtotime($request->input('EventReminderTemp2'))) : null;

        $eventRegistrationDeadlinePSTTemp1 = null;
        if (!empty($request->input('EventRegistrationDeadlinePSTTemp1'))) {
            $defaultTimezone = date_default_timezone_get();
            date_default_timezone_set('America/Los_Angeles');
            $eventRegistrationDeadlinePSTTemp1 = date('Y-m-d H:i:s', strtotime($request->input('EventRegistrationDeadlinePSTTemp1')));
            date_default_timezone_set($defaultTimezone);
        }

        $qty = $request->input('EventPreRegistrationEmailQty', 0);
        if ($event->eventpreregistrationsuccessemailqty != $qty) {
            $qtyDate = now()->toDateTimeString();
        } else {
            $qtyDate = $event->eventpreregistrationsuccessemailqtydate;
        }

        $dealers = $request->input('DealerID');
        $eventDealers = is_array($dealers) ? serialize($dealers) : 0;

        $trucks = $request->input('TruckID');
        $eventTrucks = is_array($trucks) ? serialize($trucks) : null;

        $users = $request->input('UserID');
        $eventUsers = is_array($users) ? serialize($users) : null;

        $event->update([
            'eventname' => $request->input('EventName'),
            'eventcountry' => $request->input('EventCountry'),
            'clientid' => 1,
            'eventaddress' => '',
            'eventbikesandtimes' => $eventBikesAndTimes,
            'eventstart' => $eventStart,
            'eventend' => $eventEnd,
            'eventwelcomeemail' => $request->input('EventWelcomeEmail'),
            'eventscheduledemail' => $request->input('EventScheduledEmail'),
            'eventtyemail' => $request->input('EventTyEmail'),
            'eventpremail' => $request->input('EventPrEmail'),
            'eventsalesemail' => $request->input('EventSalesEmail'),
            'eventcampaigncode' => $request->input('EventCampaignCode'),
            'eventwebsite' => $request->input('EventWebsite'),
            'eventdealers' => $eventDealers,
            'eventusersblob' => $eventUsers,
            'eventstrucksblob' => $eventTrucks,
            'demopassengerwaiver' => $request->input('EventDemoPassengerWaiver', 0),
            'trikepassengerwaiver' => $request->input('TrikePassengerWaiver', 0),
            'democc' => '',
            'prsurveycc' => '',
            'leadgencc' => '',
            'jumpstartcc' => '',
            'livewirejumpstartcc' => '',
            'livewireleadgencc' => '',
            'eventpreregistrationsuccessemailqty' => $qty,
            'eventpreregistrationsuccessemailqtydate' => $qtyDate,
            'eventpassengerwaiver2' => $request->input('EventDemoPassengerWaiver2'),
            'eventguardianwaiver' => $request->input('EventGuardianWaiver'),
            'eventdemowaiver2' => $request->input('EventDemoWaiver2'),
            'eventsocial' => null,
            'eventphone' => null,
            'eventtrike' => $eventTrike,
            'eventprsurvey' => $eventPRSurvey,
            'eventdemo' => $eventDemo,
            'eventleadgen' => $eventLeadGen,
            'enablesms' => $enableSms,
            'eventjumpstart' => $eventJumpStart,
            'photoapp' => $eventPhotoApp,
            'photoappemail' => $request->input('EventPhotoAppEmail'),
            'eventjumpstartwaiver' => $request->input('EventJumpStartWaiver'),
            'eventjumpstartwaiverunderage' => $request->input('EventJumpStartWaiverUnderAge'),
            'eventleadgenwaiver' => $request->input('EventLeadGenWaiver'),
            'eventsmstemplateid' => $request->input('EventSmsTemplateId'),
            'eventdemowaiver' => $request->input('EventDemoWaiver'),
            'trikewaiver' => $request->input('TrikeWaiver'),
            'eventlivewirejs' => $request->input('EventLiveWireJumpStartWaiver'),
            'livewireleadgen' => $eventLivewireLeadGen,
            'eventlivewirejsunderage' => $request->input('EventLiveWireJumpStartUnderAgeWaiver'),
            'eventlivewirelg' => $request->input('EventLiveWireLeadGenWaiver'),
            'eventmanager' => '',
            'photoappcc' => null,
            'leadgensurvey' => $request->input('leadgensurvey'),
            'demosurvey' => $request->input('demosurvey'),
            'postridesurvey' => $request->input('postridesurvey'),
            'jumpstartsurvey' => $request->input('jumpstartsurvey'),
            'registrationsuccessfulemailtemplate' => $request->input('registrationsuccessfulemailtemplate'),
            'waitlisttemplateemailtemplate' => $request->input('waitlisttemplateemailtemplate'),
            'additionaldetails' => $request->input('additionaldetails'),
            'remindertemplateemailtemplate' => $request->input('remindertemplateemailtemplate'),
            'remindertemplate2emailtemplate' => $request->input('remindertemplate2emailtemplate'),
            'eventregistrationdeadlinePST' => $eventRegistrationDeadlinePSTTemp1,
            'eventreminderdate2' => $eventReminderTemp2,
            'eventreminderdate1' => $eventReminderTemp1,
            'alloweventpreregistrations' => $request->input('alloweventpreregistrations'),
            'registrationsurveyid' => $request->input('registrationsurveyid'),
        ]);

        return redirect()->back()->with('msg', 'The Event has been updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EventRequest $request, $id)
    {
        $event = Event::findOrFail($id);
        $event->delete();

        return redirect()->back()->with('msg', 'The Event has been deleted successfully');
    }
}
