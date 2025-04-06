@extends('layouts.app')

@section('title', 'アンケート作成 - Kompass')
@include('components.sidebar')

<style>
    /* ツールチップの表示と非表示 */
    .tooltip-content {
        visibility: hidden;
        opacity: 0;
        transition: opacity 0.3s ease-in-out, visibility 0s 0.3s;
        position: absolute;
        top: 100%;
        left: 50%;
        transform: translateX(-50%);
        margin-top: 6px;
        background-color: #4B5563;
        color: white;
        padding: 8px;
        border-radius: 4px;
        font-size: 12px;
        width: max-content;
        max-width: 200px;
        text-align: center;
        z-index: 10;
    }

    /* アイコンにホバーしたときにツールチップを表示 */
    .relative:hover .tooltip-content {
        visibility: visible;
        opacity: 1;
        transition: opacity 0.3s ease-in-out, visibility 0s;
    }

    /* ツールチップアイコンのサイズ */
    .tooltip-icon {
        cursor: pointer;
        font-size: 1.2rem;
        margin-left: 8px;
    }

</style>

<div class="bg-[#F7F8FA] ">
    <div class="min-h-screen pb-8 ml-64 mr-8">
        {{-- ▼ ヘッダー --}}
        <div>
            <div class="flex justify-between p-5 pt-8">
                <div class="flex">
                    <figure>
                        <img src="{{ asset('images/title_logo.png') }}" alt="" />
                    </figure>
                    <p class="ml-2 text-2xl font-bold">アンケート設定 ーアンケート作成ー</p>
                </div>
            </div>
        </div>
        <div>
            @if (session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-md">
                {{ session('success') }}
            </div>
            @endif

            <!-- ✅ フォーム①：アンケート情報（上部） -->

<!-- ✅ フォーム①：アンケート情報（上部） -->
        <form id="surveyForm" action="{{ route('survey.store') }}" method="POST">
            @csrf

            <div class="bg-white p-8 mb-4 border shadow-lg">
                <!-- アンケートタイトル -->
                <label class="block text-lg font-semibold border-b pb-2 mt-4">アンケートタイトル：</label>
                <input type="text" name="name" id="surveyName" required placeholder="アンケートタイトルを入力してください"
                    value="{{ session('survey_input.name') }}"
                    class="border border-custom-gray px-4 py-2 w-full focus:ring focus:ring-blue-200">

                <!-- 詳細説明 -->
                <label class="block text-lg font-semibold border-b pb-2 mt-4">詳細説明：
                    <span class="relative inline-block ml-2">
                        <!-- ツールチップアイコン -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 cursor-pointer tooltip-icon" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-12.75a.75.75 0 00-1.5 0v1a.75.75 0 001.5 0v-1zM9 8.75a.75.75 0 011.5 0v5.5a.75.75 0 01-1.5 0v-5.5z" clip-rule="evenodd" />
                        </svg>
                        <!-- ツールチップ -->
                        <div class="tooltip-content">
                            アンケート配信時の詳細説明です。この説明にアンケート内容や匿名性について記載します。
                        </div>
                    </span>
                </label>
                <textarea name="description" id="surveyDescription" placeholder="詳細説明を入力してください"
                    class="border px-4 py-2 w-full h-24 focus:ring focus:ring-blue-200">{{ session('survey_input.description') }}</textarea>
            </div>
        </form>
            <!-- ✅ 設問リスト（フォームの外） -->
            <div class="bg-white p-8 mt-6 border shadow-lg">
                <h3 class="text-lg font-semibold border-b pb-2">設問一覧：</h3>

                @php $questionNumber = 1; @endphp

                @foreach($questions as $question)
                @if(is_object($question))
                <div class="my-4 border-b question-block transition-opacity duration-300 {{ !$question->display_status ? 'opacity-50' : '' }}" id="question-block-{{ $question->id }}">
                    <div class="flex items-center justify-between">
                        <p class="text-lg">
                            {{ $questionNumber }}. {{ $question->title }}
                        </p>

                        @if(!$question->common_status)
                        <div class="flex items-center gap-2 z-10 relative">
                            <span class="text-sm text-gray-700">非表示にする：</span>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" class="sr-only peer toggle-switch" data-id="{{ $question->id }}" data-status="{{ $question->display_status ? '1' : '0' }}" {{ !$question->display_status ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-blue-500 rounded-full peer peer-checked:bg-blue-600 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-full"></div>
                            </label>
                        </div>
                        @endif
                    </div>
                    <p class="font-semibold pl-6 my-4 text-lg flex items-center justify-between">
                        質問文：{{ $question->text }}

                        <!-- アコーディオンボタン -->
                        <button type="button" class="accordion-toggle text-sm text-blue-600 underline ml-4" data-target="options-{{ $question->id }}">
                            ▼ 選択肢を表示
                        </button>
                    </p>

                    <!-- アコーディオンの中身 -->
                    <div class="accordion-content hidden pl-10 pb-4" id="options-{{ $question->id }}">
                        @if($question->surveyQuestionOptions->isNotEmpty())
                        <ul class="list-disc pl-6 space-y-2">
                            @foreach($question->surveyQuestionOptions as $option)
                            <li class="text-gray-700">{{ $option->text }}</li>
                            @endforeach
                        </ul>
                        @else
                        <p class="text-gray-500">選択肢が登録されていません。</p>
                        @endif
                    </div>

                </div>
                @php $questionNumber++; @endphp
                @endif
                @endforeach
            </div>

            <!-- ✅ アンケート詳細画面へボタン -->
            <div class="flex justify-center mt-8">
                <a href="{{ route('survey.advanced-setting') }}" id="goToGroupSelection"
                    class="w-80 text-center px-14 py-3 bg-[#86D4FE] text-white font-bold rounded-full shadow-lg hover:bg-[#69C2FD] transition duration-300">
                    アンケート詳細画面へ
                </a>
            </div>

            <!-- ✅ 設問一覧画面へボタン -->
            <div class="flex justify-center mt-4">
                <a href="{{ route('survey_questions.index') }}" id="goToItemEdit"
                    class="w-80 text-center px-14 py-3 bg-[#C4C4C4] text-white font-bold rounded-full shadow-lg hover:bg-[#B8B8B8] transition duration-300">
                    設問一覧画面へ
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // トグル切替処理
        document.querySelectorAll('.toggle-switch').forEach(toggle => {
            toggle.addEventListener('change', function() {
                const questionId = this.dataset.id;
                const newStatus = this.checked;

                fetch(`/survey-question/toggle-display/${questionId}`, {
                        method: 'POST'
                        , headers: {
                            'Content-Type': 'application/json'
                            , 'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                        , body: JSON.stringify({
                            display_status: !newStatus
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const block = document.getElementById(`question-block-${questionId}`);
                            data.display_status ?
                                block.classList.remove('opacity-50') :
                                block.classList.add('opacity-50');
                        } else {
                            alert(data.message || '更新に失敗しました');
                        }
                    });
            });
        });

        // アコーディオン開閉処理
        document.querySelectorAll('.accordion-toggle').forEach(toggle => {
            toggle.addEventListener('click', function() {
                const target = document.getElementById(this.dataset.target);
                target.classList.toggle('hidden');
                this.textContent = target.classList.contains('hidden') ? '▼ 選択肢を表示' : '▲ 選択肢を閉じる';
            });
        });

        // 入力値をセッションに保存
        function saveSurveyToSession(callback, onError) {
            const name = document.getElementById('surveyName').value.trim();
            const description = document.getElementById('surveyDescription').value.trim();
            fetch("{{ route('survey.save-session') }}", {
                    method: 'POST'
                    , headers: {
                        'Content-Type': 'application/json'
                        , 'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                    , body: JSON.stringify({
                        name
                        , description
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (callback) callback();
                })
                .catch(() => {
                    if (onError) onError();
                });
        }
        // グループ選択画面へ（バリデーションあり＋ローディング＋復元あり）
        document.getElementById('goToGroupSelection').addEventListener('click', function(e) {
            e.preventDefault();
            const name = document.getElementById('surveyName').value.trim();
            const description = document.getElementById('surveyDescription').value.trim();
            if (!name || !description) {
                alert('アンケートタイトルと詳細説明を入力してください。');
                return;
            }
            const button = this;
            const originalText = button.textContent;
            // ローディング表示
            button.textContent = '保存中...';
            button.classList.add('opacity-70', 'pointer-events-none');
            saveSurveyToSession(
                // 成功時
                () => {
                    window.location.href = "{{ route('survey.advanced-setting') }}";
                },
                // エラー時
                () => {
                    alert('セッション保存中にエラーが発生しました');
                    button.textContent = originalText;
                    button.classList.remove('opacity-70', 'pointer-events-none');
                }
            );
        });
        // 項目編集画面へ（バリデーションなし・同様に保存 → 遷移）
        document.getElementById('goToItemEdit').addEventListener('click', function(e) {
            e.preventDefault();
            const button = this;
            const originalText = button.textContent;
            button.classList.add('opacity-70', 'pointer-events-none');
            saveSurveyToSession(
                () => {
                    window.location.href = "{{ route('survey_questions.index') }}";
                }
                , () => {
                    alert('セッション保存中にエラーが発生しました');
                    button.textContent = originalText;
                    button.classList.remove('opacity-70', 'pointer-events-none');
                }
            );
        });
    });

</script>
