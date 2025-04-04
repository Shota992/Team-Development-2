<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>部署別比較</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .scrollbar-always-visible {
            overflow-x: scroll;         /* 常にスクロール可能 */
            scrollbar-width: auto;     /* Firefox用 */
        }
    
        .scrollbar-always-visible::-webkit-scrollbar {
            height: 8px;               /* スクロールバーの高さ */
        }
    
        .scrollbar-always-visible::-webkit-scrollbar-thumb {
            background-color: #ccc;    /* スクロールバーの色 */
            border-radius: 4px;
        }
    
        .scrollbar-always-visible::-webkit-scrollbar-track {
            background: #f1f1f1;       /* トラック背景 */
        }
    </style>
    
</head>
<body class="bg-gray-100 font-sans text-gray-800">

@include('components.sidebar')

<main class="ml-64 mr-8">
    {{-- ▼ ヘッダー --}}
    <div>
        <div class="flex justify-between p-5">
            <div class="flex">
                <figure>
                    <img src="{{ asset('images/title_logo.png') }}" alt="" />
                </figure>
                <p class="ml-2 text-2xl font-bold">部署別比較</p>
            </div>
        </div>
    </div>

    {{-- ▼ アンケート選択フォーム --}}
    <form method="GET" action="{{ route('departments.index') }}" class="mb-2 flex items-center gap-4">
        <label for="date" class="text-sm font-medium">年月を選択</label>
        <select name="date" id="date" onchange="this.form.submit()" class="border border-gray-300 rounded-full px-4 py-2 text-sm">
            @foreach($surveyDates as $date)
                <option value="{{ $date }}" @if(request('date') === $date) selected @endif>
                    {{ \Carbon\Carbon::parse($date)->format('Y年n月') }}
                </option>
            @endforeach
        </select>
    </form>

    {{-- ▼ カラーファン（右上） --}}
    <div class="flex justify-end mb-6">
        <div class="flex rounded-full overflow-hidden border border-gray-300 shadow-sm text-sm font-medium">
            <div class="px-3 py-1 bg-[#FFA1A1] text-gray-800">Bad</div>
            <div class="px-3 py-1 bg-[#FFE0E0] text-gray-800 border-l">not Good</div>
            <div class="px-3 py-1 bg-[#ededed] text-gray-800 border-l">Normal</div>
            <div class="px-3 py-1 bg-[#E0F4FF] text-gray-800 border-l">Good</div>
            <div class="px-3 py-1 bg-[#99DBFF] text-gray-800 border-l">very Good</div>
        </div>
    </div>

    {{-- ▼ テーブル --}}
    <div class="overflow-x-auto scrollbar-always-visible">
        <div class="min-w-max w-fit relative">
            <table class="w-full border border-[#C4C4C4] bg-white shadow-sm text-sm border-collapse">
                <thead>
                <tr class="bg-[#f7f7f7] text-center">
                    <th class="w-24 px-4 py-4 border border-[#C4C4C4] text-left font-semibold text-gray-700 bg-white sticky left-0 z-10">部署</th>
                                        @foreach($questions as $question)
                        @php
                            $imageMap = [
                                '顧客基盤の安定性' => 'company.png',
                                '理念戦略への納得感' => 'corporate-philosophy.png',
                                '社会的貢献' => 'society.png',
                                '責任と顧客・社会への貢献' => 'responsibility.png',
                                '連帯感と相互尊重' => 'feeling-solidarity.png',
                                '魅力的な上司・同僚' => 'boss.png',
                                '勤務地や会社設備の魅力' => 'location.png',
                                '評価・給与と柔軟な働き方' => 'work-style.png',
                                '顧客ニーズや事業戦略の伝達' => 'needs.png',
                                '上司や会社からの理解' => 'understanding.png',
                                '公平な評価' => 'evaluation.png',
                                '上司からの適切な教育・支援' => 'education.png',
                                '顧客の期待を上回る提案' => 'expectation.png',
                                '具体的な目標の共有' => 'target.png',
                                '未来に向けた活動' => 'future.png',
                                'ナレッジの標準化' => 'knowledge.png',
                            ];
                            $iconFile = $imageMap[$question->title] ?? 'default.png';
                        @endphp

                    <th class="w-24 px-2 py-2 border-b text-xs text-gray-600 align-top border border-[#C4C4C4]">
                        <div class="flex flex-col items-center justify-start">
                            <div class="w-10 h-10 relative mb-1 shrink-0">
                                <div class="absolute inset-0 bg-white rounded-full"></div>
                                <img src="{{ asset('images/' . $iconFile) }}"
                                    alt="{{ $question->title }}"
                                    class="w-6 h-6 absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2" />
                            </div>
                            <span class="pt-2 text-xs text-center leading-tight break-words w-20">
                                {{ $question->title }}
                            </span>
                        </div>
                    </th>


                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($departments as $department)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-3 border border-[#C4C4C4] text-left font-medium bg-gray-50 whitespace-nowrap text-gray-700 sticky left-0 z-10">
                            {{ $department->name }}
                        </td>
                        @foreach($questions as $question)
                            @php
                                $score = $scores[$department->id][$question->id] ?? null;
                                $bgClass = match(true) {
                                    is_null($score)                     => 'bg-white-100',
                                    $score >= 0    && $score < 2.0      => 'bg-[#FFA1A1]',
                                    $score >= 2.0  && $score < 2.5      => 'bg-[#FFE0E0]',
                                    $score >= 2.5  && $score < 3.5      => 'bg-[#ededed]',
                                    $score >= 3.5  && $score < 4.0      => 'bg-[#E0F4FF]',
                                    $score >= 4.0                       => 'bg-[#99DBFF]',
                                };
                            @endphp
                            <td class="px-3 py-3 border border-[#C4C4C4] text-center align-middle {{ $bgClass }}">
                                {{ $score !== null ? number_format($score, 1) : 'ー' }}
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</main>

</body>
</html>
