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
            <a href="javascript:;" onclick="jQuery('#truck-modal').modal('show');"><span class="hidden-xs">Add Social Media</span></a>
        </li>
    </ul>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Social Media</h3>
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
                        <th>Facebook</th>
                        <th>Twitter</th>
                        <th>Instagram</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="middle-align">
                    @foreach ($socialmedias as $socialmedia)
                    @php
                        $blob = @unserialize($socialmedia->socialblob) ?: [];
                        $facebook = $blob['Facebook'] ?? '';
                        $twitter = $blob['Twitter'] ?? '';
                        $instagram = $blob['Instagram'] ?? '';
                    @endphp
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $socialmedia->socialname }}</td>
                        <td>{{ $facebook }}</td>
                        <td>{{ $twitter }}</td>
                        <td>{{ $instagram }}</td>
                        <td>
                            <a href="javascript:;" 
                               data-id="{{ $socialmedia->socialid }}"
                               data-name="{{ $socialmedia->socialname }}"
                               data-facebook="{{ $facebook }}"
                               data-twitter="{{ $twitter }}"
                               data-instagram="{{ $instagram }}"
                               onclick="jQuery('#truck-modal-edit').modal('show');" 
                               class="btn btn-secondary btn-sm btn-icon icon-left">
                               Edit
                            </a>
                            @if(!auth()->check() || auth()->user()?->userlevel == 1)
                            <a href="javascript:;" 
                               data-id="{{ $socialmedia->socialid }}" 
                               onclick="jQuery('#socialmedia-modal-delete').modal('show');" 
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
<div class="modal fade custom-width" id="socialmedia-modal-delete" tabindex="-1" role="dialog" aria-labelledby="delete-modal-label" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="delete-modal-label">Are you sure? </h4>
            </div>
            <form method="post" action="#" id="SocialMediaDelete">
                @csrf
                @method('DELETE')
                <input type="hidden" name="DeleteSocialMediaID" id="DeleteSocialMediaID" value="">
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-info">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade custom-width" id="truck-modal" tabindex="-1" role="dialog" aria-labelledby="create-modal-label" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="create-modal-label">Add Social Media</h4>
            </div>
            <form method="post" action="{{ route('manage-social-media.store') }}" id="SocialMediaForm">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-2">
                            <label class="control-label" for="SocialName">Saved SM Presets</label>
                        </div>
                        <div class="col-md-9">
                            <div class="form-group">
                                <input type="text" class="form-control" id="SocialName" name="SocialName" placeholder="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="input-group">
                                <span class="input-group-addon">Facebook</span>
                                <input type="text" name="Facebook" class="form-control" placeholder="Facebook URL">
                            </div>
                        </div>
                        <br>
                        <br>
                        <div class="col-md-12">
                            <div class="input-group">
                                <span class="input-group-addon">Twitter</span>
                                <input type="text" name="Twitter" class="form-control" placeholder="Twitter URL">
                            </div>
                        </div>
                        <br>
                        <br>
                        <div class="col-md-12">
                            <div class="input-group">
                                <span class="input-group-addon">Instagram</span>
                                <input type="text" name="Instagram" class="form-control" placeholder="Instagram URL">
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
<div class="modal fade custom-width" id="truck-modal-edit" tabindex="-1" role="dialog" aria-labelledby="edit-modal-label" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="edit-modal-label">Edit Social Media</h4>
            </div>
            <div class="modal-body">
                <form method="post" action="#" id="SocialMediaFormEdit">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-2">
                            <label class="control-label" for="SocialNameEdit">Saved SM Presets</label>
                        </div>
                        <div class="col-md-9">
                            <div class="form-group">
                                <select name="SocialName" class="form-control selectboxit" id="SocialNameEdit">
                                    <optgroup label="Saved Social Media Settings">
                                        @foreach ($socialmedias as $item)
                                            <option value="{{ $item->socialid }}">{{ $item->socialname }}</option>
                                        @endforeach
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="input-group">
                                <span class="input-group-addon">Facebook</span>
                                <input type="text" name="Facebook" id="FacebookEdit" class="form-control" placeholder="Facebook URL">
                            </div>
                        </div>
                        <br>
                        <br>
                        <div class="col-md-12">
                            <div class="input-group">
                                <span class="input-group-addon">Twitter</span>
                                <input type="text" name="Twitter" id="TwitterEdit" class="form-control" placeholder="Twitter URL">
                            </div>
                        </div>
                        <br>
                        <br>
                        <div class="col-md-12">
                            <div class="input-group">
                                <span class="input-group-addon">Instagram</span>
                                <input type="text" name="Instagram" id="InstagramEdit" class="form-control" placeholder="Instagram URL">
                            </div>
                        </div>
                    </div>
                    <input type="hidden" id="SocialIDID" name="SocialID" value="">
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
    			var form = $( "#SocialMediaForm" );
                if (form.valid()) {
                    form.submit();
                }
    		}
    		else if($(this).text()=="Save Changes"){
    			var form = $( "#SocialMediaFormEdit" );
                if (form.valid()) {
                    form.submit();
                }
    		}
    		else if($(this).text()=="Delete"){
    			var form = $("#SocialMediaDelete");
                if (form.valid()) {
                    form.submit();
                }
    		}
    	});

    	$("a.btn-danger").click(function(){
            var id = $(this).data('id');
    		$("#DeleteSocialMediaID").val(id);
            $("#SocialMediaDelete").attr('action', '/manage-social-media/' + id);
    	});

    	$("a.btn-secondary").click(function(){
    		var btn = $(this);
            var SMID = btn.data('id');
    		$("#InstagramEdit").val(btn.data('instagram'));
    		$("#TwitterEdit").val(btn.data('twitter'));
    		$("#FacebookEdit").val(btn.data('facebook'));
            
            if ($("#SocialNameEdit").data("selectBox-selectBoxIt")) {
    		    $("#SocialNameEdit").data("selectBox-selectBoxIt").selectOption(String(SMID));
            } else {
                $("#SocialNameEdit").val(SMID);
            }
    		$("#SocialIDID").val(SMID);
            $("#SocialMediaFormEdit").attr('action', '/manage-social-media/' + SMID);
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
{!! JsValidator::formRequest('App\Http\Requests\Backend\SocialMediaRequest', '#SocialMediaForm') !!}
{!! JsValidator::formRequest('App\Http\Requests\Backend\SocialMediaRequest', '#SocialMediaFormEdit') !!}
{!! JsValidator::formRequest('App\Http\Requests\Backend\SocialMediaRequest', '#SocialMediaDelete') !!}
@endpush
