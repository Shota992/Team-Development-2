<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>社員アンケート</title>
    @vite('resources/css/app.css')
</head>
<body>
    <!-- タイトル -->
    <div class="flex flex-col items-center">
        <div class="mt-4 bg-white shadow-md rounded-lg w-11/12 md:w-full max-w-3xl">
            <div class="h-8 bg-button-blue rounded-t-lg"></div>
            <div class="p-6">
                <h2 class="font-bold text-xl">{{ $survey->name}}</h2>
                <p class="mt-2 text-base leading-relaxed">
                    {{ $survey->description }}
                </p>
            </div>
        </div>
    </div>
    @if ($dateStatus === 1)
    <!-- 開始日前のメッセージ -->
    <div class="flex flex-col items-center mt-10">
        <div class="bg-white shadow-md rounded-lg w-11/12 md:w-full max-w-3xl p-6">
            <h1 class="text-2xl font-bold text-center text-red-500">アンケートはまだ開始されていません</h1>
            <p class="mt-4 text-center text-gray-700">
                このアンケートは{{ $survey->start_date }}から開始されます。
            </p>
        </div>
    </div>
@elseif ($dateStatus === 2)
    <!-- 終了後のメッセージ -->
    <div class="flex flex-col items-center mt-10">
        <div class="bg-white shadow-md rounded-lg w-11/12 md:w-full max-w-3xl p-6">
            <h1 class="text-2xl font-bold text-center text-red-500">アンケートは終了しました</h1>
            <p class="mt-4 text-center text-gray-700">
                このアンケートは{{ $survey->end_date }}に終了しました。ご協力ありがとうございました。
            </p>
        </div>
    </div>
@else
    @if ($answeredStatus === 1)
        <!-- 回答済みメッセージ -->
        <div class="flex flex-col items-center mt-10">
            <div class="bg-white shadow-md rounded-lg w-11/12 md:w-full max-w-3xl p-6">
                <h1 class="text-2xl font-bold text-center text-custom-blue">回答済みです</h1>
                <p class="mt-4 text-center text-gray-700">
                    このアンケートはすでに回答済みです。ご協力ありがとうございました。
                </p>
            </div>
        </div>
    @else
    <!-- 進捗バー -->
    <div class="w-11/12 md:w-full flex max-w-3xl mx-auto my-4">
        <p id="progress-text" class="text-lg font-bold text-gray-700 mr-4 pt-1">0%</p>
        <div class="w-full h-2 bg-gray-200 rounded-full mt-4">
            <div id="progress-bar" class="h-full bg-button-blue rounded-full w-0"></div>
        </div>
    </div>
    <p id="saving-message" class="text-center text-lg font-bold text-gray-700 mt-4 hidden">
        解答を送信中です。しばらくお待ち下さい。
    </p>
    <!-- 質問と理由 -->
    <div id="survey-container">
        @foreach ($surveyItems as $index => $item)
        <div class="question {{ $index !== 0 ? 'hidden' : '' }}" data-question-id="{{ $item->id }}">
            <h3 class="w-11/12 md:w-full font-bold text-lg mt-4 md:mt-10 text-center mx-auto">
                Q{{ $index + 1 }}. {{ $item->text }}
            </h3>
            <div class="flex items-center my-6 md:px-6 justify-center">
                <div class="font-bold hidden md:block mr-4">満足している</div>
                <div class="flex gap-6 md:gap-12 items-center">
                    <div>
                        <input type="radio" name="response-{{ $item->id }}" id="choice-5-{{ $item->id }}" value="5" class="hidden peer">
                        <label for="choice-5-{{ $item->id }}" class="w-14 h-14 border-4 border-custom-blue rounded-full block transition peer-checked:bg-custom-blue peer-checked:text-white">
                        </label>
                    </div>
                    <div>
                        <input type="radio" name="response-{{ $item->id }}" id="choice-4-{{ $item->id }}" value="4" class="hidden peer">
                        <label for="choice-4-{{ $item->id }}" class="w-12 h-12 border-4 border-custom-blue rounded-full block transition peer-checked:bg-custom-blue peer-checked:text-white">
                        </label>
                    </div>
                    <div>
                        <input type="radio" name="response-{{ $item->id }}" id="choice-3-{{ $item->id }}" value="3" class="hidden peer">
                        <label for="choice-3-{{ $item->id }}" class="w-10 h-10 border-4 border-gray-300 rounded-full block transition peer-checked:bg-gray-300 peer-checked:text-white">
                        </label>
                    </div>
                    <div>
                        <input type="radio" name="response-{{ $item->id }}" id="choice-2-{{ $item->id }}" value="2" class="hidden peer">
                        <label for="choice-2-{{ $item->id }}" class="w-12 h-12 border-4 border-red-300 rounded-full block transition peer-checked:bg-red-300 peer-checked:text-white">
                        </label>
                    </div>
                    <div>
                        <input type="radio" name="response-{{ $item->id }}" id="choice-1-{{ $item->id }}" value="1" class="hidden peer">
                        <label for="choice-1-{{ $item->id }}" class="w-14 h-14 border-4 border-red-300 rounded-full block transition peer-checked:bg-red-300 peer-checked:text-white">
                        </label>
                    </div>
                </div>
                <div class="font-bold hidden md:block ml-4">満足していない</div>
            </div>

            <!-- 理由選択 -->
            <div class="w-11/12 md:w-full flex flex-col items-center mx-auto">
                <h3 class="font-bold text-lg mt-4 mb:mt-10 text-left">
                    上記の項目に関して不満な点があれば、以下の項目を選択してください
                </h3>
                <div class="mt-4 flex flex-col space-y-4 w-full max-w-md">
                    @foreach ($item->surveyQuestionOptions as $option)
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="reason-{{ $item->id }}[]" value="{{ $option->id }}" class="w-5 h-5 text-custom-blue">
                        <span class="leading-none">{{ $option->text }}</span>
                    </label>
                    @endforeach
                    <!-- その他の理由 -->
                    <div class="flex items-center gap-2">
                        <input type="checkbox" id="otherCheckbox-{{ $item->id }}" class="w-5 h-5">
                        <label for="otherCheckbox-{{ $item->id }}" class="cursor-pointer leading-none">その他</label>
                        <input type="text" name="other-reason-{{ $item->id }}" id="otherText-{{ $item->id }}" class="border-b border-gray-400 focus:border-black outline-none px-2 w-64 bg-transparent text-gray-400" placeholder="別の理由を入力" disabled>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- 次へボタン -->
    <div class="mt-10">
        <div class="flex justify-center">
            <button class="bg-gray-400 text-white text-lg font-bold px-10 py-3 rounded-full shadow-md transition transform mb-6 cursor-not-allowed" id="next-btn" disabled>
                次へ →
            </button>
        </div>
    </div>
    <form id="survey-form" method="POST" action="{{ route('survey.employee.post', ['token' => $id]) }}">
        @csrf
        <input type="hidden" name="survey_id" value="{{ $survey->id }}">
        <input type="hidden" name="responses" id="responses-input">
    </form>
    </div>

    @endif
    @endif

    <script>
        let currentIndex = 0;
        let questions = [];
        const progressBar = document.getElementById("progress-bar");
        const progressText = document.getElementById("progress-text");

        // 質問データを取得
        async function fetchQuestions() {
            updateQuestion();
        }

        function updateQuestion() {

            const question = questions[currentIndex];
            document.getElementById("question-title").innerText = `Q${currentIndex + 1}. ${question.title}`;
            document.getElementById("question-text").innerText = question.text;

            // 進捗バーを更新
            let progress = ((currentIndex + 1) / questions.length) * 100;
            progressBar.style.width = progress + "%";
            progressText.innerText = Math.round(progress) + "%";
        }

        document.getElementById("next-btn").addEventListener("click", function() {
            currentIndex++;
            updateQuestion();
        });

        fetchQuestions();

        document.addEventListener('DOMContentLoaded', function() {
            const otherCheckboxes = document.querySelectorAll('input[id^="otherCheckbox-"]');
            otherCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const questionId = this.id.split('-')[1]; // IDから質問IDを取得
                    const otherText = document.getElementById(`otherText-${questionId}`);
                    otherText.disabled = !this.checked; // チェック状態に応じて有効化/無効化
                    otherText.classList.toggle('text-gray-900', this.checked);
                    otherText.classList.toggle('text-gray-400', !this.checked);
                });
            });

            const questions = document.querySelectorAll('.question'); // 全ての質問を取得
            const nextButton = document.getElementById('next-btn');
            const progressBar = document.getElementById('progress-bar');
            const progressText = document.getElementById('progress-text');
            const totalQuestions = questions.length; // 質問の総数
            let currentIndex = 0; // 現在の質問のインデックス
            const responses = []; // 回答データを格納する配列

            // 初期状態で最初の質問だけを表示
            questions.forEach((question, index) => {
                if (index !== 0) {
                    question.classList.add('hidden');
                }
            });

            // 現在の質問のラジオボタンを監視
            function updateButtonState() {
                const currentQuestion = questions[currentIndex];
                const selectedOption = currentQuestion.querySelector('input[type="radio"]:checked');

                if (selectedOption) {
                    nextButton.disabled = false;
                    nextButton.classList.remove('bg-gray-400', 'cursor-not-allowed');
                    nextButton.classList.add('bg-button-blue', 'hover:bg-blue-400', 'cursor-pointer');
                } else {
                    nextButton.disabled = true;
                    nextButton.classList.remove('bg-button-blue', 'hover:bg-blue-400', 'cursor-pointer');
                    nextButton.classList.add('bg-gray-400', 'cursor-not-allowed');
                }
            }

            // ラジオボタンの変更イベントを監視
            questions.forEach((question) => {
                const radioButtons = question.querySelectorAll('input[type="radio"]');
                radioButtons.forEach((radio) => {
                    radio.addEventListener('change', updateButtonState);
                });
            });

            // 次へボタンのクリックイベント
            nextButton.addEventListener('click', function() {
                const currentQuestion = questions[currentIndex];
                const questionId = currentQuestion.getAttribute('data-question-id'); // 質問IDを取得

                // 選択されたラジオボタンの値を取得
                const selectedOption = currentQuestion.querySelector('input[type="radio"]:checked')?.value || null;

                // 選択された理由（チェックボックス）の値を取得
                const selectedReasons = Array.from(
                    currentQuestion.querySelectorAll('input[type="checkbox"]:checked')
                ).map((checkbox) => checkbox.value);

                // その他理由の記述を取得
                const otherReasonInput = currentQuestion.querySelector(`input[name="other-reason-${questionId}"]`);
                const otherReason = otherReasonInput && !otherReasonInput.disabled ? otherReasonInput.value : null;

                // データをresponses配列に追加
                responses.push({
                    question_id: questionId
                    , selectedOption: selectedOption
                    , selectedReasons: selectedReasons
                    , otherReason: otherReason
                , });

                // 現在の質問を非表示にする
                currentQuestion.classList.add('hidden');

                // 次の質問を表示
                currentIndex++;
                if (currentIndex < questions.length) {
                    questions[currentIndex].classList.remove('hidden');
                } else {
                    // アンケート送信時の処理
                    // 進捗バーを100%に更新
                    progressBar.style.width = "100%";
                    progressText.innerText = "100%";

                    // 「次へ」ボタンを非表示にする
                    nextButton.classList.add('hidden');

                    // 「保存中です」メッセージを表示
                    const savingMessage = document.getElementById('saving-message');
                    savingMessage.classList.remove('hidden');

                    // フォームにデータを設定して送信
                    const responsesInput = document.getElementById('responses-input');
                    responsesInput.value = JSON.stringify(responses); // 回答データをJSON形式で設定
                    document.getElementById('survey-form').submit(); // フォームを送信
                }

                // ボタンの状態を更新
                updateButtonState();

                // 進捗バーを更新
                updateProgressBar(currentIndex, questions.length);
            });

            // 進捗バーを更新する関数
            function updateProgressBar(currentIndex, totalQuestions) {
                const progress = ((currentIndex) / totalQuestions) * 100; // パーセンテージ計算
                progressBar.style.width = `${progress}%`; // 進捗バーの幅を更新
                progressText.innerText = `${Math.round(progress)}%`; // テキストを「%」形式で更新
            }
        });

    </script>
</body>
</html>
