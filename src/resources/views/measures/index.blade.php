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
    </body>
</html>
