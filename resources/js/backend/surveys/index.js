function deleteSurvey(c_obj){
    var id = $(c_obj).attr("id");
    $("#DeleteSurveyIndex").val(id);
    $("#DeleteSessionSurveyForm").attr('action', '/manage-surveys/' + id);
    $('#survey-modal-delete').modal('show');
}

window.deleteSurvey = deleteSurvey;

$( document ).ready(function() {
    $.session.set("SurveyName", "");
});
