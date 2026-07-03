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
    <div class="panel panel-default">
        <form method="post" action="Action.php" id="EventEditForm">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="control-label" for="Event">Select a Event to edit</label>
                        <select name="EventID" class="selectboxit select2" id="EventNameEdit">
                            <optgroup label="Events">
                                <option value="">Select an Event</option>
                                <?php $hidden_html = '';
                                    if(isset($Events->Success) && $Events->Success==1){
                                    	foreach ($Events->Events as $keys => $EventData) {
                                            foreach ($EventData as $key => $Event) {
                                                $hidden_html .= '<div style="display:none;" id="EventHTML'.$Event->EventID.'" > '.$Event->EventHTML.'</div>';
                                                echo '<option value="'.$Event->EventID.'">'.$Event->EventID.' '.$Event->EventName.'</option>';
                                            }
                                    	}
                                    }
                                ?>
                            </optgroup>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="TemplateBlob4">Header HTML Content</label>
                        <textarea class="form-control ckeditor" id="TemplateBlob4" name="EventHTML4" rows="10" contenteditable="false">   </textarea>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="TemplateBlob1">Quantity Form</label>
                        <textarea class="form-control ckeditor" id="TemplateBlob1" name="EventHTML1" rows="10" contenteditable="false">   </textarea>

                    </div>
                    <div class="form-group">
                        <label class="control-label" for="TemplateBlob2">Info Content</label>
                        <textarea class="form-control ckeditor" id="TemplateBlob2" name="EventHTML2" rows="10" contenteditable="false">   </textarea>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="TemplateBlob3">Success Content</label>
                        <textarea class="form-control ckeditor" id="TemplateBlob3" name="EventHTML3" rows="10" contenteditable="false">   </textarea>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="TemplateBlob5">Error Content</label>
                        <textarea class="form-control ckeditor" id="TemplateBlob5" name="EventHTML5" rows="10" >   </textarea>
                    </div>
                    <div class="form-group">
                        <button type="button" class="btn btn-info" data-dismiss="modal">Save Changes</button>
                    </div>

                    <?php echo $hidden_html; ?>
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="controller" value="preregisterHTML">
                </div>
            </div>
        </form>
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

            // AG - New Code Start
            for (var instance in CKEDITOR.instances) {
                CKEDITOR.instances[instance].updateElement();
            }
            // AG - New Code End

    		if($(this).text()=="Create"){
    			$( "#EventForm" ).submit();
    		}
    		else if($(this).text()=="Save Changes"){
    			$( "#EventEditForm" ).submit();
    		}
    		else if($(this).text()=="Delete" && (!($(this).hasClass('btn-danger')))){
    			$("#EventDelete").submit();
    		}
    	});
    	$("#EventNameEdit").change(function(){
            $("#cke_TemplateBlob1 .cke_wysiwyg_frame").contents().find("body").html('');
            $("#cke_TemplateBlob2 .cke_wysiwyg_frame").contents().find("body").html('');
            $("#cke_TemplateBlob3 .cke_wysiwyg_frame").contents().find("body").html('');
    		$("#cke_TemplateBlob4 .cke_wysiwyg_frame").contents().find("body").html('');
            $("#cke_TemplateBlob5 .cke_wysiwyg_frame").contents().find("body").html('');
    		$.ajax({
    			method: "POST",
    			url: "Action.php",
    			dataType:'json',
    			data: { action: "select", controller: "preregisterHTML" , EventID: $(this).val()}
    		})
      		.done(function( preregisterHTML ) {
                $("#cke_TemplateBlob1 .cke_wysiwyg_frame").contents().find("body").html(preregisterHTML.quantityform);
      			$("#cke_TemplateBlob2 .cke_wysiwyg_frame").contents().find("body").html(preregisterHTML.infoform);
      			$("#cke_TemplateBlob3 .cke_wysiwyg_frame").contents().find("body").html(preregisterHTML.completeform);
      			$("#cke_TemplateBlob4 .cke_wysiwyg_frame").contents().find("body").html(preregisterHTML.htmlcontent);
      			$("#cke_TemplateBlob5 .cke_wysiwyg_frame").contents().find("body").html(preregisterHTML.errorhtml);
      		});
    	});
    });

    // AG - Commented Code Start

    /* CKEDITOR.replace('TemplateBlob1', {
        allowedContent:true,
        filebrowserUploadUrl: 'ck_upload.php',
        filebrowserUploadMethod: 'form'
    });
    CKEDITOR.replace('TemplateBlob2', {
        allowedContent:true,
        filebrowserUploadUrl: 'ck_upload.php',
        filebrowserUploadMethod: 'form'
    });
    CKEDITOR.replace('TemplateBlob3', {
        filebrowserUploadUrl: 'ck_upload.php',
        filebrowserUploadMethod: 'form'
    });
    CKEDITOR.replace('TemplateBlob4', {
        filebrowserUploadUrl: 'ck_upload.php',
        filebrowserUploadMethod: 'form'
    });
    CKEDITOR.replace('TemplateBlob5', {
        filebrowserUploadUrl: 'ck_upload.php',
        filebrowserUploadMethod: 'form'
    }); */
    // AG - Commented Code End
</script>
@endpush
