$( document ).ready(function() {
    $("button.btn-info").click(function(){
        if($(this).text()=="Create"){
            var form = $( "#RestrictedRidersForm" );
            if (form.valid()) {
                form.submit();
            }
        }
        else if($(this).text()=="Save Changes"){
            var form = $( "#RestrictRiderFormEdit" );
            if (form.valid()) {
                form.submit();
            }
        }
        else if($(this).text()=="Delete"){
            var form = $("#RestrictedRidersDelete");
            if (form.valid()) {
                form.submit();
            }
        }
    });

    $("a.btn-danger").click(function(){
        var id = $(this).data('id');
        $("#DeleteRestrictID").val(id);
        $("#RestrictedRidersDelete").attr('action', '/manage-restricted-riders/' + id);
    });

    $("a.btn-secondary").click(function(){
        var btn = $(this);
        var RRID = btn.data('id');
        $("#RestrictLicEdit").val(btn.data('license'));
        $("#RestrictCommentEdit").val(btn.data('comment'));
        $("#RestrictID").val(RRID);
        $("#RestrictRiderFormEdit").attr('action', '/manage-restricted-riders/' + RRID);
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
