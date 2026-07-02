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
        <li><a href="javascript:;" onclick="jQuery('#template-modal').modal('show');">
            <span class="hidden-xs">Add Email Template</span>
            </a>
        </li>
    </ul>
    <div class="panel panel-default">
        <form method="post" action="Action.php" id="EmailTemplateEditForm">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="control-label" for="email">Select an email template to edit</label>
                        <select class="selectboxit" id="EmailTemplateSubjEdit" name="EmailTemplateID">
                            <optgroup label="Email Templates">
                                <!--  <option>General Email Template</option>-->
                                <option value="">Select an Email Template</option>
                                <?php $hidden_html = '';
                                    if(isset($emailtemplates->Success) && $emailtemplates->Success==1){
                                    	foreach ($emailtemplates->EmailTemplates as $key => $emailtemplate) {
                                    		$hidden_html .= "<input type='hidden' id='TemplateBlobTmp".$emailtemplate->TemplateID."' value='".$emailtemplate->TemplateBlob."'/>";
                                    		$hidden_html .= "<input type='hidden' id='TemplateSub".$emailtemplate->TemplateID."' value='".$emailtemplate->EmailTemplateSubj."'/>";
                                    		echo '<option value="'.$emailtemplate->TemplateID.'!$!'.$emailtemplate->EmailSubj.'">'.$emailtemplate->EmailSubj.'</option>';
                                    	}
                                    }
                                    ?>
                            </optgroup>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="nickname2">Subject</label>
                        <input id="EmailTemplateSub" class="form-control" type="text" placeholder="" name="EmailTemplateSub">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group" style="text-align:right;">
                        <button type="button" class="btn btn-info" data-dismiss="modal">Send Test Email</button>
                    </div>
                    <div class="form-group">
                        <textarea class="form-control ckeditor" name="TemplateBlob1" id="TemplateBlob1" rows="10"></textarea>
                        <?php echo $hidden_html; ?>
                        <input type="hidden" name="action" value="edit">
                        <input type="hidden" name="controller" value="emailtemplate">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
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

<!-- Delete Email Template Modal -->
<div class="modal fade custom-width" id="emailtemplate-modal-delete" tabindex="-1" role="dialog" aria-labelledby="emailtemplate-modal-delete-label" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Are you sure? </h4>
            </div>
            <form method="post" action="Action.php" id="EmailTemplateDelete">
                <input type="hidden" name="DeleteEmailTemplateID" id="DeleteEmailTemplateID" value="">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="controller" value="emailtemplate">
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-info" data-dismiss="modal">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Error Modal -->
<div class="modal fade custom-width" id="emailtemplate-modal-error" tabindex="-1" role="dialog" aria-labelledby="emailtemplate-modal-error-label" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><b>Error - This email template is not setup properly. It is missing the dynamic link.<b> </h4>
            </div>
        </div>
    </div>
</div>

<!-- Test Email Template Modal -->
<div class="modal fade custom-width" id="sendemail-template-modal" tabindex="-1" role="dialog" aria-labelledby="sendemail-template-modal-label" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Test Email Template</h4>
            </div>
            <div class="modal-body">
                <form method="post" action="Action.php" id="EmailTemplateSendTestEmailForm">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="control-label" for="nickname2">Email Address</label>
                                <input id="EmailSubject" class="form-control" type="text" placeholder="" name="EmailSubject">
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="EmailTemplateSubject" id="EmailTemplateSubject" value="">
                    <input type="hidden" name="template" value="" id="TestSendEmailTemplate">
                    <input type="hidden" name="action" value="send">
                    <input type="hidden" name="controller" value="emailtemplate">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-info" data-dismiss="modal">Send</button>
            </div>
        </div>
    </div>
</div>

<!-- Email Template Create Modal -->
<div class="modal fade custom-width" id="template-modal" tabindex="-1" role="dialog" aria-labelledby="template-modal-label" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Add Email Template</h4>
            </div>
            <div class="modal-body">
                <form method="post" action="Action.php" id="EmailTemplateForm">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="field-1" class="control-label">Email Template Name</label>
                                <input type="text" name="EmailTemplateSubj" class="form-control" id="field-1" placeholder="">
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="nickname2">Subject</label>
                                <input id="EmailSubject" class="form-control" type="text" placeholder="" name="EmailSubject">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <textarea class="form-control ckeditor" rows="10" name="TemplateBlob"></textarea>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="action" value="add">
                    <input type="hidden" name="controller" value="emailtemplate">
                </form>
            </div>
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
    			$( "#EmailTemplateForm" ).submit();
    		}
    		else if($(this).text()=="Send Test Email"){
    			var stringText = CKEDITOR.instances.TemplateBlob1.getData();
    			$("#TestSendEmailTemplate").val(stringText);
    			$("#EmailTemplateSubject").val($("#EmailTemplateSubjEdit").val());
    			jQuery('#sendemail-template-modal').modal('show');
    		}
    		else if($(this).text()=="Send"){
    			$( "#EmailTemplateSendTestEmailForm" ).submit();
    		}
    		else if($(this).text()=="Save Changes"){
    			var stringText = CKEDITOR.instances.TemplateBlob1.getData();
    			var textArea = document.createElement('textarea');
    		    textArea.innerHTML = stringText;
    		    var searchStr = textArea.value;
    		    if(searchStr.toLowerCase().indexOf('<a href="http://~prsurveyphoto~">')>=0){
    		    	$( "#EmailTemplateEditForm" ).submit();
    		    }
    		    else {
    		    	jQuery('#emailtemplate-modal-error').modal('show');
    		    	return false;
    		    }
    		}
    		else if($(this).text()=="Delete" && $(this).attr('class')=='btn btn-info'){
    			$("#EmailTemplateDelete").submit();
    		}
    	});
    	$("#EmailTemplateSubjEdit").change(function(){
    		$("#TemplateBlob1").html();
    		var curentVal = $(this).val();
    		var strArray = curentVal.split("!$!");
    		$("#DeleteEmailTemplateID").val(strArray[0]);
    		$("#EmailTemplateSub").val($("#TemplateSub"+strArray[0]).val());
    		$("#cke_TemplateBlob1 .cke_wysiwyg_frame").contents().find("body").html($("#TemplateBlobTmp"+strArray[0]).val());
    	});
    });
    function openModel(){
    	if($("#EmailTemplateSubjEdit").val()!=""){
    		jQuery('#emailtemplate-modal-delete').modal('show');
    	}
    	else {
    		alert("Please select Email Template that you want to delete!");
    	}
    }
</script>
@endpush
