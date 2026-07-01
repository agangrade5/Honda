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
    <div class="row">
        <div class="col-md-2">
            <div class="form-group">
                <label class="control-label">Choose a year:</label>
                <form method="post" id="YearFilterForm">
                    <script type="text/javascript">
                        function YearChange() {
                            $("#YearFilterForm").submit();
                        }
                    </script>
                    <select onchange="YearChange();" class="form-control" id="EventYear" name="EventYear">
                        <option value="">Select Year</option>
                        <?php /* foreach($years->Year as $year){?>
                        <option <?php if($year->YearID==$selectedYear){ echo "selected='selected'";}?>
                            value="<?php echo $year->YearID;?>"><?php echo $year->YearName;?></option>
                        <?php } */ ?>
                    </select>
                    <input type="hidden" name="action" value="filter">
                    <input type="hidden" name="controller" value="year">
                </form>
            </div>
        </div>
    </div>

    <?php //if(Auth::getUsers()->userlevel!=3 && Auth::getUsers()->userlevel!=5 && Auth::getUsers()->userlevel!=7 && Auth::getUsers()->userlevel!=9){ ?>
    <div class="row">
        <div class="col-xs-6">
            <!-- Multi Report -->
            <form id="MultiReportForm" action="Action.php">
                <input type="hidden" name="ids" value="">
            </form>
            <script type="text/javascript">
                // A $( document ).ready() block.
                $(document).ready(function() {

                });
            </script>
            <a href="javascript:;" style="display:none;width: 200px !important;" class="btn btn-info btn-md btnTopCus"
                id="MultiReport">View Multi-Report</a>
        </div>
        <div class="col-xs-6 text-right">
            <a class="btn btn-secondary btn-md btnTopCus" href="EventWizard-CreateEvent.php">
            <span class=""> <i class="fa fa-plus"></i> Add Event</span>
            </a>
        </div>
    </div>
    <?php //} ?>

    <?php
    if(isset($events->Events) && $events->Success==1){
        $tableID = '';
        foreach ($events->Events as $rkey => $revent) {
            $tableID = str_replace(' ', '_', trim($rkey));
    ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><?php echo $rkey; ?></h3>
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
                  $("#Region_<?php echo $tableID; ?>").click(function() {
                    $(".<?php echo $tableID; ?>").prop('checked', false);
                    if (this.checked) {
                      $(".<?php echo $tableID; ?>").prop('checked', true);
                    }
                  });
                });
            </script>
            <table class="table table-bordered table-striped" id="brazilTable-<?php echo $tableID; ?>">
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
                    <?php foreach ($revent as $key => $event) { ?>
                    <tr>
                        <td><?php $event->EventID;?></td>
                        <td><?php $event->EventName;?></td>
                        <td><?php date("m-d-Y",strtotime($event->EventStartDate));?></td>
                        <td><?php date("m-d-Y",strtotime($event->EventEndDate));?></td>
                        <td><?php $event->EventCampaignCode;?></td>
                        <td><?php $event->EventTruck;?></td>
                        <td>
                            <input type="hidden" id="EventPhotoAppEmail<?php echo $event->EventID;?>"
                                value="<?php echo $event->EventPhotoAppEmail;?>">
                            <input type="hidden" id="EventWelcomeEmail<?php echo $event->EventID;?>"
                                value="<?php echo $event->EventWelcomeEmail;?>">
                            <input type="hidden" id="EventScheduledEmail<?php echo $event->EventID;?>"
                                value="<?php echo $event->EventScheduledEmail;?>">
                            <input type="hidden" id="EventTyEmail<?php echo $event->EventID;?>"
                                value="<?php echo $event->EventTyEmail;?>">
                            <input type="hidden" id="EventPrEmail<?php echo $event->EventID;?>"
                                value="<?php echo $event->EventPrEmail;?>">
                            <input type="hidden" id="EventSalesEmail<?php echo $event->EventID;?>"
                                value="<?php echo $event->EventSalesEmail;?>">
                            <input type="hidden" id="EventCountry<?php echo $event->EventID;?>"
                                value="<?php echo $event->EventCountry;?>">
                            <input type="hidden" id="EventBikesAndTimes<?php echo $event->EventID;?>"
                                value="<?php echo $event->EventBikesAndTimes;?>">
                            <input type="hidden" id="EventLeadGenSurvey<?php echo $event->EventID;?>"
                                value="<?php echo $event->EventLeadGenSurvey;?>">
                            <input type="hidden" id="EventDemoSurvey<?php echo $event->EventID;?>"
                                value="<?php echo $event->EventDemoSurvey;?>">
                            <input type="hidden" id="EventPostRideSurvey<?php echo $event->EventID;?>"
                                value="<?php echo $event->EventPostRideSurvey;?>">
                            <input type="hidden" id="EventJumpStartSurvey<?php echo $event->EventID;?>"
                                value="<?php echo $event->EventJumpStartSurvey;?>">
                            <input type="hidden" id="EventCampaignCode<?php echo $event->EventID;?>"
                                value="<?php echo $event->EventCampaignCode;?>">
                            <input type="hidden" id="EventWebsite<?php echo $event->EventID;?>"
                                value="<?php echo $event->EventWebsite;?>">
                            <!-- my call-->
                            <input type="hidden" id="TrikeTrainingTime<?php echo $event->EventID;?>"
                                value="<?php echo $event->TrikeTrainingTime;?>">
                            <input type="hidden" id="EventStartDate<?php echo $event->EventID;?>"
                                value="<?php echo $event->EventStartDate;?>">
                            <input type="hidden" id="EventEndDate<?php echo $event->EventID;?>"
                                value="<?php echo $event->EventEndDate;?>">
                            <input type="hidden" id="EventWaiverID<?php echo $event->EventID;?>"
                                value="<?php echo $event->EventWaiverID;?>">
                            <input type="hidden" id="EventJumpStart<?php echo $event->EventID;?>"
                                value="<?php echo $event->EventJumpStart;?>">
                            <input type="hidden" id="EventLeadGen<?php echo $event->EventID;?>"
                                value="<?php echo $event->EventLeadGen;?>">
                            <input type="hidden" id="EnableSms<?php echo $event->EventID;?>"
                                value="<?php echo $event->EnableSms;?>">
                            <input type="hidden" id="EventDemo<?php echo $event->EventID;?>"
                                value="<?php echo $event->EventDemo;?>">
                            <input type="hidden" id="EventPRSurvey<?php echo $event->EventID;?>"
                                value="<?php echo $event->EventPRSurvey;?>">
                            <input type="hidden" id="EventTrike<?php echo $event->EventID;?>"
                                value="<?php echo $event->EventTrike;?>">
                            <input type="hidden" id="EventPhotoApp<?php echo $event->EventID;?>"
                                value="<?php echo $event->EventPhotoApp;?>">
                            <input type="hidden" id="PhotoAppCampaignCode<?php echo $event->EventID;?>"
                                value="<?php echo $event->EventPhotoAppCC;?>">
                            <input type="hidden" id="EventLiveWireJumpStart<?php echo $event->EventID;?>"
                                value="<?php echo $event->EventLiveWireJumpStart;?>">
                            <input type="hidden" id="EventLivewireLeadGen<?php echo $event->EventID;?>"
                                value="<?php echo $event->EventLivewireLeadGen;?>">
                            <input type="hidden" id="EventJumpStartWaiver<?php echo $event->EventID;?>"
                                value="<?php echo $event->EventJumpStartWaiver;?>">
                            <input type="hidden" id="EventJumpStartWaiverUnderAge<?php echo $event->EventID;?>"
                                value="<?php echo $event->EventJumpStartWaiverUnderAge;?>">
                            <input type="hidden" id="EventLeadGenWaiver<?php echo $event->EventID;?>"
                                value="<?php echo $event->EventLeadGenWaiver;?>">
                            <input type="hidden" id="EventSmsTemplateId<?php echo $event->EventID;?>"
                                value="<?php echo $event->EventSmsTemplateId;?>">
                            <input type="hidden" id="EventDemoWaiver_<?php echo $event->EventID;?>"
                                value="<?php echo $event->EventDemoWaiver;?>">
                            <input type="hidden" id="EventGuardianWaiver<?php echo $event->EventID;?>"
                                value="<?php echo $event->EventGuardianWaiver;?>">
                            <input type="hidden" id="EventDemoWaiver2<?php echo $event->EventID;?>"
                                value="<?php echo $event->EventDemoWaiver2;?>">
                            <input type="hidden" id="EventDemoPassengerWaiver_<?php echo $event->EventID;?>"
                                value="<?php echo $event->EventDemoPassengerWaiver;?>">
                            <input type="hidden" id="EventDemoPassengerWaiver2<?php echo $event->EventID;?>"
                                value="<?php echo $event->EventDemoPassengerWaiver2;?>">
                            <input type="hidden" id="TrikeWaiver<?php echo $event->EventID;?>"
                                value="<?php echo $event->TrikeWaiver;?>">
                            <input type="hidden" id="TrikePassengerWaiver<?php echo $event->EventID;?>"
                                value="<?php echo $event->TrikePassengerWaiver;?>">
                            <!--march27-->
                            <input type="hidden" id="Eventalloweventpreregistrations<?php echo $event->EventID;?>"
                                value="<?php echo $event->alloweventpreregistrations;?>">
                            <input type="hidden" id="Eventregistrationsurveyid<?php echo $event->EventID;?>"
                                value="<?php echo $event->registrationsurveyid;?>">
                            <input type="hidden" id="Eventregistrationsuccessfulemailtemplate<?php echo $event->EventID;?>"
                                value="<?php echo $event->registrationsuccessfulemailtemplate;?>">
                            <input type="hidden" id="Eventwaitlisttemplateemailtemplate<?php echo $event->EventID;?>"
                                value="<?php echo $event->Eventwaitlisttemplateemailtemplate;?>">
                            <input type="hidden" id="EventPreRegistrationEmailQty<?php echo $event->EventID;?>"
                                value="<?php echo $event->EventPreRegistrationEmailQty;?>">
                            <input type="hidden" id="Eventremindertemplateemailtemplate<?php echo $event->EventID;?>"
                                value="<?php echo $event->remindertemplateemailtemplate;?>">
                            <input type="hidden" id="Eventremindertemplate2emailtemplate<?php echo $event->EventID;?>"
                                value="<?php echo $event->remindertemplate2emailtemplate;?>">
                            <input type="hidden" id="Eventadditionaldetails<?php echo $event->EventID;?>"
                                value="<?php echo $event->additionaldetails;?>">
                            <input type="hidden" id="EventRegistrationDeadlinePST<?php echo $event->EventID;?>"
                                value="<?php echo $event->eventregistrationdeadlinePST;?>">
                            <input type="hidden" id="Eventreminderdate1<?php echo $event->EventID;?>"
                                value="<?php echo $event->eventreminderdate1;?>">
                            <input type="hidden" id="Eventreminderdate2<?php echo $event->EventID;?>"
                                value="<?php echo $event->eventreminderdate2;?>">
                            <input type="hidden" id="EventLiveWireJumpStartWaiver<?php echo $event->EventID;?>"
                                value="<?php echo $event->EventLiveWireJumpStartWaiver;?>">
                            <input type="hidden" id="EventLiveWireJumpStartUnderAgeWaiver<?php echo $event->EventID;?>"
                                value="<?php echo $event->EventLiveWireJumpStartUnderAgeWaiver;?>">
                            <input type="hidden" id="EventLiveWireLeadGenWaiver<?php echo $event->EventID;?>"
                                value="<?php echo $event->EventLiveWireLeadGenWaiver;?>">
                            <input type="hidden" id="EventTruckBlob<?php echo $event->EventID;?>"
                                value='<?php echo json_encode(unserialize($event->EventTruckBlob));?>'>
                            <input type="hidden" id="EventDealers<?php echo $event->EventID;?>"
                                value='<?php echo !empty($event->EventDealers)?json_encode(unserialize($event->EventDealers)):"";?>'>

                            <?php if(Auth::getUsers()->userlevel!=7 && Auth::getUsers()->userlevel!=3 && Auth::getUsers()->userlevel!=5 && Auth::getUsers()->userlevel!=7 && Auth::getUsers()->userlevel!=9){ ?>
                            <a class="btn btn-secondary btn-sm btn-icon icon-left edit" data-toggle="modal"
                                data-id="<?php echo $event->EventID;?>">Edit</a>
                            <?php } ?>

                            <a href="javascript:;" class="reportLink btn btn-info btn-sm btn-icon icon-left">
                            Reports
                            </a>

                            <?php if(Auth::getUsers()->userlevel==1){ ?>
                                <a href="javascript:;" id="<?php echo $event->EventID;?>"
                                    onclick="jQuery('#event-modal-delete').modal('show');" class="btn btn-danger btn-icon">
                                <i class="icon-white icon-heart"></i> Delete
                                </a>
                            <?php } ?>
                            <?php
                            $atvlink = '';
                            if( ($rkey == 'ATV') && ($event->alloweventpreregistrations == 1)) {
                                $atvlink = 'atv/?eventid='.base64_encode($event->EventID);
                            ?>
                                <input type="hidden" id="link<?php echo $event->EventID;?>" value="<?php echo $atvlink;?>">
                            <?php } else if($rkey != 'ATV' && $event->alloweventpreregistrations == 1) {
                                $atvlink = 'register/?eventid='.base64_encode($event->EventID);
                            ?>
                                <input type="hidden" id="link<?php echo $event->EventID;?>" value="<?php echo $atvlink;?>">
                            <?php } else { ?>
                                <input type="hidden" id="link<?php echo $event->EventID;?>" value="">
                            <?php } ?>

                            <input type="hidden" id="region<?php echo $event->EventID;?>" value="<?php echo $tableID;?>">
                            <input type="hidden" id="eventid_base64<?php echo $event->EventID;?>"
                                value="<?php echo base64_encode($event->EventID);?>">
                    </tr>
                    <?php } ?>
                    <script>
                        $(document).ready(function() {
                            $(".edit").click(function() {
                                //var id=$(this).attr('data-id');
                                //alert(id);
                                //alert($(this).attr('data-id'));
                                //$("#eventName").val("");
                                $('#event-modal').modal('show');
                            });

                            $("#brazilTable-<?php echo $tableID; ?>").dataTable({
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
    <?php } } ?>

    <!-- Main Footer -->
    @include('layouts.backend.footer')

</div>
<!-- content @e -->

<script>
    $(document).ready(function() {
        //console.log("ready!");
        var GEAdddress = '';
        $("button.btn-info").click(function() {
            //console.log($(this).text());
            if ($(this).text() == "Create") {
                //console.log("add");
                $("#EventForm").submit();
            } else if ($(this).text() == "Save Changes") {
                //check for valiudation
                var flag_valid = true;
                if (typeof $("#EventStartDateEdit").val() == 'undefined' || $("#EventStartDateEdit").val() == "") {
                    //console.log("1");
                    $("#EventStartDateError").html("<p style='color:#cc3f44;'>This field is required.</p>");
                    flag_valid = false;
                } else {
                    $("#EventStartDateError").html("");
                }
                if (typeof $("#EventEndDateEdit").val() == 'undefined' || $("#EventEndDateEdit").val() == "") {
                    //console.log("2");
                    $("#EventEndDateEditError").html("<p style='color:#cc3f44;'>This field is required.</p>");
                    flag_valid = false;
                } else {
                    $("#EventEndDateEditError").html("");
                }
                if ($("#alloweventpreregistrations").is(':checked')) {
                    $("#getLinkDiv").show();
                    if (typeof $("#EventReminderTemp2").val() == 'undefined' || $("#EventReminderTemp2").val() == "") {
                        //console.log("1");
                        $("#EventReminderTemp2Error").html("<p style='color:#cc3f44;'>This field is required.</p>");
                        flag_valid = false;
                    } else {
                        $("#EventReminderTemp2Error").html("");
                    }
                    if (typeof $("#EventReminderTemp1").val() == 'undefined' || $("#EventReminderTemp1").val() == "") {
                        //console.log("1");
                        $("#EventReminderTemp1Error").html("<p style='color:#cc3f44;'>This field is required.</p>");
                        flag_valid = false;
                    } else {
                        $("#EventReminderTemp1Error").html("");
                    }
                }
                if (flag_valid) {
                    $("#EventEditForm").submit();
                } else {
                    return false;
                }
            } else if ($(this).text() == "Delete") {
                $("#EventDelete").submit();
            }
        });

        $("a.btn-danger").click(function() {
            $("#DeleteEventID").val($(this).parent().prev().prev().prev().prev().prev().prev().text());
        });

        $("a.btn-secondary").click(function() {
            var TmpEventId = $(this).parent().prev().prev().prev().prev().prev().prev().text();
            $("#EventNameEdit").val($(this).parent().prev().prev().prev().prev().prev().text());
            //console.log($("#EventCountry" + TmpEventId).val());
            $("#EventCountryEdit").select2("val", $("#EventCountry" + TmpEventId).val());

            //Write Ajax here
            //var  EventCountry = $("#EventCountry"+TmpEventId).val();
            $('#EventCountryEdit').trigger('change');

            //console.log(EAdddressArray[3]);
            //var EAddress = $("#EventAddress"+TmpEventId).val();
            //var EAdddressArray = EAddress.split(",");
            //$("#EventAddressStreetEdit").val(EAdddressArray[0]);
            //$("#EventAddressStreet2Edit").val(EAdddressArray[1]);
            //$("#EventAddressCityEdit").val(EAdddressArray[2]);
            //alert(EAdddressArray[3]);

            //$("#EventAddressZipEdit").val(EAdddressArray[4]);

            //$("#EventPhoneEdit").val($("#EventPhone"+TmpEventId).val());
            //$("#EventManagerEdit").val($("#EventManager"+TmpEventId).val());
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

                //Default select value.
                $("#EventJumpStartWaiver").data("selectBox-selectBoxIt").selectOption($("#EventJumpStartWaiver" +
                    TmpEventId).val());
                $("#EventJumpStartWaiverUnderAge").data("selectBox-selectBoxIt").selectOption($(
                    "#EventJumpStartWaiverUnderAge" + TmpEventId).val());
            }

            //march27
            if ($("#Eventalloweventpreregistrations" + TmpEventId).val() == 1) {
                $('#alloweventpreregistrations').attr("checked", true);
                $("#getLinkDiv").show();

                //Add Values
                $("#eventwalletpassterms").val($("#Eventeventwalletpassterms" + TmpEventId).val());

                if (!$("#Eventregistrationsurveyid" + TmpEventId).val()) {
                    $("#Eventregistrationsurveyid" + TmpEventId).val($("#registrationsurveyid").val());
                }

                $("#EventPreRegistrationEmailQty").val($("#EventPreRegistrationEmailQty" + TmpEventId).val());
                $("#registrationsurveyid").data("selectBox-selectBoxIt").selectOption($("#Eventregistrationsurveyid" +
                    TmpEventId).val());
                $("#registrationsuccessfulemailtemplate").data("selectBox-selectBoxIt").selectOption($(
                    "#Eventregistrationsuccessfulemailtemplate" + TmpEventId).val());

                $("#waitlisttemplateemailtemplateEdit").data("selectBox-selectBoxIt").selectOption($(
                    "#Eventwaitlisttemplateemailtemplate" + TmpEventId).val());

                //$("#waitlisttemplateemailtemplate").data("selectBox-selectBoxIt").selectOption($("#Eventremindertemplateemailtemplate"+TmpEventId).val());

                $("#remindertemplateemailtemplate").data("selectBox-selectBoxIt").selectOption($(
                    "#Eventremindertemplateemailtemplate" + TmpEventId).val());

                $("#remindertemplate2emailtemplate").data("selectBox-selectBoxIt").selectOption($(
                    "#Eventremindertemplate2emailtemplate" + TmpEventId).val());

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

            $('#EventLeadGen').prop('checked', false); // Unchecks it
            $("#EventLeadGenWaiverDiv").hide();
            if ($("#EventLeadGen" + TmpEventId).val() == 1) {
                $('#EventLeadGen').prop('checked', true);
                $("#EventLeadGenWaiverDiv").show();
                $("#EventLeadGenWaiver").data("selectBox-selectBoxIt").selectOption($("#EventLeadGenWaiver" +
                    TmpEventId).val());
            }

            $('#EnableSms').prop('checked', false); // Unchecks it
            $("#eventSmsTemplateIdDiv").hide();
            if ($("#EnableSms" + TmpEventId).val() == 1) {
                $('#EnableSms').prop('checked', true);
                $("#eventSmsTemplateIdDiv").show();
                $("#EventSmsTemplateId").data("selectBox-selectBoxIt").selectOption($("#EventSmsTemplateId" +
                    TmpEventId).val());
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
                $("#EventDemoWaiver").data("selectBox-selectBoxIt").selectOption($("#EventDemoWaiver_" + TmpEventId)
                    .val());

                $("#EventDemoWaiverDiv2").show();
                $("#EventDemoWaiver2").data("selectBox-selectBoxIt").selectOption($("#EventDemoWaiver2" + TmpEventId)
                    .val());

                $("#EventDemoPassengerWaiverDiv").show();

                $("#EventDemoPassengerWaiver").data("selectBox-selectBoxIt").selectOption($(
                    "#EventDemoPassengerWaiver_" + TmpEventId).val());

                $("#EventDemoPassengerWaiverDiv2").show();
                $("#EventDemoPassengerWaiver2").data("selectBox-selectBoxIt").selectOption($(
                    "#EventDemoPassengerWaiver2" + TmpEventId).val());

                $("#EventGuardianWaiverDiv").show();
                $("#EventGuardianWaiver").data("selectBox-selectBoxIt").selectOption($("#EventGuardianWaiver" +
                    TmpEventId).val());

                //$("#EventDemoPassengerWaiverDiv2").show();
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
                $("#TrikePassengerWaiver").data("selectBox-selectBoxIt").selectOption($("#TrikePassengerWaiver" +
                    TmpEventId).val());
            }

            $('#EventLiveWireJumpStart').prop("checked", false);
            $("#EventLiveWireJumpStartDiv").hide();
            $("#EventLiveWireJumpStartUnderAgeDiv").hide();

            if ($("#EventLiveWireJumpStart" + TmpEventId).val() == 1) {
                $('#EventLiveWireJumpStart').prop("checked", true);
                $("#EventLiveWireJumpStartDiv").show();

                $("#EventLiveWireJumpStartWaiver").data("selectBox-selectBoxIt").selectOption($(
                    "#EventLiveWireJumpStartWaiver" + TmpEventId).val());

                $("#EventLiveWireJumpStartUnderAgeDiv").show();
                $("#EventLiveWireJumpStartUnderAgeWaiver").data("selectBox-selectBoxIt").selectOption($(
                    "#EventLiveWireJumpStartUnderAgeWaiver" + TmpEventId).val());
            }
            $('#EventLivewireLeadGen').prop("checked", false);
            $("#EventLiveWireLeadGenWaiverDiv").hide();

            if ($("#EventLivewireLeadGen" + TmpEventId).val() == 1) {
                $('#EventLivewireLeadGen').prop("checked", true);
                $("#EventLiveWireLeadGenWaiverDiv").show();
                $("#EventLiveWireLeadGenWaiver").data("selectBox-selectBoxIt").selectOption($(
                    "#EventLiveWireLeadGenWaiver" + TmpEventId).val());
            }

            //$("#UserIDEdit").select2("val", "7");
            //$("#UserIDEdit").select2('data',[{id: 1, text: 'Caleb Halford'},{id: 7, text: 'Natalie Jahnke'}]);

            //Manage Truck Blob.
            var EventTruckBlobJSON = $("#EventTruckBlob" + TmpEventId).val();
            var EventTruckBlobArray = [];
            $.each(JSON.parse(EventTruckBlobJSON), function(index, ETBV) {
                EventTruckBlobArray.push({
                    id: ETBV,
                    text: $('#TruckIDEdit option[value="' + ETBV + '"]').text()
                });
            });
            $("#TruckIDEdit").select2('data', EventTruckBlobArray);

            //Manage Dealer Blob.
            var EventDealersJSON = $("#EventDealers" + TmpEventId).val();
            var EventDealersArray = [];
            if (EventDealersJSON != '') {
                $.each(JSON.parse(EventDealersJSON), function(index, EDV) {
                    EventDealersArray.push({
                        id: EDV,
                        text: $('#DealerIDEdit option[value="' + EDV + '"]').text()
                    });
                });
            }
            $("#DealerIDEdit").select2('data', EventDealersArray);
            if (parseInt($("#EventBikesAndTimes" + TmpEventId).val())) {
                //console.log("add" + $("#EventBikesAndTimes" + TmpEventId).val());
                //console.log("true");
                //$("#eventbikesandtimes").addClass('cbr-checked');
                $("#eventbikesandtimes").prop('checked', true);
            } else {
                //console.log("remove" + $("#EventBikesAndTimes" + TmpEventId).val());
                //$("#eventbikesandtimes").removeClass('cbr-checked');
                //console.log("false");
                $("#eventbikesandtimes").prop('checked', false);
            }

            // Manage Social Data.
            $("#LeadGenSurveyEdit").data("selectBox-selectBoxIt").selectOption($("#EventLeadGenSurvey" + TmpEventId)
            .val());
            $("#DemoSurveyEdit").data("selectBox-selectBoxIt").selectOption($("#EventDemoSurvey" + TmpEventId).val());
            $("#PostRideSurveyEdit").data("selectBox-selectBoxIt").selectOption($("#EventPostRideSurvey" + TmpEventId)
            .val());
            $("#JumpStartSurveyEdit").data("selectBox-selectBoxIt").selectOption($("#EventJumpStartSurvey" +
            TmpEventId).val());

            // Manage Templates Data.
            //$("#LegalIDEdit").data("selectBox-selectBoxIt").selectOption($("#EventWaiverID"+TmpEventId).val());
            //$("#EventSocialEdit").data("selectBox-selectBoxIt").selectOption($("#EventSocials"+TmpEventId).val());
            //$("#EventSocialEdit").data("selectBox-selectBoxIt").selectOption($("#EventSocials"+TmpEventId).val());
            $("#EventPhotoAppEmailEdit").data("selectBox-selectBoxIt").selectOption($("#EventPhotoAppEmail" +
            TmpEventId).val());

            $("#EventWelcomeEmailEdit").data("selectBox-selectBoxIt").selectOption($("#EventWelcomeEmail" +
            TmpEventId).val());
            $("#EventScheduledEmailEdit").data("selectBox-selectBoxIt").selectOption($("#EventScheduledEmail" +
            TmpEventId).val());
            $("#EventTyEmailEdit").data("selectBox-selectBoxIt").selectOption($("#EventTyEmail" + TmpEventId).val());
            $("#EventPrEmailEdit").data("selectBox-selectBoxIt").selectOption($("#EventPrEmail" + TmpEventId).val());
            $("#EventSalesEmailEdit").data("selectBox-selectBoxIt").selectOption($("#EventSalesEmail" + TmpEventId).val());

            //EventSocialEdit

            //var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
            //$("#EventStartDateEdit").daterangepicker({setDate:'2013-01-01',format: 'YYYY-MM-DD',startDate: '2013-01-01', endDate: '2013-12-31' });//$("#EventPrEmail"+TmpEventId).val());
            //$("#EventEndDateEdit").val('mm/dd/yyyy');//$("#EventPrEmail"+TmpEventId).val());

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

            //console.log(final_date);
            $("#EventStartDateEdit").val(final_start_date); //$("#EventPrEmail"+TmpEventId).val());
            $("#EventEndDateEdit").val(final_end_date); //$("#EventPrEmail"+TmpEventId).val());

            //Event EventRegistrationDeadlinePST date
            var EventRegistrationDeadlinePST = $("#EventRegistrationDeadlinePST" + TmpEventId).val();
            $("#EventRegistrationDeadlinePSTTemp1").val('');
            if (EventRegistrationDeadlinePST != '') {
                var RegistrationDeadlinePSTArray = EventRegistrationDeadlinePST.split(" ");
                var RegistrationDeadlinePSTdate = RegistrationDeadlinePSTArray[0].split("-");
                var objDate = new Date(RegistrationDeadlinePSTdate[1] + "/" + RegistrationDeadlinePSTdate[2] + "/" +
                    RegistrationDeadlinePSTdate[0]),
                    locale = "en-us",
                    month = objDate.toLocaleString(locale, {
                    month: "long"
                    });
                day = objDate.toLocaleString(locale, {
                    weekday: "short"
                });
                var date = objDate.getDate();
                var year = objDate.getFullYear();
                var final_RegistrationDeadlinePST_date = day + ', ' + date + ' ' + month + ' ' + year + ' ' +
                    RegistrationDeadlinePSTArray[1];
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
            <form method="post" action="Action.php" id="EventDelete">
                <input type="hidden" name="DeleteEventID" id="DeleteEventID" value="">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="controller" value="event">
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Delete Modal -->
<div class="modal fade custom-width" id="event-modal-delete">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Are you sure? </h4>
            </div>
            <form method="post" action="Action.php" id="EventDelete">
                <input type="hidden" name="DeleteEventID" id="DeleteEventID" value="">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="controller" value="event">
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-info" data-dismiss="modal">Delete</button>
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
<div class="modal fade custom-width" id="event-modal">
    <div class="modal-dialog" style="width:800px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Edit Event</h4>
            </div>
            <div class="modal-body">
                <form id="EventEditForm" method="post" action="Action.php">
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
                                        //$("#get-link-modal").modal('show');
                                        $("#getLinkDiv").text(link);
                                    }
                                    jQuery(document).ready(function($) {
                                        $("#EventCountryEdit").select2({
                                            placeholder: 'Select Event Type...',
                                            allowClear: true,
                                            minimumResultsForSearch: -1, // Hide the search bar
                                            formatResult: function(state) {
                                            return '<div style="background:url(http://www.geonames.org/flags/x/' + state.id +
                                                '.gif) no-repeat center center;background-size:100%;display:inline-block;position:relative;width:20px;height:15px;margin-right: 10px;top:2px;"></div>' +
                                                state.text;
                                            }
                                        }).on('select2-open', function() {
                                            // Adding Custom Scrollbar
                                            $(this).data('select2').results.addClass('overflow-hidden').perfectScrollbar();
                                        });
                                    });
                                </script>
                                <select class="form-control" id="EventCountryEdit" name="EventCountry">
                                    <?php /* foreach($countries->Country as $country){?>
                                    <option value="<?php echo $country->CountryID;?>"><?php echo $country->CountryName;?></option>
                                    <?php } */ ?>
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
                                    //$("#EventDemoWaiverDiv2").show();
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
                                        <?php /* foreach($waivers->Waivers as $waiver){?>
                                        <option value="<?php echo $waiver->WaiverID;?>"><?php echo $waiver->WaiverName;?></option>
                                        <?php } */ ?>
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-5" id="EventJumpStartWaiverUnderAgeDiv" style="display:none;">
                            <div class="form-group">
                                <label class="control-label" for="waiver">Jump Start Waiver Under Age</label>
                                <select name="EventJumpStartWaiverUnderAge" class="selectboxit" id="EventJumpStartWaiverUnderAge">
                                    <optgroup label="Waivers">
                                        <?php /* foreach($waivers->Waivers as $waiver){?>
                                        <option value="<?php echo $waiver->WaiverID;?>"><?php echo $waiver->WaiverName;?></option>
                                        <?php } */ ?>
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
                                        <?php /* foreach($waivers->Waivers as $waiver){?>
                                        <option value="<?php echo $waiver->WaiverID;?>"><?php echo $waiver->WaiverName;?></option>
                                        <?php } */ ?>
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
                                        <?php /* foreach($waivers->Waivers as $waiver){?>
                                        <option value="<?php echo $waiver->WaiverID;?>"><?php echo $waiver->WaiverName;?></option>
                                        <?php } */ ?>
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-5" id="EventDemoPassengerWaiverDiv" style="display:none;">
                            <div class="form-group">
                                <label class="control-label" for="waiver">Passenger Waiver 1</label>
                                <select name="EventDemoPassengerWaiver" class="selectboxit" id="EventDemoPassengerWaiver">
                                    <optgroup label="Waivers">
                                        <?php /* foreach($waivers->Waivers as $waiver){?>
                                        <option value="<?php echo $waiver->WaiverID;?>"><?php echo $waiver->WaiverName;?></option>
                                        <?php } */ ?>
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
                                        <?php /* foreach($waivers->Waivers as $waiver){?>
                                        <option value="<?php echo $waiver->WaiverID;?>"><?php echo $waiver->WaiverName;?></option>
                                        <?php } */ ?>
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-5" id="EventDemoPassengerWaiverDiv2" style="display:none;">
                            <div class="form-group">
                                <label class="control-label" for="waiver">Passenger Waiver 2</label>
                                <select name="EventDemoPassengerWaiver2" class="selectboxit" id="EventDemoPassengerWaiver2">
                                    <optgroup label="Waivers">
                                        <?php /* foreach($waivers->Waivers as $waiver){?>
                                        <option value="<?php echo $waiver->WaiverID;?>"><?php echo $waiver->WaiverName;?></option>
                                        <?php } */ ?>
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
                                        <?php /* foreach($smstemplates->SMSTemplates as $smstemplate){?>
                                        <option value="<?php echo $smstemplate->TemplateID;?>"><?php echo $smstemplate->SmsSubj;?>
                                        </option>
                                        <?php } */ ?>
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
                                        <?php /* foreach($waivers->Waivers as $waiver){?>
                                        <option value="<?php echo $waiver->WaiverID;?>"><?php echo $waiver->WaiverName;?></option>
                                        <?php } */ ?>
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
                                            // Adding Custom Scrollbar
                                            $(this).data('select2').results.addClass('overflow-hidden').perfectScrollbar();
                                        });

                                        //march27
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
                                        <?php
                                            /* if(isset($dealers->Success) && $dealers->Success==1){
                                               	foreach ($dealers->Dealers as $key => $dealer) {
                                               		echo '<option value="'.$dealer->DealerID.'">'.$dealer->DealerName.'-'.$dealer->DealerNumber.'</option>';
                                               	}
                                            } */
                                        ?>
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
                                        <?php
                                            /* if(isset($surveys->Success) && $surveys->Success==1){
                                                foreach ($surveys->Survey as $skey => $survey) {
                                                	echo '<option value="'.$survey->SurveyID.'">'.$survey->SurveyName.'</option>';
                                                }
                                            } */
                                        ?>
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
                                        <?php /* foreach($emailTemplates->EmailTemplates as $emailtemplate){?>
                                        <option value="<?php echo $emailtemplate->TemplateID;?>"><?php echo $emailtemplate->EmailSubj;?></option>
                                        <?php } */ ?>
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
                                        <?php /* foreach($emailTemplates->EmailTemplates as $emailtemplate){?>
                                        <option value="<?php echo $emailtemplate->TemplateID;?>"><?php echo $emailtemplate->EmailSubj;?></option>
                                        <?php } */?>
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
                                        <?php /* foreach($emailTemplates->EmailTemplates as $emailtemplate){?>
                                        <option value="<?php echo $emailtemplate->TemplateID;?>"><?php echo $emailtemplate->EmailSubj;?>
                                        </option>
                                        <?php } */ ?>
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6" id="ReminderTemplate2Div" style="display:none;">
                            <div class="form-group">
                                <label class="control-label" for="remindertemplate2emailtemplate">Reminder Template 2</label>
                                <select name="remindertemplate2emailtemplate" class="selectboxit" id="remindertemplate2emailtemplate">
                                    <optgroup label="Email Templates">
                                        <?php /* foreach($emailTemplates->EmailTemplates as $emailtemplate){?>
                                        <option value="<?php echo $emailtemplate->TemplateID;?>"><?php echo $emailtemplate->EmailSubj;?>
                                        </option>
                                        <?php } */ ?>
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
                                            // Adding Custom Scrollbar
                                            $(this).data('select2').results.addClass('overflow-hidden').perfectScrollbar();
                                        });
                                        $("a.reportLink").click(function() {
                                            window.location.href = "Action.php?id=" + $(this).parent().prev().prev().prev().prev()
                                            .prev().prev().text();
                                        });
                                        $("a#MultiReport").click(function() {
                                            var checkboxes = document.getElementsByName('crm[]');
                                            var checkboxesChecked = [];
                                            // loop over them all
                                            for (var i = 0; i < checkboxes.length; i++) {
                                                // And stick the checked ones onto an array...
                                                if (checkboxes[i].checked) {
                                                    checkboxesChecked.push(checkboxes[i].value);
                                                }
                                            }
                                                window.location.href = "Action.php?id=" + checkboxesChecked;
                                        });
                                    });
                                </script>
                                <select name="TruckID[]" class="form-control" id="TruckIDEdit" multiple>
                                    <option value="">Please select the Trucks</option>
                                    <optgroup label="Dealers">
                                        <?php /* foreach($trucks->Trucks as $truck){ ?>
                                        <option value="<?php echo $truck->TruckID;?>"><?php echo $truck->TruckName;?></option>
                                        <?php } */ ?>
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
                                        <?php
                                            /* if(isset($surveys->Success) && $surveys->Success==1){
                                                foreach ($surveys->Survey as $skey => $survey) {
                                                	echo '<option value="'.$survey->SurveyID.'">'.$survey->SurveyName.'</option>';
                                                }
                                            } */
                                        ?>
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
                                        <?php
                                            /* if(isset($surveys->Success) && $surveys->Success==1){
                                                foreach ($surveys->Survey as $skey => $survey) {
                                                	echo '<option value="'.$survey->SurveyID.'">'.$survey->SurveyName.'</option>';
                                                }
                                            } */
                                        ?>
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
                                        <?php
                                            /* if(isset($surveys->Success) && $surveys->Success==1){
                                                foreach ($surveys->Survey as $skey => $survey) {
                                                	echo '<option value="'.$survey->SurveyID.'">'.$survey->SurveyName.'</option>';
                                                }
                                            } */
                                        ?>
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
                                        <?php
                                            /* if(isset($surveys->Success) && $surveys->Success==1){
                                                foreach ($surveys->Survey as $skey => $survey) {
                                                	echo '<option value="'.$survey->SurveyID.'">'.$survey->SurveyName.'</option>';
                                                }
                                            } */
                                        ?>
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
                                        <?php
                                            /* if(isset($emailTemplates->Success) && $emailTemplates->Success==1){
                                                foreach ($emailTemplates->EmailTemplates as $skey => $emailTemplate) {
                                                	echo '<option value="'.$emailTemplate->TemplateID.'">'.$emailTemplate->EmailSubj.'</option>';
                                                }
                                            } */
                                        ?>
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
                                        <?php
                                            /* if(isset($emailTemplates->Success) && $emailTemplates->Success==1){
                                                foreach ($emailTemplates->EmailTemplates as $skey => $emailTemplate) {
                                                	echo '<option value="'.$emailTemplate->TemplateID.'">'.$emailTemplate->EmailSubj.'</option>';
                                                }
                                            } */
                                            ?>
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
                                        <?php
                                            /* if(isset($emailTemplates->Success) && $emailTemplates->Success==1){
                                                foreach ($emailTemplates->EmailTemplates as $skey => $emailTemplate) {
                                                	echo '<option value="'.$emailTemplate->TemplateID.'">'.$emailTemplate->EmailSubj.'</option>';
                                                }
                                            } */
                                        ?>
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
                                        <?php
                                            /* if(isset($emailTemplates->Success) && $emailTemplates->Success==1){
                                                foreach ($emailTemplates->EmailTemplates as $skey => $emailTemplate) {
                                                	echo '<option value="'.$emailTemplate->TemplateID.'">'.$emailTemplate->EmailSubj.'</option>';
                                                }
                                            } */
                                        ?>
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
                                        <?php
                                            /* if(isset($emailTemplates->Success) && $emailTemplates->Success==1){
                                                foreach ($emailTemplates->EmailTemplates as $skey => $emailTemplate) {
                                                	echo '<option value="'.$emailTemplate->TemplateID.'">'.$emailTemplate->EmailSubj.'</option>';
                                                }
                                            } */
                                        ?>
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
                                        <?php
                                            /* if(isset($emailTemplates->Success) && $emailTemplates->Success==1){
                                                foreach ($emailTemplates->EmailTemplates as $skey => $emailTemplate) {
                                                	echo '<option value="'.$emailTemplate->TemplateID.'">'.$emailTemplate->EmailSubj.'</option>';
                                                }
                                            } */
                                        ?>
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
            </div>
            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="controller" value="event">
            <input type="hidden" id="EventEditID" name="EventID" value="">
            </form>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-info" data-dismiss="modal">Save Changes</button>
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
                //console.log("checked");
                x.style.display = "block";
            } else {
                //console.log("unchecked");
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
<script type="text/javascript">
    $('.datetimepicker').datetimepicker({
        useCurrent: true,
        format: "ddd, DD MMMM YYYY HH:mm"
    });
</script>
@endpush
