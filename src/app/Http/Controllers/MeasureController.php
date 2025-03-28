<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Measure;
use App\Models\Task;
use Carbon\Carbon;

class MeasureController extends Controller
{
    public function index(Request $request)
    {
    $user = auth()->user();

    // 基準日を取得（クエリパラメータがなければ今日の日付）
    $baseDate = $request->query('base_date', Carbon::today()->format('Y-m-d'));
    $baseDate = Carbon::parse($baseDate);

    $displayRange = $request->query('display_range', 1);

    $startDate = $baseDate->copy();
    $endDate = $startDate->copy()->addMonths($displayRange);

    // tasksテーブル目線から、measures_idと一致するtaskのうち、1つでもstatus = 0があれば表示する
    $measures = Measure::whereHas('tasks', function($query) {
        $query->where('status', 0);
    })->with('tasks.user')->get();


    // 表示範囲の開始・終了日

    // 日付リストを作成
    $dateList = [];
    for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
        $dateList[] = $date->format('Y-m-d');
    }

    return view('measures/index', compact('measures', 'dateList' , 'user' , 'baseDate', 'displayRange'));
    }
    // 施策作成フォーム表示
    public function create()
    {
        return view('create-policy');
    }

    public function store(Request $request)
    {
        // バリデーションの追加
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'evaluation_frequency' => 'required|in:1,3,6,12,custom',
            'task_name' => 'required|array', // タスク名の配列
            'task_name.*' => 'required|string|max:255', // 各タスク名の検証
            'assignee' => 'required|array',
            'assignee.*' => 'required|string', // 各担当者の検証
            'start_date_task' => 'required|array',
            'start_date_task.*' => 'required|date', // 各タスク開始日の検証
            'end_date_task' => 'required|array',
            'end_date_task.*' => 'required|date', // 各タスク終了日の検証
        ]);

        // 施策のデータ保存
        $measure = Measure::create([
            'office_id' => $request->input('office_id'),
            'department_id' => $request->input('department_id'),
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'status' => $request->input('status'),
            'evaluation_interval' => $request->input('evaluation_interval'),
            'evaluation_status' => $request->input('evaluation_status'),
        ]);
    
        // タスクデータの保存
        $tasks = $request->input('task_name');
        $assignees = $request->input('assignee');
        $startDates = $request->input('start_date_task');
        $endDates = $request->input('end_date_task');
    
        foreach ($tasks as $key => $task) {
            Task::create([
                'measure_id' => $measure->id, // 施策ID
                'name' => $task,
                'start_date' => $startDates[$key],
                'end_date' => $endDates[$key],
                'status' => 'pending', // デフォルトのステータス
            ]);
        }

        return redirect()->route('measure.index'); // 保存後、一覧画面へリダイレクト
    }

    public function noEvaluation(Request $request)
{
    $user = auth()->user();
    $currentDate = Carbon::today();

    // Measureを全件、関連タスクおよび振り返り(evaluation)情報とともに取得
    $measures = Measure::with(['tasks', 'evaluation'])->get()->filter(function($measure) use ($currentDate) {
        // まず、完全完了（status=2 または evaluation_status=2）のものは表示しない
        if ($measure->status == 2 || $measure->evaluation_status == 2) {
            return false;
        }

        // ① statusが1（完了）の場合は問答無用で表示
        if ($measure->status == 1) {
            return true;
        }

        // ② statusが0の場合、施策作成日時にevaluation_interval（日数）を足した日付が
        // 現在の日付になっているか、またはそれ以前なら表示
        if ($measure->status == 0) {
            if ($measure->created_at->copy()->addDays($measure->evaluation_interval)->lessThanOrEqualTo($currentDate)) {
                return true;
            }
        }

        // ③ evaluation_statusが1の場合
        //  該当Measureに関連するevaluationが存在するならば、そのevaluationの作成日時に
        //  evaluation_interval（日数）を足した日付が現在の日付になっているか、またはそれ以前なら表示
        if ($measure->evaluation_status == 1) {
            if ($measure->evaluation && $measure->evaluation->isNotEmpty()) {
                // 複数のevaluationの中で最新のcreated_atを取得
                $latestEvaluation = $measure->evaluation->sortByDesc('created_at')->first();
                // if ($latestEvaluation && $latestEvaluation->created_at->copy()->addDays($measure->evaluation_interval)->greaterThanOrEqualTo($currentDate)) {
                if ($latestEvaluation && $currentDate->greaterThanOrEqualTo($latestEvaluation->created_at->copy()->addDays($measure->evaluation_interval))) {
                    return true;
                }
            }
        }

        return false;
    });

    dd($measures);
    return view('measures/evaluation', compact('measures', 'user'));
}
}
