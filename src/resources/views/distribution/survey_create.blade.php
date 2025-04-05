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
            <form id="surveyForm" action="{{ route('survey.store') }}" method="POST">
                @csrf

                <div class="bg-white p-4 mb-4 border shadow-lg">
                    <label class="block text-gray-700 font-semibold mb-1 mt-1">アンケートタイトル：</label>
                    <input type="text" name="name" id="surveyName" required placeholder="アンケートタイトルを入力してください"
                        value="{{ session('survey_input.name') }}"
                        class="border border-custom-gray px-4 py-2 w-full focus:ring focus:ring-blue-200">

                    <label class="block text-gray-700 font-semibold mb-1 mt-4">詳細説明：</label>
                    <textarea name="description" id="surveyDescription" placeholder="詳細説明を入力してください"
                        class="border px-4 py-2 w-full h-24 focus:ring focus:ring-blue-200">{{ session('survey_input.description') }}</textarea>
                </div>
            </form>

            <!-- ✅ 設問リスト（フォームの外） -->
            <div class="bg-white p-4 mt-6 border shadow-lg">
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
                                            <input
                                                type="checkbox"
                                                class="sr-only peer toggle-switch"
                                                data-id="{{ $question->id }}"
                                                data-status="{{ $question->display_status ? '1' : '0' }}"
                                                {{ !$question->display_status ? 'checked' : '' }}
                                            >
                                            <div class="w-11 h-6 bg-gray-300 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-blue-500 rounded-full peer peer-checked:bg-blue-600 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-full"></div>
                                        </label>
                                    </div>
                                @endif
                            </div>
                            <p class="font-semibold pl-6 my-4 text-lg flex items-center justify-between">
                                質問文：{{ $question->text }}

                                <!-- アコーディオンボタン -->
                                <button type="button"
                                        class="accordion-toggle text-sm text-blue-600 underline ml-4"
                                        data-target="options-{{ $question->id }}">
                                    ▼ 選択肢を表示
                                </button>
                            </p>

                            <!-- アコーディオンの中身 -->
                            <div class="accordion-content hidden pl-10 pb-4" id="options-{{ $question->id }}">
                                @if($question->surveyQuestionOptions->isNotEmpty())
                                    <table class="table-auto w-full border text-sm">
                                        <thead>
                                            <tr class="bg-gray-100">
                                                <th class="border px-4 py-2">項目文</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($question->surveyQuestionOptions as $option)
                                                <tr>
                                                    <td class="border px-4 py-2">{{ $option->text }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <p class="text-gray-500">選択肢が登録されていません。</p>
                                @endif
                            </div>
                        </div>
                        @php $questionNumber++; @endphp
                    @endif
                @endforeach
            </div>

            <!-- ✅ 最下部リンク -->
            <div class="flex justify-center mt-8">
                <a href="javascript:void(0);" id="goToGroupSelection"
                    class="w-64 text-center px-14 py-3 bg-[#86D4FE] text-white font-bold rounded-full shadow-lg hover:bg-[#69C2FD] transition duration-300">
                    配信部署選択画面へ
                </a>
            </div>
            
            <div class="flex justify-center mt-4">
                <a href="javascript:void(0);" id="goToItemEdit"
                    class="w-64 text-center px-14 py-3 bg-[#C4C4C4] text-white font-bold rounded-full shadow-lg hover:bg-[#B8B8B8] transition duration-300">
                    設問編集画面へ
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // トグル切替処理
        document.querySelectorAll('.toggle-switch').forEach(toggle => {
            toggle.addEventListener('change', function () {
                const questionId = this.dataset.id;
                const newStatus = this.checked;

                fetch(`/survey-question/toggle-display/${questionId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ display_status: !newStatus })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const block = document.getElementById(`question-block-${questionId}`);
                        data.display_status
                            ? block.classList.remove('opacity-50')
                            : block.classList.add('opacity-50');
                    } else {
                        alert(data.message || '更新に失敗しました');
                    }
                });
            });
        });

        // アコーディオン開閉処理
        document.querySelectorAll('.accordion-toggle').forEach(toggle => {
            toggle.addEventListener('click', function () {
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
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ name, description })
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
        document.getElementById('goToGroupSelection').addEventListener('click', function (e) {
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
        document.getElementById('goToItemEdit').addEventListener('click', function (e) {
            e.preventDefault();

            const button = this;
            const originalText = button.textContent;

            button.textContent = '保存中...';
            button.classList.add('opacity-70', 'pointer-events-none');

            saveSurveyToSession(
                () => {
                    window.location.href = "{{ route('survey.item-edit') }}";
                },
                () => {
                    alert('セッション保存中にエラーが発生しました');
                    button.textContent = originalText;
                    button.classList.remove('opacity-70', 'pointer-events-none');
                }
            );
        });
    });
</script>
@endsection
