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
            <span class="hidden-xs">Add SMS Template</span>
            </a>
        </li>
    </ul>
    <div class="panel panel-default">
        <form method="post" action="Action.php" id="EmailTemplateEditForm">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="control-label" for="email">Select an sms template to edit</label>
                        <select class="selectboxit" id="EmailTemplateSubjEdit" name="EmailTemplateID">
                            <optgroup label="SMS Templates">
                                <!--  <option>General Email Template</option>-->
                                <option value="">Select an SMS Template</option>
                                <?php $hidden_html = '';
                                    if(isset($smstemplates->Success) && $smstemplates->Success==1){
                                        foreach ($smstemplates->SMSTemplates as $key => $emailtemplate) {
                                            $hidden_html .= "<input type='hidden' id='TemplateBlobTmp".$emailtemplate->TemplateID."' value='".$emailtemplate->TemplateBlob."'/>";
                                            $hidden_html .= "<input type='hidden' id='TemplateSub".$emailtemplate->TemplateID."' value='".$emailtemplate->SmsTemplateSubj."'/>";
                                            echo '<option value="'.$emailtemplate->TemplateID.'!$!'.$emailtemplate->SmsSubj.'">'.$emailtemplate->SmsSubj.'</option>';
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
                    <div class="form-group">
                        <textarea maxlength="160" class="form-control smsTextarea" name="TemplateBlob1" id="TemplateBlob1" rows="10"></textarea>
                        <spam style="font-weight: bold; color: red;">
                            <spam class="EditSMSLIMITERRROR">160</spam>
                            Character(s) Remaining
                        </spam>
                    </div>
                    <div class="form-group">
                        <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-info" data-dismiss="modal">Save Changes</button>
                        <?php /* if(Auth::getUsers()->userlevel==1){ ?>
                        <button type="button" class="btn btn-danger btn-info" onclick="openModel();" data-dismiss="modal">Delete</button>
                        <?php }  */?>
                    </div>
                    <?php echo $hidden_html; ?>
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="controller" value="smstemplate">
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
                <input type="hidden" name="controller" value="smstemplate">
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
                <h4 class="modal-title"><b>Error - This sms template is not setup properly. It is missing the dynamic link.<b> </h4>
            </div>
        </div>
    </div>
</div>

<!-- Test SMS Template Modal -->
<div class="modal fade custom-width" id="sendemail-template-modal" tabindex="-1" role="dialog" aria-labelledby="sendemail-template-modal-label" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Test SMS Template</h4>
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
                    <input type="hidden" name="controller" value="smstemplate">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-info" data-dismiss="modal">Send</button>
            </div>
        </div>
    </div>
</div>

<!-- SMS Template Create Modal -->
<div class="modal fade custom-width" id="template-modal" tabindex="-1" role="dialog" aria-labelledby="template-modal-label" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Add SMS Template</h4>
            </div>
            <div class="modal-body">
                <form method="post" action="Action.php" id="EmailTemplateForm">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="field-1" class="control-label">SMS Template Name</label>
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
                                <textarea maxlength="160" class="form-control addSmsTextarea" rows="10" name="TemplateBlob"></textarea>
                                <spam style="font-weight: bold; color: red;">
                                    <spam class="AddSMSLIMITERRROR">160</spam>
                                    Character(s) Remaining
                                </spam>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="action" value="add">
                    <input type="hidden" name="controller" value="smstemplate">
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
    		else if($(this).text()=="Send"){
    			$( "#EmailTemplateSendTestEmailForm" ).submit();
    		}
    		else if($(this).text()=="Save Changes"){
    		    $( "#EmailTemplateEditForm" ).submit();
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
    		var maxLength = 160;
    		var $smsTextarea = $("#TemplateBlobTmp"+strArray[0]).val();
    		var textlen = maxLength - $smsTextarea.length;
    	 	$('.EditSMSLIMITERRROR').text(textlen);
    		$("#TemplateBlob1").val($("#TemplateBlobTmp"+strArray[0]).val());
    	});
    });
    function openModel(){
    	if($("#EmailTemplateSubjEdit").val()!=""){
    		jQuery('#emailtemplate-modal-delete').modal('show');
    	}
    	else {
    		alert("Please select SMS Template that you want to delete!");
    	}
    }
    var maxLength = 160;
    $('textarea.smsTextarea').keyup(function() {
        var textlen = maxLength - $(this).val().length;
        $('.EditSMSLIMITERRROR').text(textlen);
    });
    $('textarea.addSmsTextarea').keyup(function() {
        var textlen = maxLength - $(this).val().length;
        $('.AddSMSLIMITERRROR').text(textlen);
    });
</script>
@endpush
