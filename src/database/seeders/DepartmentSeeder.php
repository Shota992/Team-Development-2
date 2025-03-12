<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Department::create([
            'name' => '人事部',
            'kind_id' => 1,
            'office_id' => 1
        ]);

        Department::create([
            'name' => '経理部',
            'kind_id' => 2,
            'office_id' => 1
        ]);

        Department::create([
            'name' => '営業部',
            'kind_id' => 3,
            'office_id' => 1
        ]);

        Department::create([
            'name' => '総務部',
            'kind_id' => 4,
            'office_id' => 1
        ]);

        Department::create([
            'name' => '財務部',
            'kind_id' => 5,
            'office_id' => 1
        ]);

        Department::create([
            'name' => '人材部',
            'kind_id' => 1,
            'office_id' => 2
        ]);

        Department::create([
            'name' => '経理部',
            'kind_id' => 2,
            'office_id' => 2
        ]);

        Department::create([
            'name' => '営業本部',
            'kind_id' => 3,
            'office_id' => 2
        ]);

        Department::create([
            'name' => '総務部',
            'kind_id' => 4,
            'office_id' => 2
        ]);

        Department::create([
            'name' => '人材管理部',
            'kind_id' => 1,
            'office_id' => 3
        ]);

        Department::create([
            'name' => '営業部',
            'kind_id' => 3,
            'office_id' => 3
        ]);

        Department::create([
            'name' => '支援部',
            'kind_id' => null,
            'office_id' => 3
        ]);
    }
}
