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
<body>
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
            新入社員研修/Slackの使い方について理解する
        </div>
        <div class="mt-6 bg-white text-black p-8 font-sans w-49/50">
            <!-- タイトル -->
            <h1 class="text-xl font-bold mb-4">●第1回の評価/改善 <span class="ml-4 text-gray-600">(2025/05/01)</span></h1>

            <!-- 施策の実行状況 -->
            <section class="mb-6">
                <div class="flex items-center justify-between mb-2">
                    <h2 class="text-lg font-bold border-b-4 border-custom-blue inline-block mb-2">施策の実行状況</h2>
                    <p class="text-sm text-gray-600 mt-1">◎: 計画以上　○: 計画通り　△: 一部実行できず　×: ほぼ実行できず　ー: 対象外</p>
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
                        <tr>
                            <td class="border px-4 py-2">ウェビナーに参加する</td>
                            <td class="border px-4 py-2">雫井 琴</td>
                            <td class="border px-4 py-2 text-center">◎</td>
                            <td class="border px-4 py-2">計画より早めに対処できた</td>
                        </tr>
                        <tr>
                            <td class="border px-4 py-2">Wiki機能について理解する</td>
                            <td class="border px-4 py-2">雫井 琴</td>
                            <td class="border px-4 py-2 text-center">○</td>
                            <td class="border px-4 py-2">計画通り実行できた</td>
                        </tr>
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
                        <ul class="list-disc pl-6">
                            <li>ああああああああああ</li>
                            <li>ああああああああああああああああ</li>
                        </ul>
                    </div>

                    <!-- PROBLEM -->
                    <div class="border border-red-300 p-4">
                        <h3 class="text-red-500 font-bold text-center text-2xl">PROBLEM</h3>
                        <p class="text-red-400 text-sm text-center mb-2">うまくいかなかったこと/発生した課題</p>
                        <ul class="list-disc pl-6">
                            <li>ああああああああああ</li>
                            <li>ああああああああああああああああ</li>
                        </ul>
                    </div>

                    <!-- TRY -->
                    <div class="border border-green-400 p-4">
                        <h3 class="text-green-500 font-bold text-center text-2xl">TRY</h3>
                        <p class="text-green-400 text-sm text-center mb-2">改善すべきこと/新たに実践すべきこと</p>
                        <ul class="list-disc pl-6">
                            <li>ああああああああああ</li>
                            <li>ああああああああああああああああ</li>
                        </ul>
                    </div>
                </div>
            </section>
        </div>
        <div class="mt-6 bg-white text-black p-8 font-sans w-49/50">
            <!-- タイトル -->
            <h1 class="text-xl font-bold mb-4">●第2回の評価/改善 <span class="ml-4 text-gray-600">(2025/05/01)</span></h1>

            <!-- 施策の実行状況 -->
            <section class="mb-6">
                <div class="flex items-center justify-between mb-2">
                    <h2 class="text-lg font-bold border-b-4 border-custom-blue inline-block mb-2">施策の実行状況</h2>
                    <p class="text-sm text-gray-600 mt-1">◎: 計画以上　○: 計画通り　△: 一部実行できず　×: ほぼ実行できず　ー: 対象外</p>
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
                        <tr>
                            <td class="border px-4 py-2">ウェビナーに参加する</td>
                            <td class="border px-4 py-2">雫井 琴</td>
                            <td class="border px-4 py-2 text-center">
                                <div class="inline-block relative w-18">
                                    <select class="appearance-none w-full bg-white border border-gray-400 py-0.5 pl-3 pr-8 rounded-full shadow focus:outline-none focus:ring-2 focus:ring-blue-300">
                                        <option value="" selected disabled></option>
                                        <option>◎</option>
                                        <option>◯</option>
                                        <option>△</option>
                                        <option>✕</option>
                                        <option>ー</option>
                                    </select>
                                </div>
                            </td>
                            <td class="border px-2 py-2">
                                <input type="text" class="appearance-none border-none focus:outline-none focus:ring-0 w-full" placeholder="こちらから入力します" />
                            </td>
                        </tr>
                        <tr>
                            <td class="border px-4 py-2">Wiki機能について理解する</td>
                            <td class="border px-4 py-2">雫井 琴</td>
                            <td class="border px-4 py-2 text-center">
                                <div class="inline-block relative w-18">
                                    <select class="appearance-none w-full bg-white border border-gray-400 py-0.5 pl-3 pr-8 rounded-full shadow focus:outline-none focus:ring-2 focus:ring-blue-300">
                                        <option value="" selected disabled></option>
                                        <option>◎</option>
                                        <option>◯</option>
                                        <option>△</option>
                                        <option>✕</option>
                                        <option>ー</option>
                                    </select>
                                </div>
                            </td>
                            <td class="border px-2 py-2">
                                <input type="text" class="appearance-none border-none focus:outline-none focus:ring-0 w-full" placeholder="入力してください（任意）" />
                            </td>
                        </tr>
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
                        <textarea
                            class="w-full border rounded p-2 focus:outline-none border-none focus:ring-0 resize-none"
                            rows="4"
                            placeholder="こちらから入力します"></textarea>
                    </div>

                    <!-- PROBLEM -->
                    <div class="border border-red-300 p-4">
                        <h3 class="text-red-500 font-bold text-center text-2xl">PROBLEM</h3>
                        <p class="text-red-400 text-sm text-center mb-2">うまくいかなかったこと/発生した課題</p>
                        <textarea
                            class="w-full border rounded p-2 focus:outline-none border-none focus:ring-0 resize-none"
                            rows="4"
                            placeholder="こちらから入力します"></textarea>
                    </div>

                    <!-- TRY -->
                    <div class="border border-green-400 p-4">
                        <h3 class="text-green-500 font-bold text-center text-2xl">TRY</h3>
                        <p class="text-green-400 text-sm text-center mb-2">改善すべきこと/新たに実践すべきこと</p>
                        <textarea
                            class="w-full border rounded p-2 focus:outline-none border-none focus:ring-0 resize-none"
                            rows="4"
                            placeholder="こちらから入力します"></textarea>
                    </div>
                </div>
            </section>

            <!-- 編集ボタン -->
            <div class="mt-8 flex justify-center">
                <button class="bg-button-blue hover:bg-custom-blue text-white px-12 py-2 rounded-full shadow font-bold">追加する</button>
            </div>
        </div>
        <div class="mt-8 mb-6 flex justify-center">
            <button class="bg-button-blue text-white px-12 py-2 rounded-full shadow text-xl font-bold">評価/改善未対応施策一覧へ</button>
        </div>
    </div>
</body
