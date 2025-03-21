<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>実行施策一覧</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    </head>
    <body>
        <div class="flex justify-between p-5">
            <div class="flex">
                <figure>
                    <img src="{{ asset('images/title_logo.png') }}" alt="" />
                </figure>
                <p class="ml-2 text-2xl font-bold">施策一覧  ー実行施策一覧ー</p>
            </div>
            <div class="flex">
                <p class="mr-3 pt-2">部署を選択：</p>
                <select name="department" id="department" class="border border-customGray w-40 text-center rounded-md">
                    <option value="1">部署1</option>
                    <option value="2">部署2</option>
                    <option value="3">部署3</option>
                    <option value="4">部署4</option>
                    <option value="5">部署5</option>
                </select>
            </div>
        </div>
        <div class="flex ml-2">
            <div class="flex mr-40">
                <p class="mr-3 pt-2">タスク実行開始日：</p>
                <input type="date" name="start_date" id="start_date" class="border border-customGray w-40 text-center rounded-md" pattern="\d{2}/\d{2}/\d{2}" placeholder="yy/mm/dd" />
            </div>
            <div class="flex">
                <p class="mr-3 pt-2">表示範囲：</p>
                <select class="border border-customGray w-40 text-center rounded-md">
                    <option value="1">6ヶ月</option>
                    <option value="2">3ヶ月</option>
                    <option value="3">1ヶ月</option>
                    <option value="4">2週間</option>
                </select>
            </div>
        </div>
        <div class="overflow-x-auto w-full">
    <table class="border-collapse table-auto w-full">
        <thead>
            <tr class="bg-gray-100 text-gray-700">
                <th class="sticky left-0 bg-white px-4 py-2 w-32 border-r-white border border-gray-300" width="200"><p class="w-80">件名</p></th>
                <th class="sticky left-[180px] bg-white px-4 py-2 w-32 border border-gray-300" width="200"><p class="w-80">担当者</p></th>
                @foreach ($dateList as $date)
                    <th class="px-2 py-2 border border-gray-300 text-center">{{ \Carbon\Carbon::parse($date)->format('m/d') }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($measures as $measure)
                {{-- 施策（measure）の行 --}}
                <tr class="measure-row border-b border-gray-300">
                    <td class="sticky left-0 bg-white px-4 py-2 font-bold cursor-pointer border border-gray-300"
                        colspan="2">
                        <button class="toggle-tasks text-blue-500 underline"
                            data-target="tasks-{{ $measure->id }}">
                            {{ $measure->title }}
                        </button>
                    </td>
                    @foreach ($dateList as $date)
                        <td id="measure{{ $measure->id }}_{{ $date }}" class="border border-gray-300"></td>
                    @endforeach
                </tr>

                {{-- タスク（task）の行 - デフォルトで非表示 --}}
                @foreach ($measure->tasks as $task)
                    <tr class="task-row tasks-{{ $measure->id }} hidden border-b border-gray-300">
                        <td class="sticky left-0 bg-white px-4 py-2 border border-gray-300" colspan="2">{{ $task->name }}</td>
                        <td class="sticky left-[220px] bg-white px-4 py-2 border border-gray-300">{{ $task->assignee }}</td>
                        @foreach ($dateList as $date)
                            @php
                                $startDate = \Carbon\Carbon::parse($task->start_date);
                                $endDate = \Carbon\Carbon::parse($task->end_date);
                                $currentDate = \Carbon\Carbon::parse($date);

                                // タスクの期間内かチェック
                                $isWithinRange = $currentDate->between($startDate, $endDate);
                                $isStart = $currentDate->equalTo($startDate);
                                $isEnd = $currentDate->equalTo($endDate);
                            @endphp

                            <td id="task{{ $task->id }}_{{ $date }}" class="border border-gray-300 text-center">
                                @if ($isWithinRange)
                                    <div class="h-5 w-full bg-blue-300
                                        @if($isStart) rounded-l-md @endif
                                        @if($isEnd) rounded-r-md @endif">
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
<script>
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".toggle-tasks").forEach(button => {
        button.addEventListener("click", function () {
            let targetClass = this.dataset.target;
            let rows = document.querySelectorAll("." + targetClass);

            rows.forEach(row => {
                row.style.display = row.style.display === "none" ? "table-row" : "none";
            });
        });
    });
});
</script>
</body>
</html>
