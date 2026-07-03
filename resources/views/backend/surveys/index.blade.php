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
            <a href="{{ route('manage-surveys.create') }}"><span class="hidden-xs">Add Survey</span></a>
        </li>
    </ul>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Survey</h3>
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
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="middle-align">
                    <?php
                        if(isset($surveys) && !empty($surveys) && $surveys->Success==1){
                            foreach ($surveys->Survey as $key => $survey) { ?>
                    <tr>
                        <td><?php echo $survey->SurveyID;?></td>
                        <td><?php echo $survey->SurveyName;?></td>
                        <td>
                            <input type="hidden" id="SurveyID<?php echo $survey->SurveyID;?>" value="<?php echo $survey->SurveyID;?>">
                            <a href="EditSurvey.php?SurveyID=<?php echo $survey->SurveyID;?>" id="CID<?php echo $survey->SurveyID;?>" onclick="jQuery('#truck-modal-edit').modal('show');" class="btn btn-secondary btn-sm btn-icon icon-left">
                            Edit
                            </a>
                            <?php if(Auth::getUsers()->userlevel==1){ ?>
                            <a href="javascript:void(0);" id="<?php echo $survey->SurveyID;?>" onclick="deleteSurvey(this);" class="btn btn-danger btn-icon"><i class="icon-white icon-heart"></i> Delete</a>
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

<!-- Delete Modal -->
<div class="modal fade custom-width" id="survey-modal-delete">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Are you sure? </h4>
            </div>
            <form method="post" action="Action.php" id="DeleteSessionSurveyForm">
                <input type="hidden" name="SurveyIndex" id="DeleteSurveyIndex" value="">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="controller" value="survey">
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                    <input type="submit" class="btn btn-info" id="deletesessionanswer" value="Delete" >
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset("assets/js/jquery.session.js")}}"></script>
<script type="text/javascript">
    function deleteSurvey(c_obj){
        $("#DeleteSurveyIndex").val($(c_obj).attr("id"));
        $('#survey-modal-delete').modal('show');
    }

    $( document ).ready(function() {
        $.session.set("SurveyName", "");
    });
</script>
@endpush
