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
        <li><a href="javascript:;" onclick="jQuery('#region-modal').modal('show');">
            <span class="hidden-xs">Add Model</span>
            </a>
        </li>
    </ul>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Models</h3>
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
                        <th>Model Name</th>
                        <th>Group Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="middle-align">
                    <?php
                        if(isset($models->Models) && $models->Success==1) {
                            foreach ($models->Models as $key => $model) { ?>
                    <tr>
                        <td><?php echo $model->ModelID;?></td>
                        <td><?php echo $model->ModelName?></td>
                        <td><?php echo $model->GroupName?></td>
                        <td>
                            <input type="hidden" id="GroupID<?php echo $model->ModelID;?>" value="<?php echo $model->GroupID?>">
                            <a href="javascript:;" onclick="jQuery('#region-modal-edit').modal('show');" class="btn btn-secondary btn-sm btn-icon icon-left">
                            Edit
                            </a>
                            <?php if(Auth::getUsers()->userlevel==1){ ?>
                            <a href="javascript:;" id="<?php echo $model->GroupID;?>" onclick="jQuery('#model-modal-delete').modal('show');" class="btn btn-danger btn-icon"><i class="icon-white icon-heart"></i> Delete</a>
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

<!-- Delete Modal -->
<div class="modal fade custom-width" id="model-modal-delete" tabindex="-1" role="dialog" aria-labelledby="model-modal-delete-label" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Are you sure? </h4>
            </div>
            <form method="post" action="Action.php" id="ModelDelete">
                <input type="hidden" name="DeleteModelID" id="DeleteModelID" value="">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="controller" value="model">
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-info" data-dismiss="modal">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade custom-width" id="region-modal" tabindex="-1" role="dialog" aria-labelledby="region-modal-label" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Add Model</h4>
            </div>
            <div class="modal-body">
                <form id="ModelForm" method="post" action="Action.php">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="field-1" class="control-label">Model Name</label>
                                <input type="text" class="form-control" id="field-1" name="ModelName" placeholder="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="group" class="control-label">Group</label>
                                <select class="form-control" id="VehicleModel" name="GroupID">
                                    <option value="0">None</option>
                                    <?php
                                        if(isset($groups->Success) && $groups->Success==1){
                                        	foreach ($groups->Groups as $key => $group) {
                                        		echo '<option value="'.$group->GroupID.'">'.$group->GroupName.'</option>';
                                        	}
                                        }
                                        ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="action" value="add">
                    <input type="hidden" name="controller" value="model">
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
<div class="modal fade custom-width" id="region-modal-edit" tabindex="-1" role="dialog" aria-labelledby="region-modal-edit-label" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Edit Model</h4>
            </div>
            <div class="modal-body">
                <form id="ModelEditForm" method="post" action="Action.php">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="field-1" class="control-label">Model Name</label>
                                <input type="text" class="form-control" id="ModelNameEdit" name="ModelName" placeholder="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="group" class="control-label">Group</label>
                                <select class="form-control" id="GroupIDEdit" name="GroupID">
                                    <option value="0">None</option>
                                    <?php
                                        if(isset($groups->Success) && $groups->Success==1){
                                        	foreach ($groups->Groups as $key => $group) {
                                        		echo '<option value="'.$group->GroupID.'">'.$group->GroupName.'</option>';
                                        	}
                                        }
                                        ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="controller" value="model">
                    <input type="hidden" id="ModelID" name="ModelID" value="">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-info" data-dismiss="modal">Save Changes</button>
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
    			$( "#ModelForm" ).submit();
    		}
    		else if($(this).text()=="Save Changes"){
    			$( "#ModelEditForm" ).submit();
    		}
    		else if($(this).text()=="Delete"){
    			$("#ModelDelete").submit();
    		}
    	});
    	$("a.btn-danger").click(function(){
    		$("#DeleteModelID").val($(this).parent().prev().prev().prev().text());
    	});
    	$("a.btn-secondary").click(function(){
    		var TmpGroupID = $(this).parent().prev().prev().prev().text();
    		$("#ModelNameEdit").val($(this).parent().prev().prev().text());
    		$("#ModelID").val(TmpGroupID);
    		$("#GroupIDEdit").val($("#GroupID"+TmpGroupID).val());
    	});
    });
</script>
@endpush
