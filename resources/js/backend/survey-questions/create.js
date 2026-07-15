$( document ).ready(function() {
    $("#AddQuestionDependency input:radio").click(function(){
        $('#AddQuestionDependency input[type=checkbox]').removeAttr('checked');
        $('#AddQuestionDependency input[type=checkbox]').attr('disabled', 'disabled');
        $(this).next().find("input[type=checkbox]").removeAttr('disabled');
    });

    $("button.btn-info").click(function(){
        if($(this).attr("id")=="sessionanswer"){
            $.ajax({
                method: "POST",
                url: "/manage-survey-answers",
                data: $( "#SessionAnswerForm" ).serialize() + "&QuestionIndex=" + $("#EditQuestionIndex").val()
            })
            .done(function( ans_data ) {
                var ans_obj = ans_data;
                var response_html = "<tr id=row-"+ans_obj.Count+">";
                response_html += "<td>"+ans_obj.Count+"</td>";
                response_html += "<td>"+ans_obj.AnswerText+"</td>";
                response_html += "<td>"+ans_obj.Required+"</td>";
                response_html += "<td>"+ans_obj.AnswerType+"</td>";
                response_html += '<td><input type="hidden" name="MailedFlag" value="'+($("#AnswerMailed").is(':checked') ? 1 : 0)+'"><a href="javascript:;" onclick="javascript:EditAnswers(this);" class="btn btn-secondary btn-sm btn-icon icon-left">Edit</a><a href="javascript:;" id="" onclick="javascript:deleteAnswer(this)" class="btn btn-danger btn-icon"><i class="icon-white icon-heart"></i> Delete</a></td></tr>';

                $("#SessionAnswerTable").find("tbody").append(response_html);
                $('#answer-modal').modal('hide');
            });
        }
        else if($(this).attr("id")=="editsessionanswer"){
            $.ajax({
                method: "POST",
                url: "/manage-survey-answers/" + $("#EditAnswerIndex").val(),
                data: $( "#EditSessionAnswerForm" ).serialize() + "&QuestionIndex=" + $("#EditQuestionIndex").val()
            })
            .done(function( ans_data ) {
                var ans_obj = ans_data;
                var response_html = "";
                response_html += "<td>"+ans_obj.Count+"</td>";
                response_html += "<td>"+ans_obj.AnswerText+"</td>";
                response_html += "<td>"+ans_obj.Required+"</td>";
                response_html += "<td>"+ans_obj.AnswerType+"</td>";
                response_html += '<td><input type="hidden" name="MailedFlag" value="'+($("#EditAnswerMailed").is(':checked') ? 1 : 0)+'"><a href="javascript:;" onclick="javascript:EditAnswers(this);" class="btn btn-secondary btn-sm btn-icon icon-left">Edit</a><a href="javascript:;" id="" onclick="javascript:deleteAnswer(this)" class="btn btn-danger btn-icon"><i class="icon-white icon-heart"></i> Delete</a></td>';

                $("#SessionAnswerTable").find("#row-"+ans_obj.Count).html(response_html);
                $('#answer-edit-modal').modal('hide');
            });
        }
        else if($(this).attr("id")=="deletesessionanswer"){
            $.ajax({
                method: "POST",
                url: "/manage-survey-answers/" + $("#DeleteAnswerIndex").val(),
                data: $( "#DeleteSessionAnswerForm" ).serialize() + "&QuestionIndex=" + $("#EditQuestionIndex").val()
            })
            .done(function( ans_data ) {
                var ans_obj = ans_data;
                $("#SessionAnswerTable").find("#row-"+ans_obj.Count).remove();
                $('#answer-modal-delete').modal('hide');
            });
        }
    });
});

function set_question_default_value(qtmpid,qreq){
    $("#"+qtmpid).val($("#QuestionName").val());
    if($('#QuestionRequired').is(':checked')){
        $("#"+qreq).val("YES");
    }
    else if($('#QuestionRequired1').is(':checked')){
        $("#"+qreq).val("NO");
    }
}

function EditAnswers(c_obj){
    set_question_default_value("EditTmpQuesName","EditTmpQuesRequired");
    $("#EditAnswerName").val($(c_obj).parent().prev().prev().prev().text());
    $("#EditAnswerType").val($(c_obj).parent().prev().text());
    $("#EditAnswerIndex").val($(c_obj).parent().prev().prev().prev().prev().text());
    var req = $(c_obj).parent().prev().prev().text();

    var MailedFlag = $(c_obj).prev().val();
    if(MailedFlag=="1"){
        $("#EditAnswerMailed").prop('checked', true);
    }
    else {
        $("#EditAnswerMailed1").prop('checked', true);
    }
    if(req=="YES"){
        $("#EditAnswerRequired").prop('checked', true);
    }
    else {
        $("#EditAnswerRequired1").prop('checked', true);
    }
    $('#answer-edit-modal').modal('show');
}

function AddAnswer(c_obj) {
    set_question_default_value("tmpQuesName","tmpQuesRequired");
    $("#AnswerName").val("");
    $("#AnswerType").val("");
    $("#AddQuestionDependency input:radio").attr("checked", false);
    $('#answer-modal').modal('show');
}

function deleteAnswer(c_obj){
    $("#DeleteAnswerIndex").val($(c_obj).parent().prev().prev().prev().prev().text());
    $('#answer-modal-delete').modal('show');
}

window.EditAnswers = EditAnswers;
window.AddAnswer = AddAnswer;
window.deleteAnswer = deleteAnswer;
