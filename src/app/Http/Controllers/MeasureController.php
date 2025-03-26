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
    /**
     * 施策一覧
     */
    public function index()
    {
        $user = auth()->user();

        $startDate = Carbon::parse('2025-02-01');
        $endDate = Carbon::parse('2025-02-28');

        // 施策とタスクを取得
        $measures = Measure::with('tasks')->where('status', 0)->get();
        $tasks = Task::whereIn('measure_id', $measures->pluck('id'))->get();

        // 日付リストを作成
        $dateList = [];
        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $dateList[] = $date->format('Y-m-d');
        }

        return view('measures.index', compact('user', 'measures', 'tasks', 'dateList'));
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
        $users = User::where('office_id', $officeId)->get();
    
        if ($departments->isEmpty() || $users->isEmpty()) {
            return redirect()->back()->with('error', '部署またはユーザーが見つかりません。');
        }
    
        return view('create-policy', compact('departments', 'users'));
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
        // バリデーションの追加
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'department_id' => 'required|exists:departments,id',
            'evaluation_frequency' => 'required|string|in:1,3,6,12,custom',
            'custom_frequency_value' => 'nullable|integer|min:1',
            'custom_frequency_unit' => 'nullable|string|in:weeks,months',
            'task_name' => 'required|array',
            'task_name.*' => 'required|string|max:255',
            'task_department_id' => 'required|array',
            'task_department_id.*' => 'required|exists:departments,id',
            'assignee' => 'required|array',
            'assignee.*' => 'required|exists:users,id',
            'start_date_task' => 'required|array',
            'start_date_task.*' => 'required|date',
            'end_date_task' => 'required|array',
            'end_date_task.*' => 'required|date',
        ]);
    
        // 次回評価日を計算
        $nextEvaluationDate = null;
        $today = Carbon::today();
    
        if ($request->input('evaluation_frequency') === 'custom') {
            $value = $request->input('custom_frequency_value');
            $unit = $request->input('custom_frequency_unit');
    
            if ($unit === 'weeks') {
                $nextEvaluationDate = $today->addWeeks($value)->next(Carbon::MONDAY); // 次の月曜日
            } elseif ($unit === 'months') {
                $nextEvaluationDate = $today->addMonths($value)->startOfMonth(); // 翌月の1日
            }
        } else {
            $months = (int) $request->input('evaluation_frequency');
            $nextEvaluationDate = $today->addMonths($months)->startOfMonth(); // 翌月の1日
        }
    
        // 施策のデータ保存
        $measureData = [
            'office_id' => auth()->user()->office_id,
            'department_id' => $request->input('department_id'),
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'status' => 0, // 未完了
            'evaluation_status' => 'pending',
            'next_evaluation_date' => $nextEvaluationDate, // 計算した次回評価日
        ];
    
        $measure = Measure::create($measureData);
    
        foreach ($request->input('task_name') as $index => $taskName) {
            Task::create([
                'measure_id'   => $measure->id,
                'name'         => $taskName,
                'department_id'=> $request->input("task_department_id.$index"),
                'user_id'      => $request->input("assignee.$index"),  // ← 担当者の user_id をここにセット
                'start_date'   => $request->input("start_date_task.$index"),
                'end_date'     => $request->input("end_date_task.$index"),
                'status'       => 0,
            ]);
        }
        
    
        return redirect()->route('measures.index')->with('success', '施策が作成されました。');
    }
    
}
