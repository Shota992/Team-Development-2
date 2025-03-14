<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>最新のサーベイ結果</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#f7f8fa] min-h-screen">
    <!-- 上部バー -->
    <div class="bg-white border-b border-gray-300 py-3">
        <div class="max-w-6xl mx-auto px-4 flex items-center justify-between">
            <h1 class="text-lg font-bold text-gray-800">
                最新のサーベイ結果
            </h1>
            @if($latestSurvey)
                <div class="text-sm text-gray-700">
                    回答期間：{{ \Carbon\Carbon::parse($latestSurvey->start_date)->format('Y/m/d') }} ～ {{ \Carbon\Carbon::parse($latestSurvey->end_date)->format('Y/m/d') }}
                </div>
            @else
                <div class="text-sm text-gray-700">
                    回答期間：データなし
                </div>
            @endif
        </div>
    </div>

    <!-- 部署選択プルダウン -->
    <div class="max-w-6xl mx-auto px-4 py-3">
        <form action="{{ route('dashboard') }}" method="GET">
            <label for="department_id" class="block text-sm font-bold text-gray-700">部署を選択:</label>
            <select name="department_id" id="department_id" class="mt-1 block w-full rounded-md border-gray-300">
                @foreach($departments as $dept)
                    <option value="{{ $dept->id }}" {{ $selectedDepartmentId == $dept->id ? 'selected' : '' }}>
                        {{ $dept->name }}
                    </option>
                @endforeach
                <option value="all" {{ $selectedDepartmentId === 'all' ? 'selected' : '' }}>会社全体</option>
            </select>
            <button type="submit" class="mt-2 px-4 py-2 bg-blue-500 text-white rounded">表示</button>
        </form>
    </div>

    <!-- メインコンテンツ: 3カラム -->
    <div class="max-w-6xl mx-auto px-4 py-6 grid grid-cols-3 gap-4">
        <!-- 左カラム: 結果―前回比―カード群 と 結果一覧テーブル -->
        <div class="col-span-2 space-y-6">
            <!-- 結果―前回比― (16カード) -->
            <div class="bg-white rounded shadow p-4">
                <h2 class="text-base font-bold text-gray-700 mb-2">
                    結果 — 前回比 —
                </h2>
                <div class="grid grid-cols-4 gap-4">
                    @foreach($cards as $card)
                        <div class="bg-white border border-gray-300 shadow-sm rounded p-3 flex flex-col text-center">
                            <div class="text-sm font-bold text-gray-700 mb-2">
                                {{ $card['label'] }}
                            </div>
                            <div class="flex items-center justify-center gap-2">
                                <img src="{{ asset('images/' . $card['img']) }}" alt="{{ $card['label'] }}" class="w-8 h-8 object-contain" />
                                <span class="text-2xl font-bold text-gray-800 leading-none">
                                    {{ number_format($card['score'], 1) }}
                                </span>
                            </div>
                            @php
                                $diff = $card['diff'];
                                $diffColor = ($diff >= 0) ? 'text-green-600' : 'text-red-600';
                                $diffSign = ($diff > 0) ? '+' : (($diff < 0) ? '' : '±');
                            @endphp
                            <div class="mt-1 text-sm font-bold leading-none {{ $diffColor }}">
                                前回比: {{ $diffSign }}{{ number_format($diff, 1) }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- 結果一覧テーブル -->
            <div class="bg-white rounded shadow p-4">
                <h2 class="text-base font-bold text-gray-700 mb-2">結果一覧</h2>
                <table class="min-w-full border-collapse border text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border p-2">項目</th>
                            <th class="border p-2">平均</th>
                            @foreach ($surveyDates as $date)
                                <th class="border p-2">{{ $date }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cards as $item)
                            <tr>
                                <td class="border p-2 text-left">{{ $item['label'] }}</td>
                                @php
                                    $avgValue = $item['score'];
                                    $avgClass = ($avgValue >= 4) ? 'text-blue-600'
                                               : (($avgValue <= 2.5) ? 'text-red-600' : '');
                                @endphp
                                <td class="border p-2 text-center">
                                    <span class="{{ $avgClass }}">
                                        {{ number_format($avgValue, 1) }}
                                    </span>
                                </td>
                                @foreach ($item['values'] as $val)
                                    @php
                                        $valClass = ($val >= 4) ? 'text-blue-600'
                                                  : (($val <= 2.5) ? 'text-red-600' : '');
                                    @endphp
                                    <td class="border p-2 text-center">
                                        <span class="{{ $valClass }}">
                                            {{ number_format($val, 1) }}
                                        </span>
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-2 text-xs text-gray-500">
                    青表示：4以上, 赤表示：2.5以下
                </div>
            </div>
        </div>
        <!-- /左カラム -->

        <!-- 右カラム: 回答状況カード + AIフィードバックカード -->
        <div class="flex flex-col space-y-6">
            <!-- 回答状況カード -->
            <div class="bg-white border border-gray-300 shadow-sm rounded p-4">
                <div class="flex items-center justify-between mb-2">
                    <h2 class="text-base font-bold text-gray-700">回答状況</h2>
                    @if($latestSurvey && \Carbon\Carbon::parse($latestSurvey->end_date)->isPast())
                        <div class="bg-gray-400 text-xs text-gray-200 px-2 py-1 rounded-full">
                            回収済み
                        </div>
                    @else
                        <div class="bg-gray-200 text-xs text-gray-700 px-2 py-1 rounded-full">
                            回答期間中
                        </div>
                    @endif
                </div>
                <div class="text-sm text-gray-500">
                    回答済み
                </div>
                @php
                    $answered   = $answerStatus['answered'] ?? 0;
                    $total      = $answerStatus['total'] ?? 0;
                    $percentage = $answerStatus['percentage'] ?? 0;
                @endphp
                <div class="mt-1 text-4xl font-bold text-blue-600 leading-tight">
                    {{ $answered }}<span class="text-gray-700 text-2xl"> / {{ $total }} 人</span>
                </div>
                <div class="mt-3 h-2 bg-gray-300 rounded-full overflow-hidden">
                    <div class="bg-blue-500 h-full" style="width: {{ $percentage }}%;"></div>
                </div>
                <div class="mt-2 flex justify-between text-sm text-gray-700">
                    <div>回答率 {{ $percentage }}%</div>
                    <div>未回答者 {{ $total - $answered }}人</div>
                </div>
                <button class="mt-4 w-full py-2 bg-blue-500 text-white text-sm rounded hover:bg-blue-600">
                    未回答者一覧へ
                </button>
            </div>
            <!-- /回答状況カード -->

            <!-- AIフィードバックカード -->
            <div class="bg-white border border-gray-300 shadow-sm rounded p-4 w-full flex flex-col items-center text-center">
                <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center mb-3">
                    <img src="{{ asset('images/ai.png') }}" alt="AIアイコン" class="w-5 h-5 object-contain">
                </div>
                <h2 class="text-base font-bold text-gray-700 mb-2">
                    AIからのフィードバック
                </h2>
                @php
                    $aiText = $aiFeedback ?? "AIフィードバックがありません。";
                @endphp
                <p class="text-sm text-gray-700 whitespace-pre-wrap mb-4">
                    {{ $aiText }}
                </p>
                <button class="mt-auto px-4 py-2 bg-blue-500 text-white text-sm rounded hover:bg-blue-600">
                    施策立案へ
                </button>
            </div>
            <!-- /AIフィードバックカード -->
        </div>
        <!-- /右カラム -->
    </div>
    <!-- /メインコンテンツ -->
</body>
</html>
