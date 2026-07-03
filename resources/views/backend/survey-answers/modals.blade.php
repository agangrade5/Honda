<!-- Add Answer -->
<div class="modal fade custom-width" id="answer-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Add Answer</h4>
            </div>
            <form method="post" action="Action.php" id="SessionAnswerForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <label class="control-label" for="social_media">Answer Text</label>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <input type="text" class="form-control" id="AnswerName" name="AnswerName" placeholder="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label class="control-label" for="social_media">Answer Type</label>
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
                            <label class="control-label" for="social_media">Required</label>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">No
                                <input type="hidden" class="" id="AnswerRequired" value="No" name="AnswerRequired" placeholder="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label class="control-label" for="social_media">Is this answer the mailed flag?</label>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group"> YES
                                <input type="radio" class="" id="AnswerMailed" value="1" name="AnswerMailed" placeholder="">
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group"> NO
                                <input type="radio" checked="checked" class="" id="AnswerMailed1" value="0" name="AnswerMailed" placeholder="">
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
                    <input type="hidden" name="action" value="add">
                    <?php if(isset($survey_id) && !empty($survey_id)){ ?>
                    <input name="SurveyIndex" value="<?php echo $survey_id; ?>" type="hidden"/>
                    <?php } if(isset($_GET['QID'])){ ?>
                    <input name="QuestionIndex" value="<?php echo $_GET['QID']; ?>" type="hidden"/>
                    <input type="hidden" name="controller" value="answersessioneditquestionsurvey">
                    <?php } else{  ?>
                    <input type="hidden" name="controller" value="answersessionaddquestionsurvey">
                    <?php } ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                    <input type="submit" class="btn btn-info btn-secondary"  value="Create">
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Answer -->
<div class="modal fade custom-width" id="answer-edit-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Edit Answer</h4>
            </div>
            <form method="post" action="Action.php" id="EditSessionAnswerForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <label class="control-label" for="social_media">Answer Text</label>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <input type="text" class="form-control" id="EditAnswerName" name="AnswerName" placeholder="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label class="control-label" for="social_media">Answer Type</label>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">1
                                <input type="hidden" value="1" class="form-control" id="EditAnswerType" name="AnswerType" placeholder="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label class="control-label" for="social_media">Required</label>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group"> NO
                                <input type="hidden" class="" id="EditAnswerRequired" value="NO" name="AnswerRequired" placeholder="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label class="control-label" for="social_media">Is this answer the mailed flag?</label>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group"> YES
                                <input type="radio" class="" id="EditAnswerMailed" value="1" name="AnswerMailed" placeholder="">
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group"> NO
                                <input type="radio" class="" id="EditAnswerMailed1" value="0" name="AnswerMailed" placeholder="">
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
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="AnswerIndex" id="EditAnswerIndex" value="">
                    <?php if(isset($survey_id) && !empty($survey_id)){ ?>
                    <input name="SurveyIndex" value="<?php echo $survey_id; ?>" type="hidden"/>
                    <?php } if(isset($_GET['QID'])){ ?>
                    <input name="QuestionIndex" value="<?php echo $_GET['QID']; ?>" type="hidden"/>
                    <input type="hidden" name="controller" value="answersessioneditquestionsurvey">
                    <?php } else{  ?>
                    <input type="hidden" name="controller" value="answersessionaddquestionsurvey">
                    <?php } ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                    <input type="submit" class="btn btn-info btn-secondary" id=""  value="Update">
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Answer -->
<div class="modal fade custom-width" id="answer-modal-delete">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Are you sure? </h4>
            </div>
            <form method="post" action="Action.php" id="DeleteSessionAnswerForm">
                <input type="hidden" name="AnswerIndex" id="DeleteAnswerIndex" value="">
                <input type="hidden" name="action" value="delete">
                <?php if(isset($survey_id) && !empty($survey_id)){ ?>
                <input name="SurveyIndex" value="<?php echo $survey_id; ?>" type="hidden"/>
                <?php } if(isset($_GET['QID'])){ ?>
                <input name="QuestionIndex" value="<?php echo $_GET['QID']; ?>" type="hidden"/>
                <input type="hidden" name="controller" value="answersessioneditquestionsurvey">
                <?php } else{  ?>
                <input type="hidden" name="controller" value="answersessionaddquestionsurvey">
                <?php } ?>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
                    <input type="submit" class="btn btn-info" id="deletesessionanswer" value="Delete" >
                </div>
            </form>
        </div>
    </div>
</div>
