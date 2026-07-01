@extends('layouts.backend.app')
@section('title', $title)
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
<div class="modal fade custom-width" id="region-modal-delete" tabindex="-1" role="dialog" aria-labelledby="region-modal-edit-label" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Are you sure? </h4>
            </div>
            <form method="post" action="#" id="RegionDelete">
                @csrf
                @method('DELETE')
                <input type="hidden" name="delete_region_id" id="delete_region_id" value="">
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-info">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Region Create Modal -->
<div class="modal fade custom-width" id="region-modal" tabindex="-1" role="dialog" aria-labelledby="region-modal-edit-label" aria-hidden="true" data-backdrop="static" data-keyboard="false" >
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
                                <input type="text" class="form-control" id="field-1" name="region_name" placeholder="">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-info">Create</button>
            </div>
        </div>
    </div>
</div>
<!-- Region Edit Modal -->
<div class="modal fade custom-width" id="region-modal-edit" tabindex="-1" role="dialog" aria-labelledby="region-modal-edit-label" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Edit Region</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <form id="RegionEdit" method="post" action="#">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="field-1" class="control-label">Region Name</label>
                                <input type="hidden" id="region_id" name="region_id" value="">
                                <input type="text" class="form-control" id="region_name_edit" name="region_name" placeholder="">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-info">Save Changes</button>
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
            var id = $(this).parent().prev().prev().text();
            $("#delete_region_id").val(id);
            $("#RegionDelete").attr('action', '/manage-regions/' + id);
        });

        $("a.btn-secondary").click(function(){
            //console.log();
            $("#region_name_edit").val($(this).parent().prev().text());
            var id = $(this).parent().prev().prev().text();
            $("#region_id").val(id);
            $("#RegionEdit").attr('action', '/manage-regions/' + id);
        });
    });
</script>
<script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js') }}"></script>
{!! JsValidator::formRequest('App\Http\Requests\Backend\RegionRequest', '#Region') !!}
{!! JsValidator::formRequest('App\Http\Requests\Backend\RegionRequest', '#RegionEdit') !!}
{!! JsValidator::formRequest('App\Http\Requests\Backend\RegionRequest', '#RegionDelete') !!}
@endsection
