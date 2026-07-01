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
                    <?php
                        if(!empty($countries) && $countries->Success==1){
                        foreach ($countries->Country as $key => $countrie) { ?>
                    <tr>
                        <td><?php echo $countrie->CountryID;?></td>
                        <td><?php echo $countrie->CountryName;?></td>
                        <td><?php echo $countrie->CountryCode;?></td>
                        <td><?php echo $countrie->Region;?></td>
                        <td>
                            <input type="hidden" id="RegionID<?php echo $countrie->CountryID;?>" value="<?php echo $countrie->RegionID;?>">
                            <a href="javascript:;" id="CID<?php echo $countrie->CountryID;?>" onclick="jQuery('#truck-modal-edit').modal('show');" class="btn btn-secondary btn-sm btn-icon icon-left">
                            Edit
                            </a>
                            <?php if(Auth::getUsers()->userlevel==1){ ?>
                            <a href="javascript:;" id="<?php echo $countrie->CountryID;?>" onclick="jQuery('#country-modal-delete').modal('show');" class="btn btn-danger btn-icon"><i class="icon-white icon-heart"></i> Delete</a>
                            <?php } ?>
                            <input type="hidden" id="StateName<?php echo $countrie->CountryID;?>" value="
                                <?php foreach ($countrie->StateName as $Statekey => $Statevalue): ?>
                                <option value='<?php echo $Statevalue->stateid;?>'><?php echo $Statevalue->statename;?></option>
                                <?php endforeach ?>
                                ">
                        </td>
                    </tr>
                    <?php }} ?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- Main Footer -->
    @include('layouts.backend.footer')
</div>
<!-- content @e -->

<!-- Delete Modal -->
<div class="modal fade custom-width" id="country-modal-delete">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Are you sure? </h4>
            </div>
            <form method="post" action="Action.php" id="CountryDelete">
                <input type="hidden" name="DeleteCountryID" id="DeleteCountryID" value="">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="controller" value="country">
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-info" data-dismiss="modal">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit State Modal -->
<div class="modal fade custom-width" id="state-modal-edit-delete">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Edit State-Province-District </h4>
            </div>
            <form method="post" action="Action.php" id="stateEditForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <label class="control-label" for="social_media">State-Province-District Name</label>
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
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="controller" value="state">
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-secondary btn-info" data-dismiss="modal">Save</button>
                    <button type="button" class="btn btn-danger btn-info" data-dismiss="modal"> Delete </button>
                </div>
            </form>
            <form method="post" action="Action.php" id="stateDeleteForm">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="controller" value="state">
                <input type="hidden" name="StateIDDelete" id="StateIDDelete" value="">
            </form>
        </div>
    </div>
</div>

<!-- Create Country Modal -->
<div class="modal fade custom-width" id="truck-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Add Country</h4>
            </div>
            <form method="post" action="Action.php" id="CountryForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <label class="control-label" for="social_media">Country Name</label>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <input type="text" class="form-control" id="CountryName" name="CountryName" placeholder="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label for="street1" class="control-label">Region</label>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <select class="form-control" id="RegionID" name="RegionID">
                                    <?php /* foreach($regions->Regions as $region){?>
                                    <option value="<?php echo $region->RegionID;?>"><?php echo $region->RegionName;?></option>
                                    <?php }  */?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="action" value="add">
                    <input type="hidden" name="controller" value="country">
            </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-info btn-secondary" data-dismiss="modal">Create</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Country Modal -->
<div class="modal fade custom-width" id="truck-modal-edit">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Edit Country</h4>
            </div>
            <div class="modal-body">
                <form method="post" action="Action.php" id="CountryFormEdit">
                    <div class="row">
                        <div class="col-md-4">
                            <label class="control-label" for="social_media">Country Name</label>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <input type="text" class="form-control" id="CountryNameEdit" name="CountryName" placeholder="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label for="street1" class="control-label">Region</label>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <select class="form-control" id="RegionIDEdit" name="RegionID">
                                    <?php /* foreach($regions->Regions as $region){?>
                                    <option value="<?php echo $region->RegionID;?>"><?php echo $region->RegionName;?></option>
                                    <?php } */ ?>
                                </select>
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
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="controller" value="country">
                    <input type="hidden" id="CountryID" name="CountryID" value="">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-info btn-secondary" data-dismiss="modal">Save Changes</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $( document ).ready(function() {
    	$("button.btn-info").click(function(){
    		if($(this).text()=="Create"){
    			$( "#CountryForm" ).submit();
    		}
    		else if($(this).text()=="Save Changes"){
    			$( "#CountryFormEdit" ).submit();
    		}
    		else if($(this).text()=="Delete"){
    			$("#CountryDelete").submit();
    		}
    		else if($(this).text()=="Save"){
    			//$("#stateEditForm").submit();

    			$.ajax({
    				method: "POST",
    				url: "Action.php",
    				data: $("#stateEditForm").serialize(),
    			})
    	  		.done(function( msg ) {
    				var str = $("#StateName"+$("#CountryID").val()).val();
                    var newStr = str.replace("<option value='"+$("#StateIDEdit").val()+"'>"+$("#OLDStateNameEdit").val()+"</option>","<option value='"+$("#StateIDEdit").val()+"'>"+msg+"</option>");
                    $("#StateName"+$("#CountryID").val()).val(newStr);
                    $("#CID"+$("#CountryID").val()).trigger("click");
    	  		});
    		}
    		else if($(this).text()==" Delete "){
                //console.log("e");
    			//$("#stateDeleteForm").submit();
    			$.ajax({
    				method: "POST",
    				url: "Action.php",
    				data: $("#stateDeleteForm").serialize(),
    			})
    	  		.done(function( msg ) {
    				var str = $("#StateName"+$("#CountryID").val()).val();
                    //console.log("OLD STR ="+$("#OLDStateNameEdit").val());
                    var newStr = str.replace("<option value='"+$("#StateIDEdit").val()+"'>"+$("#OLDStateNameEdit").val()+"</option>","");
                    //console.log("OLD STR ="+newStr);
                    $("#StateName"+$("#CountryID").val()).val(newStr);
                    $("#CID"+$("#CountryID").val()).trigger("click");
    	  		});
    		}
    	});
    	$("a.btn-danger").click(function(){
    		$("#DeleteCountryID").val($(this).parent().prev().prev().prev().prev().text());
    	});

    	$("button.btn-white").click(function(){
    		//console.log("aa");
    		$("#CID"+$("#CountryID").val()).trigger("click");
    	});

    	$("#multi-select-state").click(function(){
    		var cID = $(this).val();
    		//console.log(cID[0]);
    		//console.log($(this).find("[value='"+cID[0]+"']").text());
    		$("#StateIDEdit").val(cID[0]);
    		$("#StateIDDelete").val(cID[0]);
    		$("#OLDStateNameEdit").val($(this).find("[value='"+cID[0]+"']").text());
    		$("#StateNameEdit1").val($(this).find("[value='"+cID[0]+"']").text());
    		jQuery('#state-modal-edit-delete').modal('show');
    		$("#truck-modal-edit").find("button.close").trigger("click");

    	});

    	$("a.btn-secondary").click(function(){
    		var CID = $(this).parent().prev().prev().prev().prev().text();
    		$("#multi-select-state").empty();
    		$("#CountryNameEdit").val($(this).parent().prev().prev().prev().text());

    		$("#RegionIDEdit").val($("#RegionID"+CID).val());
    		$("#CountryID").val(CID);
    		//console.log(($("#StateName"+CID)).val());
    		$("#multi-select-state").append(($("#StateName"+CID)).val());
    	});
    	$("#add_state").click(function(){
    		CountryID = $("#CountryID").val();
    		StateName = $("#StateNameEdit").val();
    		$.ajax({
    			method: "POST",
    			url: "Action.php",
    			data: { action: "add", controller: "state" , CountryID: CountryID, StateName: StateName}
    		})
      		.done(function( msg ) {
      			//$("#multi-select-state").append();
    			var str = $("#StateName"+CountryID).val();
    			str += 	"<option value='"+msg+"'>"+StateName+"</option>";
    			//console.log(str);
    		 	$("#StateName"+CountryID).val(str);
    			$("#CID"+CountryID).trigger("click");

      		});
    	});
    });
</script>
@endpush
