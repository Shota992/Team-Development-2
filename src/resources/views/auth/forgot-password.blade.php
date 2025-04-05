<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>パスワード再設定</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            background-color: #E6F7FF;
            font-family: 'Helvetica Neue', sans-serif;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center">

    <div class="w-full max-w-md bg-white p-8 rounded-lg shadow-lg">
        <div class="text-center mb-6">
            <h1 class="text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-sky-500 mb-2">
                get mild
            </h1>
            <h2 class="text-lg font-semibold text-gray-800">パスワード再設定</h2>
        </div>

        <!-- 説明文 -->
        <p class="text-sm text-gray-600 text-center mb-4">
            登録メールアドレスを入力してください。<br>
            パスワード再設定用リンクをお送りします。
        </p>

        <!-- セッションメッセージ -->
        <x-auth-session-status class="mb-4 text-center text-sm text-green-600" :status="session('status')" />

        <!-- エラーメッセージ -->
        <x-input-error :messages="$errors->get('email')" class="mb-4 text-center text-sm text-red-600" />

        <!-- フォーム -->
        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="mb-4">
                <x-input-label for="email" :value="__('メールアドレス')" class="block text-sm font-bold text-gray-700" />
                <x-text-input id="email" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200"
                              type="email" name="email" :value="old('email')" placeholder="example@gmail.com" required autofocus />
            </div>

            <div class="mt-6">
                <button type="submit"
                    class="w-full bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-indigo-600 transition duration-200">
                    送信する
                </button>
            </div>
        </form>
    </div>

</body>
</html>
