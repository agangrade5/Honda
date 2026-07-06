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
        <div class="panel-heading">
            <h3 class="panel-title">Customers</h3>
            <div class="panel-options">
                <a href="#" data-toggle="panel">
                <span class="collapse-icon">&ndash;</span>
                <span class="expand-icon">+</span>
                </a>
            </div>
        </div>
        <form id="customerEditDate" method="post" action="{{ route('manage-pre-reg-email.index') }}">
            @csrf
            <div class="col-sm-4 pd-left-zero">
                <div class="form-group">
                    <label class="control-label">Select Start Date</label>
                    <input type="text" id="NHRAstartDate" required="" name="NHRAstartDate" class="form-control input-append date form_datetime" data-format="mm/dd/yyyy" value="{{ $NHRAstartDate }}" size="100">
                </div>
            </div>
            <div class="col-sm-4 pd-left-zero">
                <div class="form-group">
                    <label class="control-label">Select End Date</label>
                    <input type="text" id="NHRAendDate" required="" name="NHRAendDate" class="form-control input-append date form_datetime" data-format="mm/dd/yyyy" value="{{ $NHRAendDate }}" size="100">
                </div>
            </div>
            <div class="col-sm-4">
                <label class="control-label" style="width: 100%;">  &nbsp; </label>
                <input type="hidden" name="searchBydate" id="searchBydate" value="1">
                <input class="btn btn-info btn-md" type="Submit" name="submit">
            </div>
        </form>
        <div class="panel-body">
            <table class="table table-bordered table-striped" id="userTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Customer Name</th>
                        <th>Email Address</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="middle-align">
                    @if(isset($customers) && !$customers->isEmpty())
                        @foreach ($customers as $customer)
                        <tr>
                            <td>{{ $customer->custid }}</td>
                            <td>{{ $customer->custfname . ' ' . $customer->custlname }}</td>
                            <td>{{ $customer->custemail }}</td>
                            <td>
                                <a href="javascript:;" onclick="jQuery('#customer-modal-edit').modal('show');" class="btn btn-secondary btn-sm btn-icon icon-left">
                                Resend Email
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
            <script>
                $(document).ready(function(){
                    $("#userTable").dataTable({"paging":false, "ordering":false,"info":false,"sDom": '<"top"i>rt<"bottom"i><"clear">',language:{searchPlaceholder: "Search records"}}).yadcf([
                        {column_number : 0,filter_type: null},
                        {column_number : 1,filter_type: 'text'},
                        {column_number : 2, filter_type: 'text'},
                        {column_number : 3,filter_type: null},
                    ]);
                    $('.dataTables_filter input').attr("placeholder", "enter seach terms here");
                });
            </script>
        </div>
    </div>
    <!-- Main Footer -->
    @include('layouts.backend.footer')
</div>
<!-- content @e -->

<!-- Send Email Modal -->
<div class="modal fade custom-width" id="customer-modal-edit">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Send Email</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <form id="customerEdit" method="post" action="{{ route('manage-pre-reg-email.send') }}">
                            @csrf
                            <div class="form-group">
                                <label for="field-1" class="control-label">Customer Email Address</label>
                                <input type="hidden" id="customerID" name="customerID" value="">
                                <input type="text" class="form-control" id="customerEmailEdit" name="customerEmail" placeholder="" required>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-info">Resend Email</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $( document ).ready(function() {
    	$("button.btn-info").click(function(){
    		if($(this).text()=="Resend Email"){
    			$( "#customerEdit" ).submit();
    		}
    	});

    	$("a.btn-secondary").click(function(){
    		$("#customerEmailEdit").val($(this).parent().prev().text());
    		$("#customerID").val($(this).parent().prev().prev().prev().text());
    	});
    });

    //Popup date picker.
    $("#NHRAstartDate").datepicker({ minView: 2,autoclose: true,format: 'mm/dd/yyyy'});

    $("#NHRAendDate").datepicker({ minView: 2,autoclose: true,format: 'mm/dd/yyyy'});
</script>
@endpush
