import { route } from 'ziggy-js';

$( document ).ready(function() {
    // Standard action submit handlers
    $("button.btn-info").click(function(){
        if($(this).text()=="Create"){
            var form = $( "#CountryForm" );
            if (form.valid()) {
                form.submit();
            }
        }
        else if($(this).text()=="Save Changes"){
            var form = $( "#CountryFormEdit" );
            if (form.valid()) {
                form.submit();
            }
        }
        else if($(this).text()=="Delete"){
            var form = $("#CountryDelete");
            if (form.valid()) {
                form.submit();
            }
        }
    });

    // Trigger Delete
    $("a.btn-danger").click(function(){
        var id = $(this).data('id');
        $("#DeleteCountryID").val(id);
        $("#CountryDelete").attr('action', route('manage-countries.destroy', { id }));
    });

    // Trigger Edit Close to reopen Edit Country Modal
    $("#closeStateModal").click(function(){
        jQuery('#state-modal-edit-delete').modal('hide');
        $("#CID" + $("#CountryID").val()).trigger("click");
    });

    // Double click/click action on state select box to open state edit/delete modal
    $("#multi-select-state").click(function(){
        var cID = $(this).val();
        if (cID && cID.length > 0) {
            $("#StateIDEdit").val(cID[0]);
            $("#StateIDDelete").val(cID[0]);

            var stateText = $(this).find("option[value='" + cID[0] + "']").text();
            $("#OLDStateNameEdit").val(stateText);
            $("#StateNameEdit1").val(stateText);

            jQuery('#state-modal-edit-delete').modal('show');
            $("#truck-modal-edit").modal('hide');
        }
    });

    // Save State AJAX callback
    $("#saveStateBtn").click(function(){
        $.ajax({
            method: "POST",
            url: route('manage-countries.states.edit'),
            data: $("#stateEditForm").serialize(),
        })
        .done(function( msg ) {
            jQuery('#state-modal-edit-delete').modal('hide');
            var countryId = $("#CountryID").val();
            var stateId = $("#StateIDEdit").val();
            var oldText = $("#OLDStateNameEdit").val();

            var str = $("#StateName" + countryId).val();
            var oldOption = "<option value='" + stateId + "'>" + oldText + "</option>";
            var newOption = "<option value='" + stateId + "'>" + msg + "</option>";
            var newStr = str.replace(oldOption, newOption);

            $("#StateName" + countryId).val(newStr);
            $("#CID" + countryId).trigger("click");
        });
    });

    // Delete State AJAX callback
    $("#deleteStateBtn").click(function(){
        $.ajax({
            method: "POST",
            url: route('manage-countries.states.delete'),
            data: $("#stateDeleteForm").serialize(),
        })
        .done(function( msg ) {
            jQuery('#state-modal-edit-delete').modal('hide');
            var countryId = $("#CountryID").val();
            var stateId = $("#StateIDEdit").val();
            var oldText = $("#OLDStateNameEdit").val();

            var str = $("#StateName" + countryId).val();
            var oldOption = "<option value='" + stateId + "'>" + oldText + "</option>";
            var newStr = str.replace(oldOption, "");

            $("#StateName" + countryId).val(newStr);
            $("#CID" + countryId).trigger("click");
        });
    });

    // Populate Edit Modal
    $("a.btn-secondary").click(function(){
        var btn = $(this);
        var CID = btn.data('id');
        $("#multi-select-state").empty();
        $("#CountryNameEdit").val(btn.data('name'));
        $("#CountryCodeEdit").val(btn.data('code'));
        $("#RegionIDEdit").val(btn.data('region'));
        $("#CountryID").val(CID);
        $("#multi-select-state").append($("#StateName" + CID).val());
        $("#CountryFormEdit").attr('action', '/manage-countries/' + CID);
    });

    // Add State AJAX callback
    $("#add_state").click(function(){
        var countryId = $("#CountryID").val();
        var stateName = $("#StateNameEdit").val();
        if (stateName.trim() === '') return;

        $.ajax({
            method: "POST",
            url: route('manage-countries.states.add'),
            data: {
                action: "add",
                controller: "state",
                CountryID: countryId,
                StateName: stateName,
                _token: $('meta[name="csrf-token"]').attr('content'),
            }
        })
        .done(function( msg ) {
            var str = $("#StateName" + countryId).val();
            str += "<option value='" + msg + "'>" + stateName + "</option>";
            $("#StateName" + countryId).val(str);
            $("#CID" + countryId).trigger("click");
            $("#StateNameEdit").val('');
        });
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
                // Only reset forms if they aren't sub-state forms that are currently active
                if (this.id !== 'stateEditForm' && this.id !== 'stateDeleteForm') {
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
                }
            });
        }
    });
});
