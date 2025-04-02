<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use App\Models\Measure;
use App\Models\Task;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\DB;


class MeasureController extends Controller
{
    /**
     * 施策一覧
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        // 基準日を取得（クエリパラメータがなければ今日の日付）
        $baseDate = $request->query('base_date', now()->format('Y-m-d'));
        $baseDate = \Carbon\Carbon::parse($baseDate);

        // 表示範囲を取得（デフォルトは1ヶ月）
        $displayRange = $request->query('display_range', 1);

        $startDate = $baseDate->copy();
        $endDate = $startDate->copy()->addMonths($displayRange);

        // 施策とタスクを取得
        $measures = Measure::with('tasks')->get();

        // 日付リストを作成
        $dateList = [];
        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $dateList[] = $date->format('Y-m-d');
        }

        return view('measures.index', compact('baseDate', 'displayRange', 'measures', 'dateList'));
    }

    public function toggleStatus(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        $task->status = $request->status;
        $task->save();
        // 関連 Measure を取得（タスクに measure() リレーションが定義済みと仮定）
        $measure = $task->measure;
        if ($measure) {
            // measure に紐づくタスクのうち、status が 0 のタスクがなければ、すべて完了→ Measure の status を 1 にする
            if ($measure->tasks()->where('status', 0)->count() === 0) {
                $measure->status = 1;
                $measure->save();
            } else {
                // １つでも未完了があれば Measure の status を 0 に戻すなどの処理も可能
                $measure->status = 0;
                $measure->save();
            }
        }

        return response()->json([
            'success' => true,
            'new_task_status' => $task->status,
            'measure_status' => $measure ? $measure->status : null,
        ]);
    }
    public function create(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'ログインが必要です。');
        }

        $officeId = $user->office_id;

        // 部署リストを取得
        $departments = Department::where('office_id', $officeId)->get();

        // 全ユーザーを取得
        $Users = User::where('office_id', $officeId)->get();

        return view('create-policy', compact('departments', 'Users'));
    }

    public function getAssignees($departmentId)
    {
        $assignees = User::where('department_id', $departmentId)->get();

        if ($assignees->isEmpty()) {
            return response()->json([]);
        }

        return response()->json($assignees);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'                   => 'required|string|max:255',
            'description'             => 'required|string',
            'department_id'           => 'required|exists:departments,id',
            'evaluation_frequency'    => 'required|in:1,3,6,12,custom',
            'custom_frequency_value'  => 'required_if:evaluation_frequency,custom|integer|min:1',
            'custom_frequency_unit'   => 'required_if:evaluation_frequency,custom|in:weeks,months',

            'task_name.*'             => 'required|string|max:255',
            'task_department_id.*'    => 'required|exists:departments,id',
            'assignee.*'              => 'required|exists:users,id',
            'start_date_task.*'       => 'required|date',
            'end_date_task.*'         => 'required|date|after_or_equal:start_date_task.*',
        ]);

        /** @var Carbon $today */
        $today = Carbon::today();
        if ($request->evaluation_frequency === 'custom') {
            $next = $request->custom_frequency_unit === 'weeks'
                ? $today->addWeeks($request->custom_frequency_value)->next(Carbon::MONDAY)
                : $today->addMonths($request->custom_frequency_value)->startOfMonth();
        } else {
            $next = $today->addMonths((int)$request->evaluation_frequency)->startOfMonth();
        }

        $measure = DB::transaction(function () use ($request, $next, &$measure) {
            $m = Measure::create([
                'office_id'                => auth()->user()->office_id,
                'department_id'            => $request->department_id,
                'title'                    => $request->title,
                'description'              => $request->description,
                'evaluation_interval_value' => $request->evaluation_frequency === 'custom'
                    ? $request->custom_frequency_value
                    : (int)$request->evaluation_frequency,
                'evaluation_interval_unit' => $request->evaluation_frequency === 'custom'
                    ? $request->custom_frequency_unit
                    : 'months',
                'evaluation_status'        => 'pending',
                'next_evaluation_date'     => $next,
            ]);

            foreach ($request->task_name as $i => $name) {
                $measure->tasks()->create([
                    'name'          => $name,
                    'department_id' => $request->task_department_id[$i],
                    'user_id'       => $request->assignee[$i],
                    'start_date'    => $request->start_date_task[$i],
                    'end_date'      => $request->end_date_task[$i],
                    'status'        => 0,
                ]);
            }
        });

        // JSONリクエストなら JSON 返却、それ以外はリダイレクト
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'measure' => $measure->load('tasks'),
            ], 201);
        }

        return redirect()->route('measures.index')->with('success', '施策が作成されました。');
    }

    public function noEvaluation(Request $request)
    {
        $user = auth()->user();
        $currentDate = Carbon::today();

        // Measureを全件、関連タスクおよび振り返り(evaluation)情報とともに取得
        $measures = Measure::with(['tasks.user', 'evaluation'])->get()->filter(function ($measure) use ($currentDate) {
            // まず、完全完了（status=2 または evaluation_status=2）のものは表示しない
            if ($measure->status == 2 || $measure->evaluation_status == 2) {
                return false;
            }

            // ① statusが1（完了）の場合は問答無用で表示
            if ($measure->status == 1) {
                return true;
            }

            // ② statusが0の場合、measure->next_evaluation_dateの日付が現在の日付と同日もしくはそれ以前なら表示
            if ($measure->status == 0) {
                if ($currentDate->greaterThanOrEqualTo($measure->next_evaluation_date)) {
                    return true;
                }
            }

            return false;
        });

        return view('measures/no-evaluation', compact('measures', 'user'));
    }

    public function evaluationDetail($id)
    {
        $measure = Measure::with(['tasks.user', 'evaluation.evaluationTask'])->findOrFail($id);

        return view('measures.evaluation-detail', compact('measure'));
    }
}
