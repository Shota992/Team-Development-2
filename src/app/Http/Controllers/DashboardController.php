<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Models\SurveyResponse;
use App\Models\User;
use App\Models\Department;
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

    public function index(Request $request)
    {
        // ログインユーザーの情報を取得
        $user = auth()->user();
        $myDepartmentId = $user->department_id;

        // GETパラメータから部署IDを取得。指定がなければ自分の部署を利用。
        $selectedDepartmentId = $request->get('department_id', $myDepartmentId);

        if ($selectedDepartmentId === 'all') {
            // 会社全体の結果: office_id でフィルタ
            $resultData = $this->surveyService->getLatestSurveyResults($user->office_id);
            $latestSurvey = Survey::where('office_id', $user->office_id)
                ->orderBy('start_date', 'desc')
                ->first();
            $total = User::where('office_id', $user->office_id)->count();
        } else {
            // 部署ごとの結果
            $resultData = $this->surveyService->getLatestSurveyResultsByDepartment($selectedDepartmentId);
            $latestSurvey = Survey::where('department_id', $selectedDepartmentId)
                ->orderBy('start_date', 'desc')
                ->first();
            $total = User::where('department_id', $selectedDepartmentId)->count();
        }

        $answered = $latestSurvey
            ? SurveyResponse::where('survey_id', $latestSurvey->id)
                ->distinct('user_id')
                ->count('user_id')
            : 0;
        $percentage = ($total > 0) ? round(($answered / $total) * 100) : 0;
        $answerStatus = [
            'answered'   => $answered,
            'total'      => $total,
            'percentage' => $percentage,
            'unanswered' => $total - $answered,
        ];

        // AIフィードバックのための要約テキスト生成（各設問の最新スコアをまとめる）
        $summaryText = '';
        foreach ($resultData['cards'] as $card) {
            $summaryText .= "{$card['label']}: " . number_format($card['score'], 1) . "\n";
        }
        $aiFeedback = $this->aiService->getFeedback($summaryText);

        // 全部署一覧（プルダウン用）を取得
        $departments = Department::orderBy('name')->get();

        return view('dashboard', [
            'cards'                => $resultData['cards'],
            'surveyDates'          => $resultData['surveyDates'], // 最新アンケート以外の残り5件の日付
            'answerStatus'         => $answerStatus,
            'aiFeedback'           => $aiFeedback,
            'latestSurvey'         => $latestSurvey,
            'myDepartmentId'       => $myDepartmentId,
            'selectedDepartmentId' => $selectedDepartmentId,
            'departments'          => $departments,
        ]);
    }
}
