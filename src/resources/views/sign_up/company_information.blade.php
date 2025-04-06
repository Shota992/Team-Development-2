@extends('layouts.plain')

@section('content')
@php $admin = session('sign_up_admin'); @endphp

<div class="flex items-center justify-center min-h-screen bg-[#E0F4FF]">
    <div class="w-full max-w-xl bg-white p-6 rounded-md shadow mt-8 mb-8">
        <div class="text-center mb-6">
            <!-- 画像サイズ調整 -->
            <img src="{{ asset('images/Kompasslogo.jpeg') }}" alt="sign up" class="mx-auto mb-6 w-30">
        </div>

        <h2 class="text-center text-2xl font-bold mb-6">会社情報入力画面</h2>

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
                        class="inline-block w-64 py-3 bg-[#86D4FE] text-white font-bold rounded-full shadow-lg hover:bg-[#69C2FD] transition duration-300 text-center">
                    新規登録する
                </button>

                <a href="{{ route('sign-up.admin') }}"
                   class="w-64 text-center px-14 py-3 bg-[#C4C4C4] text-white font-bold rounded-full shadow-lg hover:bg-[#B8B8B8] transition duration-300">
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
    deleteBtn.className = 'text-gray-600 text-lg px-2 py-1 rounded-full flex items-center justify-center'; // シンプルなバッテン
    deleteBtn.textContent = '×';
    deleteBtn.onclick = function () {
        removeField(deleteBtn);
    };

    wrapper.appendChild(input);
    wrapper.appendChild(deleteBtn);
    container.appendChild(wrapper);
}

function removeField(button) {
    // 削除するボタンが属するフィールドを削除
    const wrapper = button.closest('div');
    // 初期値が入力されていない場合のみ削除
    if (!wrapper.querySelector('input').value) {
        wrapper.remove();
    }
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
