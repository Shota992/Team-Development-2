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
                    <p class="ml-2 text-2xl font-bold">アンケート設定 ー配信設定ー</p>
                </div>
            </div>
        </div>

        <div class="mx-auto bg-white p-8 shadow-md">
            <form id="detail-settings-form" method="POST" action="{{ route('survey.save-settings') }}">
                @csrf

                {{-- ✅ 配信日時 --}}
                <div class="mb-10 grid grid-cols-12 gap-4 items-start">
                    {{-- 左カラム：タイトル --}}
                    <h3 class="col-span-2 text-xl font-semibold">配信日時</h3>

                    {{-- 右カラム：入力エリア --}}
                    <div class="col-span-10 space-y-4">
                        {{-- ラジオボタン --}}
                        <div class="flex flex-col md:flex-row md:items-center gap-4">
                            <label class="flex items-center space-x-2">
                                <input type="radio" name="send_type" value="now" checked class="form-radio text-blue-500">
                                <span>すぐに配信する</span>
                            </label>
                            <label class="flex items-center space-x-2">
                                <input type="radio" name="send_type" value="schedule" class="form-radio text-blue-500">
                                <span>予約配信する</span>
                            </label>
                        </div>

                        {{-- 日時入力 --}}
                        <div class="flex flex-wrap items-center gap-4">
                            <label class="text-gray-700">配信日：</label>
                            <input type="date" name="scheduled_date" class="border rounded px-3 py-2 w-44 cursor-pointer" disabled>
                            <label class="text-gray-700">配信時間：</label>
                            <input type="time" name="scheduled_time" class="border rounded px-3 py-2 w-32 cursor-pointer" disabled>
                        </div>
                    </div>
                </div>

                {{-- ✅ 提出期限 --}}
                <div class="mb-10 grid grid-cols-12 gap-4 items-start">
                    {{-- 左カラム：タイトル --}}
                    <h3 class="col-span-2 text-xl font-semibold">提出期限</h3>

                    {{-- 右カラム：入力エリア --}}
                    <div class="col-span-10 space-y-4">
                        {{-- ラジオボタン --}}
                        <div class="flex flex-col md:flex-row md:items-center gap-4">
                            <label class="flex items-center space-x-2">
                                <input type="radio" name="deadline_type" value="none" checked class="form-radio text-blue-500">
                                <span>設定しない</span>
                            </label>
                            <label class="flex items-center space-x-2">
                                <input type="radio" name="deadline_type" value="set" class="form-radio text-blue-500">
                                <span>設定する</span>
                            </label>
                        </div>

                        {{-- 日時入力 --}}
                        <div class="flex flex-wrap items-center gap-4">
                            <label class="text-gray-700">期限日：</label>
                            <input type="date" name="deadline_date" class="border rounded px-3 py-2 w-44 cursor-pointer" disabled>
                            <label class="text-gray-700">期限時間：</label>
                            <input type="time" name="deadline_time" class="border rounded px-3 py-2 w-32 cursor-pointer" disabled>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <!-- ボタン -->
        <div class="flex justify-center mt-8">
            {{-- 配信内容確認へ（aタグ + JSで submit） --}}
            <a href="#" id="confirm-link"
                class="w-64 text-center px-14 py-3 bg-[#86D4FE] text-white font-bold rounded-full shadow-lg hover:bg-[#69C2FD] transition duration-300">
                配信内容確認へ
            </a>
        </div>
        <div class="flex justify-center mt-4">
            <a href="{{ route('survey.group-selection') }}"
                class="w-64 text-center px-14 py-3 bg-[#C4C4C4] text-white font-bold rounded-full shadow-lg hover:bg-[#B8B8B8] transition duration-300">
                戻る
            </a>
        </div>
    </div>
</div>


<script>
    // ✅ ラジオ切替でフォーム活性/非活性
    document.querySelectorAll('input[name="send_type"]').forEach(radio => {
        radio.addEventListener('change', () => {
            const isScheduled = document.querySelector('input[name="send_type"]:checked').value === 'schedule';
            document.querySelector('input[name="scheduled_date"]').disabled = !isScheduled;
            document.querySelector('input[name="scheduled_time"]').disabled = !isScheduled;
        });
    });

    document.querySelectorAll('input[name="deadline_type"]').forEach(radio => {
        radio.addEventListener('change', () => {
            const isSet = document.querySelector('input[name="deadline_type"]:checked').value === 'set';
            document.querySelector('input[name="deadline_date"]').disabled = !isSet;
            document.querySelector('input[name="deadline_time"]').disabled = !isSet;
        });
    });

    // ✅ ページ読み込み時 初期非活性
    window.addEventListener('DOMContentLoaded', () => {
        document.querySelector('input[name="scheduled_date"]').disabled = true;
        document.querySelector('input[name="scheduled_time"]').disabled = true;
        document.querySelector('input[name="deadline_date"]').disabled = true;
        document.querySelector('input[name="deadline_time"]').disabled = true;
    });

    // ✅ 「配信内容確認へ」ボタンが押されたら form を submit
    document.getElementById('confirm-link')?.addEventListener('click', function (e) {
        e.preventDefault();
        const link = this;
        link.classList.add('pointer-events-none', 'opacity-70');
        link.textContent = '保存中...';
        document.getElementById('detail-settings-form').submit();
    });
</script>
@endsection
