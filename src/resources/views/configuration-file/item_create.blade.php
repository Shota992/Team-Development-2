@extends('layouts.app')

@section('title', '項目追加 - Kompass')
@section('content')

@include('components.sidebar')

<div class="ml-64 p-10">
    <h2 class="text-2xl font-bold mb-6">項目追加</h2>
    <nav class="text-sm text-gray-500 mb-4" aria-label="パンくずリスト">
        <ol class="list-reset flex items-center space-x-2">
            <li>
                <a href="{{ route('survey_questions.index') }}" class="hover:underline text-gray-500">項目一覧</a>
            </li>
            <li><span>&gt;</span></li>
            <li class="text-gray-500">項目追加</li>
        </ol>
    </nav>

    <!-- 可愛いトースト通知 -->
    <div id="error-toast" class="hidden fixed top-4 right-4 bg-red-500 text-white px-4 py-2 rounded shadow z-50 transition-opacity duration-500">
        <span id="error-toast-message"></span>
    </div>

    <form id="question-form" action="{{ route('survey_questions.store') }}" method="POST">
        @csrf

        {{-- 項目名 --}}
        <div class="mb-6">
            <label for="title" class="block font-semibold mb-1">項目名：</label>
            <input type="text" id="title" name="title" placeholder="例）顧客基盤の安定性" value="{{ old('title') }}" class="w-full border rounded px-3 py-2">
        </div>

        {{-- 詳細説明 --}}
        <div class="mb-6">
            <label for="description" class="block font-semibold mb-1">詳細説明：</label>
            <textarea id="description" name="description" rows="3" placeholder="例）顧客基盤の安定性とは..." class="w-full border rounded px-3 py-2">{{ old('description') }}</textarea>
        </div>

        {{-- 質問内容 --}}
        <div class="mb-6">
            <label for="text" class="block font-semibold mb-1">アンケートにおける質問内容：</label>
            <input type="text" id="text" name="text" placeholder="例）どう思いますか？" value="{{ old('text') }}" class="w-full border rounded px-3 py-2">
        </div>

        {{-- 選択肢入力欄 --}}
        <div class="mb-4">
            <label class="block font-semibold mb-1">
                アンケートにおける回答の理由（最低3つの選択肢が必要です）：
            </label>

            <div id="options-wrapper">
                @php
                    $oldOptions = old('options', [null, null, null]);
                @endphp
                @foreach ($oldOptions as $option)
                    <div class="option-item flex items-center gap-2 mb-2">
                        <span class="handle text-gray-400 cursor-move">≡</span>
                        <input type="text" name="options[]" value="{{ $option }}" placeholder="例）どう思いますか？" class="flex-1 border rounded px-3 py-2">
                        <button type="button" class="remove-option text-gray-500 hover:text-red-500">×</button>
                    </div>
                @endforeach
            </div>

            <button type="button" id="add-option" class="mt-2 px-4 py-2 bg-gray-100 rounded hover:bg-gray-200">
                ＋ 選択肢を追加
            </button>
        </div>

        {{-- ボタン群 --}}
        <div class="flex gap-4 mt-10">
            <button type="submit" class="w-[240px] py-3 text-white bg-blue-400 hover:bg-blue-500 rounded-full">
                項目を追加する
            </button>
            <a href="{{ route('survey_questions.index') }}" class="w-[240px] py-3 text-gray-600 bg-gray-200 hover:bg-gray-300 rounded-full text-center">
                追加せずに項目一覧に戻る
            </a>
        </div>
    </form>
</div>

{{-- 並び替え用 SortableJS CDN --}}
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
    new Sortable(document.getElementById('options-wrapper'), {
        handle: '.handle',
        animation: 150,
    });

    function showErrorToast(message) {
        const toast = document.getElementById('error-toast');
        const msg = document.getElementById('error-toast-message');
        msg.textContent = message;
        toast.classList.remove('hidden');
        toast.classList.add('opacity-100');

        setTimeout(() => {
            toast.classList.add('opacity-0');
            setTimeout(() => {
                toast.classList.add('hidden');
                toast.classList.remove('opacity-0');
            }, 500);
        }, 3000);
    }

    document.getElementById('add-option').addEventListener('click', function () {
        const wrapper = document.getElementById('options-wrapper');
        const option = document.createElement('div');
        option.classList.add('option-item', 'flex', 'items-center', 'gap-2', 'mb-2');
        option.innerHTML = `
            <span class="handle text-gray-400 cursor-move">≡</span>
            <input type="text" name="options[]" placeholder="例）どう思いますか？" class="flex-1 border rounded px-3 py-2">
            <button type="button" class="remove-option text-gray-500 hover:text-red-500">×</button>
        `;
        wrapper.appendChild(option);
    });

    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-option')) {
            const wrapper = document.getElementById('options-wrapper');
            const options = wrapper.querySelectorAll('.option-item');
            if (options.length <= 3) {
                showErrorToast('最低3つの選択肢が必要です');
                return;
            }
            e.target.closest('.option-item').remove();
        }
    });

    document.getElementById('question-form').addEventListener('submit', function (e) {
        const title = document.getElementById('title').value.trim();
        const text = document.getElementById('text').value.trim();
        const options = document.querySelectorAll('input[name="options[]"]');
        const filledOptions = [...options].filter(opt => opt.value.trim() !== '');

        if (!title || !text || filledOptions.length < 3) {
            e.preventDefault();
            let msg = '';
            if (!title || !text) msg += '項目名と質問内容は必須です。';
            if (filledOptions.length < 3) msg += (msg ? ' ' : '') + '選択肢は最低3つ必要です。';
            showErrorToast(msg);
        }
    });
</script>

@endsection
