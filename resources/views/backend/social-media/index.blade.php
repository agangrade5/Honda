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
            <a href="javascript:;" onclick="jQuery('#truck-modal').modal('show');"><span class="hidden-xs">Add Social Media</span></a>
        </li>
    </ul>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Social Media</h3>
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
                        <th>Facebook</th>
                        <th>Twitter</th>
                        <th>Instagram</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="middle-align">
                    <?php
                        if(!empty($socialmedias) && $socialmedias->Success==1){
                            foreach ($socialmedias->SocialMedias as $key => $socialmedia) { ?>
                    <tr>
                        <td><?php echo $socialmedia->SocialID;?></td>
                        <td><?php echo $socialmedia->SocialName;?></td>
                        <?php $blobUrl = unserialize($socialmedia->SocialBlob);
                            foreach ($blobUrl as $keyName => $URL) {
                            		echo '<td>'.$URL.'</td>';
                            }
                            ?>
                        <td>
                            <a href="javascript:;" onclick="jQuery('#truck-modal-edit').modal('show');" class="btn btn-secondary btn-sm btn-icon icon-left">
                            Edit
                            </a>
                            <?php if(Auth::getUsers()->userlevel==1){ ?>
                            <a href="javascript:;" id="<?php echo $group->GroupID;?>" onclick="jQuery('#socialmedia-modal-delete').modal('show');" class="btn btn-danger btn-icon">
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
<div class="modal fade custom-width" id="socialmedia-modal-delete" tabindex="-1" role="dialog" aria-labelledby="socialmedia-modal-delete-label" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Are you sure? </h4>
            </div>
            <form method="post" action="Action.php" id="SocialMediaDelete">
                <input type="hidden" name="DeleteSocialMediaID" id="DeleteSocialMediaID" value="">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="controller" value="socialmedia">
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-info" data-dismiss="modal">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade custom-width" id="truck-modal" tabindex="-1" role="dialog" aria-labelledby="truck-modal-label" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Add Social Media</h4>
            </div>
            <form method="post" action="Action.php" id="SocialMediaForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-2">
                            <label class="control-label" for="social_media">Saved SM Presets</label>
                        </div>
                        <div class="col-md-9">
                            <div class="form-group">
                                <input type="text" class="form-control" id="SocialName" name="SocialName" placeholder="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="input-group">
                                <span class="input-group-addon">Facebook</span>
                                <input type="text" name="Facebook" class="form-control" placeholder="Facebook URL">
                            </div>
                        </div>
                        <br>
                        <br>
                        <div class="col-md-12">
                            <div class="input-group">
                                <span class="input-group-addon">Twitter</span>
                                <input type="text" name="Twitter" class="form-control" placeholder="Twitter URL">
                            </div>
                        </div>
                        <br>
                        <br>
                        <div class="col-md-12">
                            <div class="input-group">
                                <span class="input-group-addon">Instagram</span>
                                <input type="text" name="Instagram" class="form-control" placeholder="Instagram URL">
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="action" value="add">
                    <input type="hidden" name="controller" value="socialmedia">
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
<div class="modal fade custom-width" id="truck-modal-edit" tabindex="-1" role="dialog" aria-labelledby="truck-modal-edit-label" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Edit Social Media</h4>
            </div>
            <div class="modal-body">
                <form method="post" action="Action.php" id="SocialMediaFormEdit">
                    <div class="row">
                        <div class="col-md-2">
                            <label class="control-label" for="social_media">Saved SM Presets</label>
                        </div>
                        <div class="col-md-9">
                            <div class="form-group">
                                <select name="SocialName" class="col-sm-8 selectboxit" id="SocialNameEdit">
                                    <optgroup label="Saved Social Media Settings">
                                        <?php
                                            if(isset($socialmedias->Success) && $socialmedias->Success==1){
                                                foreach ($socialmedias->SocialMedias as $skey => $socialmedia) {
                                                	echo '<option value="'.$socialmedia->SocialID.'">'.$socialmedia->SocialName.'</option>';
                                                }
                                            }
                                            ?>
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="input-group">
                                <span class="input-group-addon">Facebook</span>
                                <input type="text" name="Facebook" id="FacebookEdit" class="form-control" placeholder="Facebook URL">
                            </div>
                        </div>
                        <br>
                        <br>
                        <div class="col-md-12">
                            <div class="input-group">
                                <span class="input-group-addon">Twitter</span>
                                <input type="text" name="Twitter" id="TwitterEdit" class="form-control" placeholder="Twitter URL">
                            </div>
                        </div>
                        <br>
                        <br>
                        <div class="col-md-12">
                            <div class="input-group">
                                <span class="input-group-addon">Instagram</span>
                                <input type="text" name="Instagram" id="InstagramEdit" class="form-control" placeholder="Instagram URL">
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="controller" value="socialmedia">
                    <input type="hidden" id="SocialIDID" name="SocialID" value="">
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
    			$( "#SocialMediaForm" ).submit();
    		}
    		else if($(this).text()=="Save Changes"){
    			$( "#SocialMediaFormEdit" ).submit();
    		}
    		else if($(this).text()=="Delete"){
    			$("#SocialMediaDelete").submit();
    		}
    	});

    	$("a.btn-danger").click(function(){
    		$("#DeleteSocialMediaID").val($(this).parent().prev().prev().prev().prev().prev().text());
    	});

    	$("a.btn-secondary").click(function(){
    		var SMID = $(this).parent().prev().prev().prev().prev().prev().text();
    		//console.log($(this).parent().prev().text());
    		$("#InstagramEdit").val($(this).parent().prev().text());
    		$("#TwitterEdit").val($(this).parent().prev().prev().text());
    		$("#FacebookEdit").val($(this).parent().prev().prev().prev().text());
    		$("#SocialNameEdit").val($(this).parent().prev().prev().prev().prev().text());
    		$("#SocialIDID").val(SMID);
    	});
    });
</script>
@endpush
