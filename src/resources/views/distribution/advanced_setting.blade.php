@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-6 shadow-md">
    <h2 class="text-2xl font-bold mb-6">⚙️ アンケート詳細設定</h2>

    <form action="{{ route('survey.save-settings') }}" method="POST">
        @csrf

        {{-- ✅ 配信日時を設定 --}}
        <div class="mb-8">
            <h3 class="text-xl font-semibold mb-4">📅 配信日時を設定</h3>

            {{-- 配信タイミング --}}
            <div class="mb-4 space-y-2">
                <label class="block font-medium text-gray-700">配信タイミング:</label>
                <div class="flex items-center space-x-4">
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

            {{-- 予約配信の日時選択 --}}
            <div id="schedule-options" class="mt-4 hidden space-y-4">
                <div>
                    <label class="block text-gray-700 mb-1">配信日：</label>
                    <input type="date" name="scheduled_date" class="border px-4 py-2 w-full">
                </div>
                <div>
                    <label class="block text-gray-700 mb-1">配信時間：</label>
                    <input type="time" name="scheduled_time" class="border px-4 py-2 w-full">
                </div>
            </div>

            {{-- 提出期限 --}}
            <div class="mt-6 space-y-4">
                <label class="block font-medium text-gray-700">提出期限：</label>
                <div>
                    <label class="block text-gray-700 mb-1">期限日：</label>
                    <input type="date" name="deadline_date" class="border px-4 py-2 w-full">
                </div>
                <div>
                    <label class="block text-gray-700 mb-1">期限時間：</label>
                    <input type="time" name="deadline_time" class="border px-4 py-2 w-full">
                </div>
            </div>
        </div>

        {{-- ✅ アンケート設定 --}}
        <div class="mb-8">
            <h3 class="text-xl font-semibold mb-4">🛡️ アンケート設定</h3>

            <div class="flex items-center space-x-4">
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

        {{-- ✅ 次へボタン --}}
        <div class="flex justify-center mt-8">
            <button type="submit" class="px-14 py-3 bg-[#86D4FE] text-white font-bold rounded-full shadow-lg hover:bg-[#69C2FD] transition duration-300">
                配信内容確認へ
            </button>
        </div>
    </form>
</div>

<script>
    // 配信方法に応じた表示切り替え
    document.querySelectorAll('input[name="send_type"]').forEach(radio => {
        radio.addEventListener('change', function () {
            const scheduleOptions = document.getElementById('schedule-options');
            if (this.value === 'schedule') {
                scheduleOptions.classList.remove('hidden');
            } else {
                scheduleOptions.classList.add('hidden');
            }
        });
    });
</script>
@endsection
