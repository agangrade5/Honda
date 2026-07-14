$( document ).ready(function() {
    $("button.btn-info").click(function(){
        if($(this).text()=="Create"){
            var form = $( "#DealerForm" );
            if (form.valid()) {
                form.submit();
            }
        }
        else if($(this).text()=="Save Changes"){
            var form = $( "#DealerFormEdit" );
            if (form.valid()) {
                form.submit();
            }
        }
        else if($(this).text()=="Delete"){
            var form = $("#DealerDelete");
            if (form.valid()) {
                form.submit();
            }
        }
    });

    $("a.btn-danger").click(function(){
        var id = $(this).data('id');
        $("#DeleteDealerID").val(id);
        $("#DealerDelete").attr('action', '/manage-dealers/' + id);
    });

    $("a.btn-secondary").click(function(){
        var btn = $(this);
        var DealerId = btn.data('id');
        $("#DealerNumberEdit").val(btn.data('number'));
        $("#DealerNameEdit").val(btn.data('name'));
        $("#DealerLocationEdit").val(btn.data('location'));
        $("#DealerRegionEdit").val(btn.data('region'));
        $("#DealerID").val(DealerId);
        $("#DealerDistrictEdit").val(btn.data('district'));
        $("#DealerFormEdit").attr('action', '/manage-dealers/' + DealerId);
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
