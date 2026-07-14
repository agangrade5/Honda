import { route } from 'ziggy-js';

function DeletUploadedFile()
{
    $.ajax({
        url: route('manage-import-vehicles.delete'),
        dataType: 'JSON',
        data: { fileName: $("#filename").val() },
        type: 'post',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(php_script_response){
            window.location.reload(true);
            return php_script_response;
        }
    });
    return false;
}

window.DeletUploadedFile = DeletUploadedFile;

jQuery(document).ready(function($) {
    $('#event_name').on('change', function() {
        var file_data = $('#event_name').prop('files')[0];
        var form_data = new FormData();
        form_data.append('file', file_data);
        
        $.ajax({
            url: route('manage-import-vehicles.upload'),
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(php_script_response){
                $("#filename").val(php_script_response);
                jQuery('#modal-1').modal('show', {backdrop: 'fade'});
            }
        });
    });

    $("#AjaxReadXls").click(function(){
        $("#ajaxLoad").show();
        $.ajax({
            url: route('manage-import-vehicles.read'),
            dataType: 'JSON',
            data: { fileName: $("#filename").val() },
            type: 'post',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            async: true,
        }).done(function() {
            console.log("done");
        }).fail(function() {
            console.log("error");
        }).always(function() {
            window.location.reload(true);
        });
    });

    // Blur focused elements on modal hide to prevent aria-hidden focus warnings in the browser console
    $('.modal').on('hide.bs.modal', function () {
        if (document.activeElement) {
            document.activeElement.blur();
        }
    });
});
