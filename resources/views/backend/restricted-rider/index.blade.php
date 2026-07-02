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
            <a href="javascript:;" onclick="jQuery('#restrictedriders-modal').modal('show');"><span class="hidden-xs">Add Rider</span></a>
        </li>
    </ul>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Restricted Riders</h3>
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
                        <th>Card/DL</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Reason</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="middle-align">
                    <?php
                        if(isset($restrictedriders->Success) && $restrictedriders->Success==1){
                        foreach ($restrictedriders->RestrictedRiders as $key => $restrictedrider) { ?>
                    <tr>
                        <td><?php echo $restrictedrider->RestrictID;?></td>
                        <td><?php if(isset($restrictedrider->RestrictLic)) echo $restrictedrider->RestrictLic;?></td>
                        <td><?php if(isset($restrictedrider->RiderFirstName)) echo $restrictedrider->RiderFirstName;?></td>
                        <td><?php if(isset($restrictedrider->RiderLastName)) echo $restrictedrider->RiderLastName;?></td>
                        <td><?php echo $restrictedrider->RestrictComment;?></td>
                        <td>
                            <a href="javascript:;" onclick="jQuery('#restrictedriders-modal-edit').modal('show');" class="btn btn-secondary btn-sm btn-icon icon-left">
                            Edit
                            </a>
                            <?php if(Auth::getUsers()->userlevel==1 || Auth::getUsers()->userlevel==2){ ?>
                            <a href="javascript:;" id="<?php echo $restrictedrider->RestrictID;?>" onclick="jQuery('#restrictedriders-modal-delete').modal('show');" class="btn btn-danger btn-icon">
                            <i class="icon-white icon-heart"></i> Delete
                            </a>
                            <?php } ?>
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
<div class="modal fade custom-width" id="restrictedriders-modal-delete" tabindex="-1" role="dialog" aria-labelledby="restrictedriders-modal-delete-label" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Are you sure? </h4>
            </div>
            <form method="post" action="Action.php" id="RestrictedRidersDelete">
                <input type="hidden" name="RestrictID" id="DeleteRestrictID" value="">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="controller" value="restrictedriders">
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-info" data-dismiss="modal">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade custom-width" id="restrictedriders-modal" tabindex="-1" role="dialog" aria-labelledby="restrictedriders-modal-label" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Add Restricted Riders</h4>
            </div>
            <form method="post" action="Action.php" id="RestrictedRidersForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-3">
                            <label class="control-label" for="social_media">Restrict Lic</label>
                        </div>
                        <div class="col-md-9">
                            <div class="form-group">
                                <input type="text" class="form-control" id="RestrictLic" name="RestrictLic" placeholder="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <label class="control-label" for="social_media">Restrict Comment</label>
                        </div>
                        <div class="col-md-9">
                            <div class="form-group">
                                <textarea class="form-control" rows="5" id="RestrictComment" name="RestrictComment"></textarea>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="action" value="add">
                    <input type="hidden" name="controller" value="restrictedriders">
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
<div class="modal fade custom-width" id="restrictedriders-modal-edit" tabindex="-1" role="dialog" aria-labelledby="restrictedriders-modal-edit-label" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Edit Restricted Riders</h4>
            </div>
            <div class="modal-body">
                <form method="post" action="Action.php" id="RestrictRiderFormEdit">
                    <div class="row">
                        <div class="col-md-3">
                            <label class="control-label" for="social_media">Restrict Lic</label>
                        </div>
                        <div class="col-md-9">
                            <div class="form-group">
                                <input type="text" class="form-control" id="RestrictLicEdit" name="RestrictLic" placeholder="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <label class="control-label" for="social_media">Restrict Comment</label>
                        </div>
                        <div class="col-md-9">
                            <div class="form-group">
                                <textarea class="form-control" rows="5" id="RestrictCommentEdit" name="RestrictComment"></textarea>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="controller" value="restrictedriders">
                    <input type="hidden" id="RestrictID" name="RestrictID" value="">
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
    			$( "#RestrictedRidersForm" ).submit();
    		}
    		else if($(this).text()=="Save Changes"){
    			$( "#RestrictRiderFormEdit" ).submit();
    		}
    		else if($(this).text()=="Delete"){
    			$("#RestrictedRidersDelete").submit();
    		}
    	});

    	$("a.btn-danger").click(function(){
    		$("#DeleteRestrictID").val($(this).parent().prev().prev().prev().prev().prev().text());
    	});

    	$("a.btn-secondary").click(function(){
    		var RRID = $(this).parent().prev().prev().prev().prev().prev().text();
    		//console.log(RRID);
    		$("#RestrictLicEdit").val($(this).parent().prev().prev().prev().prev().text());
    		$("#RestrictCommentEdit").val($(this).parent().prev().text());
    		$("#RestrictID").val(RRID);

    	});
    });
</script>
@endpush
