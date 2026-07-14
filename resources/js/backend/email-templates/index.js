function openModel(){
    var val = $("#EmailTemplateSubjEdit").val();
    if(val && val !== ""){
        var id = $("#DeleteEmailTemplateID").val();
        $("#EmailTemplateDelete").attr('action', '/manage-email-templates/' + id);
        jQuery('#emailtemplate-modal-delete').modal('show');
    }
    else {
        alert("Please select Email Template that you want to delete!");
    }
}

window.openModel = openModel;

$( document ).ready(function() {
    $("#btn-create-confirm").click(function(){
        var form = $( "#EmailTemplateForm" );
        if (form.valid()) {
            form.submit();
        }
    });

    $("#btn-send-test-modal").click(function(){
        var stringText = CKEDITOR.instances.TemplateBlob1.getData();
        $("#TestSendEmailTemplate").val(stringText);
        $("#EmailTemplateSubject").val($("#EmailTemplateSub").val());
        jQuery('#sendemail-template-modal').modal('show');
    });

    $("#btn-send-test-confirm").click(function(){
        var form = $( "#EmailTemplateSendTestEmailForm" );
        if (form.valid()) {
            form.submit();
        }
    });

    $("#btn-save-changes").click(function(){
        var stringText = CKEDITOR.instances.TemplateBlob1.getData();
        var textArea = document.createElement('textarea');
        textArea.innerHTML = stringText;
        var searchStr = textArea.value;
        if(searchStr.toLowerCase().indexOf('<a href="http://~prsurveyphoto~">')>=0){
            var form = $( "#EmailTemplateEditForm" );
            if (form.valid()) {
                form.submit();
            }
        }
        else {
            jQuery('#emailtemplate-modal-error').modal('show');
            return false;
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
            if (CKEDITOR.instances['TemplateBlob1']) {
                CKEDITOR.instances['TemplateBlob1'].setData('');
            }
            return;
        }
        var strArray = curentVal.split("!$!");
        var id = strArray[0];
        $("#DeleteEmailTemplateID").val(id);
        $("#EmailTemplateSub").val($("#TemplateSub"+id).val());
        $("#EmailTemplateEditForm").attr('action', '/manage-email-templates/' + id);

        var blobContent = $("#TemplateBlobTmp"+id).val();
        if (CKEDITOR.instances['TemplateBlob1']) {
            CKEDITOR.instances['TemplateBlob1'].setData(blobContent);
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
