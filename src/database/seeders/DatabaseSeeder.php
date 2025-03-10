<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 他のシーダーがある場合はここに記述
        $this->call(UserSeeder::class);
    }
}
