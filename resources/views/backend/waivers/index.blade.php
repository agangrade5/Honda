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
        <li><a href="javascript:;" onclick="jQuery('#waiver-modal').modal('show');">
            <span class="hidden-xs">Add Waiver</span>
            </a>
        </li>
    </ul>
    <div class="panel panel-default">
        <form method="post" action="Action.php" id="WaiverEditForm">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="control-label" for="waiver">Select a Waiver to edit</label>
                        <select name="WaiverID" class="selectboxit" id="WaiverNameEdit">
                            <optgroup label="Waivers">
                                <option value="">Select an Waiver</option>
                                <?php $hidden_html = '';
                                    if(isset($waivers->Success) && $waivers->Success==1){
                                    	foreach ($waivers->Waivers as $key => $waiver) {
                                    		$hidden_html .= '<div style="display:none;" id="WaiverHTML'.$waiver->WaiverID.'" > '.$waiver->WaiverHTML.'</div>';
                                    		echo '<option value="'.$waiver->WaiverID."!$!".$waiver->WaiverName.'">'.$waiver->WaiverName.'</option>';
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
                        <textarea class="form-control ckeditor" name="WaiverHTML1" rows="10">   </textarea>
                        <?php echo $hidden_html; ?>
                        <input type="hidden" name="action" value="edit">
                        <input type="hidden" name="controller" value="waiver">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <button type="button" class="btn btn-info" data-dismiss="modal">Save Changes</button>
                        <?php /* if(Auth::getUsers()->userlevel==1){ ?>
                        <button type="button" class="btn btn-danger btn-info" onclick="openModel();" data-dismiss="modal">Delete</button>
                        <?php } */ ?>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <!-- Main Footer -->
    @include('layouts.backend.footer')
</div>
<!-- content @e -->

<!-- Delete Modal -->
<div class="modal fade custom-width" id="waiver-modal-delete" tabindex="-1" role="dialog" aria-labelledby="waiver-modal-delete-label" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Are you sure? </h4>
            </div>
            <form method="POST" action="Action.php" id="WaiverDelete">
                <input type="hidden" name="DeleteWaiverID" id="DeleteWaiverID" value="">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="controller" value="waiver">
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-info" data-dismiss="modal">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade custom-width" id="waiver-modal" tabindex="-1" role="dialog" aria-labelledby="waiver-modal-label" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Add Waiver</h4>
            </div>
            <form method="post" action="Action.php" id="WaiverForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="field-1" class="control-label">Waiver Name</label>
                                <input name="WaiverName" type="text" class="form-control" id="field-1" placeholder="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <textarea name="WaiverHTML" class="form-control ckeditor" rows="10">
                                </textarea>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="action" value="add">
                    <input type="hidden" name="controller" value="waiver">
            </form>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-info" data-dismiss="modal">Create</button>
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
    			$( "#WaiverForm" ).submit();
    		}
    		else if($(this).text()=="Save Changes"){
    			$( "#WaiverEditForm" ).submit();
    		}
    		else if($(this).text()=="Delete" && (!($(this).hasClass('btn-danger')))){
    			$("#WaiverDelete").submit();
    		}
    	});
    	$("#WaiverNameEdit").change(function(){
    		$("#TemplateBlob1").html();
    		var curentVal = $(this).val();
    		var strArray = curentVal.split("!$!");
    		$("#DeleteWaiverID").val(strArray[0]);
    		$("#cke_WaiverHTML1 .cke_wysiwyg_frame").contents().find("body").html($("#WaiverHTML"+strArray[0]).html());
    	});
    });
</script>
@endpush
