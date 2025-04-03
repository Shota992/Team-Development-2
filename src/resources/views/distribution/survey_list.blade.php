@extends('layouts.app')

@section('content')
<div class="p-8 bg-[#F7F8FA]">
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

    <div class="bg-[#FEFEFE] shadow-md shadow-black/25 rounded p-6">
        <div class="overflow-x-auto border border-gray-300 rounded">
            <table class="min-w-full border-collapse text-sm text-center">
                <thead class="bg-white border-b border-gray-300 text-gray-700">
                    <tr>
                        <th class="px-4 py-2 border-b">タイトル</th>
                        <th class="px-4 py-2 border-b">部署</th>
                        <th class="px-4 py-2 border-b">ステータス</th>
                        <th class="px-4 py-2 border-b">回答数（回答率）</th>
                        <th class="px-4 py-2 border-b">配信日</th>
                        <th class="px-4 py-2 border-b">提出期限</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($surveys as $survey)
                        @php
                            $delivered = $survey->responseUsers->count();
                            $answered = $survey->responses->pluck('user_id')->unique()->count();
                            $rate = $delivered > 0 ? round(($answered / $delivered) * 100) : 0;

                            $status = '未配信';
                            $statusClass = 'bg-gray-200 text-gray-600';
                            if ($survey->is_active) {
                                if ($survey->end_date && \Carbon\Carbon::now()->gt($survey->end_date)) {
                                    $status = '解答終了';
                                    $statusClass = 'bg-red-100 text-red-500';
                                } else {
                                    $status = '配信中';
                                    $statusClass = 'bg-blue-100 text-blue-500';
                                }
                            }
                        @endphp
                        <tr class="border-b border-gray-200">
                            <td class="px-4 py-2">{{ $survey->name }}</td>
                            <td class="px-4 py-2">{{ $survey->department->name ?? '-' }}</td>
                            <td class="px-4 py-2">
                                <span class="px-3 py-1 rounded font-semibold {{ $statusClass }}">{{ $status }}</span>
                            </td>
                            <td class="px-4 py-2">
                                @if($delivered > 0)
                                    {{ $answered }}/{{ $delivered }}（{{ $rate }}%）
                                @else
                                    ―
                                @endif
                            </td>
                            <td class="px-4 py-2">{{ \Carbon\Carbon::parse($survey->start_date)->format('Y/m/d') }}</td>
                            <td class="px-4 py-2">
                                {{ $survey->end_date ? \Carbon\Carbon::parse($survey->end_date)->format('Y/m/d') : '―' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-4 text-gray-500">アンケートはまだありません。</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
