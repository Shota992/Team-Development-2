document.addEventListener('DOMContentLoaded', () => {
  const container = document.getElementById('tasks-container');
  const template = document.getElementById('task-entry-template');
  const addBtn = document.getElementById('add-task-btn');
  const form = document.querySelector('form');
  const freq = document.getElementById('evaluation_frequency');
  const customField = document.getElementById('custom-frequency-field');

  if (!template || !container || !addBtn || !form) {
    console.error('必須要素が見つかりません');
    return;
  }

  // Fetch assignees when dept changes
  function fetchAssignees(dept, assignee) {
    assignee.innerHTML = '<option value="">担当者を選んでください</option>';
    if (!dept.value) return;
    fetch(`/get-assignees/${dept.value}`)
      .then(r => r.ok ? r.json() : Promise.reject())
      .then(users => users.forEach(u => {
        const opt = document.createElement('option');
        opt.value = u.id; opt.textContent = u.name;
        assignee.appendChild(opt);
      }))
      .catch(console.error);
  }

  // Initialize a new task entry
  function initTaskEntry(clone) {
    clone.querySelectorAll('input, select').forEach(el => {
      el.disabled = false;
      el.required = true;
      if (el.tagName === 'INPUT') el.value = '';
      if (el.tagName === 'SELECT') el.selectedIndex = 0;
    });
    const dept = clone.querySelector('[name="task_department_id[]"]');
    const assignee = clone.querySelector('[name="assignee[]"]');
    dept.addEventListener('change', () => fetchAssignees(dept, assignee));
  }

  // Add initial row assignee fetch
  const firstDept = container.querySelector('[name="task_department_id[]"]');
  const firstAssignee = container.querySelector('[name="assignee[]"]');
  if (firstDept && firstAssignee) {
    fetchAssignees(firstDept, firstAssignee);
    firstDept.addEventListener('change', () => fetchAssignees(firstDept, firstAssignee));
  }

  // Add new task row
  addBtn.addEventListener('click', () => {
    const clone = template.firstElementChild.cloneNode(true);
    clone.style.display = 'block';
    initTaskEntry(clone);
    container.appendChild(clone);
  });

  // Toggle custom frequency fields
  const toggleCustom = () => customField.style.display = freq.value === 'custom' ? 'block' : 'none';
  freq.addEventListener('change', toggleCustom);
  toggleCustom();

  // Submit handler → API call
  form.addEventListener('submit', async e => {
    e.preventDefault();
    const token = document.querySelector('meta[name="csrf-token"]').content;

    const payload = {
      title: form.title.value,
      description: form.description.value,
      department_id: form.department_id.value,
      evaluation_frequency: freq.value,
      custom_frequency_value: freq.value === 'custom' ? form.custom_frequency_value.value : null,
      custom_frequency_unit: freq.value === 'custom' ? form.custom_frequency_unit.value : null,
      task_name: [...document.querySelectorAll('[name="task_name[]]')].map(i => i.value),
      task_department_id: [...document.querySelectorAll('[name="task_department_id[]]')].map(i => i.value),
      assignee: [...document.querySelectorAll('[name="assignee[]]')].map(i => i.value),
      start_date_task: [...document.querySelectorAll('[name="start_date_task[]]')].map(i => i.value),
      end_date_task: [...document.querySelectorAll('[name="end_date_task[]]')].map(i => i.value),
    };

    try {
      const res = await fetch('/api/measures', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': token,
          'Accept': 'application/json'
        },
        body: JSON.stringify(payload)
      });
      const json = await res.json();
      if (!res.ok) throw json;
      alert('保存完了！ID=' + json.measure.id);
      window.location.href = '/measures';
    } catch(err) {
      console.error(err);
      alert('保存失敗：コンソールを確認してください');
    }
  });
});
