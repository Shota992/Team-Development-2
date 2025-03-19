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

    $startDate = Carbon::parse('2025-02-01');
    $endDate = Carbon::parse('2025-02-28');

        // 例: 施策とタスクを取得
    $measures = Measure::with('tasks')->where('status', 0)->get();
    $tasks = Task::whereIn('measure_id', $measures->pluck('id'))->get();

    // 表示範囲の開始・終了日

    // 日付リストを作成
    $dateList = [];
    for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
        $dateList[] = $date->format('Y-m-d');
        return view('measures/index', compact('user'));
    }}
}
