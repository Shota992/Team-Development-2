<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use App\Models\Measure;
use App\Models\Task;
use Carbon\Carbon;
use App\Models\User;

class MeasureController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $startDate = Carbon::parse('2025-02-01');
        $endDate = Carbon::parse('2025-02-28');

        // 例: 施策とタスクを取得
        $measures = Measure::with('tasks')->where('status', 0)->get();
        $tasks = Task::whereIn('measure_id', $measures->pluck('id'))->get();

        // 日付リストを作成
        $dateList = [];
        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $dateList[] = $date->format('Y-m-d');
        }

        return view('measures/index', compact('user'));
    }
    // 施策作成フォーム表示
    public function create(Request $request)
    {
        $departments = Department::all(); // 部署を全て取得
        $employees = [];

        // 部署が選ばれている場合、その部署に所属する担当者を取得
        if ($request->has('department_id')) {
            $departmentId = $request->input('department_id');
            $employees = User::where('department_id', $departmentId)->get(); // 部署に対応する担当者を取得
        }

        return view('create-policy', compact('departments', 'employees'));
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
        $measureData = [
            'office_id' => $request->input('office_id'),
            'department_id' => $request->input('department_id'),
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'status' => $request->input('status'),
            'evaluation_interval' => $request->input('evaluation_interval'),
            'evaluation_status' => $request->input('evaluation_status'),
        ];

        // カスタム設定の場合、次回評価日を計算
        if ($request->input('evaluation_interval') === 'custom') {
            $interval = $request->input('custom_frequency_interval');
            $unit = $request->input('custom_frequency_unit');
            $nextEvaluationDate = null;

            // 月単位の場合
            if ($unit == 'months') {
                $nextEvaluationDate = Carbon::now()->addMonths($interval)->firstOfMonth();
            }

            // 週単位の場合
            if ($unit == 'weeks') {
                $nextEvaluationDate = Carbon::now()->addWeeks($interval)->next(Carbon::MONDAY);
            }

            // 次回評価日を設定
            $measureData['next_evaluation_date'] = $nextEvaluationDate;
        }

        // 施策の作成
        $measure = Measure::create($measureData);

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

    public function getAssignees($departmentId)
    {
        // 部署IDに基づいてユーザー（担当者）を取得
        $employees = User::where('department_id', $departmentId)->get(['id', 'name']);
        
        return response()->json($employees); // JSON 形式で担当者リストを返す
    }
}
