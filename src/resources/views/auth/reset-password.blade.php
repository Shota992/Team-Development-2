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

        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            <!-- トークン -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <!-- メールアドレス -->
            <div class="mb-4">
                <x-input-label for="email" :value="__('メールアドレス')" />
                <x-text-input id="email" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200"
                              type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-600" />
            </div>

            <!-- パスワード -->
            <div class="mb-4">
                <x-input-label for="password" :value="__('新しいパスワード')" />
                <x-text-input id="password" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200"
                              type="password" name="password" required autocomplete="new-password" />
                <p class="text-xs text-gray-500 mt-1 ml-1">
                    ※ 半角英数字記号8文字以上16文字以内（英数字混在）、空白は使用できません
                </p>
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-red-600" />
            </div>

            <!-- パスワード（確認） -->
            <div class="mb-6">
                <x-input-label for="password_confirmation" :value="__('パスワード（確認）')" />
                <x-text-input id="password_confirmation" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-200"
                              type="password" name="password_confirmation" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-sm text-red-600" />
            </div>

            <div>
                <button type="submit"
                        class="w-full bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-indigo-600 transition duration-200">
                    パスワードをリセットする
                </button>
            </div>
        </form>
    </div>

</body>
</html>
