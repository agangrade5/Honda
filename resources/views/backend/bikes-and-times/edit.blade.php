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
        <li>
            <a href="javascript:;" id="btn-quick-times"><span class="hidden-xs">Quick Times</span></a>
        </li>
        <li>
            <a href="javascript:;" id="btn-add-model"><span class="hidden-xs">Add Model</span></a>
        </li>
    </ul>

    <div class="row" style="margin-bottom: 20px;">
        <form action="{{ route('manage-bikes-and-times.update', $btSet->btset_id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="col-md-4">
                <input type="text" name="BTSetName" class="form-control" value="{{ $btSet->btset_name }}" required> 
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-secondary btn-sm">Apply</button>
            </div>
        </form>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Edit {{ $btSet->btset_name }}</h3>
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
                        <th>Position</th>
                        <th>Qty</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="middle-align">
                    @foreach ($btModels as $btmodel)
                    <tr>
                        <td>{{ $btmodel->bt_modelid }}</td>
                        <td>{{ $btmodel->bt_modelname }}</td>
                        <td>{{ $btmodel->bt_position }}</td>
                        <td>{{ $btmodel->bt_qty }}</td>
                        <td>
                            <button type="button" class="btn btn-secondary btn-sm btn-edit-model" 
                                    data-id="{{ $btmodel->bt_modelid }}"
                                    data-name="{{ $btmodel->bt_modelname }}"
                                    data-qty="{{ $btmodel->bt_qty }}"
                                    data-position="{{ $btmodel->bt_position }}"
                                    data-times="{{ $btmodel->bt_times }}">
                                Edit
                            </button>
                            @if(!auth()->check() || auth()->user()?->userlevel == 1)
                            <button type="button" class="btn btn-danger btn-sm btn-delete-model" data-id="{{ $btmodel->bt_modelid }}">
                                Delete
                            </button>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                    <tr>
                        <td colspan="5" align="right">
                            <a href="{{ route('manage-bikes-and-times.index') }}" class="btn btn-info"> Back to Sets </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <!-- Main Footer -->
    @include('layouts.backend.footer')
</div>
<!-- content @e -->

<input type="hidden" name="currentPopupEvent" id="currentPopupEvent" value=""/>
<input type="hidden" id="CurrentModelID" value=""/>
<input type="hidden" id="CurrentIndex" value=""/>

<!-- Add Model Modal -->
<div class="modal fade custom-width" id="model-modal" tabindex="-1" role="dialog" aria-labelledby="model-modal-label" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Add Model</h4>
            </div>
            <form method="post" action="{{ route('manage-bikes-and-times.add-model', $btSet->btset_id) }}" id="ModelFormAdd">
                @csrf
                <div class="modal-body">
                    <div class="row" style="margin-bottom: 10px;">
                        <div class="col-md-4">
                            <label class="control-label">Model Name</label>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <input type="text" class="form-control" id="BTModelName" name="BTModelName" required />
                            </div>
                        </div>
                    </div>  
                    <div class="row" style="margin-bottom: 10px;">
                        <div class="col-md-4">
                            <label for="BTPosition" class="control-label">Position</label>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <input type="number" class="form-control" id="BTPosition" name="BTPosition" placeholder="" required>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="margin-bottom: 10px;">
                        <div class="col-md-4">
                            <label for="BTQty" class="control-label">Quantity</label>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <input type="number" class="form-control" id="BTQty" name="BTQty" placeholder="" required>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="margin-bottom: 10px;">
                        <div class="col-md-12">
                            <label class="control-label">Time Management</label>
                        </div>
                    </div>
                    <div class="row" style="margin-bottom: 10px;">
                        <div class="col-md-6">
                            <div class="row" style="margin-bottom: 10px;">
                                <div class="col-xs-3">Every</div>
                                <div class="col-xs-6">
                                    <input type="number" class="form-control" id="BTTimeintervalAdd" value="10" /> 
                                </div>
                                <div class="col-xs-3" style="padding-left:0%;">Min</div>
                            </div>
                            <div class="input-group bootstrap-timepicker timepicker" style="margin-bottom: 10px;">
                                <input id="starttimeadd" type="text" class="form-control input-small">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
                            </div>
                            <div class="input-group bootstrap-timepicker timepicker" style="margin-bottom: 10px;">
                                <input id="endtimeadd" type="text" class="form-control input-small">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
                            </div>
                            <span id="TimeErrorAdd" style="display:none;color:red;"> Please Fill Correct Time </span>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <button type="button" class="btn btn-white" id="add_time">Add Time Range</button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label for="multi-select-time" class="control-label">Generated Times</label><br>
                            <select class="form-control" multiple="multiple" id="multi-select-time" size="10">
                            </select>
                            <br>
                            <button type="button" class="btn btn-info btn-secondary btn-clear-select">Clear</button>
                        </div>
                    </div>
                    <input type="hidden" id="TimeAddValue" name="TimeAddValue"/>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-info btn-secondary btn-create-confirm">Create</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Model Modal -->
<div class="modal fade custom-width" id="btmodel-modal-edit" tabindex="-1" role="dialog" aria-labelledby="btmodel-modal-edit-label" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Edit Model</h4>
            </div>
            <form method="post" action="{{ route('manage-bikes-and-times.edit-model', $btSet->btset_id) }}" id="ModelFormEdit">
                @csrf
                <input type="hidden" id="BTModelIDEdit" name="BTModelID" value=""/>
                <div class="modal-body">
                    <div class="row" style="margin-bottom: 10px;">
                        <div class="col-md-4">
                            <label class="control-label">Model Name</label>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <input type="text" class="form-control" id="BTModelNameEdit" name="BTModelName" required />
                            </div>
                        </div>
                    </div>  
                    <div class="row" style="margin-bottom: 10px;">
                        <div class="col-md-4">
                            <label for="BTPositionEdit" class="control-label">Position</label>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <input type="number" class="form-control" id="BTPositionEdit" name="BTPosition" placeholder="" required>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="margin-bottom: 10px;">
                        <div class="col-md-4">
                            <label for="BTQtyEdit" class="control-label">Quantity</label>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <input type="number" class="form-control" id="BTQtyEdit" name="BTQty" placeholder="" required>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="margin-bottom: 10px;">
                        <div class="col-md-12">
                            <label class="control-label">Time Management</label>
                        </div>
                    </div>
                    <div class="row" style="margin-bottom: 10px;">
                        <div class="col-md-6">
                            <div class="row" style="margin-bottom: 10px;">
                                <div class="col-xs-3">Every</div>
                                <div class="col-xs-6">
                                    <input type="number" class="form-control" id="BTTimeinterval" value="10" /> 
                                </div>
                                <div class="col-xs-3" style="padding-left:0%;">Min</div>
                            </div>
                            <div class="input-group bootstrap-timepicker timepicker" style="margin-bottom: 10px;">
                                <input id="starttime" type="text" class="form-control input-small">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
                            </div>
                            <div class="input-group bootstrap-timepicker timepicker" style="margin-bottom: 10px;">
                                <input id="endtime" type="text" class="form-control input-small">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
                            </div>
                            <span id="TimeErrorEdit" style="display:none;color:red;"> Please Fill Correct Time </span>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <button type="button" class="btn btn-white" id="edit_add_time">Add Time Range</button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label for="multi-select-edit-time" class="control-label">Generated Times</label><br>
                            <select class="form-control" multiple="multiple" id="multi-select-edit-time" size="10">
                            </select>
                            <br>
                            <button type="button" class="btn btn-info btn-secondary btn-clear-select">Clear</button>
                        </div>
                    </div>
                    <input type="hidden" id="TimeEditValue" name="TimeEditValue"/>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-info btn-secondary btn-save-confirm">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Quick Times Modal -->
<div class="modal fade custom-width" id="model-quick-time-modal" tabindex="-1" role="dialog" aria-labelledby="model-quick-time-modal-label" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Add Quick Times</h4>
            </div>
            <form method="post" action="{{ route('manage-bikes-and-times.apply-to-all', $btSet->btset_id) }}" id="ModelFormQuickAdd">
                @csrf
                <div class="modal-body">
                    <div class="row" style="margin-bottom: 10px;">
                        <div class="col-md-12">
                            <label class="control-label">Choose Model</label>
                            <div class="main-time-model" style="max-height: 200px; overflow-y: auto; border: 1px solid #ddd; padding: 10px; margin-bottom: 10px;">
                                @foreach ($btModels as $btmodel)
                                <div class="sub-time-model">
                                    <label>
                                        <input type="checkbox" name="TimeAppliedModelName[]" value="{{ $btmodel->bt_modelid }}">
                                        {{ $btmodel->bt_modelname }}
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="row" style="margin-bottom: 10px;">
                        <div class="col-md-12">
                            <label class="control-label">Time Management</label>
                        </div>
                    </div>
                    <div class="row" style="margin-bottom: 10px;">
                        <div class="col-md-6">
                            <div class="row" style="margin-bottom: 10px;">
                                <div class="col-xs-3">Every</div>
                                <div class="col-xs-6">
                                    <input type="number" class="form-control" id="BTQuickTimeintervalAdd" value="10" /> 
                                </div>
                                <div class="col-xs-3" style="padding-left:0%;">Min</div>
                            </div>
                            <div class="input-group bootstrap-timepicker timepicker" style="margin-bottom: 10px;">
                                <input id="startquicktimeadd" type="text" class="form-control input-small">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
                            </div>
                            <div class="input-group bootstrap-timepicker timepicker" style="margin-bottom: 10px;">
                                <input id="endquicktimeadd" type="text" class="form-control input-small">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
                            </div>
                            <span id="QuickTimeErrorAdd" style="display:none;color:red;"> Please Fill Correct Time </span>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <button type="button" class="btn btn-white" id="add_quick_time">Add Time Range</button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label for="multi-select-quick-time" class="control-label">Generated Times</label><br>
                            <select class="form-control" multiple="multiple" id="multi-select-quick-time" size="10">
                            </select>
                            <br>
                            <button type="button" class="btn btn-info btn-secondary btn-clear-select">Clear</button>
                        </div>
                    </div>
                    <input type="hidden" id="QuickTimeAddValue" name="QuickTimeAddValue"/>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-info btn-secondary btn-submit-quick-confirm">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Clear Confirm Modal -->
<div class="modal fade custom-width" id="btmodel-modal-clear" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Are you sure you want to clear the times? </h4>
            </div>                 
            <div class="modal-footer">
                <button type="button" class="btn btn-white" id="hideM">Close</button>
                <button type="button" class="btn btn-info btn-clear-confirm">Clear</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Model Modal -->
<div class="modal fade custom-width" id="btmodel-modal-delete" tabindex="-1" role="dialog" aria-labelledby="btmodel-modal-delete-label" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Are you sure? </h4>
            </div>
            <form method="post" action="{{ route('manage-bikes-and-times.delete-model', $btSet->btset_id) }}" id="BTModelDelete">  
                @csrf
                <input type="hidden" name="DeleteBTModelID" id="DeleteBTModelID" value="">
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-info">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Time Helper Modal -->
<div class="modal fade custom-width" id="tmgmt-modal-add-delete" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Edit Time Management</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4">
                        <label class="control-label">Time</label>
                    </div>
                    <div class="col-md-8">
                        <input type="text" class="form-control" id="tmgmtNameAdd1" placeholder="">
                        <input type="hidden" id="OLDtmgmtNameAdd">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" id="addTimeManagementClose">Close</button>
                <button type="button" class="btn btn-secondary btn-info" id="savetotmgmtadd">Save</button>
                <button type="button" class="btn btn-danger btn-info" id="deletetotmgmtadd">Delete</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Time Helper Modal -->
<div class="modal fade custom-width" id="tmgmt-modal-edit-delete" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Edit Time Management</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4">
                        <label class="control-label">Time</label>
                    </div>
                    <div class="col-md-8">
                        <input type="text" class="form-control" id="tmgmtNameEdit1" placeholder="">
                        <input type="hidden" id="OLDtmgmtNameEdit">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" id="editTimeManagementClose">Close</button>
                <button type="button" class="btn btn-secondary btn-info" id="savetotmgmt">Save</button>
                <button type="button" class="btn btn-danger btn-info" id="deletetotmgmt">Delete</button>
            </div>
        </div>
    </div>
</div>

<!-- Quick Time Helper Modal -->
<div class="modal fade custom-width" id="quick-modal-add-delete" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Edit Time Management</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-4">
                        <label class="control-label">Time</label>
                    </div>
                    <div class="col-md-8">
                        <input type="text" class="form-control" id="QuickNameAdd1" placeholder="">
                        <input type="hidden" id="OLDQuickNameAdd">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" id="addQuickTimeManagementClose">Close</button>
                <button type="button" class="btn btn-secondary btn-info" id="quicksavetotmgmtadd">Save</button>
                <button type="button" class="btn btn-danger btn-info" id="quickdeletetotmgmtadd">Delete</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/time/bootstrap-timepicker.min.css') }}">
@vite(['resources/css/backend/bikes-and-times/edit.css'])
@endpush

@push('scripts')
{!! returnScriptWithNonce(asset('assets/js/time/bootstrap-timepicker.min.js')) !!}
@vite(['resources/js/backend/bikes-and-times/edit.js'])
@endpush
