@extends('layouts.app')

@section('title', '項目別評価一覧 - Kompass')
@section('content')
@include('components.sidebar')

<div  class="bg-[#F7F8FA] min-h-screen pb-8">
    <div class="ml-64 mr-8">
        {{-- ▼ 部署選択 --}}
        <div class="flex items-center justify-between mb-6">
            <div class="flex justify-between p-5">
                <div class="flex">
                    <figure>
                        <img src="{{ asset('images/title_logo.png') }}" alt="" />
                    </figure>
                    <p class="ml-2 text-2xl font-bold">項目別評価一覧</p>
                </div>
            </div>
            {{-- <h2 class="text-2xl font-bold text-gray-800">項目別評価一覧</h2> --}}
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
        <div class="mb-8 rounded border overflow-hidden">
            <div class="bg-[#939393] text-white px-4 py-4 flex items-center justify-between">
                <span class="font-semibold text-[16px]">項目</span>
                <div class="flex items-center space-x-2">
                    <label class="text-sm">項目を選択</label>
                    <select id="questionSelect" class="px-3 py-1 rounded border text-black w-80 text-center text-[16px]">
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
        <div class="mb-8 bg-white border rounded shadow p-8">
            <div class="flex items-center mb-2">
                <img id="chartIcon" src="{{ asset('images/' . $cards[0]['img']) }}" alt="グラフアイコン" class="w-8 h-8 mr-2">
                <h3 id="chartLabel" class="text-lg font-semibold">{{ $cards[0]['label'] }}</h3>
                <h3 class="text-lg font-semibold">評価推移</h3>
            </div>
            <canvas id="chartCanvas" height="120"></canvas>
        </div>

        {{-- ▼ 積み上げ棒グラフ --}}
        <div class="mb-8 bg-white border rounded shadow p-8">
            <h3 class="text-lg font-semibold mb-2">満足度割合（過去6回）</h3>
            <canvas id="stackedChartCanvas" height="120"></canvas>
        </div>

        {{-- ▼発生事象 --}}
        <div class="bg-[#939393] text-white font-semibold text-[16px] px-4 py-4 flex items-center justify-between">発生事象</div>
        <div class="bg-white border rounded shadow">
        {{-- ▼ 原因の表 --}}
            <div class="p-8">
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
            <div class="p-8">
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
            '社会的貢献' :  'society.png',
            '責任と顧客・社会への貢献':  'responsibility.png',
            '連帯感と相互尊重' :  'feeling-solidarity.png',
            '魅力的な上司・同僚' : 'boss.png',
            '勤務地や会社設備の魅力' : 'location.png',
            '評価・給与と柔軟な働き方' :  'work-style.png',
            '顧客ニーズや事業戦略の伝達' :  'needs.png',
            '上司や会社からの理解':  'understanding.png',
            '公平な評価' :  'evaluation.png',
            '上司からの適切な教育・支援' :  'education.png',
            '顧客の期待を上回る提案' :  'expectation.png',
            '具体的な目標の共有' :  'target.png',
            '未来に向けた活動' :  'future.png',
            'ナレッジの標準化' :  'knowledge.png',
            default :  'default.png',
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
                borderColor: '#00A6FF',
                pointBackgroundColor: '#00A6FF',
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
        { label: '不満', data: dist.map(d => d['1']), backgroundColor: '#FFA1A1' },
        { label: 'やや不満', data: dist.map(d => d['2']), backgroundColor: '#FFE0E0' },
        { label: '普通', data: dist.map(d => d['3']), backgroundColor: '#ededed' },
        { label: 'やや満足', data: dist.map(d => d['4']), backgroundColor: '#E0F4FF' },
        { label: '満足', data: dist.map(d => d['5']), backgroundColor: '#99DBFF' }
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
