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

    <button data-dismiss="modal" class="btn btn-info import_vehicles pull-right" type="button">Import Vehicles</button>
    <ul class="nav nav-tabs right-aligned">
        <li>
            <a href="javascript:;" onclick="jQuery('#truck-modal').modal('show');"><span class="hidden-xs">Add Truck</span></a>
        </li>
    </ul>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Trucks</h3>
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
                        <th>Inv. Count</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="middle-align">
                    @foreach ($trucks as $truck)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $truck->TruckName }}</td>
                        <td>{{ count($truck->TruckInventory) }}
                            <input type="hidden" name="TruckInventorySelected" id="TruckInventorySelected{{ $truck->TruckID }}" value="{{ implode(',', $truck->TruckInventory) }}">
                            <input type="hidden" name="BTSetID" id="BTSetID{{ $truck->TruckID }}" value="{{ $truck->BTSetID }}">
                        </td>
                        <td>
                            <a href="javascript:;" 
                               data-id="{{ $truck->TruckID }}" 
                               data-name="{{ $truck->TruckName }}"
                               data-btset="{{ $truck->BTSetID }}"
                               data-inventory="{{ implode(',', $truck->TruckInventory) }}"
                               onclick="jQuery('#truck-modal-edit').modal('show');" 
                               class="btn btn-secondary btn-sm btn-icon icon-left">
                               Edit
                            </a>
                            @if(!auth()->check() || auth()->user()?->userlevel == 1)
                            <a href="javascript:;" 
                               data-id="{{ $truck->TruckID }}" 
                               onclick="jQuery('#truck-modal-delete').modal('show');" 
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
<div class="modal fade custom-width" id="truck-modal-delete" tabindex="-1" role="dialog" aria-labelledby="delete-modal-label" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="delete-modal-label">Are you sure? </h4>
            </div>
            <form method="post" action="#" id="TruckDelete">
                @csrf
                @method('DELETE')
                <input type="hidden" name="DeleteTruckID" id="DeleteTruckID" value="">
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-info">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Upload COV Modal -->
<div class="modal fade custom-width" id="cov-modal-upload" tabindex="-1" role="dialog" aria-labelledby="cov-upload-label" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="cov-upload-label">Upload COV</h4>
            </div>
            <div class="modal-body">
                 <form method="post" action="#" id="cov">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="uploadCOV" class="control-label">Upload COV</label>
                                <input style="padding-bottom:7%;" type="file" class="form-control" id="uploadCOV" name="uploadCOV" placeholder="">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                <button type="button" class="uploadCOVClass btn btn-info">Save</button>
            </div>
        </div>
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade custom-width" id="truck-modal" tabindex="-1" role="dialog" aria-labelledby="create-modal-label" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="create-modal-label">Add Truck</h4>
            </div>
            <div class="modal-body">
                <form method="post" action="{{ route('manage-trucks.store') }}" id="Truck">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="TruckName" class="control-label">Truck Name</label>
                                <input type="text" class="form-control" id="TruckName" name="TruckName" placeholder="">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <label for="multi-select-vehicle" class="control-label">Event Or Truck Inventory</label>
                            <div> <strong><span style="margin-left:9%;">OFF Truck </span>   <span style="margin-left:25%;">ON Truck</span></strong></div>
                            <select class="form-control" multiple="multiple" id="multi-select-vehicle" name="TruckInventory[]">
                                @foreach ($inventory as $item)
                                    <option value="{{ $item->VehicleID }}">{{ $item->VehicleNickName }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label" for="BTSetID">BTSet ID</label>
                                <select name="SetID" class="form-control selectboxit" id="BTSetID">
                                    @foreach ($btsets as $btset)
                                        <option value="{{ $btset->BTSetID }}">{{ $btset->BTSetName }}</option>
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
<div class="modal fade custom-width" id="truck-modal-edit" tabindex="-1" role="dialog" aria-labelledby="edit-modal-label" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="edit-modal-label">Edit Truck</h4>
            </div>
            <div class="modal-body">
                <form method="post" action="#" id="TruckEdit">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label">Total Bikes : <span id="totalBikeText"></span></label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="TruckNameEdit" class="control-label">Truck Name</label>
                                <input type="text" class="form-control" id="TruckNameEdit" name="TruckName" placeholder="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label for="multi-select-vehicle2" class="control-label">Event Or Truck Inventory</label>
                            <div> <strong><span style="margin-left:9%;">OFF Truck </span>   <span style="margin-left:25%;">ON Truck</span></strong></div>
                            <select class="form-control" multiple="multiple" id="multi-select-vehicle2" name="TruckInventory[]">
                                @foreach ($inventory as $item)
                                    <option value="{{ $item->VehicleID }}">{{ $item->VehicleNickName }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label" for="BTSetIDEdit">BTSet ID</label>
                                <select name="SetID" class="form-control selectboxit" id="BTSetIDEdit">
                                    @foreach ($btsets as $btset)
                                        <option value="{{ $btset->BTSetID }}">{{ $btset->BTSetName }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" id="TruckEditID" name="TruckID" value="">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-info">Save Changes</button>
                <button type="button" class="btn btn-info">Upload COV</button>
            </div>
        </div>
    </div>
</div>

<!-- Import Vehicles Modal -->
<div class="modal fade" id="modal-2" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="import-modal-label">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="import-modal-label">Import Vehicles</h4>
            </div>
            <input type="hidden" name="filename" id="filename" value="" />
            <div class="modal-body">
                <div class="form-group">
                    <label for="event_name" class="control-label">Upload file</label>
                    <input type="file" placeholder="Choose excel file" id="event_name" name="EventName" class="form-control" style="padding-top:3%;padding-bottom:6%;">
                </div>
                <div class="form-group">
                    <label class="control-label" for="truckName">Select Truck</label>
                    <select name="truckName" class="form-control selectboxit" id="truckName">
                        <optgroup label="Select Truck">
                            <option value="0">None</option>
                            @foreach ($trucks as $truck)
                                <option value="{{ $truck->TruckID }}">{{ $truck->TruckName }}</option>
                            @endforeach
                        </optgroup>
                    </select>
                </div>
                <div class="form-group">
                    <button class="btn btn-info" id="AjaxReadXls" type="button">Submit</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@vite(['resources/js/backend/trucks/index.js'])
{!! returnScriptWithNonce(asset('vendor/jsvalidation/js/jsvalidation.js')) !!}
{!! JsValidator::formRequest('App\Http\Requests\Backend\TruckRequest', '#Truck') !!}
{!! JsValidator::formRequest('App\Http\Requests\Backend\TruckRequest', '#TruckEdit') !!}
{!! JsValidator::formRequest('App\Http\Requests\Backend\TruckRequest', '#TruckDelete') !!}
@endpush
