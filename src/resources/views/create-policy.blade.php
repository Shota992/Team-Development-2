@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- 施策カード -->
    <h2 class="text-2xl font-semibold mb-4">施策作成画面</h2>
    <div class="max-w-4xl mx-auto bg-white shadow-md rounded-lg p-6 mb-6">
        <form action="{{ route('store-policy') }}" method="POST">
            @csrf

            <!-- 施策名 -->
            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-gray-700">施策名</label>
                <input type="text" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" id="title" name="title" placeholder="施策名を入力してください" value="{{ old('title') }}" required>
                
                @error('title')
                    <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- 施策内容 -->
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700">施策内容</label>
                <textarea class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" id="description" name="description" rows="4" placeholder="施策内容を入力してください" required>{{ old('description') }}</textarea>
                
                @error('description')
                    <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- 評価改善の頻度 -->
            <div class="mb-4">
                <label for="evaluation_frequency" class="block text-sm font-medium text-gray-700">評価改善の頻度</label>
                <select class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" id="evaluation_frequency" name="evaluation_frequency" required>
                    <option value="1" {{ old('evaluation_frequency') == '1' ? 'selected' : '' }}>毎月1回</option>
                    <option value="3" {{ old('evaluation_frequency') == '3' ? 'selected' : '' }}>3ヶ月に1回</option>
                    <option value="6" {{ old('evaluation_frequency') == '6' ? 'selected' : '' }}>6ヶ月に1回</option>
                    <option value="12" {{ old('evaluation_frequency') == '12' ? 'selected' : '' }}>年1回</option>
                    <option value="custom" {{ old('evaluation_frequency') == 'custom' ? 'selected' : '' }}>カスタム設定</option>
                </select>
                
                @error('evaluation_frequency')
                    <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                @enderror
            </div>

        </form>
    </div>

    <!-- タスクカード（動的追加） -->
    <div class="max-w-4xl mx-auto bg-white shadow-md rounded-lg p-6 mb-6">
        <h3 class="text-xl font-semibold mb-4">タスク作成</h3>
        <form action="{{ route('store-policy') }}" method="POST">
            @csrf
            <div id="tasks-container">
                <!-- 最初の空のタスクフォーム -->
                <div class="task-entry mb-4">
                    <label for="task_name_1" class="block text-sm font-medium text-gray-700">タスク</label>
                    <input type="text" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" name="task_name[]" id="task_name_1" placeholder="タスクを入力してください">

                    @error('task_name.*')
                        <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- 担当者 -->
                <div class="mb-4">
                    <label for="assignee_1" class="block text-sm font-medium text-gray-700">担当者</label>
                    <select class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" name="assignee[]" id="assignee_1">
                        <option value="employee1">社員1</option>
                        <option value="employee2">社員2</option>
                        <option value="employee3">社員3</option>
                    </select>
                    @error('assignee.*')
                        <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- 日付入力 -->
                <div class="mb-4 flex items-center justify-between">
                    <div class="w-1/3 mr-2">
                        <label for="start_date_task_1" class="block text-sm font-medium text-gray-700">タスク実行開始日</label>
                        <input type="date" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" name="start_date_task[]" id="start_date_task_1">

                        @error('start_date_task.*')
                            <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="w-1/3">
                        <label for="end_date_task_1" class="block text-sm font-medium text-gray-700">タスク実行終了日</label>
                        <input type="date" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" name="end_date_task[]" id="end_date_task_1">

                        @error('end_date_task.*')
                            <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- タスク追加ボタン -->
            <div class="mt-4">
                <button type="button" id="add-task-btn" class="w-full bg-gray-200 text-gray-700 font-semibold py-2 px-4 rounded-md hover:bg-gray-300">タスクを追加</button>
            </div>

            <!-- 登録ボタン -->
            <div class="mt-4">
                <button type="submit" class="w-full bg-[#86D4FE] text-white font-semibold py-2 px-4 rounded-md hover:bg-[#67C1E3]">登録</button>
            </div>

            <!-- キャンセルボタン -->
            <div class="mt-2">
                <button type="button" class="w-full bg-gray-300 text-gray-700 font-semibold py-2 px-4 rounded-md hover:bg-gray-400">キャンセル</button>
            </div>
        </form>
    </div>
</div>

<!-- JavaScript -->
@section('scripts')
<script>
    document.getElementById('add-task-btn').addEventListener('click', function() {
        var taskContainer = document.getElementById('tasks-container');
        
        // 最初のタスクフォームを取得
        var taskEntry = taskContainer.querySelector('.task-entry');
        
        // クローンを作成
        var newTaskEntry = taskEntry.cloneNode(true); 
        
        // 新しいタスクにインデックスを追加（idとnameに番号を付与）
        var taskCount = taskContainer.querySelectorAll('.task-entry').length; // 現在のタスク数をカウント
        
        // 新しいタスクにインデックスを追加（idとnameに番号を付与）
        newTaskEntry.querySelector('[name="task_name[]"]').setAttribute('name', 'task_name[' + taskCount + ']');
        newTaskEntry.querySelector('[name="assignee[]"]').setAttribute('name', 'assignee[' + taskCount + ']');
        newTaskEntry.querySelector('[name="start_date_task[]"]').setAttribute('name', 'start_date_task[' + taskCount + ']');
        newTaskEntry.querySelector('[name="end_date_task[]"]').setAttribute('name', 'end_date_task[' + taskCount + ']');
        
        // タスクフォームを追加
        taskContainer.appendChild(newTaskEntry);
    });
</script>
@endsection

@endsection
