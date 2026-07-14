$( document ).ready(function() {
    $("button.btn-info").click(function(){
        if($(this).text()=="Create"){
            var form = $( "#Inventory" );
            if (form.valid()) {
                form.submit();
            }
        }
        else if($(this).text()=="Save Changes"){
            var form = $( "#InventoryEdit" );
            if (form.valid()) {
                form.submit();
            }
        }
        else if($(this).text()=="Delete"){
            var form = $("#InventoryDelete");
            if (form.valid()) {
                form.submit();
            }
        }
    });

    $("a.btn-danger").click(function(){
        var id = $(this).data('id');
        $("#DeleteInventoryID").val(id);
        $("#InventoryDelete").attr('action', '/manage-inventory/' + id);
    });

    $("a.btn-secondary").click(function(){
        var btn = $(this);
        var VId = btn.data('id');
        $("#VehicleNickNameEdit").val(btn.data('nickname'));
        $("#VehicleModelEdit").val(btn.data('group'));
        $("#VehicleColorEdit").val(btn.data('color'));
        $("#VehicleTruckIDEdit").val(btn.data('truck'));
        $("#VehicleID").val(VId);
        $("#ModelIDEdit").val(btn.data('model'));
        $("#VehicleLicPlateEdit").val(btn.data('plate'));
        $("#VehicleVINEdit").val(btn.data('vin'));
        $("#VehicleCOVEdit").val(btn.data('cov'));
        $("#VehicleTypeEdit").val(btn.data('type'));

        if(parseInt(btn.data('archive')) == 1){
            $("#EventArchiveEdit").prop('checked', true);
            $("#EventArchiveEdit1").prop('checked', false);
        }
        else {
            $("#EventArchiveEdit").prop('checked', false);
            $("#EventArchiveEdit1").prop('checked', true);
        }
        $("#InventoryEdit").attr('action', '/manage-inventory/' + VId);
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
