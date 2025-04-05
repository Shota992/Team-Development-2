@extends('layouts.app')

@section('content')
<div class="max-w-6xl ml-64 bg-white p-6">
    <h2 class="text-2xl font-bold">📄 配信情報の確認</h2>

    {{-- ✅ アンケート基本情報 --}}
    <div>
        <h3 class="text-lg font-semibold border-b pb-2 mb-2 mt-4">アンケート基本情報</h3>
        <p><strong>タイトル：</strong>{{ session('survey_input.name') }}</p>
        <p><strong>説明：</strong>{{ session('survey_input.description') }}</p>
    </div>

    {{-- ✅ 配信設定 --}}
    <div>
        <h3 class="text-lg font-semibold border-b pb-2 mb-2 mt-4">配信日時・期限</h3>
        <p><strong>配信日時：</strong>
            {{ session('survey_input.start_date') ? \Carbon\Carbon::parse(session('survey_input.start_date'))->format('Y年m月d日 H:i') : '未設定' }}
        </p>
        <p><strong>提出期限：</strong>
            {{ session('survey_input.end_date') ? \Carbon\Carbon::parse(session('survey_input.end_date'))->format('Y年m月d日 H:i') : '未設定' }}
        </p>
    </div>

    {{-- ✅ ボタンエリア --}}
    <div class="flex flex-col items-center space-y-4">

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
