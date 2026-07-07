<?php

namespace App\Http\Controllers\Backend\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RegistrationController extends Controller
{
    public function index(Request $request)
    {
        $showError = '';
        $eventid = '';
        $eventName = '';
        $totalQuestion = 0;
        $questionIDS = '';
        $questionIDSC = '';
        $questionIDSS = '';
        $registrationsurveyid = '';
        $isCheckedAll = 2;
        
        $preregisterHTML = null;

        $encodedEventId = $request->input('eventid');
        if ($encodedEventId) {
            $eventid = base64_decode($encodedEventId);
            if (isset($eventid) && is_numeric($eventid)) {
                // To check event is valid
                $event = DB::table('events')->where('eventid', $eventid)->first();
                if (!$event) {
                    $showError = 'This is not a valid event for honda member registration';
                } else {
                    $eventName = $event->eventname;
                    $registrationsurveyid = $event->registrationsurveyid;
                    
                    // Fetch pre-register HTML template
                    $preregisterHTML = DB::table('preregisterHTML')->where('eventid', $eventid)->first();
                    if (!$preregisterHTML) {
                        $preregisterHTML = DB::table('preregisterHTML')->where('eventid', 'system')->first();
                    }
                }
            } else {
                $showError = 'This is not a valid event for honda member registration';
            }
        } else {
            $showError = 'This is not a valid event for honda member registration';
        }

        $strHtml1 = '';
        $strHtml2 = '';
        $strHtml3 = '';
        $strHtml4 = '';
        $strHtml5 = '';

        if (empty($showError) && $eventid) {
            // Get Survey Data
            $surveyblob = null;
            if ($registrationsurveyid) {
                $surveyblob = DB::table('surveys')->where('surveyid', $registrationsurveyid)->value('surveyblob');
            }

            if ($surveyblob) {
                $surveyArray = json_decode($surveyblob);
                $surveyArr = isset($surveyArray->SurveyData) ? $surveyArray->SurveyData : [];
                $totalQuestion = count($surveyArr);

                // Make Select (str_1 to str_5)
                $str_1 = "";
                $str_2 = "";
                $str_3 = "";
                $str_4 = "";
                $str_5 = "";

                $str = '<ul>';
                foreach ($surveyArr as $surveyArrVal) {
                    $QuestionID = $surveyArrVal->QuestionID;
                    $questionIDSS .= "," . $QuestionID;
                    $q = $surveyArrVal->QuestionText;
                    $str .= '<li>';
                    $str .= isset($q[0]->LanguageText) ? $q[0]->LanguageText : '';
                    $str_1 .= $str;
                    $str_2 .= $str;
                    $str_3 .= $str;
                    $str_4 .= $str;
                    $str_5 .= $str;
                    $str = "";

                    // Get Answer
                    $a = isset($surveyArrVal->Answers) ? $surveyArrVal->Answers : null;
                    if ($a) {
                        $str_1 .= '<select name="q_' . $QuestionID . '_1" id="q_' . $QuestionID . '_1" class="input-field">';
                        $str_2 .= '<select name="q_' . $QuestionID . '_2" id="q_' . $QuestionID . '_2" class="input-field">';
                        $str_3 .= '<select name="q_' . $QuestionID . '_3" id="q_' . $QuestionID . '_3" class="input-field">';
                        $str_4 .= '<select name="q_' . $QuestionID . '_4" id="q_' . $QuestionID . '_4" class="input-field">';
                        $str_5 .= '<select name="q_' . $QuestionID . '_5" id="q_' . $QuestionID . '_5" class="input-field">';
                        foreach ($a as $ans) {
                            $AnswerID = $ans->AnswerID;
                            $ansTxt = $ans->AnswerText;
                            $ansType = $ans->AnswerType;
                            if ($ansType != 1) {
                                $isCheckedAll = 1;
                            }
                            $str .= '<option value="' . $AnswerID . '">';
                            $str .= isset($ansTxt[0]->LanguageText) ? $ansTxt[0]->LanguageText : '';
                            $str .= '</option>';
                        }
                        $str .= '</select>';
                    }
                    $str .= '</li>';
                }
                $str .= '</ul>';
                $str_1 .= $str;
                $str_2 .= $str;
                $str_3 .= $str;
                $str_4 .= $str;
                $str_5 .= $str;

                // Make Checkbox (str1_1 to str1_5)
                $str1_1 = "";
                $str1_2 = "";
                $str1_3 = "";
                $str1_4 = "";
                $str1_5 = "";

                $str1 = '<ul>';
                foreach ($surveyArr as $surveyArrVal) {
                    $QuestionID = $surveyArrVal->QuestionID;
                    $questionIDSC .= "," . $QuestionID;
                    $q = $surveyArrVal->QuestionText;
                    $str1 .= '<li>';
                    $str1 .= isset($q[0]->LanguageText) ? $q[0]->LanguageText : '';
                    $str1_1 .= $str1;
                    $str1_2 .= $str1;
                    $str1_3 .= $str1;
                    $str1_4 .= $str1;
                    $str1_5 .= $str1;
                    $str1 = "";

                    // Get Answer
                    $a = isset($surveyArrVal->Answers) ? $surveyArrVal->Answers : null;
                    if ($a) {
                        foreach ($a as $ans) {
                            $AnswerID = $ans->AnswerID;
                            $ansTxtObj = $ans->AnswerText;
                            $ansTxt = isset($ansTxtObj[0]->LanguageText) ? $ansTxtObj[0]->LanguageText : '';

                            $ansTxtVal = str_replace(" ", "#", $ansTxt);
                            $ansTxtVal = str_replace("-", "@", $ansTxtVal);
                            $ansTxtVal = str_replace("/", "", $ansTxtVal);

                            $str1_1 .= '<br />';
                            $str1_2 .= '<br />';
                            $str1_3 .= '<br />';
                            $str1_4 .= '<br />';
                            $str1_5 .= '<br />';

                            $str1_1 .= '<input type="checkbox" name="qc_' . $QuestionID . '_1" value="' . $AnswerID . '~' . addslashes($ansTxtVal) . '" class="required validate">' . ' ' . addslashes($ansTxt);
                            $str1_2 .= '<input type="checkbox" name="qc_' . $QuestionID . '_2" value="' . $AnswerID . '~' . addslashes($ansTxtVal) . '" class="required validate">' . ' ' . addslashes($ansTxt);
                            $str1_3 .= '<input type="checkbox" name="qc_' . $QuestionID . '_3" value="' . $AnswerID . '~' . addslashes($ansTxtVal) . '" class="required validate">' . ' ' . addslashes($ansTxt);
                            $str1_4 .= '<input type="checkbox" name="qc_' . $QuestionID . '_4" value="' . $AnswerID . '~' . addslashes($ansTxtVal) . '" class="required validate">' . ' ' . addslashes($ansTxt);
                            $str1_5 .= '<input type="checkbox" name="qc_' . $QuestionID . '_5" value="' . $AnswerID . '~' . addslashes($ansTxtVal) . '" class="required validate">' . ' ' . addslashes($ansTxt);
                        }
                    }
                    $str1 .= '</li>';
                }
                $str1 .= '</ul>';
                $str1_1 .= $str1;
                $str1_2 .= $str1;
                $str1_3 .= $str1;
                $str1_4 .= $str1;
                $str1_5 .= $str1;

                if ($isCheckedAll == 1) {
                    $strHtml1 = $str1_1;
                    $strHtml2 = $str1_2;
                    $strHtml3 = $str1_3;
                    $strHtml4 = $str1_4;
                    $strHtml5 = $str1_5;
                    $questionIDS = $questionIDSC;
                } else {
                    $strHtml1 = $str_1;
                    $strHtml2 = $str_2;
                    $strHtml3 = $str_3;
                    $strHtml4 = $str_4;
                    $strHtml5 = $str_5;
                    $questionIDS = $questionIDSS;
                }
            }
        }

        return view('backend.auth.register', compact(
            'showError',
            'eventid',
            'eventName',
            'totalQuestion',
            'questionIDS',
            'registrationsurveyid',
            'isCheckedAll',
            'preregisterHTML',
            'strHtml1',
            'strHtml2',
            'strHtml3',
            'strHtml4',
            'strHtml5',
            'encodedEventId'
        ));
    }
}
