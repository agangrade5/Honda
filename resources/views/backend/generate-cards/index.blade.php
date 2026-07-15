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
        <li><a href="javascript:;" id="btn-file-history">
            <span class="hidden-xs">File History</span>
            </a>
        </li>
    </ul>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Generate Cards</h3>
            <div class="panel-options">
                <a href="#" data-toggle="panel">
                <span class="collapse-icon">&ndash;</span>
                <span class="expand-icon">+</span>
                </a>
            </div>
        </div>
        <div class="panel-body">
            <form id="bookForm" method="post" action="{{ route('generate-cards.store') }}">
                @csrf
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="field-1" class="control-label">How many would you like to generate?</label>
                            <input type="text" class="form-control" id="field-1" name="count" placeholder="" required>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-3 control-label">What suffix would you like?</label>
                    <div class="col-xs-4">
                        <input type="text" class="form-control" name="card[0][card_suffix]" placeholder="Card Suffix" />
                    </div>
                    <div class="col-xs-4">
                        <input type="text" class="form-control" name="card1[0][card_no]" placeholder="No of Cards" />
                    </div>
                    <div class="col-xs-1">
                        <button type="button" class="btn btn-default addButton"><i class="fa fa-plus"></i></button>
                    </div>
                </div>
                <!-- The template for adding new field -->
                <div class="form-group hide" id="bookTemplate">
                    <div class="col-xs-4 col-xs-offset-3">
                        <input type="text" class="form-control" name="card_suffix" placeholder="Card Suffix" />
                    </div>
                    <div class="col-xs-4">
                        <input type="text" class="form-control" name="card_no" placeholder="No of Cards" />
                    </div>
                    <div class="col-xs-1">
                        <button type="button" class="btn btn-default removeButton"><i class="fa fa-minus"></i></button>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-xs-2 col-xs-offset-10">
                        <button id="SubmitCardButton" type="submit" class="btn btn-info">Generate</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- Main Footer -->
    @include('layouts.backend.footer')
</div>
<!-- content @e -->

<!-- File History Modal -->
<div class="modal fade custom-width" id="region-modal" tabindex="-1" role="dialog" aria-labelledby="region-modal-label" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">File History</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-bordered table-striped" id="userTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>File Path</th>
                                    <th>Date</th>
                                    <th>Card Batch</th>
                                    <th> Action </th>
                                </tr>
                            </thead>
                            <tbody class="middle-align">
                                @foreach ($histories as $history)
                                <tr>
                                    <td>{{ $history->historyid }}</td>
                                    <td>{{ basename($history->historyfilepath) }}</td>
                                    <td>{{ $history->historyfiledate }}</td>
                                    <td>{{ $history->historycardbatch }}</td>
                                    <td> <a href="{{ asset($history->historyfilepath) }}" target="_blank">Download </a></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@vite(['resources/js/backend/generate-cards/index.js'])
@endpush
