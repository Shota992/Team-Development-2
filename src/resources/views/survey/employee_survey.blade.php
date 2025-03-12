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
            <div class="mt-4 bg-white shadow-md rounded-lg w-full max-w-3xl">
                <div class="h-8 bg-blue-300 rounded-t-lg"></div>
                <div class="p-6">
                    <h2 class="font-bold text-xl">社内改善アンケートへのご協力のお願い</h2>
                    <p class="mt-2 text-base leading-relaxed">
                        皆さまの日々の業務環境をより良くするため、社内改善に関するアンケートを実施いたします。<br>
                        お手数ですが、ご自身の率直なご意見をお聞かせください。<br>回答は約3分で完了します。
                    </p>
                </div>
            </div>
        </div>
    
        <!-- 進捗バー -->
        <div class="w-full max-w-3xl mx-auto my-4">
            <p class="text-sm text-gray-700">15%</p>
            <div class="w-full h-2 bg-gray-200 rounded-full">
                <div class="w-1/6 h-full bg-blue-500 rounded-full"></div>
            </div>
        </div>
    
        <!-- 質問と理由 -->
        <div class="flex flex-col items-center w-full max-w-3xl mx-auto">
            <div class="w-full">
                <h3 class="font-bold text-lg mt-10 text-center">
                    Q1. 当社の顧客基盤の安定性について、どの程度満足していますか？
                </h3>
                <div class="flex justify-between items-center my-6 px-6">
                    <span class="font-bold">満足している</span>
                    <div class="flex gap-4 items-center">
                        <input type="radio" name="satisfaction" id="choice-5" class="hidden">
                        <label for="choice-5" class="w-14 h-14 border-4 border-blue-500 rounded-full block"></label>
    
                        <input type="radio" name="satisfaction" id="choice-4" class="hidden">
                        <label for="choice-4" class="w-10 h-10 border-4 border-blue-500 rounded-full block"></label>
    
                        <input type="radio" name="satisfaction" id="choice-3" class="hidden">
                        <label for="choice-3" class="w-8 h-8 border-4 border-gray-300 rounded-full block"></label>
    
                        <input type="radio" name="satisfaction" id="choice-2" class="hidden">
                        <label for="choice-2" class="w-10 h-10 border-4 border-red-300 rounded-full block"></label>
    
                        <input type="radio" name="satisfaction" id="choice-1" class="hidden">
                        <label for="choice-1" class="w-14 h-14 border-4 border-red-300 rounded-full block"></label>
                    </div>
                    <span class="font-bold">満足していない</span>
                </div>
            </div>
    
            <!-- 理由選択 -->
            <div class="w-full">
                <h3 class="font-bold text-lg mt-10 text-center">
                    上記をお選びいただいた理由（該当するものを全て選択してください）
                </h3>
                <div class="flex flex-col mt-4 space-y-4">
                    <label class="flex items-center">
                        <input type="checkbox" class="w-5 h-5 mr-2"> 既存のお客様との取引が安定していると感じるため
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" class="w-5 h-5 mr-2"> 新規顧客の獲得が順調であると感じるため
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" class="w-5 h-5 mr-2"> 顧客との関係性が強く、長期的な信頼が築けているため
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" class="w-5 h-5 mr-2"> 顧客の入れ替わりが激しく、不安定であると感じるため
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" class="w-5 h-5 mr-2"> 競合他社と比較して、当社の顧客基盤に不安を感じるため
                    </label>
    
                    <!-- その他 -->
                    <div class="flex items-center">
                        <input type="checkbox" id="otherCheckbox" class="w-5 h-5 mr-2">
                        <label for="otherCheckbox" class="mr-2 cursor-pointer">その他</label>
                        <input type="text" id="otherText" class="border-b border-gray-400 focus:border-black outline-none px-2 w-48 bg-transparent text-gray-400" placeholder="別の理由を入力" disabled>
                    </div>
                </div>
            </div>
    
            <!-- 次へボタン -->
            <div class="mt-10">
                <button class="bg-blue-300 text-white text-lg font-bold px-10 py-3 rounded-full shadow-md hover:bg-blue-400 transition transform hover:scale-105 active:scale-95">
                    次へ →
                </button>
            </div>
        </div>
    
        <script>
            document.getElementById('otherCheckbox').addEventListener('change', function() {
                const otherText = document.getElementById('otherText');
                otherText.disabled = !this.checked;
                otherText.classList.toggle('text-gray-900', this.checked);
                otherText.classList.toggle('text-gray-400', !this.checked);
            });
        </script>
</body>
</html>
