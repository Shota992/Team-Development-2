<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }} - Kompass</title>
    @vite('resources/css/app.css')
</head>
<body>
    <!-- タイトル -->
    <div class="flex flex-col items-center">
        <div class="mt-4 bg-white shadow-md rounded-lg w-11/12 md:w-full max-w-3xl">
            <div class="h-8 bg-button-blue rounded-t-lg"></div>
            <div class="p-6">
                <h2 class="font-bold text-xl">{{ $title }}</h2>
                <p class="mt-2 text-base leading-relaxed">
                    {{ $description }}
                </p>
            </div>
        </div>
    </div>
    <div>
        <div class="flex flex-col items-center mt-8">
            <div class="bg-white shadow-md rounded-lg w-11/12 md:w-full max-w-3xl p-6">
                <h2 class="font-bold text-xl text-custom-blue">アンケートの回答が完了しました</h2>
                <p class="mt-2 text-base leading-relaxed">
                    ご協力ありがとうございました。
                </p>
            </div>
        </div>
    </div>
</body>
