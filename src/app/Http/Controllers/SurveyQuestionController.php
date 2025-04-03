<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SurveyQuestion;
use Illuminate\Support\Facades\Auth;

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

    $existingOptions = $question->surveyQuestionOptions()->get()->keyBy('id');
    $newOptionTexts = $request->input('options', []);
    $newOptionIds = $request->input('option_ids', []);
    $keepIds = [];

    foreach ($newOptionTexts as $i => $text) {
        $text = trim($text);
        if (empty($text)) continue;

        $optionId = $newOptionIds[$i] ?? null;

        if ($optionId && isset($existingOptions[$optionId])) {
            $existingOptions[$optionId]->update(['text' => $text]);
            $keepIds[] = $optionId;
        } else {
            $question->surveyQuestionOptions()->create(['text' => $text]);
        }
    }

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
    $question = SurveyQuestion::where('id', $id)->where('common_status', false)->firstOrFail();
    $question->delete();

    return redirect()->route('survey_questions.index')->with('success', '削除しました');
}



}
