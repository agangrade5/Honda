$(document).ready(function($){
    $("form#SubmitFrm").validate({
        rules: {
            dob_1: { validDate: true },
            dob_2: { validDate: true },
            dob_3: { validDate: true },
            dob_4: { validDate: true },
            dob_5: { validDate: true },
            cellphone_1: { validPhoneLimit: 12 },
            cellphone_2: { validPhoneLimit: 12 },
            cellphone_3: { validPhoneLimit: 12 },
            cellphone_4: { validPhoneLimit: 12 },
            cellphone_5: { validPhoneLimit: 12 }
        },
        messages: {
            dob_1: { validDate: 'Please follow the date format MM-DD-YYYY with slashes' },
            cellphone_1: { validPhoneLimit: 'Please enter a value greater than or equal to 10' }
        },
        submitHandler: function(form){
            document.getElementById("SubmitFrm").submit();
        }
    });

    $(":input").inputmask();
    $("#cellphone_1").inputmask({"mask": "(999) 999-9999"});
});

function changeParentDetails(i){
    var under18_1 = $("input[name=under18_"+i+"]:checked").val();
    if(under18_1 == 1){
        $("#parentDetails_"+i).show();
    } else {
        $("#parentDetails_"+i).hide();
    }
}

window.changeParentDetails = changeParentDetails;
