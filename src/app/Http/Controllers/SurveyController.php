<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Survey;
use App\Models\SurveyQuestion;

class SurveyController extends Controller
{
    public function getSurveyQuestions($surveyId)
    {
        // アンケートの全質問を取得
        $questions = SurveyQuestion::where('survey_id', $surveyId)->get();

        return response()->json([
            'questions' => $questions
        ]);
    }
}

