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

    <form id="VehicleForm" method="post" action="UploadAction.php" class="form-wizard validate" novalidate>
        <ul class="tabs">
            <li class="active">
            </li>
        </ul>
        <div class="progress-indicator">
            <span></span>
        </div>
        <script>
            $( document ).ready(function() {
                $("#createEventButton").click(function(){
                    $( "#EventForm" ).submit();
                });
            });
        </script>
        <div class="tab-content no-margin">
            <!-- Tabs Content -->
            <div class="tab-pane with-bg active" id="fwv-1">
                <input type="hidden" name="action" value="add">
                <input type="hidden" name="controller" value="event">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label" for="event_name">Upload file</label>
                            <input style="padding-top:3%;padding-bottom:6%;" class="form-control" type="file" name="EventName" id="event_name" data-validate="required" placeholder="Choose a name for this event" onchange="" />
                        </div>
                    </div>
                    <script type="text/javascript">
                        jQuery(document).ready(function(){
                            $('#event_name').on('change', function() {
                                var file_data = $('#event_name').prop('files')[0];
                                var form_data = new FormData();
                                form_data.append('file', file_data)
                                form_data.append('action', 'upload')
                                //console.log(form_data);
                                $.ajax({
                                    url: 'uploadVehicle.php', // point to server-side PHP script
                                    dataType: 'text',  // what to expect back from the PHP script, if anything
                                    cache: false,
                                    contentType: false,
                                    processData: false,
                                    data: form_data,
                                    type: 'post',
                                    success: function(php_script_response){
                                    $("#filename").val(php_script_response);
                                        //alert(php_script_response); // display response from the PHP script, if any
                                        jQuery('#modal-1').modal('show', {backdrop: 'fade'});
                                    }
                                });
                            });
                        });
                    </script>
                </div>
            </div>
    </form>
    <!-- Main Footer -->
    @include('layouts.backend.footer')
</div>
<!-- content @e -->

<!-- Modal 1 (Basic)-->
<div class="modal fade" id="modal-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Import Vehicles</h4>
            </div>
            <input type="hidden" name="filename" id="filename" value="" />
            <div class="modal-body">
                Are you sure you want to upload this new vehicle list?
                <div style="text-align:center;display:none;" id="ajaxLoad">
                    <img src="assets/images/ajax-loader.gif">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal" onclick="return DeletUploadedFile();">NO</button>
                <button type="button" class="btn btn-info" id="AjaxReadXls" onclick="javascript:;">YES</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal 1 end-->
<form method="post" action="Action.php" id="ReadExl">
    <input type="hidden" name="action" value="add">
    <input type="hidden" name="controller" value="importvehicles">
    <input type="hidden" id="EventEditID" name="EventID" value="">
</form>
@endsection

@push('scripts')
<!-- Form wizard with validation starts here -->
<script type="text/javascript">
    jQuery(document).ready(function($) {
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

        $(".selectboxit").selectBoxIt().on('open', function() {
            // Adding Custom Scrollbar
            $(this).data('selectBoxSelectBoxIt').list.perfectScrollbar();
        });

        var eventData;
        var waiverData;
        var userData;
        var socialMediaData;
        var truckData;
    });

    function DeletUploadedFile()
    {
    	$.ajax({
            url: 'uploadVehicle.php', // point to server-side PHP script
            dataType: 'JSON',  // what to expect back from the PHP script, if anything
            data:{ action:'delete',fileName:$("#filename").val()},
            type: 'post',
            success: function(php_script_response){
                window.location.reload(true);
                return php_script_response;
            }
        });
    	return false;
    }
    function readExcelSheet(){
    	$("#ajaxLoad").show();
    	$.ajax({
            url: 'uploadVehicle.php', // point to server-side PHP script
            dataType: 'JSON',  // what to expect back from the PHP script, if anything
            data:{ action:'read',fileName:$("#filename").val()},
            type: 'post',
            async: false,
    	}).done(function() {
            //console.log("done");
    	});
    }

    jQuery(document).ready(function($){
        $("#AjaxReadXls").click(function(){
            $("#ajaxLoad").show();
            $.ajax({
                url: 'uploadVehicle.php',
                dataType: 'JSON',
                data:{ action:'read',fileName:$("#filename").val()},
                type: 'post',
                async: true,
            }).done(function() {
                //console.log("done");
            }) .fail(function() {
                //console.log("error");
            })
            .always(function() {
                window.location.reload(true);
            });
        });
        $("#EndSubmitForm").click(function(){
            $( "#EventForm" ).submit();
        });
        $("li.next").click(function(){
            if($("div.tab-content").find(".active").attr("id")=="fwv-5"){
                $("#EndSubmitForm").prev().hide();
                $("#EndSubmitForm").show();
            }
        });
        $(".previous").click(function(){
            if($.trim($("li.next").text())=="Finish"){
                $("#EndSubmitForm").prev().show();
                $("#EndSubmitForm").hide();
            }
        });
    });
</script>
@endpush
