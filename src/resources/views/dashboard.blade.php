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
            <!-- 左: タイトル -->
            <h1 class="text-lg font-bold text-gray-800">
                最新のサーベイ結果
            </h1>
            <!-- 右: 回答期間 -->
            <div class="text-sm text-gray-700">
                回答期間：2025/02/01～2025/02/28
            </div>
        </div>
    </div>

    <!-- メインコンテナ: 2カラム -->
    <div class="max-w-6xl mx-auto px-4 py-6 grid grid-cols-3 gap-4">
        <!-- 左カラム: 結果ー前回比ー (16カード) + 結果一覧テーブル -->
        <div class="col-span-2 space-y-6">
            <!-- 結果ー前回比ー (16カード) -->
            <div class="bg-white rounded shadow p-4">
                <h2 class="text-base font-bold text-gray-700 mb-2">
                    結果 — 前回比 —
                </h2>
                <!-- 4列 × 4行 = 16項目グリッド -->
                <div class="grid grid-cols-4 gap-4">
                    <!-- カード1 -->
                    <div class="bg-white border border-gray-300 shadow-sm rounded p-3 flex flex-col text-center">
                        <div class="text-sm font-bold text-gray-700 mb-2">
                            顧客基盤の安定性
                        </div>
                        <div class="flex items-center justify-center gap-2">
                            <img src="{{ asset('images/company.png') }}" alt="顧客基盤の安定性" class="w-8 h-8 object-contain" />
                            <span class="text-2xl font-bold text-gray-800 leading-none">3.8</span>
                        </div>
                        @php
                            $diff = 0.4;
                            $diffColor = $diff >= 0 ? 'text-green-600' : 'text-red-600';
                            $diffSign = $diff > 0 ? '+' : ($diff < 0 ? '' : '±');
                        @endphp
                        <div class="mt-1 text-sm font-bold leading-none {{ $diffColor }}">
                            前回比: {{ $diffSign }}{{ number_format($diff, 1) }}
                        </div>
                    </div>

                    <!-- カード2 -->
                    <div class="bg-white border border-gray-300 shadow-sm rounded p-3 flex flex-col text-center">
                        <div class="text-sm font-bold text-gray-700 mb-2">
                            企業理念の納得度
                        </div>
                        <div class="flex items-center justify-center gap-2">
                            <img src="{{ asset('images/Corporate Philosophy.png') }}" alt="企業理念の納得度" class="w-8 h-8 object-contain" />
                            <span class="text-2xl font-bold text-gray-800 leading-none">3.3</span>
                        </div>
                        @php
                            $diff = -0.2;
                            $diffColor = $diff >= 0 ? 'text-green-600' : 'text-red-600';
                            $diffSign = $diff > 0 ? '+' : ($diff < 0 ? '' : '±');
                        @endphp
                        <div class="mt-1 text-sm font-bold leading-none {{ $diffColor }}">
                            前回比: {{ $diffSign }}{{ number_format($diff, 1) }}
                        </div>
                    </div>

                    <!-- カード3 -->
                    <div class="bg-white border border-gray-300 shadow-sm rounded p-3 flex flex-col text-center">
                        <div class="text-sm font-bold text-gray-700 mb-2">
                            社会的貢献
                        </div>
                        <div class="flex items-center justify-center gap-2">
                            <img src="{{ asset('images/society.png') }}" alt="社会的貢献" class="w-8 h-8 object-contain" />
                            <span class="text-2xl font-bold text-gray-800 leading-none">2.9</span>
                        </div>
                        @php
                            $diff = -0.5;
                            $diffColor = $diff >= 0 ? 'text-green-600' : 'text-red-600';
                            $diffSign = $diff > 0 ? '+' : ($diff < 0 ? '' : '±');
                        @endphp
                        <div class="mt-1 text-sm font-bold leading-none {{ $diffColor }}">
                            前回比: {{ $diffSign }}{{ number_format($diff, 1) }}
                        </div>
                    </div>

                    <!-- カード4 -->
                    <div class="bg-white border border-gray-300 shadow-sm rounded p-3 flex flex-col text-center">
                        <div class="text-sm font-bold text-gray-700 mb-2">
                            責任と顧客・社会への貢献
                        </div>
                        <div class="flex items-center justify-center gap-2">
                            <img src="{{ asset('images/responsibility.png') }}" alt="責任と顧客・社会への貢献" class="w-8 h-8 object-contain" />
                            <span class="text-2xl font-bold text-gray-800 leading-none">4.1</span>
                        </div>
                        @php
                            $diff = 1.2;
                            $diffColor = $diff >= 0 ? 'text-green-600' : 'text-red-600';
                            $diffSign = $diff > 0 ? '+' : ($diff < 0 ? '' : '±');
                        @endphp
                        <div class="mt-1 text-sm font-bold leading-none {{ $diffColor }}">
                            前回比: {{ $diffSign }}{{ number_format($diff, 1) }}
                        </div>
                    </div>

                    <!-- カード5 -->
                    <div class="bg-white border border-gray-300 shadow-sm rounded p-3 flex flex-col text-center">
                        <div class="text-sm font-bold text-gray-700 mb-2">
                            連帯感と相互尊重
                        </div>
                        <div class="flex items-center justify-center gap-2">
                            <img src="{{ asset('images/feeling of solidarity.png') }}" alt="連帯感と相互尊重" class="w-8 h-8 object-contain" />
                            <span class="text-2xl font-bold text-gray-800 leading-none">3.3</span>
                        </div>
                        @php
                            $diff = 0.4;
                            $diffColor = $diff >= 0 ? 'text-green-600' : 'text-red-600';
                            $diffSign = $diff > 0 ? '+' : ($diff < 0 ? '' : '±');
                        @endphp
                        <div class="mt-1 text-sm font-bold leading-none {{ $diffColor }}">
                            前回比: {{ $diffSign }}{{ number_format($diff, 1) }}
                        </div>
                    </div>

                    <!-- カード6 -->
                    <div class="bg-white border border-gray-300 shadow-sm rounded p-3 flex flex-col text-center">
                        <div class="text-sm font-bold text-gray-700 mb-2">
                            魅力的な上司・同僚
                        </div>
                        <div class="flex items-center justify-center gap-2">
                            <img src="{{ asset('images/boss.png') }}" alt="魅力的な上司・同僚" class="w-8 h-8 object-contain" />
                            <span class="text-2xl font-bold text-gray-800 leading-none">3.8</span>
                        </div>
                        @php
                            $diff = 0.0;
                            $diffColor = 'text-green-600';
                            $diffSign = '±';
                        @endphp
                        <div class="mt-1 text-sm font-bold leading-none {{ $diffColor }}">
                            前回比: {{ $diffSign }}{{ number_format($diff, 1) }}
                        </div>
                    </div>

                    <!-- カード7 -->
                    <div class="bg-white border border-gray-300 shadow-sm rounded p-3 flex flex-col text-center">
                        <div class="text-sm font-bold text-gray-700 mb-2">
                            勤務地や会社設備の魅力
                        </div>
                        <div class="flex items-center justify-center gap-2">
                            <img src="{{ asset('images/location.png') }}" alt="勤務地や会社設備の魅力" class="w-8 h-8 object-contain" />
                            <span class="text-2xl font-bold text-gray-800 leading-none">3.5</span>
                        </div>
                        @php
                            $diff = -0.5;
                            $diffColor = $diff >= 0 ? 'text-green-600' : 'text-red-600';
                            $diffSign = $diff > 0 ? '+' : ($diff < 0 ? '' : '±');
                        @endphp
                        <div class="mt-1 text-sm font-bold leading-none {{ $diffColor }}">
                            前回比: {{ $diffSign }}{{ number_format($diff, 1) }}
                        </div>
                    </div>

                    <!-- カード8 -->
                    <div class="bg-white border border-gray-300 shadow-sm rounded p-3 flex flex-col text-center">
                        <div class="text-sm font-bold text-gray-700 mb-2">
                            評価・給与と柔軟な働き方
                        </div>
                        <div class="flex items-center justify-center gap-2">
                            <img src="{{ asset('images/work style.png') }}" alt="評価・給与と柔軟な働き方" class="w-8 h-8 object-contain" />
                            <span class="text-2xl font-bold text-gray-800 leading-none">3.2</span>
                        </div>
                        @php
                            $diff = 0.3;
                            $diffColor = $diff >= 0 ? 'text-green-600' : 'text-red-600';
                            $diffSign = $diff > 0 ? '+' : ($diff < 0 ? '' : '±');
                        @endphp
                        <div class="mt-1 text-sm font-bold leading-none {{ $diffColor }}">
                            前回比: {{ $diffSign }}{{ number_format($diff, 1) }}
                        </div>
                    </div>

                    <!-- カード9 -->
                    <div class="bg-white border border-gray-300 shadow-sm rounded p-3 flex flex-col text-center">
                        <div class="text-sm font-bold text-gray-700 mb-2">
                            顧客ニーズや事務戦略の伝達
                        </div>
                        <div class="flex items-center justify-center gap-2">
                            <img src="{{ asset('images/needs.png') }}" alt="顧客ニーズや事務戦略の伝達" class="w-8 h-8 object-contain" />
                            <span class="text-2xl font-bold text-gray-800 leading-none">3.9</span>
                        </div>
                        @php
                            $diff = 0.1;
                            $diffColor = $diff >= 0 ? 'text-green-600' : 'text-red-600';
                            $diffSign = $diff > 0 ? '+' : ($diff < 0 ? '' : '±');
                        @endphp
                        <div class="mt-1 text-sm font-bold leading-none {{ $diffColor }}">
                            前回比: {{ $diffSign }}{{ number_format($diff, 1) }}
                        </div>
                    </div>

                    <!-- カード10 -->
                    <div class="bg-white border border-gray-300 shadow-sm rounded p-3 flex flex-col text-center">
                        <div class="text-sm font-bold text-gray-700 mb-2">
                            上司や会社からの理解
                        </div>
                        <div class="flex items-center justify-center gap-2">
                            <img src="{{ asset('images/understanding.png') }}" alt="上司や会社からの理解" class="w-8 h-8 object-contain" />
                            <span class="text-2xl font-bold text-gray-800 leading-none">3.5</span>
                        </div>
                        @php
                            $diff = -0.2;
                            $diffColor = $diff >= 0 ? 'text-green-600' : 'text-red-600';
                            $diffSign = $diff > 0 ? '+' : ($diff < 0 ? '' : '±');
                        @endphp
                        <div class="mt-1 text-sm font-bold leading-none {{ $diffColor }}">
                            前回比: {{ $diffSign }}{{ number_format($diff, 1) }}
                        </div>
                    </div>

                    <!-- カード11 -->
                    <div class="bg-white border border-gray-300 shadow-sm rounded p-3 flex flex-col text-center">
                        <div class="text-sm font-bold text-gray-700 mb-2">
                            公平な評価
                        </div>
                        <div class="flex items-center justify-center gap-2">
                            <img src="{{ asset('images/evaluation.png') }}" alt="公平な評価" class="w-8 h-8 object-contain" />
                            <span class="text-2xl font-bold text-gray-800 leading-none">3.1</span>
                        </div>
                        @php
                            $diff = 0.0;
                            $diffColor = 'text-green-600';
                            $diffSign = '±';
                        @endphp
                        <div class="mt-1 text-sm font-bold leading-none {{ $diffColor }}">
                            前回比: {{ $diffSign }}{{ number_format($diff, 1) }}
                        </div>
                    </div>

                    <!-- カード12 -->
                    <div class="bg-white border border-gray-300 shadow-sm rounded p-3 flex flex-col text-center">
                        <div class="text-sm font-bold text-gray-700 mb-2">
                            上司からの適切な教育・支援
                        </div>
                        <div class="flex items-center justify-center gap-2">
                            <img src="{{ asset('images/education.png') }}" alt="上司からの適切な教育・支援" class="w-8 h-8 object-contain" />
                            <span class="text-2xl font-bold text-gray-800 leading-none">3.1</span>
                        </div>
                        @php
                            $diff = 0.0;
                            $diffColor = 'text-green-600';
                            $diffSign = '±';
                        @endphp
                        <div class="mt-1 text-sm font-bold leading-none {{ $diffColor }}">
                            前回比: {{ $diffSign }}{{ number_format($diff, 1) }}
                        </div>
                    </div>

                    <!-- カード13 -->
                    <div class="bg-white border border-gray-300 shadow-sm rounded p-3 flex flex-col text-center">
                        <div class="text-sm font-bold text-gray-700 mb-2">
                            顧客の期待を上回る提案
                        </div>
                        <div class="flex items-center justify-center gap-2">
                            <img src="{{ asset('images/expectation.png') }}" alt="顧客の期待を上回る提案" class="w-8 h-8 object-contain" />
                            <span class="text-2xl font-bold text-gray-800 leading-none">4.8</span>
                        </div>
                        @php
                            $diff = 0.5;
                            $diffColor = $diff >= 0 ? 'text-green-600' : 'text-red-600';
                            $diffSign = $diff > 0 ? '+' : ($diff < 0 ? '' : '±');
                        @endphp
                        <div class="mt-1 text-sm font-bold leading-none {{ $diffColor }}">
                            前回比: {{ $diffSign }}{{ number_format($diff, 1) }}
                        </div>
                    </div>

                    <!-- カード14 -->
                    <div class="bg-white border border-gray-300 shadow-sm rounded p-3 flex flex-col text-center">
                        <div class="text-sm font-bold text-gray-700 mb-2">
                            具体的な目標の共有
                        </div>
                        <div class="flex items-center justify-center gap-2">
                            <img src="{{ asset('images/Target.png') }}" alt="具体的な目標の共有" class="w-8 h-8 object-contain" />
                            <span class="text-2xl font-bold text-gray-800 leading-none">2.1</span>
                        </div>
                        @php
                            $diff = -1.2;
                            $diffColor = $diff >= 0 ? 'text-green-600' : 'text-red-600';
                            $diffSign = $diff > 0 ? '+' : ($diff < 0 ? '' : '±');
                        @endphp
                        <div class="mt-1 text-sm font-bold leading-none {{ $diffColor }}">
                            前回比: {{ $diffSign }}{{ number_format($diff, 1) }}
                        </div>
                    </div>

                    <!-- カード15 -->
                    <div class="bg-white border border-gray-300 shadow-sm rounded p-3 flex flex-col text-center">
                        <div class="text-sm font-bold text-gray-700 mb-2">
                            未来に向けた活動
                        </div>
                        <div class="flex items-center justify-center gap-2">
                            <img src="{{ asset('images/future.png') }}" alt="未来に向けた活動" class="w-8 h-8 object-contain" />
                            <span class="text-2xl font-bold text-gray-800 leading-none">3.4</span>
                        </div>
                        @php
                            $diff = 0.5;
                            $diffColor = $diff >= 0 ? 'text-green-600' : 'text-red-600';
                            $diffSign = $diff > 0 ? '+' : ($diff < 0 ? '' : '±');
                        @endphp
                        <div class="mt-1 text-sm font-bold leading-none {{ $diffColor }}">
                            前回比: {{ $diffSign }}{{ number_format($diff, 1) }}
                        </div>
                    </div>

                    <!-- カード16 -->
                    <div class="bg-white border border-gray-300 shadow-sm rounded p-3 flex flex-col text-center">
                        <div class="text-sm font-bold text-gray-700 mb-2">
                            ナレッジの標準化
                        </div>
                        <div class="flex items-center justify-center gap-2">
                            <img src="{{ asset('images/knowledge.png') }}" alt="ナレッジの標準化" class="w-8 h-8 object-contain" />
                            <span class="text-2xl font-bold text-gray-800 leading-none">3.7</span>
                        </div>
                        @php
                            $diff = 0.2;
                            $diffColor = $diff >= 0 ? 'text-green-600' : 'text-red-600';
                            $diffSign = $diff > 0 ? '+' : ($diff < 0 ? '' : '±');
                        @endphp
                        <div class="mt-1 text-sm font-bold leading-none {{ $diffColor }}">
                            前回比: {{ $diffSign }}{{ number_format($diff, 1) }}
                        </div>
                    </div>
                </div><!-- /grid 16 cards -->
            </div><!-- /結果ー前回比ー -->

            <!-- 結果一覧テーブル -->
            <div class="bg-white rounded shadow p-4">
                <h2 class="text-base font-bold text-gray-700 mb-2">結果一覧</h2>
                @php
                    // 過去6回分のアンケート実施日
                    $surveyDates = ['2025/03/01','2025/02/22','2025/02/15','2025/02/08','2025/02/01','2025/01/25'];
                    // 16項目のサンプルデータ (省略せず16項目記述)
                    $items = [
                        [
                          'label'   => '顧客基盤の安定性',
                          'average' => 3.8,
                          'values'  => [3.5, 3.2, 3.9, 4.2, 3.8, 4.0],
                        ],
                        [
                          'label'   => '企業理念の納得度',
                          'average' => 3.3,
                          'values'  => [3.1, 2.9, 3.0, 3.2, 3.4, 3.3],
                        ],
                        [
                          'label'   => '社会的貢献',
                          'average' => 2.9,
                          'values'  => [2.8, 2.5, 2.9, 3.0, 2.7, 2.8],
                        ],
                        [
                          'label'   => '責任と顧客・社会への貢献',
                          'average' => 4.1,
                          'values'  => [4.0, 4.2, 4.1, 4.3, 4.0, 4.1],
                        ],
                        [
                          'label'   => '連帯感と相互尊重',
                          'average' => 3.3,
                          'values'  => [3.2, 3.3, 3.3, 3.4, 3.3, 3.3],
                        ],
                        [
                          'label'   => '魅力的な上司・同僚',
                          'average' => 3.8,
                          'values'  => [3.7, 3.8, 3.8, 3.9, 3.8, 3.8],
                        ],
                        [
                          'label'   => '勤務地や会社設備の魅力',
                          'average' => 3.5,
                          'values'  => [3.3, 3.5, 3.5, 3.6, 3.5, 3.4],
                        ],
                        [
                          'label'   => '評価・給与と柔軟な働き方',
                          'average' => 3.2,
                          'values'  => [3.1, 3.2, 3.2, 3.3, 3.2, 3.2],
                        ],
                        [
                          'label'   => '顧客ニーズや事務戦略の伝達',
                          'average' => 3.9,
                          'values'  => [3.8, 3.9, 3.9, 4.0, 3.9, 3.9],
                        ],
                        [
                          'label'   => '上司や会社からの理解',
                          'average' => 3.5,
                          'values'  => [3.4, 3.5, 3.5, 3.6, 3.5, 3.5],
                        ],
                        [
                          'label'   => '公平な評価',
                          'average' => 3.1,
                          'values'  => [3.0, 3.1, 3.1, 3.2, 3.1, 3.1],
                        ],
                        [
                          'label'   => '上司からの適切な教育・支援',
                          'average' => 3.1,
                          'values'  => [3.0, 3.1, 3.1, 3.2, 3.1, 3.1],
                        ],
                        [
                          'label'   => '顧客の期待を上回る提案',
                          'average' => 4.8,
                          'values'  => [4.7, 4.8, 4.8, 4.9, 4.8, 4.8],
                        ],
                        [
                          'label'   => '具体的な目標の共有',
                          'average' => 2.1,
                          'values'  => [2.0, 2.1, 2.1, 2.2, 2.1, 2.1],
                        ],
                        [
                          'label'   => '未来に向けた活動',
                          'average' => 3.4,
                          'values'  => [3.3, 3.4, 3.4, 3.5, 3.4, 3.4],
                        ],
                        [
                          'label'   => 'ナレッジの標準化',
                          'average' => 3.7,
                          'values'  => [3.6, 3.7, 3.7, 3.8, 3.7, 3.7],
                        ],
                    ];
                @endphp
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
                        @foreach ($items as $item)
                            <tr>
                                <td class="border p-2 text-left">{{ $item['label'] }}</td>
                                @php
                                    $avgValue = $item['average'];
                                    $avgClass = ($avgValue >= 4) ? 'text-blue-600'
                                               : (($avgValue <= 2.5) ? 'text-red-600' : '');
                                @endphp
                                <td class="border p-2 text-center">
                                    <span class="{{ $avgClass }}">{{ number_format($avgValue, 1) }}</span>
                                </td>
                                @foreach($item['values'] as $val)
                                    @php
                                        $valClass = ($val >= 4) ? 'text-blue-600'
                                                  : (($val <= 2.5) ? 'text-red-600' : '');
                                    @endphp
                                    <td class="border p-2 text-center">
                                        <span class="{{ $valClass }}">{{ number_format($val, 1) }}</span>
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
        </div><!-- /左カラム -->

        <!-- 右カラム: 回答状況カード + AIフィードバックカード -->
        <div class="flex flex-col space-y-6">
            <!-- 回答状況カード -->
            <div class="bg-white border border-gray-300 shadow-sm rounded p-4 flex flex-col w-full">
                <div class="flex items-center justify-between mb-2">
                    <h2 class="text-base font-bold text-gray-700">回答状況</h2>
                    <div class="bg-gray-200 text-xs text-gray-700 px-2 py-1 rounded-full">
                        回答期間中
                    </div>
                </div>
                <div class="text-sm text-gray-500">
                    回答済み
                </div>
                @php
                    $answered = 23;  
                    $total = 50;     
                    $percentage = round(($answered / $total) * 100);
                @endphp
                <div class="mt-1 text-4xl font-bold text-blue-600 leading-tight">
                    {{ $answered }}
                    <span class="text-gray-700 text-2xl"> / {{ $total }} 人</span>
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

            <!-- AIフィードバックカード -->
            <div class="bg-white border border-gray-300 shadow-sm rounded p-4 w-full flex flex-col items-center text-center">
                <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center mb-3">
                    <img src="{{ asset('images/ai.png') }}" alt="AIアイコン" class="w-5 h-5 object-contain">
                </div>
                <h2 class="text-base font-bold text-gray-700 mb-2">
                    AIからのフィードバック
                </h2>
                @php
                    // コントローラで取得したAIフィードバックを想定
                    $aiFeedback = $aiFeedback ?? "今回のアンケートでは○○という項目が低いようです。○○に陥っていないですか？
もしそのようであるならば直しましょう！

また、前回はかなり低かった○○という項目に関して回復傾向にあります。
この調子で組織の運営をしていきましょう。";
                @endphp
                <p class="text-sm text-gray-700 whitespace-pre-wrap mb-4">
                    {{ $aiFeedback }}
                </p>
                <button class="mt-auto px-4 py-2 bg-blue-500 text-white text-sm rounded hover:bg-blue-600">
                    施策立案へ
                </button>
            </div>
        </div><!-- /右カラム -->
    </div><!-- /メインコンテナ -->

</body>
</html>
