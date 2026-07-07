@extends('layouts.backend.app')
@section('title', $title)
@section('content')
@php
    $getQuestionText = function($q) {
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
        return is_array($q) ? ($q['Required'] ?? 'NO') : ($q->Required ?? 'NO');
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

    <!-- Add Question -->
    <div class="custom-width" id="truck-modal">
        <div class="">
            <div class="">
                <form method="post" action="{{ route('manage-surveys.update', $survey_id) }}" id="SessionSurveyForm">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-2">
                                <label class="control-label" for="SurveyName"><b>Survey Name</b></label>
                            </div>
                            <div class="col-md-10">
                                <div class="form-group">
                                    <input type="text" class="form-control" id="SurveyName" name="SurveyName" value="{{ $survey_name ?? '' }}" placeholder="" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <ul class="nav nav-tabs right-aligned">
                                    <!--available classes "right-aligned" -->
                                    <li>
                                        <a href="{{ route('manage-survey-questions.create') }}?SurveyID={{ $survey_id }}"><span class="hidden-xs">Add Question</span></a>
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
                                                @if(session('questions'))
                                                    @foreach(session('questions') as $q_key => $question)
                                                    <tr id="question-id-{{ $q_key + 1 }}">
                                                        <td>{{ $q_key }}</td>
                                                        <td>{{ $getQuestionText($question) }}</td>
                                                        <td>{{ $getQuestionRequired($question) }}</td>
                                                        <td>
                                                            <a href="{{ route('manage-survey-questions.edit', $q_key) }}?SurveyID={{ $survey_id }}" class="btn btn-secondary btn-sm btn-icon icon-left">
                                                            Edit
                                                            </a>
                                                            <a href="javascript:;" onclick="deleteQuestion(this);" class="btn btn-danger btn-icon btn-sm"><i class="icon-white icon-heart"></i> Delete</a>
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
                        <input type="hidden" name="SurveyIndex" value="{{ $survey_id }}"/>
                    </div>
                    <div class="modal-footer">
                        <input type="submit" class="hog-button btn btn-info btn-secondary" value="Update">
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Main Footer -->
    @include('layouts.backend.footer')
</div>
<!-- content @e -->

<!-- Question Delete Modal -->
<div class="modal fade custom-width" id="question-delete-modal" tabindex="-1" role="dialog" aria-labelledby="question-modal-label" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Are you sure? </h4>
            </div>
            <form method="post" action="#" id="DeleteSessionQuestionForm">
                @csrf
                @method('DELETE')
                <input type="hidden" name="QuestionIndex" id="DeleteQuestionIndex" value="">
                <input type="hidden" name="SurveyIndex" value="{{ $survey_id }}"/>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                    <input type="submit" class="btn btn-info" id="deletesessionquestion" value="Delete"/>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/jquery.session.js') }}"></script>
<script>
    function deleteQuestion(c_obj){
        var id = $(c_obj).parent().prev().prev().prev().text();
        $("#DeleteQuestionIndex").val(id);
        $("#DeleteSessionQuestionForm").attr('action', '/manage-survey-questions/' + id);
        $('#question-delete-modal').modal('show');
    }

    $( document ).ready(function() {
    	$("#SurveyName").change(function(){
    		$.session.set("SurveyName", $(this).val());
    	});
    });
</script>
@endpush
