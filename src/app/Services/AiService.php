<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class AiService
{
    /**
     * 最新アンケート結果の要約テキストから、AIフィードバックを取得する
     *
     * @param string $summary 最新アンケート結果の要約テキスト
     * @return string AIからのフィードバック
     */
    public function getFeedback(string $summary): string
    {
        // .env に設定されている API キーを取得
        $apiKey = config('services.openai.api_key');
        $url = 'https://api.openai.com/v1/chat/completions';
        
        // 利用可能なモデルは "gpt-4o-mini" または "o3-mini" です。ここでは gpt-4o-mini を使用。
        $model = 'gpt-4o-mini';

        $response = Http::withToken($apiKey)->post($url, [
            'model' => $model,
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'あなたはアンケート結果に基づくフィードバックを提供するアシスタントです。'
                ],
                [
                    'role' => 'user',
                    'content' => "以下のアンケート結果を踏まえて、組織の強み、改善点、次に取るべきアクションを簡潔に提案してください:\n\n{$summary}"
                ]
            ],
            'temperature' => 0.7,
        ]);

        if ($response->successful()) {
            $json = $response->json();
            return $json['choices'][0]['message']['content'] ?? 'フィードバックを取得できませんでした。';
        }
        return 'AIフィードバック呼び出し中にエラーが発生しました。';
    }
}
