<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SurveyQuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('backend.survey-questions.index', [
            'title' => 'Manage Survey Questions',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        session(['answers' => []]);

        return view('backend.survey-questions.create', [
            'title' => 'Manage Survey Questions - Create',
            'survey_id' => $request->query('SurveyID'),
            'question_data' => null,
            'qid' => null
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'QuestionName' => 'required|string|max:255',
        ]);

        $questions = json_decode(json_encode(session('questions', [])));
        
        $dependency = (object)[];
        if ($request->has('DependenceyQuestion') && $request->input('DependenceyQuestion') !== '') {
            $dependency->QuestionID = $request->input('DependenceyQuestion');
            if ($request->has('DependenceyAnswer')) {
                $dependency->Answers = json_encode($request->input('DependenceyAnswer'));
            }
        }

        $answers = session('answers', []);

        $question_obj = (object)[
            'QuestionID' => '',
            'Required' => $request->input('QuestionRequired', 'NO'),
            'QuestionText' => [
                (object)[
                    'Language' => 100,
                    'LanguageText' => $request->input('QuestionName')
                ]
            ],
            'Answers' => $answers,
            'Dependency' => $dependency
        ];

        $questions[] = $question_obj;
        session(['questions' => $questions]);
        session()->forget(['answers', 'tmp_ques_name', 'tmp_ques_required']);

        $surveyId = $request->input('SurveyIndex');
        if ($surveyId) {
            return redirect()->route('manage-surveys.edit', $surveyId)->with(['msg' => 'The Question has been created successfully', 'status' => 'success']);
        } else {
            return redirect()->route('manage-surveys.create')->with(['msg' => 'The Question has been created successfully', 'status' => 'success']);
        }
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
    public function edit(Request $request, string $id)
    {
        $qid = (int)$id;
        $questions = json_decode(json_encode(session('questions', [])));
        $question_data = $questions[$qid] ?? null;

        if (!$question_data) {
            return redirect()->back()->with(['msg' => 'Question not found in session.', 'status' => 'error']);
        }

        session(['answers' => isset($question_data->Answers) ? (array)$question_data->Answers : []]);

        return view('backend.survey-questions.create', [
            'title' => 'Manage Survey Questions - Edit',
            'survey_id' => $request->query('SurveyID'),
            'question_data' => $question_data,
            'qid' => $qid
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'QuestionName' => 'required|string|max:255',
        ]);

        $qid = (int)$id;
        $questions = json_decode(json_encode(session('questions', [])));

        if (isset($questions[$qid])) {
            $dependency = (object)[];
            if ($request->has('DependenceyQuestion') && $request->input('DependenceyQuestion') !== '') {
                $dependency->QuestionID = $request->input('DependenceyQuestion');
                if ($request->has('DependenceyAnswer')) {
                    $dependency->Answers = json_encode($request->input('DependenceyAnswer'));
                }
            }

            $questions[$qid]->QuestionText[0]->LanguageText = $request->input('QuestionName');
            $questions[$qid]->Required = $request->input('QuestionRequired', 'NO');
            $questions[$qid]->Dependency = $dependency;
            $questions[$qid]->Answers = session('answers', []);
        }

        session(['questions' => $questions]);
        session()->forget(['answers', 'tmp_ques_name', 'tmp_ques_required']);

        $surveyId = $request->input('SurveyIndex');
        if ($surveyId) {
            return redirect()->route('manage-surveys.edit', $surveyId)->with(['msg' => 'The Question has been updated successfully', 'status' => 'success']);
        } else {
            return redirect()->route('manage-surveys.create')->with(['msg' => 'The Question has been updated successfully', 'status' => 'success']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $qid = (int)$id;
        $questions = json_decode(json_encode(session('questions', [])));

        if (isset($questions[$qid])) {
            unset($questions[$qid]);
            $questions = array_values($questions);
            session(['questions' => $questions]);
        }

        return redirect()->back()->with(['msg' => 'The Question has been deleted successfully', 'status' => 'success']);
    }
}
