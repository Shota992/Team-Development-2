<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Task;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Task::create([
            'measure_id' => 1,
            'department_id' => 1,
            'user_id' => 1,
            'name' => '退職者アンケートの実施と分析',
            'start_date' => '2025-02-10',
            'end_date' => '2025-02-22',
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        Task::create([
            'measure_id' => 1,
            'department_id' => 1,
            'user_id' => 2,
            'name' => '従業員満足度サーベイの実施',
            'start_date' => '2025-02-15',
            'end_date' => '2025-03-01',
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        Task::create([
            'measure_id' => 1,
            'department_id' => 1,
            'user_id' => 3,
            'name' => '上司との1on1制度の設計と導入',
            'start_date' => '2025-03-01',
            'end_date' => '2025-03-15',
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        Task::create([
            'measure_id' => 2,
            'department_id' => 1, // 人事部
            'user_id' => 1,
            'name' => '退職者アンケートの実施と分析',
            'start_date' => '2025-02-10',
            'end_date' => '2025-02-22',
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        Task::create([
            'measure_id' => 2,
            'department_id' => 1, // 人事部
            'user_id' => 2,
            'name' => '従業員満足度サーベイの実施',
            'start_date' => '2025-02-15',
            'end_date' => '2025-03-01',
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        Task::create([
            'measure_id' => 2,
            'department_id' => 1, // 人事部
            'user_id' => 3,
            'name' => '1on1制度の設計と導入',
            'start_date' => '2025-03-01',
            'end_date' => '2025-03-15',
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        Task::create([
            'measure_id' => 3,
            'department_id' => 1, // 人事部
            'user_id' => 1,
            'name' => '新卒採用プロセスの現状分析',
            'start_date' => '2025-03-01',
            'end_date' => '2025-03-15',
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        Task::create([
            'measure_id' => 3,
            'department_id' => 1, // 人事部
            'user_id' => 2,
            'name' => '新卒採用の課題整理と改善案作成',
            'start_date' => '2025-03-16',
            'end_date' => '2025-03-31',
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        Task::create([
            'measure_id' => 3,
            'department_id' => 1, // 人事部
            'user_id' => 3,
            'name' => '新卒採用プロセスの改善案実施',
            'start_date' => '2025-04-01',
            'end_date' => '2025-04-15',
            'status' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // measure_id=4 のタスク
        Task::create([
            'measure_id' => 4,
            'department_id' => 1, // 人事部
            'user_id' => 1,
            'name' => '中途採用プロセスの現状分析',
            'start_date' => '2025-03-10',
            'end_date' => '2025-03-25',
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        Task::create([
            'measure_id' => 4,
            'department_id' => 1, // 人事部
            'user_id' => 2,
            'name' => '中途採用の課題整理と改善案作成',
            'start_date' => '2025-03-26',
            'end_date' => '2025-04-10',
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        Task::create([
            'measure_id' => 4,
            'department_id' => 1, // 人事部
            'user_id' => 3,
            'name' => '中途採用プロセスの改善案実施',
            'start_date' => '2025-04-11',
            'end_date' => '2025-04-25',
            'status' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // measure_id=5 のタスク
        Task::create([
            'measure_id' => 5,
            'department_id' => 1, // 人事部
            'user_id' => 1,
            'name' => '研修プログラムの現状調査',
            'start_date' => '2025-03-20',
            'end_date' => '2025-04-05',
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        Task::create([
            'measure_id' => 5,
            'department_id' => 1, // 人事部
            'user_id' => 2,
            'name' => '研修プログラムの課題整理と改善案作成',
            'start_date' => '2025-04-06',
            'end_date' => '2025-04-20',
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        Task::create([
            'measure_id' => 5,
            'department_id' => 1, // 人事部
            'user_id' => 3,
            'name' => '研修プログラムの改善案実施',
            'start_date' => '2025-04-21',
            'end_date' => '2025-05-05',
            'status' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
