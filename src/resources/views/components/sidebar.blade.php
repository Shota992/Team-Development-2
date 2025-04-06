{{-- resources/views/sidebar.blade.php --}}
<aside class="w-56 h-screen bg-white shadow-lg fixed top-0 left-0 overflow-y-auto overflow-x-visible z-40">
    <!-- ヘッダー -->
    <div class="py-4 border-b text-center">
      <a href="{{ route('dashboard') }}" class="text-lg font-bold text-gray-700">get mild</a>
    </div>
  
    <!-- 会社・部署、通知＋ログアウト -->
    <div class="p-4 border-b bg-gray-100">
      <!-- 会社・部署情報 -->
      <div class="text-center text-gray-800 font-semibold pb-2">
        {{ auth()->user()->office->name ?? '会社名未設定' }}
      </div>
      <div class="text-center text-sm text-gray-600 pb-4">
        {{ auth()->user()->department->name ?? '部署名未設定' }}
      </div>
  
      <!-- 通知とログアウト -->
      <div class="flex items-center justify-center gap-8 relative">
        <!-- 通知ベルとポップアップ -->
        <div x-data="{ open: false }" class="relative">
          <!-- 通知ベル -->
          <button @click="open = !open" class="relative focus:outline-none">
            <img src="{{ asset('images/bellicon.png') }}" alt="Bell" class="w-6 h-6 cursor-pointer">
            @if ($notifications->where('read_at', null)->count() > 0)
              <!-- 赤いドット（ベルアイコンの左上に表示） -->
              <span class="absolute top-0 left-0 transform -translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full w-2 h-2"></span>
            @endif
          </button>
  
          <!-- 通知ポップアップ（サイドバー外にはみ出して表示される） -->
          <div x-show="open" @click.away="open = false" x-cloak
               class="absolute left-0 top-full mt-2 w-80 bg-white border border-gray-300 rounded-md shadow-lg z-50 transition-all duration-200 ease-out"
               style="max-height: 70vh; overflow-y: auto;">
            <div class="px-4 py-2 border-b text-gray-800 font-semibold flex justify-between items-center">
              通知
              <button @click="open = false" class="text-gray-400 hover:text-gray-600 text-sm">✕</button>
            </div>
            <ul class="max-h-80 overflow-y-auto text-sm text-gray-700 divide-y">
              @forelse ($notifications as $notification)
                <li class="p-3 hover:bg-gray-50 {{ is_null($notification->read_at) ? 'bg-blue-50' : '' }}">
                  <div class="flex justify-between items-center">
                    <div class="font-bold text-sm">{{ $notification->title }}</div>
                    @if (is_null($notification->read_at))
                      <span class="ml-2 inline-flex items-center px-2 py-0.5 text-xs font-medium text-white bg-green-500 rounded-full">
                        NEW
                      </span>
                    @endif
                  </div>
                  <div class="text-xs text-gray-500">{{ $notification->body }}</div>
                  <div class="text-right text-xs text-gray-400 mt-1">
                    {{ $notification->created_at->diffForHumans() }}
                  </div>
                  @if (is_null($notification->read_at))
                    <form action="{{ route('notifications.read', $notification) }}" method="POST" class="mt-2">
                      @csrf
                      <button type="submit" class="text-xs text-blue-600 hover:underline">既読にする</button>
                    </form>
                  @endif
                </li>
              @empty
                <li class="p-3 text-center text-gray-500 text-sm">通知はありません</li>
              @endforelse
            </ul>
          </div>
        </div>
  
        <!-- ログアウト -->
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" class="focus:outline-none">
            <img src="{{ asset('images/logouticon.png') }}" alt="Logout" class="w-6 h-6">
          </button>
        </form>
      </div>
    </div>
  
    <!-- ナビゲーションメニュー -->
    <nav>
      <ul>
        <li class="hover:bg-[#e6f8fe] p-4 rounded">
          <a href="{{ route('dashboard') }}" class="flex items-center space-x-2 text-gray-700">
            <img src="{{ asset('images/dashboardicon.png') }}" class="w-6 h-6" alt="Dashboard">
            <span>ダッシュボード</span>
          </a>
        </li>
        <li class="hover:bg-[#e6f8fe] p-4 rounded">
          <a href="{{ route('items.index') }}" class="flex items-center space-x-2 text-gray-700">
            <img src="{{ asset('images/item-detail icon.png') }}" class="w-6 h-6" alt="Item Details">
            <span>項目別詳細</span>
          </a>
        </li>
        <li class="hover:bg-[#e6f8fe] p-4 rounded">
          <a href="{{ route('departments.index') }}" class="flex items-center space-x-2 text-gray-700">
            <img src="{{ asset('images/dept-compareicon.png') }}" class="w-6 h-6" alt="Department Compare">
            <span>部署別比較</span>
          </a>
        </li>
        <li class="hover:bg-[#e6f8fe] p-4 rounded">
          <details class="group cursor-pointer">
            <summary class="flex justify-between items-center text-gray-700">
              <div class="flex items-center space-x-2">
                <img src="{{ asset('images/policy-createicon.png') }}" class="w-6 h-6" alt="Policy Create">
                <span>施策立案</span>
              </div>
              <span class="transition-transform group-open:rotate-90">▶</span>
            </summary>
            <ul class="pl-6 mt-4 space-y-1">
              <li><a href="{{ route('create-policy') }}" class="text-gray-600 hover:text-blue-600">施策作成</a></li>
              <li><a href="{{ route('chat.index') }}" class="text-gray-600 hover:text-blue-600">AIメンター</a></li>
              <li><a href="{{ route('mindmap.index') }}" class="text-gray-600 hover:text-blue-600">施策深掘り</a></li>
            </ul>
          </details>
        </li>
        <li class="hover:bg-[#e6f8fe] p-4 rounded">
          @php
            $totalTaskBadgeCount = ($executingTasksCount ?? 0) + ($pendingEvaluationMeasuresCount ?? 0);
          @endphp
          <details class="group cursor-pointer">
            <summary class="flex justify-between items-center text-gray-700">
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
            <ul class="pl-6 mt-4 space-y-1">
              <li class="flex justify-between items-center">
                <a href="{{ route('measures.index') }}" class="text-gray-600 hover:text-blue-600">実行中施策一覧</a>
                @if($executingTasksCount > 0)
                  <span class="bg-blue-500 text-white text-xs font-bold rounded-full px-2 py-0.5">
                    {{ $executingTasksCount }}
                  </span>
                @endif
              </li>
              <li class="flex justify-between items-center">
                <a href="{{ route('measure.no-evaluation') }}" class="text-gray-600 hover:text-blue-600">評価/改善未対応施策一覧</a>
                @if($pendingEvaluationMeasuresCount > 0)
                  <span class="bg-blue-500 text-white text-xs font-bold rounded-full px-2 py-0.5">
                    {{ $pendingEvaluationMeasuresCount }}
                  </span>
                @endif
              </li>
              <li class="flex justify-between items-center">
                <a href="{{ route('measures.evaluation-list') }}" class="text-gray-600 hover:text-blue-600">評価/改善済み施策一覧</a>
              </li>
            </ul>
          </details>
        </li>
        <li class="hover:bg-[#e6f8fe] p-4 rounded">
          <details class="group cursor-pointer">
            <summary class="flex justify-between items-center text-gray-700">
              <div class="flex items-center space-x-2">
                <img src="{{ asset('images/survey-configiconicon.png') }}" class="w-6 h-6" alt="Survey Config">
                <span>アンケート設定</span>
              </div>
              <span class="transition-transform group-open:rotate-90">▶</span>
            </summary>
            <ul class="pl-6 mt-4 space-y-1">
              <li><a href="{{ route('survey.create') }}" class="text-gray-600 hover:text-blue-600">アンケート作成</a></li>
              <li><a href="{{ route('survey.list') }}" class="text-gray-600 hover:text-blue-600">アンケート一覧</a></li>
            </ul>
          </details>
        </li>
        <li class="hover:bg-[#e6f8fe] p-4 rounded">
          <details class="group cursor-pointer">
            <summary class="flex justify-between items-center text-gray-700">
              <div class="flex items-center space-x-2">
                <img src="{{ asset('images/settingsicon.png') }}" class="w-6 h-6" alt="Settings">
                <span>設定</span>
              </div>
              <span class="transition-transform group-open:rotate-90">▶</span>
            </summary>
            <ul class="pl-6 mt-4 space-y-1">
              <li><a href="{{ route('survey_questions.index') }}" class="text-gray-600 hover:text-blue-600">項目設定</a></li>
            </ul>
            <ul class="pl-6 mt-4 space-y-1">
              <li><a href="{{ route('setting.employee-list') }}" class="text-gray-600 hover:text-blue-600">従業員一覧</a></li>
            </ul>
            <ul class="pl-6 mt-4 space-y-1">
              <li><a href="{{ route('employee.create') }}" class="text-gray-600 hover:text-blue-600">従業員登録</a></li>
            </ul>
          </details>
        </li>
      </ul>
    </nav>
  </aside>
  