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
    <div class="row">
        <div class="col-md-2">
            <div class="form-group">
                <form method="post" id="ArchivedFilterForm">
                    <script type="text/javascript">
                        function ArchiveChange(){
                        	//$("#ArchivedFilterForm").submit();
                        }
                    </script>
                    <input type="hidden" name="action" value="filter">
                    <input type="hidden" name="controller" value="archived">
                    <button type="button" onclick="ArchiveChange();" class="btn btn-info" data-dismiss="modal">Show Archived vehicles</button>
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
                    <?php
                        //if(isset($inventories->Vehicles) && $inventories->Success==1){
                        //foreach ($inventories->Vehicles as $key => $inventorie) { ?>
                    <tr>
                        <td>1</td>
                        <td>Test</td>
                        <td>Test</td>
                        <td>Test</td>
                        <td>Test</td>
                        <td>Test</td>
                        <td>Test</td>
                        <td>
                            <input type="hidden" name="" id="VehicleVIN<?php //echo $inventorie->VehicleID;?>" value="<?php //echo $inventorie->VehicleVIN;?>">
                            <input type="hidden" name="" id="EventArchive<?php //echo $inventorie->VehicleID;?>" value="<?php //echo $inventorie->VehicleArchive;?>">
                            <input type="hidden" name="" id="VehicleCOV<?php //echo $inventorie->VehicleID;?>" value="<?php //echo $inventorie->VehicleCOV;?>">
                            <input type="hidden" name="" id="VehicleTruckID<?php //echo $inventorie->VehicleID;?>" value="<?php //echo $inventorie->VehicleTruckID;?>">
                            <input type="hidden" name="" id="group<?php //echo $inventorie->VehicleID;?>" value="<?php //echo $inventorie->VehicleGroupID;?>">
                            <input type="hidden" name="" id="ModelID<?php //echo $inventorie->VehicleID;?>" value="<?php //echo $inventorie->ModelID;?>">
                            <input type="hidden" name=""  value="<?php //echo $inventorie->VehicleID;?>">
                            <a href="javascript:;" onclick="jQuery('#vehicle-modal-edit').modal('show');" class="btn btn-secondary btn-sm btn-icon icon-left">
                            Edit
                            </a>
                            <?php //if((Auth::getUsers()->userlevel==1)){ ?>
                            <a href="javascript:;" id="<?php //echo $inventorie->VehicleID;?>" onclick="jQuery('#inventory-modal-delete').modal('show');" class="btn btn-danger btn-icon">
                            <i class="icon-white icon-heart"></i> Delete
                            </a>
                            <?php //} ?>
                            <!--  <a href="#" class="btn btn-danger btn-sm btn-icon icon-left">
                                Delete
                                </a> -->
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
<div class="modal fade custom-width" id="inventory-modal-delete">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Are you sure? </h4>
            </div>
            <form method="post" action="Action.php" id="InventoryDelete">
                <input type="hidden" name="DeleteInventoryID" id="DeleteInventoryID" value="">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="controller" value="inventory">
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-info" data-dismiss="modal">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Create Modal -->
<div class="modal fade custom-width" id="vehicle-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Add Vehicle</h4>
            </div>
            <form method="post" action="Action.php" id="Inventory">
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
                                <label for="group" class="control-label">Group</label>
                                <select class="form-control" id="VehicleModel" name="VehicleModel">
                                    <option value="0">None</option>
                                    <?php
                                        /* if(isset($groups->Success) && $groups->Success==1){
                                        	foreach ($groups->Groups as $key => $groups) {
                                        		echo '<option value="'.$groups->GroupID.'">'.$groups->GroupName.'</option>';
                                        	}
                                        } */
                                        ?>
                                        <option value="1">Rebel 1</option>
                                        <option value="2">Rebel 2</option>
                                        <option value="3">Rebel 3</option>
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
                                <label class="control-label">Truck</label>
                                <script type="text/javascript">
                                    jQuery(document).ready(function($)
                                    {
                                        $("#sboxit-1").selectBoxIt().on('open', function()
                                        {
                                            // Adding Custom Scrollbar
                                            $(this).data('selectBoxSelectBoxIt').list.perfectScrollbar();
                                        });
                                    });
                                </script>
                                <select class="form-control" id="VehicleTruckID" name="VehicleTruckID">
                                    <option value="0">None</option>
                                    <?php
                                        /* if(isset($trucks->Trucks) && $trucks->Success==1){
                                        	foreach ($trucks->Trucks as $key => $truck) {
                                        		echo '<option value="'.$truck->TruckID.'">'.$truck->TruckName.'</option>';
                                        	}
                                        } */
                                        ?>
                                        <option value="1">Rebel 1</option>
                                        <option value="2">Rebel 2</option>
                                        <option value="3">Rebel 3</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="plate" class="control-label">Plate #</label>
                                <input type="text" class="form-control" id="VehicleLicPlate" name="VehicleLicPlate" placeholder="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="color" class="control-label">Vehicle VIN</label>
                                <input type="text" class="form-control" id="VehicleVIN" name="VehicleVIN" placeholder="">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="color" class="control-label">Vehicle COV</label>
                                <input type="text" class="form-control" id="VehicleCOV" name="VehicleCOV" placeholder="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="group" class="control-label">Model</label>
                                <select class="form-control" id="ModelID" name="ModelID">
                                    <option value="0">None</option>
                                    <?php
                                        /* if(isset($models->Models) && $models->Success==1){
                                        	foreach ($models->Models as $key => $model) {
                                        		echo '<option value="'.$model->ModelID.'">'.$model->ModelName.'</option>';
                                        	}
                                        } */
                                        ?>
                                        <option value="1">Rebel 1</option>
                                        <option value="2">Rebel 2</option>
                                        <option value="3">Rebel 3</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="group" class="control-label">Type</label>
                                <select class="form-control" name="VehicleType">
                                    <option value="display">Display</option>
                                    <option value="demo">Demo</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="color" class="control-label">Archive</label>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <div class="form-group">
                                <input type="radio" value="1" placeholder="" name="EventArchive" id="EventArchive"> Yes
                                <input type="radio" value="0" placeholder="" name="EventArchive" id="EventArchive1"> No
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="action" value="add">
                <input type="hidden" name="controller" value="inventory">
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-info" data-dismiss="modal">Create</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Edit Modal -->
<div class="modal fade custom-width" id="vehicle-modal-edit">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Edit Vehicle</h4>
            </div>
            <form method="post" action="Action.php" id="InventoryEdit">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nickname2" class="control-label">Nickname</label>
                                <input type="text" class="form-control" id="VehicleNickNameEdit" name="VehicleNickName" placeholder="">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="group2" class="control-label">Group</label>
                                <select class="form-control" id="VehicleModelEdit" name="VehicleModel">
                                    <option value="0">None</option>
                                    <?php
                                       /*  if(isset($groups1->Success) && $groups1->Success==1){
                                        	foreach ($groups1->Groups as $key => $groups1) {
                                        		echo '<option value="'.$groups1->GroupID.'">'.$groups1->GroupName.'</option>';
                                        	}
                                        } */
                                        ?>
                                        <option value="1">Rebel 1</option>
                                        <option value="2">Rebel 2</option>
                                        <option value="3">Rebel 3</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="color2" class="control-label">Color</label>
                                <input type="text" class="form-control" id="VehicleColorEdit" name="VehicleColor" placeholder="">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Truck</label>
                                <script type="text/javascript">
                                    jQuery(document).ready(function($)
                                    {
                                        $("#sboxit-2").selectBoxIt().on('open', function()
                                        {
                                            // Adding Custom Scrollbar
                                            $(this).data('selectBoxSelectBoxIt').list.perfectScrollbar();
                                        });
                                    });
                                </script>
                                <select class="form-control" id="VehicleTruckIDEdit" name="VehicleTruckID">
                                    <option value="0">None</option>
                                    <?php
                                        /* if(isset($trucks->Success) && $trucks->Success==1){
                                        	foreach ($trucks->Trucks as $key => $truck) {
                                        		echo '<option value="'.$truck->TruckID.'">'.$truck->TruckName.'</option>';
                                        	}
                                        } */
                                        ?>
                                        <option value="1">Rebel 1</option>
                                        <option value="2">Rebel 2</option>
                                        <option value="3">Rebel 3</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="plate2" class="control-label">Plate #</label>
                                <input type="text" class="form-control" id="VehicleLicPlateEdit" name="VehicleLicPlate" placeholder="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="color" class="control-label">Vehicle VIN</label>
                                <input type="text" class="form-control" id="VehicleVINEdit" name="VehicleVIN" placeholder="">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="color" class="control-label">Vehicle COV</label>
                                <input type="text" class="form-control" id="VehicleCOVEdit" name="VehicleCOV" placeholder="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="group" class="control-label">Model</label>
                                <select class="form-control" id="ModelIDEdit" name="ModelID">
                                    <option value="0">None</option>
                                    <?php
                                        /* if(isset($models->Success) && $models->Success==1){
                                        	foreach ($models->Models as $key => $model) {
                                        		echo '<option value="'.$model->ModelID.'">'.$model->ModelName.'</option>';
                                        	}
                                        } */
                                        ?>
                                        <option value="1">Rebel 1</option>
                                        <option value="2">Rebel 2</option>
                                        <option value="3">Rebel 3</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="group" class="control-label">Type</label>
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
                                <label for="color" class="control-label">Archive</label>
                            </div>
                        </div>
                        <div class="col-md-10">
                            <div class="form-group">
                                <input type="radio" value="1" placeholder="" name="EventArchive" id="EventArchiveEdit"> Yes
                                <input type="radio" value="0" placeholder="" name="EventArchive" id="EventArchiveEdit1"> No
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-info" data-dismiss="modal">Save Changes</button>
                </div>
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="controller" value="inventory">
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
    		if($(this).text()=="Create"){ console.log("create");
    			$( "#Inventory" ).submit();
    		}
    		else if($(this).text()=="Save Changes"){ console.log("edit");
    			$( "#InventoryEdit" ).submit();
    		}
    		else if($(this).text()=="Delete"){
    			$("#InventoryDelete").submit();
    		}
    	});

    	$("a.btn-danger").click(function(){
    		$("#DeleteInventoryID").val($(this).prev().prev().val());//$(this).parent().prev().prev().prev().prev().prev().prev().text()
    	});

    	$("a.btn-secondary").click(function(){

    		var VId = $(this).prev().val();//$(this).parent().prev().prev().prev().prev().prev().prev().prev().text();
    		$("#VehicleNickNameEdit").val($(this).parent().prev().prev().prev().prev().prev().prev().text());
    		$("#VehicleModelEdit").val($("#group"+VId).val());
    		$("#VehicleColorEdit").val($(this).parent().prev().prev().prev().prev().text());
    		$("#VehicleTruckIDEdit").val($("#VehicleTruckID"+VId).val());
    		$("#VehicleID").val(VId);
    		$("#ModelIDEdit").val($("#ModelID"+VId).val());
    		$("#VehicleLicPlateEdit").val($(this).parent().prev().prev().text());
    		$("#VehicleVINEdit").val($("#VehicleVIN"+VId).val());
    		$("#VehicleCOVEdit").val($("#VehicleCOV"+VId).val());
    		$("#VehicleTypeEdit").val($(this).parent().prev().text().toLowerCase());
    		if(parseInt($("#EventArchive"+VId).val())==1){ console.log(VId);
    			$("#EventArchiveEdit").attr('checked', true);
    			$("#EventArchiveEdit1").removeAttr('checked');
    		}
    		else { console.log("else");
    			$("#EventArchiveEdit").removeAttr('checked');
    			$("#EventArchiveEdit1").attr('checked', true);
    		}

    	});
    });
</script>
@endpush
