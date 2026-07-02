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
            <span class="hidden-xs">Add Groups</span>
            </a>
        </li>
    </ul>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Groups</h3>
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
                        <th>Group Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="middle-align">
                    @foreach ($groups->Groups as $group)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $group->groupname }}</td>
                        <td>
                            <a href="javascript:;" 
                               data-id="{{ $group->groupid }}"
                               data-name="{{ $group->groupname }}"
                               onclick="jQuery('#region-modal-edit').modal('show');" 
                               class="btn btn-secondary btn-sm btn-icon icon-left">
                               Edit
                            </a>
                            @if(!auth()->check() || auth()->user()?->userlevel == 1)
                            <a href="javascript:;" 
                               data-id="{{ $group->groupid }}" 
                               onclick="jQuery('#group-modal-delete').modal('show');" 
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

<!-- Group Delete Modal -->
<div class="modal fade custom-width" id="group-modal-delete" tabindex="-1" role="dialog" aria-labelledby="group-modal-delete-label" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="group-modal-delete-label">Are you sure? </h4>
            </div>
            <form method="post" action="#" id="GroupDelete">
                @csrf
                @method('DELETE')
                <input type="hidden" name="GroupID" id="DeleteGroupID" value="">
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-info">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Region Create Modal -->
<div class="modal fade custom-width" id="region-modal" tabindex="-1" role="dialog" aria-labelledby="region-modal-label" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="region-modal-label">Add Group</h4>
            </div>
            <div class="modal-body">
                <form id="Group" method="post" action="{{ route('manage-groups.store') }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="field-1" class="control-label">Group Name</label>
                                <input type="text" class="form-control" id="field-1" name="GroupName" placeholder="">
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

<!-- Region Edit Modal -->
<div class="modal fade custom-width" id="region-modal-edit" tabindex="-1" role="dialog" aria-labelledby="region-modal-edit-label" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="region-modal-edit-label">Edit Group</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <form id="GroupEdit" method="post" action="#">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="GroupNameEdit" class="control-label">Group Name</label>
                                <input type="hidden" id="GroupID" name="GroupID" value="">
                                <input type="text" class="form-control" id="GroupNameEdit" name="GroupName" placeholder="">
                            </div>
                        </form>
                    </div>
                </div>
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
    			var form = $( "#Group" );
                if (form.valid()) {
                    form.submit();
                }
    		}
    		else if($(this).text()=="Save Changes"){
    			var form = $( "#GroupEdit" );
                if (form.valid()) {
                    form.submit();
                }
    		}
    		else if($(this).text()=="Delete"){
    			var form = $("#GroupDelete");
                if (form.valid()) {
                    form.submit();
                }
    		}
    	});

    	$("a.btn-danger").click(function(){
            var id = $(this).data('id');
    		$("#DeleteGroupID").val(id);
            $("#GroupDelete").attr('action', '/manage-groups/' + id);
    	});

    	$("a.btn-secondary").click(function(){
    		var btn = $(this);
            var GroupID = btn.data('id');
    		$("#GroupNameEdit").val(btn.data('name'));
    		$("#GroupID").val(GroupID);
            $("#GroupEdit").attr('action', '/manage-groups/' + GroupID);
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
{!! JsValidator::formRequest('App\Http\Requests\Backend\GroupRequest', '#Group') !!}
{!! JsValidator::formRequest('App\Http\Requests\Backend\GroupRequest', '#GroupEdit') !!}
{!! JsValidator::formRequest('App\Http\Requests\Backend\GroupRequest', '#GroupDelete') !!}
@endpush
