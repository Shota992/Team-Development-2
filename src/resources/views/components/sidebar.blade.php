<aside class="w-56 h-screen bg-white shadow-lg fixed overflow-y-auto overflow-x-hidden z-40">
    <div class="py-3 border-b text-center">
        <a href="{{ route('dashboard') }}" class="text-lg font-bold text-gray-700"><img src="{{ asset('images/Kompasslogo.jpeg')}}" alt="Kompass"></a>
    </div>

    <div class="p-4 border-b bg-gray-100">
        {{-- 会社・部署 --}}
        <div class="text-center text-gray-800 font-semibold pb-2">
            {{ auth()->user()->office->name ?? '会社名未設定' }}
        </div>
        <div class="text-center text-sm text-gray-600 pb-4">
            {{ auth()->user()->department->name ?? '部署名未設定' }}
        </div>

        {{-- 通知＋ログアウト --}}
        <div class="flex items-center justify-center gap-8 relative">
            <!-- 通知ポップアップ -->
            <div x-data="{ open: false }" class="relative w-auto min-w-[48px]">
                <!-- 通知ベル（クリックでトグル） -->
                <img src="{{ asset('images/bellicon.png') }}" alt="Bell" class="w-6 h-6 cursor-pointer" @click="open = !open" />

                <!-- 未読通知がある場合に赤いドットを表示 -->
                @if ($notifications->where('read_at', null)->count() > 0)
                    <span class="absolute top-0 right-0 transform translate-x-1/2 -translate-y-1/2 block bg-red-600 rounded-full" style="width: 8px; height: 8px;"></span>
                @endif

                <!-- 通知ポップアップ本体 -->
                <div x-show="open" @click.away="open = false"
                class="fixed left-64 top-20 bg-white border border-gray-300 rounded-md shadow-lg z-50 transition duration-200"
                style="width: 400px;">
               <div class="px-4 py-2 border-b text-gray-800 font-semibold flex justify-between items-center">
                   通知
                   <button @click="open = false" class="text-gray-400 hover:text-gray-600 text-sm">✕</button>
               </div>
               <ul class="max-h-80 overflow-y-auto text-sm text-gray-700 divide-y">
                        @forelse ($notifications as $notification)
                            <li class="p-3 hover:bg-gray-50 {{ is_null($notification->read_at) ? 'bg-blue-50' : '' }}">
                                <div class="flex justify-between items-center">
                                    <div class="font-bold text-sm mb-1">{{ $notification->title }}</div>
                                    @if (is_null($notification->read_at))
                                        <!-- 未読の場合にNEWインジケーターを表示 -->
                                        <span class="ml-2 inline-flex items-center px-2 py-0.5 text-xs font-medium leading-none text-white bg-green-500 rounded-full">
                                            NEW
                                        </span>
                                    @endif
                                </div>
                                <div class="text-xs text-gray-500">{{ $notification->body }}</div>
                                <div class="text-right text-xs text-gray-400 mt-1">
                                    {{ $notification->created_at->diffForHumans() }}
                                </div>
                                @if (is_null($notification->read_at))
                                    <!-- 未読の場合、既読にするボタンを表示 -->
                                    <form action="{{ route('notifications.read', $notification) }}" method="POST" class="mt-2">
                                        @csrf
                                        <button type="submit" class="text-xs text-blue-600 hover:underline">
                                            既読にする
                                        </button>
                                    </form>
                                @endif
                            </li>
                        @empty
                            <li class="p-3 text-center text-gray-500 text-sm">通知はありません</li>
                        @endforelse
                    </ul>
                </div>
            </div>

            {{-- ログアウト --}}
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="focus:outline-none">
                    <img src="{{ asset('images/logouticon.png') }}" alt="Logout" class="w-6 h-6" />
                </button>
            </form>
        </div>
    </div>

    <!-- ナビゲーションメニュー -->
    <nav>
        <ul>
            <li class="hover:bg-chart-gray p-4 rounded {{ request()->routeIs('dashboard') ? 'bg-[#e6f8fe]'  : '' }}">
                <a href="{{ route('dashboard') }}" class="flex items-center space-x-2 text-gray-700">
                    <img src="{{ asset('images/dashboardicon.png') }}" class="w-6 h-6" alt="Dashboard">
                    <span>ダッシュボード</span>
                </a>
            </li>
            <li class="hover:bg-chart-gray p-4 rounded {{ request()->routeIs('items.index') ? 'bg-[#e6f8fe]' : '' }}">
                <a href="{{ route('items.index') }}" class="flex items-center space-x-2 text-gray-700">
                    <img src="{{ asset('images/item-detail icon.png') }}" class="w-6 h-6" alt="Item Details">
                    <span>項目別詳細</span>
                </a>
            </li>
            <li class="hover:bg-chart-gray p-4 rounded {{ request()->routeIs('departments.index') ? 'bg-[#e6f8fe]' : '' }}">
                <a href="{{ route('departments.index') }}" class="flex items-center space-x-2 text-gray-700">
                    <img src="{{ asset('images/dept-compareicon.png') }}" class="w-6 h-6" alt="Department Compare">
                    <span>部署別比較</span>
                </a>
            </li>
            <li class="hover:bg-chart-gray rounded {{ request()->routeIs('create-policy', 'chat.index', 'mindmap.index') ? 'bg-[#e6f8fe]' : '' }}">
                <details class="group cursor-pointer" {{ request()->routeIs('create-policy', 'chat.index', 'mindmap.index') ? 'open' : '' }}>
                    <summary class="flex justify-between items-center text-gray-700 p-4">
                        <div class="flex items-center space-x-2">
                            <img src="{{ asset('images/policy-createicon.png') }}" class="w-6 h-6" alt="Policy Create">
                            <span>施策立案</span>
                        </div>
                        <span class="transition-transform group-open:rotate-90">▶</span>
                    </summary>
                    <ul class="space-y-1 pb-2">
                        <li class="pl-6 hover:bg-chart-do-gray {{ request()->routeIs('create-policy') ? 'bg-[#cff2fe]' : '' }}"><a href="{{ route('create-policy') }}" class="block w-full text-gray-600">施策作成</a></li>
                        <li class="pl-6 hover:bg-chart-do-gray {{ request()->routeIs('chat.index') ? 'bg-[#cff2fe]' : '' }}"><a href="{{ route('chat.index') }}" class="block w-full text-gray-600">AIメンター</a></li>
                        <li class="pl-6 hover:bg-chart-do-gray {{ request()->routeIs('mindmap.index') ? 'bg-[#cff2fe]' : '' }}"><a href="{{ route('mindmap.index') }}" class="block w-full text-gray-600">施策深掘り</a></li>
                    </ul>
                </details>
            </li>
            <li class="hover:bg-chart-gray rounded {{ request()->routeIs('measures.index', 'measure.no-evaluation' , 'measures.evaluation-list') ? 'bg-[#e6f8fe]' : '' }}">
                @php
                    $totalTaskBadgeCount = ($executingTasksCount ?? 0) + ($pendingEvaluationMeasuresCount ?? 0);
                @endphp
                <details class="group cursor-pointer" {{ request()->routeIs('measures.index', 'measure.no-evaluation' , 'measures.evaluation-list') ? 'open' : '' }}>
                    <summary class="flex justify-between items-center text-gray-700 p-4">
                        <div class="flex items-center space-x-2">
                            <img src="{{ asset('images/policy-listicon.png') }}" class="w-6 h-6" alt="Policy List">
                            <span>施策一覧</span>
                            @if($totalTaskBadgeCount > 0)
                                <span class="ml-2 bg-blue-600 text-white text-xs font-bold rounded-full px-2 py-0.5">
                                    {{ $totalTaskBadgeCount }}
                                </span>
                            @endif
                        </div>
                        <span class="transition-transform group-open:rotate-90">▶</span>
                    </summary>
                    <ul class="space-y-1 pb-2">
                        <li class="pl-6 hover:bg-chart-do-gray {{ request()->routeIs('measures.index') ? 'bg-[#cff2fe]' : '' }} flex justify-between items-center">
                            <a href="{{ route('measures.index') }}" class="block w-full text-gray-600">実行施策一覧
                            @if($executingTasksCount > 0)
                                <span class="bg-blue-500 text-white text-xs font-bold rounded-full px-2 py-0.5">
                                    {{ $executingTasksCount }}
                                </span>
                            @endif
                            </a>
                        </li>
                        <li class="pl-6 hover:bg-chart-do-gray  {{ request()->routeIs('measure.no-evaluation') ? 'bg-[#cff2fe]' : '' }} flex justify-between items-center">
                            <a href="{{ route('measure.no-evaluation') }}" class="block w-full text-gray-600">評価/改善未対応施策一覧
                            @if($pendingEvaluationMeasuresCount > 0)
                                <span class="bg-blue-500 text-white text-xs font-bold rounded-full px-2 py-0.5">
                                    {{ $pendingEvaluationMeasuresCount }}
                                </span>
                            @endif
                            </a>
                        </li>
                        <li class="pl-6 hover:bg-chart-do-gray {{ request()->routeIs('measures.evaluation-list') ? 'bg-[#cff2fe]' : '' }} flex justify-between items-center">
                            <a href="{{ route('measures.evaluation-list') }}" class="block w-full text-gray-600">評価/改善済み施策一覧</a>
                        </li>
                    </ul>
                </details>
            </li>
            <li class="hover:bg-chart-gray rounded {{ request()->routeIs('survey.create', 'survey.list') ? 'bg-[#e6f8fe]' : '' }}">
                <details class="group cursor-pointer" {{ request()->routeIs('survey.create', 'survey.list') ? 'open' : '' }}>
                    <summary class="flex justify-between items-center text-gray-700 p-4">
                        <div class="flex items-center space-x-2">
                            <img src="{{ asset('images/survey-configiconicon.png') }}" class="w-6 h-6" alt="Survey Config">
                            <span>アンケート設定</span>
                        </div>
                        <span class="transition-transform group-open:rotate-90">▶</span>
                    </summary>
                    <ul class="space-y-1 pb-2">
                        <li class="pl-6 hover:bg-chart-do-gray {{ request()->routeIs('survey.create') ? 'bg-[#cff2fe]' : '' }}"><a href="{{ route('survey.create') }}" class="block w-full text-gray-600">アンケート作成</a></li>
                        <li class="pl-6 hover:bg-chart-do-gray {{ request()->routeIs('survey.list') ? 'bg-[#cff2fe]' : '' }}"><a href="{{ route('survey.list') }}" class="block w-full text-gray-600">アンケート一覧</a></li>
                    </ul>
                </details>
            </li>
            <li class="hover:bg-chart-gray rounded {{ request()->routeIs('survey_questions.index', 'setting.employee-list' , 'employee.create') ? 'bg-[#e6f8fe]' : '' }}">
                <details class="group cursor-pointer" {{ request()->routeIs('survey_questions.index', 'setting.employee-list' , 'employee.create') ? 'open' : '' }}>
                    <summary class="flex justify-between items-center text-gray-700 p-4">
                        <div class="flex items-center space-x-2">
                            <img src="{{ asset('images/settingsicon.png') }}" class="w-6 h-6" alt="Settings">
                            <span>設定</span>
                        </div>
                        <span class="transition-transform group-open:rotate-90">▶</span>
                    </summary>
                    <ul class="space-y-1 pb-2">
                        <li class="pl-6 hover:bg-chart-do-gray {{ request()->routeIs('survey_questions.index') ? 'bg-[#cff2fe]' : '' }}"><a href="{{ route('survey_questions.index') }}" class="block w-full text-gray-600">項目設定</a></li>
                        <li class="pl-6 hover:bg-chart-do-gray {{ request()->routeIs('setting.employee-list') ? 'bg-[#cff2fe]' : '' }}"><a href="{{ route('setting.employee-list') }}" class="block w-full text-gray-600">従業員一覧</a></li>
                        <li class="pl-6 hover:bg-chart-do-gray {{ request()->routeIs('employee.create') ? 'bg-[#cff2fe]' : '' }}"><a href="{{ route('employee.create') }}" class="block w-full text-gray-600">従業員登録</a></li>
                    </ul>
                    <ul class="pl-6 mt-4 space-y-1">
                </details>
            </li>
        </ul>
    </nav>
</aside>

<script>
document.addEventListener("toggle", function(event) {
  if (event.target.open) {
    // 同じ aside 内の全ての details 要素を取得し、
    // 開いているものがあれば閉じる
    document.querySelectorAll("aside details").forEach(function(detail) {
      if (detail !== event.target) {
        detail.removeAttribute("open");
      }
    });
  }
}, true);
</script>
