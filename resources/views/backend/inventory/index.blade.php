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

    <div class="row">
        <div class="col-md-2">
            <div class="form-group">
                <form method="GET" action="{{ route('manage-inventory.index') }}" id="ArchivedFilterForm">
                    <input type="hidden" name="archive" value="{{ $selectedArchive ? 0 : 1 }}">
                    <button type="submit" class="btn btn-info">{{ $selectedArchive ? 'Show Active vehicles' : 'Show Archived vehicles' }}</button>
                </form>
            </div>
        </div>
    </div>

    <ul class="nav nav-tabs right-aligned">
        <!-- available classes "right-aligned" -->
        <li><a href="javascript:;" onclick="jQuery('#vehicle-modal').modal('show');">
            <span class="hidden-xs">Add Vehicle</span>
            </a>
        </li>
    </ul>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Vehicles</h3>
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
                        <th>Nickname</th>
                        <th>Group</th>
                        <th>Color</th>
                        <th>Truck</th>
                        <th>Plate #</th>
                        <th>Type</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="middle-align">
                    @foreach ($vehicles as $inventorie)
                    <tr>
                        <td>{{ $inventorie->VehicleCOV }}</td>
                        <td>{{ $inventorie->VehicleNickName }}</td>
                        <td>{{ $inventorie->VehicleGroup }}</td>
                        <td>{{ $inventorie->VehicleColor }}</td>
                        <td>{{ $inventorie->TruckName }}</td>
                        <td>{{ $inventorie->VehicleLicPlate }}</td>
                        <td>{{ ucfirst($inventorie->VehicleType) }}</td>
                        <td>
                            <a href="javascript:;"
                               data-id="{{ $inventorie->VehicleID }}"
                               data-nickname="{{ $inventorie->VehicleNickName }}"
                               data-group="{{ $inventorie->VehicleGroupID }}"
                               data-color="{{ $inventorie->VehicleColor }}"
                               data-truck="{{ $inventorie->VehicleTruckID }}"
                               data-model="{{ $inventorie->ModelID }}"
                               data-plate="{{ $inventorie->VehicleLicPlate }}"
                               data-vin="{{ $inventorie->VehicleVIN }}"
                               data-cov="{{ $inventorie->VehicleCOV }}"
                               data-type="{{ strtolower($inventorie->VehicleType) }}"
                               data-archive="{{ $inventorie->VehicleArchive }}"
                               onclick="jQuery('#vehicle-modal-edit').modal('show');"
                               class="btn btn-secondary btn-sm btn-icon icon-left">
                               Edit
                            </a>
                            @if(!auth()->check() || auth()->user()?->userlevel == 1)
                            <a href="javascript:;"
                               data-id="{{ $inventorie->VehicleID }}"
                               onclick="jQuery('#inventory-modal-delete').modal('show');"
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
<div class="modal fade custom-width" id="inventory-modal-delete" tabindex="-1" role="dialog" aria-labelledby="delete-modal-label" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="delete-modal-label">Are you sure? </h4>
            </div>
            <form method="post" action="#" id="InventoryDelete">
                @csrf
                @method('DELETE')
                <input type="hidden" name="DeleteInventoryID" id="DeleteInventoryID" value="">
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-info">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade custom-width" id="vehicle-modal" tabindex="-1" role="dialog" aria-labelledby="create-modal-label" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="create-modal-label">Add Vehicle</h4>
            </div>
            <form method="post" action="{{ route('manage-inventory.store') }}" id="Inventory">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nickname" class="control-label">Nickname</label>
                                <input type="text" class="form-control" id="nickname" name="VehicleNickName" placeholder="">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="VehicleModel" class="control-label">Group</label>
                                <select class="form-control" id="VehicleModel" name="VehicleModel">
                                    <option value="0">None</option>
                                    @foreach ($groups as $group)
                                        <option value="{{ $group->groupid }}">{{ $group->groupname }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="color" class="control-label">Color</label>
                                <input type="text" class="form-control" id="color" name="VehicleColor" placeholder="">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="VehicleTruckID" class="control-label">Truck</label>
                                <select class="form-control" id="VehicleTruckID" name="VehicleTruckID">
                                    <option value="0">None</option>
                                    @foreach ($trucks as $truck)
                                        <option value="{{ $truck->truckid }}">{{ $truck->truckname }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="VehicleLicPlate" class="control-label">Plate #</label>
                                <input type="text" class="form-control" id="VehicleLicPlate" name="VehicleLicPlate" placeholder="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="VehicleVIN" class="control-label">Vehicle VIN</label>
                                <input type="text" class="form-control" id="VehicleVIN" name="VehicleVIN" placeholder="">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="VehicleCOV" class="control-label">Vehicle COV</label>
                                <input type="text" class="form-control" id="VehicleCOV" name="VehicleCOV" placeholder="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="ModelID" class="control-label">Model</label>
                                <select class="form-control" id="ModelID" name="ModelID">
                                    <option value="0">None</option>
                                    @foreach ($models as $model)
                                        <option value="{{ $model->modelid }}">{{ $model->modelname }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="VehicleType" class="control-label">Type</label>
                                <select class="form-control" id="VehicleType" name="VehicleType">
                                    <option value="display">Display</option>
                                    <option value="demo">Demo</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="control-label">Archive</label>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <div class="form-group">
                                <label class="radio-inline">
                                    <input type="radio" value="1" name="EventArchive" id="EventArchive"> Yes
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" value="0" name="EventArchive" id="EventArchive1" checked> No
                                </label>
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
<div class="modal fade custom-width" id="vehicle-modal-edit" tabindex="-1" role="dialog" aria-labelledby="edit-modal-label" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="edit-modal-label">Edit Vehicle</h4>
            </div>
            <form method="post" action="#" id="InventoryEdit">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="VehicleNickNameEdit" class="control-label">Nickname</label>
                                <input type="text" class="form-control" id="VehicleNickNameEdit" name="VehicleNickName" placeholder="">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="VehicleModelEdit" class="control-label">Group</label>
                                <select class="form-control" id="VehicleModelEdit" name="VehicleModel">
                                    <option value="0">None</option>
                                    @foreach ($groups as $group)
                                        <option value="{{ $group->groupid }}">{{ $group->groupname }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="VehicleColorEdit" class="control-label">Color</label>
                                <input type="text" class="form-control" id="VehicleColorEdit" name="VehicleColor" placeholder="">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="VehicleTruckIDEdit" class="control-label">Truck</label>
                                <select class="form-control" id="VehicleTruckIDEdit" name="VehicleTruckID">
                                    <option value="0">None</option>
                                    @foreach ($trucks as $truck)
                                        <option value="{{ $truck->truckid }}">{{ $truck->truckname }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="VehicleLicPlateEdit" class="control-label">Plate #</label>
                                <input type="text" class="form-control" id="VehicleLicPlateEdit" name="VehicleLicPlate" placeholder="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="VehicleVINEdit" class="control-label">Vehicle VIN</label>
                                <input type="text" class="form-control" id="VehicleVINEdit" name="VehicleVIN" placeholder="">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="VehicleCOVEdit" class="control-label">Vehicle COV</label>
                                <input type="text" class="form-control" id="VehicleCOVEdit" name="VehicleCOV" placeholder="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="ModelIDEdit" class="control-label">Model</label>
                                <select class="form-control" id="ModelIDEdit" name="ModelID">
                                    <option value="0">None</option>
                                    @foreach ($models as $model)
                                        <option value="{{ $model->modelid }}">{{ $model->modelname }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="VehicleTypeEdit" class="control-label">Type</label>
                                <select class="form-control" id="VehicleTypeEdit" name="VehicleType">
                                    <option value="display">Display</option>
                                    <option value="demo">Demo</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="control-label">Archive</label>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <div class="form-group">
                                <label class="radio-inline">
                                    <input type="radio" value="1" name="EventArchive" id="EventArchiveEdit"> Yes
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" value="0" name="EventArchive" id="EventArchiveEdit1"> No
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-info">Save Changes</button>
                </div>
                <input type="hidden" id="VehicleID" name="VehicleID" value="">
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    $( document ).ready(function() {
    	$("button.btn-info").click(function(){
    		if($(this).text()=="Create"){
    			var form = $( "#Inventory" );
                if (form.valid()) {
                    form.submit();
                }
    		}
    		else if($(this).text()=="Save Changes"){
    			var form = $( "#InventoryEdit" );
                if (form.valid()) {
                    form.submit();
                }
    		}
    		else if($(this).text()=="Delete"){
    			var form = $("#InventoryDelete");
                if (form.valid()) {
                    form.submit();
                }
    		}
    	});

    	$("a.btn-danger").click(function(){
            var id = $(this).data('id');
    		$("#DeleteInventoryID").val(id);
            $("#InventoryDelete").attr('action', '/manage-inventory/' + id);
    	});

    	$("a.btn-secondary").click(function(){
    		var btn = $(this);
    		var VId = btn.data('id');
    		$("#VehicleNickNameEdit").val(btn.data('nickname'));
    		$("#VehicleModelEdit").val(btn.data('group'));
    		$("#VehicleColorEdit").val(btn.data('color'));
    		$("#VehicleTruckIDEdit").val(btn.data('truck'));
    		$("#VehicleID").val(VId);
    		$("#ModelIDEdit").val(btn.data('model'));
    		$("#VehicleLicPlateEdit").val(btn.data('plate'));
    		$("#VehicleVINEdit").val(btn.data('vin'));
    		$("#VehicleCOVEdit").val(btn.data('cov'));
    		$("#VehicleTypeEdit").val(btn.data('type'));

    		if(parseInt(btn.data('archive')) == 1){
    			$("#EventArchiveEdit").prop('checked', true);
    			$("#EventArchiveEdit1").prop('checked', false);
    		}
    		else {
    			$("#EventArchiveEdit").prop('checked', false);
    			$("#EventArchiveEdit1").prop('checked', true);
    		}
            $("#InventoryEdit").attr('action', '/manage-inventory/' + VId);
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
{!! JsValidator::formRequest('App\Http\Requests\Backend\InventoryRequest', '#Inventory') !!}
{!! JsValidator::formRequest('App\Http\Requests\Backend\InventoryRequest', '#InventoryEdit') !!}
{!! JsValidator::formRequest('App\Http\Requests\Backend\InventoryRequest', '#InventoryDelete') !!}

@endpush
