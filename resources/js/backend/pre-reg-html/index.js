import { route } from 'ziggy-js';

$( document ).ready(function() {
    $("#btn-save-changes").click(function(){
        if ($("#EventNameEdit").val() == "") {
            alert("Please select an Event that you want to edit!");
            return false;
        }
        for (var instance in CKEDITOR.instances) {
            CKEDITOR.instances[instance].updateElement();
        }
        $( "#EventEditForm" ).submit();
    });

    $("#EventNameEdit").change(function(){
        $("#cke_TemplateBlob1 .cke_wysiwyg_frame").contents().find("body").html('');
        $("#cke_TemplateBlob2 .cke_wysiwyg_frame").contents().find("body").html('');
        $("#cke_TemplateBlob3 .cke_wysiwyg_frame").contents().find("body").html('');
        $("#cke_TemplateBlob4 .cke_wysiwyg_frame").contents().find("body").html('');
        $("#cke_TemplateBlob5 .cke_wysiwyg_frame").contents().find("body").html('');
        
        var eventId = $(this).val();
        if (eventId !== "") {
            $.ajax({
                method: "POST",
                url: route('manage-pre-reg-html.select'),
                dataType: 'json',
                data: { 
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    EventID: eventId
                }
            })
            .done(function( preregisterHTML ) {
                $("#cke_TemplateBlob1 .cke_wysiwyg_frame").contents().find("body").html(preregisterHTML.quantityform);
                $("#cke_TemplateBlob2 .cke_wysiwyg_frame").contents().find("body").html(preregisterHTML.infoform);
                $("#cke_TemplateBlob3 .cke_wysiwyg_frame").contents().find("body").html(preregisterHTML.completeform);
                $("#cke_TemplateBlob4 .cke_wysiwyg_frame").contents().find("body").html(preregisterHTML.htmlcontent);
                $("#cke_TemplateBlob5 .cke_wysiwyg_frame").contents().find("body").html(preregisterHTML.errorhtml);
            });
        }
    });
});
