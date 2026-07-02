@extends('layouts.backend.app')
@section('title', $title)
@section('content')
<!-- content @s -->
<div class="main-content">
    <!-- Content Header section -->
    @include('layouts.backend.content_header', compact('title'))

    @if(session('msg'))
    <div class="dx-warning">
        <div>
            <p>{{ session('msg') }}</p>
        </div>
    </div>
    @endif

    <ul class="nav nav-tabs right-aligned">
        <!-- available classes "right-aligned" -->
        <li><a href="javascript:;" onclick="jQuery('#region-modal').modal('show');">
            <span class="hidden-xs">Add Model</span>
            </a>
        </li>
    </ul>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Models</h3>
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
                        <th>Model Name</th>
                        <th>Group Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="middle-align">
                    @foreach ($models->Models as $model)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $model->ModelName }}</td>
                        <td>{{ $model->GroupName }}</td>
                        <td>
                            <a href="javascript:;" 
                               data-id="{{ $model->ModelID }}"
                               data-name="{{ $model->ModelName }}"
                               data-group-id="{{ $model->GroupID }}"
                               onclick="jQuery('#region-modal-edit').modal('show');" 
                               class="btn btn-secondary btn-sm btn-icon icon-left">
                               Edit
                            </a>
                            @if(!auth()->check() || auth()->user()?->userlevel == 1)
                            <a href="javascript:;" 
                               data-id="{{ $model->ModelID }}" 
                               onclick="jQuery('#model-modal-delete').modal('show');" 
                               class="btn btn-danger btn-icon">
                               <i class="icon-white icon-heart"></i> Delete
                            </a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <!-- Main Footer -->
    @include('layouts.backend.footer')
</div>
<!-- content @e -->

<!-- Delete Modal -->
<div class="modal fade custom-width" id="model-modal-delete" tabindex="-1" role="dialog" aria-labelledby="model-modal-delete-label" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="model-modal-delete-label">Are you sure? </h4>
            </div>
            <form method="post" action="#" id="ModelDelete">
                @csrf
                @method('DELETE')
                <input type="hidden" name="DeleteModelID" id="DeleteModelID" value="">
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-info">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade custom-width" id="region-modal" tabindex="-1" role="dialog" aria-labelledby="region-modal-label" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="region-modal-label">Add Model</h4>
            </div>
            <div class="modal-body">
                <form id="ModelForm" method="post" action="{{ route('manage-models.store') }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="field-1" class="control-label">Model Name</label>
                                <input type="text" class="form-control" id="field-1" name="ModelName" placeholder="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="VehicleModel" class="control-label">Group</label>
                                <select class="form-control" id="VehicleModel" name="GroupID">
                                    <option value="0">None</option>
                                    @foreach ($groups->Groups as $group)
                                    <option value="{{ $group->GroupID }}">{{ $group->GroupName }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-info">Create</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade custom-width" id="region-modal-edit" tabindex="-1" role="dialog" aria-labelledby="region-modal-edit-label" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="region-modal-edit-label">Edit Model</h4>
            </div>
            <div class="modal-body">
                <form id="ModelEditForm" method="post" action="#">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="ModelNameEdit" class="control-label">Model Name</label>
                                <input type="text" class="form-control" id="ModelNameEdit" name="ModelName" placeholder="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="GroupIDEdit" class="control-label">Group</label>
                                <select class="form-control" id="GroupIDEdit" name="GroupID">
                                    <option value="0">None</option>
                                    @foreach ($groups->Groups as $group)
                                    <option value="{{ $group->GroupID }}">{{ $group->GroupName }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" id="ModelID" name="ModelID" value="">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-info">Save Changes</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $( document ).ready(function() {
    	$("button.btn-info").click(function(){
    		if($(this).text()=="Create"){
    			var form = $( "#ModelForm" );
                if (form.valid()) {
                    form.submit();
                }
    		}
    		else if($(this).text()=="Save Changes"){
    			var form = $( "#ModelEditForm" );
                if (form.valid()) {
                    form.submit();
                }
    		}
    		else if($(this).text()=="Delete"){
    			var form = $("#ModelDelete");
                if (form.valid()) {
                    form.submit();
                }
    		}
    	});

    	$("a.btn-danger").click(function(){
            var id = $(this).data('id');
    		$("#DeleteModelID").val(id);
            $("#ModelDelete").attr('action', '/manage-models/' + id);
    	});

    	$("a.btn-secondary").click(function(){
    		var btn = $(this);
            var ModelID = btn.data('id');
    		$("#ModelNameEdit").val(btn.data('name'));
    		$("#ModelID").val(ModelID);
    		$("#GroupIDEdit").val(btn.data('group-id') || 0);
            $("#ModelEditForm").attr('action', '/manage-models/' + ModelID);
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
{!! JsValidator::formRequest('App\Http\Requests\Backend\ModelsRequest', '#ModelForm') !!}
{!! JsValidator::formRequest('App\Http\Requests\Backend\ModelsRequest', '#ModelEditForm') !!}
{!! JsValidator::formRequest('App\Http\Requests\Backend\ModelsRequest', '#ModelDelete') !!}
@endpush
