<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Survey;
use App\Models\SurveyQuestion;
use App\Models\User;
use App\Models\Department;
use App\Models\SurveyUserToken;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\SurveyNotificationMail;
use App\Jobs\SendSurveyEmailJob;




class DistributionController extends Controller
{
    public function create()
    {
        $loggedInUserDepartmentId = auth()->user()->department_id;
        $questions = SurveyQuestion::with('surveyQuestionOptions')
            ->where(function ($query) use ($loggedInUserDepartmentId) {
                $query->where('common_status', 1)
                    ->orWhere('department_id', $loggedInUserDepartmentId);
            })
            ->orderBy('id', 'asc')
            ->get();
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
        $loggedInUserOfficeId = auth()->user()->office_id;
        $departments = Department::where('office_id', $loggedInUserOfficeId)->get();
        return view('distribution.group_selection', compact('users', 'departments'));
    }

    public function finalizeDistribution(Request $request)
    {
        $groupsJson = $request->input('groups_json');

        // ここでログ出力！ 👇
        Log::debug('🚀 groups_json 受け取り', ['groups_json' => $groupsJson]);

        $groups = json_decode($groupsJson, true);

        // 念のため null チェック
        if (!is_array($groups)) {
            Log::error('❌ groups_json のパースに失敗しました', ['raw' => $groupsJson]);
            return back()->with('error', 'データが正しく送信されていません');
        }

        $groups = json_decode($groupsJson, true);

        $selectedDepartments = [];
        $selectedUsers = [];

        foreach ($groups as $group) {
            $deptId = $group['department_id'];
            $dept = Department::find($deptId);

            if ($dept) {
                $selectedDepartments[] = $dept->name;
                $selectedUsers[$dept->name] = $group['user_ids'];
            }
        }

        session([
            'selected_departments' => $selectedDepartments,
            'survey_selected_users_grouped' => $selectedUsers
        ]);

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

        return redirect()->route('survey.confirmation');
    }

    public function sendSurvey(Request $request)
    {
        $input = session('survey_input');

        // 📝 Survey作成（department_idにNULLは入れない）
        $survey = Survey::create([
            'name'         => $input['name'] ?? 'タイトル未設定',
            'description'  => $input['description'] ?? null,
            'start_date'   => $input['start_date'] ?? now(),
            'end_date'     => $input['end_date'] ?? null,
            'office_id'    => auth()->user()->office_id,
            'department_id' => auth()->user()->department_id, // authから取得したdepartment_idを挿入
            'is_active'    => true,
        ]);

        $departmentId = auth()->user()->department_id;
        $department = Department::with('user')->find($departmentId);

        if ($department) {
            $grouped[$department->name] = $department->user->pluck('id')->toArray();
        }
        foreach ($grouped as $deptName => $userIds) {
            foreach ($userIds as $userId) {
                $token = \Illuminate\Support\Str::random(50); // ランダムな50文字のトークンを生成

                // SurveyUserTokenモデルを使用して登録
                SurveyUserToken::create([
                    'survey_id' => $survey->id,
                    'user_id' => $userId,
                    'token' => $token,
                    'answered' => false,
                ]);

                // ✅ ユーザーにメール送信
                $user = \App\Models\User::find($userId);
                if ($user) {
                    $startDate = $survey->start_date; // 配信予定日時
                    SendSurveyEmailJob::dispatch($survey, $user, $token)->delay($startDate);
                }
            }
        }


        // ✅ ユーザー情報保存
        $selectedUserIds = session('survey_selected_users', []);
        foreach ($selectedUserIds as $userId) {
            DB::table('survey_user')->insert([
                'survey_id' => $survey->id,
                'user_id' => $userId,
                'is_delivered' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // ✅ セッション片付け
        session()->forget('survey_input');
        session()->forget('survey_selected_users');
        session()->forget('selected_departments');
        session()->forget('survey_selected_users_grouped');

        // ✅ 配信完了画面へリダイレクト
        return redirect()->route('survey.completion');
    }

    public function confirmation()
    {
        Log::debug('🧾 確認画面に渡されたセッション', session('survey_input'));
        return view('distribution.confirmation');
    }

    public function list(Request $request)
    {
        $query = Survey::with('department');

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->input('department_id'));
        }

        $surveys = $query->orderByDesc('start_date')->get();
        $departments = \App\Models\Department::all();

        // 回答数と部署ユーザー数を集計して連想配列で渡す
        $responseCounts = \App\Models\SurveyResponse::select('survey_id', DB::raw('COUNT(DISTINCT user_id) as answered_count'))
            ->groupBy('survey_id')
            ->pluck('answered_count', 'survey_id')
            ->toArray();

        $departmentUserCounts = \App\Models\User::select('department_id', DB::raw('COUNT(*) as user_count'))
            ->groupBy('department_id')
            ->pluck('user_count', 'department_id')
            ->toArray();

        return view('distribution.survey_list', [
            'surveys' => $surveys,
            'departments' => $departments,
            'selectedDepartmentId' => $request->input('department_id'),
            'responseCounts' => $responseCounts,
            'departmentUserCounts' => $departmentUserCounts,
        ]);
    }
}
