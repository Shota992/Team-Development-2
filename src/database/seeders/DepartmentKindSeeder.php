<?php

namespace Database\Seeders;

use App\Models\DepartmentKind;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentKindSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DepartmentKind::create([
            'name' => '人事'
        ]);

        DepartmentKind::create([
            'name' => '経理'
        ]);

        DepartmentKind::create([
            'name' => '営業'
        ]);

        DepartmentKind::create([
            'name' => '総務'
        ]);

        DepartmentKind::create([
            'name' => '財務'
        ]);

        DepartmentKind::create([
            'name' => '法務'
        ]);

        DepartmentKind::create([
            'name' => '企画'
        ]);

        DepartmentKind::create([
            'name' => 'マーケティング'
        ]);

        DepartmentKind::create([
            'name' => '広報'
        ]);

        DepartmentKind::create([
            'name' => 'IT'
        ]);

        DepartmentKind::create([
            'name' => '開発'
        ]);

        DepartmentKind::create([
            'name' => '品質管理'
        ]);
    }
}
