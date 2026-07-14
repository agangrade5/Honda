$( document ).ready(function() {
    $("#btn-add-sms-template").on("click", function() {
        jQuery('#template-modal').modal('show');
    });

    $("#btn-delete-sms-template").on("click", function() {
        var val = $("#EmailTemplateSubjEdit").val();
        if(val && val !== ""){
            var id = $("#DeleteEmailTemplateID").val();
            $("#EmailTemplateDelete").attr('action', '/manage-sms-templates/' + id);
            jQuery('#emailtemplate-modal-delete').modal('show');
        }
        else {
            alert("Please select SMS Template that you want to delete!");
        }
    });
    $("#btn-create-confirm").click(function(){
        var form = $( "#EmailTemplateForm" );
        if (form.valid()) {
            form.submit();
        }
    });

    $("#btn-save-changes").click(function(){
        var form = $( "#EmailTemplateEditForm" );
        if (form.valid()) {
            form.submit();
        }
    });

    $("#btn-delete-confirm").click(function(){
        var form = $("#EmailTemplateDelete");
        if (form.valid()) {
            form.submit();
        }
    });

    $("#EmailTemplateSubjEdit").change(function(){
        var curentVal = $(this).val();
        if (!curentVal) {
            $("#DeleteEmailTemplateID").val('');
            $("#EmailTemplateSub").val('');
            $("#TemplateBlob1").val('');
            $('.EditSMSLIMITERRROR').text(160);
            return;
        }
        var strArray = curentVal.split("!$!");
        var id = strArray[0];
        $("#DeleteEmailTemplateID").val(id);
        $("#EmailTemplateSub").val($("#TemplateSub"+id).val());
        $("#EmailTemplateEditForm").attr('action', '/manage-sms-templates/' + id);

        var blobContent = $("#TemplateBlobTmp"+id).val() || '';
        $("#TemplateBlob1").val(blobContent);
        var maxLength = 160;
        var textlen = maxLength - blobContent.length;
        $('.EditSMSLIMITERRROR').text(textlen);
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

var maxLength = 160;
$('textarea.smsTextarea').keyup(function() {
    var textlen = maxLength - $(this).val().length;
    $('.EditSMSLIMITERRROR').text(textlen);
});
$('textarea.addSmsTextarea').keyup(function() {
    var textlen = maxLength - $(this).val().length;
    $('.AddSMSLIMITERRROR').text(textlen);
});
