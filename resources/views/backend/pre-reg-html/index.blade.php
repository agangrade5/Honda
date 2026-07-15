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

    <div class="panel panel-default">
        <form method="post" action="{{ route('manage-pre-reg-html.store') }}" id="EventEditForm">
            @csrf
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="control-label" for="Event">Select an Event to edit</label>
                        <select name="EventID" class="selectboxit select2" id="EventNameEdit">
                            <option value="">Select an Event</option>
                            @foreach($events as $event)
                                <option value="{{ $event->eventid }}">{{ $event->eventid }} {{ $event->eventname }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="TemplateBlob4">Header HTML Content</label>
                        <textarea class="form-control ckeditor" id="TemplateBlob4" name="EventHTML4" rows="10"></textarea>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="TemplateBlob1">Quantity Form</label>
                        <textarea class="form-control ckeditor" id="TemplateBlob1" name="EventHTML1" rows="10"></textarea>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="TemplateBlob2">Info Content</label>
                        <textarea class="form-control ckeditor" id="TemplateBlob2" name="EventHTML2" rows="10"></textarea>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="TemplateBlob3">Success Content</label>
                        <textarea class="form-control ckeditor" id="TemplateBlob3" name="EventHTML3" rows="10"></textarea>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="TemplateBlob5">Error Content</label>
                        <textarea class="form-control ckeditor" id="TemplateBlob5" name="EventHTML5" rows="10"></textarea>
                    </div>
                    <div class="form-group">
                        <button type="button" class="btn btn-info" id="btn-save-changes">Save Changes</button>
                    </div>
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
@vite(['resources/js/backend/pre-reg-html/index.js'])
@endpush
