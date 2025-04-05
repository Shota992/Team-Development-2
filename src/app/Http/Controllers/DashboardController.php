<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Models\Department;
use App\Models\User;
use App\Services\SurveyService;
use App\Services\AiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        // ログインユーザー
        $user = auth()->user();
        $myDepartmentId = $user->department_id;

        // 選択された部署ID（デフォルトは自分の部署）
        $selectedDepartmentId = $request->get('department_id', $myDepartmentId);

        // 部署または会社全体の結果を取得
        if ($selectedDepartmentId === 'all') {
            // 会社全体
            $resultData = $this->surveyService->getLatestSurveyResults($user->office_id);
            $latestSurvey = Survey::where('office_id', $user->office_id)
                ->orderBy('start_date', 'desc')
                ->first();

            // 会社全体のユーザー総数
            $total = User::where('office_id', $user->office_id)->count();
        } else {
            // 選択部署
            $department = Department::find($selectedDepartmentId);
            if ($department) {
                // 指定部署の結果
                $resultData = $this->surveyService->getLatestSurveyResultsByDepartment($selectedDepartmentId);
                $latestSurvey = Survey::where('department_id', $selectedDepartmentId)
                    ->orderBy('start_date', 'desc')
                    ->first();

                // 部署のユーザー総数
                $total = User::where('department_id', $selectedDepartmentId)->count();
            } else {
                // 存在しない部署IDが指定された場合、会社全体にフォールバック
                $resultData = $this->surveyService->getLatestSurveyResults($user->office_id);
                $latestSurvey = Survey::where('office_id', $user->office_id)
                    ->orderBy('start_date', 'desc')
                    ->first();

                $total = User::where('office_id', $user->office_id)->count();
                $selectedDepartmentId = 'all';
            }
        }

        // 回答者数を集計する
        // survey_response_usersテーブルから回答者を集計しつつ、usersテーブルとJOINして
        // 選択された部署(または会社)に属するユーザーのみを対象とする
        $answeredQuery = DB::table('survey_user_tokens')
        ->join('users', 'survey_user_tokens.user_id', '=', 'users.id')
        ->where('survey_user_tokens.survey_id', $latestSurvey ? $latestSurvey->id : 0)
        ->where('survey_user_tokens.answered', true);
    
    if ($selectedDepartmentId === 'all') {
        $answeredQuery->where('users.office_id', $user->office_id);
    } else {
        $answeredQuery->where('users.department_id', $selectedDepartmentId);
    }
    
    $answered = $answeredQuery
        ->distinct('survey_user_tokens.user_id')
        ->count('survey_user_tokens.user_id');

        // 回答率などの計算
        $percentage = ($total > 0) ? round(($answered / $total) * 100) : 0;
        $answerStatus = [
            'answered'   => $answered,
            'total'      => $total,
            'percentage' => $percentage,
            'unanswered' => $total - $answered,
        ];

        // AIフィードバック用テキスト生成
        $summaryText = '';
        foreach ($resultData['cards'] as $card) {
            $summaryText .= "{$card['label']}: " . number_format($card['score'], 1) . "\n";
        }

        // AIフィードバック
        $aiFeedback = $this->aiService->getFeedback($summaryText);

        // 部署一覧(ログインユーザーのオフィス所属)
        // 重複排除（同名部署が複数ある場合、最小IDのみ）
        $departments = Department::selectRaw('MIN(id) as id, name')
            ->where('office_id', $user->office_id)
            ->groupBy('name')
            ->orderBy('name')
            ->get();

        return view('dashboard', [
            'cards'                => $resultData['cards'],
            'surveyDates'          => $resultData['surveyDates'],
            'answerStatus'         => $answerStatus,
            'aiFeedback'           => $aiFeedback,
            'latestSurvey'         => $latestSurvey,
            'myDepartmentId'       => $myDepartmentId,
            'selectedDepartmentId' => $selectedDepartmentId,
            'departments'          => $departments,
        ]);
    }
}
