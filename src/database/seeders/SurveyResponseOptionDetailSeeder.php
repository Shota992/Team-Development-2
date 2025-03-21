<?php

namespace Database\Seeders;

use App\Models\SurveyResponseOptionDetail;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class SurveyResponseOptionDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        for ($i = 0; $i < 6; $i++) {
            $responseDetailIdStart = 1 + ($i * 13);
            $responseDetailIdEnd = 4 + ($i * 13);

            for ($responseDetailId = $responseDetailIdStart; $responseDetailId <= $responseDetailIdEnd; $responseDetailId++) {
                for ($optionId = 1; $optionId <= 119; $optionId++) {
                    if ($faker->boolean(30)) { // 30%の確率で値を入れる
                        SurveyResponseOptionDetail::create([
                            'response_detail_id' => $responseDetailId,
                            'option_id' => $optionId,
                        ]);
                    }
                }
            }
        }
    }
}
