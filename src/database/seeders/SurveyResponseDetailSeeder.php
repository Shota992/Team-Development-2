<?php

namespace Database\Seeders;

use App\Models\SurveyResponseDetail;
use Faker\Factory as Faker;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SurveyResponseDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $faker = Faker::create();

        for ($i = 0; $i < 5; $i++) {
            for ($responseId = 1 + ($i * 13); $responseId <= 13 + ($i * 13); $responseId++) {
                for ($questionId = 1; $questionId <= 16; $questionId++) {
                    SurveyResponseDetail::create([
                        'response_id' => $responseId,
                        'question_id' => $questionId,
                        'rating' => $faker->numberBetween(1, 5),
                        'free_text' => null,
                    ]);
                }
            }
        }

        for ($responseId = 66; $responseId <= 77; $responseId++) {
            for ($questionId = 1; $questionId <= 16; $questionId++) {
                SurveyResponseDetail::create([
                    'response_id' => $responseId,
                    'question_id' => $questionId,
                    'rating' => $faker->numberBetween(1, 5),
                    'free_text' => 'この数値にした理由は〇〇です',
                ]);
            }
        }

        for ($i = 0; $i < 4; $i++) {
            for ($responseId = 78 + ($i * 10); $responseId <= 87 + ($i * 10); $responseId++) {
                for ($questionId = 1; $questionId <= 16; $questionId++) {
                    SurveyResponseDetail::create([
                        'response_id' => $responseId,
                        'question_id' => $questionId,
                        'rating' => $faker->numberBetween(1, 5),
                        'free_text' => null,
                    ]);
                }
            }
        }
    }
}
