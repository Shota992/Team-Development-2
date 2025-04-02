<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\DataSummaryService;
use App\Services\OpenAiService;

class ChatController extends Controller
{
    protected $dataSummaryService;
    protected $openAiService;

    public function __construct(DataSummaryService $dataSummaryService, OpenAiService $openAiService)
    {
        $this->dataSummaryService = $dataSummaryService;
        $this->openAiService = $openAiService;
    }

    public function index()
    {
        return view('chat'); // resources/views/chat.blade.php
    }

    public function ask(Request $request)
    {
        $conversation = $request->input('conversation', []);
        $userMessage  = $request->input('userMessage', '');
    
        // 毎回最新のアンケートサマリーを取得
        $dataSummary = $this->dataSummaryService->generateSurveyDataSummary();
    
        // 新しいシステムメッセージ（最新のサマリー）を作成
        $systemMessage = [
            'role'    => 'system',
            'content' => "以下は最新のアンケート結果のサマリーです:\n" . $dataSummary . 
                         "\n上記の情報を踏まえて、ユーザーの質問に柔軟に回答してください。"
        ];
    
        // 会話履歴から既存のシステムメッセージを除去する（もしあれば）
        $filteredConversation = array_filter($conversation, function ($msg) {
            return $msg['role'] !== 'system';
        });
    
        // 最新のシステムメッセージを先頭に追加し、その後にユーザーのメッセージを追加
        $messages = array_merge([$systemMessage], $filteredConversation, [
            ['role' => 'user', 'content' => $userMessage]
        ]);
    
        // AI から回答を取得
        $chatResponse = $this->openAiService->getResponseWithData($dataSummary, $userMessage, $messages);
    
        // 会話履歴にユーザーと AI のメッセージを追加
        $messages[] = ['role' => 'assistant', 'content' => $chatResponse];
    
        return response()->json([
            'chatResponse' => $chatResponse,
            'conversation' => $messages,
        ]);
    }
    
}
