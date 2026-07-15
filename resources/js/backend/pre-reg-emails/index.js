$( document ).ready(function() {
    $("button.btn-info").click(function(){
        if($(this).text()=="Resend Email"){
            $( "#customerEdit" ).submit();
        }
    });

    $("a.btn-secondary").click(function(){
        $("#customerEmailEdit").val($(this).parent().prev().text());
        $("#customerID").val($(this).parent().prev().prev().prev().text());
        jQuery('#customer-modal-edit').modal('show');
    });
});

//Popup date picker.
$("#NHRAstartDate").datepicker({ minView: 2,autoclose: true,format: 'mm/dd/yyyy'});

$("#NHRAendDate").datepicker({ minView: 2,autoclose: true,format: 'mm/dd/yyyy'});
