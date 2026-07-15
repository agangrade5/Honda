$( document ).ready(function() {
    $("#btn-file-history").on("click", function() {
        jQuery('#region-modal').modal('show');
    });

    var bookIndex = 0;

    $('#bookForm')
        .on('click', '.addButton', function() {
            bookIndex++;
            var $template = $('#bookTemplate'),
                $clone    = $template
                                .clone()
                                .removeClass('hide')
                                .removeAttr('id')
                                .attr('data-book-index', bookIndex)
                                .insertBefore($template);

            // Update the name attributes
            $clone
                .find('[name="card_suffix"]').attr('name', 'card[' + bookIndex + '][card_suffix]').end()
                .find('[name="card_no"]').attr('name', 'card1[' + bookIndex + '][card_no]').end();
        })

        .on('click', '.removeButton', function() {
            var $row  = $(this).parents('.form-group');
            $row.remove();
        });

    // Blur focused elements on modal hide to prevent aria-hidden focus warnings in the browser console
    $('.modal').on('hide.bs.modal', function () {
        if (document.activeElement) {
            document.activeElement.blur();
        }
    });
});
