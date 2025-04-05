<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>最新のサーベイ結果</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Google Fonts (例: Nunito) -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Nunito', sans-serif; }
    </style>
</head>
<body class="bg-[#f7f8fa] min-h-screen">
    {{-- サイドバー --}}
    @include('components.sidebar')
    
    <div class="ml-64">
        <!-- 上部バー -->
        <div class="bg-white border-b border-gray-300 py-3 shadow-sm">
            <div class="max-w-6xl mx-auto px-4 flex items-center justify-between">
                <h1 class="text-2xl font-bold text-gray-800">最新のサーベイ結果</h1>
                @if($latestSurvey)
                    <div class="text-sm text-gray-600">
                        回答期間：{{ \Carbon\Carbon::parse($latestSurvey->start_date)->format('Y/m/d') }} ～ 
                        {{ \Carbon\Carbon::parse($latestSurvey->end_date)->format('Y/m/d') }}
                    </div>
                @else
                    <div class="text-sm text-gray-600">回答期間：データなし</div>
                @endif
            </div>
        </div>

        <!-- 部署選択プルダウン -->
        <div class="max-w-6xl mx-auto px-4 py-3">
            <form action="{{ route('dashboard') }}" method="GET" class="bg-white p-4 rounded-lg shadow-md">
                <label for="department_id" class="block text-sm font-bold text-gray-800">部署を選択:</label>
                <select name="department_id" id="department_id" class="mt-1 block w-full rounded-md border-gray-300 p-2">
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ $selectedDepartmentId == $dept->id ? 'selected' : '' }}>
                            {{ $dept->name }}
                        </option>
                    @endforeach
                    <option value="all" {{ $selectedDepartmentId === 'all' ? 'selected' : '' }}>会社全体</option>
                </select>
                <button type="submit" class="mt-3 w-full py-2 bg-blue-400 text-white rounded hover:bg-blue-500 transition">
                    表示
                </button>
            </form>
        </div>

        <!-- メインコンテンツ -->
        @if(!$latestSurvey || now() < \Carbon\Carbon::parse($latestSurvey->start_date))
            <!-- アンケートが存在しない または 開始前の場合 -->
            <div class="max-w-6xl mx-auto px-4 py-6">
                <div class="bg-gray-100 border border-gray-200 p-6 rounded-lg shadow-md text-center">
                    <h2 class="text-xl font-semibold text-gray-700 mb-2">アンケートが存在しません</h2>
                    <p class="text-gray-600">アンケート開始までお待ちください。</p>
                </div>
            </div>
        @else
            <!-- アンケートが開始済みの場合 -->
            <div class="max-w-6xl mx-auto px-4 py-6 grid grid-cols-3 gap-6">
                <!-- 左カラム: 前回比カード群 & 結果一覧テーブル -->
                <div class="col-span-2 space-y-6">
                    @php
                        $percentage = $answerStatus['percentage'] ?? 0;
                    @endphp

                    {{-- 前回比カード部分 --}}
                    @if($percentage < 60)
                        <!-- 回答率が60%未満の場合：かわいらしい警告カード -->
                        <div class="bg-pink-100 border border-pink-200 p-6 rounded-lg shadow-md text-center">
                            <div class="flex justify-center mb-4">
                                <!-- ハートアイコン -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-pink-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M4.318 6.318a4.5 4.5 0 016.364 0L12 7.636l1.318-1.318a4.5 4.5 0 116.364 6.364L12 21.364l-7.682-7.682a4.5 4.5 0 010-6.364z" />
                                </svg>
                            </div>
                            <h2 class="text-xl font-semibold text-pink-700">前回比は表示されません</h2>
                            <p class="mt-2 text-pink-600">
                                十分な回答が集まると、前回比の結果が表示されます！
                            </p>
                        </div>
                    @else
                        <!-- 回答率が60%以上の場合：通常の前回比カード群 -->
                        <div class="bg-white rounded-lg shadow p-6">
                            <h2 class="text-lg font-bold text-gray-800 mb-4">結果 — 前回比 —</h2>
                            <div class="grid grid-cols-4 gap-4">
                                @foreach($cards as $card)
                                    <div class="bg-white border border-gray-200 shadow rounded-lg p-4 flex flex-col text-center">
                                        <div class="text-sm font-bold text-gray-700 mb-2">{{ $card['label'] }}</div>
                                        <div class="flex items-center justify-center gap-2">
                                            <img src="{{ asset('images/' . $card['img']) }}" alt="{{ $card['label'] }}" class="w-8 h-8 object-contain">
                                            <span class="text-2xl font-bold text-gray-800">
                                                {{ number_format($card['score'], 1) }}
                                            </span>
                                        </div>
                                        @php
                                            $diff = $card['diff'];
                                            $diffColor = ($diff >= 0) ? 'text-blue-600' : 'text-red-600';
                                            $diffSign = ($diff > 0) ? '+' : (($diff < 0) ? '' : '±');
                                        @endphp
                                        <div class="mt-2 text-sm font-bold {{ $diffColor }}">
                                            前回比: {{ $diffSign }}{{ number_format($diff, 1) }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- 結果一覧テーブル --}}
                    @php
                        if($percentage < 60) {
                            // 回答率が低い場合は、最新アンケート結果（先頭）を除外
                            $filteredDates = collect($surveyDates)->slice(1)->values();
                            $filteredCards = collect($cards)->map(function($card) {
                                $card['values'] = collect($card['values'])->slice(1)->values()->toArray();
                                return $card;
                            })->toArray();
                        } else {
                            // 回答率が十分なら、最新アンケート結果も表示
                            $filteredDates = $surveyDates;
                            $filteredCards = $cards;
                        }
                    @endphp

                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-lg font-bold text-gray-800 mb-4">結果一覧</h2>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm border-collapse">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="border border-gray-200 p-2 text-left">項目</th>
                                        <th class="border border-gray-200 p-2 text-center">平均</th>
                                        @foreach ($filteredDates as $date)
                                            <th class="border border-gray-200 p-2 text-center">{{ $date }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($filteredCards as $item)
                                        <tr>
                                            <td class="border border-gray-200 p-2 text-left">{{ $item['label'] }}</td>
                                            @php
                                                $avgValue = $item['score'];
                                                $avgBgClass = ($avgValue >= 4) ? 'bg-blue-50' : (($avgValue <= 2.5) ? 'bg-red-50' : '');
                                            @endphp
                                            <td class="border border-gray-200 p-2 text-center {{ $avgBgClass }}">
                                                {{ number_format($avgValue, 1) }}
                                            </td>
                                            @foreach ($item['values'] as $val)
                                                @php
                                                    $valBgClass = ($val >= 4) ? 'bg-blue-50' : (($val <= 2.5) ? 'bg-red-50' : '');
                                                @endphp
                                                <td class="border border-gray-200 p-2 text-center {{ $valBgClass }}">
                                                    {{ number_format($val, 1) }}
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-2 text-xs text-gray-500">青表示：4以上, 赤表示：2.5以下</div>
                    </div>
                </div>
                <!-- /左カラム -->

                <!-- 右カラム: 回答状況カード + AIフィードバックカード -->
                @if($latestSurvey && now() >= \Carbon\Carbon::parse($latestSurvey->start_date))
                    <div class="col-span-1 space-y-6">
                        <!-- 回答状況カード -->
                        <div class="bg-white rounded-lg shadow p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h2 class="text-lg font-bold text-gray-800">回答状況</h2>
                                @if($latestSurvey && \Carbon\Carbon::parse($latestSurvey->end_date)->isPast())
                                    <div class="bg-gray-400 text-xs text-gray-200 px-2 py-1 rounded-full">回収済み</div>
                                @else
                                    <div class="bg-gray-200 text-xs text-gray-700 px-2 py-1 rounded-full">回答期間中</div>
                                @endif
                            </div>
                            <div class="text-sm text-gray-600 mb-2">回答済み</div>
                            @php
                                $answered   = $answerStatus['answered'] ?? 0;
                                $total      = $answerStatus['total'] ?? 0;
                                $percentage = $answerStatus['percentage'] ?? 0;
                            @endphp
                            <div class="text-4xl font-bold text-blue-600 leading-tight mb-2">
                                {{ $answered }}<span class="text-gray-700 text-2xl"> / {{ $total }} 人</span>
                            </div>
                            <div class="h-2 bg-gray-300 rounded-full overflow-hidden mb-2">
                                <div class="bg-blue-500 h-full" style="width: {{ $percentage }}%;"></div>
                            </div>
                            <div class="flex justify-between text-sm text-gray-700">
                                <div>回答率 {{ $percentage }}%</div>
                                <div>未回答者 {{ $total - $answered }}人</div>
                            </div>
                            @if ($latestSurvey)
                            <a href="{{ route('survey.unanswered-users', $latestSurvey->id) }}">
                                <button class="mt-4 w-full py-2 bg-blue-400 text-white rounded hover:bg-blue-500 transition">
                                    未回答者一覧へ
                                </button>
                            </a>
                        @endif
                        </div>

                        <!-- AIフィードバックカード -->
                        <div class="bg-white rounded-lg shadow p-6 flex flex-col text-center">
                            <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                <img src="{{ asset('images/ai.png') }}" alt="AIアイコン" class="w-5 h-5 object-contain">
                            </div>
                            <h2 class="text-lg font-bold text-gray-800 mb-4">AIからのフィードバック</h2>
                            @php
                                $aiText = $aiFeedback ?? "AIフィードバックがありません。";
                            @endphp
                            <p class="text-sm text-gray-700 break-words whitespace-pre-wrap mb-6">
                                {{ $aiText }}
                            </p>
                            <a href="{{ route('measures.index') }}" class="block bg-blue-400 hover:bg-blue-500 text-white text-center rounded px-4 py-2 transition">
                                施策一覧
                            </a>
                        </div>
                    </div>
                @endif
                <!-- /右カラム -->
            </div>
        @endif
    </div><!-- /メインコンテンツ -->
</body>
</html>
