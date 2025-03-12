<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>結果 — 前回比 —</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#f7f8fa] min-h-screen">

    <div class="bg-gray-500 py-3 border-b border-gray-300">
        <div class="max-w-5xl mx-auto px-4">
            <h2 class="text-base font-bold text-white tracking-wide text-left">
                結果 — 前回比 —
            </h2>
        </div>
    </div>

    <!-- メインコンテンツコンテナ -->
    <div class="max-w-5xl mx-auto px-4 py-6">
        <!-- グリッドレイアウト (4列 × 4行 = 16項目) -->
        <div class="grid grid-cols-4 gap-4">

            <!-- カード1: 顧客基盤の安定性 -->
            <div class="bg-white border border-gray-300 shadow-sm rounded p-3 flex flex-col text-center">
                <!-- ラベル (上段) -->
                <div class="text-sm font-bold text-gray-700 mb-2">
                    顧客基盤の安定性
                </div>
                <!-- アイコン + 数値 (横並び) -->
                <div class="flex items-center justify-center gap-2">
                    <img src="{{ asset('images/company.png') }}" alt="顧客基盤の安定性" class="w-8 h-8 object-contain" />
                    <span class="text-2xl font-bold text-gray-800 leading-none">3.8</span>
                </div>
                <!-- 前回比 (下段) -->
                @php
                    $diff = 0.4;
                    $diffColor = $diff >= 0 ? 'text-green-600' : 'text-red-600';
                    $diffSign = $diff > 0 ? '+' : ($diff < 0 ? '' : '±');
                @endphp
                <div class="mt-1 text-sm font-bold leading-none {{ $diffColor }}">
                    前回比: {{ $diffSign }}{{ number_format($diff, 1) }}
                </div>
            </div>

            <!-- カード2: 企業理念の納得度 -->
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

            <!-- カード3: 社会的貢献 -->
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

            <!-- カード4: 責任と顧客・社会への貢献 -->
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

            <!-- 5. 連帯感と相互尊重 -->
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

            <!-- 6. 魅力的な上司・同僚 -->
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

            <!-- 7. 勤務地や会社設備の魅力 -->
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

            <!-- 8. 評価・給与と柔軟な働き方 -->
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

            <!-- 9. 顧客ニーズや事務戦略の伝達 -->
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

            <!-- 10. 上司や会社からの理解 -->
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

            <!-- 11. 公平な評価 -->
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

            <!-- 12. 上司からの適切な教育・支援 -->
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

            <!-- 13. 顧客の期待を上回る提案 -->
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

            <!-- 14. 具体的な目標の共有 -->
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

            <!-- 15. 未来に向けた活動 -->
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

            <!-- 16. ナレッジの標準化 -->
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

        </div> <!-- /grid -->
    </div> <!-- /max-w-5xl -->

    <div class="bg-white border border-gray-300 shadow-sm rounded p-4 w-full max-w-sm">
        <!-- 上部: タイトルとバッジを左右に配置 -->
        <div class="flex items-center justify-between mb-2">
            <h2 class="text-base font-bold text-gray-700">回答状況</h2>
            <div class="bg-gray-200 text-xs text-gray-700 px-2 py-1 rounded-full">
                回答期間中
            </div>
        </div>
    
        <!-- 回答済みラベル -->
        <div class="text-sm text-gray-500">
            回答済み
        </div>
    
        @php
            $answered = 23;  // 回答済み人数
            $total = 50;     // 全体人数
            $percentage = round(($answered / $total) * 100); // 回答率
        @endphp
    
        <!-- 回答数/総数 -->
        <div class="mt-1 text-4xl font-bold text-blue-600 leading-tight">
            {{ $answered }}
            <span class="text-gray-700 text-2xl"> / {{ $total }} 人</span>
        </div>
    
        <!-- 進捗バー -->
        <div class="mt-3 h-2 bg-gray-300 rounded-full ">
            <div class="bg-red-500 h-full" style="width: {{ $percentage }}%;"></div>
        </div>
    
        <!-- 回答率・未回答者数 -->
        <div class="mt-2 flex justify-between text-sm text-gray-700">
            <div>回答率 {{ $percentage }}%</div>
            <div>未回答者 {{ $total - $answered }}人</div>
        </div>
    
        <!-- ボタン -->
        <button class="mt-4 w-full py-2 bg-blue-500 text-white text-sm rounded hover:bg-blue-600">
            未回答者一覧へ
        </button>
    </div>

</body>
</html>