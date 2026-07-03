<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SurveyAnswerController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $answers = json_decode(json_encode(session('answers', [])));

        $answerText = $request->input('AnswerName');
        $answerMailed = $request->input('AnswerMailed', 0);

        $answer_obj = (object)[
            'AnswerID' => '',
            'Required' => 'NO',
            'AnswerText' => [
                (object)[
                    'Language' => 100,
                    'LanguageText' => $answerText
                ]
            ],
            'AnswerType' => 1,
            'MailedFlag' => $answerMailed
        ];

        $answers[] = $answer_obj;
        session(['answers' => $answers]);

        $qid = $request->input('QuestionIndex');
        if ($qid !== null && $qid !== '') {
            $questions = json_decode(json_encode(session('questions', [])));
            if (isset($questions[$qid])) {
                $questions[$qid]->Answers = $answers;
                session(['questions' => $questions]);
            }
        }

        return response()->json([
            'Count' => count($answers),
            'AnswerText' => $answerText,
            'Required' => 'NO',
            'AnswerType' => 1,
            'Response' => $this->getDependencyResponse()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $answers = json_decode(json_encode(session('answers', [])));
        $index = (int)$id - 1; // 1-indexed count to 0-indexed index

        $answerText = $request->input('AnswerName');
        $answerMailed = $request->input('AnswerMailed', 0);

        if (isset($answers[$index])) {
            $answers[$index]->AnswerText[0]->LanguageText = $answerText;
            $answers[$index]->MailedFlag = $answerMailed;
        }
        session(['answers' => $answers]);

        $qid = $request->input('QuestionIndex');
        if ($qid !== null && $qid !== '') {
            $questions = json_decode(json_encode(session('questions', [])));
            if (isset($questions[$qid])) {
                $questions[$qid]->Answers = $answers;
                session(['questions' => $questions]);
            }
        }

        return response()->json([
            'Count' => $id,
            'AnswerText' => $answerText,
            'Required' => 'NO',
            'AnswerType' => 1,
            'Response' => $this->getDependencyResponse()
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $answers = json_decode(json_encode(session('answers', [])));
        $index = (int)$id - 1; // 1-indexed count to 0-indexed index

        if (isset($answers[$index])) {
            unset($answers[$index]);
            $answers = array_values($answers);
            session(['answers' => $answers]);
        }

        $qid = $request->input('QuestionIndex');
        if ($qid !== null && $qid !== '') {
            $questions = json_decode(json_encode(session('questions', [])));
            if (isset($questions[$qid])) {
                $questions[$qid]->Answers = $answers;
                session(['questions' => $questions]);
            }
        }

        return response()->json([
            'Count' => $id,
            'Response' => $this->getDependencyResponse()
        ]);
    }

    private function getDependencyResponse()
    {
        $questions = json_decode(json_encode(session('questions', [])));
        $res = [];
        foreach ($questions as $key => $q) {
            $answersList = [];
            $answersArray = isset($q->Answers) ? (array)$q->Answers : [];
            foreach ($answersArray as $ans) {
                $ansText = '';
                if (isset($ans->AnswerText[0]->LanguageText)) {
                    $ansText = $ans->AnswerText[0]->LanguageText;
                } elseif (is_string($ans->AnswerText)) {
                    $ansText = $ans->AnswerText;
                }
                $answersList[] = (object)[
                    'AnswerText' => $ansText,
                ];
            }

            $qText = '';
            if (isset($q->QuestionText[0]->LanguageText)) {
                $qText = $q->QuestionText[0]->LanguageText;
            } elseif (is_string($q->QuestionText)) {
                $qText = $q->QuestionText;
            }

            $res[] = (object)[
                'QuestionText' => $qText,
                'Answers' => $answersList,
                'Dependency' => isset($q->Dependency) ? $q->Dependency : (object)[]
            ];
        }
        return $res;
    }
}
