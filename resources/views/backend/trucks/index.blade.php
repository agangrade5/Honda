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
<script>
    var count = 0;
    $( document ).ready(function() {
        // Initialize MultiSelect elements
        $("#multi-select-vehicle").multiSelect({
            afterInit: function() {
                this.$selectableContainer.add(this.$selectionContainer).find('.ms-list').perfectScrollbar();
            },
            afterSelect: function() {
                this.$selectableContainer.add(this.$selectionContainer).find('.ms-list').perfectScrollbar('update');
            }
        });

        $("#multi-select-vehicle2").multiSelect({
            afterInit: function() {
                this.$selectableContainer.add(this.$selectionContainer).find('.ms-list').perfectScrollbar();
            },
            afterSelect: function() {
                count++;
                $("#totalBikeText").text(count);
                this.$selectableContainer.add(this.$selectionContainer).find('.ms-list').perfectScrollbar('update');
            },
            afterDeselect: function() {
                count--;
                $("#totalBikeText").text(count);
            }
        });

        // Trigger Import modal
    	$(".import_vehicles").click(function(){
    		jQuery('#modal-2').modal('show', {backdrop: 'fade'});
    	});

        // Handle AJAX file upload on change
        $('#event_name').on('change', function() {
            var file_data = $('#event_name').prop('files')[0];
            var form_data = new FormData();
            form_data.append('file', file_data);
            form_data.append('action', 'upload');
            form_data.append('_token', '{{ csrf_token() }}');
            
            $.ajax({
                url: '{{ route("manage-trucks.import") }}',
                dataType: 'text',
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                type: 'post',
                success: function(php_script_response){
                    $("#filename").val(php_script_response);
                }
            });
        });

        // Handle Import Submit
    	$("#AjaxReadXls").click(function(){
    		$("#ajaxLoad").show();
            jQuery('#modal-2').modal('hide');
    		$.ajax({
    			url: '{{ route("manage-trucks.import") }}',
    			dataType: 'JSON',
    		 	data: { 
                    action: 'read',
                    fileName: $("#filename").val(),
                    truckId: $('#truckName').val(),
                    _token: '{{ csrf_token() }}'
                },
    		 	type: 'post',
        		async: true,
    		}).done(function() {
    			console.log("done");
    		}).fail(function() {
    			console.log( "error" );
    		}).always(function() {
    			window.location.reload(true);
    		});
        });

        // Submit form controls
    	$("button.btn-info").click(function(){
    		if($(this).text()=="Create"){
    			var form = $( "#Truck" );
                if (form.valid()) {
                    form.submit();
                }
    		}
    		else if($(this).text()=="Save Changes"){
    			var form = $( "#TruckEdit" );
                if (form.valid()) {
                    form.submit();
                }
    		}
    		else if($(this).text()=="Delete"){
    			var form = $("#TruckDelete");
                if (form.valid()) {
                    form.submit();
                }
    		}
    		else if($(this).text()=="Upload COV"){
    			$(".close").trigger("click");
    			jQuery('#cov-modal-upload').modal('show');
    			return false;
    		}
    	});

        // Handle COV save
        $(".uploadCOVClass").click(function(){
            var file_data = $('#uploadCOV').prop('files')[0];
            var form_data = new FormData();
            form_data.append('file', file_data);
            form_data.append('action', 'uploadCOV');
            form_data.append('_token', '{{ csrf_token() }}');
            
            $.ajax({
                url: '{{ route("manage-trucks.import") }}',
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                type: 'post',
                success: function(response){
                    jQuery('#cov-modal-upload').modal('hide');
                    jQuery('#truck-modal-edit').modal('show');
                    
                    $('#multi-select-vehicle2').multiSelect('deselect_all');
                    if (response.data && response.data.length > 0) {
                        $('#multi-select-vehicle2').multiSelect('select', response.data.map(String));
                    }
                }
             });
            return false;
        });

        // Wire Action Click Listeners
    	$("a.btn-danger").click(function(){
            var id = $(this).data('id');
    		$("#DeleteTruckID").val(id);
            $("#TruckDelete").attr('action', '/manage-trucks/' + id);
    	});

    	$("a.btn-secondary").click(function(){
    		count = 0;
            var btn = $(this);
    		var TruckId = btn.data('id');
    		$("#DeleteTruckID").val(TruckId);
    		$("#TruckEditID").val(TruckId);

            if ($("#BTSetIDEdit").data("selectBox-selectBoxIt")) {
    		    $("#BTSetIDEdit").data("selectBox-selectBoxIt").selectOption(String(btn.data('btset')));
            } else {
                $("#BTSetIDEdit").val(btn.data('btset'));
            }

    		$("#TruckNameEdit").val(btn.data('name'));
    		
    		var TruckInventoryStr = String(btn.data('inventory') || '');
    		var TruckInventoryArray = TruckInventoryStr ? TruckInventoryStr.split(",") : [];
    		var inv = [];
    		$.each(TruckInventoryArray, function(index, InventoryID){
    			count++;
    			inv.push(String(InventoryID));
    		});
    		
    		$('#multi-select-vehicle2').multiSelect('deselect_all');
            if (inv.length > 0) {
    		    $('#multi-select-vehicle2').multiSelect('select', inv);
            }
            $("#totalBikeText").text(count);
            $("#TruckEdit").attr('action', '/manage-trucks/' + TruckId);
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
{!! JsValidator::formRequest('App\Http\Requests\Backend\TruckRequest', '#Truck') !!}
{!! JsValidator::formRequest('App\Http\Requests\Backend\TruckRequest', '#TruckEdit') !!}
{!! JsValidator::formRequest('App\Http\Requests\Backend\TruckRequest', '#TruckDelete') !!}
@endpush
