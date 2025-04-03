<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>評価/改善済み施策一覧</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
</head>
<body>
    @include('components.sidebar')
    <div class="ml-64">
        @if (session('success'))
        <div id="alert-message" class="fixed top-0 left-0 w-full bg-green-500 text-white text-center py-3 transform -translate-y-full transition-transform duration-500">
            {{ session('success') }}
        </div>
        @endif
        <div class="flex justify-between p-5">
            <div class="flex">
                <figure>
                    <img src="{{ asset('images/title_logo.png') }}" alt="" />
                </figure>
                <p class="ml-2 text-2xl font-bold">評価/改善済み施策一覧</p>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-49/50 pr-3 bg-white border border-chart-border-gray">
                <thead class="bg-chart-gray text-left">
                    <tr>
                        <th class="px-4 py-2 border border-chart-border-gray">施策タイトル</th>
                        <th class="px-4 py-2 border border-chart-border-gray text-center">評価頻度</th>
                        <th class="px-4 py-2 border border-chart-border-gray text-center">評価回数</th>
                        <th class="px-4 py-2 border border-chart-border-gray text-center">最終評価日</th>
                        <th class="px-4 py-2 border border-chart-border-gray text-center">次回評価日</th>
                        <th class="px-4 py-2 border border-chart-border-gray text-center">ステータス</th>
                        {{-- <th class="px-4 py-2 border border-chart-border-gray">アクション</th> --}}
                    </tr>
                </thead>

                <tbody>
                    @foreach ($measures as $measure)
                    <!-- ▼ 施策 -->
                    <tr class="border-t">
                        <td class="px-4 py-2 border w-1/3 border-chart-border-gray font-medium text-sky-500 cursor-pointer toggle-btn" data-target="task-{{ $measure->id }}">
                            <a href="{{ route('measures.evaluation-list-detail', ['id' => $measure->id]) }}" class="hover:underline toggle-disable">
                                {{ $measure->title }}</a><span class="arrow ml-3 text-black">▶</span>
                        </td>
                        <td class="px-4 py-2 border border-chart-border-gray text-center">
                            @php
                            $intervalUnit = $measure->evaluation_interval_unit === 'months' ? 'ヶ月ごと' : ($measure->evaluation_interval_unit === 'weeks' ? '週間ごと' : '');
                            @endphp
                            {{ $measure->evaluation_interval_value }}{{ $intervalUnit }}
                        </td>
                        <td class="px-4 py-2 border border-chart-border-gray text-center">{{ $measure->evaluation_count }}回</td>
                        <td class="px-4 py-2 border border-chart-border-gray text-center">{{ $measure->evaluation_last_date ? $measure->evaluation_last_date->format('Y-m-d') : 'ー' }}</td>
                        <td class="px-4 py-2 border border-chart-border-gray text-center">
                            {{ $measure->evaluation_status == 2 ? 'ー' : $measure->next_evaluation_date }}
                        </td>
                        <td class="px-4 py-2 border border-chart-border-gray text-center">
                            @if ($measure->status == 1)
                            <span class="inline-block px-3 py-1 rounded-full bg-green-200 text-green-700">実行済</span>
                            @elseif ($measure->evaluation_status == 0)
                            <span class="inline-block px-3 py-1 rounded-full bg-gray-200 text-gray-700">未評価</span>
                            @elseif ($measure->evaluation_status == 1)
                            <span class="inline-block px-3 py-1 rounded-full bg-blue-200 text-blue-700">評価済</span>
                            @elseif ($measure->evaluation_status == 2)
                            <span class="inline-block px-3 py-1 rounded-full bg-red-200 text-red-700">完了</span>
                            @endif
                        </td>
                    </tr>

                    <!-- ▼ タスク -->
                    @foreach ($measure->tasks as $task)
                    <tr id="task-{{ $measure->id }}" class="border-t hidden text-sm">
                        <td class="px-6 py-4 border border-chart-border-gray">
                            <div class="mb-2">↳ {{ $task->name }}（{{ $task->user->name }}）</div>
                            <div class=" text-gray-600 pl-6">{{ $task->start_date }} - {{ $task->end_date }}</div>
                        </td>
                        <td class="px-6 py-4 border border-chart-border-gray text-center"></td>
                        <td class="px-6 py-4 border border-chart-border-gray text-center"></td>
                        <td class="px-6 py-4 border border-chart-border-gray text-center"></td>
                        <td class="px-6 py-4 border border-chart-border-gray text-center"></td>
                        <td class="px-6 py-4 border border-chart-border-gray text-center"></td>
                    </tr>
                    @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const toggleButtons = document.querySelectorAll(".toggle-btn");
            const alertMessage = document.getElementById("alert-message");

            if (alertMessage) {
                setTimeout(() => {
                    alertMessage.style.transform = "translateY(0)";
                }, 100);

                setTimeout(() => {
                    alertMessage.style.transform = "translateY(-100%)";
                }, 5500);
            }

            toggleButtons.forEach(btn => {
                btn.addEventListener("click", function() {
                    const targetClass = btn.getAttribute("data-target"); // タスク行のクラス名を取得
                    const targetRows = document.querySelectorAll(`#${targetClass}`); // 該当するすべてのタスク行を取得
                    const arrow = btn.querySelector(".arrow");

                    // 現在の状態を確認（最初の行の状態を基準にする）
                    const isHidden = targetRows[0].classList.contains("hidden");

                    // hiddenクラスを一括で切り替え
                    targetRows.forEach(row => {
                        if (isHidden) {
                            row.classList.remove("hidden");
                        } else {
                            row.classList.add("hidden");
                        }
                    });

                    // 矢印の表示を切り替え
                    arrow.textContent = isHidden ? "▼" : "▶";
                });
            });

            const links = document.querySelectorAll(".toggle-disable");
            links.forEach(link => {
                link.addEventListener("click", function(event) {
                    event.stopPropagation(); // クリックイベントの伝播を停止
                });
            });
        });

    </script>
</body>
</html>
