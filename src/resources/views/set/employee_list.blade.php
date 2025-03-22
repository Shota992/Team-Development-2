@extends('layouts.app')

@section('content')
<div class="p-8 bg-[#F7F8FA]">
    <!-- タイトルと検索バー -->
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-2xl font-bold">従業員一覧</h2>
        <input type="text" placeholder="検索" class="border rounded px-3 py-1 w-64" />
    </div>

    <!-- フィルター＆テーブル枠 -->
    <div class="bg-[#FEFEFE] shadow-md shadow-black/25 rounded p-6">
        <!-- フィルター部分 -->
        <form method="GET" class="mb-4 flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="flex items-center border rounded">
                    <button class="px-2 py-1 text-gray-600" disabled>&lt;</button>
                    <span class="bg-blue-300 text-white px-3 py-1">1</span>
                    <button class="px-2 py-1 text-gray-600" disabled>&gt;</button>
                </div>
                <span>1-100/100件</span>
                <div class="flex items-center space-x-2">
                    <span>表示件数</span>
                    <select class="border rounded px-2 py-1">
                        <option>100件</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center space-x-2">
                <span class="text-sm">絞り込み</span>
                <!-- 部署 -->
                <select name="department_id" onchange="this.form.submit()" class="border border-black bg-[#F7F8FA] px-4 py-1 rounded appearance-none text-sm font-semibold text-black">
                    <option value="">全ての部署</option>
                    @foreach ($departments as $department)
                        <option value="{{ $department->id }}" {{ request('department_id') == $department->id ? 'selected' : '' }}>
                            {{ $department->name }}
                        </option>
                    @endforeach
                </select>
                <!-- 役職 -->
                <select name="position_id" onchange="this.form.submit()" class="border border-black bg-[#F7F8FA] px-4 py-1 rounded appearance-none text-sm font-semibold text-black">
                    <option value="">全ての役職</option>
                    @foreach ($positions as $position)
                        <option value="{{ $position->id }}" {{ request('position_id') == $position->id ? 'selected' : '' }}>
                            {{ $position->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </form>

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
                        <th class="px-4 py-2 border-b border-gray-300">操作</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($employees as $employee)
                    <tr class="text-center text-sm border-b border-gray-200">
                        <td class="px-4 py-2">{{ $employee->name }}</td>
                        <td class="px-4 py-2">{{ $employee->gender }}</td>
                        <td class="px-4 py-2">{{ \Carbon\Carbon::parse($employee->birthday)->format('Y/m/d') }}</td>
                        <td class="px-4 py-2">{{ $employee->department->name ?? '-' }}</td>
                        <td class="px-4 py-2">{{ $employee->position->name ?? '-' }}</td>
                        <td class="px-4 py-2">
                            <button class="bg-red-600 text-white px-4 py-1 rounded hover:bg-red-700">削除</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
