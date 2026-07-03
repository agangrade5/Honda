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
        <li><a href="javascript:;" onclick="jQuery('#user-modal').modal('show');">
            <span class="hidden-xs">Add User</span>
            </a>
        </li>
    </ul>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Users</h3>
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
                        <th>Full Name</th>
                        <th>Username</th>
                        <th>Phone #</th>
                        <th>Level</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="middle-align">
                    @foreach ($users->Users as $user)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $user->UserFullName }}</td>
                        <td>{{ $user->UserName }}</td>
                        <td>{{ $user->UserPhone }}</td>
                        <td>{{ $user->UserTypeTitle }}</td>
                        <td>
                            <a href="javascript:;" 
                               data-id="{{ $user->UserID }}"
                               data-first-name="{{ $user->firstname }}"
                               data-last-name="{{ $user->lastname }}"
                               data-username="{{ $user->UserName }}"
                               data-phone="{{ $user->UserPhone }}"
                               data-level="{{ $user->UserLevel }}"
                               data-pass="{{ $user->UserPass }}"
                               data-regions="{{ json_encode($user->AllowRegion) }}"
                               data-events="{{ json_encode($user->AllowEvents) }}"
                               data-countries="{{ json_encode($user->AllowCountry) }}"
                               onclick="jQuery('#user-modal-edit').modal('show');" 
                               class="btn btn-secondary btn-sm btn-icon icon-left">
                               Edit
                            </a>
                            @if(!auth()->check() || auth()->user()?->userlevel == 1)
                            <a href="javascript:;" 
                               data-id="{{ $user->UserID }}" 
                               onclick="jQuery('#user-modal-delete').modal('show');" 
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

<!-- User Delete Modal -->
<div class="modal fade custom-width" id="user-modal-delete" tabindex="-1" role="dialog" aria-labelledby="user-modal-delete-label" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="user-modal-delete-label">Are you sure? </h4>
            </div>
            <form method="post" action="#" id="UserDelete">
                @csrf
                @method('DELETE')
                <input type="hidden" name="DeleteUserID" id="DeleteUserID" value="">
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-info">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- User Create Modal -->
<div class="modal fade custom-width" id="user-modal" tabindex="-1" role="dialog" aria-labelledby="user-modal-label" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="user-modal-label">Add User</h4>
            </div>
            <form method="post" action="{{ route('manage-users.store') }}" id="User">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="firstName" class="control-label">First Name</label>
                                <input type="text" class="form-control" id="firstName" name="FirstName" placeholder="">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="lastName" class="control-label">Last Name</label>
                                <input type="text" class="form-control" id="lastName" name="LastName" placeholder="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="username" class="control-label">Username</label>
                                <input type="text" class="form-control" id="username" name="UserName" placeholder="">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="sboxit-1" class="control-label">Permission Level</label>
                                <select class="form-control" id="sboxit-1" name="UserLevel">
                                    <option value="0">Select</option>
                                    @foreach ($usertypes->UserTypes as $usertype)
                                    <option value="{{ $usertype->UserTypeId }}">{{ $usertype->UserTypeTitle }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="phone" class="control-label">Phone Number</label>
                                <input type="text" class="form-control" id="phone" name="UserPhone" placeholder="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="UserPassword" class="control-label">User Password</label>
                                <input type="text" class="form-control" id="UserPassword" name="UserPass" placeholder="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="region" class="control-label">Region</label>
                                <select class="form-control" id="region" multiple name="Region[]">
                                    <option></option>
                                    <optgroup label="Region">
                                        @foreach ($regions->Regions as $region)
                                        <option value="{{ $region->RegionID }}">{{ $region->RegionName }}</option>
                                        @endforeach
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="events" class="control-label">Events</label>
                                <select class="form-control" id="events" multiple name="Events[]">
                                    <option></option>
                                    <optgroup label="Events">
                                        @foreach ($events->Events as $regionGroup)
                                            @foreach ($regionGroup as $event)
                                            <option value="{{ $event->EventID }}">{{ $event->EventName }}</option>
                                            @endforeach
                                        @endforeach
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="country" class="control-label">Country</label>
                                <select class="form-control" id="country" multiple name="Country[]">
                                    <option></option>
                                    <optgroup label="Country">
                                        @foreach ($countries->Country as $Countrys)
                                        <option value="{{ $Countrys->CountryID }}">{{ $Countrys->CountryName }}</option>
                                        @endforeach
                                    </optgroup>
                                </select>
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

<!-- User Edit Modal -->
<div class="modal fade custom-width" id="user-modal-edit" tabindex="-1" role="dialog" aria-labelledby="user-modal-edit-label" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="user-modal-edit-label">Edit User</h4>
            </div>
            <form method="post" action="#" id="UserEdit">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="FirstNameEdit" class="control-label">First Name</label>
                                <input type="text" class="form-control" id="FirstNameEdit" name="FirstName" placeholder="">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="LastNameEdit" class="control-label">Last Name</label>
                                <input type="text" class="form-control" id="LastNameEdit" name="LastName" placeholder="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="UserNameEdit" class="control-label">Username</label>
                                <input type="text" class="form-control" id="UserNameEdit" name="UserName" placeholder="">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="UserLevelEdit" class="control-label">Permission Level</label>
                                <select class="form-control" id="UserLevelEdit" name="UserLevel">
                                    <option value="0">Select</option>
                                    @foreach ($usertypes->UserTypes as $usertype)
                                    <option value="{{ $usertype->UserTypeId }}">{{ $usertype->UserTypeTitle }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="UserPhoneEdit" class="control-label">Phone Number</label>
                                <input type="text" class="form-control" id="UserPhoneEdit" name="UserPhone" placeholder="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="UserPasswordEdit" class="control-label">User Password</label>
                                <input type="text" class="form-control" id="UserPasswordEdit" name="UserPass" placeholder="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="region1" class="control-label">Region</label>
                                <select class="form-control" id="region1" multiple name="Region[]">
                                    <option></option>
                                    <optgroup label="Region">
                                        @foreach ($regions->Regions as $region)
                                        <option value="{{ $region->RegionID }}">{{ $region->RegionName }}</option>
                                        @endforeach
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="events1" class="control-label">Events</label>
                                <select class="form-control" id="events1" multiple name="Events[]">
                                    <option></option>
                                    <optgroup label="Events">
                                        @foreach ($events->Events as $regionGroup)
                                            @foreach ($regionGroup as $event)
                                            <option value="{{ $event->EventID }}">{{ $event->EventName }}</option>
                                            @endforeach
                                        @endforeach
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="country1" class="control-label">Country</label>
                                <select class="form-control" id="country1" multiple name="Country[]">
                                    <option></option>
                                    <optgroup label="Country">
                                        @foreach ($countries->Country as $Countrys)
                                        <option value="{{ $Countrys->CountryID }}">{{ $Countrys->CountryName }}</option>
                                        @endforeach
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" id="UserID" name="UserID" value="">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-info">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $( document ).ready(function() {
        // Setup select2 plugins for modals
        $("#region").select2({
            placeholder: 'Choose the region.',
            allowClear: true
        });
        $("#events").select2({
            placeholder: 'Choose the events.',
            allowClear: true
        });
        $("#country").select2({
            placeholder: 'Choose the countrys.',
            allowClear: true
        });

        $("#region1").select2({
            placeholder: 'Choose the region.',
            allowClear: true
        });
        $("#events1").select2({
            placeholder: 'Choose the events.',
            allowClear: true
        });
        $("#country1").select2({
            placeholder: 'Choose the countrys.',
            allowClear: true
        });

    	$("button.btn-info").click(function(){
    		if($(this).text()=="Create"){
    			var form = $( "#User" );
                if (form.valid()) {
                    form.submit();
                }
    		}
    		else if($(this).text()=="Save Changes"){
    			var form = $( "#UserEdit" );
                if (form.valid()) {
                    form.submit();
                }
    		}
    		else if($(this).text()=="Delete"){
    			var form = $("#UserDelete");
                if (form.valid()) {
                    form.submit();
                }
    		}
    	});

    	$("a.btn-danger").click(function(){
            var id = $(this).data('id');
    		$("#DeleteUserID").val(id);
            $("#UserDelete").attr('action', '/manage-users/' + id);
    	});

    	$("a.btn-secondary").click(function(){
    		var btn = $(this);
    		var UserId = btn.data('id');
    		$("#FirstNameEdit").val(btn.data('first-name'));
    		$("#LastNameEdit").val(btn.data('last-name'));
    		$("#UserNameEdit").val(btn.data('username'));
    		$("#UserPhoneEdit").val(btn.data('phone'));
    		$("#UserID").val(UserId);
    		$("#UserLevelEdit").val(btn.data('level') || 0);
    		$("#UserPasswordEdit").val(btn.data('pass'));

    		// Manage User Events select2 value binding
    		var UserEventsArray = [];
    		$.each(btn.data('events') || [], function(index, EDV){
    			UserEventsArray.push({id:EDV, text:$('#events1 option[value="'+EDV+'"]').text()});
    		});
    		$("#events1").select2('data', UserEventsArray);

    		// Manage User Country select2 value binding
    		var UserCountryArray = [];
    		$.each(btn.data('countries') || [], function(index, EDV){
    			UserCountryArray.push({id:EDV, text:$('#country1 option[value="'+EDV+'"]').text()});
    		});
    		$("#country1").select2('data', UserCountryArray);

    		// Manage User Region select2 value binding
    		var UserRegionArray = [];
    		$.each(btn.data('regions') || [], function(index, EDV){
    			UserRegionArray.push({id:EDV, text:$('#region1 option[value="'+EDV+'"]').text()});
    		});
    		$("#region1").select2('data', UserRegionArray);

            $("#UserEdit").attr('action', '/manage-users/' + UserId);
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
                    $(this).find('select').val(0).trigger('change');
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
{!! JsValidator::formRequest('App\Http\Requests\Backend\UserRequest', '#User') !!}
{!! JsValidator::formRequest('App\Http\Requests\Backend\UserRequest', '#UserEdit') !!}
{!! JsValidator::formRequest('App\Http\Requests\Backend\UserRequest', '#UserDelete') !!}
@endpush
