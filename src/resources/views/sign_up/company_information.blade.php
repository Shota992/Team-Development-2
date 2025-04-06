@extends('layouts.plain')

@section('title', '管理者情報入力画面 - Kompass')
@section('content')
@php $admin = session('sign_up_admin'); @endphp

<div class="flex items-center justify-center min-h-screen bg-[#E0F4FF]">
    <div class="w-full max-w-xl bg-white p-8 rounded-md shadow">
        <h1 class="text-center text-2xl font-bold mb-6">get mild</h1>
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
                    <div class="flex mb-2 space-x-2">
                        <input type="text" name="departments[]" value="{{ $admin['department'] ?? '' }}" class="w-full p-2 border rounded bg-gray-100" readonly>
                    </div>
                </div>

                {{-- ※ Laravel側では配列は必須でなくてもよい --}}
                @error('departments')
                    <p class="text-red-500 text-sm">部署を1つ以上入力してください。</p>
                @enderror

                <button type="button" onclick="addField('departmentFields', 'departments[]')" class="bg-gray-200 text-sm px-4 py-1 rounded">＋部署を追加</button>
            </div>

            {{-- 役職 --}}
            <div class="mb-6">
                <label class="block text-gray-700 font-semibold mb-1">会社の役職 <span class="text-red-500">*</span></label>
                <p class="text-red-500 text-sm mb-1">※ このアプリケーションで使用する役職をすべて入力してください</p>

                <div id="positionFields">
                    <div class="flex mb-2 space-x-2">
                        <input type="text" name="positions[]" value="{{ $admin['position'] ?? '' }}" class="w-full p-2 border rounded bg-gray-100" readonly>
                    </div>
                </div>

                @error('positions')
                    <p class="text-red-500 text-sm">役職を1つ以上入力してください。</p>
                @enderror

                <button type="button" onclick="addField('positionFields', 'positions[]')" class="bg-gray-200 text-sm px-4 py-1 rounded">＋役職を追加</button>
            </div>

            {{-- ボタンエリア --}}
            <div class="mt-10 flex flex-col items-center space-y-4">
                <button type="submit"
                        id="submit-register"
                        class="w-60 py-3 bg-[#4880FF] text-white font-bold rounded-md shadow text-center">
                    新規登録する
                </button>

                <a href="{{ route('sign-up.admin') }}"
                   class="w-60 py-3 bg-[#C4C4C4] text-white font-bold rounded-md shadow text-center">
                    戻る
                </a>
            </div>
        </form>
    </div>
</div>

<script>
function addField(containerId, name) {
    const container = document.getElementById(containerId);
    const wrapper = document.createElement('div');
    wrapper.className = 'flex mb-2 space-x-2';

    const input = document.createElement('input');
    input.type = 'text';
    input.name = name;
    input.required = true;
    input.className = 'w-full p-2 border rounded';

    const deleteBtn = document.createElement('button');
    deleteBtn.type = 'button';
    deleteBtn.className = 'px-4 py-1 text-sm bg-red-400 text-white rounded whitespace-nowrap text-center';
    deleteBtn.textContent = '削除';
    deleteBtn.onclick = function () {
        container.removeChild(wrapper);
    };

    wrapper.appendChild(input);
    wrapper.appendChild(deleteBtn);
    container.appendChild(wrapper);
}

// 保存中表示
document.getElementById('submit-register')?.addEventListener('click', function () {
    const btn = this;
    btn.classList.add('pointer-events-none', 'opacity-70');
    btn.textContent = '保存中...';
});

// ページ戻り時にボタンを元に戻す
window.addEventListener('pageshow', function () {
    const btn = document.getElementById('submit-register');
    if (btn && btn.textContent.includes('保存中')) {
        btn.classList.remove('pointer-events-none', 'opacity-70');
        btn.textContent = '新規登録する';
    }
});
</script>
@endsection
