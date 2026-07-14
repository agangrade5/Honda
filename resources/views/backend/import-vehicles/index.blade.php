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

    <form id="VehicleForm" method="post" action="#" class="form-wizard validate" novalidate>
        @csrf
        <ul class="tabs">
            <li class="active">
            </li>
        </ul>
        <div class="progress-indicator">
            <span></span>
        </div>
        <div class="tab-content no-margin">
            <!-- Tabs Content -->
            <div class="tab-pane with-bg active" id="fwv-1">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="control-label" for="event_name">Upload file</label>
                            <input style="padding-top:3%;padding-bottom:6%;" class="form-control" type="file" name="EventName" id="event_name" data-validate="required" placeholder="Choose a name for this event" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <!-- Main Footer -->
    @include('layouts.backend.footer')
</div>
<!-- content @e -->

<!-- Modal 1 (Basic)-->
<div class="modal fade" id="modal-1" tabindex="-1" role="dialog" aria-labelledby="modal-1-label" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="modal-1-label">Import Vehicles</h4>
            </div>
            <input type="hidden" name="filename" id="filename" value="" />
            <div class="modal-body">
                Are you sure you want to upload this new vehicle list?
                <div style="text-align:center;display:none;" id="ajaxLoad">
                    <img src="{{ asset('assets/images/ajax-loader.gif') }}">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal" onclick="return DeletUploadedFile();">NO</button>
                <button type="button" class="btn btn-info" id="AjaxReadXls">YES</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal 1 end-->
@endsection

@push('scripts')
@vite(['resources/js/backend/import-vehicles/index.js'])
@endpush
