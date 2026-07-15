$('#starttime').timepicker();
$('#endtime').timepicker();
$('#starttimeadd').timepicker();
$('#endtimeadd').timepicker();
$('#startquicktimeadd').timepicker();
$('#endquicktimeadd').timepicker();

function DisplayCurrentTime(date) {        
    var hours = date.getHours() > 12 ? date.getHours() - 12 : date.getHours();
    var am_pm = date.getHours() >= 12 ? "PM" : "AM";
    hours = hours < 10 ? "0" + hours : hours;
    var minutes = date.getMinutes() < 10 ? "0" + date.getMinutes() : date.getMinutes();
    var time = hours + ":" + minutes + " " + am_pm;
    return time;
}

$( document ).ready(function() {
    var DeletedTime = [];
    var UpdateTime = [];
    var EditTime = [];

    // Tab Triggers
    $("#btn-add-model").on("click", function() {
        $("#currentPopupEvent").val("add");
        jQuery('#model-modal').modal('show');
    });

    $("#btn-quick-times").on("click", function() {
        $("#currentPopupEvent").val("quick");
        jQuery('#model-quick-time-modal').modal('show');
    });

    // Edit Model click
    $(".btn-edit-model").on("click", function() {
        var id = $(this).data("id");
        var name = $(this).data("name");
        var qty = $(this).data("qty");
        var position = $(this).data("position");
        var times = $(this).data("times");

        $("#BTModelIDEdit").val(id);
        $("#BTModelNameEdit").val(name);
        $("#BTQtyEdit").val(qty);
        $("#BTPositionEdit").val(position);

        $('#multi-select-edit-time').children().remove();
        EditTime = [];

        if (times) {
            try {
                var timesList = typeof times === 'string' ? JSON.parse(times) : times;
                $.each(timesList, function(index, value) {
                    EditTime.push(value);
                    $('#multi-select-edit-time').append($('<option></option>').attr('value', value).text(value));
                });
            } catch(e) {
                console.error("Error parsing times json", e);
            }
        }
        $("#currentPopupEvent").val("edit");
        jQuery('#btmodel-modal-edit').modal('show');
    });

    // Delete Model click
    $(".btn-delete-model").on("click", function() {
        var id = $(this).data("id");
        $("#DeleteBTModelID").val(id);
        jQuery('#btmodel-modal-delete').modal('show');
    });

    // Submit Confirm Buttons
    $(".btn-create-confirm").on("click", function() {
        var timeSend = "";
        $('#multi-select-time').children().each(function(i, options){ 
            timeSend += $(options).text() + "#$$#"; 
        });
        $("#TimeAddValue").val(timeSend); 
        $( "#ModelFormAdd" ).submit();
    });

    $(".btn-submit-quick-confirm").on("click", function() {
        var timeSend = "";
        $('#multi-select-quick-time').children().each(function(i, options){ 
            timeSend += $(options).text() + "#$$#"; 
        });
        $("#QuickTimeAddValue").val(timeSend); 
        $( "#ModelFormQuickAdd" ).submit();
    });

    $(".btn-save-confirm").on("click", function() {
        var timeSend = "";
        $('#multi-select-edit-time').children().each(function(i, options){ 
            timeSend += $(options).text() + "#$$#"; 
        });
        $("#TimeEditValue").val(timeSend);
        $( "#ModelFormEdit" ).submit();
    });

    // Clear Selection Actions
    $(".btn-clear-select").on("click", function() {
        var mode = $("#currentPopupEvent").val();
        if (mode == "add") {
            jQuery('#model-modal').modal('hide');
        } else if (mode == "edit") {
            jQuery('#btmodel-modal-edit').modal('hide');
        } else if (mode == "quick") {
            jQuery('#model-quick-time-modal').modal('hide');
        }
        jQuery('#btmodel-modal-clear').modal('show');
    });

    $("#hideM").on("click", function() {
        var mode = $("#currentPopupEvent").val();
        jQuery('#btmodel-modal-clear').modal('hide');
        if (mode == "add") {
            jQuery('#model-modal').modal('show');
        } else if (mode == "edit") {
            jQuery('#btmodel-modal-edit').modal('show');
        } else if (mode == "quick") {
            jQuery('#model-quick-time-modal').modal('show');
        }
    });

    $(".btn-clear-confirm").on("click", function() {
        var mode = $("#currentPopupEvent").val();
        jQuery('#btmodel-modal-clear').modal('hide');
        if (mode == "add") {
            $('#multi-select-time').children().remove();
            jQuery('#model-modal').modal('show');
        } else if (mode == "edit") {
            $('#multi-select-edit-time').children().remove();
            jQuery('#btmodel-modal-edit').modal('show');
        } else if (mode == "quick") {
            $('#multi-select-quick-time').children().remove();
            jQuery('#model-quick-time-modal').modal('show');
        }
    });

    // Add calculated time ranges
    $("#add_time").click(function(){
        $('#multi-select-time').children().remove();
        var fullDate = new Date(); 
        var twoDigitMonth = (fullDate.getMonth() + 1 < 10) ? '0' + (fullDate.getMonth() + 1) : (fullDate.getMonth() + 1);
        var currentDate =  twoDigitMonth+ "/" + fullDate.getDate() + "/" + fullDate.getFullYear();
        var timeStart = new Date(currentDate+" "+$("#starttimeadd").val());
        var timeEnd = new Date(currentDate+" "+$("#endtimeadd").val());
        var diff = (timeEnd - timeStart) / 60000;
        if(diff>0){
            var interval = parseInt($("#BTTimeintervalAdd").val());
            var t=0;
            do{				
                var timeStartTmp = new Date(timeStart.getTime()+(t * 60 * 1000));
                var display_time = DisplayCurrentTime(timeStartTmp);				
                $('#multi-select-time').append($('<option></option>').attr('value', display_time).text(display_time));
                t+=interval;
            } while (t<=parseInt(diff));
        }		
    });

    $("#add_quick_time").click(function(){
        $('#multi-select-quick-time').children().remove();
        var fullDate = new Date(); 
        var twoDigitMonth = (fullDate.getMonth() + 1 < 10) ? '0' + (fullDate.getMonth() + 1) : (fullDate.getMonth() + 1);
        var currentDate =  twoDigitMonth+ "/" + fullDate.getDate() + "/" + fullDate.getFullYear();
        var timeStart = new Date(currentDate+" "+$("#startquicktimeadd").val());
        var timeEnd = new Date(currentDate+" "+$("#endquicktimeadd").val());
        var diff = (timeEnd - timeStart) / 60000;
        if(diff>0){
            var interval = parseInt($("#BTQuickTimeintervalAdd").val());
            var t=0;
            do{				
                var timeStartTmp = new Date(timeStart.getTime()+(t * 60 * 1000));
                var display_time = DisplayCurrentTime(timeStartTmp);				
                $('#multi-select-quick-time').append($('<option></option>').attr('value', display_time).text(display_time));
                t+=interval;
            } while (t<=parseInt(diff));
        }		
    });

    $("#edit_add_time").click(function(){
        $('#multi-select-edit-time').children().remove();
        var fullDate = new Date(); 
        var twoDigitMonth = (fullDate.getMonth() + 1 < 10) ? '0' + (fullDate.getMonth() + 1) : (fullDate.getMonth() + 1);
        var currentDate =  twoDigitMonth+ "/" + fullDate.getDate() + "/" + fullDate.getFullYear();
        var timeStart = new Date(currentDate+" "+$("#starttime").val());
        var timeEnd = new Date(currentDate+" "+$("#endtime").val());
        var diff = (timeEnd - timeStart) / 60000;
        if(diff>0){
            var interval = parseInt($("#BTTimeinterval").val());
            var t=0;
            EditTime = [];
            do{				
                var timeStartTmp = new Date(timeStart.getTime()+(t * 60 * 1000));
                var display_time = DisplayCurrentTime(timeStartTmp);				
                EditTime.push(display_time);
                $('#multi-select-edit-time').append($('<option></option>').attr('value', display_time).text(display_time));
                t+=interval;
            } while (t<=parseInt(diff));
        }
    });

    // Time list management modals bindings
    $("#multi-select-quick-time").on("click", function(){ 
        var cID = $(this).val();		
        if (cID && cID.length > 0) {
            $("#QuickNameAdd1").val(cID[0]);
            $("#OLDQuickNameAdd").val(cID[0]);		
            jQuery('#quick-modal-add-delete').modal('show');		
            jQuery('#model-quick-time-modal').modal('hide');
        }
    });

    $("#addQuickTimeManagementClose").on("click", function(){
        jQuery('#quick-modal-add-delete').modal('hide');
        jQuery('#model-quick-time-modal').modal('show');
    });

    $("#quicksavetotmgmtadd").on("click", function(){
        var oldVal = $("#OLDQuickNameAdd").val();
        var newVal = $("#QuickNameAdd1").val();
        $("#multi-select-quick-time option[value='"+oldVal+"']").replaceWith("<option value='"+newVal+"'>"+newVal+"</option>");
        jQuery('#quick-modal-add-delete').modal('hide');
        jQuery('#model-quick-time-modal').modal('show');
    });

    $("#quickdeletetotmgmtadd").on("click", function(){
        var oldVal = $("#OLDQuickNameAdd").val();
        $("#multi-select-quick-time option[value='"+oldVal+"']").remove();
        jQuery('#quick-modal-add-delete').modal('hide');
        jQuery('#model-quick-time-modal').modal('show');
    });

    $("#multi-select-time").on("click", function(){ 
        var cID = $(this).val();		
        if (cID && cID.length > 0) {
            $("#tmgmtNameAdd1").val(cID[0]);
            $("#OLDtmgmtNameAdd").val(cID[0]);		
            jQuery('#tmgmt-modal-add-delete').modal('show');		
            jQuery('#model-modal').modal('hide');
        }
    });

    $("#addTimeManagementClose").on("click", function(){		
        jQuery('#tmgmt-modal-add-delete').modal('hide');
        jQuery('#model-modal').modal('show');		
    });

    $("#savetotmgmtadd").on("click", function(){
        var oldVal = $("#OLDtmgmtNameAdd").val();
        var newVal = $("#tmgmtNameAdd1").val();
        $("#multi-select-time option[value='"+oldVal+"']").replaceWith("<option value='"+newVal+"'>"+newVal+"</option>");
        jQuery('#tmgmt-modal-add-delete').modal('hide');
        jQuery('#model-modal').modal('show');
    });

    $("#deletetotmgmtadd").on("click", function(){
        var oldVal = $("#OLDtmgmtNameAdd").val();
        $("#multi-select-time option[value='"+oldVal+"']").remove();
        jQuery('#tmgmt-modal-add-delete').modal('hide');
        jQuery('#model-modal').modal('show');
    });

    $("#multi-select-edit-time").on("click", function(){ 
        var cID = $(this).val();		
        if (cID && cID.length > 0) {
            $("#tmgmtNameEdit1").val(cID[0]);
            $("#OLDtmgmtNameEdit").val(cID[0]);
            jQuery('#tmgmt-modal-edit-delete').modal('show');		
            jQuery('#btmodel-modal-edit').modal('hide');		
        }
    });

    $("#editTimeManagementClose").on("click", function(){		
        jQuery('#tmgmt-modal-edit-delete').modal('hide');
        jQuery('#btmodel-modal-edit').modal('show');				
    });

    $("#savetotmgmt").on("click", function(){		
        jQuery('#tmgmt-modal-edit-delete').modal('hide');		
        var oldVal = $("#OLDtmgmtNameEdit").val();
        var newVal = $("#tmgmtNameEdit1").val();
        var index = EditTime.indexOf(oldVal);
        if (index !== -1) {
            EditTime[index] = newVal;		
        }
        $('#multi-select-edit-time').children().remove();
        for (var i = 0; i < EditTime.length; i++) {
            $('#multi-select-edit-time').append($('<option></option>').attr('value', EditTime[i]).text(EditTime[i]));
        }		
        jQuery('#btmodel-modal-edit').modal('show');		
    });

    $("#deletetotmgmt").on("click", function(){		
        jQuery('#tmgmt-modal-edit-delete').modal('hide');
        var oldVal = $("#OLDtmgmtNameEdit").val();
        var index = EditTime.indexOf(oldVal);
        if (index !== -1) {
            EditTime.splice(index, 1);
        }
        $('#multi-select-edit-time').children().remove();
        for (var i = 0; i < EditTime.length; i++) {
            $('#multi-select-edit-time').append($('<option></option>').attr('value', EditTime[i]).text(EditTime[i]));
        }		
        jQuery('#btmodel-modal-edit').modal('show');	
    });

    // Blur focused elements on modal hide to prevent aria-hidden focus warnings in the browser console
    $('.modal').on('hide.bs.modal', function () {
        if (document.activeElement) {
            document.activeElement.blur();
        }
    });
});
