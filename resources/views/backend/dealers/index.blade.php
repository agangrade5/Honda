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
            <span class="hidden-xs">Add Dealer</span>
            </a>
        </li>
    </ul>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Dealers</h3>
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
                        <th>Dealer number</th>
                        <th>Dealer name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="middle-align">
                    @foreach ($dealers as $dealer)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $dealer->dealernumber }}</td>
                        <td>{{ $dealer->dealername }}</td>
                        <td>
                            <a href="javascript:;" 
                               data-id="{{ $dealer->dealerid }}"
                               data-number="{{ $dealer->dealernumber }}"
                               data-name="{{ $dealer->dealername }}"
                               data-location="{{ $dealer->dealerlocation }}"
                               data-region="{{ $dealer->dealerregion }}"
                               data-district="{{ $dealer->dealerdistrict }}"
                               onclick="jQuery('#user-modal-edit').modal('show');" 
                               class="btn btn-secondary btn-sm btn-icon icon-left">
                               Edit
                            </a>
                            @if(!auth()->check() || auth()->user()?->userlevel == 1)
                            <a href="javascript:;" 
                               data-id="{{ $dealer->dealerid }}" 
                               onclick="jQuery('#dealer-modal-delete').modal('show');" 
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

<!-- Dealer Delete Modal -->
<div class="modal fade custom-width" id="dealer-modal-delete" tabindex="-1" role="dialog" aria-labelledby="dealer-modal-delete-label" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="dealer-modal-delete-label">Are you sure? </h4>
            </div>
            <form method="post" action="#" id="DealerDelete">
                @csrf
                @method('DELETE')
                <input type="hidden" name="DeleteDealerID" id="DeleteDealerID" value="">
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-info">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Dealer Create Modal -->
<div class="modal fade custom-width" id="user-modal" tabindex="-1" role="dialog" aria-labelledby="user-modal-label" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="user-modal-label">Add Dealer</h4>
            </div>
            <form method="post" action="{{ route('manage-dealers.store') }}" id="DealerForm">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="DealerNumber" class="control-label">Dealer Number</label>
                                <input type="text" class="form-control" id="DealerNumber" name="DealerNumber" placeholder="">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="DealerName" class="control-label">Dealer Name</label>
                                <input type="text" class="form-control" id="DealerName" name="DealerName" placeholder="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="DealerLocation" class="control-label">Dealer Location</label>
                                <input type="text" class="form-control" id="DealerLocation" name="DealerLocation" placeholder="">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="DealerRegion" class="control-label">Dealer Region</label>
                                <input type="text" class="form-control" id="DealerRegion" name="DealerRegion" placeholder="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="DealerDistrict" class="control-label">Dealer District</label>
                                <input type="text" class="form-control" id="DealerDistrict" name="DealerDistrict" placeholder="">
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

<!-- Dealer Edit Modal -->
<div class="modal fade custom-width" id="user-modal-edit" tabindex="-1" role="dialog" aria-labelledby="user-modal-edit-label" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="user-modal-edit-label">Edit Dealer</h4>
            </div>
            <form method="post" action="#" id="DealerFormEdit">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="DealerNumberEdit" class="control-label">Dealer Number</label>
                                <input type="text" class="form-control" id="DealerNumberEdit" name="DealerNumber" placeholder="">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="DealerNameEdit" class="control-label">Dealer Name</label>
                                <input type="text" class="form-control" id="DealerNameEdit" name="DealerName" placeholder="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="DealerLocationEdit" class="control-label">Dealer Location</label>
                                <input type="text" class="form-control" id="DealerLocationEdit" name="DealerLocation" placeholder="">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="DealerRegionEdit" class="control-label">Dealer Region</label>
                                <input type="text" class="form-control" id="DealerRegionEdit" name="DealerRegion" placeholder="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="DealerDistrictEdit" class="control-label">Dealer District</label>
                                <input type="text" class="form-control" id="DealerDistrictEdit" name="DealerDistrict" placeholder="">
                            </div>
                        </div>
                    </div>
                    <input type="hidden" id="DealerID" name="DealerID" value="">
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
    	$("button.btn-info").click(function(){
    		if($(this).text()=="Create"){
    			var form = $( "#DealerForm" );
                if (form.valid()) {
                    form.submit();
                }
    		}
    		else if($(this).text()=="Save Changes"){
    			var form = $( "#DealerFormEdit" );
                if (form.valid()) {
                    form.submit();
                }
    		}
    		else if($(this).text()=="Delete"){
    			var form = $("#DealerDelete");
                if (form.valid()) {
                    form.submit();
                }
    		}
    	});

    	$("a.btn-danger").click(function(){
            var id = $(this).data('id');
    		$("#DeleteDealerID").val(id);
            $("#DealerDelete").attr('action', '/manage-dealers/' + id);
    	});

    	$("a.btn-secondary").click(function(){
    		var btn = $(this);
            var DealerId = btn.data('id');
    		$("#DealerNumberEdit").val(btn.data('number'));
    		$("#DealerNameEdit").val(btn.data('name'));
    		$("#DealerLocationEdit").val(btn.data('location'));
    		$("#DealerRegionEdit").val(btn.data('region'));
    		$("#DealerID").val(DealerId);
    		$("#DealerDistrictEdit").val(btn.data('district'));
            $("#DealerFormEdit").attr('action', '/manage-dealers/' + DealerId);
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
{!! JsValidator::formRequest('App\Http\Requests\Backend\DealerRequest', '#DealerForm') !!}
{!! JsValidator::formRequest('App\Http\Requests\Backend\DealerRequest', '#DealerFormEdit') !!}
{!! JsValidator::formRequest('App\Http\Requests\Backend\DealerRequest', '#DealerDelete') !!}
@endpush
