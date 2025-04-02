<?php

namespace App\Services;

use App\Models\Survey;
use App\Models\SurveyResponseDetail;
use App\Models\SurveyQuestion;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class SurveyService
{
    /**
     * 会社全体の最新6件のアンケート結果と前回比、過去5件分の日付とスコアを算出する
     *
     * @param int $officeId
     * @return array ['cards' => [...], 'surveyDates' => [...]]
     */
    public function getLatestSurveyResults(int $officeId): array
    {
        $surveys = Survey::where('office_id', $officeId)
            ->orderBy('start_date', 'desc')
            ->take(6)
            ->get();

        if ($surveys->isEmpty()) {
            return ['cards' => [], 'surveyDates' => []];
        }

        $latestSurvey = $surveys->first();

        $surveyDates = [];
        for ($i = 1; $i < count($surveys); $i++) {
            $surveyDates[] = Carbon::parse($surveys[$i]->start_date)->format('Y/m/d');
        }

        // 共通設問 + 固有設問（表示中）を取得
        $questions = SurveyQuestion::where(function ($q) {
            $q->where('common_status', true)
              ->orWhere('display_status', true);
        })->orderBy('id')->get();

        $cards = [];
        foreach ($questions as $question) {
            $latestAvg = SurveyResponseDetail::where('question_id', $question->id)
                ->whereHas('response', function ($q) use ($latestSurvey) {
                    $q->where('survey_id', $latestSurvey->id);
                })
                ->avg('rating') ?? 0;

            $prevAvg = 0;
            if ($surveys->count() > 1) {
                $prevSurvey = $surveys[1];
                $prevAvg = SurveyResponseDetail::where('question_id', $question->id)
                    ->whereHas('response', function ($q) use ($prevSurvey) {
                        $q->where('survey_id', $prevSurvey->id);
                    })
                    ->avg('rating') ?? 0;
            }
            $diff = $latestAvg - $prevAvg;

            $values = [];
            for ($i = 1; $i < count($surveys); $i++) {
                $avg = SurveyResponseDetail::where('question_id', $question->id)
                    ->whereHas('response', function ($q) use ($surveys, $i) {
                        $q->where('survey_id', $surveys[$i]->id);
                    })
                    ->avg('rating') ?? 0;
                $values[] = $avg;
            }

            $cards[] = [
                'label'  => $question->title,
                'score'  => $latestAvg,
                'diff'   => $diff,
                'values' => $values,
                'img'    => $this->mapQuestionToImage($question->title),
            ];
        }

        return [
            'cards'       => $cards,
            'surveyDates' => $surveyDates,
        ];
    }

    /**
     * 指定された部署の最新6件のアンケート結果と前回比、過去5件分の日付とスコアを算出する
     *
     * @param int $departmentId
     * @return array
     */
    public function getLatestSurveyResultsByDepartment(int $departmentId): array
    {
        $surveys = Survey::where('department_id', $departmentId)
            ->orderBy('start_date', 'desc')
            ->take(6)
            ->get();

        if ($surveys->isEmpty()) {
            return ['cards' => [], 'surveyDates' => []];
        }

        $latestSurvey = $surveys->first();

        $surveyDates = [];
        for ($i = 1; $i < count($surveys); $i++) {
            $surveyDates[] = Carbon::parse($surveys[$i]->start_date)->format('Y/m/d');
        }

        // 共通設問 + 固有設問（表示中）を取得
        $questions = SurveyQuestion::where(function ($q) {
            $q->where('common_status', true)
              ->orWhere('display_status', true);
        })->orderBy('id')->get();

        $cards = [];
        foreach ($questions as $question) {
            $latestAvg = SurveyResponseDetail::where('question_id', $question->id)
                ->whereHas('response', function ($q) use ($latestSurvey) {
                    $q->where('survey_id', $latestSurvey->id);
                })
                ->avg('rating') ?? 0;

            $prevAvg = 0;
            if ($surveys->count() > 1) {
                $prevSurvey = $surveys[1];
                $prevAvg = SurveyResponseDetail::where('question_id', $question->id)
                    ->whereHas('response', function ($q) use ($prevSurvey) {
                        $q->where('survey_id', $prevSurvey->id);
                    })
                    ->avg('rating') ?? 0;
            }

            $values = [];
            for ($i = 1; $i < count($surveys); $i++) {
                $avg = SurveyResponseDetail::where('question_id', $question->id)
                    ->whereHas('response', function ($q) use ($surveys, $i) {
                        $q->where('survey_id', $surveys[$i]->id);
                    })
                    ->avg('rating') ?? 0;
                $values[] = $avg;
            }

            $cards[] = [
                'label'  => $question->title,
                'score'  => $latestAvg,
                'diff'   => $latestAvg - $prevAvg,
                'values' => $values,
                'img'    => $this->mapQuestionToImage($question->title),
            ];
        }

        return [
            'cards'       => $cards,
            'surveyDates' => $surveyDates,
        ];
    }

    /**
     * 質問タイトルに応じた画像ファイル名を返す
     *
     * @param string $questionText
     * @return string
     */
    private function mapQuestionToImage(string $questionText): string
    {
        $questionText = trim($questionText);
        return match ($questionText) {
            '顧客基盤の安定性' => 'company.png',
            '理念戦略への納得感' => 'corporate-philosophy.png',
            '社会的貢献' => 'society.png',
            '責任と顧客・社会への貢献' => 'responsibility.png',
            '連帯感と相互尊重' => 'feeling-solidarity.png',
            '魅力的な上司・同僚' => 'boss.png',
            '勤務地や会社設備の魅力' => 'location.png',
            '評価・給与と柔軟な働き方' => 'work-style.png',
            '顧客ニーズや事業戦略の伝達' => 'needs.png',
            '上司や会社からの理解' => 'understanding.png',
            '公平な評価' => 'evaluation.png',
            '上司からの適切な教育・支援' => 'education.png',
            '顧客の期待を上回る提案' => 'expectation.png',
            '具体的な目標の共有' => 'target.png',
            '未来に向けた活動' => 'future.png',
            'ナレッジの標準化' => 'knowledge.png',
            default => 'default.png',
        };
    }
    public function getLatestSurveyWithQuestions()
    {
        $user = Auth::user();

        // 最新のアンケートを取得（office_id が一致または null）
        $latestSurvey = Survey::where(function ($query) use ($user) {
                $query->where('office_id', $user->office_id)
                      ->orWhereNull('office_id');
            })
            ->orderBy('end_date', 'desc')
            ->first();

        if (!$latestSurvey) return null;

        // 設問（共通 or office_id が null または一致）
        $questions = $latestSurvey->questions()
            ->orWhere(function ($query) use ($user) {
                $query->whereNull('office_id')
                      ->orWhere('office_id', $user->office_id);
            })
            ->get();

        return [
            'survey' => $latestSurvey,
            'questions' => $questions,
        ];
    }
}

