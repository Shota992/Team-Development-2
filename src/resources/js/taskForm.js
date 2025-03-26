document.addEventListener('DOMContentLoaded', function () {
    // 最初に表示されるタスクフォームの部署選択
    const initialDepartmentSelect = document.querySelector('[name="task_department_id[]"]'); // 最初のタスクフォームの部署選択
    const initialAssigneeSelect = document.querySelector('[name="assignee[]"]'); // 最初のタスクフォームの担当者選択

    // 最初に表示されるタスクフォームの部署選択がされている場合、担当者を更新
    if (initialDepartmentSelect && initialAssigneeSelect) {
        const initialDepartmentId = initialDepartmentSelect.value;
        if (initialDepartmentId) {
            fetch(`/get-assignees/${initialDepartmentId}`)
                .then(response => response.json())
                .then(data => {
                    console.log('Initial assignees data:', data); // デバッグ用
                    initialAssigneeSelect.innerHTML = '<option value="">担当者を選んでください</option>';
                    data.forEach(employee => {
                        const option = document.createElement('option');
                        option.value = employee.id;
                        option.textContent = employee.name;
                        initialAssigneeSelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Error fetching assignees:', error));
        }

        // 最初のタスクフォームの部署選択変更時に担当者を更新
        initialDepartmentSelect.addEventListener('change', function () {
            const departmentId = this.value;

            if (!departmentId) {
                initialAssigneeSelect.innerHTML = '<option value="">担当者を選んでください</option>';
                return;
            }

            fetch(`/get-assignees/${departmentId}`)
                .then(response => response.json())
                .then(data => {
                    console.log('Updated assignees data:', data); // デバッグ用
                    initialAssigneeSelect.innerHTML = '<option value="">担当者を選んでください</option>';
                    data.forEach(employee => {
                        const option = document.createElement('option');
                        option.value = employee.id;
                        option.textContent = employee.name;
                        initialAssigneeSelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Error fetching assignees:', error));
        });
    }

    // タスク追加ボタンがクリックされたとき
    document.getElementById('add-task-btn').addEventListener('click', function () {
        const taskTemplate = document.getElementById('task-entry-template');
        const newTaskCard = taskTemplate.cloneNode(true);
        newTaskCard.style.display = 'block'; // 最初のタスクフォームを表示
        document.getElementById('tasks-container').appendChild(newTaskCard);

        const newDepartmentSelect = newTaskCard.querySelector('[name="task_department_id[]"]'); // 新しい部署選択
        const newAssigneeSelect = newTaskCard.querySelector('[name="assignee[]"]'); // 新しい担当者選択

        newDepartmentSelect.addEventListener('change', function () {
            const departmentId = this.value;

            if (!departmentId) {
                newAssigneeSelect.innerHTML = '<option value="">担当者を選んでください</option>';
                return;
            }

            fetch(`/get-assignees/${departmentId}`)
            .then(response => response.json())
            .then(data => {
                console.log('Updated assignees data:', data); // デバッグ用
                newAssigneeSelect.innerHTML = '<option value="">担当者を選んでください</option>';
                data.forEach(employee => {
                    const option = document.createElement('option');
                    option.value = employee.id; // 担当者の user_id を value に設定
                    option.textContent = employee.name;
                    newAssigneeSelect.appendChild(option);
                });
            })
            .catch(error => console.error('Error fetching assignees:', error));
        });
    });

    // カスタム設定の評価改善頻度の表示
    const evaluationFrequencySelect = document.getElementById('evaluation_frequency');
    const customFrequencyField = document.getElementById('custom-frequency-field');

    evaluationFrequencySelect.addEventListener('change', function () {
        if (this.value === 'custom') {
            customFrequencyField.style.display = 'block';
        } else {
            customFrequencyField.style.display = 'none';
        }
    });

    // ページロード時にカスタム設定が選択されている場合の処理
    if (evaluationFrequencySelect.value === 'custom') {
        customFrequencyField.style.display = 'block';
    }
});