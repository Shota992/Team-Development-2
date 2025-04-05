@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto mt-10 bg-[#F7F8FA] p-8 rounded shadow">
    <h2 class="text-xl font-bold mb-6">従業員登録</h2>

    <form action="{{ route('employee.store') }}" method="POST">
        @csrf

        {{-- 氏名 --}}
        <div class="flex items-center mb-4 border border-[#939393] rounded h-14">
            <label class="w-1/4 bg-[#D9D9D9] p-2 text-right">氏名</label>
            <input type="text" name="name" class="w-3/4 p-3 border-none focus:outline-none" required>
        </div>

        {{-- 性別と生年月日 --}}
        <div class="flex items-center mb-4">
            <div class="flex items-center border border-[#939393] rounded h-14 w-1/2 mr-2">
                <label class="w-1/2 bg-[#D9D9D9] p-2 text-right">性別</label>
                <select name="gender" class="w-1/2 p-3 border-none focus:outline-none">
                    <option value="1">男</option>
                    <option value="2">女</option>
                    <option value="3">その他</option>
                </select>
            </div>
            <div class="flex items-center border border-[#939393] rounded h-14 w-1/2 ml-2">
                <label class="w-1/2 bg-[#D9D9D9] p-2 text-right">生年月日</label>
                <input type="date" name="birthday" class="w-1/2 p-3 border-none focus:outline-none">
            </div>
        </div>

        {{-- 役職 --}}
        <div class="flex items-center mb-4 border border-[#939393] rounded h-14">
            <label class="w-1/4 bg-[#D9D9D9] p-2 text-right">役職</label>
            <select name="position_id" class="w-3/4 p-3 border-none focus:outline-none">
                @foreach ($positions as $position)
                    <option value="{{ $position->id }}">{{ $position->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- メールアドレス --}}
        <div class="flex flex-col mb-4">
            <div class="flex items-center border border-[#939393] rounded h-14">
                <label class="w-1/4 bg-[#D9D9D9] p-2 text-right">メールアドレス</label>
                <input type="email" name="email" class="w-3/4 p-3 border-none focus:outline-none" required>
            </div>
            @error('email')
                <p class="text-red-600 text-sm mt-1 ml-2">{{ $message }}</p>
            @enderror
        </div>

        {{-- 部署 --}}
        <div class="flex items-center mb-4 border border-[#939393] rounded h-14">
            <label class="w-1/4 bg-[#D9D9D9] p-2 text-right">部署</label>
            <select name="department_id" class="w-3/4 p-3 border-none focus:outline-none">
                @foreach ($departments as $department)
                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- 会社（表示のみ） --}}
        <div class="flex items-center mb-4 border border-[#939393] rounded h-14 bg-gray-100">
            <label class="w-1/4 bg-[#D9D9D9] p-2 text-right">会社</label>
            <div class="w-3/4 p-3">{{ Auth::user()->office->name ?? '会社名未設定' }}</div>
        </div>

        {{-- 管理者権限（ツールチップ付き） --}}
        <div class="flex items-center mb-6 border border-[#939393] rounded h-14">
            <label class="w-1/4 bg-[#D9D9D9] p-2 text-right flex items-center justify-end relative group">
                管理者権限
                <div class="ml-1 relative group">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-500 cursor-pointer" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-12.75a.75.75 0 00-1.5 0v1a.75.75 0 001.5 0v-1zM9 8.75a.75.75 0 011.5 0v5.5a.75.75 0 01-1.5 0v-5.5z" clip-rule="evenodd" />
                    </svg>
                    <div class="absolute z-30 left-1/2 transform -translate-x-1/2 mt-2 w-72 p-2 text-sm text-white bg-gray-700 rounded shadow-lg hidden group-hover:block">
                        管理者権限とは、このアプリにログインしてアンケートの配信・分析を行うための権限です。回答のみでよい場合は「しない」で構いません。
                    </div>
                </div>
            </label>
            <div class="w-3/4 p-3 flex gap-8">
                <label><input type="radio" name="administrator" value="0" checked> しない</label>
                <label><input type="radio" name="administrator" value="1"> する</label>
            </div>
        </div>

        {{-- 登録ボタン --}}
        <div class="flex justify-center mt-6">
            <button type="submit" class="bg-[#86D4FE] text-black border border-[#0077B7] px-10 py-2 rounded shadow">
                登録
            </button>
        </div>

        {{-- キャンセルボタン --}}
        <div class="flex justify-center mt-3">
            <a href="{{ route('setting.employee-list') }}" class="bg-[#D9D9D9] px-10 py-2 rounded shadow">
                キャンセル
            </a>
        </div>
    </form>
</div>
@endsection
