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
        <li><a href="javascript:;" id="btn-add-sms-template">
            <span class="hidden-xs">Add SMS Template</span>
            </a>
        </li>
    </ul>
    <div class="panel panel-default">
        <form method="post" action="#" id="EmailTemplateEditForm">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="control-label" for="EmailTemplateSubjEdit">Select an sms template to edit</label>
                        <select class="form-control" id="EmailTemplateSubjEdit" name="EmailTemplateID">
                            <option value="">Select an SMS Template</option>
                            @foreach ($smstemplates->SMSTemplates as $emailtemplate)
                                <option value="{{ $emailtemplate->TemplateID }}!$!{{ $emailtemplate->SmsSubj }}">{{ $emailtemplate->SmsSubj }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="EmailTemplateSub">Subject</label>
                        <input id="EmailTemplateSub" class="form-control" type="text" placeholder="" name="EmailTemplateSub">
                    </div>
                    <div class="form-group">
                        <textarea maxlength="160" class="form-control smsTextarea" name="TemplateBlob1" id="TemplateBlob1" rows="10"></textarea>
                        <span style="font-weight: bold; color: red;">
                            <span class="EditSMSLIMITERRROR">160</span>
                            Character(s) Remaining
                        </span>
                    </div>
                    <div class="form-group">
                        <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                        <button type="button" id="btn-save-changes" class="btn btn-info">Save Changes</button>
                        @if(!auth()->check() || auth()->user()?->userlevel == 1)
                        <button type="button" class="btn btn-danger" id="btn-delete-sms-template">Delete</button>
                        @endif
                    </div>
                    @foreach ($smstemplates->SMSTemplates as $emailtemplate)
                        <input type="hidden" id="TemplateBlobTmp{{ $emailtemplate->TemplateID }}" value="{{ $emailtemplate->TemplateBlob }}">
                        <input type="hidden" id="TemplateSub{{ $emailtemplate->TemplateID }}" value="{{ $emailtemplate->SmsTemplateSubj }}">
                    @endforeach
                </div>
            </div>
        </form>
    </div>
    <!-- Main Footer -->
    @include('layouts.backend.footer')
</div>
<!-- content @e -->

<!-- Delete SMS Template Modal -->
<div class="modal fade custom-width" id="emailtemplate-modal-delete" tabindex="-1" role="dialog" aria-labelledby="emailtemplate-modal-delete-label" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="emailtemplate-modal-delete-label">Are you sure? </h4>
            </div>
            <form method="post" action="#" id="EmailTemplateDelete">
                @csrf
                @method('DELETE')
                <input type="hidden" name="DeleteEmailTemplateID" id="DeleteEmailTemplateID" value="">
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                    <button type="button" id="btn-delete-confirm" class="btn btn-info">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- SMS Template Create Modal -->
<div class="modal fade custom-width" id="template-modal" tabindex="-1" role="dialog" aria-labelledby="template-modal-label" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="template-modal-label">Add SMS Template</h4>
            </div>
            <div class="modal-body">
                <form method="post" action="{{ route('manage-sms-templates.store') }}" id="EmailTemplateForm">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="field-1" class="control-label">SMS Template Name</label>
                                <input type="text" name="EmailTemplateSubj" class="form-control" id="field-1" placeholder="">
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="EmailSubjectCreate">Subject</label>
                                <input id="EmailSubjectCreate" class="form-control" type="text" placeholder="" name="EmailSubject">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <textarea maxlength="160" class="form-control addSmsTextarea" rows="10" name="TemplateBlob"></textarea>
                                <span style="font-weight: bold; color: red;">
                                    <span class="AddSMSLIMITERRROR">160</span>
                                    Character(s) Remaining
                                </span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                <button type="button" id="btn-create-confirm" class="btn btn-info">Create</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@vite(['resources/js/backend/sms-templates/index.js'])
{!! returnScriptWithNonce(asset('vendor/jsvalidation/js/jsvalidation.js')) !!}
{!! JsValidator::formRequest('App\Http\Requests\Backend\SmsTemplateRequest', '#EmailTemplateForm') !!}
{!! JsValidator::formRequest('App\Http\Requests\Backend\SmsTemplateRequest', '#EmailTemplateEditForm') !!}
{!! JsValidator::formRequest('App\Http\Requests\Backend\SmsTemplateRequest', '#EmailTemplateDelete') !!}
@endpush
