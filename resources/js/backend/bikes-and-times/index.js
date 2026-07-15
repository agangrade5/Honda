$( document ).ready(function() {
    $("#btn-create-set").on("click", function() {
        jQuery('#region-modal').modal('show');
    });

    $(".btn-delete-set").on("click", function() {
        var id = $(this).data("id");
        $("#RegionDelete").attr("action", "/manage-bikes-and-times/" + id);
        jQuery('#region-modal-delete').modal('show');
    });

    // Blur focused elements on modal hide to prevent aria-hidden focus warnings in the browser console
    $('.modal').on('hide.bs.modal', function () {
        if (document.activeElement) {
            document.activeElement.blur();
        }
    });
});
