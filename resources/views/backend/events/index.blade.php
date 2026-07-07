@extends('layouts.backend.app')
@section('title', $title)
@section('content')
<!-- content @s -->
<div class="main-content">
    <!-- Content Header section -->
    @include('layouts.backend.content_header', compact('title'))

    @if(session('status') == 'error')
    <div class="dx-warning">
        <div>
            <p>{!! session('msg') !!}</p>
        </div>
    </div>
    @elseif(session('status') == 'success')
    <div class="dx-success">
        <div>
            <p>{!! session('msg') !!}</p>
        </div>
    </div>
    @endif

    <div class="row">
        <div class="col-md-2">
            <div class="form-group">
                <label class="control-label">Choose a year:</label>
                <form method="get" action="{{ route('manage-events.index') }}" id="YearFilterForm">
                    <script type="text/javascript">
                        function YearChange() {
                            $("#YearFilterForm").submit();
                        }
                    </script>
                    <select onchange="YearChange();" class="form-control" id="EventYear" name="EventYear">
                        <option value="">Select Year</option>
                        @foreach($years as $year)
                        <option @if($year->YearID == $selectedYear) selected="selected" @endif
                            value="{{ $year->YearID }}">{{ $year->YearName }}</option>
                        @endforeach
                    </select>
                </form>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-6">
            <!-- Multi Report -->
            <form id="MultiReportForm" action="{{ url('Action.php') }}">
                <input type="hidden" name="ids" value="">
            </form>
            <script type="text/javascript">
                $(document).ready(function() {

                });
            </script>
            <a href="javascript:;" style="display:none;width: 200px !important;" class="btn btn-info btn-md btnTopCus"
                id="MultiReport">View Multi-Report</a>
        </div>
        <div class="col-xs-6 text-right">
            <a class="btn btn-secondary btn-md btnTopCus" href="{{ route('manage-events.create') }}">
            <span class=""> <i class="fa fa-plus"></i> Add Event</span>
            </a>
        </div>
    </div>

    @if(isset($events->Events) && $events->Success == 1)
        @foreach ($events->Events as $rkey => $revent)
            @php $tableID = str_replace(' ', '_', trim($rkey)); @endphp
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">{{ $rkey }}</h3>
                    <div class="panel-options">
                        <a href="#" data-toggle="panel">
                        <span class="collapse-icon">&ndash;</span>
                        <span class="expand-icon">+</span>
                        </a>
                    </div>
                </div>
                <div class="panel-body">
                    <script type="text/javascript">
                        jQuery(document).ready(function($) {
                          $("#Region_{{ $tableID }}").click(function() {
                            $(".{{ $tableID }}").prop('checked', false);
                            if (this.checked) {
                              $(".{{ $tableID }}").prop('checked', true);
                            }
                          });
                        });
                    </script>
                    <table class="table table-bordered table-striped" id="brazilTable-{{ $tableID }}">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Event Name</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Event ID</th>
                                <th>Truck</th>
                                <th style="vertical-align: text-top;" width="250">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="middle-align">
                            @foreach ($revent as $key => $event)
                            <tr>
                                <td>{{ $event->EventID }}</td>
                                <td>{{ $event->EventName }}</td>
                                <td>{{ date("m-d-Y", strtotime($event->EventStartDate)) }}</td>
                                <td>{{ date("m-d-Y", strtotime($event->EventEndDate)) }}</td>
                                <td>{{ $event->EventCampaignCode }}</td>
                                <td>{{ $event->EventTruck }}</td>
                                <td>
                                    <input type="hidden" id="EventPhotoAppEmail{{ $event->EventID }}"
                                        value="{{ $event->EventPhotoAppEmail }}">
                                    <input type="hidden" id="EventWelcomeEmail{{ $event->EventID }}"
                                        value="{{ $event->EventWelcomeEmail }}">
                                    <input type="hidden" id="EventScheduledEmail{{ $event->EventID }}"
                                        value="{{ $event->EventScheduledEmail }}">
                                    <input type="hidden" id="EventTyEmail{{ $event->EventID }}"
                                        value="{{ $event->EventTyEmail }}">
                                    <input type="hidden" id="EventPrEmail{{ $event->EventID }}"
                                        value="{{ $event->EventPrEmail }}">
                                    <input type="hidden" id="EventSalesEmail{{ $event->EventID }}"
                                        value="{{ $event->EventSalesEmail }}">
                                    <input type="hidden" id="EventCountry{{ $event->EventID }}"
                                        value="{{ $event->EventCountry }}">
                                    <input type="hidden" id="EventBikesAndTimes{{ $event->EventID }}"
                                        value="{{ $event->EventBikesAndTimes }}">
                                    <input type="hidden" id="EventLeadGenSurvey{{ $event->EventID }}"
                                        value="{{ $event->EventLeadGenSurvey }}">
                                    <input type="hidden" id="EventDemoSurvey{{ $event->EventID }}"
                                        value="{{ $event->EventDemoSurvey }}">
                                    <input type="hidden" id="EventPostRideSurvey{{ $event->EventID }}"
                                        value="{{ $event->EventPostRideSurvey }}">
                                    <input type="hidden" id="EventJumpStartSurvey{{ $event->EventID }}"
                                        value="{{ $event->EventJumpStartSurvey }}">
                                    <input type="hidden" id="EventCampaignCode{{ $event->EventID }}"
                                        value="{{ $event->EventCampaignCode }}">
                                    <input type="hidden" id="EventWebsite{{ $event->EventID }}"
                                        value="{{ $event->EventWebsite }}">
                                    <input type="hidden" id="TrikeTrainingTime{{ $event->EventID }}"
                                        value="{{ $event->TrikeTrainingTime }}">
                                    <input type="hidden" id="EventStartDate{{ $event->EventID }}"
                                        value="{{ $event->EventStartDate }}">
                                    <input type="hidden" id="EventEndDate{{ $event->EventID }}"
                                        value="{{ $event->EventEndDate }}">
                                    <input type="hidden" id="EventWaiverID{{ $event->EventID }}"
                                        value="{{ $event->EventWaiverID }}">
                                    <input type="hidden" id="EventJumpStart{{ $event->EventID }}"
                                        value="{{ $event->EventJumpStart }}">
                                    <input type="hidden" id="EventLeadGen{{ $event->EventID }}"
                                        value="{{ $event->EventLeadGen }}">
                                    <input type="hidden" id="EnableSms{{ $event->EventID }}"
                                        value="{{ $event->EnableSms }}">
                                    <input type="hidden" id="EventDemo{{ $event->EventID }}"
                                        value="{{ $event->EventDemo }}">
                                    <input type="hidden" id="EventPRSurvey{{ $event->EventID }}"
                                        value="{{ $event->EventPRSurvey }}">
                                    <input type="hidden" id="EventTrike{{ $event->EventID }}"
                                        value="{{ $event->EventTrike }}">
                                    <input type="hidden" id="EventPhotoApp{{ $event->EventID }}"
                                        value="{{ $event->EventPhotoApp }}">
                                    <input type="hidden" id="PhotoAppCampaignCode{{ $event->EventID }}"
                                        value="{{ $event->EventPhotoAppCC }}">
                                    <input type="hidden" id="EventLiveWireJumpStart{{ $event->EventID }}"
                                        value="{{ $event->EventLiveWireJumpStart }}">
                                    <input type="hidden" id="EventLivewireLeadGen{{ $event->EventID }}"
                                        value="{{ $event->EventLivewireLeadGen }}">
                                    <input type="hidden" id="EventJumpStartWaiver{{ $event->EventID }}"
                                        value="{{ $event->EventJumpStartWaiver }}">
                                    <input type="hidden" id="EventJumpStartWaiverUnderAge{{ $event->EventID }}"
                                        value="{{ $event->EventJumpStartWaiverUnderAge }}">
                                    <input type="hidden" id="EventLeadGenWaiver{{ $event->EventID }}"
                                        value="{{ $event->EventLeadGenWaiver }}">
                                    <input type="hidden" id="EventSmsTemplateId{{ $event->EventID }}"
                                        value="{{ $event->EventSmsTemplateId }}">
                                    <input type="hidden" id="EventDemoWaiver_{{ $event->EventID }}"
                                        value="{{ $event->EventDemoWaiver }}">
                                    <input type="hidden" id="EventGuardianWaiver{{ $event->EventID }}"
                                        value="{{ $event->EventGuardianWaiver }}">
                                    <input type="hidden" id="EventDemoWaiver2{{ $event->EventID }}"
                                        value="{{ $event->EventDemoWaiver2 }}">
                                    <input type="hidden" id="EventDemoPassengerWaiver_{{ $event->EventID }}"
                                        value="{{ $event->EventDemoPassengerWaiver }}">
                                    <input type="hidden" id="EventDemoPassengerWaiver2{{ $event->EventID }}"
                                        value="{{ $event->EventDemoPassengerWaiver2 }}">
                                    <input type="hidden" id="TrikeWaiver{{ $event->EventID }}"
                                        value="{{ $event->TrikeWaiver }}">
                                    <input type="hidden" id="TrikePassengerWaiver{{ $event->EventID }}"
                                        value="{{ $event->TrikePassengerWaiver }}">

                                    <input type="hidden" id="Eventalloweventpreregistrations{{ $event->EventID }}"
                                        value="{{ $event->alloweventpreregistrations }}">
                                    <input type="hidden" id="Eventregistrationsurveyid{{ $event->EventID }}"
                                        value="{{ $event->registrationsurveyid }}">
                                    <input type="hidden" id="Eventregistrationsuccessfulemailtemplate{{ $event->EventID }}"
                                        value="{{ $event->registrationsuccessfulemailtemplate }}">
                                    <input type="hidden" id="Eventwaitlisttemplateemailtemplate{{ $event->EventID }}"
                                        value="{{ $event->Eventwaitlisttemplateemailtemplate }}">
                                    <input type="hidden" id="EventPreRegistrationEmailQty{{ $event->EventID }}"
                                        value="{{ $event->EventPreRegistrationEmailQty }}">
                                    <input type="hidden" id="Eventremindertemplateemailtemplate{{ $event->EventID }}"
                                        value="{{ $event->remindertemplateemailtemplate }}">
                                    <input type="hidden" id="Eventremindertemplate2emailtemplate{{ $event->EventID }}"
                                        value="{{ $event->remindertemplate2emailtemplate }}">
                                    <input type="hidden" id="Eventadditionaldetails{{ $event->EventID }}"
                                        value="{{ $event->additionaldetails }}">
                                    <input type="hidden" id="EventRegistrationDeadlinePST{{ $event->EventID }}"
                                        value="{{ $event->eventregistrationdeadlinePST }}">
                                    <input type="hidden" id="Eventreminderdate1{{ $event->EventID }}"
                                        value="{{ $event->eventreminderdate1 }}">
                                    <input type="hidden" id="Eventreminderdate2{{ $event->EventID }}"
                                        value="{{ $event->eventreminderdate2 }}">
                                    <input type="hidden" id="EventLiveWireJumpStartWaiver{{ $event->EventID }}"
                                        value="{{ $event->EventLiveWireJumpStartWaiver }}">
                                    <input type="hidden" id="EventLiveWireJumpStartUnderAgeWaiver{{ $event->EventID }}"
                                        value="{{ $event->EventLiveWireJumpStartUnderAgeWaiver }}">
                                    <input type="hidden" id="EventLiveWireLeadGenWaiver{{ $event->EventID }}"
                                        value="{{ $event->EventLiveWireLeadGenWaiver }}">
                                    <input type="hidden" id="EventTruckBlob{{ $event->EventID }}"
                                        value='{!! $event->EventTruckBlob !!}'>
                                    <input type="hidden" id="EventDealers{{ $event->EventID }}"
                                        value='{!! $event->EventDealers !!}'>

                                    @if(auth()->user()?->userlevel != 7 && auth()->user()?->userlevel != 3 && auth()->user()?->userlevel != 5 && auth()->user()?->userlevel != 9)
                                    <a class="btn btn-secondary btn-sm icon-left edit" data-toggle="modal"
                                        data-id="{{ $event->EventID }}">Edit</a>
                                    @endif

                                    <a href="{{ route('event.report.show', base64_encode($event->EventID)) }}" class="btn btn-info btn-sm icon-left">
                                    <i class="fa-bar-chart"></i> Reports
                                    </a>

                                    @if(auth()->user()?->userlevel == 1)
                                        <a href="javascript:;" id="{{ $event->EventID }}"
                                            onclick="jQuery('#event-modal-delete').modal('show');" class="btn btn-danger btn-sm">
                                        <i class="icon-white icon-heart"></i> Delete
                                        </a>
                                    @endif
                                    @php
                                    $atvlink = '';
                                    if (($rkey == 'ATV') && ($event->alloweventpreregistrations == 1)) {
                                        $atvlink = 'atv/?eventid=' . base64_encode($event->EventID);
                                    } else if ($rkey != 'ATV' && $event->alloweventpreregistrations == 1) {
                                        $atvlink = 'register/?eventid=' . base64_encode($event->EventID);
                                    }
                                    @endphp
                                    <input type="hidden" id="link{{ $event->EventID }}" value="{{ $atvlink }}">
                                    <input type="hidden" id="region{{ $event->EventID }}" value="{{ $tableID }}">
                                    <input type="hidden" id="eventid_base64{{ $event->EventID }}"
                                        value="{{ base64_encode($event->EventID) }}">
                                </td>
                            </tr>
                            @endforeach
                            <script>
                                $(document).ready(function() {
                                    $(".edit").click(function() {
                                        $('#event-modal').modal('show');
                                    });

                                    $("#brazilTable-{{ $tableID }}").dataTable({
                                        "paging": false,
                                        "ordering": false,
                                        "info": false,
                                        "sDom": '<"top"i>rt<"bottom"i><"clear">',
                                        language: {
                                            searchPlaceholder: "Search records"
                                        }
                                    }).yadcf([
                                        {
                                            column_number: 0,
                                            filter_type: 'text'
                                        },
                                        {
                                            column_number: 1,
                                            filter_type: 'text'
                                        },
                                        {
                                            column_number: 2,
                                            filter_type: 'text'
                                        },
                                        {
                                            column_number: 3,
                                            filter_type: 'text'
                                        },
                                        {
                                            column_number: 4,
                                            filter_type: 'text'
                                        },
                                        {
                                            column_number: 5,
                                            filter_type: 'text'
                                        },
                                        {
                                            column_number: 6,
                                            filter_type: null
                                        },
                                    ]);
                                    $('.dataTables_filter input').attr("placeholder", "enter seach terms here");
                                });
                            </script>
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach
    @endif

    <!-- Main Footer -->
    @include('layouts.backend.footer')

</div>
<!-- content @e -->

<script>
    $(document).ready(function() {
        var GEAdddress = '';
        $("button.btn-info").click(function() {
            if ($(this).text() == "Create") {
                $("#EventForm").submit();
            } else if ($(this).text() == "Save Changes") {
                var form = $("#EventEditForm");
                if (form.valid()) {
                    form.submit();
                } else {
                    return false;
                }
            } else if ($(this).text() == "Delete") {
                $("#EventDelete").submit();
            }
        });

        $("a.btn-danger").click(function() {
            var eventId = $(this).attr('id') || $(this).parent().prev().prev().prev().prev().prev().prev().text();
            $("#DeleteEventID").val(eventId);
            $("#EventDelete").attr("action", "{{ route('manage-events.index') }}/" + eventId);
        });

        $("a.btn-secondary").click(function() {
            var TmpEventId = $(this).parent().prev().prev().prev().prev().prev().prev().text();
            $("#EventEditForm").attr("action", "{{ route('manage-events.index') }}/" + TmpEventId);
            $("#EventNameEdit").val($(this).parent().prev().prev().prev().prev().prev().text());
            $("#EventCountryEdit").select2("val", $("#EventCountry" + TmpEventId).val());

            $('#EventCountryEdit').trigger('change');

            $("#EventCampaignCodeEdit").val($("#EventCampaignCode" + TmpEventId).val());
            $("#EventWebsiteEdit").val($("#EventWebsite" + TmpEventId).val());
            $("#TrikeTrainingTimeEdit").val($("#TrikeTrainingTime" + TmpEventId).val());

            $("#EventJumpStartWaiverDiv").hide();
            $("#EventJumpStartWaiverUnderAgeDiv").hide();
            $('#EventJumpStart').prop("checked", false);
            if ($("#EventJumpStart" + TmpEventId).val() == 1) {
                $('#EventJumpStart').prop("checked", true);
                $("#EventJumpStartWaiverDiv").show();
                $("#EventJumpStartWaiverUnderAgeDiv").show();

                var jsWaiver = $("#EventJumpStartWaiver" + TmpEventId).val();
                $("#EventJumpStartWaiver").data("selectBox-selectBoxIt").selectOption(jsWaiver == 0 || !jsWaiver ? "" : jsWaiver);

                var jsWaiverUnderAge = $("#EventJumpStartWaiverUnderAge" + TmpEventId).val();
                $("#EventJumpStartWaiverUnderAge").data("selectBox-selectBoxIt").selectOption(jsWaiverUnderAge == 0 || !jsWaiverUnderAge ? "" : jsWaiverUnderAge);
            }

            if ($("#Eventalloweventpreregistrations" + TmpEventId).val() == 1) {
                $('#alloweventpreregistrations').attr("checked", true);
                $("#getLinkDiv").show();

                $("#eventwalletpassterms").val($("#Eventeventwalletpassterms" + TmpEventId).val());

                if (!$("#Eventregistrationsurveyid" + TmpEventId).val()) {
                    $("#Eventregistrationsurveyid" + TmpEventId).val($("#registrationsurveyid").val());
                }

                $("#EventPreRegistrationEmailQty").val($("#EventPreRegistrationEmailQty" + TmpEventId).val());
                var regSurveyId = $("#Eventregistrationsurveyid" + TmpEventId).val();
                $("#registrationsurveyid").data("selectBox-selectBoxIt").selectOption(regSurveyId == 0 || !regSurveyId ? "" : regSurveyId);

                var regSuccessEmail = $("#Eventregistrationsuccessfulemailtemplate" + TmpEventId).val();
                $("#registrationsuccessfulemailtemplate").data("selectBox-selectBoxIt").selectOption(regSuccessEmail == 0 || !regSuccessEmail ? "" : regSuccessEmail);

                var waitlistTemplate = $("#Eventwaitlisttemplateemailtemplate" + TmpEventId).val();
                $("#waitlisttemplateemailtemplateEdit").data("selectBox-selectBoxIt").selectOption(waitlistTemplate == 0 || !waitlistTemplate ? "" : waitlistTemplate);

                var reminderTemplate = $("#Eventremindertemplateemailtemplate" + TmpEventId).val();
                $("#remindertemplateemailtemplate").data("selectBox-selectBoxIt").selectOption(reminderTemplate == 0 || !reminderTemplate ? "" : reminderTemplate);

                var reminderTemplate2 = $("#Eventremindertemplate2emailtemplate" + TmpEventId).val();
                $("#remindertemplate2emailtemplate").data("selectBox-selectBoxIt").selectOption(reminderTemplate2 == 0 || !reminderTemplate2 ? "" : reminderTemplate2);

                $("#additionaldetails").text($("#Eventadditionaldetails" + TmpEventId).val());

                $("#RegistrationSurveyIDDiv").show();
                $("#EventWalletPassDiv").show();
                $("#RegistrationSuccessfulEmailDiv").show();
                $("#AdditionalDetailsIDDiv").show();
                $("#ReminderTemplateDiv").show();
                $("#ReminderTemplate2Div").show();
                $("#EventReminderTemp1Div").show();
                $("#EventReminderTemp2Div").show();
                $("#EventPreRegistrationEmailQtyEdit").show();
                $("#WaitlistTemplateDiv").show();
            } else {
                $('#alloweventpreregistrations').attr("checked", false);
                $("#getLinkDiv").hide();
                $("#RegistrationSurveyIDDiv").hide();
                $("#EventWalletPassDiv").hide();
                $("#RegistrationSuccessfulEmailDiv").hide();
                $("#AdditionalDetailsIDDiv").hide();
                $("#ReminderTemplateDiv").hide();
                $("#ReminderTemplate2Div").hide();
                $("#EventReminderTemp1Div").hide();
                $("#EventReminderTemp2Div").hide();
                $("#EventPreRegistrationEmailQtyEdit").hide();
                $("#WaitlistTemplateDiv").hide();
            }

            $('#EventLeadGen').prop('checked', false);
            $("#EventLeadGenWaiverDiv").hide();
            if ($("#EventLeadGen" + TmpEventId).val() == 1) {
                $('#EventLeadGen').prop('checked', true);
                $("#EventLeadGenWaiverDiv").show();
                var lgWaiver = $("#EventLeadGenWaiver" + TmpEventId).val();
                $("#EventLeadGenWaiver").data("selectBox-selectBoxIt").selectOption(lgWaiver == 0 || !lgWaiver ? "" : lgWaiver);
            }

            $('#EnableSms').prop('checked', false);
            $("#eventSmsTemplateIdDiv").hide();
            if ($("#EnableSms" + TmpEventId).val() == 1) {
                $('#EnableSms').prop('checked', true);
                $("#eventSmsTemplateIdDiv").show();
                var smsTemplateId = $("#EventSmsTemplateId" + TmpEventId).val();
                $("#EventSmsTemplateId").data("selectBox-selectBoxIt").selectOption(smsTemplateId == 0 || !smsTemplateId ? "" : smsTemplateId);
            }

            $('#EventDemo').prop("checked", false);
            $("#EventDemoWaiverDiv").hide();
            $("#EventDemoWaiverDiv2").hide();
            $("#EventGuardianWaiverDiv").hide();
            $("#EventDemoPassengerWaiverDiv").hide();
            $("#EventDemoPassengerWaiverDiv2").hide();
            if ($("#EventDemo" + TmpEventId).val() == 1) {
                $('#EventDemo').prop("checked", true);

                $("#EventDemoWaiverDiv").show();
                var demoWaiver = $("#EventDemoWaiver_" + TmpEventId).val();
                $("#EventDemoWaiver").data("selectBox-selectBoxIt").selectOption(demoWaiver == 0 || !demoWaiver ? "" : demoWaiver);

                $("#EventDemoWaiverDiv2").show();
                var demoWaiver2 = $("#EventDemoWaiver2" + TmpEventId).val();
                $("#EventDemoWaiver2").data("selectBox-selectBoxIt").selectOption(demoWaiver2 == 0 || !demoWaiver2 ? "" : demoWaiver2);

                $("#EventDemoPassengerWaiverDiv").show();
                var passengerWaiver = $("#EventDemoPassengerWaiver_" + TmpEventId).val();
                $("#EventDemoPassengerWaiver").data("selectBox-selectBoxIt").selectOption(passengerWaiver == 0 || !passengerWaiver ? "" : passengerWaiver);

                $("#EventDemoPassengerWaiverDiv2").show();
                var passengerWaiver2 = $("#EventDemoPassengerWaiver2" + TmpEventId).val();
                $("#EventDemoPassengerWaiver2").data("selectBox-selectBoxIt").selectOption(passengerWaiver2 == 0 || !passengerWaiver2 ? "" : passengerWaiver2);

                $("#EventGuardianWaiverDiv").show();
                var guardianWaiver = $("#EventGuardianWaiver" + TmpEventId).val();
                $("#EventGuardianWaiver").data("selectBox-selectBoxIt").selectOption(guardianWaiver == 0 || !guardianWaiver ? "" : guardianWaiver);
            }

            $('#EventPRSurvey').prop("checked", false);
            $('#EventPhotoApp').prop("checked", false);
            $('#EventTrike').prop("checked", false);

            $("#TrikePassengerWaiverDiv").hide();
            $("#TrikeWaiverDiv").hide();

            if ($("#EventPRSurvey" + TmpEventId).val() == 1) {
                $('#EventPRSurvey').prop("checked", true);
            }
            if ($("#EventPhotoApp" + TmpEventId).val() == 1) {
                $('#EventPhotoApp').prop("checked", true);
            }
            if ($("#EventTrike" + TmpEventId).val() == 1) {
                $('#EventTrike').prop("checked", true);
                $("#TrikeWaiverDiv").show();
                $("#TrikeWaiver").data("selectBox-selectBoxIt").selectOption($("#TrikeWaiver" + TmpEventId).val());

                $("#TrikePassengerWaiverDiv").show();
                $("#TrikePassengerWaiver").data("selectBox-selectBoxIt").selectOption($("#TrikePassengerWaiver" + TmpEventId).val());
            }

            $('#EventLiveWireJumpStart').prop("checked", false);
            $("#EventLiveWireJumpStartDiv").hide();
            $("#EventLiveWireJumpStartUnderAgeDiv").hide();

            if ($("#EventLiveWireJumpStart" + TmpEventId).val() == 1) {
                $('#EventLiveWireJumpStart').prop("checked", true);
                $("#EventLiveWireJumpStartDiv").show();

                $("#EventLiveWireJumpStartWaiver").data("selectBox-selectBoxIt").selectOption($("#EventLiveWireJumpStartWaiver" + TmpEventId).val());

                $("#EventLiveWireJumpStartUnderAgeDiv").show();
                $("#EventLiveWireJumpStartUnderAgeWaiver").data("selectBox-selectBoxIt").selectOption($("#EventLiveWireJumpStartUnderAgeWaiver" + TmpEventId).val());
            }
            $('#EventLivewireLeadGen').prop("checked", false);
            $("#EventLiveWireLeadGenWaiverDiv").hide();

            if ($("#EventLivewireLeadGen" + TmpEventId).val() == 1) {
                $('#EventLivewireLeadGen').prop("checked", true);
                $("#EventLiveWireLeadGenWaiverDiv").show();
                $("#EventLiveWireLeadGenWaiver").data("selectBox-selectBoxIt").selectOption($("#EventLiveWireLeadGenWaiver" + TmpEventId).val());
            }

            //Manage Truck Blob.
            var EventTruckBlobJSON = $("#EventTruckBlob" + TmpEventId).val();
            var EventTruckBlobArray = [];
            if (EventTruckBlobJSON && EventTruckBlobJSON !== '') {
                try {
                    $.each(JSON.parse(EventTruckBlobJSON), function(index, ETBV) {
                        EventTruckBlobArray.push({
                            id: ETBV,
                            text: $('#TruckIDEdit option[value="' + ETBV + '"]').text()
                        });
                    });
                } catch(e) {}
            }
            $("#TruckIDEdit").select2('data', EventTruckBlobArray);

            //Manage Dealer Blob.
            var EventDealersJSON = $("#EventDealers" + TmpEventId).val();
            var EventDealersArray = [];
            if (EventDealersJSON && EventDealersJSON !== '') {
                try {
                    $.each(JSON.parse(EventDealersJSON), function(index, EDV) {
                        EventDealersArray.push({
                            id: EDV,
                            text: $('#DealerIDEdit option[value="' + EDV + '"]').text()
                        });
                    });
                } catch(e) {}
            }
            $("#DealerIDEdit").select2('data', EventDealersArray);

            if (parseInt($("#EventBikesAndTimes" + TmpEventId).val())) {
                $("#eventbikesandtimes").prop('checked', true);
            } else {
                $("#eventbikesandtimes").prop('checked', false);
            }

            var leadGenSurvey = $("#EventLeadGenSurvey" + TmpEventId).val();
            $("#LeadGenSurveyEdit").data("selectBox-selectBoxIt").selectOption(leadGenSurvey == 0 || !leadGenSurvey ? "" : leadGenSurvey);

            var demoSurvey = $("#EventDemoSurvey" + TmpEventId).val();
            $("#DemoSurveyEdit").data("selectBox-selectBoxIt").selectOption(demoSurvey == 0 || !demoSurvey ? "" : demoSurvey);

            var postRideSurvey = $("#EventPostRideSurvey" + TmpEventId).val();
            $("#PostRideSurveyEdit").data("selectBox-selectBoxIt").selectOption(postRideSurvey == 0 || !postRideSurvey ? "" : postRideSurvey);

            var jumpStartSurvey = $("#EventJumpStartSurvey" + TmpEventId).val();
            $("#JumpStartSurveyEdit").data("selectBox-selectBoxIt").selectOption(jumpStartSurvey == 0 || !jumpStartSurvey ? "" : jumpStartSurvey);

            var photoAppEmail = $("#EventPhotoAppEmail" + TmpEventId).val();
            $("#EventPhotoAppEmailEdit").data("selectBox-selectBoxIt").selectOption(photoAppEmail == 0 || !photoAppEmail ? "" : photoAppEmail);

            var welcomeEmail = $("#EventWelcomeEmail" + TmpEventId).val();
            $("#EventWelcomeEmailEdit").data("selectBox-selectBoxIt").selectOption(welcomeEmail == 0 || !welcomeEmail ? "" : welcomeEmail);

            var scheduledEmail = $("#EventScheduledEmail" + TmpEventId).val();
            $("#EventScheduledEmailEdit").data("selectBox-selectBoxIt").selectOption(scheduledEmail == 0 || !scheduledEmail ? "" : scheduledEmail);

            var tyEmail = $("#EventTyEmail" + TmpEventId).val();
            $("#EventTyEmailEdit").data("selectBox-selectBoxIt").selectOption(tyEmail == 0 || !tyEmail ? "" : tyEmail);

            var prEmail = $("#EventPrEmail" + TmpEventId).val();
            $("#EventPrEmailEdit").data("selectBox-selectBoxIt").selectOption(prEmail == 0 || !prEmail ? "" : prEmail);

            var salesEmail = $("#EventSalesEmail" + TmpEventId).val();
            $("#EventSalesEmailEdit").data("selectBox-selectBoxIt").selectOption(salesEmail == 0 || !salesEmail ? "" : salesEmail);

            //Event start date
            var EventStartDate = $("#EventStartDate" + TmpEventId).val();
            var startdateArray = EventStartDate.split(" ");
            var startdate = startdateArray[0].split("-");
            var objDate = new Date(startdate[1] + "/" + startdate[2] + "/" + startdate[0]),
            locale = "en-us",
            month = objDate.toLocaleString(locale, {
                month: "long"
            });
            day = objDate.toLocaleString(locale, {
                weekday: "short"
            });
            var date = objDate.getDate();
            var year = objDate.getFullYear();
            var final_start_date = day + ', ' + date + ' ' + month + ' ' + year;

            //Event end date
            var EventEndDate = $("#EventEndDate" + TmpEventId).val();
            var enddateArray = EventEndDate.split(" ");
            var enddate = enddateArray[0].split("-");
            var objDate = new Date(enddate[1] + "/" + enddate[2] + "/" + enddate[0]),
            locale = "en-us",
            month = objDate.toLocaleString(locale, {
                month: "long"
            });
            day = objDate.toLocaleString(locale, {
            weekday: "short"
            });
            var date = objDate.getDate();
            var year = objDate.getFullYear();
            var final_end_date = day + ', ' + date + ' ' + month + ' ' + year;

            $("#EventStartDateEdit").val(final_start_date);
            $("#EventEndDateEdit").val(final_end_date);

            //Event EventRegistrationDeadlinePST date
            var EventRegistrationDeadlinePST = $("#EventRegistrationDeadlinePST" + TmpEventId).val();
            $("#EventRegistrationDeadlinePSTTemp1").val('');
            if (EventRegistrationDeadlinePST != '') {
                var RegistrationDeadlinePSTArray = EventRegistrationDeadlinePST.split(" ");
                var RegistrationDeadlinePSTdate = RegistrationDeadlinePSTArray[0].split("-");
                var objDate = new Date(RegistrationDeadlinePSTdate[1] + "/" + RegistrationDeadlinePSTdate[2] + "/" + RegistrationDeadlinePSTdate[0]),
                    locale = "en-us",
                    month = objDate.toLocaleString(locale, {
                    month: "long"
                    });
                day = objDate.toLocaleString(locale, {
                    weekday: "short"
                });
                var date = objDate.getDate();
                var year = objDate.getFullYear();
                var final_RegistrationDeadlinePST_date = day + ', ' + date + ' ' + month + ' ' + year + ' ' + RegistrationDeadlinePSTArray[1];
                $("#EventRegistrationDeadlinePSTTemp1").val(final_RegistrationDeadlinePST_date);
            }

            //Event Reminder date
            var EventReminderDate = $("#Eventreminderdate1" + TmpEventId).val();
            if (EventReminderDate != '') {
                var ReminderdateArray = EventReminderDate.split(" ");
                var Reminderdate = ReminderdateArray[0].split("-");
                var objDate = new Date(Reminderdate[1] + "/" + Reminderdate[2] + "/" + Reminderdate[0]),
                    locale = "en-us",
                    month = objDate.toLocaleString(locale, {
                    month: "long"
                    });
                day = objDate.toLocaleString(locale, {
                    weekday: "short"
                });
                var date = objDate.getDate();
                var year = objDate.getFullYear();
                var final_Reminder_date = day + ', ' + date + ' ' + month + ' ' + year;
                $("#EventReminderTemp1").val(final_Reminder_date);
            }

            //Event Reminder date
            var EventReminderDate = $("#Eventreminderdate2" + TmpEventId).val();
            if (EventReminderDate != '') {
                var ReminderdateArray = EventReminderDate.split(" ");
                var Reminderdate = ReminderdateArray[0].split("-");
                var objDate = new Date(Reminderdate[1] + "/" + Reminderdate[2] + "/" + Reminderdate[0]),
                    locale = "en-us",
                    month = objDate.toLocaleString(locale, {
                    month: "long"
                    });
                day = objDate.toLocaleString(locale, {
                    weekday: "short"
                });
                var date = objDate.getDate();
                var year = objDate.getFullYear();
                var final_Reminder_date = day + ', ' + date + ' ' + month + ' ' + year;
                $("#EventReminderTemp2").val(final_Reminder_date);
            }

            $("#EventCountryEdit").val($("#EventCountry" + TmpEventId).val());
            $("#EventEditID").val(TmpEventId);
            $("#getLinkDiv").html(
            '<a href="javascript:;" onclick="get_link_show()" class="btn btn-info btn-sm btn-icon icon-left">                Get link                        </a>'
            );
        });
    });
</script>

<!-- Report Modal -->
<div class="modal fade custom-width" id="event-report-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">The Reports are currently under construction </h4>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade custom-width" id="event-modal-delete" tabindex="-1" role="dialog" aria-labelledby="event-modal-delete-label" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Are you sure? </h4>
            </div>
            <form method="post" action="" id="EventDelete">
                @csrf
                @method('DELETE')
                <input type="hidden" name="DeleteEventID" id="DeleteEventID" value="">
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-info" onclick="jQuery('#EventDelete').submit();">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Get Link Modal -->
<div class="modal fade custom-width" id="get-link-modal" style="height:500px;width:800px;z-index:1111 !important; ">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"></h4>
            </div>
            <strong>
                <div class="modal-body" id="get-link-modal-modal-body"></div>
            </strong>
        </div>
    </div>
</div>

<!-- Event Edit Modal -->
<div class="modal fade custom-width" id="event-modal" tabindex="-1" role="dialog" aria-labelledby="event-modal-edit-label" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" style="width:800px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Edit Event</h4>
            </div>
            <div class="modal-body">
                <form id="EventEditForm" method="post" action="">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="eventName" class="control-label">Event Name</label>
                                <input type="text" class="form-control" id="EventNameEdit" name="EventName" placeholder="">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Event Type</label>
                                <script type="text/javascript">
                                    function get_link_show() {
                                        var EventID = $("#EventEditID").val();
                                        var link = $("#link" + EventID).val();
                                        var linkbase64 = $("#eventid_base64" + EventID).val();
                                        var region = $("#region" + EventID).val();
                                        if (region == 'ATV') {
                                            link = 'atv/?eventid=' + linkbase64;
                                        } else {
                                            link = 'register/?eventid=' + linkbase64;
                                        }
                                        link = 'https://honda.kickstartuser.com/' + link;
                                        $("#get-link-modal-modal-body").text(link);
                                        $("#getLinkDiv").text(link);
                                    }
                                    jQuery(document).ready(function($) {
                                        $("#EventCountryEdit").select2({
                                            placeholder: 'Select Event Type...',
                                            allowClear: true,
                                            minimumResultsForSearch: -1,
                                            formatResult: function(state) {
                                            return '<div style="background:url(http://www.geonames.org/flags/x/' + state.id +
                                                '.gif) no-repeat center center;background-size:100%;display:inline-block;position:relative;width:20px;height:15px;margin-right: 10px;top:2px;"></div>' +
                                                state.text;
                                            }
                                        }).on('select2-open', function() {
                                            $(this).data('select2').results.addClass('overflow-hidden').perfectScrollbar();
                                        });
                                    });
                                </script>
                                <select class="form-control" id="EventCountryEdit" name="EventCountry">
                                    @foreach($countries as $country)
                                    <option value="{{ $country->CountryID }}">{{ $country->CountryName }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="eventName" class="control-label">Custom Event Identifier</label>
                                <input type="text" class="form-control" id="EventCampaignCodeEdit" name="EventCampaignCode"
                                    placeholder="">
                            </div>
                        </div>
                    </div>
                    <script type="text/javascript">
                        jQuery(document).ready(function($) {
                            $("#EventJumpStart").click(function() {
                                if ($(this).prop("checked")) {
                                    $("#EventJumpStartWaiverDiv").show();
                                    $("#EventJumpStartWaiverUnderAgeDiv").show();
                                } else {
                                    $("#EventJumpStartWaiverDiv").hide();
                                    $("#EventJumpStartWaiverUnderAgeDiv").hide();
                                }
                            });

                            $("#EventLeadGen").click(function() {
                                if ($(this).prop("checked")) {
                                    $("#EventLeadGenWaiverDiv").show();
                                } else {
                                    $("#EventLeadGenWaiverDiv").hide();
                                }
                            });

                            $("#EnableSms").click(function() {
                                if ($(this).prop("checked")) {
                                    $("#eventSmsTemplateIdDiv").show();
                                } else {
                                    $("#eventSmsTemplateIdDiv").hide();
                                }
                            });

                            $("#EventDemo").click(function() {
                                if ($(this).prop("checked")) {
                                    $("#EventDemoWaiverDiv").show();
                                    $("#EventDemoPassengerWaiverDiv").show();
                                    $("#EventDemoWaiverDiv2").show();
                                    $("#EventGuardianWaiverDiv").show();
                                    $("#EventDemoPassengerWaiverDiv2").show();
                                } else {
                                    $("#EventDemoWaiverDiv").hide();
                                    $("#EventDemoPassengerWaiverDiv").hide();
                                    $("#EventDemoWaiverDiv2").hide();
                                    $("#EventGuardianWaiverDiv").hide();
                                    $("#EventDemoPassengerWaiverDiv2").hide();
                                }
                            });

                            $("#EventTrike").click(function() {
                                if ($(this).prop("checked")) {
                                    $("#TrikeWaiverDiv").show();
                                    $("#TrikePassengerWaiverDiv").show();
                                } else {
                                    $("#TrikeWaiverDiv").hide();
                                    $("#TrikePassengerWaiverDiv").hide();
                                }
                            });

                            $("#EventLiveWireJumpStart").click(function() {
                                if ($(this).prop("checked")) {
                                    $("#EventLiveWireJumpStartDiv").show();
                                    $("#EventLiveWireJumpStartUnderAgeDiv").show();
                                } else {
                                    $("#EventLiveWireJumpStartDiv").hide();
                                    $("#EventLiveWireJumpStartUnderAgeDiv").hide()
                                }
                            });

                            $("#EventLivewireLeadGen").click(function() {
                                if ($(this).prop("checked")) {
                                    $("#EventLiveWireLeadGenWaiverDiv").show();
                                } else {
                                    $("#EventLiveWireLeadGenWaiverDiv").hide();
                                }
                            });
                        });
                    </script>
                    <div class="row">
                        <div class="col-md-2">
                            <div class="checkbox">
                                <label>
                                <input type="checkbox" id="EventJumpStart" name="EventJumpStart" placeholder=""> Event Dyno</label>
                            </div>
                        </div>
                        <div class="col-md-5" id="EventJumpStartWaiverDiv" style="display:none;">
                            <div class="form-group">
                                <label class="control-label" for="waiver">Jump Start Waiver</label>
                                <select name="EventJumpStartWaiver" class="selectboxit" id="EventJumpStartWaiver">
                                    <optgroup label="Waivers">
                                        <option value="">None</option>
                                        @foreach($waivers as $waiver)
                                        <option value="{{ $waiver->WaiverID }}">{{ $waiver->WaiverName }}</option>
                                        @endforeach
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-5" id="EventJumpStartWaiverUnderAgeDiv" style="display:none;">
                            <div class="form-group">
                                <label class="control-label" for="waiver">Jump Start Waiver Under Age</label>
                                <select name="EventJumpStartWaiverUnderAge" class="selectboxit" id="EventJumpStartWaiverUnderAge">
                                    <optgroup label="Waivers">
                                        <option value="">None</option>
                                        @foreach($waivers as $waiver)
                                        <option value="{{ $waiver->WaiverID }}">{{ $waiver->WaiverName }}</option>
                                        @endforeach
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            <div class="checkbox">
                                <label>
                                <input type="checkbox" id="EventLeadGen" name="EventLeadGen" placeholder=""> Event Lead Gen</label>
                            </div>
                        </div>
                        <div class="col-md-10" id="EventLeadGenWaiverDiv" style="display:none;">
                            <div class="form-group">
                                <label class="control-label" for="waiver">Lead Gen Waiver</label>
                                <select name="EventLeadGenWaiver" class="selectboxit" id="EventLeadGenWaiver">
                                    <optgroup label="Waivers">
                                        <option value="">None</option>
                                        @foreach($waivers as $waiver)
                                        <option value="{{ $waiver->WaiverID }}">{{ $waiver->WaiverName }}</option>
                                        @endforeach
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            <div class="checkbox">
                                <label>
                                <input type="checkbox" id="EventDemo" name="EventDemo" placeholder=""> Event Demo
                                Registration</label>
                            </div>
                        </div>
                        <div class="col-md-5" id="EventDemoWaiverDiv" style="display:none;">
                            <div class="form-group">
                                <label class="control-label" for="waiver">Rider Waiver 1</label>
                                <select name="EventDemoWaiver" class="selectboxit" id="EventDemoWaiver">
                                    <optgroup label="Waivers">
                                        <option value="">None</option>
                                        @foreach($waivers as $waiver)
                                        <option value="{{ $waiver->WaiverID }}">{{ $waiver->WaiverName }}</option>
                                        @endforeach
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-5" id="EventDemoPassengerWaiverDiv" style="display:none;">
                            <div class="form-group">
                                <label class="control-label" for="waiver">Passenger Waiver 1</label>
                                <select name="EventDemoPassengerWaiver" class="selectboxit" id="EventDemoPassengerWaiver">
                                    <optgroup label="Waivers">
                                        <option value="">None</option>
                                        @foreach($waivers as $waiver)
                                        <option value="{{ $waiver->WaiverID }}">{{ $waiver->WaiverName }}</option>
                                        @endforeach
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            &nbsp;
                        </div>
                        <div class="col-md-5" id="EventDemoWaiverDiv2" style="display:none;">
                            <div class="form-group">
                                <label class="control-label" for="waiver">Rider Waiver 2</label>
                                <select name="EventDemoWaiver2" class="selectboxit" id="EventDemoWaiver2">
                                    <optgroup label="Waivers">
                                        <option value="">None</option>
                                        @foreach($waivers as $waiver)
                                        <option value="{{ $waiver->WaiverID }}">{{ $waiver->WaiverName }}</option>
                                        @endforeach
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-5" id="EventDemoPassengerWaiverDiv2" style="display:none;">
                            <div class="form-group">
                                <label class="control-label" for="waiver">Passenger Waiver 2</label>
                                <select name="EventDemoPassengerWaiver2" class="selectboxit" id="EventDemoPassengerWaiver2">
                                    <optgroup label="Waivers">
                                        <option value="">None</option>
                                        @foreach($waivers as $waiver)
                                        <option value="{{ $waiver->WaiverID }}">{{ $waiver->WaiverName }}</option>
                                        @endforeach
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="checkbox">
                                <label>
                                <input type="checkbox" id="EnableSms" name="EnableSms" placeholder=""> Enable SMS text messages
                                after registration</label>
                            </div>
                        </div>
                        <div class="col-md-6" id="eventSmsTemplateIdDiv" style="display:none;">
                            <div class="form-group">
                                <label class="control-label" for="EventSmsTemplateId">Event SMS Template</label>
                                <select name="EventSmsTemplateId" class="selectboxit" id="EventSmsTemplateId">
                                    <optgroup label="SmsTemplate">
                                        <option value="">None</option>
                                        @foreach($smstemplates as $smstemplate)
                                        <option value="{{ $smstemplate->TemplateID }}">{{ $smstemplate->TemplateName }}</option>
                                        @endforeach
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            &nbsp;
                        </div>
                        <div class="col-md-10" id="EventGuardianWaiverDiv" style="display:none;">
                            <div class="form-group">
                                <label class="control-label" for="waiver">Guardian Waiver</label>
                                <select name="EventGuardianWaiver" class="selectboxit" id="EventGuardianWaiver">
                                    <optgroup label="Waivers">
                                        <option value="">None</option>
                                        @foreach($waivers as $waiver)
                                        <option value="{{ $waiver->WaiverID }}">{{ $waiver->WaiverName }}</option>
                                        @endforeach
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="checkbox">
                                <label>
                                <input type="checkbox" id="EventPRSurvey" name="EventPRSurvey" placeholder=""> Post-Ride
                                Survey</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="checkbox">
                                <label>
                                <input type="checkbox" id="EventPhotoApp" name="EventPhotoApp" placeholder=""> Photo App</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label">Dealers</label>
                                <script type="text/javascript">
                                    jQuery(document).ready(function($) {
                                        $("#DealerIDEdit").select2({
                                            placeholder: 'Choose the dealer or dealers that are involved in this event.',
                                            allowClear: true
                                        }).on('select2-open', function() {
                                            $(this).data('select2').results.addClass('overflow-hidden').perfectScrollbar();
                                        });

                                        $("#alloweventpreregistrations").click(function() {
                                            $("#getLinkDiv").html(
                                            '<a href="javascript:;" onclick="get_link_show()" class="btn btn-info btn-sm btn-icon icon-left">                Get link                        </a>'
                                            );
                                            if ($(this).prop("checked")) {
                                                $("#RegistrationSurveyIDDiv").show();
                                                $("#EventWalletPassDiv").show();
                                                $("#RegistrationSuccessfulEmailDiv").show();
                                                $("#AdditionalDetailsIDDiv").show();
                                                $("#ReminderTemplateDiv").show();
                                                $("#ReminderTemplate2Div").show();
                                                $("#EventReminderTemp1Div").show();
                                                $("#EventReminderTemp2Div").show();
                                                $("#getLinkDiv").show();
                                                $("#EventPreRegistrationEmailQtyEdit").show();
                                                $("#WaitlistTemplateDiv").show();
                                            } else {
                                                $("#RegistrationSurveyIDDiv").hide();
                                                $("#EventWalletPassDiv").hide();
                                                $("#RegistrationSuccessfulEmailDiv").hide();
                                                $("#AdditionalDetailsIDDiv").hide();
                                                $("#ReminderTemplateDiv").hide();
                                                $("#ReminderTemplate2Div").hide();
                                                $("#EventReminderTemp1Div").hide();
                                                $("#EventReminderTemp2Div").hide();
                                                $("#getLinkDiv").hide();
                                                $("#EventPreRegistrationEmailQtyEdit").hide();
                                                $("#WaitlistTemplateDiv").hide();
                                            }
                                        });
                                    });
                                </script>
                                <select name="DealerID[]" class="form-control" id="DealerIDEdit" multiple>
                                    <option></option>
                                    <optgroup label="Dealers">
                                        @foreach($dealers as $dealer)
                                        <option value="{{ $dealer->DealerID }}">{{ $dealer->DealerName }}</option>
                                        @endforeach
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="margin-bottom: 2%;">
                        <div class="col-md-5">
                            <div class="checkbox">
                                <label>
                                <input type="checkbox" id="alloweventpreregistrations" name="alloweventpreregistrations" value="1"
                                    placeholder=""> Allow Event Pre-Registrations</label>
                            </div>
                        </div>
                        <div class="col-md-7 text-right">
                            <div id="getLinkDiv" style="display: none;">
                                <a href="javascript:;" onclick="get_link_show()" class="btn btn-info btn-sm btn-icon icon-left">
                                Get Link
                                </a>
                            </div>
                        </div>
                        <div class="clearfix" style="margin-bottom: 10px;"></div>
                        <div class="col-md-6" id="EventPreRegistrationEmailQtyEdit" style="display:none;">
                            <div class="form-group">
                                <label for="eventName" class="control-label">Registration Cap</label>
                                <input type="number" class="form-control" id="EventPreRegistrationEmailQty"
                                    name="EventPreRegistrationEmailQty" placeholder="Qty">
                            </div>
                        </div>
                        <div class="col-md-6" id="RegistrationSurveyIDDiv" style="display: none;">
                            <div class="form-group">
                                <label class="control-label" for="registrationsurveyid">Registration Survey</label>
                                <select name="registrationsurveyid" class="selectboxit" id="registrationsurveyid">
                                    <optgroup label="Saved Survey Data">
                                        <option value="">None</option>
                                        @foreach($surveys as $survey)
                                        <option value="{{ $survey->SurveyID }}">{{ $survey->SurveyName }}</option>
                                        @endforeach
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6" id="RegistrationSuccessfulEmailDiv" style="display:none;">
                            <div class="form-group">
                                <label class="control-label" for="registrationsuccessfulemailtemplate">Registration Successful Email
                                Template</label>
                                <select name="registrationsuccessfulemailtemplate" class="selectboxit"
                                    id="registrationsuccessfulemailtemplate">
                                    <optgroup label="Email Templates">
                                        <option value="">None</option>
                                        @foreach($emailTemplates as $emailtemplate)
                                        <option value="{{ $emailtemplate->TemplateID }}">{{ $emailtemplate->TemplateName }}</option>
                                        @endforeach
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6" id="WaitlistTemplateDiv" style="display:none;">
                            <div class="form-group">
                                <label class="control-label" for="remindertemplateemailtemplate">Waitlist Template</label>
                                <select name="waitlisttemplateemailtemplate" class="selectboxit"
                                    id="waitlisttemplateemailtemplateEdit">
                                    <optgroup label="Email Templates">
                                        <option value="">None</option>
                                        @foreach($emailTemplates as $emailtemplate)
                                        <option value="{{ $emailtemplate->TemplateID }}">{{ $emailtemplate->TemplateName }}</option>
                                        @endforeach
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6" id="AdditionalDetailsIDDiv" style="display: none;">
                            <div class="form-group">
                                <label class="control-label" for="registrationsurveyid">Additional Details</label>
                                <textarea class="form-control autogrow" name="additionaldetails" id="additionaldetails"
                                    placeholder="Additional Details"></textarea>
                            </div>
                        </div>
                        <div class="col-md-6" id="ReminderTemplateDiv" style="display:none;">
                            <div class="form-group">
                                <label class="control-label" for="remindertemplateemailtemplate">Reminder Template 1</label>
                                <select name="remindertemplateemailtemplate" class="selectboxit" id="remindertemplateemailtemplate">
                                    <optgroup label="Email Templates">
                                        <option value="">None</option>
                                        @foreach($emailTemplates as $emailtemplate)
                                        <option value="{{ $emailtemplate->TemplateID }}">{{ $emailtemplate->TemplateName }}</option>
                                        @endforeach
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6" id="ReminderTemplate2Div" style="display:none;">
                            <div class="form-group">
                                <label class="control-label" for="remindertemplate2emailtemplate">Reminder Template 2</label>
                                <select name="remindertemplate2emailtemplate" class="selectboxit" id="remindertemplate2emailtemplate">
                                    <optgroup label="Email Templates">
                                        <option value="">None</option>
                                        @foreach($emailTemplates as $emailtemplate)
                                        <option value="{{ $emailtemplate->TemplateID }}">{{ $emailtemplate->TemplateName }}</option>
                                        @endforeach
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12" id="EventRegistrationDeadlinePSTTemp1Div">
                            <div class="form-group">
                                <label class="control-label" for="EventRegistrationDeadlinePSTTemp1">Registration Deadline
                                (PST):</label>
                                <div class="input-group">
                                    <input type="text" name="EventRegistrationDeadlinePSTTemp1" id="EventRegistrationDeadlinePSTTemp1"
                                        class="form-control datetimepicker" data-format="D, dd MM yyyy hh:mm">
                                    <span id="EventRegistrationDeadlinePSTTemp1Error"></span>
                                    <div class="input-group-addon">
                                        <a href="#"><i class="linecons-calendar"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12" id="EventReminderTemp1Div" style="display: none;">
                            <div class="form-group">
                                <label class="control-label" for="registrationsurveyid">Reminder 1:</label>
                                <div class="input-group">
                                    <input type="text" name="EventReminderTemp1" data-validate="required" id="EventReminderTemp1"
                                        class="form-control datepicker" data-format="D, dd MM yyyy">
                                    <span id="EventReminderTemp1Error"></span>
                                    <div class="input-group-addon">
                                        <a href="#"><i class="linecons-calendar"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12" id="EventReminderTemp2Div" style="display:none;">
                            <div class="form-group">
                                <label class="control-label" for="remindertemplateemailtemplate">Reminder 2:</label>
                                <div class="input-group">
                                    <input type="text" name="EventReminderTemp2" data-validate="required" id="EventReminderTemp2"
                                        class="form-control datepicker" data-format="D, dd MM yyyy">
                                    <span id="EventReminderTemp2Error"></span>
                                    <div class="input-group-addon">
                                        <a href="#"><i class="linecons-calendar"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="website" class="control-label">Website</label>
                                <input name="EventWebsite" type="text" class="form-control" id="EventWebsiteEdit" placeholder="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label" for="event_start">Event Start Date</label>
                                <div class="input-group">
                                    <input type="text" name="EventStartDate" data-validate="required" id="EventStartDateEdit"
                                        class="form-control datepicker" data-format="D, dd MM yyyy" autocomplete="off">
                                    <span id="EventStartDateError"></span>
                                    <div class="input-group-addon">
                                        <a href="#"><i class="linecons-calendar"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label" for="event_end">Event End Date</label>
                                <div class="input-group">
                                    <input type="text" autocomplete="off" name="EventEndDate" data-validate="required"
                                        id="EventEndDateEdit" class="form-control datepicker" data-format="D, dd MM yyyy">
                                    <span id="EventEndDateEditError"></span>
                                    <div class="input-group-addon">
                                        <a href="#"><i class="linecons-calendar"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="control-label" for="trucks">Truck Association</label>
                                <script type="text/javascript">
                                    jQuery(document).ready(function($) {
                                        $("#TruckIDEdit").select2({
                                            placeholder: 'Choose the dealer or dealers that are involved in this event.',
                                            allowClear: true
                                        }).on('select2-open', function() {
                                            $(this).data('select2').results.addClass('overflow-hidden').perfectScrollbar();
                                        });
                                        $("a.reportLink").click(function() {
                                            var eventId = $(this).parent().prev().prev().prev().prev().prev().prev().text();
                                            window.location.href = "{{ url('Action.php') }}?id=" + eventId;
                                        });
                                        $("a#MultiReport").click(function() {
                                            var checkboxes = document.getElementsByName('crm[]');
                                            var checkboxesChecked = [];
                                            for (var i = 0; i < checkboxes.length; i++) {
                                                if (checkboxes[i].checked) {
                                                    checkboxesChecked.push(checkboxes[i].value);
                                                }
                                            }
                                            window.location.href = "{{ url('Action.php') }}?id=" + checkboxesChecked;
                                        });
                                    });
                                </script>
                                <select name="TruckID[]" class="form-control" id="TruckIDEdit" multiple>
                                    <option value="">Please select the Trucks</option>
                                    <optgroup label="Trucks">
                                        @foreach($trucks as $truck)
                                        <option value="{{ $truck->TruckID }}">{{ $truck->TruckName }}</option>
                                        @endforeach
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label" for="social">Lead Gen Survey</label>
                                <select name="leadgensurvey" class="selectboxit" id="LeadGenSurveyEdit">
                                    <optgroup label="Saved Survey Data">
                                        <option value="">None</option>
                                        @foreach($surveys as $survey)
                                        <option value="{{ $survey->SurveyID }}">{{ $survey->SurveyName }}</option>
                                        @endforeach
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label" for="social">Dyno Survey</label>
                                <select name="jumpstartsurvey" class="selectboxit" id="JumpStartSurveyEdit">
                                    <optgroup label="Saved Survey Data">
                                        <option value="">None</option>
                                        @foreach($surveys as $survey)
                                        <option value="{{ $survey->SurveyID }}">{{ $survey->SurveyName }}</option>
                                        @endforeach
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label" for="social">Demo Survey</label>
                                <select name="demosurvey" class="selectboxit" id="DemoSurveyEdit">
                                    <optgroup label="Saved Survey Data">
                                        <option value="">None</option>
                                        @foreach($surveys as $survey)
                                        <option value="{{ $survey->SurveyID }}">{{ $survey->SurveyName }}</option>
                                        @endforeach
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label" for="social">Post Ride Survey</label>
                                <select name="postridesurvey" class="selectboxit" id="PostRideSurveyEdit">
                                    <optgroup label="Saved Survey Data">
                                        <option value="">None</option>
                                        @foreach($surveys as $survey)
                                        <option value="{{ $survey->SurveyID }}">{{ $survey->SurveyName }}</option>
                                        @endforeach
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label" for="social">Photo App Email Template</label>
                                <select name="EventPhotoAppEmail" class="selectboxit" id="EventPhotoAppEmailEdit">
                                    <optgroup label="Saved Photo App Email">
                                        <option value="">None</option>
                                        @foreach($emailTemplates as $emailtemplate)
                                        <option value="{{ $emailtemplate->TemplateID }}">{{ $emailtemplate->TemplateName }}</option>
                                        @endforeach
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label" for="social">Event Welcome Email Template</label>
                                <select name="EventWelcomeEmail" class="selectboxit" id="EventWelcomeEmailEdit">
                                    <optgroup label="Saved Event Welcome Email">
                                        <option value="">None</option>
                                        @foreach($emailTemplates as $emailtemplate)
                                        <option value="{{ $emailtemplate->TemplateID }}">{{ $emailtemplate->TemplateName }}</option>
                                        @endforeach
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label" for="social">Event Scheduled Email Template</label>
                                <select name="EventScheduledEmail" class="selectboxit" id="EventScheduledEmailEdit">
                                    <optgroup label="Saved Event Scheduled Email">
                                        <option value="">None</option>
                                        @foreach($emailTemplates as $emailtemplate)
                                        <option value="{{ $emailtemplate->TemplateID }}">{{ $emailtemplate->TemplateName }}</option>
                                        @endforeach
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label" for="social">Event Thanks Email Template</label>
                                <select name="EventTyEmail" class="selectboxit" id="EventTyEmailEdit">
                                    <optgroup label="Saved Event Thanks Email">
                                        <option value="">None</option>
                                        @foreach($emailTemplates as $emailtemplate)
                                        <option value="{{ $emailtemplate->TemplateID }}">{{ $emailtemplate->TemplateName }}</option>
                                        @endforeach
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label" for="social">Event Pr Email Template</label>
                                <select name="EventPrEmail" class="selectboxit" id="EventPrEmailEdit">
                                    <optgroup label="Saved Event Pr Email">
                                        <option value="">None</option>
                                        @foreach($emailTemplates as $emailtemplate)
                                        <option value="{{ $emailtemplate->TemplateID }}">{{ $emailtemplate->TemplateName }}</option>
                                        @endforeach
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label" for="social">Event Sales Email Template</label>
                                <select name="EventSalesEmail" class="selectboxit" id="EventSalesEmailEdit">
                                    <optgroup label="Saved Event Sales Email">
                                        <option value="">None</option>
                                        @foreach($emailTemplates as $emailtemplate)
                                        <option value="{{ $emailtemplate->TemplateID }}">{{ $emailtemplate->TemplateName }}</option>
                                        @endforeach
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="checkbox">
                                    <label>
                                    <input type="checkbox" name="eventbikesandtimes" id="eventbikesandtimes">
                                    This event requires Bikes and Times
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" id="EventEditID" name="EventID" value="">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-info" onclick="jQuery('#EventEditForm').submit();">Save Changes</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function showHide() {
        var x = document.getElementById("MultiReport");
        x.style.display = "none";
        setTimeout(function() {
            if ($('.crm').is(':checked')) {
                x.style.display = "block";
            }
        }, 1000);
    }
</script>

<style type="text/css">
    .modal-content {
        border: 0;
    }
    .bootstrap-datetimepicker-widget.dropdown-menu {
        z-index: 9999 !important;
    }
    .datetimepicker {
        z-index: 1201 !important;
    }
</style>
@endsection

@push('scripts')
<script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js') }}"></script>
{!! JsValidator::formRequest('App\Http\Requests\Backend\EventRequest', '#EventEditForm') !!}
{!! JsValidator::formRequest('App\Http\Requests\Backend\EventRequest', '#EventDelete') !!}

<script type="text/javascript">
    $('.datetimepicker').datetimepicker({
        useCurrent: true,
        format: "ddd, DD MMMM YYYY HH:mm"
    });
</script>
@endpush
