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
                    <?php
                        if(!empty($dealers) && $dealers->Success==1){
                            foreach ($dealers->Dealers as $key => $dealer) { ?>
                    <tr>
                        <td><?php echo $dealer->DealerID;?></td>
                        <td><?php echo $dealer->DealerNumber;?></td>
                        <td><?php echo $dealer->DealerName;?></td>
                        <td>
                            <input type="hidden" id="DealerLocation<?php echo $dealer->DealerID;?>" value="<?php echo $dealer->DealerLocation;?>">
                            <input type="hidden" id="DealerRegion<?php echo $dealer->DealerID;?>" value="<?php echo $dealer->DealerRegion;?>">
                            <input type="hidden" id="DealerDistrict<?php echo $dealer->DealerID;?>" value="<?php echo $dealer->DealerDistrict;?>">
                            <a href="javascript:;" onclick="jQuery('#user-modal-edit').modal('show');" class="btn btn-secondary btn-sm btn-icon icon-left">
                            Edit
                            </a>
                            <?php if(Auth::getUsers()->userlevel==1){ ?>
                            <a href="javascript:;" id="<?php echo $dealer->DealerID;?>" onclick="jQuery('#dealer-modal-delete').modal('show');" class="btn btn-danger btn-icon">
                            <i class="icon-white icon-heart"></i> Delete
                            </a>
                            <?php } ?>
                        </td>
                    </tr>
                    <?php } } ?>
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
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Are you sure? </h4>
            </div>
            <form method="post" action="Action.php" id="DealerDelete">
                <input type="hidden" name="DeleteDealerID" id="DeleteDealerID" value="">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="controller" value="dealer">
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-info" data-dismiss="modal">Delete</button>
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
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Add Dealer</h4>
            </div>
            <form method="post" action="Action.php" id="DealerForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="firstName" class="control-label">Dealer Number</label>
                                <input type="text" class="form-control" id="DealerNumber" name="DealerNumber" placeholder="">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="lastName" class="control-label">Dealer Name</label>
                                <input type="text" class="form-control" id="DealerName" name="DealerName" placeholder="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="firstName" class="control-label">Dealer Location</label>
                                <input type="text" class="form-control" id="DealerLocation" name="DealerLocation" placeholder="">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="lastName" class="control-label">Dealer Region</label>
                                <input type="text" class="form-control" id="DealerRegion" name="DealerRegion" placeholder="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="firstName" class="control-label">Dealer District</label>
                                <input type="text" class="form-control" id="DealerDistrict" name="DealerDistrict" placeholder="">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-info" data-dismiss="modal">Create</button>
                </div>
                <input type="hidden" name="action" value="add">
                <input type="hidden" name="controller" value="dealer">
            </form>
        </div>
    </div>
</div>

<!-- Dealer Edit Modal -->
<div class="modal fade custom-width" id="user-modal-edit" tabindex="-1" role="dialog" aria-labelledby="user-modal-edit-label" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Edit Dealer</h4>
            </div>
            <form method="post" action="Action.php" id="DealerFormEdit">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="firstName" class="control-label">Dealer Number</label>
                                <input type="text" class="form-control" id="DealerNumberEdit" name="DealerNumber" placeholder="">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="lastName" class="control-label">Dealer Name</label>
                                <input type="text" class="form-control" id="DealerNameEdit" name="DealerName" placeholder="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="firstName" class="control-label">Dealer Location</label>
                                <input type="text" class="form-control" id="DealerLocationEdit" name="DealerLocation" placeholder="">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="lastName" class="control-label">Dealer Region</label>
                                <input type="text" class="form-control" id="DealerRegionEdit" name="DealerRegion" placeholder="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="firstName" class="control-label">Dealer District</label>
                                <input type="text" class="form-control" id="DealerDistrictEdit" name="DealerDistrict" placeholder="">
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="controller" value="dealer">
                    <input type="hidden" id="DealerID" name="DealerID" value="">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-info" data-dismiss="modal">Save Changes</button>
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
    	    //console.log($(this).text());
    		if($(this).text()=="Create"){ console.log("create");
    			$( "#DealerForm" ).submit();
    		}
    		else if($(this).text()=="Save Changes"){ console.log("edit");
    			$( "#DealerFormEdit" ).submit();
    		}
    		else if($(this).text()=="Delete"){ 	console.log("D");
    			$("#DealerDelete").submit();
    		}
    	});

    	$("a.btn-danger").click(function(){
    		$("#DeleteDealerID").val($(this).parent().prev().prev().prev().text());
    	});

    	$("a.btn-secondary").click(function(){
    		var DealerId = $(this).parent().prev().prev().prev().text();
    		//console.log($("#DealerLocation"+DealerId).val());
    		$("#DealerNumberEdit").val($(this).parent().prev().prev().text());
    		$("#DealerNameEdit").val($(this).parent().prev().text());
    		$("#DealerLocationEdit").val($("#DealerLocation"+DealerId).val());
    		$("#DealerRegionEdit").val($("#DealerRegion"+DealerId).val());
    		$("#DealerID").val(DealerId);
    		$("#DealerDistrictEdit").val($("#DealerDistrict"+DealerId).val());

    	});
    });
</script>
@endpush
