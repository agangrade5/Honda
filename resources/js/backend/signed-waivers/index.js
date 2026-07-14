import { route } from 'ziggy-js';

$(document).ready(function() {
    // Initialize Server-Side DataTable
    $("#example-1").dataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": route('manage-signed-waivers.data'),
            "type": "POST",
            "headers": {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        }
    }).yadcf([
        {column_number : 0, filter_type: 'text'},
        {column_number : 1, filter_type: 'text'},
        {column_number : 2, filter_type: 'text'},
        {column_number : 3, filter_type: 'text'}
    ]);

    // PDF Download Trigger
    $( "#user-modal-edit a.btn-secondary" ).on( "click", function() {
        var waiverId = $("#WaiverDEditID").val();
        if (waiverId) {
            window.open('/manage-signed-waivers/pdf/' + waiverId);
        }
    });

    // View Signature Trigger
    $( "#example-1" ).on( "click", "tbody tr td a.btn-view-signature", function() {
        var WaiverDID = $(this).attr('id');
        $("#WaiverTitle").text($(this).parent().prev().text());
        
        var htmlData = $("#WaiverHTML" + WaiverDID).html();
        $("#WaiverHTMLEditView").html(htmlData);

        var imgName = $("#WaiverDOCLocation" + WaiverDID).val();
        var imgURL = window.location.origin + "/API/assets/legal/sigs/";
        var iname = imgName.substr(imgName.lastIndexOf("/") + 1);
        
        $("#WaiverSignedImg").attr("src", imgURL + iname);
        $("#WaiverDEditID").val(WaiverDID);
        $("#user-modal-edit").modal("show");
    });

    // Blur focused elements on modal hide to prevent aria-hidden focus warnings in the browser console
    $('.modal').on('hide.bs.modal', function () {
        if (document.activeElement) {
            document.activeElement.blur();
        }
    });
});
