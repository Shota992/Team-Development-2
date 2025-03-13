<?php

namespace Database\Seeders;

use App\Models\Survey;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SurveySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i < 6; $i++) {
            $monthsBefore = 12 - ($i * 2);
            $startDate = Carbon::today()->subMonths($monthsBefore);
            $endDate = (clone $startDate)->addDays(10);

            Survey::create([
                'name'         => '人事部アンケート' . ($i + 1),
                'description'   => '人事部アンケート' . ($i + 1) . 'についての説明です。',
                'start_date'    => $startDate->toDateString(),
                'end_date'      => $endDate->toDateString(),
                'office_id'     => 1,
                'department_id' => 1,
            ]);
        }

        // 経理部、営業部、総務部アンケート
        $departments = [
            ['department_id' => 2, 'name' => '経理部'],
            ['department_id' => 3, 'name' => '営業部'],
            ['department_id' => 4, 'name' => '総務部'],
        ];

        foreach ($departments as $dept) {
            $monthsBefore = 2; // 開始日は常に2か月前
            $startDate = Carbon::today()->subMonths($monthsBefore);
            $endDate = (clone $startDate)->addDays(10);

            Survey::create([
                'name'         => $dept['name'] . 'アンケート1',
                'description'   => $dept['name'] . 'アンケート1についての説明です。',
                'start_date'    => $startDate->toDateString(),
                'end_date'      => $endDate->toDateString(),
                'office_id'     => 1,
                'department_id' => $dept['department_id'],
            ]);
        }

        // 他社の他部署アンケート追加
        $otherDepartments = [
            [
                'office_id'     => 2,
                'department_id' => 6,
                'name'          => '人材部',
            ],
            [
                'office_id'     => 3,
                'department_id' => 10,
                'name'          => '人材管理部',
            ],
        ];

        foreach ($otherDepartments as $dept) {
            $monthsBefore = 2; // 開始日は常に2か月前
            $startDate = Carbon::today()->subMonths($monthsBefore);
            $endDate = (clone $startDate)->addDays(10);

            Survey::create([
                'name'         => $dept['name'] . 'アンケート1',
                'description'   => $dept['name'] . 'アンケート1についての説明です。',
                'start_date'    => $startDate->toDateString(),
                'end_date'      => $endDate->toDateString(),
                'office_id'     => $dept['office_id'],
                'department_id' => $dept['department_id'],
            ]);
        }
    }
}
