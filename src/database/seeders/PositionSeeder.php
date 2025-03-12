<?php

namespace Database\Seeders;

use App\Models\Position;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 3; $i++) {

            Position::create([
                'office_id' => $i,
                'name' => '社長'
            ]);

            Position::create([
                'office_id' => $i,
                'name' => '取締役'
            ]);

            Position::create([
                'office_id' => $i,
                'name' => '部長'
            ]);

            Position::create([
                'office_id' => $i,
                'name' => '課長'
            ]);

            Position::create([
                'office_id' => $i,
                'name' => '係長'
            ]);

            Position::create([
                'office_id' => $i,
                'name' => '社員'
            ]);
        }
    }
}
