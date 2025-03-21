document.addEventListener('DOMContentLoaded', function () {
    const evaluationFrequencySelect = document.getElementById('evaluation_frequency');
    const customFrequencyField = document.getElementById('custom-frequency-field');

    // 最初にカスタム設定が選ばれている場合の処理
    toggleCustomFrequencyField(evaluationFrequencySelect.value);

    evaluationFrequencySelect.addEventListener('change', function () {
        toggleCustomFrequencyField(this.value);
    });

    function toggleCustomFrequencyField(value) {
        if (value === 'custom') {
            customFrequencyField.style.display = 'block';
        } else {
            customFrequencyField.style.display = 'none';
        }
    }
});


document.addEventListener('DOMContentLoaded', function () {
    const departmentSelect = document.getElementById('department_id'); // 部署選択
    const assigneeSelect = document.getElementById('assignee'); // 担当者選択

    departmentSelect.addEventListener('change', function () {
        const departmentId = this.value;

        // 部署が選ばれていない場合は担当者リストをリセット
        if (!departmentId) {
            assigneeSelect.innerHTML = '<option value="">担当者を選んでください</option>';
            console.log('部署が選ばれていない');
            return;
        }

        console.log('部署が選ばれました。ID: ', departmentId); // ここで選択された部署IDが表示されることを確認

        // 部署に対応する担当者をバックエンドから取得
        fetch(`/get-assignees/${departmentId}`)
            .then(response => response.json())
            .then(data => {
                // 担当者の選択肢をクリア
                assigneeSelect.innerHTML = '<option value="">担当者を選んでください</option>';

                // 取得した担当者リストを追加
                data.forEach(employee => {
                    const option = document.createElement('option');
                    option.value = employee.id;
                    option.textContent = employee.name;
                    assigneeSelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error('担当者取得時にエラーが発生しました:', error);
            });
    });
});

document.addEventListener('DOMContentLoaded', function () {
    const addTaskButton = document.getElementById('add-task-btn'); // タスク追加ボタン
    const tasksContainer = document.getElementById('tasks-container'); // タスクを追加するコンテナ

    addTaskButton.addEventListener('click', function () {
        // タスクのテンプレートを複製
        const taskTemplate = document.getElementById('task-entry-template');
        const newTaskCard = taskTemplate.cloneNode(true);
        newTaskCard.style.display = 'block'; // 初期状態では非表示のため表示させる

        // 新しいタスクカード内の部署選択と担当者選択を取得
        const departmentSelectClone = newTaskCard.querySelector('[name="department_id[]"]'); // 新しい部署セレクトボックス
        const assigneeDropdown = newTaskCard.querySelector('[name="assignee[]"]'); // 新しい担当者セレクトボックス

        // 部署選択時の処理
        departmentSelectClone.addEventListener('change', function () {
            const selectedDepartmentId = departmentSelectClone.value;
            // 担当者リストを更新する
            fetch(`/get-assignees/${selectedDepartmentId}`)
                .then(response => response.json())
                .then(data => {
                    assigneeDropdown.innerHTML = '<option value="">担当者を選んでください</option>'; // リセット
                    data.forEach(employee => {
                        const option = document.createElement('option');
                        option.value = employee.id;
                        option.textContent = employee.name;
                        assigneeDropdown.appendChild(option);
                    });
                })
                .catch(error => console.error('Error fetching assignees:', error));
        });

        // 新しいタスクカードを追加する
        tasksContainer.appendChild(newTaskCard);
    });
});







