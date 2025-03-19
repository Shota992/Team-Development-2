<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Survey;
use App\Models\SurveyQuestion;

class DistributionController extends Controller
{
    // アンケート作成画面を表示
    public function create()
    {
        // 設問を `id` ごとにグループ化して取得
        $questions = SurveyQuestion::orderBy('id', 'asc')->get()->groupBy('id');


        return view('distribution.survey_create', compact('questions'));
    }

    // アンケートを保存
    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'office_id' => 'required|integer',
            'department_id' => 'required|integer',
            'questions' => 'required|array|min:1',
            'questions.*.title' => 'required|string|max:255',
            'questions.*.text' => 'required|string|max:255',
            'questions.*.description' => 'nullable|string|max:1000',
            'questions.*.common_status' => 'required|boolean',
        ]);

        // アンケートの保存
        $survey = Survey::create([
            'name' => $request->name,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'office_id' => $request->office_id,
            'department_id' => $request->department_id,
        ]);

        // 設問の保存
        foreach ($request->questions as $questionData) {
            SurveyQuestion::create([
                'survey_id' => $survey->id,
                'title' => $questionData['title'] ?? '未設定', // デフォルト値を設定
                'text' => $questionData['text'] ?? '未設定',
                'description' => $questionData['description'] ?? null,
                'common_status' => $questionData['common_status'] ?? 0,
            ]);
        }
        

        

        return redirect()->route('survey.create')->with('success', 'アンケートが作成されました！');
    }
}
