<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Office;

class OfficeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Office::create([
            'name' => '株式会社Art'
        ]);

        Office::create([
            'name' => '株式会社Believe'
        ]);

        Office::create([
            'name' => '株式会社Can'
        ]);
    }
}
