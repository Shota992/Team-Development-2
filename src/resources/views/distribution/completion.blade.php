@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto bg-white p-10 shadow-md text-center space-y-8">
    <h2 class="text-2xl font-bold text-green-600">✅ アンケートの配信が完了しました！</h2>
    <p class="text-gray-700 text-lg">設定された配信情報に従って、アンケートが正常に送信されました。</p>

    {{-- ダッシュボードへボタン --}}
    <a href="{{ route('dashboard') }}"
        class="inline-block px-6 py-3 bg-blue-500 text-white font-bold rounded-full shadow hover:bg-blue-600 transition">
        ダッシュボードへ
    </a>
</div>
@endsection
