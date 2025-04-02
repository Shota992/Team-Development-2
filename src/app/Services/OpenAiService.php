<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OpenAiService
{
    /**
     * 集約されたアンケートサマリーとユーザーの質問に基づいて
     * ChatGPT API を呼び出し、回答を取得する
     *
     * @param string $dataSummary 生成されたアンケートサマリー
     * @param string $userQuestion ユーザーの質問
     * @param array $conversation 既存の会話履歴（任意）
     * @return string AI の回答テキスト
     */
    public function getResponseWithData(string $dataSummary, string $userQuestion, array $conversation = []): string
    {
        $apiKey = config('services.openai.api_key');
        $url = 'https://api.openai.com/v1/chat/completions';

        // 集約情報に基づくシステムメッセージを作成
        // ・サマリーには、詳細な設問内容は含まれておらず、全体傾向のみを示す
        // ・機密情報は省略され、集約情報のみで回答を生成するように指示
        $systemMessage = "以下は最新のアンケート結果のサマリーです:\n" . $dataSummary .
                         "\nこのサマリーは、アンケート全体の傾向を示す集約情報です。詳細な設問内容や個々の回答は機密のため省略しています。" .
                         "\n上記の集約情報に基づいて、原因分析と具体的な改善策、施策の提案をしてください。";

        // 新たな会話メッセージ配列を作成（最新のシステムメッセージとユーザーの質問）
        $newMessages = [
            ['role' => 'system', 'content' => $systemMessage],
            ['role' => 'user', 'content' => $userQuestion]
        ];

        // 既存の会話履歴がある場合、古いシステムメッセージは除外する
        if (!empty($conversation)) {
            $filteredConversation = array_filter($conversation, function ($msg) {
                return $msg['role'] !== 'system';
            });
            $messages = array_merge($filteredConversation, $newMessages);
        } else {
            $messages = $newMessages;
        }

        // ChatGPT API を呼び出す
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type'  => 'application/json',
        ])->post($url, [
            'model'      => 'gpt-3.5-turbo',
            'messages'   => $messages,
            'max_tokens' => 1000,
        ]);

        if ($response->successful()) {
            $choices = $response->json('choices');
            if (isset($choices[0]['message']['content'])) {
                return trim($choices[0]['message']['content']);
            }
        } else {
            // エラー時は詳細をログに出力（本番環境では情報漏洩に注意）
            Log::error('OpenAI API error', [
                'status' => $response->status(),
                'body'   => $response->body()
            ]);
        }
        return 'エラーが発生しました。';
    }
}
