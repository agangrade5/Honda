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
            <a href="javascript:;" onclick="jQuery('#restrictedriders-modal').modal('show');"><span class="hidden-xs">Add Rider</span></a>
        </li>
    </ul>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Restricted Riders</h3>
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
                        <th>Card/DL</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Reason</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="middle-align">
                    @foreach ($restrictedriders->RestrictedRiders as $restrictedrider)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $restrictedrider->restrictlic }}</td>
                        <td>{{ $restrictedrider->RiderFirstName }}</td>
                        <td>{{ $restrictedrider->RiderLastName }}</td>
                        <td>{{ $restrictedrider->restrictcomment }}</td>
                        <td>
                            <a href="javascript:;" 
                               data-id="{{ $restrictedrider->restrictid }}"
                               data-license="{{ $restrictedrider->restrictlic }}"
                               data-comment="{{ $restrictedrider->restrictcomment }}"
                               onclick="jQuery('#restrictedriders-modal-edit').modal('show');" 
                               class="btn btn-secondary btn-sm btn-icon icon-left">
                               Edit
                            </a>
                            @if(!auth()->check() || in_array(auth()->user()?->userlevel, [1, 2]))
                            <a href="javascript:;" 
                               data-id="{{ $restrictedrider->restrictid }}" 
                               onclick="jQuery('#restrictedriders-modal-delete').modal('show');" 
                               class="btn btn-danger btn-icon btn-sm">
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
<div class="modal fade custom-width" id="restrictedriders-modal-delete" tabindex="-1" role="dialog" aria-labelledby="restrictedriders-modal-delete-label" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="restrictedriders-modal-delete-label">Are you sure? </h4>
            </div>
            <form method="post" action="#" id="RestrictedRidersDelete">
                @csrf
                @method('DELETE')
                <input type="hidden" name="RestrictID" id="DeleteRestrictID" value="">
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-info">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade custom-width" id="restrictedriders-modal" tabindex="-1" role="dialog" aria-labelledby="restrictedriders-modal-label" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="restrictedriders-modal-label">Add Restricted Riders</h4>
            </div>
            <form method="post" action="{{ route('manage-restricted-riders.store') }}" id="RestrictedRidersForm">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-3">
                            <label class="control-label" for="RestrictLic">Restrict Lic</label>
                        </div>
                        <div class="col-md-9">
                            <div class="form-group">
                                <input type="text" class="form-control" id="RestrictLic" name="RestrictLic" placeholder="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <label class="control-label" for="RestrictComment">Restrict Comment</label>
                        </div>
                        <div class="col-md-9">
                            <div class="form-group">
                                <textarea class="form-control" rows="5" id="RestrictComment" name="RestrictComment"></textarea>
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

<!-- Edit Modal -->
<div class="modal fade custom-width" id="restrictedriders-modal-edit" tabindex="-1" role="dialog" aria-labelledby="restrictedriders-modal-edit-label" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="restrictedriders-modal-edit-label">Edit Restricted Riders</h4>
            </div>
            <div class="modal-body">
                <form method="post" action="#" id="RestrictRiderFormEdit">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-3">
                            <label class="control-label" for="RestrictLicEdit">Restrict Lic</label>
                        </div>
                        <div class="col-md-9">
                            <div class="form-group">
                                <input type="text" class="form-control" id="RestrictLicEdit" name="RestrictLic" placeholder="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <label class="control-label" for="RestrictCommentEdit">Restrict Comment</label>
                        </div>
                        <div class="col-md-9">
                            <div class="form-group">
                                <textarea class="form-control" rows="5" id="RestrictCommentEdit" name="RestrictComment"></textarea>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" id="RestrictID" name="RestrictID" value="">
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
    			var form = $( "#RestrictedRidersForm" );
                if (form.valid()) {
                    form.submit();
                }
    		}
    		else if($(this).text()=="Save Changes"){
    			var form = $( "#RestrictRiderFormEdit" );
                if (form.valid()) {
                    form.submit();
                }
    		}
    		else if($(this).text()=="Delete"){
    			var form = $("#RestrictedRidersDelete");
                if (form.valid()) {
                    form.submit();
                }
    		}
    	});

    	$("a.btn-danger").click(function(){
            var id = $(this).data('id');
    		$("#DeleteRestrictID").val(id);
            $("#RestrictedRidersDelete").attr('action', '/manage-restricted-riders/' + id);
    	});

    	$("a.btn-secondary").click(function(){
    		var btn = $(this);
            var RRID = btn.data('id');
    		$("#RestrictLicEdit").val(btn.data('license'));
    		$("#RestrictCommentEdit").val(btn.data('comment'));
    		$("#RestrictID").val(RRID);
            $("#RestrictRiderFormEdit").attr('action', '/manage-restricted-riders/' + RRID);
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
{!! JsValidator::formRequest('App\Http\Requests\Backend\RestrictedRiderRequest', '#RestrictedRidersForm') !!}
{!! JsValidator::formRequest('App\Http\Requests\Backend\RestrictedRiderRequest', '#RestrictRiderFormEdit') !!}
{!! JsValidator::formRequest('App\Http\Requests\Backend\RestrictedRiderRequest', '#RestrictedRidersDelete') !!}
@endpush
