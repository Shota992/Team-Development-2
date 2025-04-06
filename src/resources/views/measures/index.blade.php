<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>実行施策一覧 - Kompass</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
</head>
<body>
    @include('components.sidebar')
    <div class="ml-64">
        <div class="flex justify-between p-5">
            <div class="flex">
                <figure>
                    <img src="{{ asset('images/title_logo.png') }}" alt="" />
                </figure>
                <p class="ml-2 text-2xl font-bold">施策一覧 ー実行施策一覧ー</p>
            </div>
        </div>
        <form method="GET" action="{{ route('measures.index') }}" id="dateForm">
            <div class="flex ml-2">
                <div class="flex mr-40">
                    <p class="mr-3 pt-2">タスク実行開始日：</p>
                    <input type="date" name="base_date" id="base_date" class="border border-customGray w-40 text-center rounded-md" value="{{ $baseDate->format('Y-m-d') }}" />
                </div>
                <div class="flex">
                    <p class="mr-3 pt-2">表示範囲：</p>
                    <select name="display_range" id="display_range" class="border border-custom-gray w-40 text-center rounded-md">
                        <option value="1" {{ $displayRange == 1 ? 'selected' : '' }}>1ヶ月</option>
                        <option value="3" {{ $displayRange == 3 ? 'selected' : '' }}>3ヶ月</option>
                        <option value="6" {{ $displayRange == 6 ? 'selected' : '' }}>6ヶ月</option>
                    </select>
                </div>
            </div>
        </form>
        <div class="flex justify-between pt-3">
            <div class="flex items-center justify-center gap-4 text-black">
                <a href="{{ route('measures.index', ['base_date' => $baseDate->copy()->subWeek()->format('Y-m-d'), 'display_range' => $displayRange]) }}" class="flex items-center text-sky-500">
                    <span class="text-gray-500 text-2xl mr-1 mb-1">&lt;</span>
                    <span>1週間前へ</span>
                </a>

                <span class="h-5 border-l border-gray-400"></span>

                <span>
                    {{ $baseDate->format('Y/m/d') }}
                </span>

                <span class="h-5 border-l border-gray-400"></span>

                <a href="{{ route('measures.index', ['base_date' => $baseDate->copy()->addWeek()->format('Y-m-d'), 'display_range' => $displayRange]) }}" class="flex items-center text-sky-500">
                    <span>1週間後へ</span>
                    <span class="text-gray-500 text-2xl ml-1 mb-1">&gt;</span>
                </a>
            </div>
            <div class="flex overflow-hidden rounded-full border border-gray-400 w-max text-center text-sm mb-3 mr-6">
                <div class="bg-light-red text-gray-800 px-4 py-1">
                    遅延中
                </div>
                <div class="bg-light-blue text-gray-800 border-l border-gray-300 px-4 py-1">
                    対応中
                </div>
                <div class="bg-chart-do-gray text-gray-800 border-l border-gray-300 px-4 py-1">
                    完了
                </div>
            </div>
        </div>
        <div class="overflow-x-auto w-49/50">
            <table class="border-collapse border-chart-border-gray table-auto mt-3">
                <thead>
                    <tr class="bg-chart-gray text-gray-700">
                        <th class="sticky z-30 left-0 bg-chart-gray px-4 py-2 w-32 border border-chart-border-gray" colspan="2">
                        </th>
                        @php
                        $currentMonth = \Carbon\Carbon::parse($dateList[0])->format('Y年n月');
                        $colspan = 0;
                        @endphp
                        @foreach ($dateList as $date)
                        @php
                        $dateMonth = \Carbon\Carbon::parse($date)->format('Y年n月');
                        if ($dateMonth !== $currentMonth) {
                        echo "<th class=' bg-chart-gray px-4 py-2 border border-chart-border-gray text-center' colspan='{$colspan}'>{$currentMonth}</th>";
                        $currentMonth = $dateMonth;
                        $colspan = 0;
                        }
                        $colspan++;
                        @endphp
                        @endforeach
                        <th class=" bg-chart-gray px-4 py-2 border border-chart-border-gray text-center" colspan="{{ $colspan }}">
                            {{ $currentMonth }}
                        </th>
                    </tr>
                    <tr class="bg-chart-gray text-gray-700">
                        <th class="sticky z-30 left-0 bg-chart-gray px-4 py-2 w-32 border-r-chart-gray border border-chart-border-gray">
                            <p class="w-60">件名</p>
                        </th>
                        <th class="sticky z-30 left-[272px] bg-chart-gray px-4 py-2 w-32 border border-chart-border-gray">
                            <p class="w-60 pl-20">担当者</p>
                        </th>
                        @foreach ($dateList as $date)
                        @php
                        $currentDate = \Carbon\Carbon::parse($date);

                        // 今日の日付かどうかを判定
                        $isToday = $currentDate->isToday();
                        @endphp
                        <th class="px-2 py-2 border border-chart-border-gray text-center
                            @if ($isToday) border-2 bg-light-blue  @endif">
                            <p class="w-5 text-center">{{ $currentDate->format('j') }}</p>
                        </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($measures as $measure)
                    {{-- 施策（measure）の行 --}}
                    <tr class="measure-row border-b border-chart-border-gray">
                        <td class="sticky z-30 left-0 bg-white px-4 py-2 font-bold cursor-pointer border border-chart-border-gray toggle-tasks" colspan="2" data-target="tasks-{{ $measure->id }}">
                            {{ $measure->title }}
                        </td>
                        @foreach ($dateList as $date)
                        <td id="measure{{ $measure->id }}_{{ $date }}" class="border border-chart-border-gray bg-white"></td>
                        @endforeach
                    </tr>
                    {{-- タスク（task）の行 - デフォルトで非表示 --}}
                    @foreach ($measure->tasks as $task)
                    <tr class="task-row tasks-{{ $measure->id }} hidden border-b">
                        @php
                        if ($task->status === 1) {
                        $bgClass = 'bg-chart-do-gray';
                        } elseif ($task->status === 0 && \Carbon\Carbon::parse($task->end_date)->isPast()) {
                        $bgClass = 'bg-light-red';
                        } else {
                        $bgClass = 'bg-light-blue';
                        }
                        @endphp
                        <td class="sticky z-30 left-0 {{ $bgClass }} px-4 py-2 border border-chart-border-gray" colspan="2">
                            <div class="grid grid-cols-4 ">
                                <div class="flex col-span-3">
                                    <input type="checkbox" class="mt-1 mr-2 rounded text-black task-checkbox" data-task-id="{{ $task->id }}" {{ $task->status == 1 ? 'checked' : '' }}>{{ $task->name }}
                                </div>
                                <p class="col-span-1">{{ $task->user->name }}</p>
                            </div>
                        </td>
                        @foreach ($dateList as $date)
                        @php
                        $startDate = \Carbon\Carbon::parse($task->start_date);
                        $endDate = \Carbon\Carbon::parse($task->end_date);
                        $currentDate = \Carbon\Carbon::parse($date);

                        // タスクの期間内かチェック
                        $isWithinRange = $currentDate->between($startDate, $endDate);
                        $isStart = $currentDate->equalTo($startDate);
                        $isEnd = $currentDate->equalTo($endDate);

                        if ($task->status === 1) {
                        $taskBarColor = 'bg-chart-do-gray';
                        } elseif ($task->status === 0 && \Carbon\Carbon::parse($task->end_date)->isPast()) {
                        $taskBarColor = 'bg-light-red';
                        } else {
                        $taskBarColor = 'bg-light-blue';
                        }
                        @endphp
                        <td id="task{{ $task->id }}_{{ $date }}" class="border bg-white border-chart-border-gray text-center">
                            @if ($isWithinRange)
                            <div class="h-5 w-full {{$taskBarColor}} relative z-10                                                   @if($isStart) rounded-l-md @endif
                                                    @if($isEnd) rounded-r-md @endif">
                                {{-- @if($isStart || $loop->first)
                                                        <p class="absolute w-80 text-left z-20">{{ $task->name }}</p>
                                @endif --}}
                            </div>
                            @endif
                        </td>
                        @endforeach
                    </tr>
                    @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const toggleTasks = document.querySelectorAll(".toggle-tasks");

            // ページ読み込み時に開閉状況を復元
            toggleTasks.forEach(td => {
                const targetClass = td.dataset.target;
                const rows = document.querySelectorAll("." + targetClass);

                // ローカルストレージから開閉状況を取得
                const isOpen = localStorage.getItem(targetClass) === "true";

                // 状態に応じて表示・非表示を設定
                rows.forEach(row => {
                    row.style.display = isOpen ? "table-row" : "none";
                });
            });

            // 開閉イベントの設定
            toggleTasks.forEach(td => {
                td.addEventListener("click", function() {
                    const targetClass = this.dataset.target;
                    const rows = document.querySelectorAll("." + targetClass);

                    // 現在の状態を切り替え
                    const isCurrentlyOpen = rows[0].style.display === "table-row";
                    const newState = !isCurrentlyOpen;

                    // 状態をローカルストレージに保存
                    localStorage.setItem(targetClass, newState);

                    // 表示・非表示を切り替え
                    rows.forEach(row => {
                        row.style.display = newState ? "table-row" : "none";
                    });
                });
            });
        });

        document.getElementById('base_date').addEventListener('change', function() {
            document.getElementById('dateForm').submit();
        });

        // selectタグの中身が変更された瞬間にフォームを送信
        document.getElementById('display_range').addEventListener('change', function() {
            document.getElementById('dateForm').submit();
        });

        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll('.task-checkbox').forEach(function(checkbox) {
                checkbox.addEventListener('change', function() {
                    console.log('Checkbox changed:', this.checked);
                    let taskId = this.dataset.taskId;
                    let newStatus = this.checked ? 1 : 0;

                    // チェックボックスの親要素を取得して、スピナーに置き換える
                    let parent = this.parentNode;
                    // 隠す
                    this.style.display = "none";
                    // スピナー要素を作成
                    let spinner = document.createElement("div");
                    spinner.className = "spinner inline-block w-5 h-5 mr-2 border-2 border-t-transparent border-gray-500 rounded-full animate-spin";
                    parent.insertBefore(spinner, parent.firstChild);

                    fetch(`/tasks/${taskId}/toggle`, {
                            method: 'POST'
                            , headers: {
                                'Content-Type': 'application/json'
                                , 'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                            , body: JSON.stringify({
                                status: newStatus
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // 更新成功→ページをリロード
                                location.reload();
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            // エラー時には、スピナーを元のチェックボックスに戻す
                            spinner.remove();
                            this.style.display = "inline-block";
                        });
                });
            });
        });

    </script>
</body>
</html>
