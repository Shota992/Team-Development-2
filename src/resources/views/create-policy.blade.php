@extends('layouts.app')

@section('title', '施策作成画面 - Kompass')
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
                    <p class="ml-2 text-2xl font-bold">施策立案 ー施策作成ー</p>
                </div>
            </div>
        </div>

        {{-- ▼ 施策情報カード --}}
        <div class="bg-white p-8 m-4 border shadow-lg mx-8">
            <form action="{{ route('measures.store') }}" method="POST">
                @csrf
                @if($errors->any())
                <div class="mb-6 p-4 bg-red-100 text-red-700 rounded">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <!-- 施策名 -->
                <div class="mb-4">
                    <label class="block text-lg font-semibold text-gray-800 mb-2">施策名：</label>
                    <input type="text" name="title" value="{{ old('title') }}" required class="mt-1 w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="施策名を入力してください">
                </div>

                <div class="mb-4">
                    <label class="block text-lg font-semibold text-gray-800 mb-2">評価頻度：</label>
                    <select id="evaluation_frequency" name="evaluation_frequency" class="mt-1 w-full px-3 py-2 border rounded" onchange="toggleCustomFrequency()">
                        <option value="1">1ヶ月</option>
                        <option value="3">3ヶ月</option>
                        <option value="6">6ヶ月</option>
                        <option value="12">12ヶ月</option>
                        <option value="custom" {{ old('evaluation_frequency') == 'custom' ? 'selected' : '' }}>カスタム</option>
                    </select>
                </div>
                
                <!-- カスタム頻度 -->
                <div id="custom-frequency-field" class="mb-4" style="display: none;">
                    <label class="block text-lg font-semibold text-gray-800 mb-2">カスタム頻度の値：</label>
                    <input type="number" name="custom_frequency_value" value="{{ old('custom_frequency_value') }}" class="mt-1 w-full px-3 py-2 border rounded" placeholder="例:2">
                
                    <label class="block text-lg font-semibold text-gray-800 mb-2 mt-2">単位：</label>
                    <select name="custom_frequency_unit" class="mt-1 w-full px-3 py-2 border rounded">
                        <option value="weeks" {{ old('custom_frequency_unit') == 'weeks' ? 'selected' : '' }}>週間</option>
                        <option value="months" {{ old('custom_frequency_unit') == 'months' ? 'selected' : '' }}>月間</option>
                    </select>
                </div>
                
                <script>
                    function toggleCustomFrequency() {
                        const evaluationFrequency = document.getElementById('evaluation_frequency').value;
                        const customField = document.getElementById('custom-frequency-field');
                        
                        // カスタム選択時にフィールドを表示、それ以外は非表示
                        if (evaluationFrequency === 'custom') {
                            customField.style.display = 'block';
                        } else {
                            customField.style.display = 'none';
                        }
                    }
                
                    // 初期表示のためにページロード時に評価頻度をチェック
                    window.addEventListener('DOMContentLoaded', toggleCustomFrequency);
                </script>            </form>
        </div>

            <!-- タスク作成カード -->
            <div id="tasks-container" class="space-y-4">
                <!-- 初期タスクカードが1つだけ表示される -->
                <div class="bg-white p-6 mb-4 border shadow-lg m-8">
                    <button type="button"
                        class="remove-task-btn absolute top-2 right-2 text-gray-500 hover:text-gray-700 text-lg hidden"
                        title="削除">×</button>

                    <label class="block text-lg font-semibold text-gray-800 mb-2">タスク：</label>
                    <input type="text" name="task_name[]" required class="mt-1 w-full border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400">

                    <label class="block text-lg font-semibold text-gray-800 mb-2 mt-4">部署：</label>
                    <select name="task_department_id[]" required class="mt-1 w-full border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400">
                        <option value="">選択してください</option>
                        @foreach($departments as $d)
                            <option value="{{ $d->id }}">{{ $d->name }}</option>
                        @endforeach
                    </select>

                    <label class="block text-lg font-semibold text-gray-800 mb-2 mt-4">担当者：</label>
                    <select name="assignee[]" required class="mt-1 w-full border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400"></select>

                    <label class="block text-lg font-semibold text-gray-800 mb-2 mt-4">開始日：</label>
                    <input type="date" name="start_date_task[]" required class="mt-1 w-full border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400" onfocus="this.showPicker()">

                    <label class="block text-lg font-semibold text-gray-800 mb-2 mt-4">終了日：</label>
                    <input type="date" name="end_date_task[]" required class="mt-1 w-full border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400" onfocus="this.showPicker()">
                </div>
                <!-- タスク追加ボタン -->
                <button type="button" id="add-task-btn"
                class="flex items-center justify-center m-8 px-6 py-3 text-sm font-medium text-gray-800 bg-white hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-300 rounded-full shadow-lg transform transition-all duration-200">
                    <span class="text-xl mr-2">＋</span> タスク追加
                </button>
            </div>
            <!-- ✅ 登録ボタン -->
            <div class="flex justify-center mt-8">
                <button type="submit"
                    class="w-80 text-center px-14 py-3 bg-[#86D4FE] text-white font-bold rounded-full shadow-lg hover:bg-[#69C2FD] transition duration-300">
                    登録
                </button>
            </div>

            <!-- ✅ キャンセルボタン -->
            <div class="flex justify-center mt-4">
                <a href="{{ route('survey.create') }}" 
                class="w-80 text-center px-14 py-3 bg-[#C4C4C4] text-white font-bold rounded-full shadow-lg hover:bg-[#B8B8B8] transition duration-300">
                    キャンセル
                </a>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // タスク追加のロジック
    document.getElementById('add-task-btn').addEventListener('click', function() {
        // タスクのテンプレートを取得
        var taskTemplate = document.querySelector('.task-entry').outerHTML;
        // 新しいタスクを追加
        document.getElementById('tasks-container').insertAdjacentHTML('beforeend', taskTemplate);

        // 新しく追加されたタスクカードの削除ボタンを表示
        var newTaskCard = document.querySelectorAll('.task-entry')[document.querySelectorAll('.task-entry').length - 1];
        newTaskCard.querySelector('.remove-task-btn').classList.remove('hidden');
    });

    // タスク削除のロジック
    document.addEventListener('click', function(e) {
        if (e.target && e.target.classList.contains('remove-task-btn')) {
            var taskEntry = e.target.closest('.task-entry');
            var container = document.getElementById('tasks-container');
            if (taskEntry && container.children.length > 1) {
                taskEntry.remove();
            } else {
                alert('タスクは最低1つ必要です');
            }
        }
    });
</script>
@endsection
