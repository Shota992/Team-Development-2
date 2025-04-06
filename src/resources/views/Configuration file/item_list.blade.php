<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>項目一覧 - Kompass</title>
    <style>
        th, td {
        white-space: normal;
        }
        /* スクロールバー表示調整（オプション） */
        .scrollbar-visible::-webkit-scrollbar {
        height: 12px;
        }
        .scrollbar-visible::-webkit-scrollbar-thumb {
        background-color: #ccc;
        border-radius: 6px;
        }
    </style>
</head>
<body>
    @include('components.sidebar')
    <div class="ml-64">
        <div>
            <div class="flex justify-between p-5 pt-8">
                <div class="flex">
                    <figure>
                        <img src="{{ asset('images/title_logo.png') }}" alt="" />
                    </figure>
                    <p class="ml-2 text-2xl font-bold">項目一覧</p>
                </div>
            </div>
        </div>
        <!-- 独立型タブ -->
        <div class="w-fit bg-white rounded-lg shadow-md">
            <div class="flex justify-center rounded-lg overflow-hidden">
                <button class="tab-btn px-8 py-2 font-semibold text-gray-700 transition-colors bg-[#E0F4FF]" data-tab="tab1">
                    常設項目
                </button>
                <button class="tab-btn px-8 py-2 font-semibold text-gray-700 transition-colors bg-white hover:bg-gray-100" data-tab="tab2">
                    追加項目
                </button>
            </div>
        </div>
        <!-- コンテンツ -->
        <div class="mt-4">
            <!-- 常設項目 -->
            <div id="tab1" class="tab-content">
                <!-- 表 -->
                <div class="rounded shadow bg-white max-w-[calc(100vw-3rem)] mt-8 mr-8 scrollbar-visible" style="scrollbar-gutter: stable;">
                    <table class="table-auto w-full border-separate border-spacing-0 border border-gray-300">
                        <thead class="bg-gray-200 text-gray-700">
                            <tr>
                                <th class="w-[90px] px-2 py-2 border border-gray-300 sticky left-0 bg-gray-200 z-20 whitespace-nowrap">アイコン</th>
                                <th class="w-[150px] px-4 py-2 border border-gray-300 sticky left-[90px] bg-gray-200 z-20">項目名</th>
                                <th class="w-[480px] px-6 py-2 border border-gray-300">詳細説明</th>
                                <th class="w-[450px] px-6 py-2 border border-gray-300">質問内容(アンケート)</th>
                                <th class="w-[300px] px-4 py-2 border border-gray-300">回答の理由(アンケート)</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-800 bg-white">
                        <!-- Row 1 -->
                            <tr>
                                <td class="w-[90px] px-2 py-3 border border-gray-300 sticky left-0 bg-white z-10 text-center whitespace-nowrap">
                                    <img src="https://via.placeholder.com/55" alt="アイコン1" class="w-[55px] mx-auto">
                                </td>
                                <td class="w-[150px] px-4 py-3 border border-gray-300 sticky left-[90px] bg-white z-10 text-center">項目A</td>
                                <td class="w-[480px] px-6 py-3 border border-gray-300">
                                    この説明文は50〜70文字程度で構成されています。長すぎる場合は自動的に折り返されます。
                                </td>
                                <td class="w-[450px] px-6 py-3 border border-gray-300">
                                    従業員が日々の業務を行う中で、課題に感じていることはありますか？
                                </td>
                                <td class="w-[300px] px-4 py-3 border border-gray-300">
                                    <details class="cursor-pointer">
                                    <summary class="text-blue-500 hover:underline">詳細を見る</summary>
                                    <p class="pt-2">この内容は折り返しも可能です。</p>
                                    </details>
                                </td>
                            </tr>
                            <!-- Row 2 -->
                            <tr>
                                <td class="w-[90px] px-2 py-3 border border-gray-300 sticky left-0 bg-white z-10 text-center whitespace-nowrap">
                                    <img src="https://via.placeholder.com/55" alt="アイコン2" class="w-[55px] mx-auto">
                                </td>
                                <td class="w-[150px] px-4 py-3 border border-gray-300 sticky left-[90px] bg-white z-10 text-center">項目B</td>
                                <td class="w-[480px] px-6 py-3 border border-gray-300">
                                    こちらの説明文も同様に自動で折り返され、内容が長くなっても崩れません。
                                </td>
                                <td class="w-[450px] px-6 py-3 border border-gray-300">
                                    現在の評価制度に対する満足度はどの程度ですか？改善点があれば教えてください。
                                </td>
                                <td class="w-[300px] px-4 py-3 border border-gray-300">
                                    <details class="cursor-pointer">
                                        <summary class="text-blue-500 hover:underline">詳細を見る</summary>
                                        <p class="pt-2">この内容は折り返しも可能です。</p>
                                    </details>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- 追加項目 -->
            <div id="tab2" class="tab-content hidden">
                <div class="rounded shadow bg-white max-w-[calc(100vw-3rem)] mt-8 mr-8 scrollbar-visible" style="scrollbar-gutter: stable;">
                    <table class="table-auto w-full border-separate border-spacing-0 border border-gray-300">
                        <thead class="bg-gray-200 text-gray-700">
                            <tr>
                                <th class="w-[90px] px-2 py-2 border border-gray-300 sticky left-0 bg-gray-200 z-20 whitespace-nowrap">アイコン</th>
                                <th class="w-[150px] px-4 py-2 border border-gray-300 sticky left-[90px] bg-gray-200 z-20">項目名</th>
                                <th class="w-[480px] px-6 py-2 border border-gray-300">詳細説明</th>
                                <th class="w-[450px] px-6 py-2 border border-gray-300">質問内容(アンケート)</th>
                                <th class="w-[300px] px-4 py-2 border border-gray-300">回答の理由(アンケート)</th>
                                <th class="w-[300px] px-4 py-2 border border-gray-300">アクション</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-800 bg-white">
                        <!-- Row 1 -->
                            <tr>
                                <td class="w-[90px] px-2 py-3 border border-gray-300 sticky left-0 bg-white z-10 text-center whitespace-nowrap">
                                    <img src="https://via.placeholder.com/55" alt="アイコン1" class="w-[55px] mx-auto">
                                </td>
                                <td class="w-[150px] px-4 py-3 border border-gray-300 sticky left-[90px] bg-white z-10 text-center">項目A</td>
                                <td class="w-[480px] px-6 py-3 border border-gray-300">
                                    この説明文は50〜70文字程度で構成されています。長すぎる場合は自動的に折り返されます。
                                </td>
                                <td class="w-[450px] px-6 py-3 border border-gray-300">
                                    従業員が日々の業務を行う中で、課題に感じていることはありますか？
                                </td>
                                <td class="w-[300px] px-4 py-3 border border-gray-300">
                                    <details class="cursor-pointer">
                                        <summary class="text-blue-500 hover:underline">詳細を見る</summary>
                                        <p class="pt-2">この内容は折り返しも可能です。</p>
                                    </details>
                                </td>
                                <td class="px-4 py-3 border border-gray-300">
                                    <div class="flex gap-2">
                                        <!-- 編集するボタン -->
                                        <button class="px-5 py-3 rounded-[15px] text-[#00A6FF] bg-[#00A6FF1A] hover:bg-[#00A6FF33] transition">
                                        編集する
                                        </button>
                                        <!-- 削除するボタン -->
                                        <button class="px-5 py-3 rounded-[15px] text-[#FF7676] bg-[#FF76761A] hover:bg-[#FF767633] transition">
                                        削除する
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="flex justify-center mt-8">
                    <button class="w-[360px] py-3 text-white text-center bg-[#86D4FE] hover:bg-[#5EC6FD] rounded-[50px] shadow-md transition">
                    項目を追加する
                    </button>
                </div>
            </div>
        </div>
    </div>


    <script>
        const tabs = document.querySelectorAll('.tab-btn');
        const contents = document.querySelectorAll('.tab-content');

        tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            tabs.forEach(t => {
            t.classList.remove('bg-[#E0F4FF]');
            t.classList.add('bg-white', 'hover:bg-gray-100');
            });

            tab.classList.remove('bg-white', 'hover:bg-gray-100');
            tab.classList.add('bg-[#E0F4FF]');

            contents.forEach(content => content.classList.add('hidden'));
            document.getElementById(tab.dataset.tab).classList.remove('hidden');
        });
        });
    </script>
</body>
</html>
