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
        $this->call(DepartmentKindSeeder::class);
        $this->call(OfficeSeeder::class);
        $this->call(PositionSeeder::class);
        $this->call(DepartmentSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(SurveySeeder::class);
        $this->call(SurveyQuestionSeeder::class);
        $this->call(SurveyQuestionOptionSeeder::class);
        $this->call(SurveyResponseSeeder::class);
        $this->call(SurveyResponseDetailSeeder::class);
        $this->call(SurveyResponseOptionDetailSeeder::class);
        $this->call(MeasureSeeder::class);
        $this->call(TaskSeeder::class);
        $this->call(EvaluationSeeder::class);
        $this->call(EvaluationTaskSeeder::class);
    }
}
