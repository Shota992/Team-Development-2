<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use App\Models\Measure;
use App\Models\Task;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Evaluation;
use App\Models\EvaluationTask;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


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
        $measures = Measure::with('tasks')
            ->where('status', '!=', 2)
            ->get();

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
            'task_name'               => 'required|array|min:1',
            'evaluation_frequency'    => 'required|in:1,3,6,12,custom',
            'custom_frequency_value'  => 'required_if:evaluation_frequency,custom|integer|min:1',
            'custom_frequency_unit'   => 'required_if:evaluation_frequency,custom|in:weeks,months',
            'task_name.*'             => 'required|string|max:255',
            'task_department_id'      => 'required|array',
            'task_department_id.*'    => 'required|exists:departments,id',
            'assignee'                => 'required|array',
            'assignee.*'              => 'required|exists:users,id',
            'start_date_task'         => 'required|array',
            'start_date_task.*'       => 'required|date',
            'end_date_task'           => 'required|array',
            'end_date_task.*'         => [
                'required',
                'date',
                function($attribute, $value, $fail) use ($request) {
                    $index = explode('.', $attribute)[1];
                    $start = $request->input("start_date_task.$index");
                    if ($start && $value < $start) {
                        $fail("タスク".($index+1)."の終了日は開始日以降である必要があります。");
                    }
                },
            ],
        ]);
    
        /** @var Carbon $today */
        $today = Carbon::today();
    
        // 評価頻度に応じて、次回評価日を計算
        if ($request->evaluation_frequency === 'custom') {
            $value = $request->custom_frequency_value;
            if ($request->custom_frequency_unit === 'weeks') {
                // 例：カスタムで週単位の場合、次の月曜日を基準に
                $nextEvaluationDate = $today->copy()->addWeeks($value)->next(Carbon::MONDAY);
            } else {
                // カスタムで月単位の場合、今日から value ヶ月後の月初に設定
                $nextEvaluationDate = $today->copy()->addMonths($value)->startOfMonth();
            }
        } else {
            // 数値の場合（例：1, 3, 6, 12）、今日からその月数後の月初に設定
            $nextEvaluationDate = $today->copy()->addMonths((int)$request->evaluation_frequency)->startOfMonth();
        }
    
        // Measure の登録処理（DBトランザクション内）
        $measure = DB::transaction(function () use ($request, $nextEvaluationDate) {
            $m = Measure::create([
                'office_id'                => auth()->user()->office_id,
                'department_id'            => $request->department_id,
                'title'                    => $request->title,
                'description'              => $request->description,
                'evaluation_interval_value'=> $request->evaluation_frequency === 'custom'
                    ? $request->custom_frequency_value
                    : (int)$request->evaluation_frequency,
                'evaluation_interval_unit' => $request->evaluation_frequency === 'custom'
                    ? $request->custom_frequency_unit
                    : 'months',
                // ここで evaluation_status を 0 に設定（施策作成時の初期値）
                'evaluation_status'        => 0,
                'next_evaluation_date'     => $nextEvaluationDate,
            ]);
    
            foreach ($request->task_name as $i => $name) {
                $m->tasks()->create([
                    'name'          => $name,
                    'department_id' => $request->task_department_id[$i],
                    'user_id'       => $request->assignee[$i],
                    'start_date'    => $request->start_date_task[$i],
                    'end_date'      => $request->end_date_task[$i],
                    'status'        => 0,
                ]);
            }
            return $m;
        });
    
        // JSONリクエストの場合は JSON で返却
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

    public function evaluationList(Request $request)
    {
        $user = auth()->user();
        $currentDate = Carbon::today();

        $measures = Measure::with(['tasks.user', 'evaluation'])->get()->filter(function ($measure) use ($currentDate) {
            // まず、完全完了（status=2 または evaluation_status=2）のものを表示する
            if ($measure->status == 2 || $measure->evaluation_status == 2) {
                return true;
            }

            if ($measure->evaluation_status == 1) {
                return true;
            }

            return false;
        });

        return view('measures.evaluation-list', compact('measures', 'user'));
    }
    public function evaluationDetail($id)
    {
        $currentDate = Carbon::today();

        // Measureを取得し、関連データをロード
        $measure = Measure::with(['tasks.user', 'evaluation.evaluationTask.task.user'])
            ->orderBy('created_at', 'desc') // created_atの降順で並べる
            ->findOrFail($id);

        $displayStatus = 1;
        if ($measure->status == 2 || $measure->evaluation_status == 2) {
            $displayStatus = 0;
        }

        return view('measures.evaluation-detail', compact('measure', 'displayStatus'));
    }

    public function storeEvaluation(Request $request, $id)
{
    // バリデーション
    $validated = $request->validate([
        'keep' => 'required|string|max:1000',
        'problem' => 'required|string|max:1000',
        'try' => 'required|string|max:1000',
        'tasks' => 'required|array',
        'tasks.*.score' => 'required|integer|min:1|max:5',
        'tasks.*.comment' => 'nullable|string|max:255',
    ]);

    try {
        $measure = Measure::findOrFail($id);

        // 改善点の保存
        $evaluation = Evaluation::create([
            'measure_id' => $measure->id,
            'keep' => $validated['keep'],
            'problem' => $validated['problem'],
            'try' => $validated['try'],
        ]);

        // タスク情報の保存
        foreach ($validated['tasks'] as $taskId => $taskData) {
            EvaluationTask::create([
                'evaluation_id' => $evaluation->id, // 作成したEvaluationのIDを使用
                'task_id' => $taskId,
                'score' => $taskData['score'],
                'comment' => $taskData['comment'] ?? null, // コメントはnullable
            ]);
        }

        // 状態の更新
        if ($measure->evaluation_status == 0) {
            $measure->evaluation_status = 1; // evaluation_statusを1に更新
        }

        if ($measure->status == 1) {
            $measure->status = 2; // statusを2に更新
            $measure->evaluation_status = 2; // evaluation_statusも2に更新
        } else {
            // 次回評価日を計算
            $today = Carbon::today();
            $intervalValue = $measure->evaluation_interval_value;
            $intervalUnit = $measure->evaluation_interval_unit;

            if ($intervalUnit === 'weeks') {
                $measure->next_evaluation_date = $today->addWeeks($intervalValue);
            } elseif ($intervalUnit === 'months') {
                $measure->next_evaluation_date = $today->addMonths($intervalValue);
            }}

        $measure->save(); // 更新を保存


        return redirect()->route('measures.evaluation-list')->with('success', '評価が追加されました。');
    } catch (\Exception $e) {
        // エラー時の処理
        return redirect()->back()->withErrors(['error' => '評価の保存中にエラーが発生しました。もう一度お試しください。 ']);
    }
}
    public function evaluationListDetail($id)
    {
        $currentDate = Carbon::today();

        // Measureを取得し、関連データをロード
        $measure = Measure::with(['tasks.user', 'evaluation.evaluationTask.task.user'])
            ->orderBy('created_at', 'desc') // created_atの降順で並べる
            ->findOrFail($id);

        return view('measures.evaluation-list-detail', compact('measure'));
    }
}
