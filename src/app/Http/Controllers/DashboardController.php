<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. 最新アンケートを取得 (ID降順で最初)
        $latestSurvey = Survey::orderBy('id', 'desc')->first();

        // 2. 前回アンケート (最新の1つ前)
        $previousSurvey = Survey::orderBy('id', 'desc')->skip(1)->first();

        // 3. 最新アンケートの回答数
        $currentResponsesCount = $latestSurvey
            ? $latestSurvey->surveyResponses()->count()
            : 0;

        // 4. 最新アンケートの平均スコア
        $currentAverageScore = 0;
        if ($latestSurvey) {
            $currentAverageScore = $latestSurvey->surveyResponses()
                ->join('survey_response_details', 'survey_responses.id', '=', 'survey_response_details.response_id')
                ->avg('survey_response_details.rating');
            if (!$currentAverageScore) {
                $currentAverageScore = 0;
            }
        }

        // 5. 前回アンケートの平均スコア
        $previousAverageScore = 0;
        if ($previousSurvey) {
            $previousAverageScore = $previousSurvey->surveyResponses()
                ->join('survey_response_details', 'survey_responses.id', '=', 'survey_response_details.response_id')
                ->avg('survey_response_details.rating');
            if (!$previousAverageScore) {
                $previousAverageScore = 0;
            }
        }

        // 6. 前回比
        $scoreDiff = $currentAverageScore - $previousAverageScore;

        // 7. 過去6回分のアンケート (最新→古い順)
        $recentSurveys = Survey::orderBy('id', 'desc')->take(6)->get();

        // 8. AIフィードバック (ダミー)
        $aiFeedback = "今回のアンケートでは○○という声が多いです。○○に対しては改善が期待されます。";

        return view('dashboard', [
            'latestSurvey'          => $latestSurvey,
            'previousSurvey'        => $previousSurvey,
            'currentResponsesCount' => $currentResponsesCount,
            'currentAverageScore'   => $currentAverageScore,
            'scoreDiff'             => $scoreDiff,
            'recentSurveys'         => $recentSurveys,
            'aiFeedback'            => $aiFeedback,
        ]);
    }
}
