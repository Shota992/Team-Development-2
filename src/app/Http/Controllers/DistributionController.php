<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Survey;
use App\Models\SurveyQuestion;
use Illuminate\Http\JsonResponse;

class DistributionController extends Controller
{
    // アンケート作成画面を表示
    public function create()
    {
        // 設問を ID の昇順で取得
        $questions = SurveyQuestion::with('surveyQuestionOptions')->orderBy('id', 'asc')->get();

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
                'title' => $questionData['title'] ?? '未設定',
                'text' => $questionData['text'] ?? '未設定',
                'description' => $questionData['description'] ?? null,
                'common_status' => $questionData['common_status'] ?? 0,
                'display_status' => true, // デフォルトは表示
            ]);
        }

        return redirect()->route('survey.create')->with('success', 'アンケートが作成されました！');
    }

    public function toggleDisplayStatus(Request $request, $id): JsonResponse
    {
        $question = SurveyQuestion::findOrFail($id);

        // common_status が true の設問は変更不可
        if ($question->common_status) {
            return response()->json([
                'success' => false,
                'message' => 'この設問は表示状態を変更できません。'
            ], 403);
        }

        $question->display_status = $request->display_status;
        $question->save();

        return response()->json([
            'success' => true,
            'display_status' => $question->display_status
        ]);
    }

    public function saveToSession(Request $request): \Illuminate\Http\JsonResponse
    {
        session([
            'survey_input.name' => $request->input('name'),
            'survey_input.description' => $request->input('description'),
        ]);

        return response()->json(['success' => true]);
    }


}
