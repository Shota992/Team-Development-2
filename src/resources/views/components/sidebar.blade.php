<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sidebar</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <aside class="w-56 h-screen bg-white shadow-lg fixed overflow-y-auto">
        <div class="py-4 border-b text-center">
            <a class="text-lg font-bold text-gray-700">get mild</a>
        </div>
        <div class="p-4 border-b bg-gray-100">
            <div class="text-gray-800 font-semibold text-center px-2 pb-2">オリエンタルランド株式会社</div>
            <div class="text-gray-600 text-sm text-center px-2 pb-4">経営戦略本部</div>
            <div class="flex items-center justify-center gap-8">
                <img src="./assets/img/ベルicon.png" alt="Bell" class="w-6 h-6">
                <img src="./assets/img/ログアウトicon.png" alt="Logout" class="w-6 h-6">
            </div>
        </div>
        <nav>
            <ul>
                <li class="hover:bg-[#e6f8fe] p-4 rounded">
                    <a href="#" class="flex items-center space-x-2 text-gray-700">
                        <img src="./assets/img/ダッシュボードicon.png" class="w-6 h-6" alt="">
                        <span>ダッシュボード</span>
                    </a>
                </li>
                <li class="hover:bg-[#e6f8fe] p-4 rounded">
                    <a href="#" class="flex items-center space-x-2 text-gray-700">
                        <img src="./assets/img/項目別詳細icon.png" class="w-6 h-6" alt="">
                        <span>項目別詳細</span>
                    </a>
                </li>
                <li class="hover:bg-[#e6f8fe] p-4 rounded">
                    <a href="#" class="flex items-center space-x-2 text-gray-700">
                        <img src="./assets/img/部署別比較icon.png" class="w-6 h-6" alt="">
                        <span>部署別比較</span>
                    </a>
                </li>
                <li class="hover:bg-[#e6f8fe] p-4 rounded">
                    <details class="group cursor-pointer">
                        <summary class="flex items-center space-x-2 text-gray-700">
                            <img src="./assets/img/施策立案icon.png" class="w-6 h-6" alt="">
                            <span class="flex-1">施策立案</span>
                            <span class="ml-auto transition-transform group-open:rotate-90">▶</span>
                        </summary>
                        <ul class="pl-6 mt-4 space-y-1">
                            <li><a href="#" class="text-gray-600 hover:text-blue-600">施策作成</a></li>
                            <li><a href="#" class="text-gray-600 hover:text-blue-600">AIメンター</a></li>
                            <li><a href="#" class="text-gray-600 hover:text-blue-600">施策深掘り</a></li>
                        </ul>
                    </details>
                </li>
                <li class="hover:bg-[#e6f8fe] p-4 rounded">
                    <details class="group cursor-pointer">
                        <summary class="flex items-center space-x-2 text-gray-700">
                            <img src="./assets/img/施策一覧icon.png" class="w-6 h-6" alt="">
                            <span class="flex-1">施策一覧</span>
                            <span class="ml-auto transition-transform group-open:rotate-90">▶</span>
                        </summary>
                        <ul class="pl-6 mt-4 space-y-1">
                            <li><a href="#" class="text-gray-600 hover:text-blue-600">実行タスク一覧</a></li>
                            <li><a href="#" class="text-gray-600 hover:text-blue-600">評価/改善未対応タスク一覧</a></li>
                            <li><a href="#" class="text-gray-600 hover:text-blue-600">評価/改善済みタスク一覧</a></li>
                        </ul>
                    </details>
                </li>
                <li class="hover:bg-[#e6f8fe] p-4 rounded">
                    <details class="group cursor-pointer">
                        <summary class="flex items-center space-x-2 text-gray-700">
                            <img src="./assets/img/アンケート設定icon.png" class="w-6 h-6" alt="">
                            <span class="flex-1">アンケート設定</span>
                            <span class="ml-auto transition-transform group-open:rotate-90">▶</span>
                        </summary>
                        <ul class="pl-6 mt-4 space-y-1">
                            <li><a href="#" class="text-gray-600 hover:text-blue-600">アンケート作成</a></li>
                            <li><a href="#" class="text-gray-600 hover:text-blue-600">アンケート一覧</a></li>
                        </ul>
                    </details>
                </li>
                <li class="hover:bg-[#e6f8fe] p-4 rounded">
                    <details class="group cursor-pointer">
                        <summary class="flex items-center space-x-2 text-gray-700">
                            <img src="./assets/img/設定icon.png" class="w-6 h-6" alt="">
                            <span class="flex-1">設定</span>
                            <span class="ml-auto transition-transform group-open:rotate-90">▶</span>
                        </summary>
                        <ul class="pl-6 mt-4 space-y-1">
                            <li><a href="#" class="text-gray-600 hover:text-blue-600">項目設定</a></li>
                        </ul>
                    </details>
                </li>
            </ul>
        </nav>
    </aside>
</body>
</html>