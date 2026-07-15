function deleteQuestion(c_obj){
    var id = $(c_obj).parent().prev().prev().prev().text();
    $("#DeleteQuestionIndex").val(id);
    $("#DeleteSessionQuestionForm").attr('action', '/manage-survey-questions/' + id);
    $('#question-delete-modal').modal('show');
}

window.deleteQuestion = deleteQuestion;

$( document ).ready(function() {
    $("#SurveyName").change(function(){
        $.session.set("SurveyName", $(this).val());
    });
});
