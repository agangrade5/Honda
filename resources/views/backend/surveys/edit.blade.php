@extends('layouts.backend.app')
@section('title', $title)
@section('content')
<!-- content @s -->
<div class="main-content">
    <!-- Content Header section -->
    @include('layouts.backend.content_header', compact('title'))
    <?php /* if(isset($_SESSION['msg']) && !empty($_SESSION['msg'])){ ?>
    <div class="dx-warning">
        <div>
            <p><?php echo $_SESSION['msg'];?></p>
        </div>
    </div>
    <?php }
        unset($_SESSION['msg']); */
        ?>
    <!-- Add Question -->
    <div class="custom-width" id="truck-modal">
        <div class="">
            <div class="">
                <form method="post" action="Action.php" id="SessionSurveyForm">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-2">
                                <label class="control-label" for="social_media"><b>Survey Name</b></label>
                            </div>
                            <div class="col-md-10">
                                <div class="form-group">
                                    <input type="text" class="form-control" id="SurveyName" name="SurveyName" value="<?php echo (isset($survey_name) && !empty($survey_name) ? $survey_name : ''); ?>" placeholder="">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <ul class="nav nav-tabs right-aligned">
                                    <!--available classes "right-aligned" -->
                                    <li>
                                        <a href="{{ route('manage-survey-questions.create') }}?SurveyID=<?php echo (isset($survey_id) && !empty($survey_id) ? $survey_id : ''); ?>"><span class="hidden-xs">Add Question</span></a>
                                    </li>
                                </ul>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">Questions</h3>
                                        <div class="panel-options">
                                            <a href="#" data-toggle="panel">
                                            <span class="collapse-icon">&ndash;</span>
                                            <span class="expand-icon">+</span>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="panel-body">
                                        <table class="table table-bordered table-striped" id="SessionQuestionTable">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Question</th>
                                                    <th>Required</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody class="middle-align">
                                                <?php if(isset($_SESSION['questions']) && !empty($_SESSION['questions'])) {
                                                    foreach($_SESSION['questions'] as $q_key => $question){ ?>
                                                <tr id="question-id-<?php echo ($q_key+1);?>">
                                                    <td><?php echo $q_key; ?></td>
                                                    <td> <?php echo $question->QuestionText[0]->LanguageText; ?></td>
                                                    <td><?php echo $question->Required; ?></td>
                                                    <td>
                                                        <a href="AddSurveyQuestion.php?QID=<?php echo $q_key; ?>&SurveyID=<?php echo $survey_id; ?>" class="btn btn-secondary btn-sm btn-icon icon-left">
                                                        Edit
                                                        </a>
                                                        <a href="javascript:;" id="" onclick="javascript:deleteQuestion(this);" class="btn btn-danger btn-icon"><i class="icon-white icon-heart"></i> Delete</a>
                                                    </td>
                                                </tr>
                                                <?php }} ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php if(isset($_GET['SurveyID']) && !empty($_GET['SurveyID'])){ ?>
                        <input type="hidden" name="SurveyIndex" value="<?php echo $_GET['SurveyID']; ?>"/>
                        <input type="hidden" name="action" value="edit">
                        <?php } else { ?>
                        <input type="hidden" name="action" value="add">
                        <?php } ?>
                        <input type="hidden" name="controller" value="survey">
                    </div>
                    <div class="modal-footer">
                        <!-- <button type="button" class="btn btn-white" data-dismiss="modal">Close</button> -->
                        <input type="submit" class="hog-button btn btn-info btn-secondary" id="" value="<?php if(isset($_GET['SurveyID']) && !empty($_GET['SurveyID'])){ echo 'Update'; } else { echo 'Create';}?> ">
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Main Footer -->
    @include('layouts.backend.footer')
</div>
<!-- content @e -->
@endsection

@push('scripts')
<script src="{{ asset("assets/js/jquery.session.js")}}"></script>
<script>
    $( document ).ready(function() {
    	$("button.btn-info").click(function(){
    		if($(this).attr("id")=="sessionanswer"){
    			$.ajax({
    			method: "POST",
    			url: "Action.php?QuestionIndex="+$("#EditQuestionIndex").val(),
    			data: $( "#SessionAnswerForm" ).serialize()
    		})
      		.done(function( ans_data ) {
    			var ans_obj = JSON.parse(ans_data);

    			var response_html = "<tr id=row-"+ans_obj.Count+">";
    			response_html += "<td>"+ans_obj.Count+"</td>";
    			response_html += "<td>"+ans_obj.AnswerText+"</td>";
    			response_html += "<td>"+ans_obj.Required+"</td>";
    			response_html += "<td>"+ans_obj.AnswerType+"</td>";
    			response_html += '<td><a href="javascript:;" onclick="javascript:EditAnswers(this);" class="btn btn-secondary btn-sm btn-icon icon-left">Edit</a><a href="javascript:;" id="" onclick="javascript:deleteAnswer(this)" class="btn btn-danger btn-icon"><i class="icon-white icon-heart"></i> Delete</a></td></tr>';

    			if($("#EditQuestionSession").val()=="addquestionsession"){
    					$("#SessionAnswerTable").find("tbody").append(response_html);
    				}
    				else if($("#EditQuestionSession").val()=="editquestionsession"){
    					//Manage Dependent Data
    					dependent_question_answer(ans_obj.Response);

    					$("#EditSessionAnswerTable").find("tbody").append(response_html);
    				}
      		});
    		}
    		else if($(this).attr("id")=="editsessionanswer"){  //This is for edit answer
    			$.ajax({
    				method: "POST",
    				url: "Action.php?QuestionIndex="+$("#EditQuestionIndex").val(),
    				data: $( "#EditSessionAnswerForm" ).serialize()
    			})
    	  		.done(function( ans_data ) {
    				var ans_obj = JSON.parse(ans_data);

    	  			var response_html = "";
    				response_html += "<td>"+ans_obj.Count+"</td>";
    				response_html += "<td>"+ans_obj.AnswerText+"</td>";
    				response_html += "<td>"+ans_obj.Required+"</td>";
    				response_html += "<td>"+ans_obj.AnswerType+"</td>";
    				response_html += '<td><a href="javascript:;" onclick="javascript:EditAnswers(this);" class="btn btn-secondary btn-sm btn-icon icon-left">Edit</a><a href="javascript:;" id="" onclick="javascript:deleteAnswer(this)" class="btn btn-danger btn-icon"><i class="icon-white icon-heart"></i> Delete</a></td>';
    				//$("#EditQuestionSession").val("addquestionsession");
    				if($("#EditQuestionSession").val()=="addquestionsession"){
    					$("#SessionAnswerTable").find("#row-"+ans_obj.Count).html(response_html);
    				}
    				else if($("#EditQuestionSession").val()=="editquestionsession"){
    					//Manage Dependent Data
    					dependent_question_answer(ans_obj.Response);

    					$("#EditSessionAnswerTable").find("#row-"+ans_obj.Count).html(response_html);
    				}
    			});
    		}
    		else if($(this).attr("id")=="deletesessionanswer"){  // This is for deleting answer
    			$.ajax({
    				method: "POST",
    				url: "Action.php?QuestionIndex="+$("#EditQuestionIndex").val(),
    				data: $( "#DeleteSessionAnswerForm" ).serialize()
    			})
    	  		.done(function( ans_data ) {
    				var ans_obj = JSON.parse(ans_data);
    				if($("#EditQuestionSession").val()=="addquestionsession"){
    					$("#row-"+ans_obj.Count).remove();
    				}
    				else if($("#EditQuestionSession").val()=="editquestionsession"){
    					$("#EditSessionAnswerTable").find("#row-"+ans_obj.Count).remove();
    				}

    			});
    		}
    		// This is for add questions
    		if($(this).attr("id")=="sessionquestion"){
    			$.ajax({
    			method: "POST",
    			url: "Action.php",
    			data: $( "#SessionQuestionForm" ).serialize()
    		})
      		.done(function( ans_data ) {

      			//Convert PHP JSON Response to Javascript Object.
      			var ans_obj = JSON.parse(ans_data);
      			dependent_question_answer(ans_obj.Response);


    			var response_html = "<tr id='question-id-"+ans_obj.Count+"'>";
    				response_html += "<td>"+ans_obj.Count+"</td>";
    				response_html += "<td>"+ans_obj.QuestionText+"</td>";
    				response_html += "<td>"+ans_obj.Required+"</td>";
                	response_html += '<td><a href="javascript:;"  onclick="javascript:EditQuestions(this);" class="btn btn-secondary btn-sm btn-icon icon-left">Edit</a><a href="javascript:;" id="" onclick="javascript:deleteQuestion(this);" class="btn btn-danger btn-icon"><i class="icon-white icon-heart"></i> Delete</a></td></tr>';
    				$("#SessionQuestionTable").find("tbody").append(response_html);

      		});
    		}
    		else // This is for add Survey
    		if($(this).attr("id")=="sessionsurvey"){
    			$.ajax({
    			method: "POST",
    			url: "Action.php",
    			data: $( "#SessionSurveyForm" ).serialize()
    		})
      		.done(function( ans_data ) {

      			//Convert PHP JSON Response to Javascript Object.
      			console.log(ans_data);

      		});
    		}
    		else if($(this).attr("id")=="editsessionquestion"){ // This is for edit question
    			$.ajax({
    			method: "POST",
    			url: "Action.php?QuestionIndex="+$("#EditQuestionIndex").val(),
    			data: $( "#EditSessionQuestionForm" ).serialize()
    		})
      		.done(function( ans_data ) {
    			var ans_obj = JSON.parse(ans_data);
    			console.log(ans_obj.Dependency.Answers);
    			$("#edit-dependency-answer-"+ans_obj.Dependency.QuestionID).val(ans_obj.Dependency.Answers);
    			var response_html = "";
    				response_html += "<td>"+ans_obj.Count+"</td>";
    				response_html += "<td>"+ans_obj.QuestionText+"</td>";
    				response_html += "<td>"+ans_obj.Required+"</td>";
                	response_html += '<td><a href="javascript:;"  onclick="javascript:EditQuestions(this);" class="btn btn-secondary btn-sm btn-icon icon-left">Edit</a><a href="javascript:;" id="" onclick="javascript:deleteQuestion(this);" class="btn btn-danger btn-icon"><i class="icon-white icon-heart"></i> Delete</a></td>';
    				$("#SessionQuestionTable").find("#question-id-"+ans_obj.Count).html(response_html);

      		});
    		}
    		else if($(this).attr("id")=="deletesessionquestion"){
    			$.ajax({
    				method: "POST",
    				url: "Action.php",
    				data: $( "#DeleteSessionQuestionForm" ).serialize()
    			})
    	  		.done(function( ans_data ) {
    				var ans_obj = JSON.parse(ans_data);
    				$("#DependenceyQuestion"+(parseInt(ans_obj.Count)-1)).next().remove();
    				$("#DependenceyQuestion"+(parseInt(ans_obj.Count)-1)).remove();
    				$("#SessionQuestionTable").find("#question-id-"+ans_obj.Count).remove();
    			});
    		}

    	});
    });

    function dependent_question_answer(Response){
    	//Manage Dependency of Add/Edit Question.
    	var depend_html_e = '<div class="col-md-4">';
    	depend_html_e += '<label class="control-label" for="social_media">Dependency</label>';
    	depend_html_e += '</div><div class="col-md-8"><div class="form-group">';

    	var depend_html_a = depend_html_e;
    	var dependent_q_count =1;
    	$.each(Response,function(index_q,value_q){

    		//This code for Add dependent.
    		depend_html_a += '<input type="radio" class="" value="'+(parseInt(index_q)+1)+'" id="DependenceyQuestion-'+(parseInt(index_q)+1)+'" name="DependenceyQuestion" placeholder="">';
    		depend_html_a += value_q.QuestionText;
    		depend_html_a += '<div class="row"><div class="col-md-12"><div class="form-group">';


    		depend_html_e += '<input type="hidden" id="question-'+(parseInt(index_q)+1)+'-answer" value=\''+JSON.stringify(value_q.Answers)+'\'/>';
    		var depend_q_edit = "";
    		var depedent_q_checked = "";
    		if (typeof (value_q.Dependency.QuestionID) !== "undefined") {
    			depend_q_edit = value_q.Dependency.QuestionID;
    			depedent_q_checked = "checked='checked'";
    		}
    		depend_html_e += '<input type="hidden" id="edit-dependency-question-'+(parseInt(index_q)+1)+'" value="'+depend_q_edit+'"/>';
    		var depend_a_edit = "";
    		if (typeof (value_q.Dependency.Answers) !== "undefined") {
    			depend_a_edit = value_q.Dependency.Answers;
    			console.log(value_q.Dependency.Answers);
    		}
    		depend_html_e += '<input type="hidden" id="edit-dependency-answer-'+(parseInt(index_q)+1)+'" value="'+depend_a_edit+'"/>';
    		depend_html_e += '<input type="radio"  '+depedent_q_checked+' onclick="javascript:radioClick(this);" value="'+dependent_q_count+'" id="EditDependenceyQuestion-'+dependent_q_count+'" name="DependenceyQuestion" placeholder="">';
    		depend_html_e += value_q.QuestionText;

    		//Manage Answer
    		depend_html_e += '<div class="row"><div class="col-md-12"><div class="form-group">';
    		$.each(value_q.Answers,function(index_a,value_a){

    			//This is for add
    			depend_html_a += '<input type="checkbox" class="" value="'+(parseInt(index_a)+1)+'" id="DependenceyAnswer-'+(parseInt(index_a)+1)+'" name="DependenceyAnswer[]" placeholder="">';
    			depend_html_a += value_a.AnswerText;

    			depend_html_e += '<input type="checkbox" disabled="disabled" value="'+(parseInt(index_a)+1)+'" id="EditDependenceyAnswer-'+(parseInt(index_a)+1)+'" name="DependenceyAnswer[]" placeholder="">';
    			depend_html_e += value_a.AnswerText;
    		});
    		depend_html_e += '</div></div></div>';
    		depend_html_a += '</div></div></div>';

    		dependent_q_count++;
    	});
    	depend_html_e += '</div></div></div>';
    	depend_html_a += '</div></div></div>';
    	$("#EditQuestionDependency").html(depend_html_e);
    	$("#AddQuestionDependency").html(depend_html_a);
    	console.log("Done");
    }

    /**
    * function EditAnswers
    * @params:c_obj is the current object
    **/
    function EditAnswers(c_obj){

    	$("#EditAnswerName").val($(c_obj).parent().prev().prev().prev().text());
    	$("#EditAnswerType").val($(c_obj).parent().prev().text());
    	$("#EditAnswerIndex").val($(c_obj).parent().prev().prev().prev().prev().text());
    	var req = $(c_obj).parent().prev().prev().text();

    	if(req=="Yes"){
    		$("#EditAnswerRequired").prop('checked', true);
    	}
    	else if(req=="No"){
    		$("#EditAnswerRequired1").prop('checked', true);
    	}
     	$('#answer-edit-modal').modal('show');
    }

    /**
    * function AddQuestions
    * @params:c_obj is the current object
    **/
    function AddQuestions(c_obj) {
    	$("#QuestionName").val("");
    	$("input:radio").attr("checked", false);
    	$("#SessionAnswerTable").find("tbody").html("");
    	$("#EditQuestionSession").val("addquestionsession");
    	$("#DeleteQuestionSession").val("addquestionsession");
    	$("#AddQuestionSession").val("addquestionsession");
    	$('#question-modal').modal('show');
    }

    function AddAnswer(c_obj) {
    	$("#AnswerName").val("");
    	$("#AnswerType").val("");
    	$("input:radio").attr("checked", false);
    	$('#answer-modal').modal('show');
    }

    /**
    * function EditQuestions
    * @params:c_obj is the current object
    **/
    function EditQuestions(c_obj){

    	$("#EditQuestionName").val($(c_obj).parent().prev().prev().text());
    	var index = $(c_obj).parent().prev().prev().prev().text();
    	$("#EditQuestionIndex").val(index);
    	var req = $(c_obj).parent().prev().text();
    	if(req=="Yes"){
    		$("#EditQuestionRequired").prop('checked', true);
    	}
    	else if(req=="No"){
    		$("#EditQuestionRequired1").prop('checked', true);
    	}


    	$('input[type=checkbox]').attr('disabled', 'disabled');
    	$('input[type=checkbox]').removeAttr('checked');
    	$("#EditDependenceyQuestion-"+$("#edit-dependency-question-"+index).val()).attr('checked', 'checked');
    	$("#EditDependenceyQuestion-"+$("#edit-dependency-question-"+index).val()).next().find("input:checkbox").each(function() {
       		$(this).removeAttr('disabled');
    	});

    	//Manage check box selection.
    	if (typeof ($("#edit-dependency-answer-"+index).val()) === "undefined") {
    		console.log("Un");
    	}
    	else {
    		console.log($("#edit-dependency-answer-"+index).val().split(','));
    		$.each($("#edit-dependency-answer-"+index).val().split(','),function(tmp_index,value){
    			$("#EditDependenceyQuestion-"+$("#edit-dependency-question-"+index).val()).next().find("#EditDependenceyAnswer-"+value).attr('checked', 'checked');
    		});
    	}

    	$("#EditQuestionSession").val("editquestionsession");
    	$("#DeleteQuestionSession").val("editquestionsession");
    	$("#AddQuestionSession").val("editquestionsession");

    	//Show Answers
    	if (typeof ($("#question-"+parseInt(index)+"-answer").val()) === "undefined") {
    		console.log("Un");
    	}
    	else {
    		var ans_obj = JSON.parse($("#question-"+parseInt(index)+"-answer").val());
    		var answer_html = "";
    		$.each(ans_obj,function(index,value){
    		 	answer_html += "<tr id='row-"+(parseInt(index)+1)+"'>";
    			answer_html += "<td>"+(parseInt(index)+1)+"</td>";
    			answer_html += "<td>"+value.AnswerText+"</td>";
    			answer_html += "<td>"+value.Required+"</td>";
    			answer_html += "<td>"+value.AnswerType+"</td>";
    			answer_html += '<td><a href="javascript:;" onclick="javascript:EditAnswers(this);" class="btn btn-secondary btn-sm btn-icon icon-left">Edit</a><a href="javascript:;" id="" onclick="javascript:deleteAnswer(this)" class="btn btn-danger btn-icon"><i class="icon-white icon-heart"></i> Delete</a></td></tr>';

    		});
    		$("#EditSessionAnswerTable").find("tbody").html(answer_html);
    	}
     	$('#question-edit-modal').modal('show');
    }

    //Enable selected Radio button
    function radioClick(c_obj){
    	$('input[type=checkbox]').removeAttr('checked');
    	$('input[type=checkbox]').attr('disabled', 'disabled');
    	console.log("Click");
    	$(c_obj).next().find("input:checkbox").each(function() {
    		$(this).removeAttr('disabled');
    	});
    }

    function deleteAnswer(c_obj){
    	$("#DeleteAnswerIndex").val($(c_obj).parent().prev().prev().prev().prev().text());
    	$('#answer-modal-delete').modal('show');
    }

    function deleteQuestion(c_obj){
    	$("#DeleteQuestionIndex").val($(c_obj).parent().prev().prev().prev().text());
    	$('#question-delete-modal').modal('show');
    }
</script>
@endpush
