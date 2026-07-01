@extends('layouts.backend.app')
@section('content')
<!-- content @s -->
<div class="main-content">
    <!-- Content Header section -->
    @include('layouts.backend.content_header', compact('title'))

    @if(session('msg'))
    <div class="dx-warning">
        <div>
            <p>{{ session('msg') }}</p>
        </div>
    </div>
    @endif
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
                    @foreach ($regions as $region)
                    <tr>
                        <td>{{ $region->regionid }}</td>
                        <td>{{ $region->regionname }}</td>
                        <td>
                            <a href="javascript:;" onclick="jQuery('#region-modal-edit').modal('show');" class="btn btn-secondary btn-sm btn-icon icon-left">
                            Edit
                            </a>
                            @if(!auth()->check() || auth()->user()?->userlevel == 1)
                            <a href="javascript:;" onclick="jQuery('#region-modal-delete').modal('show');" class="btn btn-danger btn-sm btn-icon icon-left">
                            Delete
                            </a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
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
            <form method="post" action="{{ route('manage-regions.destroy') }}" id="RegionDelete">
                @csrf
                <input type="hidden" name="DeleteRegionID" id="DeleteRegionID" value="">
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
                <form id="Region" method="post" action="{{ route('manage-regions.store') }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="field-1" class="control-label">Region Name</label>
                                <input type="text" class="form-control" id="field-1" name="RegionName" placeholder="">
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
                        <form id="RegionEdit" method="post" action="{{ route('manage-regions.update') }}">
                            @csrf
                            <div class="form-group">
                                <label for="field-1" class="control-label">Region Name</label>
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
