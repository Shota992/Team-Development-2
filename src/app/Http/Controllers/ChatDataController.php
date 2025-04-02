<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\DataSummaryService;
use App\Services\OpenAiService;

class ChatDataController extends Controller
{
    public function ask(Request $request)
    {
        $userQuestion = $request->input('userMessage', '');

        $dataSummaryService = new DataSummaryService();
        $dataSummary = $dataSummaryService->generateSurveyDataSummary();

        $openAiService = new OpenAiService();
        $aiResponse = $openAiService->getResponseWithData($dataSummary, $userQuestion);

        return response()->json([
            'chatResponse' => $aiResponse,
        ]);
    }
}