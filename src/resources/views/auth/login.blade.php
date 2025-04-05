{{-- resources/views/auth/login.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-blue-50">
    <div class="w-full max-w-md bg-white p-8 rounded-md shadow">
        <h1 class="text-center text-2xl font-bold mb-6">get mild</h1>

        <!-- エラーメッセージ表示 -->
        @if ($errors->any())
            <div class="mb-4 text-red-600">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            {{-- メールアドレス --}}
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

            {{-- パスワード --}}
            <div class="mt-4">
                <label for="password" class="block font-medium text-sm text-gray-700">
                    パスワード
                </label>
                <input
                    id="password"
                    type="password"
                    name="password"
                    class="block mt-1 w-full border-gray-300 rounded-md
                           focus:border-blue-300 focus:ring focus:ring-blue-200
                           focus:ring-opacity-50"
                    required
                />
            </div>

            <div class="block mt-4">
                <label for="remember_me" class="inline-flex items-center">
                    <input
                        id="remember_me"
                        type="checkbox"
                        class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500"
                        name="remember"
                        {{ old('remember') ? 'checked' : '' }}
                    >
                    <span class="ml-2 text-sm text-gray-600">
                        パスワードを記憶する
                    </span>
                </label>
            </div>
            

            <div class="flex items-center justify-between mt-6">
                @if (Route::has('password.request'))
                    <a
                        class="underline text-sm text-gray-600 hover:text-gray-900"
                        href="{{ route('password.request') }}"
                    >
                        パスワードを忘れた場合はこちら
                    </a>
                @endif

                <button
                    type="submit"
                    class="ml-4 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700
                           focus:outline-none focus:ring-2 focus:ring-offset-2
                           focus:ring-blue-500"
                >
                    ログイン
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
