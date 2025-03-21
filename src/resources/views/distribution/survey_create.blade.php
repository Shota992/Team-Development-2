@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto bg-light-gray shadow-lg p-6">
    <h2 class="text-xl font-bold mb-4 border-b pb-2">📋 配信設定 ー アンケート作成 ー</h2>

    @if (session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-md">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('survey.store') }}" method="POST">
        @csrf

        <!-- ✅ 入力可能なアンケート情報 -->
        <div class="bg-white p-4 mb-4 border">
            <label class="block text-gray-700 font-semibold mb-1 mt-1">アンケートタイトル：</label>
            <input type="text" name="name" required placeholder="アンケートタイトルを入力してください"
                class="border border-custom-gray px-4 py-2 w-full focus:ring focus:ring-blue-200">
            <label class="block text-gray-700 font-semibold mb-1 mt-1">詳細説明：</label>
            <textarea name="description" placeholder="詳細説明を入力してください"
                class="border px-4 py-2 w-full h-24 focus:ring focus:ring-blue-200"></textarea>
        </div>

        <!-- ✅ 設問リスト -->
        <div class="bg-white p-4 mb-4 border">
            <h3 class="text-lg font-semibold border-b pb-2">設問：</h3>

            @php $questionNumber = 1; @endphp

            @foreach($questions as $question)
                @if(is_object($question))
                    <div class="my-4">
                        <p class="text-lg">{{ $questionNumber }}. {{ $question->title }}</p>
                        <p class="font-semibold pl-6 my-4 text-lg">質問文：{{ $question->text }}</p>
                    </div>
                    @php $questionNumber++; @endphp
                @endif
            @endforeach
        </div>


        <div class="flex justify-center mt-4">
            <a href="#" class="text-blue-500 hover:underline">アンケート配信設定へ</a>
        </div>
    </form>
</div>

<script>
    document.getElementById('add-question').addEventListener('click', function () {
        const container = document.getElementById('questions');
        const index = container.children.length;
        const newQuestion = document.createElement('div');
        newQuestion.classList.add('question-item', 'bg-gray-50', 'p-3', 'rounded-lg', 'shadow', 'mb-2');
        newQuestion.innerHTML = `
            <input type="text" name="questions[${index}][title]" placeholder="項目名"
                class="border px-4 py-2 w-full rounded-lg focus:ring focus:ring-blue-200 mb-2">
            <input type="text" name="questions[${index}][text]" placeholder="質問文"
                class="border px-4 py-2 w-full rounded-lg focus:ring focus:ring-blue-200 mb-2">
            <input type="text" name="questions[${index}][description]" placeholder="説明文"
                class="border px-4 py-2 w-full rounded-lg focus:ring focus:ring-blue-200 mb-2">
            <select name="questions[${index}][common_status]"
                class="border px-4 py-2 w-full rounded-lg focus:ring focus:ring-blue-200">
                <option value="1">有効</option>
                <option value="0">無効</option>
            </select>
        `;
        container.appendChild(newQuestion);
    });
</script>
@endsection
