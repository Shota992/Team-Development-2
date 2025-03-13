<?php

namespace App\Services;

use App\Models\Survey;
use App\Models\SurveyResponseDetail;

class SurveyService
{
    /**
     * 最新のアンケート結果と前回比、過去6回分のスコアを算出する
     *
     * @return array ['cards' => [...], 'surveyDates' => [...]]
     */
    public function getLatestSurveyResults(): array
    {
        // 最新7件（最新1件 + 過去6件）のアンケートを開始日の降順で取得
        $surveys = Survey::orderBy('start_date', 'desc')->take(7)->get();
        if ($surveys->isEmpty()) {
            return ['cards' => [], 'surveyDates' => []];
        }
        $latestSurvey = $surveys->first(); // 最新アンケート
        $previousSurvey = $surveys->skip(1)->first(); // 直前のアンケート

        // 過去6件の日付（最新アンケートを除く）
        $surveyDates = [];
        for ($i = 1; $i < count($surveys); $i++) {
            $surveyDates[] = $surveys[$i]->start_date->format('Y/m/d');
        }

        // 最新アンケートに属するすべての質問を取得（ここでは全質問を集計、実際は16項目を想定）
        $questions = $latestSurvey->questions()->orderBy('id')->get();
        $cards = [];
        foreach ($questions as $question) {
            // 最新アンケートでの平均スコア
            $latestAvg = SurveyResponseDetail::where('question_id', $question->id)
                ->whereHas('response', function($q) use ($latestSurvey) {
                    $q->where('survey_id', $latestSurvey->id);
                })
                ->avg('rating') ?? 0;
            // 直前アンケートでの平均スコア
            $prevAvg = 0;
            if ($previousSurvey) {
                $prevAvg = SurveyResponseDetail::where('question_id', $question->id)
                    ->whereHas('response', function($q) use ($previousSurvey) {
                        $q->where('survey_id', $previousSurvey->id);
                    })
                    ->avg('rating') ?? 0;
            }
            $diff = $latestAvg - $prevAvg;

            // 過去7件の平均スコア（最新含む）
            $values = [];
            foreach ($surveys as $survey) {
                $avg = SurveyResponseDetail::where('question_id', $question->id)
                    ->whereHas('response', function($q) use ($survey) {
                        $q->where('survey_id', $survey->id);
                    })
                    ->avg('rating') ?? 0;
                $values[] = $avg;
            }

            $cards[] = [
                'label'  => $question->text,
                'score'  => $latestAvg,
                'diff'   => $diff,
                'values' => $values, // 7件分のスコア。ビュー側で最新以外6件として扱います
                'img'    => $this->mapQuestionToImage($question->text),
            ];
        }

        return [
            'cards'       => $cards,
            'surveyDates' => $surveyDates,
        ];
    }

    /**
     * 質問文からアイコンファイル名を返すマッピング例
     */
    private function mapQuestionToImage(string $questionText): string
    {
        return match($questionText) {
            '顧客基盤の安定性' => 'company.png',
            '企業理念の納得度' => 'Corporate Philosophy.png',
            '社会的貢献' => 'society.png',
            '責任と顧客・社会への貢献' => 'responsibility.png',
            '連帯感と相互尊重' => 'feeling of solidarity.png',
            '魅力的な上司・同僚' => 'boss.png',
            '勤務地や会社設備の魅力' => 'location.png',
            '評価・給与と柔軟な働き方' => 'work style.png',
            '顧客ニーズや事務戦略の伝達' => 'needs.png',
            '上司や会社からの理解' => 'understanding.png',
            '公平な評価' => 'evaluation.png',
            '上司からの適切な教育・支援' => 'education.png',
            '顧客の期待を上回る提案' => 'expectation.png',
            '具体的な目標の共有' => 'Target.png',
            '未来に向けた活動' => 'future.png',
            'ナレッジの標準化' => 'knowledge.png',
            default => 'default.png',
        };
    }
}
