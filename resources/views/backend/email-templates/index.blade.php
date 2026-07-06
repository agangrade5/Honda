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
        <li><a href="javascript:;" onclick="jQuery('#template-modal').modal('show');">
            <span class="hidden-xs">Add Email Template</span>
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
                        <label class="control-label" for="EmailTemplateSubjEdit">Select an email template to edit</label>
                        <select class="form-control" id="EmailTemplateSubjEdit" name="EmailTemplateID">
                            <option value="">Select an Email Template</option>
                            @foreach ($emailtemplates->EmailTemplates as $emailtemplate)
                                <option value="{{ $emailtemplate->TemplateID }}!$!{{ $emailtemplate->EmailSubj }}">{{ $emailtemplate->EmailSubj }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="EmailTemplateSub">Subject</label>
                        <input id="EmailTemplateSub" class="form-control" type="text" placeholder="" name="EmailTemplateSub">
                    </div>
                    <div class="form-group" style="text-align:right;">
                        <button type="button" id="btn-send-test-modal" class="btn btn-info">Send Test Email</button>
                    </div>
                    <div class="form-group">
                        <textarea class="form-control ckeditor" name="TemplateBlob1" id="TemplateBlob1" rows="10"></textarea>
                    </div>
                    <div class="form-group">
                        <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                        <button type="button" id="btn-save-changes" class="btn btn-info">Save Changes</button>
                        @if(!auth()->check() || auth()->user()?->userlevel == 1)
                        <button type="button" class="btn btn-danger" onclick="openModel();">Delete</button>
                        @endif
                    </div>
                    @foreach ($emailtemplates->EmailTemplates as $emailtemplate)
                        <input type="hidden" id="TemplateBlobTmp{{ $emailtemplate->TemplateID }}" value="{{ $emailtemplate->TemplateBlob }}">
                        <input type="hidden" id="TemplateSub{{ $emailtemplate->TemplateID }}" value="{{ $emailtemplate->EmailTemplateSubj }}">
                    @endforeach
                </div>
            </div>
        </form>
    </div>
    <!-- Main Footer -->
    @include('layouts.backend.footer')
</div>
<!-- content @e -->

<!-- Delete Email Template Modal -->
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

<!-- Error Modal -->
<div class="modal fade custom-width" id="emailtemplate-modal-error" tabindex="-1" role="dialog" aria-labelledby="emailtemplate-modal-error-label" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="emailtemplate-modal-error-label"><b>Error - This email template is not setup properly. It is missing the dynamic link.<b> </h4>
            </div>
        </div>
    </div>
</div>

<!-- Test Email Template Modal -->
<div class="modal fade custom-width" id="sendemail-template-modal" tabindex="-1" role="dialog" aria-labelledby="sendemail-template-modal-label" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="sendemail-template-modal-label">Test Email Template</h4>
            </div>
            <div class="modal-body">
                <form method="post" action="{{ route('manage-email-templates.send-test') }}" id="EmailTemplateSendTestEmailForm">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label" for="EmailSubject">Email Address</label>
                                <input id="EmailSubject" class="form-control" type="text" placeholder="" name="EmailSubject">
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="EmailTemplateSubject" id="EmailTemplateSubject" value="">
                    <input type="hidden" name="template" value="" id="TestSendEmailTemplate">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                <button type="button" id="btn-send-test-confirm" class="btn btn-info">Send</button>
            </div>
        </div>
    </div>
</div>

<!-- Email Template Create Modal -->
<div class="modal fade custom-width" id="template-modal" tabindex="-1" role="dialog" aria-labelledby="template-modal-label" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="template-modal-label">Add Email Template</h4>
            </div>
            <div class="modal-body">
                <form method="post" action="{{ route('manage-email-templates.store') }}" id="EmailTemplateForm">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="field-1" class="control-label">Email Template Name</label>
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
                                <textarea class="form-control ckeditor" rows="10" name="TemplateBlob"></textarea>
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
<script>
    function openModel(){
        var val = $("#EmailTemplateSubjEdit").val();
        if(val && val !== ""){
            var id = $("#DeleteEmailTemplateID").val();
            $("#EmailTemplateDelete").attr('action', '/manage-email-templates/' + id);
            jQuery('#emailtemplate-modal-delete').modal('show');
        }
        else {
            alert("Please select Email Template that you want to delete!");
        }
    }

    $( document ).ready(function() {
        $("#btn-create-confirm").click(function(){
            var form = $( "#EmailTemplateForm" );
            if (form.valid()) {
                form.submit();
            }
        });

        $("#btn-send-test-modal").click(function(){
            var stringText = CKEDITOR.instances.TemplateBlob1.getData();
            $("#TestSendEmailTemplate").val(stringText);
            $("#EmailTemplateSubject").val($("#EmailTemplateSub").val());
            jQuery('#sendemail-template-modal').modal('show');
        });

        $("#btn-send-test-confirm").click(function(){
            var form = $( "#EmailTemplateSendTestEmailForm" );
            if (form.valid()) {
                form.submit();
            }
        });

        $("#btn-save-changes").click(function(){
            var stringText = CKEDITOR.instances.TemplateBlob1.getData();
            var textArea = document.createElement('textarea');
            textArea.innerHTML = stringText;
            var searchStr = textArea.value;
            if(searchStr.toLowerCase().indexOf('<a href="http://~prsurveyphoto~">')>=0){
                var form = $( "#EmailTemplateEditForm" );
                if (form.valid()) {
                    form.submit();
                }
            }
            else {
                jQuery('#emailtemplate-modal-error').modal('show');
                return false;
            }
        });

        $("#btn-delete-confirm").click(function(){
            var form = $("#EmailTemplateDelete");
            if (form.valid()) {
                form.submit();
            }
        });

    	$("#EmailTemplateSubjEdit").change(function(){
    		var curentVal = $(this).val();
            if (!curentVal) {
                $("#DeleteEmailTemplateID").val('');
                $("#EmailTemplateSub").val('');
                if (CKEDITOR.instances['TemplateBlob1']) {
                    CKEDITOR.instances['TemplateBlob1'].setData('');
                }
                return;
            }
    		var strArray = curentVal.split("!$!");
            var id = strArray[0];
    		$("#DeleteEmailTemplateID").val(id);
    		$("#EmailTemplateSub").val($("#TemplateSub"+id).val());
            $("#EmailTemplateEditForm").attr('action', '/manage-email-templates/' + id);

            var blobContent = $("#TemplateBlobTmp"+id).val();
            if (CKEDITOR.instances['TemplateBlob1']) {
                CKEDITOR.instances['TemplateBlob1'].setData(blobContent);
            }
    	});

        // Blur focused elements on modal hide to prevent aria-hidden focus warnings in the browser console
        $('.modal').on('hide.bs.modal', function () {
            if (document.activeElement) {
                document.activeElement.blur();
            }
        });

        // Reset validation errors and form inputs on modal close
        $('.modal').on('hidden.bs.modal', function () {
            var form = $(this).find('form');
            if (form.length > 0) {
                form.each(function() {
                    this.reset();
                    if (typeof $(this).validate === 'function') {
                        var validator = $(this).validate();
                        if (validator) {
                            validator.resetForm();
                        }
                    }
                    $(this).find('.has-error').removeClass('has-error');
                    $(this).find('.error').removeClass('error');
                    $(this).find('.help-block').remove();
                });
            }
        });
    });
</script>
<script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js') }}"></script>
{!! JsValidator::formRequest('App\Http\Requests\Backend\EmailTemplateRequest', '#EmailTemplateForm') !!}
{!! JsValidator::formRequest('App\Http\Requests\Backend\EmailTemplateRequest', '#EmailTemplateEditForm') !!}
{!! JsValidator::formRequest('App\Http\Requests\Backend\EmailTemplateRequest', '#EmailTemplateDelete') !!}
{!! JsValidator::formRequest('App\Http\Requests\Backend\EmailTemplateRequest', '#EmailTemplateSendTestEmailForm') !!}
@endpush
