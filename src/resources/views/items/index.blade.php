<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>項目別評価一覧</title>

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
                <p class="ml-2 text-2xl font-bold">項目別評価一覧</p>
            </div>
            <div class="flex">
                <p>部署を選択：</p>
                <select class="ml-4 w-40 text-center border rounded-md">
                    <option>全て</option>
                    <option>部署1</option>
                    <option>部署2</option>
                    <option>部署3</option>
                    <option>部署4</option>
                </select>
            </div>
        </div>
        <div class="max-w-5xl mx-auto bg-white border rounded shadow">
            <!-- ヘッダー -->
            <div class="flex items-center justify-between bg-custom-gray text-white px-4 py-2">
                <span class="text-lg font-semibold">項目</span>
                <div class="flex items-center">
                    <span class="mr-2">項目を選択</span>
                    <select class="px-3 py-1 rounded border text-black w-80 text-center ml-4">
                        <option>顧客基盤の安定性</option>
                        <!-- 他の選択肢もここに追加可能 -->
                    </select>
                </div>
            </div>

            <!-- 内容 -->
            <div class="flex items-start p-4 px-8">
                <!-- 画像（アイコン） -->
                <div class="flex-shrink-0">
                    <img src="" alt="アイコン" class="w-10 h-10" />
                </div>

                <!-- テキストエリア -->
                <div class="ml-8">
                    <h2 class="text-lg font-bold mb-1">顧客基盤の安定性</h2>
                    <p class="text-sm text-gray-700">
                        顧客基盤の安定性とは、企業が長期間にわたって安定した顧客関係を築き、維持している状態です。<br>
                        この項目の数値が高いほど、〇〇ということが言えます。
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
