<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>評価/改善未対応施策一覧</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
</head>
<body class="bg-light-gray">
    @include('components.sidebar')
    <div class="ml-64">
        <div class="flex justify-between p-5">
            <div class="flex">
                <figure>
                    <img src="{{ asset('images/title_logo.png') }}" alt="" />
                </figure>
                <p class="ml-2 text-2xl font-bold">施策の評価/改善</p>
            </div>
        </div>
        <div class="text-gray-500 text-sm">
            <a href="{{ route('measure.no-evaluation') }}">評価/改善未対応施策一覧</a> > 施策の評価/改善（{{ $measure->title }}）
        </div>
        <!-- ヘッダー -->
        <div class="flex items-center justify-between bg-custom-gray text-white px-4 py-2 w-49/50 mt-4">
            <span class="text-lg font-semibold">施策 ({{ $measure->created_at->format('Y-m-d') }}) ～
                @if ($measure->evaluation_status == 2 && $measure->evaluation->isNotEmpty())
                ({{ $measure->evaluation->max('created_at')->format('Y-m-d') }})
                @endif
            </span>
        </div>
        <!-- 内容 -->
        <div class="flex items-start p-4 px-8 font-semibold bg-white w-49/50">
            {{ $measure->title}}
        </div>
        @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
            <strong class="font-bold">エラーが発生しました！</strong>
            <span class="block sm:inline">{{ $errors->first('error') }}</span>
        </div>
        @endif
        @if ($displayStatus == 1)
        <div class="mt-6 bg-white text-black p-8 font-sans w-49/50">
            <!-- フォーム開始 -->
            <form id="evaluationForm" method="POST" action="{{ route('measures.evaluation-store', ['id' => $measure->id]) }}">
                @csrf
                <!-- タスクの評価 -->
                <section class="mb-6">
                    <table class="w-full text-left border border-gray-300">
                        <thead>
                            <tr>
                                <th class="border px-4 py-2 w-2/5">タスク</th>
                                <th class="border px-4 py-2">担当者</th>
                                <th class="border px-4 py-2">総括</th>
                                <th class="border px-4 py-2 w-1/3">実行状況（達成度・進捗）</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($measure->tasks as $task)
                            <tr>
                                <td class="border px-4 py-2 w-1/5">
                                    {{ $task->name }}
                                    <input type="hidden" name="tasks[{{ $task->id }}][name]" value="{{ $task->name }}">
                                </td>
                                <td class="border px-4 py-2">
                                    {{ $task->user->name ?? '未割り当て' }}
                                    <input type="hidden" name="tasks[{{ $task->id }}][user]" value="{{ $task->user->name ?? '未割り当て' }}">
                                </td>
                                <td class="border px-4 py-2 text-center">
                                    <select name="tasks[{{ $task->id }}][score]" class="task-score appearance-none w-full bg-white border border-gray-400 py-0.5 pl-3 pr-8 rounded-full shadow focus:outline-none focus:ring-2 focus:ring-blue-300">
                                        <option value="" selected disabled></option>
                                        <option value="1">◎</option>
                                        <option value="2">◯</option>
                                        <option value="3">△</option>
                                        <option value="4">✕</option>
                                        <option value="5">ー</option>
                                    </select>
                                    <p class="text-red-500 text-sm hidden error-score mt-1">入力してください</p>
                                </td>
                                <td class="border px-2 py-2">
                                    <input type="text" name="tasks[{{ $task->id }}][comment]" class="task-comment appearance-none border-none focus:outline-none focus:ring-0 w-full" placeholder="入力してください（任意）" maxlength="255">
                                    <p class="text-red-500 text-sm hidden error-comment mt-1">255文字以内で入力してください。</p>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </section>

                <!-- 改善点の整理 -->
                <section>
                    <h2 class="text-lg font-bold border-b-4 border-custom-blue inline-block mb-4">改善点の整理</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- KEEP -->
                        <div class="border border-custom-blue p-4">
                            <h3 class="text-custom-blue font-bold text-center text-2xl">KEEP</h3>
                            <p class="text-custom-blue text-sm text-center mb-2">うまくいったこと/継続すべきこと</p>
                            <textarea name="keep" class="keep w-full border rounded p-2 focus:outline-none border-none focus:ring-0 resize-none" rows="4" placeholder="入力してください"></textarea>
                            <p class="text-red-500 text-sm hidden error-keep">KEEPは1000文字以内で入力してください。</p>
                        </div>

                        <!-- PROBLEM -->
                        <div class="border border-red-300 p-4">
                            <h3 class="text-red-500 font-bold text-center text-2xl">PROBLEM</h3>
                            <p class="text-red-400 text-sm text-center mb-2">うまくいかなかったこと/発生した課題</p>
                            <textarea name="problem" class="problem w-full border rounded p-2 focus:outline-none border-none focus:ring-0 resize-none" rows="4" placeholder="入力してください"></textarea>
                            <p class="text-red-500 text-sm hidden error-problem">PROBLEMは1000文字以内で入力してください。</p>
                        </div>

                        <!-- TRY -->
                        <div class="border border-green-400 p-4">
                            <h3 class="text-green-500 font-bold text-center text-2xl">TRY</h3>
                            <p class="text-green-400 text-sm text-center mb-2">改善すべきこと/新たに実践すべきこと</p>
                            <textarea name="try" class="try w-full border rounded p-2 focus:outline-none border-none focus:ring-0 resize-none" rows="4" placeholder="入力してください"></textarea>
                            <p class="text-red-500 text-sm hidden error-try">TRYは1000文字以内で入力してください。</p>
                        </div>
                    </div>
                </section>
                <!-- 送信ボタン -->
                <div class="mt-8 flex justify-center">
                    <button type="submit" class="bg-button-blue hover:bg-custom-blue text-white px-12 py-2 rounded-full shadow font-bold">保存する</button>
                </div>
            </form>
            <!-- フォーム終了 -->
        </div>
        @endif
        @foreach ($measure->evaluation->sortByDesc('created_at') as $index => $evaluation)
        <div class="mt-6 bg-white text-black p-8 font-sans w-49/50">
            <!-- タイトル -->
            <h1 class="text-xl font-bold mb-4">
                ●第{{ $index + 1 }}回の評価/改善
                <span class="ml-4 text-gray-600">({{ $evaluation->created_at->format('Y/m/d') }})</span>
            </h1>

            <!-- 施策の実行状況 -->
            <section class="mb-6">
                <div class="flex items-center justify-between mb-2">
                    <h2 class="text-lg font-bold border-b-4 border-custom-blue inline-block mb-2">施策の実行状況</h2>
                    <p class="text-sm text-gray-600 mt-1">◎: 計画以上　○: 計画通り　△: 一部実行できず　✕: ほぼ実行できず　ー: 対象外</p>
                </div>
                <table class="w-full text-left border border-gray-300">
                    <thead>
                        <tr>
                            <th class="border px-4 py-2 w-2/5">タスク</th>
                            <th class="border px-4 py-2">担当者</th>
                            <th class="border px-4 py-2">総括</th>
                            <th class="border px-4 py-2 w-1/3">実行状況（達成度・進捗）</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($evaluation->evaluationTask as $task)
                        <tr>
                            <td class="border px-4 py-2">{{ $task->task->name }}</td>
                            <td class="border px-4 py-2">{{ $task->task->user->name ?? '未割り当て' }}</td>
                            <td class="border px-4 py-2 text-center">
                                @switch($task->score)
                                @case(1)
                                ◎
                                @break
                                @case(2)
                                ◯
                                @break
                                @case(3)
                                △
                                @break
                                @case(4)
                                ✕
                                @break
                                @case(5)
                                ー
                                @break
                                @default
                                -
                                @endswitch
                            </td>
                            <td class="border px-4 py-2">{{ $task->comment }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </section>

            <!-- 改善点の整理 -->
            <section>
                <h2 class="text-lg font-bold border-b-4 border-custom-blue inline-block mb-4">改善点の整理</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- KEEP -->
                    <div class="border border-custom-blue p-4">
                        <h3 class="text-custom-blue font-bold text-center text-2xl">KEEP</h3>
                        <p class="text-custom-blue text-sm text-center mb-3">うまくいったこと/継続すべきこと</p>
                        <ul class="list-disc pl-2">
                            {{ $evaluation->keep }}
                        </ul>
                    </div>

                    <!-- PROBLEM -->
                    <div class="border border-red-300 p-4">
                        <h3 class="text-red-500 font-bold text-center text-2xl">PROBLEM</h3>
                        <p class="text-red-400 text-sm text-center mb-3">うまくいかなかったこと/発生した課題</p>
                        <ul class="list-disc pl-2">
                            {{ $evaluation->problem }}
                        </ul>
                    </div>

                    <!-- TRY -->
                    <div class="border border-green-400 p-4">
                        <h3 class="text-green-500 font-bold text-center text-2xl">TRY</h3>
                        <p class="text-green-400 text-sm text-center mb-3">改善すべきこと/新たに実践すべきこと</p>
                        <ul class="list-disc pl-2">
                            {{ $evaluation->try }}
                        </ul>
                    </div>
                </div>
            </section>
        </div>
        @endforeach
        <div class="mt-8 mb-6 flex justify-center">
            <a href="{{ route('measure.no-evaluation') }}" class="bg-button-blue hover:bg-custom-blue text-white px-12 py-2 rounded-full shadow text-xl font-bold">評価/改善未対応施策一覧へ</a>
        </div>
    </div>
    <script>
        document.getElementById('evaluationForm').addEventListener('submit', function(e) {
            let isValid = true;

            // タスクの評価(score)のバリデーション
            document.querySelectorAll('.task-score').forEach(function(select) {
                const error = select.parentElement.querySelector('.error-score');
                if (!select.value) {
                    error.textContent = '入力してください';
                    error.classList.remove('hidden');
                    isValid = false;
                } else {
                    error.classList.add('hidden');
                }
            });

            // タスクのコメント(comment)のバリデーション
            document.querySelectorAll('.task-comment').forEach(function(input) {
                const error = input.parentElement.querySelector('.error-comment');
                if (input.value.length > 255) {
                    error.textContent = '255文字以内で入力してください。';
                    error.classList.remove('hidden');
                    isValid = false;
                } else {
                    error.classList.add('hidden');
                }
            });

            // KEEP, PROBLEM, TRYのバリデーション
            ['keep', 'problem', 'try'].forEach(function(field) {
                const textarea = document.querySelector(`.${field}`);
                const error = document.querySelector(`.error-${field}`);
                if (!textarea.value) {
                    error.textContent = `${field.toUpperCase()}は必須項目です。`;
                    error.classList.remove('hidden');
                    isValid = false;
                } else if (textarea.value.length > 1000) {
                    error.textContent = `${field.toUpperCase()}は1000文字以内で入力してください。`;
                    error.classList.remove('hidden');
                    isValid = false;
                } else {
                    error.classList.add('hidden');
                }
            });

            // フォーム送信を停止する
            if (!isValid) {
                e.preventDefault();
            }
        });

    </script>
</body>
