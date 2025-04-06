<?php

namespace Database\Seeders;

use App\Models\Survey;
use App\Models\SurveyResponse;
use App\Models\SurveyUserToken;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SurveyResponseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($surveyId = 1; $surveyId <= 5; $surveyId++) {
            for ($userId = 1; $userId <= 13; $userId++) {
            SurveyResponse::create([
                'survey_id' => $surveyId,
            ]);
            SurveyUserToken::create([
                'survey_id' => $surveyId,
                'user_id' => $userId,
                'token' => Str::random(50),
                'answered' => true,
            ]);
            }
        }

        for ($i = 1; $i <= 10; $i++) {
            SurveyResponse::create([
                'survey_id' => 6,
            ]);
            SurveyUserToken::create([
                'survey_id' => 6,
                'user_id' => $i,
                'token' => Str::random(50),
                'answered' => true,
            ]);
        }

        for ($i = 11; $i <= 13; $i++) {
            SurveyResponse::create([
                'survey_id' => 6,
            ]);
            SurveyUserToken::create([
                'survey_id' => 6,
                'user_id' => $i,
                'token' => Str::random(50),
                'answered' => false,
            ]);
        }

        for ($surveyId = 7; $surveyId <= 9; $surveyId++) {
            for ($i = 0; $i < 3; $i++) {
            for ($userId = 14 + ($i * 10); $userId <= 23 + ($i * 10); $userId++) {
                SurveyResponse::create([
                'survey_id' => $surveyId,
                ]);
                SurveyUserToken::create([
                    'survey_id' => $surveyId,
                    'user_id' => $userId,
                    'token' => Str::random(50),
                    'answered' => true,
                ]);
            }
            }
        }

        for ($i = 52; $i <= 61; $i++) {
            SurveyResponse::create([
                'survey_id' => 10,
            ]);
            SurveyUserToken::create([
                'survey_id' => 10,
                'user_id' => $i,
                'token' => Str::random(50),
                'answered' => true,
            ]);
        }

        for ($i = 92; $i <= 101; $i++) {
            SurveyResponse::create([
                'survey_id' => 11,
            ]);
            SurveyUserToken::create([
                'survey_id' => 11,
                'user_id' => $i,
                'token' => Str::random(50),
                'answered' => true,
            ]);
        }
    }
}
