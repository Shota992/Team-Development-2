<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Models\SurveyQuestion;
use App\Models\SurveyResponseDetail;
use App\Models\SurveyResponseOptionDetail;
use App\Models\SurveyResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use App\Models\Department;
use Illuminate\Http\Request;

class SurveyController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $officeId = $user->office_id;

        // 全部署取得（所属オフィスのみ）
        $departments = Department::where('office_id', $officeId)->get();

        // 表示対象の部署ID（クエリパラメータ or デフォルト: 自部署）
        $selectedDepartmentId = $request->input('department_id', $user->department_id);

        // ↓ 現在の $departmentId を $selectedDepartmentId に置き換えるだけでOK
        $surveys = Survey::where('department_id', $selectedDepartmentId)
            ->orderBy('end_date', 'desc')
            ->take(6)
            ->get();

        if ($surveys->isEmpty()) {
            return view('items.index', [
                'cards' => [],
                'surveyDates' => [],
                'questions' => [],
                'ratingDistributions' => [],
                'causeTables' => [],
                'causeDates' => [],
                'comments' => []
            ]);
        }

        $surveyDates = $surveys->slice(1)->pluck('end_date')->map(function ($d) {
            return Carbon::parse($d)->format('Y-m-d');
        })->toArray();
        $causeDates = $surveys->pluck('end_date')->map(fn($d) => Carbon::parse($d)->format('n/j'))->reverse()->values()->toArray();

        $questions = SurveyQuestion::where(function ($q) {
            $q->where('common_status', true)->orWhere('display_status', true);
        })->orderBy('id')->with('surveyQuestionOptions')->get();

        $latestSurvey = $surveys->first();

        $cards = [];
        foreach ($questions as $question) {
            $latestAvg = SurveyResponseDetail::where('question_id', $question->id)
                ->whereHas('response', fn($q) => $q->where('survey_id', $latestSurvey->id))
                ->avg('rating') ?? 0;

            $prevAvg = $surveys->count() > 1
                ? SurveyResponseDetail::where('question_id', $question->id)
                ->whereHas('response', fn($q) => $q->where('survey_id', $surveys[1]->id))
                ->avg('rating') ?? 0
                : 0;

            $values = [];
            for ($i = 1; $i < min(6, count($surveys)); $i++) {
                $avg = SurveyResponseDetail::where('question_id', $question->id)
                    ->whereHas('response', fn($q) => $q->where('survey_id', $surveys[$i]->id))
                    ->avg('rating') ?? 0;
                $values[] = $avg;
            }

            $cards[] = [
                'label' => $question->title,
                'score' => $latestAvg,
                'diff'  => $latestAvg - $prevAvg,
                'values' => $values,
                'img'   => 'question.png',
            ];
        }

        $ratingDistributions = [];
        foreach ($questions as $question) {
            $distribution = [];
            foreach ($surveys as $survey) {
                $counts = SurveyResponseDetail::where('question_id', $question->id)
                    ->whereHas('response', fn($q) => $q->where('survey_id', $survey->id))
                    ->selectRaw('rating, COUNT(*) as count')
                    ->groupBy('rating')
                    ->pluck('count', 'rating');

                $total = $counts->sum();
                $distribution[] = [
                    '1' => $total ? round(($counts[1] ?? 0) / $total * 100, 2) : 0,
                    '2' => $total ? round(($counts[2] ?? 0) / $total * 100, 2) : 0,
                    '3' => $total ? round(($counts[3] ?? 0) / $total * 100, 2) : 0,
                    '4' => $total ? round(($counts[4] ?? 0) / $total * 100, 2) : 0,
                    '5' => $total ? round(($counts[5] ?? 0) / $total * 100, 2) : 0,
                ];
            }
            $ratingDistributions[] = $distribution;
        }

        $causeTables = [];
        foreach ($questions as $question) {
            $options = $question->surveyQuestionOptions ?? [];
            $table = [];

            foreach ($options as $option) {
                $row = ['label' => $option->text, 'values' => []];

                foreach ($surveys as $survey) {
                    $responseIds = $survey->responses()->pluck('id');
                    $total = $responseIds->count();

                    $count = SurveyResponseOptionDetail::where('option_id', $option->id)
                        ->whereHas('responseDetail.response', fn($q) => $q->where('survey_id', $survey->id))
                        ->count();

                    $row['values'][] = $total ? round($count / $total * 100, 1) : 0;
                }

                // ★ 最新が右にくるように reverse（値の順番を反転）
                $row['values'] = array_reverse($row['values']);

                $table[] = $row;
            }


            usort($table, fn($a, $b) => $b['values'][0] <=> $a['values'][0]);
            $causeTables[] = $table;
        }

        // フリーコメント（最新6回のアンケートの中で free_message があるもの）
        $surveyIds = $surveys->pluck('id');

        $comments = SurveyResponse::with('survey')
            ->whereIn('survey_id', $surveyIds)
            ->whereNotNull('free_message')
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('items.index', compact(
            'departments',
            'selectedDepartmentId',
            'cards',
            'surveyDates',
            'questions',
            'ratingDistributions',
            'causeTables',
            'causeDates',
            'comments'
        ));
    }

    public function employeeSurveyShow($id)
    {
        $user = Auth::user();

        $survey = Survey::where('id', $id)->first();
        $surveyItems = SurveyQuestion::with('surveyQuestionOptions')->get();

        return view('survey.employee-survey', compact('survey', 'surveyItems'));
    }

    public function employeeSurveyPost(Request $request)
    {
        $validated = $request->validate([
            'survey_id' => 'required|integer|exists:surveys,id', // survey_idのバリデーション
            'responses' => 'required|json',
        ]);

        $user = Auth::user();
        $surveyId = $validated['survey_id']; // バリデーション済みのsurvey_idを取得

        // アンケートデータを取得
        $survey = Survey::find($surveyId);

        try {
            // アンケートの回答を保存
            $response = SurveyResponse::create([
                'survey_id' => $surveyId,
            ]);

            // 設問ごとの回答を保存
            $responses = json_decode($validated['responses'], true); // JSONデータを配列に変換

            foreach ($responses as $responseData) {
                // 設問ごとの回答を保存
                $responseDetail = SurveyResponseDetail::create([
                    'response_id' => $response->id,
                    'question_id' => $responseData['question_id'],
                    'rating' => $responseData['selectedOption'], // statusに対応
                    'free_text' => $responseData['otherReason'], // free_textに対応
                ]);

                // 選択肢の回答を保存
                $filteredReasons = array_filter($responseData['selectedReasons'], function ($reasonId) {
                    return $reasonId !== 'on'; // "on"を除外
                });

                foreach ($filteredReasons as $reasonId) {
                    SurveyResponseOptionDetail::create([
                        'response_detail_id' => $responseDetail->id, // response_detail_idを関連付け
                        'option_id' => $reasonId, // 選択肢のID
                    ]);
                }
            }

            // 成功時のリダイレクト
            return redirect()->route('survey.employee-survey-success', ['id' => $surveyId]);
        } catch (\Exception $e) {
            // 失敗時のリダイレクト
            dd($e->getMessage());
            return redirect()->route('survey.employee-survey-fail', [
                'id' => $surveyId,
                'error_code' => $e->getCode(), // エラーコードを渡す
            ]);
        }
    }

    public function employeeSurveySuccess($id)
    {
        $survey = Survey::findOrFail($id); // アンケートデータを取得

        return view('survey.employee-survey-success', [
            'title' => $survey->name,
            'description' => $survey->description,
        ]);
    }

    public function employeeSurveyFail($id, Request $request)
    {
        $survey = Survey::findOrFail($id); // アンケートデータを取得
        $errorCode = $request->input('error_code', '不明なエラー'); // エラーコードを取得

        return view('survey.employee-survey-fail', [
            'title' => $survey->name,
            'description' => $survey->description,
            'error_code' => $errorCode,
        ]);
    }
}
