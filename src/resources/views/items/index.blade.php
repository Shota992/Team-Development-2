@extends('layouts.app')

@section('content')
@include('components.sidebar')

<div class="ml-64 px-6 py-8 space-y-10">
    {{-- ▼ 部署選択 --}}
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-800">項目別評価一覧</h2>
        <form method="GET" action="{{ route('items.index') }}">
            <div class="flex items-center space-x-2">
                <label for="departmentSelect" class="text-sm font-semibold">部署を選択:</label>
                <select name="department_id" id="departmentSelect" class="px-3 py-1 rounded border text-black" onchange="this.form.submit()">
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ $selectedDepartmentId == $dept->id ? 'selected' : '' }}>
                            {{ $dept->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </form>
    </div>

    {{-- ▼ 項目説明 --}}
    <div class="rounded border overflow-hidden">
        <div class="bg-gray-400 text-white px-4 py-2 flex items-center justify-between">
            <span class="font-semibold">項目</span>
            <div class="flex items-center space-x-2">
                <label class="text-sm">項目を選択</label>
                <select id="questionSelect" class="px-3 py-1 rounded border text-black w-80 text-center">
                    @foreach($questions as $question)
                        <option value="{{ $loop->index }}">{{ $question->title }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div id="questionDescription" class="bg-white p-5 flex items-start space-x-4 border-t">
            <img id="questionIcon" src="{{ asset('images/question.png') }}" class="w-10 h-10" alt="アイコン">
            <div>
                <h3 id="questionTitle" class="text-lg font-bold mb-1">{{ $questions[0]->title ?? '' }}</h3>
                <p id="questionText" class="text-sm text-gray-700">{{ $questions[0]->description ?? '' }}</p>
            </div>
        </div>
    </div>

    {{-- ▼ 折れ線グラフ --}}
    <div class="bg-white border rounded shadow p-4">
        <div class="flex items-center mb-2">
            <img id="chartIcon" src="{{ asset('images/' . $cards[0]['img']) }}" alt="グラフアイコン" class="w-8 h-8 mr-2">
            <h3 id="chartLabel" class="text-lg font-semibold">{{ $cards[0]['label'] }}</h3>
        </div>
        <canvas id="chartCanvas" height="120"></canvas>
    </div>

    {{-- ▼ 積み上げ棒グラフ --}}
    <div class="bg-white border rounded shadow p-4">
        <h3 class="text-lg font-semibold mb-2">満足度割合（過去6回）</h3>
        <canvas id="stackedChartCanvas" height="120"></canvas>
    </div>

    {{-- ▼ 原因の表 --}}
    <div class="bg-white border rounded shadow">
        <div class="bg-gray-400 text-white px-4 py-2 font-semibold">発生事象</div>
        <div class="overflow-x-auto">
            <table class="min-w-full table-auto border text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border px-3 py-2 text-left">#</th>
                        <th class="border px-3 py-2 text-left">項目</th>
                        @foreach($causeDates as $date)
                            <th class="border px-3 py-2">{{ $date }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody id="causeTableBody"></tbody>
            </table>
        </div>
    </div>

    {{-- ▼ コメント一覧 --}}
    <div class="bg-white border rounded shadow p-6">
        <h3 class="text-lg font-semibold mb-4">コメント</h3>
        <p class="text-sm text-gray-600 mb-4">
            （{{ $comments->total() }}件中{{ $comments->firstItem() }}〜{{ $comments->lastItem() }}件を表示）
        </p>
        <div class="space-y-4">
            @foreach($comments as $comment)
                <div class="border rounded-lg p-4 bg-gray-50">
                    <p class="text-xs text-right text-gray-500 mb-1">
                        アンケート日：{{ \Carbon\Carbon::parse($comment->survey->start_date)->format('Y/m/d') }}
                    </p>
                    <p class="text-sm text-gray-800 whitespace-pre-wrap">{{ $comment->free_message }}</p>
                </div>
            @endforeach
        </div>
        <div class="mt-4 flex justify-center">
            {{ $comments->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-annotation@1.1.0"></script>

<script>
Chart.register(window['chartjs-plugin-annotation']);

const cards = @json($cards);
const questions = @json($questions);
const surveyDates = @json($surveyDates);
const ratingDistributions = @json($ratingDistributions);
const causeTables = @json($causeTables);
const causeDates = @json($causeDates);

const select = document.getElementById('questionSelect');
const titleEl = document.getElementById('questionTitle');
const textEl = document.getElementById('questionText');
const iconEl = document.getElementById('questionIcon');
const chartCanvas = document.getElementById('chartCanvas');
const chartLabel = document.getElementById('chartLabel');
const chartIcon = document.getElementById('chartIcon');

let chartInstance = null;
let stackedChartInstance = null;

drawChart(0);
drawStackedChart(0);
drawCauseTable(0);

select.addEventListener('change', function () {
    const index = parseInt(this.value);
    const card = cards[index];
    const question = questions[index];

    titleEl.textContent = question.title;
    textEl.textContent = question.description;
    chartLabel.textContent = card.label;

    const imgMap = {
        '顧客基盤の安定性': 'company.png',
        '理念戦略への納得感': 'corporate-philosophy.png',
        '社会的貢献': 'society.png',
    };
    iconEl.src = `/images/${imgMap[question.title] || 'question.png'}`;
    chartIcon.src = `/images/${card.img}`;

    drawChart(index);
    drawStackedChart(index);
    drawCauseTable(index);
});

function drawChart(index) {
    const card = cards[index];
    const data = [parseFloat(card.score), ...card.values.map(v => parseFloat(v))];
    const labels = ['最新', ...surveyDates];
    const avg = data.filter(x => !isNaN(x)).reduce((a, b) => a + b, 0) / data.length;

    if (chartInstance) chartInstance.destroy();
    chartInstance = new Chart(chartCanvas.getContext('2d'), {
        type: 'line',
        data: {
            labels,
            datasets: [{
                label: card.label,
                data: data,
                borderColor: 'rgba(75, 192, 192, 1)',
                pointBackgroundColor: 'rgba(75, 192, 192, 1)',
                tension: 0.3
            }]
        },
        options: {
            plugins: {
                annotation: {
                    annotations: {
                        avgLine: {
                            type: 'line',
                            yMin: avg,
                            yMax: avg,
                            borderColor: 'red',
                            borderWidth: 2,
                            label: {
                                content: '全体平均: ' + avg.toFixed(2),
                                enabled: true,
                                position: 'end'
                            }
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    suggestedMax: 5
                }
            }
        }
    });
}

function drawStackedChart(index) {
    const ctx = document.getElementById('stackedChartCanvas').getContext('2d');
    const dist = ratingDistributions[index];
    const labels = surveyDates;
    const datasets = [
        { label: '不満', data: dist.map(d => d['1']), backgroundColor: '#7ee5f9' },
        { label: 'やや不満', data: dist.map(d => d['2']), backgroundColor: '#45c3dc' },
        { label: '普通', data: dist.map(d => d['3']), backgroundColor: '#3490dc' },
        { label: 'やや満足', data: dist.map(d => d['4']), backgroundColor: '#5f72b2' },
        { label: '満足', data: dist.map(d => d['5']), backgroundColor: '#4c418a' }
    ];

    if (stackedChartInstance) stackedChartInstance.destroy();

    stackedChartInstance = new Chart(ctx, {
        type: 'bar',
        data: { labels, datasets },
        options: {
            responsive: true,
            scales: {
                x: { stacked: true },
                y: {
                    stacked: true,
                    beginAtZero: true,
                    max: 100,
                    ticks: { callback: v => `${v}%` }
                }
            }
        }
    });
}

function drawCauseTable(index) {
    const tableBody = document.getElementById('causeTableBody');
    const tableData = causeTables[index];
    tableBody.innerHTML = '';
    tableData.forEach((row, i) => {
        const tr = document.createElement('tr');
        tr.innerHTML = `<td class="border px-3 py-2">${i + 1}</td><td class="border px-3 py-2">${row.label}</td>` +
            row.values.map(val => `<td class="border px-3 py-2 text-center">${val}%</td>`).join('');
        tableBody.appendChild(tr);
    });
}
</script>
@endsection
