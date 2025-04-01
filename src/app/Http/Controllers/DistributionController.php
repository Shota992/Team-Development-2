<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Survey;
use App\Models\SurveyQuestion;
use App\Models\User;
use App\Models\Department;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;

class DistributionController extends Controller
{
    public function create()
    {
        $questions = SurveyQuestion::with('surveyQuestionOptions')->orderBy('id', 'asc')->get();
        return view('distribution.survey_create', compact('questions'));
    }

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

        $survey = Survey::create([
            'name' => $request->name,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'office_id' => $request->office_id,
            'department_id' => $request->department_id,
        ]);

        foreach ($request->questions as $questionData) {
            SurveyQuestion::create([
                'title' => $questionData['title'] ?? '未設定',
                'text' => $questionData['text'] ?? '未設定',
                'description' => $questionData['description'] ?? null,
                'common_status' => $questionData['common_status'] ?? 0,
                'display_status' => true,
            ]);
        }

        return redirect()->route('survey.create')->with('success', 'アンケートが作成されました！');
    }

    public function toggleDisplayStatus(Request $request, $id): JsonResponse
    {
        $question = SurveyQuestion::findOrFail($id);

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

    public function saveToSession(Request $request): JsonResponse
    {
        session([
            'survey_input.name' => $request->input('name'),
            'survey_input.description' => $request->input('description'),
        ]);

        return response()->json(['success' => true]);
    }

    public function groupSelection()
    {
        $users = User::with('position')->get();
        $departments = Department::all();
        return view('distribution.group_selection', compact('users', 'departments'));
    }

    public function finalizeDistribution(Request $request)
    {
        $surveyId = session('latest_survey_id');

        foreach ($request->input('users', []) as $userId) {
            DB::table('survey_user')->insert([
                'survey_id' => $surveyId,
                'user_id' => $userId,
                'is_delivered' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return redirect()->route('survey.advanced-setting');
    }

    public function saveSettings(Request $request)
    {
        $sendType = $request->input('send_type');
        $isAnonymous = $request->input('is_anonymous', 0); // '1' or '0'

        $startDate = null;
        $endDate = null;

        if ($sendType === 'schedule') {
            $startDate = Carbon::parse($request->input('scheduled_date') . ' ' . $request->input('scheduled_time'));
        } elseif ($sendType === 'now') {
            $startDate = now();
        }

        if ($request->filled('deadline_date') && $request->filled('deadline_time')) {
            $endDate = Carbon::parse($request->input('deadline_date') . ' ' . $request->input('deadline_time'));
        }

        session([
            'survey_input.send_type' => $sendType,
            'survey_input.start_date' => $startDate,
            'survey_input.end_date' => $endDate,
            'survey_input.is_anonymous' => $isAnonymous,
        ]);

        return redirect()->route('dashboard')->with('success', 'アンケートの詳細設定を保存しました！');
    }
}
