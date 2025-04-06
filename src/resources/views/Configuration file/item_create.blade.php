<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>項目追加 - Kompass</title>
</head>
<body>
    @include('components.sidebar')
    <div class="ml-64">
        <div>
            <div class="flex justify-between p-5 pt-8">
                <div class="flex">
                    <figure>
                        <img src="{{ asset('images/title_logo.png') }}" alt="" />
                    </figure>
                    <p class="ml-2 text-2xl font-bold">項目追加</p>
                </div>
            </div>
        </div>
        <!-- パンくずリスト -->
        <nav class="text-base text-[#939393] mb-4 ml-4" aria-label="パンくずリスト">
            <ol class="list-reset flex items-center">
                <li>
                    <a href="" class="hover:underline">項目一覧</a><!-- 後でリンクを埋め込む -->
                </li>
                <li><span class="mx-2">&gt;</span></li>
                <li class="text-[#939393]">項目追加</li>
            </ol>
        </nav>
    </div>
</body>
</html>
