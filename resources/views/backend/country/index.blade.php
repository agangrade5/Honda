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

    <ul class="nav nav-tabs right-aligned"><!-- available classes "right-aligned" -->
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
                        if($countries->Success==1){
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
		else if($(this).text()==" Delete "){ 	 console.log("e");
			//$("#stateDeleteForm").submit();
			$.ajax({
				method: "POST",
				url: "Action.php",
				data: $("#stateDeleteForm").serialize(),
			})
	  		.done(function( msg ) {
				var str = $("#StateName"+$("#CountryID").val()).val();
console.log("OLD STR ="+$("#OLDStateNameEdit").val());
var newStr = str.replace("<option value='"+$("#StateIDEdit").val()+"'>"+$("#OLDStateNameEdit").val()+"</option>","");
console.log("OLD STR ="+newStr);
$("#StateName"+$("#CountryID").val()).val(newStr);
$("#CID"+$("#CountryID").val()).trigger("click");

	  		});
		}
	});
	$("a.btn-danger").click(function(){
		$("#DeleteCountryID").val($(this).parent().prev().prev().prev().prev().text());
	});

	$("button.btn-white").click(function(){
		console.log("aa");
		$("#CID"+$("#CountryID").val()).trigger("click");
	});

	$("#multi-select-state").click(function(){
		var cID = $(this).val();
		console.log(cID[0]);
		console.log($(this).find("[value='"+cID[0]+"']").text());
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
		console.log(($("#StateName"+CID)).val());
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
			console.log(str);
		 	$("#StateName"+CountryID).val(str);
			$("#CID"+CountryID).trigger("click");

  		});
	});
});
</script>
@endpush
