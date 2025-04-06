@extends('layouts.app')

@section('content')
@include('components.sidebar')

<div class="bg-[#F7F8FA]">
    <div class="min-h-screen pb-8 ml-64 mr-8">
        {{-- ▼ ヘッダー --}}
        <div>
            <div class="flex justify-between p-5 pt-8">
                <div class="flex">
                    <figure>
                        <img src="{{ asset('images/title_logo.png') }}" alt="" />
                    </figure>
                    <p class="ml-2 text-2xl font-bold">アンケート設定 ー配信部署設定ー</p>
                </div>
            </div>
        </div>

        <div class="max-w-4xl mx-auto bg-white p-6 shadow-md">
            <h2 class="text-2xl font-bold mb-4">📨 アンケート配信対象の選択</h2>

            <p class="mb-4 text-gray-600">✔ がついている従業員にはアンケートが配信されます。チェックを外すと配信されません。</p>

            <form id="group-selection-form" method="POST" action="{{ route('survey.finalize-distribution') }}">
                @csrf
                <input type="hidden" name="groups_json" id="groups-json">

                {{-- ✅ 部署選択セクション --}}
                <div id="department-sections" class="space-y-6 mb-6">
                    <div class="department-block">
                        <div class="flex items-center space-x-4 mb-2">
                            <div class="w-full">
                                <label class="block text-sm text-gray-700 mb-1">部署を選択してください <span class="text-red-500">*</span></label>
                                <select onchange="filterUsers(this)" class="border px-4 py-2 w-full department-select required-select">
                                    <option value="">部署を選択してください</option>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="button" onclick="removeDepartmentBlock(this)"
                                class="delete-btn h-10 px-4 bg-red-500 text-white text-sm rounded-md shadow hover:bg-red-600 transition duration-200">
                                削除
                            </button>
                        </div>

                        <div class="user-list mt-4 space-y-2">
                            {{-- JSでユーザーをここに挿入 --}}
                        </div>
                    </div>
                </div>

                {{-- ✅ プラスボタン --}}
                <div class="flex justify-center mb-6">
                    <button type="button" onclick="addDepartmentBlock()" class="w-48 h-12 flex items-center justify-center space-x-2 bg-white border rounded-full shadow hover:bg-gray-100">
                        <span class="text-2xl text-[#C4C4C4]">＋</span>
                        <span class="text-[#C4C4C4] font-semibold">部署を追加</span>
                    </button>
                </div>
            </form>
        </div>
        {{-- ✅ ボタンエリア --}}
        <div class="mt-8 flex flex-col items-center space-y-4">
            <button type="submit" id="next-button"
                class="inline-block w-60 py-3 bg-[#86D4FE] text-white font-bold rounded-full shadow-lg hover:bg-[#69C2FD] transition duration-300 text-center">
                配信詳細設定へ
            </button>

            <a href="{{ route('survey.create') }}"
                class="inline-block w-60 py-3 bg-gray-300 text-white font-bold rounded-full shadow hover:bg-gray-400 transition duration-300 text-center">
                戻る
            </a>
        </div>
    </div>
</div>


<style>
    .delete-btn {
        white-space: nowrap;
        writing-mode: horizontal-tb;
    }
</style>

<script>
    const users = @json($users);

    function filterUsers(selectElement) {
        const selectedDeptId = selectElement.value;
        const userListDiv = selectElement.closest('.department-block').querySelector('.user-list');
        userListDiv.innerHTML = '';

        if (!selectedDeptId) return;

        const filtered = users.filter(user => user.department_id == selectedDeptId);
        filtered.forEach(user => {
            const div = document.createElement('div');
            div.classList.add('flex', 'items-center', 'space-x-3', 'p-2', 'border', 'rounded', 'bg-gray-50');
            div.innerHTML = `
                <input type="checkbox" value="${user.id}" class="form-checkbox text-blue-500 user-checkbox" data-dept="${selectedDeptId}" checked>
                <span>${user.name}（${user.position?.name || '役職なし'}）</span>
            `;
            userListDiv.appendChild(div);
        });
    }

    function addDepartmentBlock() {
        const container = document.getElementById('department-sections');
        const block = document.createElement('div');
        block.classList.add('department-block');
        block.innerHTML = `
            <div class="flex items-center space-x-4 mb-2">
                <div class="w-full">
                    <select onchange="filterUsers(this)" class="border px-4 py-2 w-full department-select">
                        <option value="">部署を選択してください</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="button" onclick="removeDepartmentBlock(this)"
                    class="delete-btn h-10 px-4 bg-red-500 text-white text-sm rounded-md shadow hover:bg-red-600 transition duration-200">
                    削除
                </button>
            </div>
            <div class="user-list mt-4 space-y-2"></div>
        `;
        container.appendChild(block);
    }

    function removeDepartmentBlock(button) {
        const block = button.closest('.department-block');
        block.remove();
    }

    // ✅ フォーム送信時に groups_json を構築して hidden に入れる
    document.getElementById('group-selection-form').addEventListener('submit', function (e) {
        const firstSelect = document.querySelector('.required-select');
        if (!firstSelect || firstSelect.value === '') {
            e.preventDefault();
            alert('部署を一つ選んでください。');
            return;
        }

        const groups = [];

        document.querySelectorAll('.department-block').forEach(block => {
            const deptSelect = block.querySelector('.department-select');
            const deptId = deptSelect.value;
            if (!deptId) return;

            const userCheckboxes = block.querySelectorAll('.user-checkbox:checked');
            const userIds = Array.from(userCheckboxes).map(cb => cb.value);

            groups.push({
                department_id: deptId,
                user_ids: userIds
            });
        });

        document.getElementById('groups-json').value = JSON.stringify(groups);

        const button = document.getElementById('next-button');
        button.textContent = '保存中...';
        button.classList.add('opacity-70', 'pointer-events-none');
    });
</script>
@endsection
