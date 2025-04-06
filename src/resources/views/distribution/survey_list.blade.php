@extends('layouts.app')

@section('content')
@include('components.sidebar')

<div class="p-8 bg-[#F7F8FA] ml-64">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-2xl font-bold">アンケート一覧</h2>
        <form method="GET" class="flex items-center space-x-2">
            <span class="text-sm">部署を選択</span>
            <select name="department_id" class="border px-4 py-2 rounded" onchange="this.form.submit()">
                <option value="">全社</option>
                @foreach ($departments as $department)
                    <option value="{{ $department->id }}" {{ ($selectedDepartmentId == $department->id) ? 'selected' : '' }}>
                        {{ $department->name }}
                    </option>
                @endforeach
            </select>
        </form>
    </div>

    <div class="bg-[#FEFEFE] shadow-md shadow-black/25 rounded p-0">
        <div class="overflow-x-auto border border-[#C4C4C4] rounded">
            <table class="min-w-full border-collapse text-sm text-center">
                <thead class="bg-[#EBEBEB] text-gray-700">
                    <tr>
                        <th class="px-4 py-2 border border-[#C4C4C4]">タイトル</th>
                        <th class="px-4 py-2 border border-[#C4C4C4]">部署</th>
                        <th class="px-4 py-2 border border-[#C4C4C4]">ステータス</th>
                        <th class="px-4 py-2 border border-[#C4C4C4]">回答数（回答率）</th>
                        <th class="px-4 py-2 border border-[#C4C4C4]">配信日時</th>
                        <th class="px-4 py-2 border border-[#C4C4C4]">期限日時</th>
                        <th class="px-4 py-2 border border-[#C4C4C4]">操作</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($surveys as $survey)
                        @php
                            $answered = $responseCounts[$survey->id] ?? 0;
                            $totalUsers = $departmentUserCounts[$survey->department_id] ?? 0;
                            $rate = $totalUsers > 0 ? round(($answered / $totalUsers) * 100) : 0;

                            $status = '未配信';
                            $statusClass = 'bg-[#E6E6E6] text-black';
                            $now = \Carbon\Carbon::now();

                            if ($survey->is_active && $survey->start_date <= $now) {
                                if ($survey->end_date && $now->gt($survey->end_date)) {
                                    $status = '回答終了';
                                    $statusClass = 'bg-red-100 text-red-500';
                                } else {
                                    $status = '配信中';
                                    $statusClass = 'bg-blue-100 text-blue-500';
                                }
                            }
                        @endphp
                        <tr class="border-b border-[#C4C4C4]">
                            <td class="px-4 py-2 border border-[#C4C4C4]">{{ $survey->name }}</td>
                            <td class="px-4 py-2 border border-[#C4C4C4]">{{ $survey->department->name ?? '-' }}</td>
                            <td class="px-4 py-2 border border-[#C4C4C4]">
                                <span class="inline-block w-24 px-3 py-1 rounded font-semibold {{ $statusClass }}">{{ $status }}</span>
                            </td>
                            <td class="px-4 py-2 border border-[#C4C4C4]">
                                @if($totalUsers > 0)
                                    {{ $answered }}/{{ $totalUsers }}（{{ $rate }}%）
                                @else
                                    ―
                                @endif
                            </td>
                            <td class="px-4 py-2 border border-[#C4C4C4]">
                                {{ $survey->start_date ? $survey->start_date->format('Y/m/d H:i') : '―' }}
                            </td>
                            <td class="px-4 py-2 border border-[#C4C4C4]">
                                {{ $survey->end_date ? $survey->end_date->format('Y/m/d H:i') : '―' }}
                            </td>
                            <td class="px-4 py-2 border border-[#C4C4C4]">
                                @if ($status === '未配信' || $status === '配信中')
                                    <form method="POST" action="{{ route('survey.end', $survey->id) }}">
                                        @csrf
                                        <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 transition">回答終了</button>
                                    </form>
                                @else
                                    ―
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-4 text-gray-500">アンケートはまだありません。</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
