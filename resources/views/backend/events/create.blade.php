@extends('layouts.backend.app')
@section('title', $title)
@section('content')
<style>
    form .form-group.validate-has-error span.validate-has-error {
        color: #cc3f44;
        display: block;
        padding-top: 5px;
        font-size: 12px;
    }
</style>
<div class="main-content">
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

    <!-- Form wizard with validation starts here -->
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            $(".multi-select").multiSelect({
                afterInit: function() {
                    // Add alternative scrollbar to list
                    this.$selectableContainer.add(this.$selectionContainer).find('.ms-list').perfectScrollbar();
                },
                afterSelect: function() {
                    // Update scrollbar size
                    this.$selectableContainer.add(this.$selectionContainer).find('.ms-list').perfectScrollbar('update');
                }
            });

            $(".selectboxit").selectBoxIt().on('open', function() {
                // Adding Custom Scrollbar
                $(this).data('selectBoxSelectBoxIt').list.perfectScrollbar();
            });
        });
    </script>

    <form id="EventForm" method="post" action="{{ route('manage-events.store') }}" class="form-wizard validate" novalidate>
        @csrf
        <ul class="tabs">
            <li class="active">
                <a href="#fwv-1" data-toggle="tab">
                    Event Details
                    <span>1</span>
                </a>
            </li>
            <li>
                <a href="#fwv-3" data-toggle="tab">
                    Survey
                    <span>2</span>
                </a>
            </li>
            <li>
                <a href="#fwv-4" data-toggle="tab">
                    Users
                    <span>3</span>
                </a>
            </li>
            <li>
                <a href="#fwv-6" data-toggle="tab">
                    Truck Setup
                    <span>4</span>
                </a>
            </li>
        </ul>

        <div class="progress-indicator">
            <span></span>
        </div>



        <div class="tab-content no-margin">
            <!-- Tab 1: Event Details -->
            <div class="tab-pane with-bg active" id="fwv-1">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label" for="event_name">Event Name</label>
                            <input class="form-control" name="EventName" id="event_name" data-validate="required" placeholder="Choose a name for this event" />
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Event Type</label>
                            <script type="text/javascript">
                                jQuery(document).ready(function($) {
                                    $("#EventCountry").select2({
                                        placeholder: 'Select Event Type...',
                                        allowClear: true,
                                        minimumResultsForSearch: -1,
                                        formatResult: function(state) {
                                            return '<div style="background:url(http://www.geonames.org/flags/x/' + state.id + '.gif) no-repeat center center;background-size:100%;display:inline-block;position:relative;width:20px;height:15px;margin-right: 10px;top:2px;"></div>' + state.text;
                                        }
                                    }).on('select2-open', function() {
                                        $(this).data('select2').results.addClass('overflow-hidden').perfectScrollbar();
                                    });
                                });
                            </script>

                            <select class="form-control" id="EventCountry" name="EventCountry" data-validate="required">
                                <option></option>
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
                            <input type="text" class="form-control" id="EventCampaignCodeEdit" name="EventCampaignCode" placeholder="">
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

                        $("#EventDemo").click(function() {
                            if ($(this).prop("checked")) {
                                $("#EventDemoWaiverDiv").show();
                                $("#EventDemoPassengerWaiverDiv").show();
                                $("#EventDemoWaiverDiv2").show();
                                $("#EventDemoPassengerWaiverDiv2").show();
                            } else {
                                $("#EventDemoWaiverDiv").hide();
                                $("#EventDemoPassengerWaiverDiv").hide();
                                $("#EventDemoWaiverDiv2").hide();
                                $("#EventDemoPassengerWaiverDiv2").hide();
                            }
                        });
                    });
                </script>

                <div class="row">
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" id="eventbikesandtimes" name="eventbikesandtimes" value="1" checked> This event requires Bikes and Times
                            </label>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" id="EventJumpStart" name="EventJumpStart"> Event Dyno
                            </label>
                        </div>
                    </div>
                    <div class="col-md-4" id="EventJumpStartWaiverDiv" style="display:none;">
                        <div class="form-group">
                            <label class="control-label" for="EventJumpStartWaiver">Event Jump Start Waiver</label>
                            <select name="EventJumpStartWaiver" class="selectboxit" id="EventJumpStartWaiver">
                                <optgroup label="Waivers">
                                    <option value="">Select Waiver</option>
                                    @foreach($waivers as $waiver)
                                        <option value="{{ $waiver->WaiverID }}">{{ $waiver->WaiverName }}</option>
                                    @endforeach
                                </optgroup>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4" id="EventJumpStartWaiverUnderAgeDiv" style="display:none;">
                        <div class="form-group">
                            <label class="control-label" for="EventJumpStartWaiverUnderAge">Event Jump Start Waiver Under Age</label>
                            <select name="EventJumpStartWaiverUnderAge" class="selectboxit" id="EventJumpStartWaiverUnderAge">
                                <optgroup label="Waivers">
                                    <option value="">Select Waiver</option>
                                    @foreach($waivers as $waiver)
                                        <option value="{{ $waiver->WaiverID }}">{{ $waiver->WaiverName }}</option>
                                    @endforeach
                                </optgroup>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" id="EventLeadGen" name="EventLeadGen"> Event Lead Gen
                            </label>
                        </div>
                    </div>
                    <div class="col-md-8" id="EventLeadGenWaiverDiv" style="display:none;">
                        <div class="form-group">
                            <label class="control-label" for="EventLeadGenWaiver">Event Lead Gen Waiver</label>
                            <select name="EventLeadGenWaiver" class="selectboxit" id="EventLeadGenWaiver">
                                <optgroup label="Waivers">
                                    <option value="">Select Waiver</option>
                                    @foreach($waivers as $waiver)
                                        <option value="{{ $waiver->WaiverID }}">{{ $waiver->WaiverName }}</option>
                                    @endforeach
                                </optgroup>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" id="EventDemo" name="EventDemo"> Event Demo Registration
                            </label>
                        </div>
                    </div>
                    <div class="col-md-4" id="EventDemoWaiverDiv" style="display:none;">
                        <div class="form-group">
                            <label class="control-label" for="EventDemoWaiver">Event Demo Waiver 1</label>
                            <select name="EventDemoWaiver" class="selectboxit" id="EventDemoWaiver">
                                <optgroup label="Waivers">
                                    <option value="">Select Waiver</option>
                                    @foreach($waivers as $waiver)
                                        <option value="{{ $waiver->WaiverID }}">{{ $waiver->WaiverName }}</option>
                                    @endforeach
                                </optgroup>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4" id="EventDemoPassengerWaiverDiv" style="display:none;">
                        <div class="form-group">
                            <label class="control-label" for="EventDemoPassengerWaiver">Passenger Waiver 1</label>
                            <select name="EventDemoPassengerWaiver" class="selectboxit" id="EventDemoPassengerWaiver">
                                <optgroup label="Waivers">
                                    <option value="">Select Waiver</option>
                                    @foreach($waivers as $waiver)
                                        <option value="{{ $waiver->WaiverID }}">{{ $waiver->WaiverName }}</option>
                                    @endforeach
                                </optgroup>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        &nbsp;
                    </div>
                    <div class="col-md-4" id="EventDemoWaiverDiv2" style="display:none;">
                        <div class="form-group">
                            <label class="control-label" for="EventDemoWaiver2">Demo Waiver 2</label>
                            <select name="EventDemoWaiver2" class="selectboxit" id="EventDemoWaiver2">
                                <optgroup label="Waivers">
                                    <option value="">Select Waiver</option>
                                    @foreach($waivers as $waiver)
                                        <option value="{{ $waiver->WaiverID }}">{{ $waiver->WaiverName }}</option>
                                    @endforeach
                                </optgroup>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4" id="EventDemoPassengerWaiverDiv2" style="display:none;">
                        <div class="form-group">
                            <label class="control-label" for="EventDemoPassengerWaiver2">Passenger Waiver 2</label>
                            <select name="EventDemoPassengerWaiver2" class="selectboxit" id="EventDemoPassengerWaiver2">
                                <optgroup label="Waivers">
                                    <option value="">Select Waiver</option>
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
                                <input type="checkbox" id="EventPRSurvey" name="EventPRSurvey"> Post-Ride Survey
                            </label>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" id="EventPhotoApp" name="EventPhotoApp"> Photo App
                            </label>
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
                                });
                            </script>

                            <select name="DealerID[]" class="form-control" id="DealerIDEdit" multiple>
                                <option></option>
                                <optgroup label="Dealers">
                                    @foreach($dealers as $dealer)
                                        <option value="{{ $dealer->DealerID }}">{{ $dealer->DealerName }}-{{ $dealer->DealerNumber }}</option>
                                    @endforeach
                                </optgroup>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label" for="event_start">Event Start Date</label>
                            <div class="input-group">
                                <input type="text" name="EventStartDate" id="event_start" class="form-control datepicker" data-format="D, dd MM yyyy" data-validate="required">
                                <div class="input-group-addon">
                                    <a href="#"><i class="linecons-calendar"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label" for="event_end">Event End Date</label>
                            <div class="input-group">
                                <input type="text" name="EventEndDate" id="event_end" class="form-control datepicker" data-format="D, dd MM yyyy" data-validate="required">
                                <div class="input-group-addon">
                                    <a href="#"><i class="linecons-calendar"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab 2: Survey -->
            <div class="tab-pane with-bg" id="fwv-3">
                <strong>Add Survey</strong>
                <br /><br />
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label" for="leadgensurvey">Lead Gen Survey</label>
                            <select name="leadgensurvey" class="selectboxit" id="LeadGenSurveyEdit">
                                <optgroup label="Saved Survey Data">
                                    <option value="">Select Survey</option>
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
                            <label class="control-label" for="demosurvey">Dyno Survey</label>
                            <select name="demosurvey" class="selectboxit" id="DemoSurveyEdit">
                                <optgroup label="Saved Survey Data">
                                    <option value="">Select Survey</option>
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
                            <label class="control-label" for="postridesurvey">Demo Survey</label>
                            <select name="postridesurvey" class="selectboxit" id="PostRideSurveyEdit">
                                <optgroup label="Saved Survey Data">
                                    <option value="">Select Survey</option>
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
                            <label class="control-label" for="jumpstartsurvey">Post Ride Survey</label>
                            <select name="jumpstartsurvey" class="selectboxit" id="JumpStartSurveyEdit">
                                <optgroup label="Saved Survey Data">
                                    <option value="">Select Survey</option>
                                    @foreach($surveys as $survey)
                                        <option value="{{ $survey->SurveyID }}">{{ $survey->SurveyName }}</option>
                                    @endforeach
                                </optgroup>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab 3: Users -->
            <div class="tab-pane with-bg" id="fwv-4">
                <strong>Add Users</strong>
                <br /><br />
                <div class="row">
                    <div class="form-group">
                        <div class="col-sm-12">
                            <script type="text/javascript">
                                jQuery(document).ready(function($) {
                                    $("#UserIDEdit").multiSelect({
                                        afterInit: function() {
                                            this.$selectableContainer.add(this.$selectionContainer).find('.ms-list').perfectScrollbar();
                                        },
                                        afterSelect: function() {
                                            this.$selectableContainer.add(this.$selectionContainer).find('.ms-list').perfectScrollbar('update');
                                        }
                                    });
                                });
                            </script>
                            <select name="UserID[]" class="form-control" id="UserIDEdit" multiple>
                                <optgroup label="Please select the users">
                                    @foreach($users as $user)
                                        <option value="{{ $user->UserID }}">{{ $user->UserFullName }}</option>
                                    @endforeach
                                </optgroup>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab 4: Truck Setup -->
            <div class="tab-pane with-bg" id="fwv-6">
                <strong>Truck Association</strong>
                <br /><br />
                <div class="col-sm-12 center-block">
                    <script type="text/javascript">
                        jQuery(document).ready(function($) {
                            $("#TruckIDEdit").multiSelect({
                                afterInit: function() {
                                    this.$selectableContainer.add(this.$selectionContainer).find('.ms-list').perfectScrollbar();
                                },
                                afterSelect: function() {
                                    this.$selectableContainer.add(this.$selectionContainer).find('.ms-list').perfectScrollbar('update');
                                }
                            });
                        });
                    </script>
                    <select name="TruckID[]" class="form-control" id="TruckIDEdit" multiple>
                        <optgroup label="Please Select Truck">
                            @foreach($trucks as $truck)
                                <option value="{{ $truck->TruckID }}">{{ $truck->TruckName }}</option>
                            @endforeach
                        </optgroup>
                    </select>
                </div>
                <div class="row">
                    <div class="col-md-12 text-center">&nbsp;</div>
                </div>
            </div>

            <!-- Hidden email templates fields from legacy code -->
            <div style="display:none;">
                <select name="EventWelcomeEmail" id="EventWelcomeEmailEdit">
                    @foreach($emailTemplates as $emailtemplate)
                        <option value="{{ $emailtemplate->TemplateID }}">{{ $emailtemplate->TemplateName }}</option>
                    @endforeach
                </select>
                <select name="EventScheduledEmail" id="EventScheduledEmailEdit">
                    @foreach($emailTemplates as $emailtemplate)
                        <option value="{{ $emailtemplate->TemplateID }}">{{ $emailtemplate->TemplateName }}</option>
                    @endforeach
                </select>
                <select name="EventTyEmail" id="EventTyEmailEdit">
                    @foreach($emailTemplates as $emailtemplate)
                        <option value="{{ $emailtemplate->TemplateID }}">{{ $emailtemplate->TemplateName }}</option>
                    @endforeach
                </select>
                <select name="EventPrEmail" id="EventPrEmailEdit">
                    @foreach($emailTemplates as $emailtemplate)
                        <option value="{{ $emailtemplate->TemplateID }}">{{ $emailtemplate->TemplateName }}</option>
                    @endforeach
                </select>
                <select name="EventPhotoAppEmail" id="EventPhotoAppEmailEdit">
                    @foreach($emailTemplates as $emailtemplate)
                        <option value="{{ $emailtemplate->TemplateID }}">{{ $emailtemplate->TemplateName }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Tabs Pager -->
            <ul class="pager wizard">
                <li class="previous">
                    <a href="#"><i class="entypo-left-open"></i> Previous</a>
                </li>
                <li class="next">
                    <a href="#">Next <i class="entypo-right-open"></i></a>
                </li>
                <li class="" id="EndSubmitForm" style="display:none;">
                    <a style="float:right;" href="javascript:;" id="createEventButton">Finish <i class="entypo-right-open"></i></a>
                </li>
            </ul>
        </div>
    </form>

    <footer class="main-footer sticky footer-type-1">
        <div class="footer-inner">
            <div class="footer-text">
                &copy; {{ date('Y') }} <strong>NCompassTrac</strong>
            </div>
            <div class="go-up">
                <a href="#" rel="go-top">
                    <i class="fa-angle-up"></i>
                </a>
            </div>
        </div>
    </footer>
</div>
@endsection

@push('scripts')
{!! returnScriptWithNonce(asset('vendor/jsvalidation/js/jsvalidation.js')) !!}
{!! JsValidator::formRequest('App\Http\Requests\Backend\EventRequest', '#EventForm') !!}

<script type="text/javascript">
    jQuery(document).ready(function($) {
        $("#EventCountry").change(function() {
            $("#EventAddressStateEdit").empty();
            $("#EventAddressStateEdit").selectBoxIt("refresh");
            var CountryID = $(this).val();
            $.ajax({
                method: "POST",
                url: "{{ route('manage-countries.states.by-country') }}",
                dataType: 'json',
                data: {
                    CountryID: CountryID
                }
            })
            .done(function(msg) {
                $.each(msg, function(key, data) {
                    $("#EventAddressStateEdit").append('<option value="' + data.statename + '">' + data.statename + '</option>');
                    $("#EventAddressStateEdit").selectBoxIt("refresh");
                });
            });
        });

        $("#EndSubmitForm").click(function() {
            $("#EventForm").submit();
        });

        // Bottom Scripts for submit finishing
        $("li.next").click(function() {
            if ($("div.tab-content").find(".active").attr("id") == "fwv-4") {
                $("#EndSubmitForm").prev().hide();
                $("#EndSubmitForm").show();
            }
        });
        $(".previous").click(function() {
            if ($.trim($("li.next").text()) == "Finish" || $("#EndSubmitForm").is(":visible")) {
                $("#EndSubmitForm").prev().show();
                $("#EndSubmitForm").hide();
            }
        });
    });
</script>
@endpush
