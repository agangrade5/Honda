@extends('layouts.backend.app')
@section('title', $title)
@section('content')
@php
    $getQuestionText = function($q) {
        if (!$q) return '';
        $qText = '';
        $questionTextData = is_array($q) ? ($q['QuestionText'] ?? '') : ($q->QuestionText ?? '');
        if (is_array($questionTextData) && isset($questionTextData[0])) {
            $firstText = $questionTextData[0];
            $qText = is_array($firstText) ? ($firstText['LanguageText'] ?? '') : ($firstText->LanguageText ?? '');
        } else {
            $qText = is_string($questionTextData) ? $questionTextData : '';
        }
        return $qText;
    };

    $getQuestionRequired = function($q) {
        if (!$q) return 'NO';
        return is_array($q) ? ($q['Required'] ?? 'NO') : ($q->Required ?? 'NO');
    };

    $getQuestionDependencyQid = function($q) {
        if (!$q) return null;
        $dep = is_array($q) ? ($q['Dependency'] ?? null) : ($q->Dependency ?? null);
        if (!$dep) return null;
        return is_array($dep) ? ($dep['QuestionID'] ?? null) : ($dep->QuestionID ?? null);
    };

    $getQuestionDependencyAnswers = function($q) {
        if (!$q) return [];
        $dep = is_array($q) ? ($q['Dependency'] ?? null) : ($q->Dependency ?? null);
        if (!$dep) return [];
        $answers = is_array($dep) ? ($dep['Answers'] ?? null) : ($dep->Answers ?? null);
        if (!$answers) return [];
        return is_string($answers) ? json_decode($answers, true) : (array)$answers;
    };

    $getAnswerText = function($a) {
        if (!$a) return '';
        $aText = '';
        $answerTextData = is_array($a) ? ($a['AnswerText'] ?? '') : ($a->AnswerText ?? '');
        if (is_array($answerTextData) && isset($answerTextData[0])) {
            $firstText = $answerTextData[0];
            $aText = is_array($firstText) ? ($firstText['LanguageText'] ?? '') : ($firstText->LanguageText ?? '');
        } else {
            $aText = is_string($answerTextData) ? $answerTextData : '';
        }
        return $aText;
    };

    $getAnswerRequired = function($a) {
        if (!$a) return 'NO';
        return is_array($a) ? ($a['Required'] ?? 'NO') : ($a->Required ?? 'NO');
    };

    $getAnswerType = function($a) {
        if (!$a) return 1;
        return is_array($a) ? ($a['AnswerType'] ?? 1) : ($a->AnswerType ?? 1);
    };

    $getAnswerMailedFlag = function($a) {
        if (!$a) return 0;
        return is_array($a) ? ($a['MailedFlag'] ?? 0) : ($a->MailedFlag ?? 0);
    };
@endphp
<!-- content @s -->
<div class="main-content">
    <!-- Content Header section -->
    @include('layouts.backend.content_header', compact('title'))

    @if(session('status') == 'error')
    <div class="dx-warning">
        <div>
            <p>{!! session('msg') !!}</p>
        </div>
    </div>
    @elseif(session('status') == 'success')
    <div class="dx-success">
        <div>
            <p>{!! session('msg') !!}</p>
        </div>
    </div>
    @endif

    <div class="custom-width" id="question-modal">
        <div class="">
            <div class="">
                @if(isset($qid))
                <form method="post" action="{{ route('manage-survey-questions.update', $qid) }}" id="SessionQuestionForm">
                    @method('PUT')
                @else
                <form method="post" action="{{ route('manage-survey-questions.store') }}" id="SessionQuestionForm">
                @endif
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-2">
                                <label class="control-label" for="QuestionName">Question Name: </label>
                            </div>
                            <div class="col-md-9">
                                <div class="form-group">
                                    <input type="text" class="form-control" id="QuestionName" name="QuestionName" value="{{ $getQuestionText($question_data) ?: session('tmp_ques_name', '') }}" placeholder="" required>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    &nbsp;
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <label class="control-label">Required: </label>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <input type="radio" {{ $getQuestionRequired($question_data) == 'YES' || session('tmp_ques_required') == 'YES' ? 'checked="checked"' : '' }} id="QuestionRequired" value="YES" name="QuestionRequired"> YES
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <input type="radio" {{ (!isset($question_data) && !session()->has('tmp_ques_required')) || $getQuestionRequired($question_data) == 'NO' || session('tmp_ques_required') == 'NO' ? 'checked="checked"' : '' }} id="QuestionRequired1" value="NO" name="QuestionRequired"> NO
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    &nbsp;
                                </div>
                            </div>
                        </div>
                        <div class="row" id="AddQuestionDependency">
                            @if(session('questions') && !empty(session('questions')))
                            <div class="col-md-4">
                                <label class="control-label">Dependency</label>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    @php $q_count = 1; @endphp
                                    @foreach(session('questions') as $q_key => $question)
                                        @if(!isset($qid) || $qid != $q_key)
                                        <input type="radio" {{ $getQuestionDependencyQid($question_data) == $q_key ? "checked='checked'" : '' }} value="{{ $q_key }}" id="DependenceyQuestion-{{ $q_key }}" name="DependenceyQuestion">
                                        {{ $getQuestionText($question) }}
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    @php
                                                        $dependency_answers = $getQuestionDependencyAnswers($question_data);
                                                        $answersArray = isset($question->Answers) ? (array)$question->Answers : (isset($question['Answers']) ? (array)$question['Answers'] : []);
                                                    @endphp
                                                    @foreach($answersArray as $a_key => $answer)
                                                    <input type="checkbox" {{ is_array($dependency_answers) && in_array($a_key, $dependency_answers) && $getQuestionDependencyQid($question_data) == $q_key ? "checked='checked'" : '' }} value="{{ $a_key }}" id="DependenceyAnswer-{{ $a_key }}" name="DependenceyAnswer[]">
                                                    {{ $getAnswerText($answer) }}
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                        @php $q_count++; @endphp
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <ul class="nav nav-tabs right-aligned">
                                    <li>
                                        <a href="javascript:;" onclick="javascript:AddAnswer(this);"><span class="hidden-xs">Add Answer</span></a>
                                    </li>
                                </ul>
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">Answers</h3>
                                        <div class="panel-options">
                                            <a href="#" data-toggle="panel">
                                            <span class="collapse-icon">&ndash;</span>
                                            <span class="expand-icon">+</span>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="panel-body">
                                        <table class="table table-bordered table-striped" id="SessionAnswerTable">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Answer</th>
                                                    <th>Required</th>
                                                    <th>Answer Type</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody class="middle-align">
                                                @if(session('answers'))
                                                    @foreach(session('answers') as $a_key => $answer)
                                                    <tr id="row-{{ $a_key + 1 }}">
                                                        <td>{{ $a_key + 1 }}</td>
                                                        <td>{{ $getAnswerText($answer) }}</td>
                                                        <td>{{ $getAnswerRequired($answer) }}</td>
                                                        <td>{{ $getAnswerType($answer) }}</td>
                                                        <td>
                                                            <input type="hidden" name="MailedFlag" value="{{ $getAnswerMailedFlag($answer) }}">
                                                            <a href="javascript:;" onclick="javascript:EditAnswers(this);" class="btn btn-secondary btn-sm btn-icon icon-left">
                                                            Edit
                                                            </a>
                                                            <a href="javascript:;" onclick="javascript:deleteAnswer(this);" class="btn btn-danger btn-icon"><i class="icon-white icon-heart"></i> Delete</a>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="SurveyIndex" value="{{ $survey_id }}">
                        <input type="hidden" name="QuestionIndex" value="{{ $qid }}" id="EditQuestionIndex">
                    </div>
                    <div class="modal-footer">
                        <input type="submit" class="btn btn-info btn-secondary" value="{{ isset($qid) ? 'Update' : 'Create' }}">
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Main Footer -->
    @include('layouts.backend.footer')
</div>
<!-- content @e -->

@include('backend.survey-answers.modals')

@endsection

@push('scripts')
<script>
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
</script>
@endpush
