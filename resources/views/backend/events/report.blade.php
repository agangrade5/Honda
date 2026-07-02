@extends('layouts.backend.app')
@section('title', $title)
@section('content')
<!-- content @s -->
<div class="main-content">

    <!-- User-info navbar -->
    <nav class="navbar user-info-navbar" role="navigation">
        <!-- Left links -->
        <ul class="user-info-menu left-links list-inline list-unstyled">
            <li class="hidden-sm hidden-xs">
                <a href="#" data-toggle="sidebar"><i class="fa-bars"></i></a>
            </li>
            <li>
                <h3 style="margin-top:26px;">
                    Event: {{ $event->eventname }}
                </h3>
            </li>
        </ul>
        <!-- Right links -->
        <ul class="user-info-menu right-links list-inline list-unstyled">
            <li>
                <a href="{{ route('manage-events.index') }}">
                    <i class="fa-arrow-left"></i> Back to Events
                </a>
            </li>
            <li>
                <a href="javascript:;" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fa-lock"></i> Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
                    @csrf
                </form>
            </li>
        </ul>
    </nav>

    <div class="dx-warning hidden">
        <div>
            <h2>How to Include Charts Library in Xenon Theme</h2>
            <p>The reason why you don't see charts in this page is because of license restrictions from DevExpress company.</p>
        </div>
    </div>

    {{-- Charts init script (runs at document ready) --}}
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            if (!$.isFunction($.fn.dxChart))
                $(".dx-warning").removeClass('hidden');

            @if(!empty($report3))
            var report3 = @json($report3);

            // Gender Pie Chart
            var genderData = [
                { gender: "Male",   val: report3.MaleLeads   || 0 },
                { gender: "Female", val: report3.FemaleLeads || 0 }
            ];
            if ($.isFunction($.fn.dxPieChart)) {
                $("#gender-chart div").dxPieChart({
                    dataSource: genderData,
                    tooltip: {
                        enabled: true,
                        customizeText: function() { return this.argumentText + "<br/>" + this.valueText; }
                    },
                    size:   { height: 250 },
                    legend: { visible: true },
                    series: [{ type: "doughnut", argumentField: "gender", label: { visible: true }, hoverStyle: {}, selectionStyle: {} }],
                    palette: ['#fcd036','#4fcdfc','#00b19d']
                });

                // Motorcycle License Pie Chart
                var licenseData = [
                    { licenseStatus: "MC Lic",    val: report3.LeadsMotorcycleLicense        || 0 },
                    { licenseStatus: "No MC Lic", val: report3.LeadsWithoutMotorcycleLicense || 0 }
                ];
                $("#license-chart div").dxPieChart({
                    dataSource: licenseData,
                    tooltip: {
                        enabled: true,
                        customizeText: function() { return this.argumentText + "<br/>" + this.valueText; }
                    },
                    size:   { height: 250 },
                    legend: { visible: true },
                    series: [{ type: "doughnut", argumentField: "licenseStatus", label: { visible: true }, hoverStyle: {}, selectionStyle: {} }],
                    palette: ['#68b828','#7c38bc','#0e62c7']
                });
            }

            if ($.isFunction($.fn.dxChart)) {
                // Age Demographics Bar Chart
                $("#age-demo-chart").dxChart({
                    dataSource: [
                        { stat: "Under 30", age: report3.UnderThirty         || 0 },
                        { stat: "30 - 40",  age: report3.BetweenThirtyFourty || 0 },
                        { stat: "40 - 50",  age: report3.BetweenFourtyFifty  || 0 },
                        { stat: "Above 50", age: report3.AboveFifty          || 0 }
                    ],
                    tooltip: {
                        enabled: true,
                        customizeText: function() { return this.argumentText + "<br/>" + this.valueText; }
                    },
                    series: { argumentField: "stat", valueField: "age", name: "Age Average", type: "bar", color: '#7c38bc', label: { visible: true } },
                    commonAxisSettings: { label: { visible: true }, grid: { visible: true } },
                    legend: { visible: true },
                    argumentAxis: { valueMarginsEnabled: true }
                });

                // Intent to Purchase Bar Chart
                $("#intent-to-purchase-chart").dxChart({
                    dataSource: [
                        { stat: "Less than 3 Months", time: report3.IntendToPurchaseGraph?.LessThanThreeMonths || 0 },
                        { stat: "3 Months to a Year", time: report3.IntendToPurchaseGraph?.ThreeToTwelveMonths || 0 },
                        { stat: "More than a Year",   time: report3.IntendToPurchaseGraph?.MoreThanOneYear || 0 },
                        { stat: "Not Sure",           time: report3.IntendToPurchaseGraph?.NotSure || 0 }
                    ],
                    tooltip: {
                        enabled: true,
                        customizeText: function() { return this.argumentText + "<br/>" + this.valueText; }
                    },
                    series: { argumentField: "stat", valueField: "time", name: "Time", type: "bar", color: '#c00000', label: { visible: true } },
                    commonAxisSettings: { label: { visible: true }, grid: { visible: true } },
                    legend: { visible: true },
                    argumentAxis: { valueMarginsEnabled: true }
                });

                // Intent to Learn Bar Chart
                $("#intent-to-learn-chart").dxChart({
                    dataSource: [
                        { stat: "Less than 3 Months", time: report3.IntendToLearnGraph?.LessThanThreeMonths || 0 },
                        { stat: "3 Months to a Year", time: report3.IntendToLearnGraph?.ThreeToTwelveMonths || 0 },
                        { stat: "More than a Year",   time: report3.IntendToLearnGraph?.MoreThanOneYear || 0 },
                        { stat: "Not Sure",           time: report3.IntendToLearnGraph?.NotSure || 0 },
                        { stat: "Not Interested",     time: report3.IntendToLearnGraph?.NotInterested || 0 }
                    ],
                    tooltip: {
                        enabled: true,
                        customizeText: function() { return this.argumentText + "<br/>" + this.valueText; }
                    },
                    series: { argumentField: "stat", valueField: "time", name: "Time", type: "bar", color: '#14722a', label: { visible: true } },
                    commonAxisSettings: { label: { visible: true }, grid: { visible: true } },
                    legend: { visible: true },
                    argumentAxis: { valueMarginsEnabled: true }
                });

                // Previously Owned Bar Chart
                $("#previously-owned-chart").dxChart({
                    dataSource: [
                        { stat: "Harley-Davidson", brand: report3.PreviouslyOwnedGraph?.HarleyDavidson || 0 },
                        { stat: "BMW",             brand: report3.PreviouslyOwnedGraph?.BMW || 0 },
                        { stat: "Ducati",          brand: report3.PreviouslyOwnedGraph?.Ducati || 0 },
                        { stat: "Honda",           brand: report3.PreviouslyOwnedGraph?.Honda || 0 },
                        { stat: "Kawasaki",        brand: report3.PreviouslyOwnedGraph?.Kawasaki || 0 },
                        { stat: "Suzuki",          brand: report3.PreviouslyOwnedGraph?.Suzuki || 0 },
                        { stat: "Triumph",         brand: report3.PreviouslyOwnedGraph?.Triumph || 0 },
                        { stat: "Yamaha",          brand: report3.PreviouslyOwnedGraph?.Yamaha || 0 },
                        { stat: "Other",           brand: report3.PreviouslyOwnedGraph?.Other || 0 }
                    ],
                    tooltip: {
                        enabled: true,
                        customizeText: function() { return this.argumentText + "<br/>" + this.valueText; }
                    },
                    series: { argumentField: "stat", valueField: "brand", name: "Brand", type: "bar", color: '#8E3D00', label: { visible: true } },
                    commonAxisSettings: { label: { visible: true }, grid: { visible: true } },
                    legend: { visible: true },
                    argumentAxis: { valueMarginsEnabled: true }
                });

                // Currently Owned Bar Chart
                $("#currently-owned-chart").dxChart({
                    dataSource: [
                        { stat: "Harley-Davidson", brand: report3.CurrentlyOwnedGraph?.HarleyDavidson || 0 },
                        { stat: "BMW",             brand: report3.CurrentlyOwnedGraph?.BMW || 0 },
                        { stat: "Ducati",          brand: report3.CurrentlyOwnedGraph?.Ducati || 0 },
                        { stat: "Honda",           brand: report3.CurrentlyOwnedGraph?.Honda || 0 },
                        { stat: "Kawasaki",        brand: report3.CurrentlyOwnedGraph?.Kawasaki || 0 },
                        { stat: "Suzuki",          brand: report3.CurrentlyOwnedGraph?.Suzuki || 0 },
                        { stat: "Triumph",         brand: report3.CurrentlyOwnedGraph?.Triumph || 0 },
                        { stat: "Yamaha",          brand: report3.CurrentlyOwnedGraph?.Yamaha || 0 },
                        { stat: "Other",           brand: report3.CurrentlyOwnedGraph?.Other || 0 }
                    ],
                    tooltip: {
                        enabled: true,
                        customizeText: function() { return this.argumentText + "<br/>" + this.valueText; }
                    },
                    series: { argumentField: "stat", valueField: "brand", name: "Brand", type: "bar", color: '#F5821F', label: { visible: true } },
                    commonAxisSettings: { label: { visible: true }, grid: { visible: true } },
                    legend: { visible: true },
                    argumentAxis: { valueMarginsEnabled: true }
                });
            }

            if ($.isFunction($.fn.dxPieChart)) {
                // Active Military Pie Chart
                var militaryData = [
                    { possibleAnswer: "YES , ACTIVE", val: report3.possibleAnswerYesActive || 0 },
                    { possibleAnswer: "YES , VETERAN", val: report3.possibleAnswerYesVeteran || 0 },
                    { possibleAnswer: "NO", val: report3.possibleAnswerNo || 0 }
                ];
                var totalAnswer = report3.possibleAnswerTotal || 1;
                $("#possible-answer-chart div").dxPieChart({
                    dataSource: militaryData,
                    tooltip: {
                        enabled: true,
                        customizeText: function() {
                            return this.argumentText + "<br/>" + this.valueText + " (" + ((parseInt(this.valueText) / parseInt(totalAnswer)) * 100).toFixed(2) + "%)";
                        }
                    },
                    size: { height: 250 },
                    legend: { visible: true },
                    series: [{ type: "doughnut", argumentField: "possibleAnswer", label: { visible: true } }],
                    palette: ['#68b828','#7c38bc','#0e62c7']
                });
            }
            @endif

            @if(!empty($report2))
            var report2 = @json($report2);
            if ($.isFunction($.fn.dxPieChart)) {
                // Market Segments Pie Chart
                var segmentData = [
                    { segment: "Young Adult", val: report2.segment1 || 0 },
                    { segment: "Core", val: report2.segment2 || 0 },
                    { segment: "Female", val: report2.segment3 || 0 },
                    { segment: "Hispanic", val: report2.segment4 || 0 },
                    { segment: "African American", val: report2.segment5 || 0 },
                    { segment: "Other", val: report2.segment6 || 0 }
                ];
                var segmentTotal = report2.segmentTotal || 1;
                $("#customer-segments-chart div").dxPieChart({
                    dataSource: segmentData,
                    tooltip: {
                        enabled: true,
                        customizeText: function() {
                            return this.argumentText + "<br/>" + this.valueText + " (" + ((parseInt(this.valueText) / parseInt(segmentTotal)) * 100).toFixed(2) + "%)";
                        }
                    },
                    size: { height: 250 },
                    legend: { visible: true },
                    series: [{ type: "doughnut", argumentField: "segment", label: { visible: true } }],
                    palette: ['#68b828','#7c38bc','#0e62c7', '#fcd036', '#4fcdfc', '#00b19d']
                });
            }
            @endif

            @if(!empty($graphTrans))
            if ($.isFunction($.fn.dxChart)) {
                var graphTransData = [
                    @foreach($graphTrans as $model => $rides)
                    { stat: "{{ $model }}", time: {{ count($rides) }} },
                    @endforeach
                ];
                $("#dynamic-customer-trans-graph").dxChart({
                    dataSource: graphTransData,
                    tooltip: { enabled: true, customizeText: function() { return this.argumentText + "<br/>" + this.valueText; } },
                    series: { argumentField: "stat", valueField: "time", name: "Count", type: "bar", color: '#c00000', label: { visible: true } },
                    commonAxisSettings: { label: { visible: true }, grid: { visible: true } },
                    legend: { visible: true },
                    argumentAxis: { valueMarginsEnabled: true }
                });
            }
            @endif

        });
    </script>

    {{-- Notification script --}}
    <script type="text/javascript">
        var sample_notification;
        jQuery(document).ready(function($) {
            window.clearTimeout(sample_notification);
            var notification = setTimeout(function() {
                var opts = {
                    "closeButton": true, "debug": false,
                    "positionClass": "toast-top-right toast-default",
                    "toastClass": "black", "onclick": null,
                    "showDuration": "100", "hideDuration": "1000",
                    "timeOut": "5000", "extendedTimeOut": "1000",
                    "showEasing": "swing", "hideEasing": "linear",
                    "showMethod": "fadeIn", "hideMethod": "fadeOut"
                };
                toastr.info("The data and graphs below represent the statistics gathered from your selected event.", "Welcome to the Event Statistics Dashboard", opts);
            }, 3800);

            if (!$.isFunction($.fn.dxChart)) return;
        });
    </script>

    {{-- ── Demo Reports & Share Link buttons ── --}}
    <div style="font-weight:bold; font-size:15px; width:100%; padding: 10px 20px;">
        <div style="width:15%; float:left;">
            <a href="javascript:;" class="btn btn-info btn-lg" id="popupReports" onclick="jQuery('#report-modal').modal('show'); popUPDemoReport();">Demo Reports</a>
        </div>
        <div style="width:15%; float:right;">
            <a href="javascript:;" class="btn btn-info btn-lg" id="popupSharedPublicLinkReports" onclick="jQuery('#event-report-modal').modal('show');">Share Link</a>
        </div>
        <div style="clear:both;"></div>
    </div>

    {{-- ── KPI Counter Widgets Row ── --}}
    <div class="row">

        <div class="col-sm-6">
            <div class="xe-widget xe-counter">
                <div class="xe-icon"><i class="linecons-cloud"></i></div>
                <div class="xe-label">
                    <strong class="num">{{ $report1['count1'] ?? 0 }}</strong>
                    <span>LeadGen</span>
                </div>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="xe-widget xe-counter xe-counter-blue" data-easing="false">
                <div class="xe-icon"><i class="linecons-cloud"></i></div>
                <div class="xe-label">
                    <strong class="num">{{ $report1['count2'] ?? 0 }}</strong>
                    <span>Dyno</span>
                </div>
            </div>
        </div>

        <div class="col-sm-3">
            <div class="xe-widget xe-counter">
                <div class="xe-icon"><i class="fa-users"></i></div>
                <div id="totalLeadsValue" class="xe-label">
                    <strong class="num">{{ $report1['TotalLeads'] ?? 'Loading...' }}</strong>
                    <span>Total Leads</span>
                </div>
            </div>

            <div class="xe-widget xe-counter xe-counter-blue">
                <div class="xe-icon"><i class="fa-pied-piper"></i></div>
                <div id="maleLeadsValue" class="xe-label">
                    <strong class="num">{{ $report1['EmailOptIn'] ?? 'Loading...' }}</strong>
                    <span>Email Opt-In</span>
                </div>
            </div>

            <div class="xe-widget xe-counter xe-counter-purple">
                <div class="xe-icon"><i class="fa-check-square-o"></i></div>
                <div id="femaleLeadsValue" class="xe-label">
                    <strong class="num">{{ $report2['SurveysCollected'] ?? 'Loading...' }}</strong>
                    <span>Surveys Collected</span>
                </div>
            </div>
        </div>

        <div class="col-sm-3">
            <div class="chart-item-bg">
                <div id="gender-chart" style="height:298px; position:relative;">
                    <div style="position:absolute; top:25px; right:0; left:20px; bottom:0;"></div>
                </div>
            </div>
        </div>

        <div class="col-sm-3">
            <div class="chart-item-bg">
                <div id="license-chart" style="height:298px; position:relative;">
                    <div style="position:absolute; top:25px; right:0; left:20px; bottom:0;"></div>
                </div>
            </div>
        </div>

        <div class="col-sm-3">
            <div class="xe-widget xe-counter xe-counter-black">
                <div class="xe-icon"><i class="fa-calendar"></i></div>
                <div id="eventDate" class="xe-label">
                    <strong class="num">{{ $event->eventstart ? date('M d Y', strtotime($event->eventstart)) : '–' }}</strong>
                    <span>Event Date</span>
                </div>
            </div>

            <div class="xe-widget xe-counter xe-counter-orange">
                <div class="xe-icon"><i class="fa-male"></i></div>
                <div id="averageAgeMale" class="xe-label">
                    <strong class="num">{{ $report3['AverageAgeMale'] ?? 'Loading...' }}</strong>
                    <span>Average Male Age</span>
                </div>
            </div>

            <div class="xe-widget xe-counter xe-counter-turquoise">
                <div class="xe-icon"><i class="fa-female"></i></div>
                <div id="averageAgeFemale" class="xe-label">
                    <strong class="num">{{ $report3['AverageAgeFemale'] ?? 'Loading...' }}</strong>
                    <span>Average Female Age</span>
                </div>
            </div>
        </div>

    </div>{{-- /KPI row --}}

    {{-- Missing Charts Section --}}
    <div class="row">
        <div class="col-sm-6">
            <div class="chart-item-bg xe-label">
                <p class="num" style="padding-top: 10px; margin-left: 15px; font-size: 28px !important; color: black;">Intent to Purchase:</p>
                <div id="intent-to-purchase-chart" style="height: 250px; padding: 0 15px 15px 15px;"></div>
            </div>
            <div class="chart-item-bg xe-label">
                <p class="num" style="padding-top: 10px; margin-left: 15px; font-size: 28px !important; color: black;">Intent to Learn:</p>
                <div id="intent-to-learn-chart" style="height: 250px; padding: 0 15px 15px 15px;"></div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="chart-item-bg xe-label">
                <p class="num" style="padding-top: 10px; margin-left: 15px; font-size: 28px !important; color: black;">Age Demographics:</p>
                <div id="age-demo-chart" style="height: 590px; padding: 0 15px 15px 15px;"></div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="chart-item-bg xe-label">
                <p class="num" style="padding-top: 10px; margin-left: 15px; font-size: 28px !important; color: black;">Previously Owned Brand:</p>
                <div id="previously-owned-chart" style="height: 250px; padding: 0 15px 15px 15px;"></div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="chart-item-bg xe-label">
                <p class="num" style="padding-top: 10px; margin-left: 15px; font-size: 28px !important; color: black;">Currently Owned Brand:</p>
                <div id="currently-owned-chart" style="height: 250px; padding: 0 15px 15px 15px;"></div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-6">
            <div class="chart-item-bg">
                <p class="num" style="padding-top: 10px; margin-left: 15px; font-size: 28px !important; color: black;">Active Military:</p>
                <div id="possible-answer-chart" style="height: 298px; position: relative;">
                    <div style="position: absolute; top: 25px; right: 0; left: 20px; bottom: 0"></div>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="chart-item-bg">
                <p class="num" style="padding-top: 10px; margin-left: 15px; font-size: 28px !important; color: black;">Market Segments:</p>
                <div id="customer-segments-chart" style="height: 298px; position: relative;">
                    <div style="position: absolute; top: 25px; right: 0; left: 20px; bottom: 0"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Dynamic Survey Charts ── --}}
    @if(!empty($reportData2->dynamic_survey_data))
        @foreach($reportData2->dynamic_survey_data as $surveyID => $survey_row)
        <div class="row">
            <div class="col-sm-12">
                <div style="height:50px;" class="chart-item-bg xe-label">
                    <h1>Survey: {{ $survey_row->name }}</h1>
                </div>
            </div>
        </div>

        @foreach($survey_row->questions->Question as $ques_key => $ques_row)
        <div class="row">
            <div class="col-sm-12">
                <div class="chart-item-bg xe-label">
                    <p class="num" style="padding-top:10px; margin-left:15px; font-size:28px !important; color:black;">{{ $ques_row->name }}</p>
                    <div id="dynamic-survey-graph-{{ $surveyID }}-{{ $ques_key }}" style="height:250px; padding:0 15px 15px 15px;"></div>
                </div>
            </div>
        </div>
        <script type="text/javascript">
            jQuery(document).ready(function($) {
                if (!$.isFunction($.fn.dxChart)) return;
                $("#dynamic-survey-graph-{{ $surveyID }}-{{ $ques_key }}").dxChart({
                    dataSource: [
                        @foreach($ques_row->answers as $ans_row)
                        { stat: "{{ $ans_row->name }}", time: {{ $ans_row->count }} },
                        @endforeach
                    ],
                    tooltip: { enabled: true, customizeText: function() { return this.argumentText + "<br/>" + this.valueText; } },
                    series: { argumentField: "stat", valueField: "time", name: "Count", type: "bar", color: '#c00000', label: { visible: true } },
                    commonAxisSettings: { label: { visible: true }, grid: { visible: true } },
                    legend: { visible: true },
                    argumentAxis: { valueMarginsEnabled: true }
                });
            });
        </script>
        @endforeach
        @endforeach
    @endif

    {{-- ── Demo Rides by Model Chart ── --}}
    @if(!empty($graphTrans))
    <div class="row">
        <div class="col-sm-12">
            <div class="chart-item-bg xe-label">
                <p class="num" style="padding-top:10px; margin-left:15px; font-size:28px !important; color:black;"><strong>Demo Rides by Model</strong></p>
                <div id="dynamic-customer-trans-graph" style="height:250px; padding:0 15px 15px 15px;"></div>
            </div>
        </div>
    </div>
    @endif

    {{-- ── States Table ── --}}
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">States</h3>
                    <div class="panel-options">
                        <a href="#"><i class="linecons-cog"></i></a>
                        <a href="#" data-toggle="panel">
                            <span class="collapse-icon">&ndash;</span>
                            <span class="expand-icon">+</span>
                        </a>
                        <a href="#" data-toggle="reload"><i class="fa-rotate-right"></i></a>
                        <a href="#" data-toggle="remove">&times;</a>
                    </div>
                </div>
                <div class="scrollable" data-max-height="300">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>State Name</th>
                                <th>State Count</th>
                                <th>%</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty($report2['stateData']) && !empty($report2['stateTotalCount']))
                                @php $kCount = 1; @endphp
                                @foreach($report2['stateData'] as $stateName => $stateCount)
                                <tr>
                                    <td>{{ $kCount++ }}</td>
                                    <td>{{ $stateName }}</td>
                                    <td>{{ $stateCount }}</td>
                                    <td class="middle-align">
                                        {{ round(($stateCount / $report2['stateTotalCount']) * 100, 2) }}%
                                    </td>
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="4" class="text-center text-muted">No state data available.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @include('layouts.backend.footer')

</div>{{-- /main-content --}}

{{-- ════════════════════════════════════════
     Demo Reports Modal  (#report-modal)
════════════════════════════════════════ --}}
<div class="modal fade custom-width" id="report-modal">
    <div class="modal-dialog" style="width:90%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Demo Reports</h4>
            </div>
            <div class="modal-body">

                <!-- Instructions -->
                <div class="row">
                    <div class="col-sm-12">
                        <div class="vertical-top" style="font-size:16px;">
                            <b>Instructions:</b> If you would like to search by a specific time on the ranges below,
                            select a date and then add the time in the following format: HH:mm (example: 01/30/2015 13:01)
                        </div>
                    </div>
                </div>
                <br/><br/>

                <!-- Date range row -->
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="control-label">Choose Start Date</label>
                            <div class="input-group">
                                <input type="text" id="startDate" name="startDate" class="form-control" data-format="mm/dd/yyyy">
                                <div class="input-group-addon"><a href="#"><i class="linecons-calendar"></i></a></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="control-label">Choose End Date</label>
                            <div class="input-group">
                                <input type="text" id="endDate" name="endDate" class="form-control" data-format="mm/dd/yyyy">
                                <div class="input-group-addon"><a href="#"><i class="linecons-calendar"></i></a></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4" style="margin-top:15px;">
                        <button class="btn btn-info btn-lg" id="demoReportBtn">Result</button>
                    </div>
                </div>

                <!-- Result counter widgets -->
                <div class="row">
                    <div class="col-sm-6">
                        <div class="xe-widget xe-counter">
                            <div class="xe-icon"><i class="linecons-cloud"></i></div>
                            <div class="xe-label">
                                <strong class="num" id="htmlResponse1"><img src="{{ asset('assets/images/ajax-loader.gif') }}" class="img-responsive img-circle"/></strong>
                                <span>Total Demo Rides</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="xe-widget xe-counter xe-counter-blue" data-easing="false">
                            <div class="xe-icon"><i class="linecons-cloud"></i></div>
                            <div class="xe-label">
                                <strong class="num" id="htmlResponse2"><img src="{{ asset('assets/images/ajax-loader.gif') }}" class="img-responsive img-circle"/></strong>
                                <span>Total Dyno</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="xe-widget xe-counter xe-counter-info" data-easing="true">
                            <div class="xe-icon"><i class="linecons-cloud"></i></div>
                            <div class="xe-label">
                                <strong class="num" id="htmlResponse3"><img src="{{ asset('assets/images/ajax-loader.gif') }}" class="img-responsive img-circle"/></strong>
                                <span>Total Lead Gen</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="xe-widget xe-counter xe-counter-red" data-easing="true" data-delay="1">
                            <div class="xe-icon"><i class="linecons-cloud"></i></div>
                            <div class="xe-label">
                                <strong class="num" id="htmlResponse4"><img src="{{ asset('assets/images/ajax-loader.gif') }}" class="img-responsive img-circle"/></strong>
                                <span>Unique Demo Registrations</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="xe-widget xe-counter xe-counter-orange">
                            <div class="xe-icon"><i class="fa-male"></i></div>
                            <div class="xe-label">
                                <strong class="num" id="htmlResponse7"><img src="{{ asset('assets/images/ajax-loader.gif') }}" class="img-responsive img-circle"/></strong>
                                <span>Average Male Age</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="xe-widget xe-counter xe-counter-turquoise">
                            <div class="xe-icon"><i class="fa-female"></i></div>
                            <div class="xe-label">
                                <strong class="num" id="htmlResponse8"><img src="{{ asset('assets/images/ajax-loader.gif') }}" class="img-responsive img-circle"/></strong>
                                <span>Average Female Age</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>{{-- /modal-body --}}
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

{{-- ════════════════════════════════════════
     Share Link Modal  (#event-report-modal)
════════════════════════════════════════ --}}
<div class="modal fade custom-width" id="event-report-modal">
    <div class="modal-dialog" style="width:70%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h2 class="modal-title">Public Share Link</h2>
            </div>
            <div class="modal-footer">
                <div style="text-align:left; word-break:break-all; font-size:15px; margin-bottom:12px;">
                    <strong>Share URL:</strong><br>
                    <a href="{{ $shareLink }}" target="_blank">{{ $shareLink }}</a>
                </div>
                <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

{{-- ── Scripts ── --}}
<script type="text/javascript">
jQuery(document).ready(function($) {

    // Datepickers
    $("#startDate").datepicker({ forceParse: false, autoclose: true });
    $('#startDate').datepicker('update', new Date('{{ date("F d, Y H:i:s") }}'));
    $("#endDate").datepicker({ forceParse: false, autoclose: true });
    $('#endDate').datepicker('update', new Date('{{ date("F d, Y H:i:s") }}'));

    // Demo report date-range button
    $("#demoReportBtn").click(function() {
        $('#htmlResponse1').html('<img src="{{ asset("assets/images/ajax-loader.gif") }}" class="img-responsive img-circle"/>');
        $('#htmlResponse2').html('<img src="{{ asset("assets/images/ajax-loader.gif") }}" class="img-responsive img-circle"/>');
        $('#htmlResponse3').html('<img src="{{ asset("assets/images/ajax-loader.gif") }}" class="img-responsive img-circle"/>');
        $('#htmlResponse4').html('<img src="{{ asset("assets/images/ajax-loader.gif") }}" class="img-responsive img-circle"/>');
        $('#htmlResponse7').html('<img src="{{ asset("assets/images/ajax-loader.gif") }}" class="img-responsive img-circle"/>');
        $('#htmlResponse8').html('<img src="{{ asset("assets/images/ajax-loader.gif") }}" class="img-responsive img-circle"/>');

        $.ajax({
            url: '{{ route("event.report.demo", $encodedId) }}',
            data: {
                startDate: $("#startDate").val(),
                endDate:   $("#endDate").val()
            },
            dataType: 'json'
        }).done(function(data) {
            if (data) {
                $('#htmlResponse1').html(data.TotalDemoRides    || 0);
                $('#htmlResponse2').html(data.Jumpstart         || 0);
                $('#htmlResponse3').html(data.LeadGen           || 0);
                $('#htmlResponse4').html(data.DemoRegistrations || 0);
            }
        });

        $.ajax({
            url: '{{ route("event.report.stats", $encodedId) }}',
            data: {
                startDate: $("#startDate").val(),
                endDate:   $("#endDate").val()
            },
            dataType: 'json'
        }).done(function(data) {
            if (data) {
                $('#htmlResponse7').html(data.AverageAgeMale   || 0);
                $('#htmlResponse8').html(data.AverageAgeFemale || 0);
            }
        });
    });
});

function popUPDemoReport() {
    // Auto-fire initial load with today's date
    $('#htmlResponse1,#htmlResponse2,#htmlResponse3,#htmlResponse4,#htmlResponse7,#htmlResponse8')
        .html('<img src="{{ asset("assets/images/ajax-loader.gif") }}" class="img-responsive img-circle"/>');

    jQuery.ajax({
        url: '{{ route("event.report.demo", $encodedId) }}',
        dataType: 'json'
    }).done(function(data) {
        if (data) {
            jQuery('#htmlResponse1').html(data.TotalDemoRides    || 0);
            jQuery('#htmlResponse2').html(data.Jumpstart         || 0);
            jQuery('#htmlResponse3').html(data.LeadGen           || 0);
            jQuery('#htmlResponse4').html(data.DemoRegistrations || 0);
        }
    });

    jQuery.ajax({
        url: '{{ route("event.report.stats", $encodedId) }}',
        dataType: 'json'
    }).done(function(data) {
        if (data) {
            jQuery('#htmlResponse7').html(data.AverageAgeMale   || 0);
            jQuery('#htmlResponse8').html(data.AverageAgeFemale || 0);
        }
    });
}
</script>

@endsection
