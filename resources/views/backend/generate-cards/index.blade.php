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
        <li><a href="javascript:;" onclick="jQuery('#region-modal').modal('show');">
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
            <form id="bookForm" method="post" action="Action.php">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="field-1" class="control-label">How many would you like to generate?</label>
                            <input type="text" class="form-control" id="field-1" name="count" placeholder="">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-3 control-label">What suffix would you like?</label>
                    <div class="col-xs-4">
                        <input type="text" class="form-control" name="card[0].card_suffix" placeholder="Card Suffix" />
                    </div>
                    <div class="col-xs-4">
                        <input type="text" class="form-control" name="card1[0].card_no" placeholder="No of Cards" />
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
                        <input type="hidden" name="action" value="add">
                        <input type="hidden" name="controller" value="generatecards">
                        <button id="SubmitCardButton" type="submit" class="btn btn-info" data-dismiss="modal">Generate</button>
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
<div class="modal fade custom-width" id="region-modal" tabindex="-1" role="dialog" aria-labelledby="region-modal-label" data-backdrop="static" data-keyboard="false">>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">File History</h4>
            </div>
            <div class="modal-body">
                <form id="Region" method="post" action="Action.php">
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
                                    <?php
                                    if(!empty($histories) && $histories->Success==1) {
                                        foreach ($histories->FileHistory as $key => $history) {
                                            $filename = str_replace('history/','',$history->HistoryFilePath);
                                    ?>
                                    <tr>
                                        <td><?php echo $history->HistoryID;?></td>
                                        <td><?php echo $filename?></td>
                                        <td><?php echo $history->HistoryFileDate?></td>
                                        <td><?php echo $history->HistoryCardBatch?></td>
                                        <td> <a href="Download.php?file=<?php echo $filename; ?>" target="_blank">Download </a></td>
                                    </tr>
                                    <?php } } ?>
                                </tbody>
                            </table>
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
@endsection

@push('scripts')
<script>
    $( document ).ready(function() {
    	$("button#SubmitCardButton").click(function(){
    		$("#bookForm").submit();
    	});
    	$("button.btn-info").click(function(){
    		if($(this).text()=="Create"){
    			$( "#Region" ).submit();
    		}
    		else if($(this).text()=="Save Changes"){
    			$( "#RegionEdit" ).submit();
    		}
    		else if($(this).text()=="Delete"){
    			$("#RegionDelete").submit();
    		}
    	});

    	$("a.btn-danger").click(function(){
    		$("#DeleteRegionID").val($(this).parent().prev().prev().text());
    	});

    	$("a.btn-secondary").click(function(){
    		$("#RegionNameEdit").val($(this).parent().prev().text());
    		$("#RegionID").val($(this).parent().prev().prev().text());
    	});
    });

    $(document).ready(function() {
        var titleValidators = {
                row: '.col-xs-4',   // The title is placed inside a <div class="col-xs-4"> element
                validators: {
                    notEmpty: {
                        message: 'The title is required'
                    }
                }
            },
            isbnValidators = {
                row: '.col-xs-4',
                validators: {
                    notEmpty: {
                        message: 'The ISBN is required'
                    },
                    isbn: {
                        message: 'The ISBN is not valid'
                    }
                }
            },
            priceValidators = {
                row: '.col-xs-2',
                validators: {
                    notEmpty: {
                        message: 'The price is required'
                    },
                    numeric: {
                        message: 'The price must be a numeric number'
                    }
                }
            },
            bookIndex = 0;

        $('#bookForm')
            // Add button click handler*/
            .on('click', '.addButton', function() {
                bookIndex++;
                var $template = $('#bookTemplate'),
                    $clone    = $template
                                    .clone()
                                    .removeClass('hide')
                                    .removeAttr('id')
                                    .attr('data-book-index', bookIndex)
                                    .insertBefore($template);

                // Update the name attributes
                $clone
                    .find('[name="card_suffix"]').attr('name', 'card[' + bookIndex + '].card_suffix').end()
                    .find('[name="card_no"]').attr('name', 'card1[' + bookIndex + '].card_no').end()

                // Add new fields
                // Note that we also pass the validator rules for new field as the third parameter
                $('#bookForm')
            })

            // Remove button click handler
            .on('click', '.removeButton', function() {
                var $row  = $(this).parents('.form-group'),
                    index = $row.attr('data-book-index');

                // Remove fields
                $('#bookForm')

                // Remove element containing the fields
                $row.remove();
            });
    });
</script>
@endpush
