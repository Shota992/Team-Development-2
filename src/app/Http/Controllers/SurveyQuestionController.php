<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SurveyQuestion;

class SurveyQuestionController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $commonQuestions = SurveyQuestion::where('common_status', true)
            ->where('display_status', true)
            ->with('surveyQuestionOptions')
            ->get();

        $customQuestions = SurveyQuestion::where('common_status', false)
            ->where('office_id', $user->office_id)
            ->where('department_id', $user->department_id)
            ->where('display_status', true)
            ->with('surveyQuestionOptions')
            ->get();

        return view('configuration-file.item_list', compact('commonQuestions', 'customQuestions'));
    }

    public function create()
    {
        return view('configuration-file.item_create');
    }

    
    public function store(Request $request)
    {
        // バリデーション
        $request->validate([
            'title' => 'required|string|max:255',
            'text' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'options' => 'required|array|min:3',
            'options.*' => 'required|string|max:255',
        ]);
    
        $user = Auth::user();
    
        // 項目を作成
        $question = SurveyQuestion::create([
            'title' => $request->title,
            'text' => $request->text,
            'description' => $request->description,
            'common_status' => false,
            'office_id' => $user->office_id,
            'department_id' => $user->department_id,
            'display_status' => true,
        ]);
    
        // 選択肢を登録
        foreach ($request->options as $text) {
            $question->surveyQuestionOptions()->create([
                'text' => $text,
            ]);
        }
    
        return redirect()->route('survey_questions.index')->with('success', '項目を追加しました');
    }
    

    public function edit($id)
    {
        $question = SurveyQuestion::where('id', $id)->where('common_status', false)->firstOrFail();
        return view('configuration-file.item_edit', compact('question'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'text' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'options' => 'nullable|array',
            'options.*' => 'nullable|string|max:255',
            'option_ids' => 'nullable|array',
        ]);
    
        $question = SurveyQuestion::where('id', $id)
            ->where('common_status', false)
            ->firstOrFail();
    
        $question->update([
            'title' => $request->title,
            'text' => $request->text,
            'description' => $request->description,
        ]);
    
        // 既存の選択肢を取得（ID => Model の形にする）
        $existingOptions = $question->surveyQuestionOptions()->get()->keyBy('id');
    
        $newOptionTexts = $request->input('options', []);
        $newOptionIds = $request->input('option_ids', []);
        $keepIds = [];
    
        foreach ($newOptionTexts as $i => $text) {
            $text = trim($text);
            if ($text === '') continue;
    
            $optionId = $newOptionIds[$i] ?? null;
    
            if ($optionId && isset($existingOptions[$optionId])) {
                // 既存 → 更新
                $existingOptions[$optionId]->update(['text' => $text]);
                $keepIds[] = $optionId;
            } else {
                // 新規 → 作成
                $new = $question->surveyQuestionOptions()->create(['text' => $text]);
                $keepIds[] = $new->id;
            }
        }
    
        // 削除処理（使われなかった option_id を削除する）
        foreach ($existingOptions as $id => $option) {
            if (!in_array($id, $keepIds)) {
                if ($option->surveyResponseOptionDetails()->count() === 0) {
                    $option->delete();
                }
            }
        }
    
        return redirect()->route('survey_questions.index')->with('success', '項目を更新しました');
    }
    

    public function destroy($id)
    {
        $question = SurveyQuestion::where('id', $id)
            ->where('common_status', false)
            ->with('surveyQuestionOptions.surveyResponseOptionDetails')
            ->firstOrFail();
    
        // 回答詳細 → 選択肢 → 質問 の順で削除
        foreach ($question->surveyQuestionOptions as $option) {
            $option->surveyResponseOptionDetails()->delete();
            $option->delete();
        }
    
        $question->delete();
    
        return redirect()->route('survey_questions.index')->with('success', '項目と関連する回答データを削除しました');
    }
    
    
}
