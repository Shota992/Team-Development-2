@extends('layouts.app')

@section('content')
@include('components.sidebar')
<div class="p-8 bg-[#F7F8FA] ml-56">
    <!-- タイトル -->
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-2xl font-bold">従業員一覧</h2>

        <!-- 表示件数だけ独立フォーム -->
        <form method="GET">
            <input type="hidden" name="keyword" value="{{ request('keyword') }}">
            <input type="hidden" name="department_id" value="{{ request('department_id') }}">
            <input type="hidden" name="position_id" value="{{ request('position_id') }}">
            <div class="flex items-center space-x-2">
                <span>表示件数</span>
                <select name="per_page" onchange="this.form.submit()" class="border rounded pl-4 pr-8 py-1">
                    <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10件</option>
                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50件</option>
                    <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100件</option>
                </select>
            </div>
        </form>
    </div>

    <!-- 検索フォーム -->
    <form method="GET" class="mb-4 flex flex-wrap items-center space-x-4">
        <!-- 名前検索 -->
        <input
            type="text"
            name="keyword"
            placeholder="名前で検索"
            class="border rounded px-3 py-1 w-64"
            value="{{ request('keyword') }}"
        />

        <!-- 部署 -->
        <select name="department_id" class="border border-black bg-[#F7F8FA] pl-4 pr-8 py-1 rounded appearance-none text-sm font-semibold text-black">
            <option value="">全ての部署</option>
            @foreach ($departments as $department)
                <option value="{{ $department->id }}" {{ request('department_id') == $department->id ? 'selected' : '' }}>
                    {{ $department->name }}
                </option>
            @endforeach
        </select>

        <!-- 役職 -->
        <select name="position_id" class="border border-black bg-[#F7F8FA] pl-4 pr-8 py-1 rounded appearance-none text-sm font-semibold text-black">
            <option value="">全ての役職</option>
            @foreach ($positions as $position)
                <option value="{{ $position->id }}" {{ request('position_id') == $position->id ? 'selected' : '' }}>
                    {{ $position->name }}
                </option>
            @endforeach
        </select>

        <!-- 検索ボタン -->
        <button type="submit" class="px-4 py-1 bg-blue-500 text-white rounded hover:bg-blue-600">検索</button>
    </form>

    <!-- 削除メッセージ -->
    @if(session('success'))
        <div class="mb-4 text-green-600 font-semibold">
            {{ session('success') }}
        </div>
    @endif

    <!-- フィルター＆テーブル枠 -->
    <div class="bg-[#FEFEFE] shadow-md shadow-black/25 rounded p-6">
        <!-- ページネーション風ボタン -->
        <div class="mb-4 flex items-center space-x-4">
            <div class="flex items-center border rounded">
                <a href="{{ $employees->appends(request()->query())->previousPageUrl() }}" class="px-2 py-1 text-gray-600 {{ $employees->onFirstPage() ? 'pointer-events-none opacity-50' : '' }}">‹</a>
                <span class="bg-blue-300 text-white px-4 py-1">
                    {{ $employees->currentPage() }}
                </span>
                <a href="{{ $employees->appends(request()->query())->nextPageUrl() }}" class="px-2 py-1 text-gray-600 {{ $employees->hasMorePages() ? '' : 'pointer-events-none opacity-50' }}">›</a>
            </div>
            <span>
                {{ $employees->firstItem() }}-{{ $employees->lastItem() }}/{{ $employees->total() }}件
            </span>
        </div>

        <!-- テーブル -->
        <div class="overflow-x-auto border border-gray-300 rounded">
            <table class="min-w-full border-collapse">
                <thead class="bg-white border-b border-gray-300">
                    <tr class="text-center text-sm text-gray-700">
                        <th class="px-4 py-2 border-b border-gray-300">氏名</th>
                        <th class="px-4 py-2 border-b border-gray-300">性別</th>
                        <th class="px-4 py-2 border-b border-gray-300">生年月日</th>
                        <th class="px-4 py-2 border-b border-gray-300">部署</th>
                        <th class="px-4 py-2 border-b border-gray-300">役職</th>
                        <th class="px-4 py-2 border-b border-gray-300">管理者権限</th>
                        <th class="px-4 py-2 border-b border-gray-300">操作</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($employees as $employee)
                    <tr class="text-center text-sm border-b border-gray-200">
                        <td class="px-4 py-2">{{ $employee->name }}</td>
                        <td class="px-4 py-2">
                            @switch($employee->gender)
                                @case(1) 男性 @break
                                @case(2) 女性 @break
                                @case(3) その他 @break
                                @default -
                            @endswitch
                        </td>
                        <td class="px-4 py-2">{{ \Carbon\Carbon::parse($employee->birthday)->format('Y/m/d') }}</td>
                        <td class="px-4 py-2">{{ $employee->department->name ?? '-' }}</td>
                        <td class="px-4 py-2">{{ $employee->position->name ?? '-' }}</td>
                        <td class="px-4 py-2">{{ $employee->administrator ? 'あり' : 'なし' }}</td>
                        <td class="px-4 py-2">
                            <form method="POST" action="{{ route('employee.delete', $employee->id) }}" onsubmit="return confirm('本当に削除しますか？');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-600 text-white px-4 py-1 rounded hover:bg-red-700">削除</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
