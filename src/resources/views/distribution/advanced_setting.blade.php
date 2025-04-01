@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-8 shadow-md">
    <h2 class="text-2xl font-bold mb-10">⚙️ アンケート詳細設定</h2>

    <form action="{{ route('survey.save-settings') }}" method="POST">
        @csrf

        {{-- ✅ 配信日時を設定 --}}
        <div class="mb-10">
            <div class="flex flex-col md:flex-row md:items-center md:space-x-8 mb-4">
                <h3 class="text-xl font-semibold whitespace-nowrap mb-2 md:mb-0">📅 配信日時を設定</h3>

                <div class="flex flex-col space-y-2">
                    <label class="flex items-center space-x-2">
                        <input type="radio" name="send_type" value="now" checked class="form-radio text-blue-500">
                        <span>すぐに配信する</span>
                    </label>
                    <label class="flex items-center space-x-2">
                        <input type="radio" name="send_type" value="schedule" class="form-radio text-blue-500">
                        <span>予約配信する</span>
                    </label>
                </div>
            </div>

            {{-- 日付・時間入力 --}}
            <div class="flex flex-wrap items-center space-x-6 mt-4">
                <label class="text-gray-700">配信日：</label>
                <input type="date" name="scheduled_date" class="border rounded px-3 py-2 w-44 cursor-pointer" disabled>
                <label class="text-gray-700">配信時間：</label>
                <input type="time" name="scheduled_time" class="border rounded px-3 py-2 w-32 cursor-pointer" disabled>
            </div>
        </div>

        {{-- ✅ 提出期限 --}}
        <div class="mb-10">
            <div class="flex flex-col md:flex-row md:items-center md:space-x-8 mb-4">
                <h3 class="text-xl font-semibold whitespace-nowrap mb-2 md:mb-0">📤 提出期限</h3>

                <div class="flex flex-col space-y-2">
                    <label class="flex items-center space-x-2">
                        <input type="radio" name="deadline_type" value="none" checked class="form-radio text-blue-500">
                        <span>設定しない</span>
                    </label>
                    <label class="flex items-center space-x-2">
                        <input type="radio" name="deadline_type" value="set" class="form-radio text-blue-500">
                        <span>設定する</span>
                    </label>
                </div>
            </div>

            <div class="flex flex-wrap items-center space-x-6 mt-4">
                <label class="text-gray-700">期限日：</label>
                <input type="date" name="deadline_date" class="border rounded px-3 py-2 w-44 cursor-pointer" disabled>
                <label class="text-gray-700">期限時間：</label>
                <input type="time" name="deadline_time" class="border rounded px-3 py-2 w-32 cursor-pointer" disabled>
            </div>
        </div>

        {{-- ✅ 匿名設定 --}}
        <div class="mb-10">
            <div class="flex flex-col md:flex-row md:items-center md:space-x-8 mb-4">
                <h3 class="text-xl font-semibold whitespace-nowrap mb-2 md:mb-0">🛡️ 匿名設定</h3>

                <div class="flex flex-col space-y-2">
                    <label class="flex items-center space-x-2">
                        <input type="radio" name="is_anonymous" value="1" checked class="form-radio text-blue-500">
                        <span>匿名で回答させる</span>
                    </label>
                    <label class="flex items-center space-x-2">
                        <input type="radio" name="is_anonymous" value="0" class="form-radio text-blue-500">
                        <span>名前を記入させる</span>
                    </label>
                </div>
            </div>
        </div>

        {{-- ✅ ボタンエリア --}}
        <div class="mt-10 text-center space-y-4">
            <button type="submit"
                class="w-60 py-3 bg-[#86D4FE] text-white font-bold rounded-full shadow-lg hover:bg-[#69C2FD] transition duration-300">
                配信内容確認へ
            </button>

            <div>
                <a href="{{ route('survey.group-selection') }}"
                class="inline-block w-60 py-3 bg-gray-300 text-gray-800 font-bold rounded-full shadow hover:bg-gray-400 transition duration-300 text-center">
                    戻る
                </a>
            </div>
        </div>
    </form>
</div>

<script>
    // ラジオボタンによる表示切り替え（配信タイミング）
    document.querySelectorAll('input[name="send_type"]').forEach(radio => {
        radio.addEventListener('change', () => {
            const isScheduled = document.querySelector('input[name="send_type"]:checked').value === 'schedule';
            document.querySelector('input[name="scheduled_date"]').disabled = !isScheduled;
            document.querySelector('input[name="scheduled_time"]').disabled = !isScheduled;
        });
    });

    // ラジオボタンによる表示切り替え（提出期限）
    document.querySelectorAll('input[name="deadline_type"]').forEach(radio => {
        radio.addEventListener('change', () => {
            const isSet = document.querySelector('input[name="deadline_type"]:checked').value === 'set';
            document.querySelector('input[name="deadline_date"]').disabled = !isSet;
            document.querySelector('input[name="deadline_time"]').disabled = !isSet;
        });
    });

    // 初期無効化（読み込み時対応）
    window.addEventListener('DOMContentLoaded', () => {
        document.querySelector('input[name="scheduled_date"]').disabled = true;
        document.querySelector('input[name="scheduled_time"]').disabled = true;
        document.querySelector('input[name="deadline_date"]').disabled = true;
        document.querySelector('input[name="deadline_time"]').disabled = true;
    });
</script>
@endsection
