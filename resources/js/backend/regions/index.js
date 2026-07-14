$( document ).ready(function() {
    $("button.btn-info").click(function(){
        console.log($(this).text());
        if($(this).text()=="Create"){
            var form = $( "#Region" );
            if (form.valid()) {
                form.submit();
            }
        }
        else if($(this).text()=="Save Changes"){
            var form = $( "#RegionEdit" );
            if (form.valid()) {
                form.submit();
            }
        }
        else if($(this).text()=="Delete"){
            var form = $("#RegionDelete");
            if (form.valid()) {
                form.submit();
            }
        }
    });

    $("a.btn-danger").click(function(){
        var id = $(this).data('id');
        $("#delete_region_id").val(id);
        $("#RegionDelete").attr('action', '/manage-regions/' + id);
    });

    $("a.btn-secondary").click(function(){
        $("#region_name_edit").val($(this).parent().prev().text());
        var id = $(this).data('id');
        $("#region_id").val(id);
        $("#RegionEdit").attr('action', '/manage-regions/' + id);
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
