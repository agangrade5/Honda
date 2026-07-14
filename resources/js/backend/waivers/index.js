$( document ).ready(function() {
    $("#btn-add-waiver").on("click", function() {
        jQuery('#waiver-modal').modal('show');
    });

    $("#btn-delete-waiver").on("click", function() {
        var val = $("#WaiverNameEdit").val();
        if(val && val !== ""){
            var id = $("#DeleteWaiverID").val();
            $("#WaiverDelete").attr('action', '/manage-waivers/' + id);
            jQuery('#waiver-modal-delete').modal('show');
        }
        else {
            alert("Please select Waiver that you want to delete!");
        }
    });

    $("button.btn-info").click(function(){
        if($(this).text()=="Create"){
            var form = $( "#WaiverForm" );
            if (form.valid()) {
                form.submit();
            }
        }
        else if($(this).text()=="Save Changes"){
            var form = $( "#WaiverEditForm" );
            if (form.valid()) {
                form.submit();
            }
        }
        else if($(this).text()=="Delete"){
            var form = $("#WaiverDelete");
            if (form.valid()) {
                form.submit();
            }
        }
    });

    $("#WaiverNameEdit").change(function(){
        var curentVal = $(this).val();
        if (!curentVal) {
            $("#DeleteWaiverID").val('');
            if (CKEDITOR.instances['WaiverHTML1']) {
                CKEDITOR.instances['WaiverHTML1'].setData('');
            }
            return;
        }
        var strArray = curentVal.split("!$!");
        var id = strArray[0];
        $("#DeleteWaiverID").val(id);
        $("#WaiverEditForm").attr('action', '/manage-waivers/' + id);
        
        var htmlContent = $("#WaiverHTML" + id).html();
        if (CKEDITOR.instances['WaiverHTML1']) {
            CKEDITOR.instances['WaiverHTML1'].setData(htmlContent);
        }
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
