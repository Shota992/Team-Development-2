@extends('layouts.app')

@section('content')
@include('components.sidebar')

<div class="bg-[#F7F8FA]">
    <div class="min-h-screen pb-8 ml-64 mr-8">
        {{-- ▼ ヘッダー --}}
        <div>
            <div class="flex justify-between p-5 pt-8">
                <div class="flex">
                    <figure>
                        <img src="{{ asset('images/title_logo.png') }}" alt="" />
                    </figure>
                    <p class="ml-2 text-2xl font-bold">アンケート設定 ーアンケート配信確認ー</p>
                </div>
            </div>
        </div>

        <div class="max-w-6xl bg-white p-6">
            {{-- ✅ アンケート基本情報 --}}
            <div class="space-y-4">
                <h3 class="text-lg font-semibold border-b pb-2 mb-2">●アンケート基本情報</h3>
                <p class="my-2"><strong>タイトル：</strong>{{ session('survey_input.name') }}</p>
                <p class="my-2"><strong>説明：</strong>{{ session('survey_input.description') }}</p>
                <p class="my-2"><strong>配信部署：</strong>{{ Auth::user()->department->name ?? '未設定' }}</p>
            </div>

            {{-- ✅ 配信設定 --}}
            <div class="space-y-4">
                <h3 class="text-lg font-semibold border-b pb-2 mb-2 mt-8">●配信日時・期限</h3>
                <p><strong>配信日時：</strong>
                    {{ session('survey_input.start_date') ? \Carbon\Carbon::parse(session('survey_input.start_date'))->format('Y年m月d日 H:i') : '未設定' }}
                </p>
                <p><strong>提出期限：</strong>
                    {{ session('survey_input.end_date') ? \Carbon\Carbon::parse(session('survey_input.end_date'))->format('Y年m月d日 H:i') : '未設定' }}
                </p>
            </div>
        </div>

        {{-- ✅ ボタンエリア --}}
        <div class="flex flex-col items-center mt-8 space-y-4">
            {{-- 配信ボタン（確認付き） --}}
            <form action="{{ route('survey.send') }}" method="POST" onsubmit="return confirmSend();">
                @csrf
                <button type="submit"
                    class="w-60 py-3 bg-[#86D4FE] text-white font-bold rounded-full shadow-lg hover:bg-[#69C2FD] transition duration-300">
                    配信する
                </button>
            </form>

            {{-- 戻るボタン（詳細設定画面へ） --}}
            <a href="{{ route('survey.advanced-setting') }}"
                class="w-60 text-center px-14 py-3 bg-[#C4C4C4] text-white font-bold rounded-full shadow-lg hover:bg-[#B8B8B8] transition duration-300">
                戻る
            </a>
        </div>
    </div>
</div>

<script>
    function confirmSend() {
        return confirm('本当にアンケートを配信しますか？');
    }
</script>
@endsection
