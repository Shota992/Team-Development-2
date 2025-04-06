@extends('layouts.app')

@section('content')
@include('components.sidebar')
<div class="container mx-auto px-4 py-6">
    <h2 class="text-2xl font-semibold mb-4 ml-64">施策作成画面</h2>

    <div class="max-w-4xl mx-auto bg-white shadow-md rounded-lg p-6 mb-6 ml-64">
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
                <label class="block text-sm font-medium text-gray-700">施策名</label>
                <input type="text" name="title" value="{{ old('title') }}" required class="mt-1 w-full px-3 py-2 border rounded" placeholder="施策名を入力してください">
            </div>

            <!-- 施策内容 -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">施策内容</label>
                <textarea name="description" rows="4" required class="mt-1 w-full px-3 py-2 border rounded" placeholder="施策内容を入力してください">{{ old('description') }}</textarea>
            </div>

            <!-- 部署 -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">部署</label>
                <select name="department_id" required class="mt-1 w-full px-3 py-2 border rounded">
                    <option value="">選択してください</option>
                    @foreach($departments as $department)
                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- 評価頻度 -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">評価頻度</label>
                <select id="evaluation_frequency" name="evaluation_frequency" class="mt-1 w-full px-3 py-2 border rounded">
                    <option value="1">1ヶ月</option>
                    <option value="3">3ヶ月</option>
                    <option value="6">6ヶ月</option>
                    <option value="12">12ヶ月</option>
                    <option value="custom">カスタム</option>
                </select>
            </div>

            <!-- カスタム頻度 -->
            <div id="custom-frequency-field" class="mb-4" style="display:none;">
                <label class="block text-sm font-medium text-gray-700">カスタム頻度の値</label>
                <input type="number" name="custom_frequency_value" class="mt-1 w-full px-3 py-2 border rounded" placeholder="例:2">

                <label class="block text-sm font-medium text-gray-700 mt-4">単位</label>
                <select name="custom_frequency_unit" class="mt-1 w-full px-3 py-2 border rounded">
                    <option value="weeks">週間</option>
                    <option value="months">月間</option>
                </select>
            </div>

            <!-- タスク作成 -->
            <h3 class="text-xl font-semibold mb-4">タスク作成</h3>
            <div id="tasks-container" class="space-y-4">
                <div class="task-entry bg-white p-6 rounded shadow relative">
                    <button type="button"
                        class="remove-task-btn absolute top-2 right-2 text-red-500 hover:text-red-700 text-lg hidden"
                        title="削除">×</button>

                    <label class="block text-sm">タスク</label>
                    <input type="text" name="task_name[]" required class="mt-1 w-full border rounded">

                    <label class="block text-sm mt-4">部署</label>
                    <select name="task_department_id[]" required class="mt-1 w-full border rounded">
                        <option value="">選択してください</option>
                        @foreach($departments as $d)
                            <option value="{{ $d->id }}">{{ $d->name }}</option>
                        @endforeach
                    </select>

                    <label class="block text-sm mt-4">担当者</label>
                    <select name="assignee[]" required class="mt-1 w-full border rounded"></select>

                    <label class="block text-sm mt-4">開始日</label>
                    <input type="date" name="start_date_task[]" required class="mt-1 w-full border rounded">

                    <label class="block text-sm mt-4">終了日</label>
                    <input type="date" name="end_date_task[]" required class="mt-1 w-full border rounded">
                </div>
            </div>

            <!-- クローン用テンプレート -->
            <div id="task-entry-template" style="display:none;">
                <div class="task-entry bg-white p-6 rounded shadow relative">
                    <button type="button"
                        class="remove-task-btn  top-1 right-2 text-red-500 hover:text-red-700 text-xl font-bold z-10"
                        title="削除">×</button>

                        <label class="block text-sm">タスク</label>
                        <input type="text" name="task_name[]" required class="mt-1 w-full border rounded">

                    <label class="block text-sm mt-4">部署</label>
                    <select name="task_department_id[]" disabled class="mt-1 w-full border rounded">
                        <option value="">選択してください</option>
                        @foreach($departments as $d)
                            <option value="{{ $d->id }}">{{ $d->name }}</option>
                        @endforeach
                    </select>

                    <label class="block text-sm mt-4">担当者</label>
                    <select name="assignee[]" disabled class="mt-1 w-full border rounded"></select>

                    <label class="block text-sm mt-4">開始日</label>
                    <input type="date" name="start_date_task[]" disabled class="mt-1 w-full border rounded">

                    <label class="block text-sm mt-4">終了日</label>
                    <input type="date" name="end_date_task[]" disabled class="mt-1 w-full border rounded">
                </div>
            </div>

            <div class="flex flex-col items-center space-y-4 mt-8">
                <!-- タスク追加 -->
                <button type="button" id="add-task-btn"
                    class="flex items-center justify-center px-4 py-2 text-sm font-medium text-gray-600 bg-gray-100 border border-gray-300 rounded-full shadow hover:bg-gray-200">
                    <span class="text-xl mr-2">＋</span> タスク追加
                </button>
            
                <!-- 登録ボタン -->
                <button type="submit"
                    class="w-64 py-3 text-white bg-blue-500 hover:bg-blue-600 font-bold text-lg rounded-full shadow transition duration-200">
                    登録
                </button>
            
                <!-- キャンセルボタン -->
                <button type="button" id="cancel-btn"
                    class="w-64 py-3 text-gray-600 bg-gray-200 hover:bg-gray-300 font-semibold text-lg rounded-full shadow-inner transition duration-200">
                    キャンセル
                </button>
            </div>
            
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ mix('js/taskForm.js') }}"></script>
<script>
    document.addEventListener('click', (e) => {
      if (e.target.classList.contains('remove-task-btn')) {
        const taskEntry = e.target.closest('.task-entry');
        const container = document.getElementById('tasks-container');
        if (taskEntry && container.children.length > 1) {
          taskEntry.remove();
        } else {
          alert('タスクは最低1つ必要です');
        }
      }
    });
  </script>
@endsection
