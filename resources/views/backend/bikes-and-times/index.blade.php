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
            <span class="hidden-xs">Create</span>
            </a>
        </li>
    </ul>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Bikes and Times</h3>
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
                        <th>Set Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="middle-align">
                    <?php
                        if( isset($btSets) && !empty($btSets) && $btSets->Success==1){
                            foreach ($btSets->BTSets as $key => $btSet) { ?>
                    <tr>
                        <td><?php echo $btSet->BTSetID;?></td>
                        <td><?php echo $btSet->BTSetName?></td>
                        <td>
                            <a href="EditListModel.php?id=<?php echo $btSet->BTSetID;?>" class="btn btn-secondary btn-sm btn-icon icon-left">
                            Edit
                            </a>
                            <a href="javascript:;" id="<?php echo $btSet->RegionID;?>" onclick="jQuery('#region-modal-delete').modal('show');" class="btn btn-danger btn-icon"><i class="icon-white icon-heart"></i> Delete</a>
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
<div class="modal fade custom-width" id="region-modal-delete" tabindex="-1" role="dialog" aria-labelledby="region-modal-delete-label" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Are you sure you want to delete this time set? </h4>
            </div>
            <form method="post" action="Action.php" id="RegionDelete">
                <input type="hidden" name="DeleteBTSetID" id="DeleteBTSetID" value="">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="controller" value="btset">
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-info">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade custom-width" id="region-modal" tabindex="-1" role="dialog" aria-labelledby="region-modal-label" data-backdrop="static" data-keyboard="false" >
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Add Set</h4>
            </div>
            <div class="modal-body">
                <form id="Region" method="post" action="Action.php">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="field-1" class="control-label">Set Name</label>
                                <input type="text" class="form-control" id="BTSetName" name="BTSetName" placeholder="">
                                <input type="hidden" name="action" value="add">
                                <input type="hidden" name="controller" value="btset">
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
<div class="modal fade custom-width" id="region-modal-edit" tabindex="-1" role="dialog" aria-labelledby="region-modal-edit-label" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Edit Set</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <form id="RegionEdit" method="post" action="Action.php">
                            <div class="form-group">
                                <label for="field-1" class="control-label">Set Name</label>
                                <input type="hidden" name="action" value="edit">
                                <input type="hidden" name="controller" value="btset">
                                <input type="hidden" id="BTSetID" name="BTSetID" value="">
                                <input type="text" class="form-control" id="BTSetNameEdit" name="BTSetName" placeholder="">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-info">Save Changes</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    /* $('.btnCancel').on('click', function () {
        this.blur();
        $('#region-modal').modal('hide');
    }); */
    $( document ).ready(function() {
    	$("button.btn-info").click(function(){
    		if($(this).text()=="Create"){
    			$( "#Region" ).submit();
    		}
    		else if($(this).text()=="Save Changes"){
    			$( "#RegionEdit" ).submit();
    		}
    		else if($(this).text()=="Delete"){
    			$("#RegionDelete").submit();
    		}
    	});

    	$("a.btn-danger").click(function(){
    		$("#DeleteBTSetID").val($(this).parent().prev().prev().text());
    	});

    	$("a.btn-secondary").click(function(){
    		$("#BTSetNameEdit").val($(this).parent().prev().text());
    		$("#BTSetID").val($(this).parent().prev().prev().text());
    	});
    });
</script>
@endpush
