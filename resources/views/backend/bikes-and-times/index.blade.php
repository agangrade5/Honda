@extends('layouts.backend.app')
@section('title', $title)
@section('content')
<!-- content @s -->
<div class="main-content">
    <!-- Content Header section -->
    @include('layouts.backend.content_header', compact('title'))

    @if(session('status') == 'error')
    <div class="dx-warning">
        <div>
            <p>{!! session('msg') !!}</p>
        </div>
    </div>
    @elseif(session('status') == 'success')
    <div class="dx-success">
        <div>
            <p>{!! session('msg') !!}</p>
        </div>
    </div>
    @endif

    <ul class="nav nav-tabs right-aligned">
        <!-- available classes "right-aligned" -->
        <li><a href="javascript:;" id="btn-create-set">
            <span class="hidden-xs">Create</span>
            </a>
        </li>
    </ul>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Bikes and Times</h3>
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
                        <th>Set Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="middle-align">
                    @foreach ($btSets as $btSet)
                    <tr>
                        <td>{{ $btSet->btset_id }}</td>
                        <td>{{ $btSet->btset_name }}</td>
                        <td>
                            <a href="{{ route('manage-bikes-and-times.edit', $btSet->btset_id) }}" class="btn btn-secondary btn-sm icon-left">
                            Edit
                            </a>
                            @if(!auth()->check() || auth()->user()?->userlevel == 1)
                            <button type="button" class="btn btn-danger btn-sm btn-delete-set" data-id="{{ $btSet->btset_id }}">
                            Delete
                            </button>
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

<!-- Delete Modal -->
<div class="modal fade custom-width" id="region-modal-delete" tabindex="-1" role="dialog" aria-labelledby="region-modal-delete-label" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Are you sure you want to delete this time set? </h4>
            </div>
            <form method="post" action="" id="RegionDelete">
                @csrf
                @method('DELETE')
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-info">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Create Modal -->
<div class="modal fade custom-width" id="region-modal" tabindex="-1" role="dialog" aria-labelledby="region-modal-label" data-backdrop="static" data-keyboard="false" >
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Add Set</h4>
            </div>
            <form id="Region" method="post" action="{{ route('manage-bikes-and-times.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="BTSetName" class="control-label">Set Name</label>
                                <input type="text" class="form-control" id="BTSetName" name="BTSetName" placeholder="" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-info">Create</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
@vite(['resources/js/backend/bikes-and-times/index.js'])
@endpush
