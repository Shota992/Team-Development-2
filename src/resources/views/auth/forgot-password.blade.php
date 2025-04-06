@extends('layouts.plain')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-[#E0F4FF]">
    <div class="w-full max-w-2xl bg-white p-8 rounded-md shadow">
        <div class="flex justify-center mb-4">
            <img src="{{ asset('images/Kompasslogo.jpeg') }}" alt="reset password"
                 class="mx-auto max-w-full w-[600px] h-auto object-contain mb-4">
        </div>
        

        <h2 class="text-lg font-semibold text-center text-gray-800 mb-4">パスワード再設定</h2>

        <p class="text-sm text-gray-600 text-center mb-4">
            登録メールアドレスを入力してください。<br>
            パスワード再設定用リンクをお送りします。
        </p>

        <!-- セッションメッセージ -->
        <x-auth-session-status class="mb-4 text-center text-sm text-green-600" :status="session('status')" />

        <!-- エラーメッセージ -->
        <x-input-error :messages="$errors->get('email')" class="mb-4 text-center text-sm text-red-600" />

        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div>
                <label for="email" class="block font-medium text-sm text-gray-700">
                    メールアドレス
                </label>
                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    class="block mt-1 w-full border-gray-300 rounded-md
                           focus:border-blue-300 focus:ring focus:ring-blue-200
                           focus:ring-opacity-50"
                    required
                />
            </div>

            <div class="mt-6 flex justify-center">
                <button
                    type="submit"
                    class="px-10 py-2 bg-[#86D4FE] text-white font-bold rounded-full shadow-md hover:bg-[#69C2FD] transition duration-300"
                >
                    送信する
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
