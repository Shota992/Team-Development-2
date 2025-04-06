@extends('layouts.plain')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-[#E0F4FF]">
    <div class="w-full max-w-2xl bg-white p-8 rounded-md shadow">
        <div class="flex justify-center mb-6">
            <img src="{{ asset('images/Kompasslogo.jpeg') }}" alt="log in" class="w-[600px] h-auto object-contain">
        </div>

        <!-- エラーメッセージ -->
        @if ($errors->any())
            <div class="mb-4 text-red-600">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            <!-- トークン -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <!-- メールアドレス -->
            <div class="mb-4">
                <label for="email" class="block font-medium text-sm text-gray-700">メールアドレス</label>
                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email', $request->email) }}"
                    class="block mt-1 w-full border-gray-300 rounded-md focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                    required autofocus autocomplete="username"
                />
            </div>

            <!-- 新しいパスワード -->
            <div class="mb-4">
                <label for="password" class="block font-medium text-sm text-gray-700">新しいパスワード</label>
                <input
                    id="password"
                    type="password"
                    name="password"
                    class="block mt-1 w-full border-gray-300 rounded-md focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                    required autocomplete="new-password"
                />
                <p class="text-xs text-gray-500 mt-1 ml-1">
                    ※ 半角英数字記号8文字以上16文字以内（英数字混在）、空白は使用できません
                </p>
            </div>

            <!-- パスワード（確認） -->
            <div class="mb-6">
                <label for="password_confirmation" class="block font-medium text-sm text-gray-700">パスワード（確認）</label>
                <input
                    id="password_confirmation"
                    type="password"
                    name="password_confirmation"
                    class="block mt-1 w-full border-gray-300 rounded-md focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                    required autocomplete="new-password"
                />
            </div>

            <div class="flex justify-center">
                <button
                    type="submit"
                    class="px-10 py-2 bg-[#86D4FE] text-white font-bold rounded-full shadow-md hover:bg-[#69C2FD] transition duration-300"
                >
                    パスワードをリセットする
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
