@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-6 shadow-md">
    <h2 class="text-2xl font-bold mb-4">📨 アンケート配信対象の選択</h2>

    <p class="mb-4 text-gray-600">✔ がついている従業員にはアンケートが配信されます。チェックを外すと配信されません。</p>

    <form action="{{ route('survey.finalize-distribution') }}" method="POST">
        @csrf

        {{-- ✅ 部署選択セクション --}}
        <div id="department-sections" class="space-y-6 mb-6">
            <div class="department-block">
                <div class="flex items-center space-x-4 mb-2">
                    <select onchange="filterUsers(this)" class="border px-4 py-2 w-full department-select">
                        <option value="">部署を選択してください</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                        @endforeach
                    </select>

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

        {{-- ✅ プラスボタン（部署を追加） --}}
        <div class="flex justify-center mb-6">
            <button type="button" onclick="addDepartmentBlock()" class="w-48 h-12 flex items-center justify-center space-x-2 bg-white border rounded-full shadow hover:bg-gray-100">
                <span class="text-2xl text-[#C4C4C4]">＋</span>
                <span class="text-[#C4C4C4] font-semibold">部署を追加</span>
            </button>
        </div>

        {{-- ✅ 配信を確定する --}}
        <div class="mt-6 text-center">
            <button type="submit" class="px-14 py-3 bg-[#86D4FE] text-white font-bold rounded-full shadow-lg hover:bg-[#69C2FD] transition duration-300">
                配信を確定する
            </button>
        </div>
    </form>
</div>

{{-- ✅ スタイル補正（削除ボタンが縦になる対策） --}}
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

        userListDiv.innerHTML = ''; // 一度クリア

        if (selectedDeptId === '') return;

        const filtered = users.filter(user => user.department_id == selectedDeptId);

        filtered.forEach(user => {
            const div = document.createElement('div');
            div.classList.add('flex', 'items-center', 'space-x-3', 'p-2', 'border', 'rounded', 'bg-gray-50');

            div.innerHTML = `
                <input type="checkbox" name="users[]" value="${user.id}" checked class="form-checkbox text-blue-500">
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
                <select onchange="filterUsers(this)" class="border px-4 py-2 w-full department-select">
                    <option value="">部署を選択してください</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                    @endforeach
                </select>

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
</script>
@endsection
