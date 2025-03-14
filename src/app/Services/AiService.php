<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class AiService
{
    public function getFeedback(string $summary): string
    {
        $apiKey = config('services.openai.api_key');
        $url = 'https://api.openai.com/v1/chat/completions';
        $model = 'gpt-4o-mini'; // 利用許諾のあるモデル

        $response = Http::withToken($apiKey)->post($url, [
            'model' => $model,
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'あなたはアンケート結果に基づくフィードバックを提供するアシスタントです。'
                ],
                [
                    'role' => 'user',
                    'content' => "以下のアンケート結果を踏まえて、組織の強みと改善点、次に取るべきアクションを簡潔に提案してください:\n\n{$summary}"
                ]
            ],
            'temperature' => 0.7,
        ]);

        if (!$response->successful()) {
            $json = $response->json();
            if (isset($json['error']['code']) && $json['error']['code'] === 'insufficient_quota') {
                return '利用回数が超えました。プランや請求情報をご確認ください。';
            }
            return 'AIフィードバック呼び出し中にエラーが発生しました。';
        }

        $json = $response->json();
        return $json['choices'][0]['message']['content'] ?? 'フィードバックを取得できませんでした。';
    }
}
