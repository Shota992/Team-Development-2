@extends('layouts.app')

@section('content')
@include('components.sidebar')

<div class="bg-[#F7F8FA]">
    <div class="min-h-screen ml-64 py-20 flex justify-center items-center px-4">
        <div class="bg-white shadow-lg rounded-lg p-10 w-full max-w-2xl text-center">

            {{-- ✅ 成功メッセージ --}}
            <div class="mb-6">
                <div class="flex justify-center mb-4">
                    <div class="text-[#86D4FE]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                </div>
                <h2 class="text-2xl font-bold text-[#86D4FE]">アンケートの配信が完了しました！</h2>
                <p class="text-gray-700 mt-2">設定された配信情報に従って、アンケートが正常に送信されました。</p>
            </div>

            {{-- ✅ ダッシュボードへ --}}
            <a href="{{ route('dashboard') }}"
                class="inline-block px-10 py-3 bg-[#86D4FE] text-white font-bold rounded-full shadow-md hover:bg-[#69C2FD] transition duration-300">
                ダッシュボードへ
            </a>
        </div>
    </div>
</div>
@endsection
