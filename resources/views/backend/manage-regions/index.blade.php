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
        <li>
            <a href="javascript:;" onclick="jQuery('#region-modal').modal('show');">
                <span class="hidden-xs">Add Region</span>
            </a>
        </li>
    </ul>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Regions</h3>
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
                        <th>Region Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="middle-align">
                    <tr>
                        <td>1</td>
                        <td>India</td>
                        <td>
                            <a href="javascript:;" onclick="jQuery('#region-modal-edit').modal('show');" class="btn btn-secondary btn-sm btn-icon icon-left">
                            Edit
                            </a>
                            <a href="#" class="btn btn-danger btn-sm btn-icon icon-left">
                            Delete
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Latin America</td>
                        <td>
                            <a href="javascript:;" onclick="jQuery('#region-modal-edit').modal('show');" class="btn btn-secondary btn-sm btn-icon icon-left">
                            Edit
                            </a>
                            <a href="#" class="btn btn-danger btn-sm btn-icon icon-left">
                            Delete
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>Asia Pacific</td>
                        <td>
                            <a href="javascript:;" onclick="jQuery('#region-modal-edit').modal('show');" class="btn btn-secondary btn-sm btn-icon icon-left">
                            Edit
                            </a>
                            <a href="#" class="btn btn-danger btn-sm btn-icon icon-left">
                            Delete
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <!-- Main Footer -->
    @include('layouts.backend.footer')
</div>
<!-- content @e -->
<!-- Region Delete -->
<div class="modal fade custom-width" id="region-modal-delete">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Are you sure? </h4>
            </div>
            <form method="post" action="Action.php" id="RegionDelete">
                <input type="hidden" name="DeleteRegionID" id="DeleteRegionID" value="">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="controller" value="region">
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-info" data-dismiss="modal">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Region Create Modal -->
<div class="modal fade custom-width" id="region-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Add Region</h4>
            </div>
            <div class="modal-body">
                <form id="Region" method="post" action="Action.php">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="field-1" class="control-label">Region Name</label>
                                <input type="text" class="form-control" id="field-1" name="RegionName" placeholder="">
                                <input type="hidden" name="action" value="add">
                                <input type="hidden" name="controller" value="region">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-info" data-dismiss="modal">Create</button>
            </div>
        </div>
    </div>
</div>
<!-- Region Edit Modal -->
<div class="modal fade custom-width" id="region-modal-edit">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Edit Region</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <form id="RegionEdit" method="post" action="Action.php">
                            <div class="form-group">
                                <label for="field-1" class="control-label">Region Name</label>
                                <input type="hidden" name="action" value="edit">
                                <input type="hidden" name="controller" value="region">
                                <input type="hidden" id="RegionID" name="RegionID" value="">
                                <input type="text" class="form-control" id="RegionNameEdit" name="RegionName" placeholder="">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-info" data-dismiss="modal">Save Changes</button>
            </div>
        </div>
    </div>
</div>
<script>
    $( document ).ready(function() {
        $("button.btn-info").click(function(){
            console.log($(this).text());
            if($(this).text()=="Create"){
                $( "#Region" ).submit();
            }
            else if($(this).text()=="Save Changes"){
                $( "#RegionEdit" ).submit();
            }
            else if($(this).text()=="Delete"){
                $("#RegionDelete").submit();
            }
            //$( "#Region" ).submit();
        });

        $("a.btn-danger").click(function(){
            $("#DeleteRegionID").val($(this).parent().prev().prev().text());
        });

        $("a.btn-secondary").click(function(){
            //console.log();
            $("#RegionNameEdit").val($(this).parent().prev().text());
            $("#RegionID").val($(this).parent().prev().prev().text());
        });
    });
</script>
@endsection
