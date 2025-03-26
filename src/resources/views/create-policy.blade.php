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

            <!-- 部署選択 -->
            <div class="mb-4">
                <label for="measure_department_id" class="block text-sm font-medium text-gray-700">部署</label>
                <select class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" id="measure_department_id" name="department_id" required>
                    <option value="">部署を選択してください</option>
                    @foreach($departments as $department)
                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- 評価改善の頻度 -->
            <div class="mb-4">
                <label for="evaluation_frequency" class="block text-sm font-medium text-gray-700">評価改善の頻度</label>
                <label for="evaluation_frequency" class="block text-sm font-medium text-gray-700 mt-4">評価頻度</label>
                <select id="evaluation_frequency" name="evaluation_frequency" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option value="1">1ヶ月</option>
                    <option value="3">3ヶ月</option>
                    <option value="6">6ヶ月</option>
                    <option value="12">12ヶ月</option>
                    <option value="custom">カスタム</option>
                </select>
            </div>

            <!-- カスタム評価頻度 -->
            <div id="custom-frequency-field" style="display: none;">
                <label for="custom_frequency_value" class="block text-sm font-medium text-gray-700 mt-4">カスタム頻度の値</label>
                <input type="number" id="custom_frequency_value" name="custom_frequency_value" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="例: 2">

                <label for="custom_frequency_unit" class="block text-sm font-medium text-gray-700 mt-4">カスタム頻度の単位</label>
                <select id="custom_frequency_unit" name="custom_frequency_unit" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option value="weeks">週間</option>
                    <option value="months">月間</option>
                </select>
                <span class="text-sm">ごと</span>
            </div>
            </div>

            <!-- タスクカード（最初のタスクフォームを表示） -->
            <div class="max-w-4xl mx-auto bg-white shadow-md rounded-lg p-6 mb-6">
                <h3 class="text-xl font-semibold mb-4">タスク作成</h3>
                <div id="tasks-container" class="space-y-4">
                    <!-- 最初のタスクフォーム -->
                    <div class="task-entry mb-4">
                        <div class="max-w-xs mx-auto bg-white shadow-md rounded-lg p-6 mb-6">
                            <label for="task_name_1" class="block text-sm font-medium text-gray-700">タスク</label>
                            <input type="text" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" name="task_name[]" id="task_name_1" placeholder="タスクを入力してください">

                            <!-- 部署選択 -->
                            <label for="task_department_id_1" class="block text-sm font-medium text-gray-700 mt-4">部署</label>
                            <select class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" name="task_department_id[]" id="task_department_id_1" required>
                                <option value="">部署を選択してください</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                                @endforeach
                            </select>

                            <!-- 担当者選択 -->
                            <select name="assignee[]" class="form-control">
                                <option value="">担当者を選んでください</option>
                                @foreach ($Users as $User)
                                    <option value="{{ $User->id }}">{{ $User->name }}</option>
                                @endforeach
                            </select>

                            <!-- 開始日 -->
                            <label for="start_date_task_1" class="block text-sm font-medium text-gray-700 mt-4">タスク実行開始日</label>
                            <input type="date" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" name="start_date_task[]" id="start_date_task_1">

                            <!-- 終了日 -->
                            <label for="end_date_task_1" class="block text-sm font-medium text-gray-700 mt-4">タスク実行終了日</label>
                            <input type="date" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" name="end_date_task[]" id="end_date_task_1">
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
            </div>
        </form>
    </div>
</div>

<!-- 最初のタスクフォーム（非表示） -->
<div id="task-entry-template" style="display: none;">
    <div class="task-entry mb-4 max-w-xs mx-auto bg-white shadow-md rounded-lg p-6 mb-6">
        <label for="task_name" class="block text-sm font-medium text-gray-700">タスク</label>
        <input type="text" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" name="task_name[]" placeholder="タスクを入力してください">

        <!-- 部署選択 -->
        <label for="task_department_id" class="block text-sm font-medium text-gray-700 mt-4">部署</label>
        <select class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" name="task_department_id[]" id="task_department_id" required>
            <option value="">部署を選んでください</option>
            @foreach($departments as $department)
                <option value="{{ $department->id }}">{{ $department->name }}</option>
            @endforeach
        </select>

        <!-- 担当者選択 -->
        <select name="assignee[]" class="form-control">
            <option value="">担当者を選んでください</option>
            @foreach ($Users as $User)
                <option value="{{ $User->id }}">{{ $User->name }}</option>
            @endforeach
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