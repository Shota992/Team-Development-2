<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Models\SurveyResponse;
use App\Models\User;
use App\Services\SurveyService;
use App\Services\AiService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $surveyService;
    protected $aiService;

    public function __construct(SurveyService $surveyService, AiService $aiService)
    {
        $this->surveyService = $surveyService;
        $this->aiService = $aiService;
    }

    public function index()
    {
        // 最新のアンケート結果と集計データを取得
        $resultData = $this->surveyService->getLatestSurveyResults();

        // 回答状況の計算
        $latestSurvey = Survey::orderBy('start_date', 'desc')->first();
        $answered = 0;
        $total = User::count();
        if ($latestSurvey) {
            // 重複しないユーザーごとの回答件数をカウント
            $answered = SurveyResponse::where('survey_id', $latestSurvey->id)
                ->distinct('user_id')
                ->count('user_id');
        }
        $percentage = ($total > 0) ? round(($answered / $total) * 100) : 0;
        $answerStatus = [
            'answered'   => $answered,
            'total'      => $total,
            'percentage' => $percentage,
            'unanswered' => $total - $answered,
        ];

        // AIフィードバックの要約を作成（各質問の最新スコア一覧）
        $summaryText = "";
        foreach ($resultData['cards'] as $card) {
            $summaryText .= "{$card['label']}: " . number_format($card['score'], 1) . "\n";
        }
        $aiFeedback = $this->aiService->getFeedback($summaryText);

        // 必要であれば、$latestSurvey もビューに渡す
        return view('dashboard', [
            'cards'        => $resultData['cards'],
            'surveyDates'  => $resultData['surveyDates'],
            'answerStatus' => $answerStatus,
            'aiFeedback'   => $aiFeedback,
            'latestSurvey' => $latestSurvey,
        ]);
    }
}
