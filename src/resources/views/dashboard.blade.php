<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>最新のサーベイ結果 - Kompass</title>
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

    @if($showEvaluationPopup)
    <div x-data="{ open: true }" x-show="open" class="fixed inset-0 flex items-center justify-center z-50" style="display: none;">
      <!-- 半透明のオーバーレイ -->
      <div class="absolute inset-0 bg-gray-600 bg-opacity-50" @click="open = false"></div>
      <!-- ポップアップ本体 -->
      <div class="relative bg-white rounded-lg shadow-lg p-6 w-11/12 max-w-md mx-auto">
        <button @click="open = false" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 text-2xl font-bold">&times;</button>
        <h2 class="text-xl font-bold mb-4">施策の評価が可能になりました</h2>
        <p class="text-gray-700 mb-4">
          施策の評価改善が出来る時期になりました。<br>
          評価ページに移動して施策の評価を行ってください。
        </p>
        <div class="text-right">
          <a href="{{ route('measure.no-evaluation') }}" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
            評価ページへ
          </a>
        </div>
      </div>
    </div>
    @endif

    <div class="bg-[#F7F8FA]">
        <div class="min-h-screen pb-8 ml-64 mr-8">
            {{-- ▼ ヘッダー --}}
            <div class="p-5 pt-8">
                <div class="flex justify-between items-center">
                    <div class="flex items-center space-x-6">
                        <figure>
                            <img src="{{ asset('images/title_logo.png') }}" alt="" class="w-8 h-8" />
                        </figure>
                        <p class="text-2xl font-bold whitespace-nowrap">最新のサーベイ結果</p>
                        @if($latestSurvey)
                            <div class="text-base text-gray-600 whitespace-nowrap">
                                回答期間：{{ \Carbon\Carbon::parse($latestSurvey->start_date)->format('Y/m/d') }} ～ 
                                {{ \Carbon\Carbon::parse($latestSurvey->end_date)->format('Y/m/d') }}
                            </div>
                        @else
                            <div class="text-base text-gray-600 whitespace-nowrap">回答期間：データなし</div>
                        @endif
                    </div>

                    <form method="GET" action="{{ route('dashboard') }}">
                        <div class="flex items-center space-x-2">
                            <label for="departmentSelect" class="text-[15px] font-semibold text-gray-800">部署を選択：</label>
                            <select name="department_id" id="departmentSelect"
                                class="px-3 py-1 pr-8 rounded border border-gray-300 bg-white text-black shadow-sm focus:ring focus:ring-blue-200"
                                onchange="this.form.submit()">
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}" {{ $selectedDepartmentId == $dept->id ? 'selected' : '' }}>
                                        {{ $dept->name }}
                                    </option>
                                @endforeach
                                <option value="all" {{ $selectedDepartmentId === 'all' ? 'selected' : '' }}>会社全体</option>
                            </select>
                        </div>
                    </form>
                </div>
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
                            <div class="bg-pink-100 border border-pink-200 p-6 rounded-lg shadow-md text-center">
                                <div class="flex justify-center mb-4">
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
                            <div class="bg-white rounded-lg shadow p-6">
                                <h2 class="text-lg font-bold text-gray-800 mb-4">結果 — 前回比 —</h2>
                                <div class="grid grid-cols-4 gap-4">
                                    @foreach($cards as $card)
                                        @php
                                            $diff = $card['diff'];
                                            $score = number_format($card['score'], 1);
                                            $diffSign = ($diff > 0) ? '+' : (($diff < 0) ? '' : '±');
                                            $diffStyle = ($diff > 0) ? 'color: #00A6FF;' : (($diff < 0) ? 'color: #FFB3B3;' : 'color: #939393;');
                                        @endphp
                                        <div class="bg-white border border-gray-200 shadow rounded-lg p-4 flex flex-col items-center text-center">
                                            <div class="text-sm font-bold text-gray-700 mb-2">{{ $card['label'] }}</div>
                                            <div class="flex items-center justify-center gap-2 mb-2">
                                                <img src="{{ asset('images/' . $card['img']) }}" alt="{{ $card['label'] }}" class="w-8 h-8 object-contain">
                                                <span class="text-2xl font-bold text-[#6699CC]">{{ $score }}</span>
                                            </div>
                                            <div class="text-sm font-bold" style="{{ $diffStyle }}">
                                                前回比: {{ $diffSign }}{{ number_format($diff, 1) }}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- 結果一覧テーブル --}}
                        @php
                            // 条件：回答率が60%未満なら最新アンケート（収集中）を除外、60%以上なら最新も含む
                            if($percentage < 60) {
                                $filteredDates = collect($surveyDates)->slice(1, 4)->values();
                                $filteredCards = collect($cards)->map(function($card) {
                                    $card['values'] = collect($card['values'])->slice(1, 4)->values()->toArray();
                                    return $card;
                                })->toArray();
                            } else {
                                $filteredDates = collect($surveyDates)->slice(0, 4)->values();
                                $filteredCards = collect($cards)->map(function($card) {
                                    $card['values'] = collect($card['values'])->slice(0, 4)->values()->toArray();
                                    return $card;
                                })->toArray();
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
                                                    $validValues = collect($item['values'])->filter(fn($val) => is_numeric($val));
                                                    $avgValue = $validValues->isNotEmpty() ? $validValues->avg() : null;
                                                    $avgBgClass = '';
                                                    if (!is_null($avgValue)) {
                                                        if ($avgValue >= 4) {
                                                            $avgBgClass = 'bg-blue-50';
                                                        } elseif ($avgValue <= 2.5) {
                                                            $avgBgClass = 'bg-red-50';
                                                        }
                                                    }
                                                @endphp
                                                <td class="border border-gray-200 p-2 text-center {{ $avgBgClass }}">
                                                    {{ !is_null($avgValue) ? number_format($avgValue, 1) : 'ー' }}
                                                </td>
                                                @foreach ($item['values'] as $val)
                                                    @php
                                                        $valBgClass = '';
                                                        if (is_numeric($val)) {
                                                            if ($val >= 4) {
                                                                $valBgClass = 'bg-blue-50';
                                                            } elseif ($val <= 2.5) {
                                                                $valBgClass = 'bg-red-50';
                                                            }
                                                        }
                                                    @endphp
                                                    <td class="border border-gray-200 p-2 text-center {{ $valBgClass }}">
                                                        {{ is_null($val) ? 'ー' : number_format($val, 1) }}
                                                    </td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-2 text-xs text-gray-500">
                                青表示：4以上, 赤表示：2.5以下
                            </div>
                        </div>
                    </div>

                    <!-- 右カラム：均等な幅 -->
                    @if($latestSurvey && now() >= \Carbon\Carbon::parse($latestSurvey->start_date))
                    <div class="space-y-6">
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
                            <div class="text-4xl font-bold text-[#66C6F0] leading-tight mb-2">
                                {{ $answered }}<span class="text-gray-700 text-2xl"> / {{ $total }} 人</span>
                            </div>
                            <div class="h-2 bg-gray-300 rounded-full overflow-hidden mb-2">
                                <div class="h-full" style="width: {{ $percentage }}%; background-color: #99DBFF;"></div>
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
                            <div class="flex justify-center mb-4">
                                <img src="{{ asset('images/ai.png') }}" alt="AIアイコン" class="w-16 h-16 object-contain">
                            </div>
                            <h2 class="text-lg font-bold text-gray-800 mb-4">AIからのフィードバック</h2>
                            @if($percentage < 60)
                                <p class="text-sm text-gray-700 break-words whitespace-pre-wrap mb-6">
                                    AIのフィードバックも、十分な結果が集まり次第回答いたします。
                                </p>
                            @else
                                <p class="text-sm text-gray-700 break-words whitespace-pre-wrap mb-6">
                                    {{ $aiFeedback ?? "AIフィードバックがありません。" }}
                                </p>
                            @endif
                            <div class="flex justify-center mt-4">
                                <a href="{{ route('measures.index') }}"
                                   class="w-64 text-center px-14 py-3 bg-[#86D4FE] text-white font-bold rounded-full shadow-lg hover:bg-[#69C2FD] transition duration-300">
                                    施策一覧へ
                                </a>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
</body>
</html>
