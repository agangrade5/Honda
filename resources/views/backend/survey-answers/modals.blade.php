<!-- Add Answer -->
<div class="modal fade custom-width" id="answer-modal" tabindex="-1" role="dialog" aria-labelledby="answer-modal-label" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Add Answer</h4>
            </div>
            <form method="post" action="#" id="SessionAnswerForm">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <label class="control-label" for="AnswerName">Answer Text</label>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <input type="text" class="form-control" id="AnswerName" name="AnswerName" placeholder="" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label class="control-label" for="AnswerType">Answer Type</label>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                1
                                <input type="hidden" value="1" class="form-control" id="AnswerType" name="AnswerType" placeholder="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label class="control-label">Required</label>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">No
                                <input type="hidden" id="AnswerRequired" value="No" name="AnswerRequired">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label class="control-label">Is this answer the mailed flag?</label>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group"> YES
                                <input type="radio" class="" id="AnswerMailed" value="1" name="AnswerMailed">
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group"> NO
                                <input type="radio" checked="checked" class="" id="AnswerMailed1" value="0" name="AnswerMailed">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                &nbsp;
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="tmp_ques_name" value="" id="tmpQuesName">
                    <input type="hidden" name="tmp_ques_required" value="" id="tmpQuesRequired">
                    <input type="hidden" name="QuestionIndex" value="{{ $qid ?? '' }}">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                    <button type="button" id="sessionanswer" class="btn btn-info btn-secondary">Create</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Answer -->
<div class="modal fade custom-width" id="answer-edit-modal" tabindex="-1" role="dialog" aria-labelledby="answer-edit-modal-label" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">  
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Edit Answer</h4>
            </div>
            <form method="post" action="#" id="EditSessionAnswerForm">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <label class="control-label" for="EditAnswerName">Answer Text</label>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <input type="text" class="form-control" id="EditAnswerName" name="AnswerName" placeholder="" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label class="control-label" for="EditAnswerType">Answer Type</label>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">1
                                <input type="hidden" value="1" class="form-control" id="EditAnswerType" name="AnswerType">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label class="control-label">Required</label>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group"> NO
                                <input type="hidden" id="EditAnswerRequired" value="NO" name="AnswerRequired">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label class="control-label">Is this answer the mailed flag?</label>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group"> YES
                                <input type="radio" class="" id="EditAnswerMailed" value="1" name="AnswerMailed">
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group"> NO
                                <input type="radio" class="" id="EditAnswerMailed1" value="0" name="AnswerMailed">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                &nbsp;
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="tmp_ques_name" value="" id="EditTmpQuesName">
                    <input type="hidden" name="tmp_ques_required" value="" id="EditTmpQuesRequired">
                    <input type="hidden" name="AnswerIndex" id="EditAnswerIndex" value="">
                    <input type="hidden" name="QuestionIndex" value="{{ $qid ?? '' }}">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                    <button type="button" id="editsessionanswer" class="btn btn-info btn-secondary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Answer -->
<div class="modal fade custom-width" id="answer-modal-delete" tabindex="-1" role="dialog" aria-labelledby="answer-modal-label" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Are you sure? </h4>
            </div>
            <form method="post" action="#" id="DeleteSessionAnswerForm">
                @csrf
                @method('DELETE')
                <input type="hidden" name="AnswerIndex" id="DeleteAnswerIndex" value="">
                <input type="hidden" name="QuestionIndex" value="{{ $qid ?? '' }}">
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                    <button type="button" id="deletesessionanswer" class="btn btn-info">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>
