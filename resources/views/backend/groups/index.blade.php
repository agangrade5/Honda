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
            <span class="hidden-xs">Add Groups</span>
            </a>
        </li>
    </ul>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Groups</h3>
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
                        <th>Group Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="middle-align">
                    <?php
                        if(isset($groups->Groups) && $groups->Success==1){
                            foreach ($groups->Groups as $key => $group) { ?>
                    <tr>
                        <td><?php echo $group->GroupID;?></td>
                        <td><?php echo $group->GroupName?></td>
                        <td>
                            <a href="javascript:;" onclick="jQuery('#region-modal-edit').modal('show');" class="btn btn-secondary btn-sm btn-icon icon-left">
                            Edit
                            </a>
                            <?php if(Auth::getUsers()->userlevel==1){ ?>
                            <a href="javascript:;" id="<?php echo $group->GroupID;?>" onclick="jQuery('#group-modal-delete').modal('show');" class="btn btn-danger btn-icon">
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

<!-- Group Delete Modal -->
<div class="modal fade custom-width" id="group-modal-delete" tabindex="-1" role="dialog" aria-labelledby="group-modal-delete-label" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Are you sure? </h4>
            </div>
            <form method="post" action="Action.php" id="GroupDelete">
                <input type="hidden" name="DeleteGroupID" id="DeleteGroupID" value="">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="controller" value="group">
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-info" data-dismiss="modal">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Region Create Modal -->
<div class="modal fade custom-width" id="region-modal" tabindex="-1" role="dialog" aria-labelledby="region-modal-label" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Add Group</h4>
            </div>
            <div class="modal-body">
                <form id="Group" method="post" action="Action.php">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="field-1" class="control-label">Group Name</label>
                                <input type="text" class="form-control" id="field-1" name="GroupName" placeholder="">
                                <input type="hidden" name="action" value="add">
                                <input type="hidden" name="controller" value="group">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-info" data-dismiss="modal">Create</button>
            </div>
        </div>
    </div>
</div>

<!-- Region Edit Modal -->
<div class="modal fade custom-width" id="region-modal-edit" tabindex="-1" role="dialog" aria-labelledby="region-modal-edit-label" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Edit Group</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <form id="GroupEdit" method="post" action="Action.php">
                            <div class="form-group">
                                <label for="field-1" class="control-label">Group Name</label>
                                <input type="hidden" name="action" value="edit">
                                <input type="hidden" name="controller" value="group">
                                <input type="hidden" id="GroupID" name="GroupID" value="">
                                <input type="text" class="form-control" id="GroupNameEdit" name="GroupName" placeholder="">
                            </div>
                        </form>
                    </div>
                </div>
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
    	$("a.btn-danger").click(function(){
    		$("#DeleteGroupID").val($(this).parent().prev().prev().text());
    	});
    	$("button.btn-info").click(function(){
    		if($(this).text()=="Create"){
    			$( "#Group" ).submit();
    		}
    		else if($(this).text()=="Save Changes"){
    			$( "#GroupEdit" ).submit();
    		}
    		else if($(this).text()=="Delete"){
    			$("#GroupDelete").submit();
    		}
    		//$( "#Region" ).submit();
    	});

    	$("a.btn-secondary").click(function(){
    		//console.log();
    		$("#GroupNameEdit").val($(this).parent().prev().text());
    		$("#GroupID").val($(this).parent().prev().prev().text());
    	});
    });
</script>
@endpush
