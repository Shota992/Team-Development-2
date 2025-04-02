document.addEventListener('DOMContentLoaded', () => {
  const container = document.getElementById('tasks-container');
  const template = document.getElementById('task-entry-template');
  const addBtn = document.getElementById('add-task-btn');
  const freq = document.getElementById('evaluation_frequency');
  const custom = document.getElementById('custom-frequency-field');

  if (!template || !container || !addBtn) {
    console.error('タスクフォーム要素が見つかりません');
    return;
  }

  /** 部署変更時に担当者を取得・セット */
  function fetchAssignees(dept, assignee) {
    assignee.innerHTML = '<option value="">担当者を選んでください</option>';
    if (!dept.value) return;
    fetch(`/get-assignees/${dept.value}`)
      .then(res => {
        if (!res.ok) throw new Error('Fetch error');
        return res.json();
      })
      .then(users => {
        users.forEach(u => {
          const opt = document.createElement('option');
          opt.value = u.id;
          opt.textContent = u.name;
          assignee.appendChild(opt);
        });
      })
      .catch(err => console.error('担当者取得失敗:', err));
  }

  /** クローン後のフォーム初期化 */
  function initializeTaskForm(clone, index) {
    clone.querySelectorAll('input, select').forEach(el => {
      el.disabled = false;
      el.required = true;
      if (el.tagName === 'INPUT') el.value = '';
      if (el.tagName === 'SELECT') el.selectedIndex = 0;
    });

    clone.querySelector('[name="task_name[]"]').id = `task_name_${index}`;
    const dept = clone.querySelector('[name="task_department_id[]"]');
    const assignee = clone.querySelector('[name="assignee[]"]');
    dept.id = `task_department_id_${index}`;
    assignee.id = `assignee_${index}`;
    clone.querySelector('[name="start_date_task[]"]').id = `start_date_task_${index}`;
    clone.querySelector('[name="end_date_task[]"]').id = `end_date_task_${index}`;

    dept.addEventListener('change', () => fetchAssignees(dept, assignee));
  }

  // 初期行の担当者取得
  const initDept = document.querySelector('[name="task_department_id[]"]');
  const initAssignee = document.querySelector('[name="assignee[]"]');
  if (initDept && initAssignee) {
    fetchAssignees(initDept, initAssignee);
    initDept.addEventListener('change', () => fetchAssignees(initDept, initAssignee));
  }

  // タスク追加ボタン
  addBtn.addEventListener('click', () => {
    const clone = template.firstElementChild.cloneNode(true);
    clone.style.display = 'block';
    clone.removeAttribute('id');

    const index = container.getElementsByClassName('task-entry').length;
    initializeTaskForm(clone, index);
    container.appendChild(clone);
  });

  // カスタム頻度表示切替
  if (freq && custom) {
    const toggle = () => custom.style.display = freq.value === 'custom' ? 'block' : 'none';
    freq.addEventListener('change', toggle);
    toggle();
  }
});


