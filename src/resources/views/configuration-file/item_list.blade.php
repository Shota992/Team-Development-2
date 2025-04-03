@extends('layouts.app')

@section('content')

@include('components.sidebar')

@php
    $iconMap = [
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
@endphp

<div class="ml-64">
    <div class="flex justify-between p-5">
        <div class="flex">
            <figure>
                <img src="{{ asset('images/title_logo.png') }}" alt="" />
            </figure>
            <p class="ml-2 text-2xl font-bold">項目一覧</p>
        </div>
    </div>

    <div class="w-fit bg-white rounded-lg shadow-md">
        <div class="flex justify-center rounded-lg overflow-hidden">
            <button class="tab-btn px-8 py-2 font-semibold text-gray-700 transition-colors bg-[#E0F4FF]" data-tab="tab1">
                常設項目
            </button>
            <button class="tab-btn px-8 py-2 font-semibold text-gray-700 transition-colors bg-white hover:bg-gray-100" data-tab="tab2">
                追加項目
            </button>
        </div>
    </div>

    {{-- 常設項目 --}}
    <div id="tab1" class="tab-content mt-4">
        <div class="rounded shadow bg-white max-w-[calc(100vw-3rem)] mt-8 mr-8 scrollbar-visible">
            <table class="table-auto w-full border-separate border-spacing-0 border border-gray-300">
                <thead class="bg-gray-200 text-gray-700">
                    <tr>
                        <th class="w-[90px] px-2 py-2 border sticky left-0 bg-gray-200 z-20">アイコン</th>
                        <th class="w-[150px] px-4 py-2 border sticky left-[90px] bg-gray-200 z-20">項目名</th>
                        <th class="w-[480px] px-6 py-2 border">詳細説明</th>
                        <th class="w-[450px] px-6 py-2 border">質問内容</th>
                        <th class="w-[300px] px-4 py-2 border">回答の理由</th>
                    </tr>
                </thead>
                <tbody class="text-gray-800 bg-white">
                    @foreach ($commonQuestions as $question)
                        <tr>
                            <td class="px-2 py-3 border sticky left-0 bg-white z-10 text-center">
                                @php
                                    $icon = $iconMap[$question->title] ?? 'default.png';
                                @endphp
                                <img src="{{ asset('images/' . $icon) }}" alt="アイコン" class="w-[55px] mx-auto">
                            </td>
                            <td class="px-4 py-3 border sticky left-[90px] bg-white z-10 text-center">{{ $question->title }}</td>
                            <td class="px-6 py-3 border">{{ $question->description }}</td>
                            <td class="px-6 py-3 border">{{ $question->text }}</td>
                            <td class="px-4 py-3 border">
                                <details class="cursor-pointer">
                                    <summary class="text-blue-500 hover:underline">詳細を見る</summary>
                                    <ul class="pt-2 list-disc list-inside">
                                        @foreach ($question->surveyQuestionOptions as $option)
                                            <li>{{ $option->text }}</li>
                                        @endforeach
                                    </ul>
                                </details>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- 追加項目 --}}
    <div id="tab2" class="tab-content hidden mt-4">
        <div class="rounded shadow bg-white max-w-[calc(100vw-3rem)] mt-8 mr-8 scrollbar-visible">
            <table class="table-auto w-full border-separate border-spacing-0 border border-gray-300">
                <thead class="bg-gray-200 text-gray-700">
                    <tr>
                        <th class="w-[90px] px-2 py-2 border sticky left-0 bg-gray-200 z-20">アイコン</th>
                        <th class="w-[150px] px-4 py-2 border sticky left-[90px] bg-gray-200 z-20">項目名</th>
                        <th class="w-[480px] px-6 py-2 border">詳細説明</th>
                        <th class="w-[450px] px-6 py-2 border">質問内容</th>
                        <th class="w-[300px] px-4 py-2 border">回答の理由</th>
                        <th class="w-[300px] px-4 py-2 border">アクション</th>
                    </tr>
                </thead>
                <tbody class="text-gray-800 bg-white">
                    @foreach ($customQuestions as $question)
                        <tr>
                            <td class="px-2 py-3 border sticky left-0 bg-white z-10 text-center">
                                @php
                                    $icon = $iconMap[$question->title] ?? 'default.png';
                                @endphp
                                <img src="{{ asset('images/' . $icon) }}" alt="アイコン" class="w-[55px] mx-auto">
                            </td>
                            <td class="px-4 py-3 border sticky left-[90px] bg-white z-10 text-center">{{ $question->title }}</td>
                            <td class="px-6 py-3 border">{{ $question->description }}</td>
                            <td class="px-6 py-3 border">{{ $question->text }}</td>
                            <td class="px-4 py-3 border">
                                <details class="cursor-pointer">
                                    <summary class="text-blue-500 hover:underline">詳細を見る</summary>
                                    <ul class="pt-2 list-disc list-inside">
                                        @foreach ($question->surveyQuestionOptions as $option)
                                            <li>{{ $option->text }}</li>
                                        @endforeach
                                    </ul>
                                </details>
                            </td>
                            <td class="px-4 py-3 border">
                                <div class="flex gap-2">
                            
                                    {{-- 編集するボタン --}}
                                    <a href="{{ route('survey_questions.edit', $question->id) }}"
                                       class="px-5 py-3 rounded-[15px] text-[#00A6FF] bg-[#00A6FF1A] hover:bg-[#00A6FF33] transition text-center">
                                        編集する
                                    </a>
                            
                                    {{-- 削除するボタン --}}
                                    <form action="{{ route('survey_questions.destroy', $question->id) }}" method="POST" onsubmit="return confirmDelete();">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-5 py-3 rounded-[15px] text-[#FF7676] bg-[#FF76761A] hover:bg-[#FF767633] transition">
                                            削除する
                                        </button>
                                    </form>
                                    
                                    <script>
                                        function confirmDelete() {
                                            return confirm('この項目とすべての回答データを削除します。よろしいですか？');
                                        }
                                    </script>
                                </div>
                            </td>
                            
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="flex justify-center mt-8">
            <a href="{{ route('survey_questions.create') }}" class="w-[360px] py-3 text-white text-center bg-[#86D4FE] hover:bg-[#5EC6FD] rounded-[50px] shadow-md transition">
                項目を追加する
            </a>
        </div>
    </div>
</div>

<script>
    const tabs = document.querySelectorAll('.tab-btn');
    const contents = document.querySelectorAll('.tab-content');

    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            tabs.forEach(t => t.classList.remove('bg-[#E0F4FF]'));
            tabs.forEach(t => t.classList.add('bg-white', 'hover:bg-gray-100'));

            tab.classList.remove('bg-white', 'hover:bg-gray-100');
            tab.classList.add('bg-[#E0F4FF]');

            contents.forEach(content => content.classList.add('hidden'));
            document.getElementById(tab.dataset.tab).classList.remove('hidden');
        });
    });
</script>

@endsection
