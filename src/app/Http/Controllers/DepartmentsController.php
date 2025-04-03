<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\Survey;
use App\Models\SurveyQuestion;
use App\Models\SurveyResponseDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DepartmentsController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $officeId = $user->office_id;
        $userDepartmentId = $user->department_id;
    
        // 📅 月リスト（"YYYY-MM" 形式）
        $surveyDates = Survey::where('office_id', $officeId)
            ->orderBy('start_date', 'desc')
            ->get()
            ->pluck('start_date')
            ->map(fn($d) => \Carbon\Carbon::parse($d)->format('Y-m'))
            ->unique()
            ->values()
            ->toArray();
    
        // 📆 現在の月を基準に最も近い月をデフォルトに
        $now = \Carbon\Carbon::now()->format('Y-m');
        $selectedMonth = $request->input('date') ?? collect($surveyDates)
            ->sortBy(fn($date) => abs(strtotime($now) - strtotime($date)))
            ->first();
    
        // 月が選択されていない・アンケートが存在しない場合
        if (!$selectedMonth) {
            return view('departments.index', [
                'departments' => [],
                'questions' => [],
                'scores' => [],
                'surveyDates' => [],
                'latestSurveyDate' => null,
                'latestSurveyName' => null,
            ]);
        }
    
        // 📆 月初〜月末を範囲に変換
        $startOfMonth = \Carbon\Carbon::parse($selectedMonth)->startOfMonth()->format('Y-m-d');
        $endOfMonth = \Carbon\Carbon::parse($selectedMonth)->endOfMonth()->format('Y-m-d');
    
        // 🗂️ 該当月のアンケートIDを取得
        $surveyIds = Survey::where('office_id', $officeId)
            ->whereBetween('start_date', [$startOfMonth, $endOfMonth])
            ->pluck('id')
            ->toArray();
    
        // 🏢 表示する部署（同じ会社の全て）
        $departments = Department::where('office_id', $officeId)
            ->orderBy('name')
            ->get();
    
        // 📋 表示対象設問（共通＋自部署の独自項目）
        $questions = SurveyQuestion::where(function ($q) use ($userDepartmentId) {
                $q->where('common_status', true)
                  ->orWhere('department_id', $userDepartmentId);
            })
            ->where('display_status', true)
            ->orderBy('id')
            ->get();
    
        $questionIds = $questions->pluck('id');
    
        // 📊 回答スコア取得（部署×設問単位の平均）
        $rawScores = SurveyResponseDetail::select(
                'surveys.department_id',
                'survey_response_details.question_id',
                DB::raw('AVG(survey_response_details.rating) as avg_rating')
            )
            ->join('survey_responses', 'survey_response_details.response_id', '=', 'survey_responses.id')
            ->join('surveys', 'survey_responses.survey_id', '=', 'surveys.id')
            ->whereIn('survey_responses.survey_id', $surveyIds)
            ->whereIn('survey_response_details.question_id', $questionIds)
            ->groupBy('surveys.department_id', 'survey_response_details.question_id')
            ->get();
    
        // 🔃 整形 [部署ID][設問ID] => スコア
        $scores = [];
        foreach ($rawScores as $row) {
            $scores[$row->department_id][$row->question_id] = $row->avg_rating;
        }
    
        return view('departments.index', [
            'departments' => $departments,
            'questions' => $questions,
            'scores' => $scores,
            'surveyDates' => $surveyDates,
            'latestSurveyDate' => $selectedMonth,
            'latestSurveyName' => \Carbon\Carbon::parse($selectedMonth)->format('Y年n月'),
        ]);
    }
    
}
