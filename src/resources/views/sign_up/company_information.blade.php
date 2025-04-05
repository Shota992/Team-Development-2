@extends('layouts.app')

@section('content')
@php
    $admin = session('sign_up_admin');
@endphp

<div class="min-h-screen flex items-center justify-center bg-blue-100">
    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-xl">
        <h1 class="text-3xl text-center font-bold text-blue-400 mb-6">get mild</h1>
        <h2 class="text-lg text-center font-semibold mb-6">会社情報入力画面</h2>

        <form method="POST" action="{{ route('sign-up.register') }}" id="companyForm">
            @csrf

            {{-- 会社名（表示のみ） --}}
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-1">会社名</label>
                <input type="text" value="{{ $admin['company'] ?? '' }}" disabled class="w-full p-2 border rounded bg-gray-100">
            </div>

            {{-- 部署 --}}
            <div class="mb-6">
                <label class="block text-gray-700 font-semibold mb-1">会社の部署 <span class="text-red-500">*</span></label>
                <p class="text-red-500 text-sm mb-1">※ このアプリケーションで使用する部署をすべて入力してください</p>

                <div id="departmentFields">
                    <input type="text" name="departments[]" value="{{ $admin['department'] ?? '' }}" class="w-full p-2 border rounded mb-2" required>
                </div>

                <button type="button" onclick="addField('departmentFields', 'departments[]')" class="bg-gray-200 text-sm px-4 py-1 rounded">＋部署を追加</button>
                @error('departments') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>

            {{-- 役職 --}}
            <div class="mb-6">
                <label class="block text-gray-700 font-semibold mb-1">会社の役職 <span class="text-red-500">*</span></label>
                <p class="text-red-500 text-sm mb-1">※ このアプリケーションで使用する役職をすべて入力してください</p>

                <div id="positionFields">
                    <input type="text" name="positions[]" value="{{ $admin['position'] ?? '' }}" class="w-full p-2 border rounded mb-2" required>
                </div>

                <button type="button" onclick="addField('positionFields', 'positions[]')" class="bg-gray-200 text-sm px-4 py-1 rounded">＋役職を追加</button>
                @error('positions') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
            </div>

            {{-- ボタン --}}
            <div class="flex justify-between">
                <a href="{{ route('sign-up.admin') }}" class="bg-gray-400 text-white px-4 py-2 rounded">戻る</a>
                <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600">新規登録する</button>
            </div>
        </form>
    </div>
</div>

<script>
function addField(containerId, name) {
    const container = document.getElementById(containerId);
    const input = document.createElement('input');
    input.type = 'text';
    input.name = name;
    input.required = true;
    input.className = 'w-full p-2 border rounded mb-2';
    container.appendChild(input);
}
</script>
@endsection
