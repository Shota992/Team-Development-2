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
        // dd($request->all());
        $validated = $request->validate([
            'title'                   => 'required|string|max:255',
            'description'             => 'required|string',
            'department_id'           => 'required|exists:departments,id',
            'evaluation_frequency'    => 'required|string|in:1,3,6,12,custom',
            'custom_frequency_value'  => 'required_if:evaluation_frequency,custom|nullable|integer|min:1',
            'custom_frequency_unit'   => 'required_if:evaluation_frequency,custom|nullable|string|in:weeks,months',

            'task_name'               => 'required|array|min:1',
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

        if ($request->evaluation_frequency === 'custom') {
            $value = $request->custom_frequency_value;
            if ($request->custom_frequency_unit === 'weeks') {
                $nextEvaluationDate = $today->addWeeks($value)->next(Carbon::MONDAY);
            } else {
                $nextEvaluationDate = $today->addMonths($value)->startOfMonth();
            }
        } else {
            $nextEvaluationDate = $today->addMonths((int)$request->evaluation_frequency)->startOfMonth();
        }

        DB::transaction(function() use ($request, $nextEvaluationDate) {
            $measure = Measure::create([
                'office_id'                 => auth()->user()->office_id,
                'department_id'             => $request->department_id,
                'title'                     => $request->title,
                'description'               => $request->description,
                'status'                    => 0,
                'evaluation_interval_value' => $request->evaluation_frequency === 'custom'
                                               ? $request->custom_frequency_value
                                               : (int)$request->evaluation_frequency,
                'evaluation_interval_unit'  => $request->evaluation_frequency === 'custom'
                                               ? $request->custom_frequency_unit
                                               : 'months',
                'evaluation_status'         => 'pending',
                'next_evaluation_date'      => $nextEvaluationDate,
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

        return redirect()->route('measures.index')->with('success', '施策が作成されました。');
    }

}
