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
        <li><a href="javascript:;" id="btn-add-waiver">
            <span class="hidden-xs">Add Waiver</span>
            </a>
        </li>
    </ul>
    <div class="panel panel-default">
        <form method="post" action="#" id="WaiverEditForm">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="control-label" for="WaiverNameEdit">Select a Waiver to edit</label>
                        <select name="WaiverID" class="form-control" id="WaiverNameEdit">
                            <option value="">Select a Waiver</option>
                            @foreach ($waivers->Waivers as $waiver)
                                <option value="{{ $waiver->WaiverID }}!$!{{ $waiver->WaiverName }}">{{ $waiver->WaiverName }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <textarea class="form-control ckeditor" id="WaiverHTML1" name="WaiverHTML1" rows="10"></textarea>
                    </div>
                    <div class="form-group">
                        <button type="button" class="btn btn-info">Save Changes</button>
                        @if(!auth()->check() || auth()->user()?->userlevel == 1)
                        <button type="button" class="btn btn-danger" id="btn-delete-waiver">Delete</button>
                        @endif
                    </div>
                    @foreach ($waivers->Waivers as $waiver)
                        <div style="display:none;" id="WaiverHTML{{ $waiver->WaiverID }}">{!! $waiver->WaiverHTML !!}</div>
                    @endforeach
                </div>
            </div>
        </form>
    </div>
    <!-- Main Footer -->
    @include('layouts.backend.footer')
</div>
<!-- content @e -->

<!-- Delete Modal -->
<div class="modal fade custom-width" id="waiver-modal-delete" tabindex="-1" role="dialog" aria-labelledby="waiver-modal-delete-label" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="waiver-modal-delete-label">Are you sure? </h4>
            </div>
            <form method="POST" action="#" id="WaiverDelete">
                @csrf
                @method('DELETE')
                <input type="hidden" name="DeleteWaiverID" id="DeleteWaiverID" value="">
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-info">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade custom-width" id="waiver-modal" tabindex="-1" role="dialog" aria-labelledby="waiver-modal-label" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="waiver-modal-label">Add Waiver</h4>
            </div>
            <form method="post" action="{{ route('manage-waivers.store') }}" id="WaiverForm">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="field-1" class="control-label">Waiver Name</label>
                                <input name="WaiverName" type="text" class="form-control" id="field-1" placeholder="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <textarea name="WaiverHTML" class="form-control ckeditor" rows="10"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-info">Create</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $( document ).ready(function() {
        $("#btn-add-waiver").on("click", function() {
            jQuery('#waiver-modal').modal('show');
        });

        $("#btn-delete-waiver").on("click", function() {
            var val = $("#WaiverNameEdit").val();
            if(val && val !== ""){
                var id = $("#DeleteWaiverID").val();
                $("#WaiverDelete").attr('action', '/manage-waivers/' + id);
                jQuery('#waiver-modal-delete').modal('show');
            }
            else {
                alert("Please select Waiver that you want to delete!");
            }
        });

    	$("button.btn-info").click(function(){
    		if($(this).text()=="Create"){
    			var form = $( "#WaiverForm" );
                if (form.valid()) {
                    form.submit();
                }
    		}
    		else if($(this).text()=="Save Changes"){
    			var form = $( "#WaiverEditForm" );
                if (form.valid()) {
                    form.submit();
                }
    		}
    		else if($(this).text()=="Delete"){
    			var form = $("#WaiverDelete");
                if (form.valid()) {
                    form.submit();
                }
    		}
    	});

    	$("#WaiverNameEdit").change(function(){
    		var curentVal = $(this).val();
            if (!curentVal) {
                $("#DeleteWaiverID").val('');
                if (CKEDITOR.instances['WaiverHTML1']) {
                    CKEDITOR.instances['WaiverHTML1'].setData('');
                }
                return;
            }
    		var strArray = curentVal.split("!$!");
            var id = strArray[0];
    		$("#DeleteWaiverID").val(id);
            $("#WaiverEditForm").attr('action', '/manage-waivers/' + id);
    		
            var htmlContent = $("#WaiverHTML" + id).html();
            if (CKEDITOR.instances['WaiverHTML1']) {
                CKEDITOR.instances['WaiverHTML1'].setData(htmlContent);
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
{!! returnScriptWithNonce(asset('vendor/jsvalidation/js/jsvalidation.js')) !!}
{!! JsValidator::formRequest('App\Http\Requests\Backend\WaiverRequest', '#WaiverForm') !!}
{!! JsValidator::formRequest('App\Http\Requests\Backend\WaiverRequest', '#WaiverEditForm') !!}
{!! JsValidator::formRequest('App\Http\Requests\Backend\WaiverRequest', '#WaiverDelete') !!}
@endpush
