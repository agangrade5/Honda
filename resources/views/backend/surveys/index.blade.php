@extends('layouts.backend.app')
@section('title', $title)
@section('content')
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

    <ul class="nav nav-tabs right-aligned">
        <!-- available classes "right-aligned" -->
        <li>
            <a href="{{ route('manage-surveys.create') }}"><span class="hidden-xs">Add Survey</span></a>
        </li>
    </ul>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Survey</h3>
            <div class="panel-options">
                <a href="#" data-toggle="panel">
                <span class="collapse-icon">&ndash;</span>
                <span class="expand-icon">+</span>
                </a>
            </div>
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped" id="userTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="middle-align">
                    @if(isset($surveys) && !empty($surveys) && $surveys->Success==1)
                        @foreach ($surveys->Survey as $survey)
                        <tr>
                            <td>{{ $survey->SurveyID }}</td>
                            <td>{{ $survey->SurveyName }}</td>
                            <td>
                                <input type="hidden" id="SurveyID{{ $survey->SurveyID }}" value="{{ $survey->SurveyID }}">
                                <a href="{{ route('manage-surveys.edit', $survey->SurveyID) }}" id="CID{{ $survey->SurveyID }}" class="btn btn-secondary btn-sm btn-icon icon-left">
                                Edit
                                </a>
                                @if(!auth()->check() || auth()->user()?->userlevel == 1)
                                <a href="javascript:void(0);" id="{{ $survey->SurveyID }}" onclick="deleteSurvey(this);" class="btn btn-danger btn-icon"><i class="icon-white icon-heart"></i> Delete</a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
    <!-- Main Footer -->
    @include('layouts.backend.footer')
</div>
<!-- content @e -->

<!-- Delete Modal -->
<div class="modal fade custom-width" id="survey-modal-delete" tabindex="-1" role="dialog" aria-labelledby="template-modal-label" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Are you sure? </h4>
            </div>
            <form method="post" action="#" id="DeleteSessionSurveyForm">
                @csrf
                @method('DELETE')
                <input type="hidden" name="SurveyIndex" id="DeleteSurveyIndex" value="">
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                    <input type="submit" class="btn btn-info" id="deletesessionanswer" value="Delete" >
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/js/jquery.session.js') }}"></script>
<script type="text/javascript">
    function deleteSurvey(c_obj){
        var id = $(c_obj).attr("id");
        $("#DeleteSurveyIndex").val(id);
        $("#DeleteSessionSurveyForm").attr('action', '/manage-surveys/' + id);
        $('#survey-modal-delete').modal('show');
    }

    $( document ).ready(function() {
        $.session.set("SurveyName", "");
    });
</script>
@endpush
