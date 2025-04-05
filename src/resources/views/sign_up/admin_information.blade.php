@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="w-full max-w-2xl p-8 bg-white rounded shadow-lg">
        <h2 class="text-2xl font-semibold text-gray-800 mb-6">管理者情報の登録</h2>

        <form method="POST" action="{{ route('sign-up.admin.store') }}">
            @csrf

            {{-- 氏名 --}}
            <div class="mb-4">
                <label class="block text-gray-700">氏名</label>
                <input type="text" name="name" value="{{ old('name') }}" required class="w-full p-2 border rounded">
                @error('name') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>

            {{-- 性別 --}}
            <div class="mb-4">
                <label class="block text-gray-700">性別</label>
                <select name="gender" required class="w-full p-2 border rounded">
                    <option value="">選択してください</option>
                    <option value="1" {{ old('gender') == 1 ? 'selected' : '' }}>男性</option>
                    <option value="2" {{ old('gender') == 2 ? 'selected' : '' }}>女性</option>
                    <option value="3" {{ old('gender') == 3 ? 'selected' : '' }}>その他</option>
                </select>
                @error('gender') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>

            {{-- 生年月日 --}}
            <div class="mb-4">
                <label class="block text-gray-700">生年月日</label>
                <input type="date" name="birthday" value="{{ old('birthday') }}" required class="w-full p-2 border rounded">
                @error('birthday') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>

            {{-- メール --}}
            <div class="mb-4">
                <label class="block text-gray-700">メールアドレス</label>
                <input type="email" name="email" value="{{ old('email') }}" required class="w-full p-2 border rounded">
                @error('email') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>

            {{-- パスワード --}}
            <div class="mb-4">
                <label class="block text-gray-700">パスワード</label>
                <input type="password" name="password" required class="w-full p-2 border rounded">
                <p class="text-sm text-gray-500 mt-1">（注）半角英数字記号8文字以上16文字以内（英数字混在）で入力してください。空白は使用できません。</p>
                @error('password') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>

            {{-- パスワード確認 --}}
            <div class="mb-4">
                <label class="block text-gray-700">パスワード再入力</label>
                <input type="password" name="password_confirmation" required class="w-full p-2 border rounded">
                @error('password_confirmation') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>

            {{-- 会社名 --}}
            <div class="mb-4">
                <label class="block text-gray-700">会社名</label>
                <input type="text" name="company" value="{{ old('company') }}" required class="w-full p-2 border rounded">
                @error('company') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>

            {{-- 部署名 --}}
            <div class="mb-4">
                <label class="block text-gray-700">部署名</label>
                <input type="text" name="department" value="{{ old('department') }}" required class="w-full p-2 border rounded">
                @error('department') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>

            {{-- 役職名 --}}
            <div class="mb-6">
                <label class="block text-gray-700">役職名</label>
                <input type="text" name="position" value="{{ old('position') }}" required class="w-full p-2 border rounded">
                @error('position') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>

            {{-- ボタン --}}
            <div class="text-right">
                <button type="submit" class="px-6 py-2 bg-red-500 text-white font-semibold rounded hover:bg-red-600">
                    次へ
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
