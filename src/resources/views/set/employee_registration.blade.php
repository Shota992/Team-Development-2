@extends('layouts.app')

@section('title', '従業員登録 - Kompass')

@section('content')

@include('components.sidebar')
<div class="bg-[#F7F8FA]">
    <div class="min-h-screen pb-8 ml-64 mr-8">
        <div class="flex justify-between p-5 pt-8">
            <div class="flex">
                <figure>
                    <img src="{{ asset('images/title_logo.png') }}" alt="" />
                </figure>
                <p class="ml-2 text-2xl font-bold">従業員登録</p>
            </div>
        </div>

<!-- カード本体 -->
<div class="bg-white p-8 rounded-lg shadow w-full mx-auto">
    <form action="{{ route('employee.store') }}" method="POST">
        @csrf

        {{-- 氏名 --}}
        <div class="mb-4">
            <label class="block font-semibold mb-1">氏名：</label>
            <input type="text" name="name" class="w-full border rounded px-3 py-2" placeholder="氏名を入力してください" required>
        </div>

        {{-- 性別・生年月日（横並び） --}}
        <div class="flex gap-4 mb-4">
            <div class="w-1/2">
                <label class="block font-semibold mb-1">性別：</label>
                <select name="gender" class="w-full border rounded px-3 py-2">
                    <option value="">選択してください</option>
                    <option value="1">男</option>
                    <option value="2">女</option>
                    <option value="3">その他</option>
                </select>
            </div>
            <div class="w-1/2">
                <label for="birthday" class="block font-semibold mb-1">生年月日：</label>
                <input
                    type="date"
                    name="birthday"
                    id="birthday"
                    class="w-full border border-gray-300 rounded px-3 py-2 appearance-none cursor-pointer focus:ring focus:ring-blue-200"
                    placeholder="YYYY-MM-DD"
                    onfocus="this.showPicker && this.showPicker()"
                />
            </div>
                    </div>

        {{-- 役職 --}}
        <div class="mb-4">
            <label class="block font-semibold mb-1">役職：</label>
            <select name="position_id" class="w-full border rounded px-3 py-2">
                <option value="">選択してください</option>
                @foreach ($positions as $position)
                    <option value="{{ $position->id }}">{{ $position->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- メールアドレス --}}
        <div class="mb-4">
            <label class="block font-semibold mb-1">メールアドレス：</label>
            <input type="email" name="email" class="w-full border rounded px-3 py-2" placeholder="example@example.com" required>
            @error('email')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- 部署 --}}
        <div class="mb-4">
            <label class="block font-semibold mb-1">部署：</label>
            <select name="department_id" class="w-full border rounded px-3 py-2">
                <option value="">選択してください</option>
                @foreach ($departments as $department)
                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- 会社（表示のみ） --}}
        <div class="mb-4">
            <label class="block font-semibold mb-1">会社：</label>
            <div class="w-full px-3 py-2 bg-gray-100 rounded border">{{ Auth::user()->office->name ?? '会社名未設定' }}</div>
        </div>

        {{-- 管理者権限（ラジオ） --}}
        <div class="mb-4">
            <label class="block font-semibold mb-1">管理者権限：</label>
            <div class="flex gap-6 pl-2">
                <label><input type="radio" name="administrator" value="0" checked> しない</label>
                <label><input type="radio" name="administrator" value="1"> する</label>
            </div>
            <p class="text-sm text-gray-500 mt-1">
                ※ 管理者はログインしてアンケートの配信や分析ができます
            </p>
        </div>
    </form>
</div>

<!-- ▼ 登録ボタン（カード外） -->
<div class="flex justify-center mt-8">
    <button form="employee-form" type="submit"
        class="w-64 text-center px-14 py-3 bg-[#86D4FE] text-white font-bold rounded-full shadow-lg hover:bg-[#69C2FD] transition duration-300">
        登録
    </button>
</div>

<!-- ▼ キャンセルボタン（カード外） -->
<div class="flex justify-center mt-4">
    <a href="{{ route('setting.employee-list') }}"
        class="w-64 text-center px-14 py-3 bg-[#C4C4C4] text-white font-bold rounded-full shadow-lg hover:bg-[#B8B8B8] transition duration-300">
        キャンセル
    </a>
</div>
    </div>
</div>
@endsection
