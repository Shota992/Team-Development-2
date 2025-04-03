@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto mt-10 bg-white p-8 rounded shadow">
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
        <div class="flex items-center mb-4 border border-[#939393] rounded h-14">
            <label class="w-1/4 bg-[#D9D9D9] p-2 text-right">メールアドレス</label>
            <input type="email" name="email" class="w-3/4 p-3 border-none focus:outline-none" required>
        </div>

        {{-- 部署 --}}
        <div class="flex items-center mb-6 border border-[#939393] rounded h-14">
            <label class="w-1/4 bg-[#D9D9D9] p-2 text-right">部署</label>
            <select name="department_id" class="w-3/4 p-3 border-none focus:outline-none">
                @foreach ($departments as $department)
                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                @endforeach
            </select>
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
