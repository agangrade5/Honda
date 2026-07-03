<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Answer;
use App\Models\Question;
use App\Models\Survey;
use Illuminate\Http\Request;

class SurveyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        session()->forget(['questions', 'answers', 'tmp_ques_name', 'tmp_ques_required']);

        $list = Survey::all();
        foreach ($list as $s) {
            $s->SurveyID = $s->surveyid;
            $s->SurveyName = $s->surveyname;
        }

        $surveys = (object)[
            'Success' => 1,
            'Survey' => $list
        ];

        return view('backend.surveys.index', [
            'title' => 'Manage Surveys',
            'surveys' => $surveys
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!session()->has('questions')) {
            session(['questions' => []]);
        }

        return view('backend.surveys.create', [
            'title' => 'Manage Surveys - Create',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'SurveyName' => 'required|string|max:100',
        ]);

        $questions = $this->normalizeQuestions(session('questions', []));
        $survey_array = ["SurveyData" => []];
        $tmp_dependency_qids = [];
        $tmp_dependency_aids = [];

        foreach ($questions as $key => $question) {
            $q_required = (isset($question->Required) && $question->Required === 'YES') ? 1 : 0;
            $q_text = is_array($question->QuestionText) ? $question->QuestionText[0]->LanguageText : $question->QuestionText;

            $db_question = Question::create([
                'text' => $q_text,
                'required' => $q_required
            ]);
            $question_id = $db_question->questionid;
            $tmp_dependency_qids[$key] = $question_id;

            $survey_array["SurveyData"][$key] = [
                'QuestionID' => $question_id,
                'Required' => $question->Required ?? 'NO',
                'QuestionText' => [
                    [
                        'Language' => 100,
                        'LanguageText' => $q_text
                    ]
                ],
                'Answers' => []
            ];

            $answersArray = isset($question->Answers) ? (array)$question->Answers : [];
            foreach ($answersArray as $a_key => $answer) {
                $a_required = (isset($answer->Required) && $answer->Required === 'YES') ? 1 : 0;
                $a_text = is_array($answer->AnswerText) ? $answer->AnswerText[0]->LanguageText : $answer->AnswerText;

                $db_answer = Answer::create([
                    'text' => $a_text,
                    'required' => $a_required,
                    'type' => 1
                ]);
                $answer_id = $db_answer->answerid;
                $tmp_dependency_aids[$key][$a_key] = $answer_id;

                $survey_array["SurveyData"][$key]['Answers'][] = [
                    'AnswerID' => $answer_id,
                    'Required' => $answer->Required ?? 'NO',
                    'AnswerText' => [
                        [
                            'Language' => 100,
                            'LanguageText' => $a_text
                        ]
                    ],
                    'MailedFlag' => $answer->MailedFlag ?? 0,
                    'AnswerType' => 1
                ];
            }
        }

        // Fill in dependencies
        foreach ($questions as $key => $question) {
            $dep = $question->Dependency ?? null;
            if (empty($dep) || !isset($dep->QuestionID) || $dep->QuestionID === '') {
                $survey_array["SurveyData"][$key]['Dependency'] = (object)[];
            } else {
                $parent_q_key = $dep->QuestionID;
                $parent_db_qid = $tmp_dependency_qids[$parent_q_key] ?? null;

                $dependent_answers_db = [];
                if (isset($dep->Answers) && !empty($dep->Answers)) {
                    $dep_answers_decoded = is_string($dep->Answers) ? json_decode($dep->Answers) : $dep->Answers;
                    if (is_array($dep_answers_decoded)) {
                        foreach ($dep_answers_decoded as $d_a_j) {
                            if (isset($tmp_dependency_aids[$parent_q_key][$d_a_j])) {
                                $dependent_answers_db[] = $tmp_dependency_aids[$parent_q_key][$d_a_j];
                            }
                        }
                    }
                }

                $survey_array["SurveyData"][$key]['Dependency'] = [
                    'QuestionID' => $parent_db_qid,
                    'DependentAnswers' => $dependent_answers_db
                ];
            }
        }

        Survey::create([
            'surveyname' => $request->input('SurveyName'),
            'surveyblob' => json_encode($survey_array),
            'apiprocessed' => 0
        ]);

        session()->forget('questions');

        return redirect()->route('manage-surveys.index')->with('msg', 'The Survey has been created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $survey = Survey::findOrFail($id);
        $survey_name = $survey->surveyname;

        if (!session()->has('questions')) {
            $blob = json_decode($survey->surveyblob);
            $questions = isset($blob->SurveyData) ? $blob->SurveyData : [];
            session(['questions' => $questions]);
        }

        return view('backend.surveys.edit', [
            'title' => 'Manage Surveys - Edit',
            'survey_id' => $id,
            'survey_name' => $survey_name
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'SurveyName' => 'required|string|max:100',
        ]);

        $survey = Survey::findOrFail($id);
        $questions = $this->normalizeQuestions(session('questions', []));
        $survey_array = ["SurveyData" => []];
        $tmp_dependency_qids = [];
        $tmp_dependency_aids = [];

        foreach ($questions as $key => $question) {
            $q_required = (isset($question->Required) && $question->Required === 'YES') ? 1 : 0;
            $q_text = is_array($question->QuestionText) ? $question->QuestionText[0]->LanguageText : $question->QuestionText;

            if (isset($question->QuestionID) && !empty($question->QuestionID)) {
                $db_question = Question::find($question->QuestionID);
                if ($db_question) {
                    $db_question->update([
                        'text' => $q_text,
                        'required' => $q_required
                    ]);
                } else {
                    $db_question = Question::create([
                        'text' => $q_text,
                        'required' => $q_required
                    ]);
                }
                $question_id = $db_question->questionid;
            } else {
                $db_question = Question::create([
                    'text' => $q_text,
                    'required' => $q_required
                ]);
                $question_id = $db_question->questionid;
            }
            $tmp_dependency_qids[$key] = $question_id;

            $survey_array["SurveyData"][$key] = [
                'QuestionID' => $question_id,
                'Required' => $question->Required ?? 'NO',
                'QuestionText' => [
                    [
                        'Language' => 100,
                        'LanguageText' => $q_text
                    ]
                ],
                'Answers' => []
            ];

            $answersArray = isset($question->Answers) ? (array)$question->Answers : [];
            foreach ($answersArray as $a_key => $answer) {
                $a_required = (isset($answer->Required) && $answer->Required === 'YES') ? 1 : 0;
                $a_text = is_array($answer->AnswerText) ? $answer->AnswerText[0]->LanguageText : $answer->AnswerText;

                if (isset($answer->AnswerID) && !empty($answer->AnswerID)) {
                    $db_answer = Answer::find($answer->AnswerID);
                    if ($db_answer) {
                        $db_answer->update([
                            'text' => $a_text,
                            'required' => $a_required
                        ]);
                    } else {
                        $db_answer = Answer::create([
                            'text' => $a_text,
                            'required' => $a_required,
                            'type' => 1
                        ]);
                    }
                    $answer_id = $db_answer->answerid;
                } else {
                    $db_answer = Answer::create([
                        'text' => $a_text,
                        'required' => $a_required,
                        'type' => 1
                    ]);
                    $answer_id = $db_answer->answerid;
                }
                $tmp_dependency_aids[$key][$a_key] = $answer_id;

                $survey_array["SurveyData"][$key]['Answers'][] = [
                    'AnswerID' => $answer_id,
                    'Required' => $answer->Required ?? 'NO',
                    'AnswerText' => [
                        [
                            'Language' => 100,
                            'LanguageText' => $a_text
                        ]
                    ],
                    'MailedFlag' => $answer->MailedFlag ?? 0,
                    'AnswerType' => 1
                ];
            }
        }

        // Fill in dependencies
        foreach ($questions as $key => $question) {
            $dep = $question->Dependency ?? null;
            if (empty($dep) || !isset($dep->QuestionID) || $dep->QuestionID === '') {
                $survey_array["SurveyData"][$key]['Dependency'] = (object)[];
            } else {
                $parent_q_key = $dep->QuestionID;
                $parent_db_qid = $tmp_dependency_qids[$parent_q_key] ?? null;

                $dependent_answers_db = [];
                if (isset($dep->Answers) && !empty($dep->Answers)) {
                    $dep_answers_decoded = is_string($dep->Answers) ? json_decode($dep->Answers) : $dep->Answers;
                    if (is_array($dep_answers_decoded)) {
                        foreach ($dep_answers_decoded as $d_a_j) {
                            if (isset($tmp_dependency_aids[$parent_q_key][$d_a_j])) {
                                $dependent_answers_db[] = $tmp_dependency_aids[$parent_q_key][$d_a_j];
                            }
                        }
                    }
                }

                $survey_array["SurveyData"][$key]['Dependency'] = [
                    'QuestionID' => $parent_db_qid,
                    'DependentAnswers' => $dependent_answers_db
                ];
            }
        }

        $survey->update([
            'surveyname' => $request->input('SurveyName'),
            'surveyblob' => json_encode($survey_array)
        ]);

        session()->forget('questions');

        return redirect()->route('manage-surveys.index')->with('msg', 'The Survey has been updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $survey = Survey::findOrFail($id);
        $survey->delete();

        return redirect()->route('manage-surveys.index')->with('msg', 'The Survey has been deleted successfully');
    }

    private function normalizeQuestions($questions)
    {
        if (empty($questions)) {
            return [];
        }
        return json_decode(json_encode($questions));
    }
}
