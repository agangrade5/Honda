@extends('layouts.backend.app')
@section('title', $title)
@section('content')
<!-- content @s -->
<div class="main-content">
    <!-- Content Header section -->
    @include('layouts.backend.content_header', compact('title'))

    <form id="EventForm" method="post" action="javascript:;" class="form-wizard validate" novalidate>
        <ul class="tabs">
            <li class="active">
                <a href="#fwv-1" data-toggle="tab">
                    Select Event
                    <span>1</span>
                </a>
            </li>

            <li class="exportData">
                <a href="#fwv-2" data-toggle="tab">
                    Customer Data Or Survey Data
                    <span>2</span>
                </a>
            </li>

            <li class="exportData">
                <a href="#fwv-3" data-toggle="tab">
                    Generating data
                    <span>3</span>
                </a>
            </li>
        </ul>
        <div class="progress-indicator">
            <span></span>
        </div>
        <div class="tab-content no-margin">
            <!-- Tabs Content -->
            <div class="tab-pane with-bg active" id="fwv-1">
                <strong>Please Select Event</strong>
                <br />
                <br />
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label" for="eventID">Select Event</label>
                            <select name="eventID" class="selectboxit" id="eventID">
                                <optgroup label="Saved Events">
                                    @foreach ($events as $event)
                                        <option value="{{ $event->eventid }}">{{ $event->eventname }}</option>
                                    @endforeach
                                </optgroup>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane with-bg" id="fwv-2">
                <strong>Data Type</strong>
                <br />
                <br />
                <input type="radio" name="type" value="c"> EXPORT CUSTOMER DATA <br />
                <input type="radio" name="type" value="s"> EXPORT SURVEY DATA <br />
                <input type="radio" name="type" value="y"> EXPORT HONDA DATA
            </div>

            <div class="tab-pane with-bg" id="fwv-3">
                <div id="data-loading">
                    Data Loading..........
                </div>
                <div id="survey-show">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label" for="JumpStartSurveyEdit"><strong>Please Select Survey</strong></label>
                                <select name="jumpstartsurvey" class="selectboxit" id="JumpStartSurveyEdit">
                                    <optgroup label="Saved Survey Data">
                                        @foreach ($surveys as $survey)
                                            <option value="{{ $survey->surveyid }}">{{ $survey->surveyname }}</option>
                                        @endforeach
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
                <a style="float:right;" href="#">Finish <i class="entypo-right-open"></i></a>
            </li>
        </ul>
    </form>

    <!-- Main Footer -->
    @include('layouts.backend.footer')
</div>
<!-- content @e -->
@endsection

@push('scripts')
    <!-- Form wizard with validation starts here -->
    <script type="text/javascript">
        jQuery(document).ready(function($)
        {
            $(".multi-select").multiSelect({
                afterInit: function()
                {
                    // Add alternative scrollbar to list
                    this.$selectableContainer.add(this.$selectionContainer).find('.ms-list').perfectScrollbar();
                },
                afterSelect: function()
                {
                    // Update scrollbar size
                    this.$selectableContainer.add(this.$selectionContainer).find('.ms-list').perfectScrollbar('update');
                }
            });

            $(".selectboxit").selectBoxIt().on('open', function()
            {
                // Adding Custom Scrollbar
                $(this).data('selectBoxSelectBoxIt').list.perfectScrollbar();
            });

            $('input[name="type"]').on('click', function(){
                if($('input[name="type"]:checked').val()=="c"){
                    $(".next").hide();
                    $("#EndSubmitForm").show();
                    $("#data-loading").show();
                    $("#survey-show").hide();
                }
                else if($('input[name="type"]:checked').val()=="s"){
                    $(".next").show();
                    $("#EndSubmitForm").hide();
                    $("#data-loading").hide();
                    $("#survey-show").show();
                }
                else if($('input[name="type"]:checked').val()=="y"){
                    $(".next").hide();
                    $("#EndSubmitForm").show();
                    $("#data-loading").show();
                    $("#survey-show").hide();
                }
            });

            $(".previous").on('click', function(){
                if($("div.tab-content").find(".active").attr("id")=="fwv-3"){
                    $("#EndSubmitForm").hide();
                }
                $(".next").show();
            });

            $("#EndSubmitForm").on('click', function(e){
                e.preventDefault();
                var eventId = $("#eventID").val();
                var action = $('input[name="type"]:checked').val();
                var surveyId = $("#JumpStartSurveyEdit").val();

                var url = "{{ route('manage-data-management.export') }}?EventID=" + eventId;

                if (action == "c") {
                    url += "&action=cust";
                } else if (action == "s") {
                    url += "&action=survey&SurveyID=" + surveyId;
                } else if (action == "y") {
                    url += "&action=honda";
                }

                window.open(url, "_blank");
            });

            $(".next").on('click', function(){
                if($("div.tab-content").find(".active").attr("id")=="fwv-2"){
                    if($('input[name="type"]:checked').val()=="c"){
                        $(".next").hide();
                        $("#survey-show").hide();
                        $("#data-loading").show();
                    }
                    else if($('input[name="type"]:checked').val()=="s"){
                        $(".next").hide();
                        $("#survey-show").show();
                        $("#data-loading").hide();
                        $("#EndSubmitForm").show();
                    }
                    else if($('input[name="type"]:checked').val()=="y"){
                        $(".next").hide();
                        $("#survey-show").hide();
                        $("#data-loading").show();
                    }
                }
            });
        });
    </script>
@endpush
