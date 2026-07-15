window.onbeforeunload = function () {
    $(".page-loading-overlay").removeClass("loaded");
};
window.addEventListener("pageshow", function (event) {
    $(".page-loading-overlay").addClass("loaded");
});

jQuery(document).ready(function($)
{
    $.cookie('authorized','no');

    // Reveal Login form
    setTimeout(function(){ $(".fade-in-effect").addClass('in'); }, 1);

    // Validation and Ajax action
    $("form#login").validate({
        rules: {
            username: {
                required: true
            },
            passwd: {
                required: true
            }
        },

        messages: {
            username: {
                required: 'Please enter your username.'
            },
            passwd: {
                required: 'Please enter your password.'
            }
        },

        submitHandler: function(form)
        {
            form.submit();
        }
    });

    // Set Form focus
    $("form#login .form-group:has(.form-control):first .form-control").focus();

    $(document).on('click', '#toast-container .toast-close-button', function() {
        $('#toast-container').remove();
    });
});
