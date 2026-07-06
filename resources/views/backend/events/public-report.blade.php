@extends('layouts.backend.public')
@section('title', $title)
@section('content')
<!-- content @s -->
<div class="main-content">

    <!-- User-info navbar -->
    <nav class="navbar user-info-navbar" role="navigation">
        <!-- Left links -->
        <ul class="user-info-menu left-links list-inline list-unstyled">
            <li class="hidden-sm hidden-xs">
                &nbsp;&nbsp;
            </li>
            <li>
                <h3 style="margin-top:26px;">
                    Event: {{ $event->eventname }}
                </h3>
            </li>
        </ul>
    </nav>

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
            $('#popupDemoReports').on('click', function() {
                jQuery('#report-modal').modal('show');
                popUPDemoReport();
            });
            $('#popupNHRAReports').on('click', function() {
                jQuery('#NHRA-report-modal').modal('show');
                popUPNHRAReport();
            });

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

    {{-- ── Demo Reports & NHRA Reports buttons ── --}}
    <div style="font-weight:bold; font-size:15px; width:100%; padding: 10px 20px;">
        <div style="width:15%; float:left;">
            <a href="javascript:;" class="btn btn-info btn-lg" id="popupDemoReports">Demo Reports</a>
        </div>
        <div style="width:15%; float:left; margin-left:10px;">
            <a href="javascript:;" class="btn btn-info btn-lg" id="popupNHRAReports">NHRA Reports</a>
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
                                <span>Total Jumpstart</span>
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
                                <span>Total Demo Registrations</span>
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

                <!-- Charts Row 1: Active Military and Market Segments -->
                <div class="row" style="margin-top: 20px;">
                    <div class="col-sm-6">
                        <div class="chart-item-bg" style="background-color: #fff; padding: 15px; margin-bottom: 20px; border-radius: 4px; border: 1px solid #e4e4e4;">
                            <p style="font-size: 16px; color: #333; margin-bottom: 15px; font-weight: bold; text-transform: uppercase;">Active Military</p>
                            <div id="possible-answer-demo-chart" style="height:250px; position: relative;">
                                <div id="possible-answer-demo-loader" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 10;">
                                    <img src="{{ asset('assets/images/ajax-loader.gif') }}" class="img-responsive img-circle" />
                                </div>
                                <div style="height: 250px;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="chart-item-bg" style="background-color: #fff; padding: 15px; margin-bottom: 20px; border-radius: 4px; border: 1px solid #e4e4e4;">
                            <p style="font-size: 16px; color: #333; margin-bottom: 15px; font-weight: bold; text-transform: uppercase;">Market Segments</p>
                            <div id="customer-segments-demo-chart" style="height:250px; position: relative;">
                                <div id="customer-segments-demo-loader" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 10;">
                                    <img src="{{ asset('assets/images/ajax-loader.gif') }}" class="img-responsive img-circle" />
                                </div>
                                <div style="height: 250px;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts Row 2: Intent to Purchase and Age Demographics -->
                <div class="row">
                    <div class="col-sm-6">
                        <div class="chart-item-bg" style="background-color: #fff; padding: 15px; margin-bottom: 20px; border-radius: 4px; border: 1px solid #e4e4e4;">
                            <p style="font-size: 16px; color: #333; margin-bottom: 15px; font-weight: bold; text-transform: uppercase;">Intent to Purchase</p>
                            <div id="intent-to-purchase-demo-chart" style="height: 300px; position: relative;">
                                <div id="intent-to-purchase-demo-loader" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 10;">
                                    <img src="{{ asset('assets/images/ajax-loader.gif') }}" class="img-responsive img-circle" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="chart-item-bg" style="background-color: #fff; padding: 15px; margin-bottom: 20px; border-radius: 4px; border: 1px solid #e4e4e4;">
                            <p style="font-size: 16px; color: #333; margin-bottom: 15px; font-weight: bold; text-transform: uppercase;">Age Demographics</p>
                            <div id="age-demographics-demo-chart" style="height: 300px; position: relative;">
                                <div id="age-demographics-demo-loader" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 10;">
                                    <img src="{{ asset('assets/images/ajax-loader.gif') }}" class="img-responsive img-circle" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts Row 3: Currently Owned Brand -->
                <div class="row">
                    <div class="col-sm-12">
                        <div class="chart-item-bg" style="background-color: #fff; padding: 15px; margin-bottom: 20px; border-radius: 4px; border: 1px solid #e4e4e4;">
                            <p style="font-size: 16px; color: #333; margin-bottom: 15px; font-weight: bold; text-transform: uppercase;">Currently Owned Brand</p>
                            <div id="currently-owned-demo-chart" style="height: 250px; position: relative;">
                                <div id="currently-owned-demo-loader" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 10;">
                                    <img src="{{ asset('assets/images/ajax-loader.gif') }}" class="img-responsive img-circle" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts Row 4: Demo Rides by Group -->
                <div class="row">
                    <div class="col-sm-12">
                        <div class="chart-item-bg" style="background-color: #fff; padding: 15px; margin-bottom: 20px; border-radius: 4px; border: 1px solid #e4e4e4;">
                            <p style="font-size: 16px; color: #333; margin-bottom: 15px; font-weight: bold; text-transform: uppercase;">Demo Rides by Group</p>
                            <div id="report-demo-chart-wrapper" style="height: 250px; position: relative;">
                                <div id="report-demo-loader" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 10;">
                                    <img src="{{ asset('assets/images/ajax-loader.gif') }}" class="img-responsive img-circle" />
                                </div>
                                <div id="report-demo-chart" style="height: 250px;"></div>
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
     NHRA Reports Modal  (#NHRA-report-modal)
════════════════════════════════════════ --}}
<div class="modal fade custom-width" id="NHRA-report-modal">
    <div class="modal-dialog" style="width:90%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">NHRA Reports</h4>
            </div>
            <div class="modal-body">

                <!-- Date range row -->
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="control-label">Choose Start Date</label>
                            <div class="input-group">
                                <input type="text" id="NHRAstartDate" name="NHRAstartDate" class="form-control" data-format="mm/dd/yyyy">
                                <div class="input-group-addon"><a href="#"><i class="linecons-calendar"></i></a></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="control-label">Choose End Date</label>
                            <div class="input-group">
                                <input type="text" id="NHRAendDate" name="NHRAendDate" class="form-control" data-format="mm/dd/yyyy">
                                <div class="input-group-addon"><a href="#"><i class="linecons-calendar"></i></a></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4" style="margin-top:15px;">
                        <button class="btn btn-info btn-lg" id="NHRAReportBtn">Result</button>
                    </div>
                </div>

                <!-- Result counter widgets -->
                <div class="row">
                    <div class="col-sm-3">
                        <div class="xe-widget xe-counter xe-counter-info">
                            <div class="xe-icon"><i class="linecons-cloud"></i></div>
                            <div class="xe-label">
                                <strong class="num" id="htmlNHRAResponse2"><img src="{{ asset('assets/images/ajax-loader.gif') }}" class="img-responsive img-circle"/></strong>
                                <span>NHRA Lead Gen</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="xe-widget xe-counter xe-counter-blue">
                            <div class="xe-icon"><i class="linecons-cloud"></i></div>
                            <div class="xe-label">
                                <strong class="num" id="htmlNHRAResponse1"><img src="{{ asset('assets/images/ajax-loader.gif') }}" class="img-responsive img-circle"/></strong>
                                <span>Total Jumpstart</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="xe-widget xe-counter xe-counter-info">
                            <div class="xe-icon"><i class="linecons-cloud"></i></div>
                            <div class="xe-label">
                                <strong class="num" id="htmlNHRAResponse3"><img src="{{ asset('assets/images/ajax-loader.gif') }}" class="img-responsive img-circle"/></strong>
                                <span>Photo App</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="xe-widget xe-counter xe-counter-blue">
                            <div class="xe-icon"><i class="linecons-cloud"></i></div>
                            <div class="xe-label">
                                <strong class="num" id="htmlNHRAResponse4"><img src="{{ asset('assets/images/ajax-loader.gif') }}" class="img-responsive img-circle"/></strong>
                                <span>KIDS Registered</span>
                            </div>
                        </div>
                    </div>
                </div>
        <!-- Demo Charts -->
        

            </div>{{-- /modal-body --}}
            <div class="modal-footer">
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

    $("#NHRAstartDate").datepicker({ forceParse: false, autoclose: true });
    $('#NHRAstartDate').datepicker('setDate', new Date());
    $("#NHRAendDate").datepicker({ forceParse: false, autoclose: true });
    $('#NHRAendDate').datepicker('setDate', new Date());

    // Demo report date-range button
    $("#demoReportBtn").click(function() {
        loadDemoReportData($("#startDate").val(), $("#endDate").val());
    });

    // NHRA report date-range button
    $("#NHRAReportBtn").click(function() {
        $('#htmlNHRAResponse1, #htmlNHRAResponse2, #htmlNHRAResponse3, #htmlNHRAResponse4')
            .html('<img src="{{ asset("assets/images/ajax-loader.gif") }}" class="img-responsive img-circle" />');

        $.ajax({
            url: '{{ route("event.public-report.nhra", $encodedId) }}',
            data: {
                startDate: $("#NHRAstartDate").val(),
                endDate:   $("#NHRAendDate").val()
            },
            dataType: 'json'
        }).done(function(data) {
            if (data) {
                $('#htmlNHRAResponse1').html(data.Jumpstart || 0);
                $('#htmlNHRAResponse2').html(data.NHRACount || 0);
                $('#htmlNHRAResponse3').html(data.PhotoApp || 0);
                $('#htmlNHRAResponse4').html(data.totalKids || 0);
            }
        });
    });
});

function loadDemoReportData(startDate, endDate) {
    // Show loaders on widgets
    jQuery('#htmlResponse1, #htmlResponse2, #htmlResponse3, #htmlResponse4, #htmlResponse7, #htmlResponse8')
        .html('<img src="{{ asset("assets/images/ajax-loader.gif") }}" class="img-responsive img-circle" />');

    // Show loaders on charts
    jQuery('#possible-answer-demo-loader, #customer-segments-demo-loader, #intent-to-purchase-demo-loader, #age-demographics-demo-loader, #currently-owned-demo-loader, #report-demo-loader').show();
    
    // Empty charts/containers
    jQuery("#possible-answer-demo-chart div, #customer-segments-demo-chart div").empty();
    jQuery("#intent-to-purchase-demo-chart, #age-demographics-demo-chart, #currently-owned-demo-chart, #report-demo-chart").empty();

    var params = {};
    if (startDate) params.startDate = startDate;
    if (endDate) params.endDate = endDate;

    // 1. demoReports (TDR, Jumpstart, LeadGen, DemoRegistrations)
    jQuery.ajax({
        url: '{{ route("event.public-report.demo", $encodedId) }}',
        data: params,
        dataType: 'json'
    }).done(function(data) {
        if (data) {
            jQuery('#htmlResponse1').html(data.TotalDemoRides    || 0);
            jQuery('#htmlResponse2').html(data.Jumpstart         || 0);
            jQuery('#htmlResponse3').html(data.LeadGen           || 0);
            jQuery('#htmlResponse4').html(data.DemoRegistrations || 0);
        }
    });

    // 2. demoReports2 (Market Segments)
    jQuery.ajax({
        url: '{{ route("event.public-report.demo2", $encodedId) }}',
        data: params,
        dataType: 'json'
    }).done(function(data) {
        if (data) {
            var doughnut1_data_source_demo = [
                {segment: "Young Adult", val: data.segment1 || 0},
                {segment: "Core", val: data.segment2 || 0},
                {segment: "Female", val: data.segment3 || 0},
                {segment: "Hispanic", val: data.segment4 || 0},
                {segment: "African American", val: data.segment5 || 0},
                {segment: "Other", val: data.segment6 || 0},
            ];

            var segmentTotalDemo = data.segmentTotal || 1;

            jQuery("#customer-segments-demo-chart div").dxPieChart({
                dataSource: doughnut1_data_source_demo,
                tooltip: {
                    enabled: true,
                    customizeText: function() {
                        return this.argumentText + "<br/>" + this.valueText + " (" + (((parseInt(this.valueText)) / parseInt(segmentTotalDemo)) * 100).toFixed(2) + "%)";
                    }
                },
                size: { height: 250 },
                legend: { visible: true },
                series: [{
                    type: "doughnut",
                    argumentField: "segment",
                    label: { visible: true },
                }],
                palette: ['#fcd036','#0E62C7','#00b19d','#F5821F','#CE3A3A','#14722A'],
            });
        }
        jQuery("#customer-segments-demo-loader").hide();
    });

    // 3. statsReports (Demographics, Active Military, Brand Charts)
    jQuery.ajax({
        url: '{{ route("event.public-report.stats", $encodedId) }}',
        data: params,
        dataType: 'json'
    }).done(function(data) {
        if (data) {
            jQuery('#htmlResponse7').html(data.AverageAgeMale   || 0);
            jQuery('#htmlResponse8').html(data.AverageAgeFemale || 0);

            // Active Military Doughnut Chart
            var doughnut_military = [
                {possibleAnswer: "YES, ACTIVE", val: data.possibleAnswerYesActive || 0},
                {possibleAnswer: "YES, VETERAN", val: data.possibleAnswerYesVeteran || 0},
                {possibleAnswer: "NO", val: data.possibleAnswerNo || 0},
            ];
            var totalAnswer = data.possibleAnswerTotal || 1;

            jQuery("#possible-answer-demo-chart div").dxPieChart({
                dataSource: doughnut_military,
                tooltip: {
                    enabled: true,
                    customizeText: function() {
                        return this.argumentText + "<br/>" + this.valueText + " (" + ((parseInt(this.valueText) / parseInt(totalAnswer)) * 100).toFixed(2) + "%)";
                    }
                },
                size: { height: 250 },
                legend: { visible: true },
                series: [{
                    type: "doughnut",
                    argumentField: "possibleAnswer",
                    label: { visible: true },
                }],
                palette: ['#68b828','#7c38bc','#0e62c7'],
            });

            // Intent to Purchase Bar Chart
            jQuery("#intent-to-purchase-demo-chart").dxChart({
                dataSource: [
                    {stat: "Less than 3 Months", time: data.IntendToPurchaseGraph?.LessThanThreeMonths || 0},
                    {stat: "3 Months to a Year", time: data.IntendToPurchaseGraph?.ThreeToTwelveMonths || 0},
                    {stat: "More than a Year",   time: data.IntendToPurchaseGraph?.MoreThanOneYear || 0},
                    {stat: "Not Sure",           time: data.IntendToPurchaseGraph?.NotSure || 0}
                ],
                tooltip: {
                    enabled: true,
                    customizeText: function() { return this.argumentText + "<br/>" + this.valueText; }
                },
                series: {
                    argumentField: "stat",
                    valueField: "time",
                    name: "Time",
                    type: "bar",
                    color: '#c00000',
                    label: { visible: true },
                },
                commonAxisSettings: {
                    label: { visible: true },
                    grid: { visible: true }
                },
                legend: { visible: true },
                argumentAxis: { valueMarginsEnabled: true }
            });

            // Age Demographics Bar Chart
            jQuery("#age-demographics-demo-chart").dxChart({
                dataSource: [
                    {stat: "Under 30", age: data.UnderThirty || 0},
                    {stat: "30 - 40",  age: data.BetweenThirtyFourty || 0},
                    {stat: "40 - 50",  age: data.BetweenFourtyFifty || 0},
                    {stat: "Above 50", age: data.AboveFifty || 0}
                ],
                tooltip: {
                    enabled: true,
                    customizeText: function() { return this.argumentText + "<br/>" + this.valueText; }
                },
                series: {
                    argumentField: "stat",
                    valueField: "age",
                    name: "Age Average",
                    type: "bar",
                    color: '#7c38bc',
                    label: { visible: true },
                },
                commonAxisSettings: {
                    label: { visible: true },
                    grid: { visible: true }
                },
                legend: { visible: true },
                argumentAxis: { valueMarginsEnabled: true }
            });

            // Currently Owned Brand Chart
            jQuery("#currently-owned-demo-chart").dxChart({
                dataSource: [
                    {stat: "Harley-Davidson", brand: data.CurrentlyOwnedGraph?.HarleyDavidson || 0},
                    {stat: "BMW",             brand: data.CurrentlyOwnedGraph?.BMW || 0},
                    {stat: "Ducati",          brand: data.CurrentlyOwnedGraph?.Ducati || 0},
                    {stat: "Honda",           brand: data.CurrentlyOwnedGraph?.Honda || 0},
                    {stat: "Kawasaki",        brand: data.CurrentlyOwnedGraph?.Kawasaki || 0},
                    {stat: "Suzuki",          brand: data.CurrentlyOwnedGraph?.Suzuki || 0},
                    {stat: "Triumph",         brand: data.CurrentlyOwnedGraph?.Triumph || 0},
                    {stat: "Yamaha",          brand: data.CurrentlyOwnedGraph?.Yamaha || 0},
                    {stat: "Other",           brand: data.CurrentlyOwnedGraph?.Other || 0},
                ],
                tooltip: {
                    enabled: true,
                    customizeText: function() { return this.argumentText + "<br/>" + this.valueText; }
                },
                series: {
                    argumentField: "stat",
                    valueField: "brand",
                    name: "Brand",
                    type: "bar",
                    color: '#F5821F',
                    label: { visible: true },
                },
                commonAxisSettings: {
                    label: { visible: true },
                    grid: { visible: true }
                },
                legend: { visible: true },
                argumentAxis: { valueMarginsEnabled: true }
            });
        }
        jQuery("#possible-answer-demo-loader, #intent-to-purchase-demo-loader, #age-demographics-demo-loader, #currently-owned-demo-loader").hide();
    });

    // 4. demoGraphReports (Demo rides by group)
    jQuery.ajax({
        url: '{{ route("event.public-report.graph", $encodedId) }}',
        data: params,
        dataType: 'json'
    }).done(function(data) {
        var datasourceVar = [];
        if (data) {
            jQuery.each(data, function(index, val) {
                datasourceVar.push({
                    stat: index,
                    age: val.count || 0
                });
            });
        }

        jQuery("#report-demo-chart").dxChart({
            dataSource: datasourceVar,
            tooltip: {
                enabled: true,
                customizeText: function() { return this.argumentText + "<br/>" + this.valueText; }
            },
            series: {
                argumentField: "stat",
                valueField: "age",
                name: "Number of Rides",
                type: "bar",
                color: '#7c38bc',
                label: { visible: true },
            },
            commonAxisSettings: {
                label: { visible: true },
                grid: { visible: true }
            },
            legend: { visible: true },
            argumentAxis: { valueMarginsEnabled: true }
        });
        jQuery("#report-demo-loader").hide();
    });
}

function popUPDemoReport() {
    jQuery('#report-modal').modal('show');
    loadDemoReportData();
}

function popUPNHRAReport() {
    $('#htmlNHRAResponse1, #htmlNHRAResponse2, #htmlNHRAResponse3, #htmlNHRAResponse4')
        .html('<img src="{{ asset("assets/images/ajax-loader.gif") }}" class="img-responsive img-circle" />');

    jQuery.ajax({
        url: '{{ route("event.public-report.nhra", $encodedId) }}',
        dataType: 'json'
    }).done(function(data) {
        if (data) {
            $('#htmlNHRAResponse1').html(data.Jumpstart || 0);
            $('#htmlNHRAResponse2').html(data.NHRACount || 0);
            $('#htmlNHRAResponse3').html(data.PhotoApp || 0);
            $('#htmlNHRAResponse4').html(data.totalKids || 0);
        }
    });
}
</script>

@endsection
