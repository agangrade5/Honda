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

    <!-- Basic Setup -->
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Basic Setup</h3>
            <h3 class="panel-title" style="margin-left:210px;"><b>Total Waivers : {{ isset($count) && !empty($count) ? $count->Count : 0 }}</b></h3>
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
<div class="modal fade custom-width" id="user-modal-edit" tabindex="-1" role="dialog" aria-labelledby="user-modal-edit-label" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 id="WaiverTitle" class="modal-title">Edit Truck</h4>
                <span style="float:right;font-weight:bold;margin-top:-5%;">
                <a class="btn btn-secondary btn-sm btn-icon icon-left" href="javascript:;">
                Download PDF
                </a>
                </span>
            </div>
            <div class="modal-body">
                <form method="post" action="#" id="TruckEdit">
                    @csrf
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
                    <input type="hidden" id="WaiverDEditID" name="WaiverDID" value="">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialize Server-Side DataTable
        $("#example-1").dataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "{{ route('manage-signed-waivers.data') }}",
                "type": "POST",
                "headers": {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            }
        }).yadcf([
            {column_number : 0, filter_type: 'text'},
            {column_number : 1, filter_type: 'text'},
            {column_number : 2, filter_type: 'text'},
            {column_number : 3, filter_type: 'text'}
        ]);

        // PDF Download Trigger
        $( "#user-modal-edit a.btn-secondary" ).on( "click", function() {
            var waiverId = $("#WaiverDEditID").val();
            if (waiverId) {
                window.open('/manage-signed-waivers/pdf/' + waiverId);
            }
        });

        // View Signature Trigger
        $( "#example-1" ).on( "click", "tbody tr td a.btn-view-signature", function() {
            var WaiverDID = $(this).attr('id');
            $("#WaiverTitle").text($(this).parent().prev().text());
            
            var htmlData = $("#WaiverHTML" + WaiverDID).html();
            $("#WaiverHTMLEditView").html(htmlData);

            var imgName = $("#WaiverDOCLocation" + WaiverDID).val();
            var imgURL = window.location.origin + "/API/assets/legal/sigs/";
            var iname = imgName.substr(imgName.lastIndexOf("/") + 1);
            
            $("#WaiverSignedImg").attr("src", imgURL + iname);
            $("#WaiverDEditID").val(WaiverDID);
            $("#user-modal-edit").modal("show");
        });

        // Blur focused elements on modal hide to prevent aria-hidden focus warnings in the browser console
        $('.modal').on('hide.bs.modal', function () {
            if (document.activeElement) {
                document.activeElement.blur();
            }
        });
    });
</script>
<script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js') }}"></script>
@endpush
