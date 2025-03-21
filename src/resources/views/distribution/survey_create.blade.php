@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto bg-light-gray shadow-lg p-6">
    <h2 class="text-xl font-bold mb-4 border-b pb-2">📋 配信設定 ー アンケート作成 ー</h2>

    @if (session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-md">
            {{ session('success') }}
        </div>
    @endif

    <!-- ✅ フォーム開始 -->
    <form action="{{ route('survey.store') }}" method="POST">
        @csrf

        <!-- ✅ アンケート情報入力 -->
        <div class="bg-white p-4 mb-4 border">
            <label class="block text-gray-700 font-semibold mb-1 mt-1">アンケートタイトル：</label>
            <input type="text" name="name" required placeholder="アンケートタイトルを入力してください"
                class="border border-custom-gray px-4 py-2 w-full focus:ring focus:ring-blue-200">

            <label class="block text-gray-700 font-semibold mb-1 mt-1">詳細説明：</label>
            <textarea name="description" placeholder="詳細説明を入力してください"
                class="border px-4 py-2 w-full h-24 focus:ring focus:ring-blue-200"></textarea>
        </div>

        <!-- ✅ フォーム内：送信ボタンのみ -->
        <div class="flex justify-center mt-4">
            <a href="#" class="text-blue-500 hover:underline">アンケート配信設定へ</a>
        </div>
    </form>

    <!-- ✅ フォームの外に表示・非表示ボタン付き設問リスト -->
    <div class="bg-white p-4 mt-6 border">
        <h3 class="text-lg font-semibold border-b pb-2">設問：</h3>

        @php $questionNumber = 1; @endphp

        @foreach($questions as $question)
            @if(is_object($question))
                <div class="my-4 border-b question-block transition-opacity duration-300 {{ !$question->display_status ? 'opacity-50' : '' }}" id="question-block-{{ $question->id }}">
                    <div class="flex items-center justify-between">
                        <p class="text-lg">
                            {{ $questionNumber }}. {{ $question->title }}
                        </p>

                        @if(!$question->common_status)
                            <div class="flex items-center gap-2 z-10 relative">
                                <span class="text-sm text-gray-700">非表示にする：</span>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input
                                        type="checkbox"
                                        class="sr-only peer toggle-switch"
                                        data-id="{{ $question->id }}"
                                        data-status="{{ $question->display_status ? '1' : '0' }}"
                                        {{ !$question->display_status ? 'checked' : '' }}
                                    >
                                    <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-blue-500 rounded-full peer peer-checked:bg-blue-600 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-full"></div>
                                </label>
                            </div>
                        @endif
                    </div>
                    <p class="font-semibold pl-6 my-4 text-lg">質問文：{{ $question->text }}</p>
                </div>
                @php $questionNumber++; @endphp
            @endif
        @endforeach
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggles = document.querySelectorAll('.toggle-switch');

        toggles.forEach(toggle => {
            toggle.addEventListener('change', function () {
                const questionId = this.dataset.id;
                const newStatus = this.checked; // チェックされてる → 非表示

                fetch(`/survey-question/toggle-display/${questionId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        display_status: !newStatus // ONなら false（非表示）、OFFなら true（表示）
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const block = document.getElementById(`question-block-${questionId}`);
                        if (data.display_status) {
                            block.classList.remove('opacity-50');
                        } else {
                            block.classList.add('opacity-50');
                        }
                    } else {
                        alert(data.message || '更新に失敗しました');
                    }
                });
            });
        });
    });
</script>
@endsection
