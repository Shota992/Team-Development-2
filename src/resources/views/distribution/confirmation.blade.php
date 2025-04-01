@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-6 shadow-md space-y-8">
    <h2 class="text-2xl font-bold">📄 配信情報の確認</h2>

    {{-- ✅ アンケート基本情報 --}}
    <div>
        <h3 class="text-lg font-semibold border-b pb-2 mb-2">アンケート基本情報</h3>
        <p><strong>タイトル：</strong>{{ session('survey_input.name') }}</p>
        <p><strong>説明：</strong>{{ session('survey_input.description') }}</p>
    </div>

    {{-- ✅ 配信対象部署（仮に部署ID表示。後で名前に変更可） --}}
    <div>
        <h3 class="text-lg font-semibold border-b pb-2 mb-2">配信対象部署</h3>
        @if(session('selected_departments'))
            <ul class="list-disc list-inside">
                @foreach(session('selected_departments') as $deptId)
                    <li>部署ID: {{ $deptId }}</li>
                @endforeach
            </ul>
        @else
            <p class="text-gray-500">選択された部署がありません。</p>
        @endif
    </div>

    {{-- ✅ 配信設定 --}}
    <div>
        <h3 class="text-lg font-semibold border-b pb-2 mb-2">配信日時・期限</h3>
        <p><strong>配信日時：</strong>{{ session('survey_input.start_date') }}</p>
        <p><strong>提出期限：</strong>{{ session('survey_input.end_date') ?? '設定なし' }}</p>
    </div>

    {{-- ✅ 匿名設定 --}}
    <div>
        <h3 class="text-lg font-semibold border-b pb-2 mb-2">匿名設定</h3>
        <p>
            <strong>
                {{ session('survey_input.is_anonymous') ? '匿名で回答させる' : '名前を記入させる' }}
            </strong>
        </p>
    </div>

    {{-- ✅ ボタンエリア --}}
    <div class="flex flex-col items-center space-y-4">
        {{-- プレビューボタン（動作未実装） --}}
        <button type="button"
            class="w-60 py-3 bg-gray-300 text-gray-800 font-bold rounded-full shadow hover:bg-gray-400 transition duration-300">
            プレビューする
        </button>

        {{-- 配信ボタン（確認付き） --}}
        <form action="{{ route('survey.send') }}" method="POST" onsubmit="return confirmSend();">
            @csrf
            <button type="submit"
                class="w-60 py-3 bg-[#86D4FE] text-white font-bold rounded-full shadow-lg hover:bg-[#69C2FD] transition duration-300">
                配信する
            </button>
        </form>
    </div>
</div>

<script>
    function confirmSend() {
        return confirm('本当にアンケートを配信しますか？');
    }
</script>
@endsection
