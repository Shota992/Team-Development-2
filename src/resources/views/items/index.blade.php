@extends('layouts.app')

@section('content')
<div class="ml-64 p-5">
    <h2 class="text-2xl font-bold mb-5">アンケート結果グラフ（部署別）</h2>

    @if(count($cards) === 0)
        <p class="text-gray-600">表示できるアンケートデータがありません。</p>
    @endif

    <!-- ▼ プルダウンと説明表示 -->
    <div class="bg-custom-gray text-white px-4 py-3 flex items-center justify-between rounded-t-md">
        <span class="text-lg font-semibold">項目</span>
        <div class="flex items-center">
            <label class="mr-2">項目を選択</label>
            <select id="questionSelect" class="px-3 py-1 rounded border text-black w-80 text-center ml-4">
                @foreach($questions as $question)
                    <option value="{{ $loop->index }}">{{ $question->title }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- ▼ 説明エリア -->
    <div id="questionDescription" class="bg-white border-b border-l border-r p-5 flex">
        <img id="questionIcon" src="{{ asset('images/question.png') }}" class="w-10 h-10 mr-4" alt="アイコン">
        <div>
            <h3 id="questionTitle" class="text-lg font-bold mb-1">{{ $questions[0]->title ?? '' }}</h3>
            <p id="questionText" class="text-sm text-gray-700">
                {{ $questions[0]->description ?? '' }}
            </p>
        </div>
    </div>

    <!-- ▼ 折れ線グラフ -->
    <div id="chart-area" class="bg-white border rounded shadow p-5 mt-6">
        <div class="flex items-center mb-4">
            <img id="chartIcon" src="{{ asset('images/' . $cards[0]['img']) }}" alt="グラフアイコン" class="w-10 h-10 mr-3">
            <h3 id="chartLabel" class="text-xl font-semibold">{{ $cards[0]['label'] }}</h3>
        </div>
        <canvas id="chartCanvas" width="600" height="400"></canvas>
    </div>

    <!-- ▼ 積み上げ棒グラフ -->
    <div id="stacked-chart-area" class="bg-white border rounded shadow p-5 mt-6">
        <h3 class="text-xl font-semibold mb-4">満足度割合（過去6回）</h3>
        <canvas id="stackedChartCanvas" width="600" height="400"></canvas>
    </div>

    <!-- ▼ 原因表 -->
    <div id="cause-table-area" class="bg-white border rounded shadow p-5 mt-6">
        <h3 class="text-xl font-semibold mb-4">発生事象</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full table-auto border border-gray-300">
                <thead>
                    <tr class="bg-gray-200 text-gray-700">
                        <th class="px-3 py-2 text-left">項目</th>
                        @foreach(array_reverse($causeDates) as $date)
                            <th class="px-3 py-2">{{ $date }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody id="causeTableBody">
                    <!-- JavaScriptで動的に入れ替え -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- ▼ コメント一覧 -->
    <div class="bg-white border rounded shadow p-5 mt-10">
        <h3 class="text-xl font-semibold mb-4">コメント</h3>

        <p class="text-sm text-gray-600 mb-2">
            （{{ $comments->total() }}件中{{ $comments->firstItem() }}〜{{ $comments->lastItem() }}件目を表示）
        </p>

        <div class="space-y-4">
            @foreach($comments as $comment)
                <div class="border border-gray-300 rounded-lg p-4 bg-gray-50">
                    <p class="text-xs text-right text-gray-500 mb-1">
                        アンケート日：{{ \Carbon\Carbon::parse($comment->survey->start_date)->format('Y/m/d') }}
                    </p>
                    <p class="text-sm text-gray-800 whitespace-pre-wrap">{{ $comment->free_message }}</p>
                </div>
            @endforeach
        </div>

        <!-- ページネーション -->
        <div class="mt-4 flex justify-center">
            {{ $comments->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-annotation@1.1.0/dist/chartjs-plugin-annotation.min.js"></script>

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
            labels: labels,
            datasets: [{
                label: card.label,
                data: data,
                fill: false,
                borderColor: 'rgba(75, 192, 192, 1)',
                pointBackgroundColor: 'rgba(75, 192, 192, 1)',
                tension: 0.3,
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
                                position: 'end',
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
            plugins: { legend: { position: 'top' } },
            responsive: true,
            scales: {
                x: { stacked: true },
                y: {
                    stacked: true,
                    beginAtZero: true,
                    max: 100,
                    ticks: { callback: val => val + '%' }
                }
            }
        }
    });
}

function drawCauseTable(index) {
    const tableBody = document.getElementById('causeTableBody');
    const tableData = causeTables[index];
    const reversedDates = [...causeDates].reverse();

    tableBody.innerHTML = '';
    tableData.forEach(row => {
        const tr = document.createElement('tr');
        tr.innerHTML = `<td class="border px-3 py-2">${row.label}</td>` +
            reversedDates.map((_, i) =>
                `<td class="border px-3 py-2 text-center">${row.values[i]}%</td>`).join('');
        tableBody.appendChild(tr);
    });
}
</script>
@endsection
