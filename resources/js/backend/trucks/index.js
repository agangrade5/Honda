import { route } from 'ziggy-js';

var count = 0;
$( document ).ready(function() {
    // Initialize MultiSelect elements
    $("#multi-select-vehicle").multiSelect({
        afterInit: function() {
            this.$selectableContainer.add(this.$selectionContainer).find('.ms-list').perfectScrollbar();
        },
        afterSelect: function() {
            this.$selectableContainer.add(this.$selectionContainer).find('.ms-list').perfectScrollbar('update');
        }
    });

    $("#multi-select-vehicle2").multiSelect({
        afterInit: function() {
            this.$selectableContainer.add(this.$selectionContainer).find('.ms-list').perfectScrollbar();
        },
        afterSelect: function() {
            count++;
            $("#totalBikeText").text(count);
            this.$selectableContainer.add(this.$selectionContainer).find('.ms-list').perfectScrollbar('update');
        },
        afterDeselect: function() {
            count--;
            $("#totalBikeText").text(count);
        }
    });

    // Trigger Import modal
    $(".import_vehicles").click(function(){
        jQuery('#modal-2').modal('show', {backdrop: 'fade'});
    });

    // Handle AJAX file upload on change
    $('#event_name').on('change', function() {
        var file_data = $('#event_name').prop('files')[0];
        var form_data = new FormData();
        form_data.append('file', file_data);
        form_data.append('action', 'upload');
        form_data.append('_token', $('meta[name="csrf-token"]').attr('content'));
        
        $.ajax({
            url: route('manage-trucks.import'),
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            success: function(php_script_response){
                $("#filename").val(php_script_response);
            }
        });
    });

    // Handle Import Submit
    $("#AjaxReadXls").click(function(){
        $("#ajaxLoad").show();
        jQuery('#modal-2').modal('hide');
        $.ajax({
            url: route('manage-trucks.import'),
            dataType: 'JSON',
            data: { 
                action: 'read',
                fileName: $("#filename").val(),
                truckId: $('#truckName').val(),
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            type: 'post',
            async: true,
        }).done(function() {
            console.log("done");
        }).fail(function() {
            console.log( "error" );
        }).always(function() {
            window.location.reload(true);
        });
    });

    // Submit form controls
    $("button.btn-info").click(function(){
        if($(this).text()=="Create"){
            var form = $( "#Truck" );
            if (form.valid()) {
                form.submit();
            }
        }
        else if($(this).text()=="Save Changes"){
            var form = $( "#TruckEdit" );
            if (form.valid()) {
                form.submit();
            }
        }
        else if($(this).text()=="Delete"){
            var form = $("#TruckDelete");
            if (form.valid()) {
                form.submit();
            }
        }
        else if($(this).text()=="Upload COV"){
            $(".close").trigger("click");
            jQuery('#cov-modal-upload').modal('show');
            return false;
        }
    });

    // Handle COV save
    $(".uploadCOVClass").click(function(){
        var file_data = $('#uploadCOV').prop('files')[0];
        var form_data = new FormData();
        form_data.append('file', file_data);
        form_data.append('action', 'uploadCOV');
        form_data.append('_token', $('meta[name="csrf-token"]').attr('content'));
        
        $.ajax({
            url: route('manage-trucks.import'),
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            success: function(response){
                jQuery('#cov-modal-upload').modal('hide');
                jQuery('#truck-modal-edit').modal('show');
                
                $('#multi-select-vehicle2').multiSelect('deselect_all');
                if (response.data && response.data.length > 0) {
                    $('#multi-select-vehicle2').multiSelect('select', response.data.map(String));
                }
            }
         });
        return false;
    });

    // Wire Action Click Listeners
    $("a.btn-danger").click(function(){
        var id = $(this).data('id');
        $("#DeleteTruckID").val(id);
        $("#TruckDelete").attr('action', '/manage-trucks/' + id);
    });

    $("a.btn-secondary").click(function(){
        count = 0;
        var btn = $(this);
        var TruckId = btn.data('id');
        $("#DeleteTruckID").val(TruckId);
        $("#TruckEditID").val(TruckId);

        if ($("#BTSetIDEdit").data("selectBox-selectBoxIt")) {
            $("#BTSetIDEdit").data("selectBox-selectBoxIt").selectOption(String(btn.data('btset')));
        } else {
            $("#BTSetIDEdit").val(btn.data('btset'));
        }

        $("#TruckNameEdit").val(btn.data('name'));
        
        var TruckInventoryStr = String(btn.data('inventory') || '');
        var TruckInventoryArray = TruckInventoryStr ? TruckInventoryStr.split(",") : [];
        var inv = [];
        $.each(TruckInventoryArray, function(index, InventoryID){
            count++;
            inv.push(String(InventoryID));
        });
        
        $('#multi-select-vehicle2').multiSelect('deselect_all');
        if (inv.length > 0) {
            $('#multi-select-vehicle2').multiSelect('select', inv);
        }
        $("#totalBikeText").text(count);
        $("#TruckEdit").attr('action', '/manage-trucks/' + TruckId);
    });

    // Blur focused elements on modal hide to prevent aria-hidden focus warnings in the browser console
    $('.modal').on('hide.bs.modal', function () {
        if (document.activeElement) {
            document.activeElement.blur();
        }
    });

    // Reset validation errors and form inputs on modal close
    $('.modal').on('hidden.bs.modal', function () {
        var form = $(this).find('form');
        if (form.length > 0) {
            form.each(function() {
                this.reset();
                if (typeof $(this).validate === 'function') {
                    var validator = $(this).validate();
                    if (validator) {
                        validator.resetForm();
                    }
                }
                $(this).find('.has-error').removeClass('has-error');
                $(this).find('.error').removeClass('error');
                $(this).find('.help-block').remove();
            });
        }
    });
});
