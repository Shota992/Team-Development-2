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

        $optionIdStart = 1; // option_idの開始値
        $optionIdEnd = 7;   // option_idの終了値

        for ($responseDetailId = 1; $responseDetailId <= 1872; $responseDetailId++) { // response_detail_idをループ
            for ($optionId = $optionIdStart; $optionId <= $optionIdEnd; $optionId++) { // 各response_detail_idに対して7つのoption_idを割り当て
                if ($faker->boolean(30)) { // 30%の確率で値を入れる
                    SurveyResponseOptionDetail::create([
                        'response_detail_id' => $responseDetailId,
                        'option_id' => $optionId,
                    ]);
                }
            }

            // option_idをリセットまたは次の範囲に進める
            if ($responseDetailId % 16 === 0) {
                // response_detail_idが16の倍数の場合、option_idをリセット
                $optionIdStart = 1;
                $optionIdEnd = 7;
            } else {
                // 次の範囲に進める
                $optionIdStart += 7;
                $optionIdEnd += 7;
            }
        }
    }
}
