$( document ).ready(function() {
    $("button.btn-info").click(function(){
        if($(this).text()=="Create"){
            var form = $( "#SocialMediaForm" );
            if (form.valid()) {
                form.submit();
            }
        }
        else if($(this).text()=="Save Changes"){
            var form = $( "#SocialMediaFormEdit" );
            if (form.valid()) {
                form.submit();
            }
        }
        else if($(this).text()=="Delete"){
            var form = $("#SocialMediaDelete");
            if (form.valid()) {
                form.submit();
            }
        }
    });

    $("a.btn-danger").click(function(){
        var id = $(this).data('id');
        $("#DeleteSocialMediaID").val(id);
        $("#SocialMediaDelete").attr('action', '/manage-social-media/' + id);
    });

    $("a.btn-secondary").click(function(){
        var btn = $(this);
        var SMID = btn.data('id');
        $("#InstagramEdit").val(btn.data('instagram'));
        $("#TwitterEdit").val(btn.data('twitter'));
        $("#FacebookEdit").val(btn.data('facebook'));
        
        if ($("#SocialNameEdit").data("selectBox-selectBoxIt")) {
            $("#SocialNameEdit").data("selectBox-selectBoxIt").selectOption(String(SMID));
        } else {
            $("#SocialNameEdit").val(SMID);
        }
        $("#SocialIDID").val(SMID);
        $("#SocialMediaFormEdit").attr('action', '/manage-social-media/' + SMID);
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
