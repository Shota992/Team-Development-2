<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\SurveyUserToken;

class DistributionController extends Controller
{
    public function create()
    {
        $loggedInUserDepartmentId = auth()->user()->department_id;
        $questions = \App\Models\SurveyQuestion::with('surveyQuestionOptions')
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
            'name'         => 'required|string|max:255',
            'description'  => 'nullable|string|max:1000',
            'start_date'   => 'required|date',
            'end_date'     => 'nullable|date|after_or_equal:start_date',
            'office_id'    => 'required|integer',
            'department_id'=> 'required|integer',
            'questions'    => 'required|array|min:1',
            'questions.*.title' => 'required|string|max:255',
            'questions.*.text'  => 'required|string|max:255',
            'questions.*.description' => 'nullable|string|max:1000',
            'questions.*.common_status' => 'required|boolean',
        ]);

        $survey = Survey::create([
            'name'         => $request->name,
            'description'  => $request->description,
            'start_date'   => $request->start_date,
            'end_date'     => $request->end_date,
            'office_id'    => $request->office_id,
            'department_id'=> $request->department_id,
        ]);

        foreach ($request->questions as $questionData) {
            \App\Models\SurveyQuestion::create([
                'title'          => $questionData['title'] ?? '未設定',
                'text'           => $questionData['text'] ?? '未設定',
                'description'    => $questionData['description'] ?? null,
                'common_status'  => $questionData['common_status'] ?? 0,
                'display_status' => true,
            ]);
        }

        return redirect()->route('survey.create')->with('success', 'アンケートが作成されました！');
    }

    public function toggleDisplayStatus(Request $request, $id)
    {
        $question = \App\Models\SurveyQuestion::findOrFail($id);

        if ($question->common_status) {
            return response()->json([
                'success' => false,
                'message' => 'この設問は表示状態を変更できません。'
            ], 403);
        }

        $question->display_status = $request->display_status;
        $question->save();

        return response()->json([
            'success'        => true,
            'display_status' => $question->display_status
        ]);
    }

    public function saveToSession(Request $request)
    {
        session([
            'survey_input.name'        => $request->input('name'),
            'survey_input.description' => $request->input('description'),
        ]);

        return response()->json(['success' => true]);
    }

    public function groupSelection()
    {
        $users = User::with('position')->get();
        $loggedInUserOfficeId = auth()->user()->office_id;
        // ログインユーザーの office_id に該当する部署のみ取得
        $departments = Department::where('office_id', $loggedInUserOfficeId)->get();
        return view('distribution.group_selection', compact('users', 'departments'));
    }

    public function finalizeDistribution(Request $request)
    {
        $groupsJson = $request->input('groups_json');

        \Illuminate\Support\Facades\Log::debug('🚀 groups_json 受け取り', ['groups_json' => $groupsJson]);

        $groups = json_decode($groupsJson, true);

        if (!is_array($groups)) {
            \Illuminate\Support\Facades\Log::error('❌ groups_json のパースに失敗しました', ['raw' => $groupsJson]);
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
            'selected_departments'           => $selectedDepartments,
            'survey_selected_users_grouped'  => $selectedUsers
        ]);

        return redirect()->route('survey.advanced-setting');
    }

    public function saveSettings(Request $request)
    {
        $sendType = $request->input('send_type');
        $isAnonymous = $request->input('is_anonymous', 0);

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
            'survey_input.send_type'   => $sendType,
            'survey_input.start_date'  => $startDate,
            'survey_input.end_date'    => $endDate,
            'survey_input.is_anonymous'=> $isAnonymous,
        ]);

        return redirect()->route('survey.confirmation');
    }

    public function sendSurvey(Request $request)
    {
        $input = session('survey_input');

        $survey = Survey::create([
            'name'         => $input['name'] ?? 'タイトル未設定',
            'description'  => $input['description'] ?? null,
            'start_date'   => $input['start_date'] ?? now(),
            'end_date'     => $input['end_date'] ?? null,
            'office_id'    => auth()->user()->office_id,
            'department_id'=> auth()->user()->department_id,
            'is_active'    => true,
        ]);

        $departmentId = auth()->user()->department_id;
        $department = Department::with('user')->find($departmentId);

        if ($department) {
            $grouped[$department->name] = $department->user->pluck('id')->toArray();
        }
        foreach ($grouped as $deptName => $userIds) {
            foreach ($userIds as $userId) {
                $token = \Illuminate\Support\Str::random(50);

                SurveyUserToken::create([
                    'survey_id' => $survey->id,
                    'user_id'   => $userId,
                    'token'     => $token,
                    'answered'  => false,
                ]);

                $user = User::find($userId);
                if ($user) {
                    $startDate = $survey->start_date;
                    \App\Jobs\SendSurveyEmailJob::dispatch($survey, $user, $token)->delay($startDate);
                }
            }
        }

        $selectedUserIds = session('survey_selected_users', []);
        foreach ($selectedUserIds as $userId) {
            DB::table('survey_user')->insert([
                'survey_id'   => $survey->id,
                'user_id'     => $userId,
                'is_delivered'=> true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }

        session()->forget('survey_input');
        session()->forget('survey_selected_users');
        session()->forget('selected_departments');
        session()->forget('survey_selected_users_grouped');

        return redirect()->route('survey.completion');
    }

    public function confirmation()
    {
        \Illuminate\Support\Facades\Log::debug('🧾 確認画面に渡されたセッション', session('survey_input'));
        return view('distribution.confirmation');
    }

    public function list(Request $request)
    {
        // ① ログインユーザーのoffice_idに一致するアンケートのみ取得
        $query = Survey::with('department')
            ->where('office_id', auth()->user()->office_id);

        // ② 部署IDが指定されていればその部署に絞る
        if ($request->filled('department_id')) {
            $selectedDeptId = $request->input('department_id');
            $query->where('department_id', $selectedDeptId);
        }

        $surveys = $query->orderByDesc('start_date')->get();
        // ③ ログインユーザーのoffice_idに該当する部署一覧を取得
        $departments = Department::where('office_id', auth()->user()->office_id)->get();

        // ④ 回答済み件数・配信件数の集計
        if ($request->filled('department_id')) {
            // 部署選択時：その部署のみで集計
            $selectedDeptId = $request->input('department_id');
            $responseCounts = DB::table('survey_user_tokens')
                ->join('users', 'survey_user_tokens.user_id', '=', 'users.id')
                ->where('survey_user_tokens.answered', true)
                ->where('users.department_id', $selectedDeptId)
                ->groupBy('survey_user_tokens.survey_id')
                ->select('survey_user_tokens.survey_id', DB::raw('COUNT(DISTINCT survey_user_tokens.user_id) as answered_count'))
                ->pluck('answered_count', 'survey_user_tokens.survey_id')
                ->toArray();

            $departmentUserCounts = [
                $selectedDeptId => User::where('department_id', $selectedDeptId)
                    ->where('office_id', auth()->user()->office_id)
                    ->count()
            ];
        } else {
            // 全社表示時：各アンケートごとに、【部署ごと】の集計結果を取得
            $answeredByDept = DB::table('survey_user_tokens')
                ->join('users', 'survey_user_tokens.user_id', '=', 'users.id')
                ->where('users.office_id', auth()->user()->office_id)
                ->where('survey_user_tokens.answered', true)
                ->groupBy('survey_user_tokens.survey_id', 'users.department_id')
                ->select('survey_user_tokens.survey_id', 'users.department_id', DB::raw('COUNT(DISTINCT survey_user_tokens.user_id) as answered_count'))
                ->get();

            $deliveredByDept = DB::table('survey_user_tokens')
                ->join('users', 'survey_user_tokens.user_id', '=', 'users.id')
                ->where('users.office_id', auth()->user()->office_id)
                ->groupBy('survey_user_tokens.survey_id', 'users.department_id')
                ->select('survey_user_tokens.survey_id', 'users.department_id', DB::raw('COUNT(DISTINCT survey_user_tokens.user_id) as delivered_count'))
                ->get();

            // 集計結果をアンケートIDごと、部署ごとに連想配列へ整形
            $responseCounts = [];   // [survey_id][department_id] = answered_count
            $departmentUserCounts = []; // [department_id] = total (※配信済みユーザー数)
            foreach ($answeredByDept as $row) {
                $surveyId = $row->survey_id;
                $deptId = $row->department_id;
                $responseCounts[$surveyId][$deptId] = $row->answered_count;
            }
            foreach ($deliveredByDept as $row) {
                $deptId = $row->department_id;
                // 各部署について、同一部署のユーザーは重複しないはずなので、1行目で十分（もしくはUserテーブルから取得しても可）
                $departmentUserCounts[$deptId] = $row->delivered_count;
            }
        }

        return view('distribution.survey_list', [
            'surveys'              => $surveys,
            'departments'          => $departments,
            'selectedDepartmentId' => $request->input('department_id'),
            'responseCounts'       => $responseCounts,
            'departmentUserCounts' => $departmentUserCounts,
        ]);
    }

    public function showSurveyDetails($id)
    {
        $survey = Survey::with('questions')->findOrFail($id);
        return view('distribution.survey_details', compact('survey'));
    }
}
