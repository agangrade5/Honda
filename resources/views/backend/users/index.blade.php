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
            <span class="hidden-xs">Add User</span>
            </a>
        </li>
    </ul>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Users</h3>
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
                        <th>Full Name</th>
                        <th>Username</th>
                        <th>Phone #</th>
                        <th>Level</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="middle-align">
                    <?php
                        if( !empty($users) && isset($users->Success) && $users->Success==1){
                            foreach ($users->Users as $key => $user) { ?>
                    <tr>
                        <td><?php echo $user->UserID;?></td>
                        <td><?php echo $user->UserFullName;?></td>
                        <td><?php echo $user->UserName;?></td>
                        <td><?php echo $user->UserPhone;?></td>
                        <td><?php echo $user->UserTypeTitle;?></td>
                        <td>
                            <input type="hidden" id="UserLevel<?php echo $user->UserID;?>" value="<?php echo $user->UserLevel;?>">
                            <input type="hidden" id="UserPass<?php echo $user->UserID;?>" value="<?php echo $user->UserPass;?>">
                            <input type="hidden" id="UserRegion<?php echo $user->UserID;?>" value='<?php echo json_encode(unserialize($user->AllowRegion));?>'>
                            <input type="hidden" id="UserEvents<?php echo $user->UserID;?>" value='<?php echo json_encode(unserialize($user->AllowEvents));?>'>
                            <input type="hidden" id="UserCountry<?php echo $user->UserID;?>" value='<?php echo json_encode(unserialize($user->AllowCountry));?>'>
                            <a href="javascript:;" onclick="jQuery('#user-modal-edit').modal('show');" class="btn btn-secondary btn-sm btn-icon icon-left">
                            Edit
                            </a>
                            <?php if(Auth::getUsers()->userlevel==1){ ?>
                            <a href="javascript:;" id="<?php echo $user->UserID;?>" onclick="jQuery('#user-modal-delete').modal('show');" class="btn btn-danger btn-icon">
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

<!-- User Delete Modal -->
<div class="modal fade custom-width" id="user-modal-delete" tabindex="-1" role="dialog" aria-labelledby="user-modal-delete-label" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Are you sure? </h4>
            </div>
            <form method="post" action="Action.php" id="UserDelete">
                <input type="hidden" name="DeleteUserID" id="DeleteUserID" value="">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="controller" value="user">
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-info" data-dismiss="modal">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- User Create Modal -->
<div class="modal fade custom-width" id="user-modal" tabindex="-1" role="dialog" aria-labelledby="user-modal-label" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Add User</h4>
            </div>
            <form method="post" action="Action.php" id="User">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="firstName" class="control-label">First Name</label>
                                <input type="text" class="form-control" id="firstName" name="FirstName" placeholder="">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="lastName" class="control-label">Last Name</label>
                                <input type="text" class="form-control" id="lastName" name="LastName" placeholder="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="username" class="control-label">Username</label>
                                <input type="text" class="form-control" id="username" name="UserName" placeholder="">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Permission Level</label>
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
                                <select class="form-control" id="sboxit-1" name="UserLevel">
                                    <option value="0">Select</option>
                                    <?php
                                        if(isset($usertypes->Success) && $usertypes->Success==1){
                                        	foreach ($usertypes->UserTypes as $key => $usertype) {
                                        		echo '<option value="'.$usertype->UserTypeId.'">'.$usertype->UserTypeTitle.'</option>';
                                        	}
                                        }
                                        ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="phone" class="control-label">Phone Number</label>
                                <input type="text" class="form-control" id="phone" name="UserPhone" placeholder="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="phone" class="control-label">User Password</label>
                                <input type="text" class="form-control" id="UserPassword" name="UserPass" placeholder="">
                            </div>
                        </div>
                    </div>
                    <!-- Added by khushbu for Add -->
                    <script type="text/javascript">
                        jQuery(document).ready(function($)
                                {
                                	//code for region
                                    $("#region").select2({
                                        placeholder: 'Choose the region.',
                                        allowClear: true
                                    }).on('select2-open', function()
                                    {
                                        // Adding Custom Scrollbar
                                        $(this).data('select2').results.addClass('overflow-hidden').perfectScrollbar();
                                    });

                                    //code for event
                                    $("#events").select2({
                                        placeholder: 'Choose the events.',
                                        allowClear: true
                                    }).on('select2-open', function()
                                    {
                                        // Adding Custom Scrollbar
                                        $(this).data('select2').results.addClass('overflow-hidden').perfectScrollbar();
                                    });

                                    //code for country
                                    $("#country").select2({
                                        placeholder: 'Choose the countrys.',
                                        allowClear: true
                                    }).on('select2-open', function()
                                    {
                                        // Adding Custom Scrollbar
                                        $(this).data('select2').results.addClass('overflow-hidden').perfectScrollbar();
                                    });

                                });
                    </script>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="phone" class="control-label">Region</label>
                                <select class="form-control" id="region" multiple name="Region[]">
                                    <option></option>
                                    <optgroup label="Region">
                                        <?php
                                            if(!empty($regions->Regions)) {
                                                foreach ($regions->Regions as $key => $region) {
                                                    echo "<option value='".$region->RegionID."'>".$region->RegionName."</option>";
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
                            <div class="form-group">
                                <label for="phone" class="control-label">Events</label>
                                <select class="form-control" id="events" multiple name="Events[]">
                                    <option></option>
                                    <optgroup label="Events">
                                        <?php
                                            if (!empty($events->Events)) {
                                                foreach ($events->Events as $key => $region) {
                                                    foreach ($region as $key=>$event ) {
                                                        echo "<option value='".$event->EventID."'>".$event->EventName."</option>";
                                                    }
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
                            <div class="form-group">
                                <label for="phone" class="control-label">Country</label>
                                <select class="form-control" id="country" multiple name="Country[]">
                                    <option></option>
                                    <optgroup label="Country">
                                        <?php
                                            if (!empty($countries->Country)) {
                                                foreach ($countries->Country as $key => $Countrys) {
                                                    echo "<option value='".$Countrys->CountryID."'>".$Countrys->CountryName."</option>";
                                                }
                                            }
                                        ?>
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                    </div>
                    <!-- Added by khushbu -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-info" data-dismiss="modal">Create</button>
                </div>
                <input type="hidden" name="action" value="add">
                <input type="hidden" name="controller" value="user">
            </form>
        </div>
    </div>
</div>

<!-- User Edit Modal -->
<div class="modal fade custom-width" id="user-modal-edit" tabindex="-1" role="dialog" aria-labelledby="user-modal-edit-label" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Edit User</h4>
            </div>
            <form method="post" action="Action.php" id="UserEdit">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="firstName2" class="control-label">First Name</label>
                                <input type="text" class="form-control" id="FirstNameEdit" name="FirstName" placeholder="">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="lastName2" class="control-label">Last Name</label>
                                <input type="text" class="form-control" id="LastNameEdit" name="LastName" placeholder="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="username2" class="control-label">Username</label>
                                <input type="text" class="form-control" id="UserNameEdit" name="UserName" placeholder="">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label">Permission Level</label>
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
                                <select class="form-control" id="UserLevelEdit" name="UserLevel">
                                    <option value="0">Select</option>
                                    <?php
                                        if(isset($usertypes->Success) && $usertypes->Success==1){
                                        	foreach ($usertypes->UserTypes as $key => $usertype) {
                                        		echo '<option value="'.$usertype->UserTypeId.'">'.$usertype->UserTypeTitle.'</option>';
                                        	}
                                        }
                                        ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="phone2" class="control-label">Phone Number</label>
                                <input type="text" class="form-control" id="UserPhoneEdit" name="UserPhone" placeholder="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="phone" class="control-label">User Password</label>
                                <input type="text" class="form-control" id="UserPasswordEdit" name="UserPass" placeholder="">
                            </div>
                        </div>
                    </div>
                    <!-- Added by khushbu -->
                    <script type="text/javascript">
                        jQuery(document).ready(function($)
                        {
                        	//code for region
                            $("#region1").select2({
                                placeholder: 'Choose the region.',
                                allowClear: true
                            }).on('select2-open', function()
                            {
                                // Adding Custom Scrollbar
                                $(this).data('select2').results.addClass('overflow-hidden').perfectScrollbar();
                            });

                            //code for event
                            $("#events1").select2({
                                placeholder: 'Choose the events.',
                                allowClear: true
                            }).on('select2-open', function()
                            {
                                // Adding Custom Scrollbar
                                $(this).data('select2').results.addClass('overflow-hidden').perfectScrollbar();
                            });

                            //code for country
                            $("#country1").select2({
                                placeholder: 'Choose the countrys.',
                                allowClear: true
                            }).on('select2-open', function()
                            {
                                // Adding Custom Scrollbar
                                $(this).data('select2').results.addClass('overflow-hidden').perfectScrollbar();
                            });

                        });
                    </script>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="phone" class="control-label">Region</label>
                                <select class="form-control" id="region1" multiple name="Region[]">
                                    <option></option>
                                    <optgroup label="Region">
                                        <?php
                                            if (!empty($regions->Regions)) {
                                                foreach ($regions->Regions as $key => $region) {
                                                    echo "<option value='".$region->RegionID."'>".$region->RegionName."</option>";
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
                            <div class="form-group">
                                <label for="phone" class="control-label">Events</label>
                                <select class="form-control" id="events1" multiple name="Events[]">
                                    <option></option>
                                    <optgroup label="Events">
                                        <?php
                                            if (!empty($events->Events)) {
                                                foreach ($events->Events as $key => $region) {
                                                    foreach ($region as $key=>$event ) {
                                                        echo "<option value='".$event->EventID."'>".$event->EventName."</option>";
                                                    }
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
                            <div class="form-group">
                                <label for="phone" class="control-label">Country</label>
                                <select class="form-control" id="country1" multiple name="Country[]">
                                    <option></option>
                                    <optgroup label="Country">
                                        <?php
                                            if (!empty($countries->Country)) {
                                                foreach ($countries->Country as $key => $Countrys) {
                                                    echo "<option value='".$Countrys->CountryID."'>".$Countrys->CountryName."</option>";
                                                }
                                            }
                                        ?>
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                    </div>
                    <!-- Added by khushbu -->
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="controller" value="user">
                    <input type="hidden" id="UserID" name="UserID" value="">
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
    		if($(this).text()=="Create"){
                //console.log("create");
    			$( "#User" ).submit();
    		}
    		else if($(this).text()=="Save Changes"){
                //console.log("edit");
    			$( "#UserEdit" ).submit();
    		}
    		else if($(this).text()=="Delete"){
    			$("#UserDelete").submit();
    		}
    	});

    	$("a.btn-danger").click(function(){
    		$("#DeleteUserID").val($(this).parent().prev().prev().prev().prev().prev().text());
    	});
    	$("a.btn-secondary").click(function(){
    		var fullname = $(this).parent().prev().prev().prev().prev().text();
    		var splitStr = fullname.split(" ");
    		var UserId = $(this).parent().prev().prev().prev().prev().prev().text();
    		$("#FirstNameEdit").val(splitStr[0]);
    		$("#LastNameEdit").val(splitStr[1]);
    		$("#UserNameEdit").val($(this).parent().prev().prev().prev().text());
    		$("#UserPhoneEdit").val($(this).parent().prev().prev().text());
    		$("#UserID").val(UserId);
    		$("#UserLevelEdit").val($("#UserLevel"+UserId).val());
    		$("#UserPasswordEdit").val($("#UserPass"+UserId).val());

    		//Manage User Events Blob.
    		var UserEventsJSON = $("#UserEvents"+UserId).val();
    		//console.log(UserEventsJSON);
    		var UserEventsArray = [];
    		$.each(JSON.parse(UserEventsJSON),function(index,EDV){
    			UserEventsArray.push({id:EDV,text:$('#events1 option[value="'+EDV+'"]').text()});
    		});
    		$("#events1").select2('data',UserEventsArray);

    		//Manage User Country Blob.
    		var UserCountryJSON = $("#UserCountry"+UserId).val();
    		var UserCountryArray = [];
    		$.each(JSON.parse(UserCountryJSON),function(index,EDV){
    			UserCountryArray.push({id:EDV,text:$('#country1 option[value="'+EDV+'"]').text()});
    		});
    		$("#country1").select2('data',UserCountryArray);

    		//Manage User Region Blob.
    		var UserRegionJSON = $("#UserRegion"+UserId).val();
    		var UserRegionArray = [];
    		$.each(JSON.parse(UserRegionJSON),function(index,EDV){
    			UserRegionArray.push({id:EDV,text:$('#region1 option[value="'+EDV+'"]').text()});
    		});
    		$("#region1").select2('data',UserRegionArray);
    	});
    });
</script>
@endpush
