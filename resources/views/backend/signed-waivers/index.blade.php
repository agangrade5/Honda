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
    <!-- Basic Setup -->
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Basic Setup</h3>
            <h3 class="panel-title" style="margin-left:210px;"><b>Total Waivers : <?php echo isset($count) && !empty($count) ? $count->Count : 0;?></b></h3>
            <div class="panel-options">
                <a href="#" data-toggle="panel">
                <span class="collapse-icon">&ndash;</span>
                <span class="expand-icon">+</span>
                </a>
                <a href="#" data-toggle="remove">
                &times;
                </a>
            </div>
        </div>
        <div class="panel-body">
            <script type="text/javascript">
                function update_editable(){

                }
                /* jQuery(document).ready(function($)
                    {
                        $("#example-1").dataTable({
                        "processing": true,
                        "serverSide": true,
                        "ajax": {
                            "url": "server1.php",
                            "type": "POST"
                        }
                    }).yadcf([
                        {column_number : 0, filter_type: 'text'},
                        {column_number : 1, filter_type: 'text'},
                        {column_number : 2, filter_type: 'text'},
                        {column_number : 3, filter_type: 'text'}
                    ]);
                }); */
            </script>
            <table id="example-1" class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Legal Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>Waiver Data ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Legal Name</th>
                        <th>Actions</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <!-- Main Footer -->
    @include('layouts.backend.footer')
</div>
<!-- content @e -->

<!-- modal @s -->
<div class="modal fade custom-width" id="user-modal-edit">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <!-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>-->
                <h4 id="WaiverTitle" class="modal-title">Edit Truck</h4>
                <span style="float:right;font-weight:bold;margin-top:-5%;">
                <a class="btn btn-secondary btn-sm btn-icon icon-left"  href="javascript:;">
                Download PDF
                </a>
                </span>
            </div>
            <div class="modal-body">
                <form method="post" action="Action.php" id="TruckEdit">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <span id="WaiverHTMLEditView"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <img width="565" id="WaiverSignedImg" src="" />
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="controller" value="truck">
                    <input type="hidden" id="WaiverDEditID" name="WaiverDID" value="">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                <!-- <button type="button" class="btn btn-info" data-dismiss="modal">Save Changes</button>-->
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $( document ).ready(function() {
    	$( "#user-modal-edit a.btn-secondary" ).on( "click", function() {
    		var str = window.location.href;
    		var filePath = window.location.pathname;
    		var fileName = filePath.substr(filePath.lastIndexOf("/") + 1);
    		var repStr = str.replace(fileName,"");
    		window.open(repStr+"WaiverPDF.php?WaiverID="+$("#WaiverDEditID").val());
    	});
    });

    $( "#example-1" ).on( "click", "tbody tr td a.btn-secondary", function() {
    	//console.log("vvv");
    	var WaiverDID = $(this).parent().prev().prev().prev().prev().text();
    	//console.log(WaiverDID);
    	$("#WaiverTitle").text($(this).parent().prev().text());
    	//console.log(WaiverDID);
    	var htmlData = $("#WaiverHTML"+WaiverDID).html();
    	//console.log(htmlData);

    	$("#WaiverHTMLEditView").html(htmlData);
    	var imgName = $("#WaiverDOCLocation"+WaiverDID).val();

    	var imgURL = "http://"+window.location.hostname+"/API/assets/legal/sigs/";
    	//imgName = imgName.replace("bin","legal");
    	var iname = imgName.substr(imgName.lastIndexOf("/") + 1);
    	//console.log(iname);
    	$("#WaiverSignedImg").attr("src",imgURL+iname);
    	//console.log($("#WaiverHTML"+WaiverDID).val());
    	$("#WaiverDEditID").val(WaiverDID);
    	//console.log($(this).parent().prev().text());
    });
</script>
@endpush
