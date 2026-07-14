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
            <a href="javascript:;" onclick="jQuery('#truck-modal').modal('show');"><span class="hidden-xs">Add Country</span></a>
        </li>
    </ul>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Countries</h3>
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
                        <th>Country Code</th>
                        <th>Region</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="middle-align">
                    @foreach ($countries as $countrie)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $countrie->CountryName }}</td>
                        <td>{{ $countrie->CountryCode }}</td>
                        <td>{{ $countrie->Region }}</td>
                        <td>
                            <input type="hidden" id="RegionID{{ $countrie->CountryID }}" value="{{ $countrie->RegionID }}">
                            <a href="javascript:;"
                               id="CID{{ $countrie->CountryID }}"
                               data-id="{{ $countrie->CountryID }}"
                               data-name="{{ $countrie->CountryName }}"
                               data-code="{{ $countrie->CountryCode }}"
                               data-region="{{ $countrie->RegionID }}"
                               onclick="jQuery('#truck-modal-edit').modal('show');"
                               class="btn btn-secondary btn-sm btn-icon icon-left">
                               Edit
                            </a>
                            @if(!auth()->check() || auth()->user()?->userlevel == 1)
                            <a href="javascript:;"
                               data-id="{{ $countrie->CountryID }}"
                               onclick="jQuery('#country-modal-delete').modal('show');"
                               class="btn btn-danger btn-icon btn-sm">
                               <i class="icon-white icon-heart"></i> Delete
                            </a>
                            @endif
                            <input type="hidden" id="StateName{{ $countrie->CountryID }}" value="
                                @foreach ($countrie->StateName as $Statevalue)
                                    <option value='{{ $Statevalue->stateid }}'>{{ $Statevalue->statename }}</option>
                                @endforeach
                            ">
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
<div class="modal fade custom-width" id="country-modal-delete" tabindex="-1" role="dialog" aria-labelledby="country-modal-delete-label" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="delete-modal-label">Are you sure? </h4>
            </div>
            <form method="post" action="#" id="CountryDelete">
                @csrf
                @method('DELETE')
                <input type="hidden" name="DeleteCountryID" id="DeleteCountryID" value="">
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-info">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit State Modal -->
<div class="modal fade custom-width" id="state-modal-edit-delete" tabindex="-1" role="dialog" aria-labelledby="state-modal-edit-delete-label" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="state-edit-label">Edit State-Province-District </h4>
            </div>
            <form method="post" action="#" id="stateEditForm">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <label class="control-label" for="StateNameEdit1">State-Province-District Name</label>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <input type="text" class="form-control" id="StateNameEdit1" name="StateName1" placeholder="">
                                <input type="hidden" id="OLDStateNameEdit" name="OLDStateNameEdit" placeholder="">
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="StateIDEdit" id="StateIDEdit" value="">
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" id="closeStateModal">Close</button>
                    <button type="button" class="btn btn-secondary btn-info" id="saveStateBtn">Save</button>
                    <button type="button" class="btn btn-danger btn-info" id="deleteStateBtn"> Delete </button>
                </div>
            </form>
            <form method="post" action="#" id="stateDeleteForm">
                @csrf
                <input type="hidden" name="StateIDDelete" id="StateIDDelete" value="">
            </form>
        </div>
    </div>
</div>

<!-- Create Country Modal -->
<div class="modal fade custom-width" id="truck-modal" tabindex="-1" role="dialog" aria-labelledby="truck-modal-label" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="create-modal-label">Add Country</h4>
            </div>
            <form method="post" action="{{ route('manage-countries.store') }}" id="CountryForm">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <label class="control-label" for="CountryName">Country Name</label>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <input type="text" class="form-control" id="CountryName" name="CountryName" placeholder="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label for="RegionID" class="control-label">Region</label>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <select class="form-control" id="RegionID" name="RegionID">
                                    @foreach($regions as $region)
                                        <option value="{{ $region->regionid }}">{{ $region->regionname }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label for="CountryCode" class="control-label">Country Code</label>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <input type="text" class="form-control" id="CountryCode" name="CountryCode" placeholder="">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-info btn-secondary">Create</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Country Modal -->
<div class="modal fade custom-width" id="truck-modal-edit" tabindex="-1" role="dialog" aria-labelledby="truck-modal-edit-label" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="edit-modal-label">Edit Country</h4>
            </div>
            <div class="modal-body">
                <form method="post" action="#" id="CountryFormEdit">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-4">
                            <label class="control-label" for="CountryNameEdit">Country Name</label>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <input type="text" class="form-control" id="CountryNameEdit" name="CountryName" placeholder="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label for="RegionIDEdit" class="control-label">Region</label>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <select class="form-control" id="RegionIDEdit" name="RegionID">
                                    @foreach($regions as $region)
                                        <option value="{{ $region->regionid }}">{{ $region->regionname }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label for="CountryCodeEdit" class="control-label">Country Code</label>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <input type="text" class="form-control" id="CountryCodeEdit" name="CountryCode" placeholder="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label for="manage_states" class="control-label">Manage State-Province-District</label><br><br>
                        </div>
                    </div>
                    <!--Text box to add states to country-->
                    <div class="row">
                        <div class="col-md-6">
                            <input type="text" class="form-control" id="StateNameEdit" name="StateName" placeholder="">
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <button type="button" class="btn btn-white" id="add_state">Add State-Province-District</button>
                            </div>
                        </div>
                    </div>
                    <!--Multiple select box for states-->
                    <div class="row">
                        <div class="col-md-12">
                            <label for="multi-select-state" class="control-label">State-Province-District</label><br><br>
                            <select class="form-control" multiple="multiple" id="multi-select-state" name="SelectState[]" size="10">
                            </select>
                        </div>
                    </div>
                    <input type="hidden" id="CountryID" name="CountryID" value="">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-info btn-secondary">Save Changes</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@vite(['resources/js/backend/country/index.js'])
{!! returnScriptWithNonce(asset('vendor/jsvalidation/js/jsvalidation.js')) !!}
{!! JsValidator::formRequest('App\Http\Requests\Backend\CountryRequest', '#CountryForm') !!}
{!! JsValidator::formRequest('App\Http\Requests\Backend\CountryRequest', '#CountryFormEdit') !!}
{!! JsValidator::formRequest('App\Http\Requests\Backend\CountryRequest', '#CountryDelete') !!}
@endpush
