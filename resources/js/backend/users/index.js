$( document ).ready(function() {
    // Setup select2 plugins for modals
    $("#region").select2({
        placeholder: 'Choose the region.',
        allowClear: true
    });
    $("#events").select2({
        placeholder: 'Choose the events.',
        allowClear: true
    });
    $("#country").select2({
        placeholder: 'Choose the countrys.',
        allowClear: true
    });

    $("#region1").select2({
        placeholder: 'Choose the region.',
        allowClear: true
    });
    $("#events1").select2({
        placeholder: 'Choose the events.',
        allowClear: true
    });
    $("#country1").select2({
        placeholder: 'Choose the countrys.',
        allowClear: true
    });

    $("button.btn-info").click(function(){
        if($(this).text()=="Create"){
            var form = $( "#User" );
            if (form.valid()) {
                form.submit();
            }
        }
        else if($(this).text()=="Save Changes"){
            var form = $( "#UserEdit" );
            if (form.valid()) {
                form.submit();
            }
        }
        else if($(this).text()=="Delete"){
            var form = $("#UserDelete");
            if (form.valid()) {
                form.submit();
            }
        }
    });

    $("a.btn-danger").click(function(){
        var id = $(this).data('id');
        $("#DeleteUserID").val(id);
        $("#UserDelete").attr('action', '/manage-users/' + id);
    });

    $("a.btn-secondary").click(function(){
        var btn = $(this);
        var UserId = btn.data('id');
        $("#FirstNameEdit").val(btn.data('first-name'));
        $("#LastNameEdit").val(btn.data('last-name'));
        $("#UserNameEdit").val(btn.data('username'));
        $("#UserPhoneEdit").val(btn.data('phone'));
        $("#UserID").val(UserId);
        $("#UserLevelEdit").val(btn.data('level') || 0);
        $("#UserPasswordEdit").val(btn.data('pass'));

        // Manage User Events select2 value binding
        var UserEventsArray = [];
        $.each(btn.data('events') || [], function(index, EDV){
            UserEventsArray.push({id:EDV, text:$('#events1 option[value="'+EDV+'"]').text()});
        });
        $("#events1").select2('data', UserEventsArray);

        // Manage User Country select2 value binding
        var UserCountryArray = [];
        $.each(btn.data('countries') || [], function(index, EDV){
            UserCountryArray.push({id:EDV, text:$('#country1 option[value="'+EDV+'"]').text()});
        });
        $("#country1").select2('data', UserCountryArray);

        // Manage User Region select2 value binding
        var UserRegionArray = [];
        $.each(btn.data('regions') || [], function(index, EDV){
            UserRegionArray.push({id:EDV, text:$('#region1 option[value="'+EDV+'"]').text()});
        });
        $("#region1").select2('data', UserRegionArray);

        $("#UserEdit").attr('action', '/manage-users/' + UserId);
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
                $(this).find('select').val(0).trigger('change');
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
