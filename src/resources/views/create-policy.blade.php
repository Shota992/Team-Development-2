@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- 施策カード -->
    <h2 class="text-2xl font-semibold mb-4">施策作成画面</h2>
    <div class="max-w-4xl mx-auto bg-white shadow-md rounded-lg p-6 mb-6">
        <form action="{{ route('measures.store') }}" method="POST">
            @csrf
            <!-- 施策名 -->
            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-gray-700">施策名</label>
                <input type="text" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" id="title" name="title" placeholder="施策名を入力してください" value="{{ old('title') }}" required>
            </div>

            <!-- 施策内容 -->
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700">施策内容</label>
                <textarea class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" id="description" name="description" rows="4" placeholder="施策内容を入力してください" required>{{ old('description') }}</textarea>
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
            </div>

            <!-- カスタム設定（間隔の設定） -->
            <div id="custom-frequency-field" style="display: none;" class="mb-4">
                <label for="custom_frequency_interval" class="block text-sm font-medium text-gray-700">評価間隔</label>
                <div class="flex items-center space-x-2">
                    <input type="number" name="custom_frequency_interval" id="custom_frequency_interval" class="mt-1 block w-1/3 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="例: 2" min="1">
                    
                    <select name="custom_frequency_unit" id="custom_frequency_unit" class="mt-1 block w-1/3 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="weeks" {{ old('custom_frequency_unit') == 'weeks' ? 'selected' : '' }}>週</option>
                        <option value="months" {{ old('custom_frequency_unit') == 'months' ? 'selected' : '' }}>月</option>
                    </select>

                    <span class="text-sm">ごと</span>
                </div>
            </div>

        </form>
    </div>

            <!-- タスクカード（最初のタスクフォームを表示） -->
            <div class="max-w-4xl mx-auto bg-white shadow-md rounded-lg p-6 mb-6">
                <h3 class="text-xl font-semibold mb-4">タスク作成</h3>
                <form action="{{ route('measures.store') }}" method="POST">
                    @csrf
                    <div id="tasks-container" class="space-y-4">
                        <!-- 最初のタスクフォーム -->
                        <div class="task-entry mb-4">
                            <div class="max-w-xs mx-auto bg-white shadow-md rounded-lg p-6 mb-6">
                                <label for="task_name_1" class="block text-sm font-medium text-gray-700">タスク</label>
                                <input type="text" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" name="task_name[]" id="task_name_1" placeholder="タスクを入力してください">

                        <!-- 部署選択 -->
                        <div class="mb-4">
                            <label for="department_id" class="block text-sm font-medium text-gray-700">部署</label>
                            <select class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" id="department_id" name="department_id" required>
                                <option value="">部署を選択してください</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- 担当者選択 -->
                        <div class="mb-4">
                            <label for="assignee" class="block text-sm font-medium text-gray-700">担当者</label>
                            <select class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" id="assignee" name="assignee" required>
                                <option value="">担当者を選んでください</option>
                            </select>
                        </div>
                                <!-- 開始日 -->
                                <label for="start_date_task" class="block text-sm font-medium text-gray-700 mt-4">タスク実行開始日</label>
                                <input type="date" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" name="start_date_task[]">

                                <!-- 終了日 -->
                                <label for="end_date_task" class="block text-sm font-medium text-gray-700 mt-4">タスク実行終了日</label>
                                <input type="date" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" name="end_date_task[]">
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
                    <div class="mt-4">
                        <button type="button" id="cancel-btn" class="w-full bg-red-200 text-red-700 font-semibold py-2 px-4 rounded-md hover:bg-red-300">キャンセル</button>
                    </div>
                </form>
            </div>
        </form>
    </div>
</div>

<!-- 最初のタスクフォーム（非表示） -->
<div id="task-entry-template" style="display: none;">
    <div class="task-entry mb-4 max-w-xs mx-auto bg-white shadow-md rounded-lg p-6 mb-6">
        <!-- タスク名 -->
        <label for="task_name" class="block text-sm font-medium text-gray-700">タスク</label>
        <input type="text" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" name="task_name[]" placeholder="タスクを入力してください">

        <!-- 部署選択 -->
        <label for="department_id" class="block text-sm font-medium text-gray-700 mt-4">部署</label>
        <select class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" name="department_id[]" id="department_id" required>
            <option value="">部署を選んでください</option>
            @foreach($departments as $department)
                <option value="{{ $department->id }}">{{ $department->name }}</option>
            @endforeach
        </select>

        <!-- 担当者選択 -->
        <label for="assignee" class="block text-sm font-medium text-gray-700 mt-4">担当者</label>
        <select class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" name="assignee[]" id="assignee" required>
            <option value="">担当者を選んでください</option>
        </select>

        <!-- 開始日 -->
        <label for="start_date_task" class="block text-sm font-medium text-gray-700 mt-4">タスク実行開始日</label>
        <input type="date" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" name="start_date_task[]">

        <!-- 終了日 -->
        <label for="end_date_task" class="block text-sm font-medium text-gray-700 mt-4">タスク実行終了日</label>
        <input type="date" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" name="end_date_task[]">
    </div>
</div>

@section('scripts')
<script src="{{ mix('js/taskForm.js') }}"></script>
@endsection

@endsection
