<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Measure;
use App\Models\Task;
use Carbon\Carbon;

class MeasureController extends Controller
{
    public function index()
    {
    $user = auth()->user();

    $startDate = Carbon::today();
    $endDate = Carbon::today()->addMonth();

    $measures = Measure::with('tasks')->where('status', 0)->get();
    $tasks = Task::whereIn('measure_id', $measures->pluck('id'))->get();

    // 表示範囲の開始・終了日

    // 日付リストを作成
    $dateList = [];
    for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
        $dateList[] = $date->format('Y-m-d');
    }

    return view('measures/index', compact('measures', 'tasks', 'dateList' , 'user'));
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
}
