@extends('layouts.backend.app')
@section('title', $title)
@section('content')
<!-- content @s -->
<div class="main-content">
    <!-- Content Header section -->
    @include('layouts.backend.content_header', compact('title'))

    <?php /* if(isset($_SESSION['msg']) && !empty($_SESSION['msg'])){ ?>
    <div class="dx-warning">
        <div>
            <p><?php echo $_SESSION['msg'];?></p>
        </div>
    </div>
    <?php }
        unset($_SESSION['msg']); */
    ?>

    <form id="EventForm" method="post" action="Action.php" class="form-wizard validate" novalidate>
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
        <script type="text/javascript">
            jQuery(document).ready(function($){
                $(".exportData").click(function(){
                    //console.log($('input[name="type"]').val());
                });

                $('input[name="type"]').click(function(){
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

                $(".previous").click(function(){
                    if($("div.tab-content").find(".active").attr("id")=="fwv-3"){
                        $("#EndSubmitForm").hide();
                    }
                    $(".next").show();
                });

                $("#EndSubmitForm").click(function(){
                    if($('input[name="type"]:checked').val()=="c"){
                        $(".next").hide();
                        window.open("http://honda.kickstartuser.com/Export.php?EventID="+$("#eventID").val()+"&action=cust", "_blank");
                    }
                    else if($('input[name="type"]:checked').val()=="s"){
                        $(".next").hide();
                        window.open("http://honda.kickstartuser.com/Export.php?EventID="+$("#eventID").val()+"&action=survey&SurveyID="+$("#JumpStartSurveyEdit").val(), "_blank");
                    }
                    else if($('input[name="type"]:checked').val()=="y"){
                        $(".next").hide();
                        window.open("http://honda.kickstartuser.com/Export.php?EventID="+$("#eventID").val()+"&action=honda", "_blank");
                    }
                });

                $(".next").click(function(){
                    //console.log($(this).text());
                    //console.log($("div.tab-content").find(".active").attr("id"));
                    if($("div.tab-content").find(".active").attr("id")=="fwv-2"){
                        if($('input[name="type"]:checked').val()=="c"){ console.log("c");
                            $(".next").hide();
                            $("#survey-show").hide();
                            $("#data-loading").show();
                        }
                        else if($('input[name="type"]:checked').val()=="s"){ console.log("s");
                            $(".next").hide();
                            $("#survey-show").show();
                            $("#data-loading").hide();
                            $("#EndSubmitForm").show();
                        }
                        else if($('input[name="type"]:checked').val()=="y"){ console.log("y");
                            $(".next").hide();
                            $("#survey-show").hide();
                            $("#data-loading").show();
                        }
                    }
                });
            });
        </script>
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
                            <label class="control-label" for="social">Select Event</label>
                            <select name="eventID" class="selectboxit" id="eventID">
                                <optgroup label="Saved Social Media Settings">
                                    <?php
                                    if(isset($events->Success) && $events->Success==1){
                                        foreach ($events->Event as $skey => $event) {
                                            echo '<option value="'.$event->EventID.'">'.$event->EventName.'</option>';
                                        } }?>
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
                                <label class="control-label" for="social"><strong>Please Select Survey</strong></label>
                                <select name="jumpstartsurvey" class="selectboxit" id="JumpStartSurveyEdit">
                                    <optgroup label="Saved Survey Data">
                                    <?php
                                        if(isset($surveys->Success) && $surveys->Success==1){
                                            foreach ($surveys->Survey as $skey => $survey) {
                                                echo '<option value="'.$survey->SurveyID.'">'.$survey->SurveyName.'</option>';
                                            } }?>
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

            var eventData;
            var waiverData;
            var userData;
            var socialMediaData;
            var truckData;
        });

    </script>
@endpush
