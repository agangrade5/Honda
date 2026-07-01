@extends('layouts.backend.app')
@section('title', $title)
@section('content')
<!-- content @s -->
<div class="main-content">
    <!-- Content Header section -->
    @include('layouts.backend.content_header', compact('title'))
    <?php /* if(isset($_SESSION['msg']) && !empty($_SESSION['msg'])){ ?>
    <div class="dx-warning">
        <div>
            <p><?php echo $_SESSION['msg'];?></p>
        </div>
    </div>
    <?php }
        unset($_SESSION['msg']); */
        ?>
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
                    <?php
                        //if(isset($trucks->Trucks) && !empty($trucks->Trucks)){
                        //foreach ($trucks->Trucks as $key => $truck) {
                        ?>
                    <tr>
                        <td>1</td>
                        <td>Test</td>
                        <td>Test
                            <input type="hidden" name="TruckInventorySelected" id="TruckInventorySelected<?php //echo $truck->TruckID;?>" value="<?php //echo implode(',',$truck->TruckInventory);?>">
                            <input type="hidden" name="BTSetID" id="BTSetID<?php //echo $truck->TruckID;?>" value="<?php //echo $truck->BTSetID;?>">
                        </td>
                        <td>
                            <a href="javascript:;" onclick="jQuery('#truck-modal-edit').modal('show');" class="btn btn-secondary btn-sm btn-icon icon-left">
                            Edit
                            </a>
                            <?php //if(Auth::getUsers()->userlevel==1){ ?>
                            <a href="javascript:;" id="<?php //echo $truck->TruckID;?>" onclick="jQuery('#truck-modal-delete').modal('show');" class="btn btn-danger btn-icon"><i class="icon-white icon-heart"></i> Delete</a>
                            <?php //} ?>
                        </td>
                    </tr>
                    <?php //}} ?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- Main Footer -->
    @include('layouts.backend.footer')
</div>
<!-- content @e -->

<!-- Delete Modal -->
<div class="modal fade custom-width" id="truck-modal-delete">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Are you sure? </h4>
            </div>
            <form method="post" action="Action.php" id="TruckDelete">
                <input type="hidden" name="DeleteTruckID" id="DeleteTruckID" value="">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="controller" value="truck">
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-info" data-dismiss="modal">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Truck Edit & Create Modal -->
<div class="modal fade custom-width" id="cov-modal-upload">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Upload COV</h4>
            </div>
            <div class="modal-body">
                 <form method="post" action="" id="cov">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="add-field-1" class="control-label">Upload COV</label>
                                <input style="padding-bottom:7%;" type="file" class="form-control" id="uploadCOV" name="uploadCOV" placeholder="">
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="action" value="upload">
                    <input type="hidden" name="controller" value="cov">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                <button type="button" class="uploadCOVClass btn btn-info" data-dismiss="modal">Save</button>
            </div>
        </div>
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade custom-width" id="truck-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Add Truck</h4>
            </div>
            <div class="modal-body">
                <form method="post" action="Action.php" id="Truck">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="add-field-1" class="control-label">Truck Name</label>
                                <input type="text" class="form-control" id="TruckName" name="TruckName" placeholder="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <script type="text/javascript">
                                jQuery(document).ready(function($)
                                {
                                    $("#multi-select-vehicle").multiSelect({
                                        afterInit: function()
                                        {
                                            // Add alternative scrollbar to list
                                            this.$selectableContainer.add(this.$selectionContainer).find('.ms-list').perfectScrollbar();
                                        },
                                        afterSelect: function()
                                        {
                                            // Update scrollbar size
                                            this.$selectableContainer.add(this.$selectionContainer).find('.ms-list').perfectScrollbar('update');
                                        }
                                    });
                                });
                            </script>
                            <label for="multi-select-vehicle" class="control-label">Event Or Truck Inventory</label>
                            <div> <strong><span style="margin-left:9%;">OFF Truck </span>   <span style="margin-left:25%;">ON Truck</span></strong></div>
                            <select class="form-control" multiple="multiple" id="multi-select-vehicle" name="TruckInventory[]">
                            <?php
                                /* if(isset($inventory->Success) && $inventory->Success==1){
                                   	foreach ($inventory->Vehicles as $key => $inventory1) {
                                   		echo '<option value="'.$inventory1->VehicleID.'">'.$inventory1->VehicleNickName.'</option>';
                                   	}
                                } */
                                ?>
                            </select>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label" for="social">BTSet ID</label>
                                <select name="SetID" class="selectboxit" id="BTSetID">
                                    <?php
                                        /* if(isset($btsets->Success) && $btsets->Success==1){
                                            foreach ($btsets->BTSets as $skey => $btset) {
                                            	echo '<option value="'.$btset->BTSetID.'">'.$btset->BTSetName.'</option>';
                                            }
                                        } */
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="action" value="add">
                    <input type="hidden" name="controller" value="truck">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-info" data-dismiss="modal">Create</button>
            </div>
        </div>
    </div>
</div>
<!-- Edit Modal -->
<div class="modal fade custom-width" id="truck-modal-edit">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Edit Truck</h4>
            </div>
            <div class="modal-body">
                <form method="post" action="Action.php" id="TruckEdit">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="add-field-1" class="control-label">Total Bikes : <span id="totalBikeText"></span></label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="edit-field-1" class="control-label">Truck Name</label>
                                <input type="text" class="form-control" id="TruckNameEdit" name="TruckName" placeholder="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <script type="text/javascript">
                                jQuery(document).ready(function($)
                                {
                                    $("#multi-select-vehicle2").multiSelect({
                                        afterInit: function()
                                        {
                                            // Add alternative scrollbar to list
                                            this.$selectableContainer.add(this.$selectionContainer).find('.ms-list').perfectScrollbar();
                                        },
                                        afterSelect: function()
                                        {
                                    		count++;
                                        	$("#totalBikeText").text(count);
                                    		//console.log(this.$selectableContainer.add(this.$selectionContainer).find('.ms-list'));
                                            // Update scrollbar size
                                            this.$selectableContainer.add(this.$selectionContainer).find('.ms-list').perfectScrollbar('update');
                                        },
                                        afterDeselect:function(){
                                        	count--
                                    		//console.log(count);
                                        	$("#totalBikeText").text(count);
                                        }
                                    });
                                });
                            </script>
                            <label for="multi-select-vehicle" class="control-label">Event Or Truck Inventory</label>
                            <div> <strong><span style="margin-left:9%;">OFF Truck </span>   <span style="margin-left:25%;">ON Truck</span></strong></div>
                            <select class="form-control" multiple="multiple" id="multi-select-vehicle2" name="TruckInventory[]">
                            <?php
                                /* if(isset($inventory->Success) && $inventory->Success==1){
                                   	foreach ($inventory->Vehicles as $key => $inventory1) {
                                   		echo '<option value="'.$inventory1->VehicleID.'">'.$inventory1->VehicleNickName.'</option>';
                                   	}
                                } */
                                ?>
                            </select>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label" for="social">BTSet ID</label>
                                <select name="SetID" class="selectboxit" id="BTSetIDEdit">
                                    <?php
                                        /* if(isset($btsets->Success) && $btsets->Success==1){
                                            foreach ($btsets->BTSets as $skey => $btset) {
                                            	echo '<option value="'.$btset->BTSetID.'">'.$btset->BTSetName.'</option>';
                                            }
                                        } */
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="controller" value="truck">
                    <input type="hidden" id="TruckEditID" name="TruckID" value="">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-info" data-dismiss="modal">Save Changes</button>
                <button type="button" class="btn btn-info" data-dismiss="modal">Upload COV</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal 2 (Basic)-->
<div class="modal fade" id="modal-2" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Import Vehicles</h4>
            </div>
            <input type="hidden" name="filename" id="filename" value="" />
            <div class="modal-body">
                <div class="form-group">
                    <label for="event_name" class="control-label">Upload file</label>
                    <input type="file" onchange="" placeholder="Choose a name for this event" data-validate="required" id="event_name" name="EventName" class="form-control" style="padding-top:3%;padding-bottom:6%;">
                    <input id="filename" type="hidden" value="" name="filename">
                </div>
                <script type="text/javascript">
                    jQuery(document).ready(function(){
                        $('#event_name').on('change', function() {
                            var file_data = $('#event_name').prop('files')[0];
                            var form_data = new FormData();
                            form_data.append('file', file_data)
                            form_data.append('action', 'upload')
                            //console.log(form_data);
                            $.ajax({
                                    url: 'uploadVehicle.php', // point to server-side PHP script
                                    dataType: 'text',  // what to expect back from the PHP script, if anything
                                    cache: false,
                                    contentType: false,
                                    processData: false,
                                    data: form_data,
                                    type: 'post',
                                    success: function(php_script_response){
                                    $("#filename").val(php_script_response);
                                        //jQuery('#modal-1').modal('show', {backdrop: 'fade'});
                                    }
                            });
                        });
                    });
                </script>
                <div class="form-group">
                    <label class="control-label" for="email">Select Truck</label>
                    <select name="truckName" class="selectboxit" id="truckName">
                        <optgroup label="Select Truck">
                            <?php
                            if(isset($trucks->Trucks) && !empty($trucks->Trucks)) {
                                foreach ($trucks->Trucks as $key => $truck) {
                            ?>
                                <option value="<?php echo $truck->TruckID;?>"><?php echo $truck->TruckName;?></option>
                            <?php
                                }
                            }
                            ?>
                        </optgroup>
                    </select>
                </div>
                <div class="form-group">
                    <button data-dismiss="modal" class="btn btn-info" id="AjaxReadXls" type="button">Submit</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal 2 end-->
@endsection

@push('scripts')
<script>
    var count = 0;
    var uploadedData = []
    $( document ).ready(function() {
    	$("#AjaxReadXls").click(function(){
    		$("#ajaxLoad").show();
    		$.ajax({
    			url: 'uploadVehicle.php',
    			dataType: 'JSON',
    		 	data:{ action:'read',fileName:$("#filename").val(),truckId:$('#truckName').val()},
    		 	type: 'post',
        		async: true,
    		}).done(function() {
    			//console.log("done");
    		}) .fail(function() {
    			//console.log( "error" );
    		})
    		.always(function() {
    			window.location.reload(true);
    		});
        });

    	$(".import_vehicles").click(function(){
    		jQuery('#modal-2').modal('show', {backdrop: 'fade'});
    	});


    	$("button.btn-info").click(function(){
    		//console.log($(this).text());
    		if($(this).text()=="Create"){
                //console.log("create");
    			$( "#Truck" ).submit();
    		}
    		else if($(this).text()=="Save Changes"){
                //console.log("edit");
    			$( "#TruckEdit" ).submit();
    		}
    		else if($(this).text()=="Delete"){
    			//console.log("delete123");
    			$("#TruckDelete").submit();
    		}
    		else if($(this).text()=="Upload COV"){
    			//console.log("sss");
    			$(".close").trigger("click");
    			jQuery('#cov-modal-upload').modal('show');
    			return false;
    		}
    		else if($(this).text()=="Save"){
    			//console.log("COV save");
    			var file_data = $('#uploadCOV').prop('files')[0];
        		//console.log(file_data);
        		var form_data = new FormData();
        		form_data.append('file', file_data)
        		form_data.append('action', 'upload')
        		$.ajax({
                    url: 'uploadCOV.php', // point to server-side PHP script
                    dataType: 'text',  // what to expect back from the PHP script, if anything
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: form_data,
                    type: 'post',
                    success: function(php_script_response){
                    	//console.log(php_script_response);
                    	$(".close").trigger("click");
                    	jQuery('#truck-modal-edit').modal('show');
                    	var jsArray = JSON.parse(php_script_response);
                    	//$('#multi-select-vehicle2').multiSelect('select',jsArray.data);
                    	//var ids = [];
                    	//$("#filename").val(php_script_response);
                        //jQuery('#modal-1').modal('show', {backdrop: 'fade'});
                       $.each(jsArray.data,function(index,InventoryID){
                        	//$('#multi-select-vehicle2').multiSelect('addOption', { value: 42, text: 'test 42', index: 0 });
                        	//uploadedData.push(InventoryID);
    						$('#multi-select-vehicle2').multiSelect('select',InventoryID);
                       });
                        //$('#multi-select-vehicle2').multiSelect('select',ids);
                    }
        		 });
    			return false;
    		}
    	});

    	$("a.btn-danger").click(function(){
    		$("#DeleteTruckID").val($(this).parent().prev().prev().prev().text());
    	});
    	$("a.btn-secondary").click(function(){
    		count = 0;
    		var TruckId = $(this).parent().prev().prev().prev().text();
    		$("#DeleteTruckID").val(TruckId);
    		$("#TruckEditID").val(TruckId);
    		//$("#BTSetIDEdit").val($("#BTSetID"+TruckId).val());
    		$("#BTSetIDEdit").data("selectBox-selectBoxIt").selectOption($("#BTSetID"+TruckId).val());
    		//console.log("a"+TruckId+"  --"+$("#TruckEditID").val());
    		//console.log(splitStr[0]);
    		$("#TruckNameEdit").val($(this).parent().prev().prev().text());

    		//console.log($(this).parent().prev().text());
    		//console.log("Test");
    		var TruckInventoryStr = $("#TruckInventorySelected"+TruckId).val();
    		var TruckInventoryArray = TruckInventoryStr ? TruckInventoryStr.split(",") : [];
    		//Disable selected..
    		//console.log($('#multi-select-vehicle2 option[value="1"]').index());
    		//console.log($('#multi-select-vehicle2 option[value="5"]').index());
    		//console.log($('#multi-select-vehicle2 option[value="34"]').index());
    		//$("div.ms-selection").find("ul li").hide();
    		//$("div.ms-selectable").find("ul li").show();
    		var inv = [];
    		$.each(TruckInventoryArray,function(index,InventoryID){
    			count++;
    			//var InventoryIndex = $('#multi-select-vehicle2 option[value="'+InventoryID+'"]').index();
    			//$("div.ms-selectable").find("ul li:nth-child("+(parseInt(InventoryIndex)+1)+")").trigger( "click" );
    			inv.push(InventoryID);
    		});

    		$('#multi-select-vehicle2').multiSelect('deselect_all');
    		$('#multi-select-vehicle2').multiSelect('select',inv);
    	});
    });
</script>
@endpush
