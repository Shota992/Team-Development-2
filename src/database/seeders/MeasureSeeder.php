<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Measure;

class MeasureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Measure::create ([
            'office_id' => 1,
            'department_id' => 1,
            'title' => '離職率の低下に向けた取り組み',
            'description' => '退職理由の分析と従業員満足度向上を図るための施策を実施。',
            'status' => 2,
            'evaluation_interval_value' => 2,
            'evaluation_interval_unit' => 'weeks',
            'evaluation_status' => 2,
            'next_evaluation_date' => '2025-03-20',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        Measure::create([
            'office_id' => 1,
            'department_id' => 1, // 人事部
            'title' => '人事部の離職率低下施策',
            'description' => '人事部が主導する離職率低下のための施策。',
            'status' => 1,
            'evaluation_interval_value' => 2,
            'evaluation_interval_unit' => 'weeks',
            'evaluation_status' => 1,
            'next_evaluation_date' => '2025-04-24',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        Measure::create([
            'office_id' => 1,
            'department_id' => 1, // 人事部
            'title' => '人事部の新卒採用改善施策',
            'description' => '新卒採用プロセスの改善を通じて、優秀な人材の確保を目指す。',
            'status' => 0,
            'evaluation_interval_value' => 1,
            'evaluation_interval_unit' => 'months',
            'evaluation_status' => 1,
            'next_evaluation_date' => '2025-04-03',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Measure::create([
            'office_id' => 1,
            'department_id' => 1, // 人事部
            'title' => '人事部の中途採用プロセス改善',
            'description' => '中途採用プロセスの効率化と候補者体験の向上を目指す。',
            'status' => 0,
            'evaluation_interval_value' => 2,
            'evaluation_interval_unit' => 'weeks',
            'evaluation_status' => 1,
            'next_evaluation_date' => '2025-04-04',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Measure::create([
            'office_id' => 1,
            'department_id' => 1, // 人事部
            'title' => '人事部の研修プログラム強化',
            'description' => '従業員のスキルアップを目的とした研修プログラムの強化。',
            'status' => 0,
            'evaluation_interval_value' => 3,
            'evaluation_interval_unit' => 'months',
            'evaluation_status' => 1,
            'next_evaluation_date' => '2025-04-05',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
