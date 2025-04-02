<?php

namespace App\Services;

use App\Models\Survey;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DataSummaryService
{
    /**
     * 最新のアンケートの集約情報を生成する
     *
     * ログインユーザーの所属部署に基づく最新アンケートを取得し、
     * アンケートの基本情報（名称、実施期間、説明、回答数）に加えて、
     * アンケートに紐づく設問のうち common_status が 1 のものについて、各設問の平均スコアと
     * 最も多く選ばれたオプション（原因）の情報を付加してサマリーとしてまとめる。
     *
     * @return string
     */
    public function generateSurveyDataSummary(): string
    {
        // ログインしているユーザーの所属部署のアンケートを取得
        $latestSurvey = Survey::where('department_id', auth()->user()->department_id)
            ->orderBy('end_date', 'desc')
            ->first();

        if (!$latestSurvey) {
            return 'アンケートデータが存在しません。';
        }

        // 日付のパース
        try {
            $startDate = Carbon::parse($latestSurvey->start_date);
            $endDate   = Carbon::parse($latestSurvey->end_date);
        } catch (\Exception $e) {
            return 'アンケートの日付情報に不備があります。';
        }

        // 回答数の取得
        $responseCount = $latestSurvey->responses()->count();

        // 基本情報のサマリー作成
        $summary  = "アンケート名: {$latestSurvey->name}\n";
        $summary .= "実施期間: " . $startDate->format('Y-m-d') . " ～ " . $endDate->format('Y-m-d') . "\n";
        $summary .= "説明: {$latestSurvey->description}\n";
        $summary .= "回答数: {$responseCount}\n";

        $commonQuestions = \App\Models\SurveyQuestion::where('common_status', 1)
        ->where(function ($query) use ($latestSurvey) {
            $query->whereNull('department_id')
                  ->orWhere('department_id', $latestSurvey->department_id);
        })
        ->get();
    
    if (!$commonQuestions->isEmpty()) {
        foreach ($commonQuestions as $question) {
            // 各設問の平均スコアを取得
            $averageScore = DB::table('survey_response_details')
                ->where('question_id', $question->id)
                ->avg('rating');
            $averageScore = $averageScore ?? 0;
            $summary .= "設問: {$question->title} - 平均スコア: " . round($averageScore, 2);
    
            // 各設問に対する最も多く選ばれたオプション（原因）の取得
            $topOption = DB::table('survey_response_option_details')
                ->join('survey_question_options', 'survey_response_option_details.option_id', '=', 'survey_question_options.id')
                ->where('survey_question_options.question_id', $question->id)
                ->select('survey_question_options.text', DB::raw('COUNT(*) as count'))
                ->groupBy('survey_question_options.text')
                ->orderByDesc('count')
                ->first();
    
            if ($topOption) {
                $summary .= " / 原因: {$topOption->text} (選択数: {$topOption->count})";
            }
            $summary .= "\n";
        }
    } else {
        $summary .= "※共通設問（common_status=1）が存在しません。\n";
    }

        // 補足情報
        $summary .= "\n※このサマリーは、最新のアンケート結果の集約情報です。詳細な設問内容は機密のため省略しています。";

        Log::info('生成されたアンケートサマリー:', ['summary' => $summary]);

        return $summary;
    }
}
