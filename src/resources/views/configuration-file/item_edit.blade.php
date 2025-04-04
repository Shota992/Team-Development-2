@extends('layouts.app')

@section('content')

@include('components.sidebar')

<div class="ml-64 p-10">
    <h2 class="text-2xl font-bold mb-6">項目編集</h2>
    <nav class="text-sm text-gray-500 mb-4" aria-label="パンくずリスト">
        <ol class="list-reset flex items-center space-x-2">
            <li>
                <a href="{{ route('survey_questions.index') }}" class="hover:underline text-gray-500">項目一覧</a>
            </li>
            <li><span>&gt;</span></li>
            <li class="text-gray-500">項目追加</li>
        </ol>
    </nav>

    <form action="{{ route('survey_questions.update', $question->id) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- 項目名 --}}
        <div class="mb-6">
            <label for="title" class="block font-semibold mb-1">項目名：</label>
            <input type="text" id="title" name="title" value="{{ old('title', $question->title) }}" class="w-full border rounded px-3 py-2">
        </div>

        {{-- 詳細説明 --}}
        <div class="mb-6">
            <label for="description" class="block font-semibold mb-1">詳細説明：</label>
            <textarea id="description" name="description" rows="3" class="w-full border rounded px-3 py-2">{{ old('description', $question->description) }}</textarea>
        </div>

        {{-- 質問内容 --}}
        <div class="mb-6">
            <label for="text" class="block font-semibold mb-1">アンケートにおける質問内容：</label>
            <input type="text" id="text" name="text" value="{{ old('text', $question->text) }}" class="w-full border rounded px-3 py-2">
        </div>

        {{-- 回答の理由（選択肢） --}}
        <div class="mb-4">
            <label class="block font-semibold mb-1">
                アンケートにおける回答の理由（最低3つの選択肢が必要です）：
            </label>

            <div id="options-wrapper">
                @foreach ($question->surveyQuestionOptions as $index => $option)
                    <div class="option-item flex items-center gap-2 mb-2">
                        <span class="handle text-gray-400 cursor-move">≡</span>
                        <input type="hidden" name="option_ids[]" value="{{ $option->id }}">
                        <input type="text" name="options[]" value="{{ old('options.' . $index, $option->text) }}" class="flex-1 border rounded px-3 py-2">
                        <button type="button" class="remove-option text-gray-500 hover:text-red-500">×</button>
                    </div>
                @endforeach
            </div>

            <button type="button" id="add-option" class="mt-2 px-4 py-2 bg-gray-100 rounded hover:bg-gray-200">
                ＋ 選択肢を追加
            </button>
        </div>

        {{-- ボタン --}}
        <div class="flex gap-4 mt-10">
            <button type="submit" class="w-[240px] py-3 text-white bg-blue-400 hover:bg-blue-500 rounded-full">
                編集を確定する
            </button>
            <a href="{{ route('survey_questions.index') }}" class="w-[240px] py-3 text-gray-600 bg-gray-200 hover:bg-gray-300 rounded-full text-center">
                編集せずに項目一覧に戻る
            </a>
        </div>
    </form>
</div>

{{-- 並び替えにSortableJSを使用 --}}
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
    // 並び替え対応
    new Sortable(document.getElementById('options-wrapper'), {
        handle: '.handle',
        animation: 150,
    });

    document.getElementById('add-option').addEventListener('click', function () {
        const wrapper = document.getElementById('options-wrapper');
        const option = document.createElement('div');
        option.classList.add('option-item', 'flex', 'items-center', 'gap-2', 'mb-2');
        option.innerHTML = `
            <span class="handle text-gray-400 cursor-move">≡</span>
            <input type="hidden" name="option_ids[]" value="">
            <input type="text" name="options[]" class="flex-1 border rounded px-3 py-2">
            <button type="button" class="remove-option text-gray-500 hover:text-red-500">×</button>
        `;
        wrapper.appendChild(option);
    });

    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-option')) {
            const wrapper = document.getElementById('options-wrapper');
            const options = wrapper.querySelectorAll('.option-item');
            if (options.length <= 3) {
                alert('最低3つの選択肢が必要です');
                return;
            }
            e.target.closest('.option-item').remove();
        }
    });
</script>

@endsection
