const App = (() => {

  // ── STATE ────────────────────────────────────────────────
  let state = {
    projects: [],
    activeProjectIdx: null,
    activeTaskIdx: null,
    activeTab: 'headers',  // headers | query | body | response
    sending: false,
    lastResponse: null,
  };

  // ── PERSIST ──────────────────────────────────────────────
  function save() {
    localStorage.setItem('netprobe_v1', JSON.stringify(state.projects));
  }

  function load() {
    try {
      const raw = localStorage.getItem('netprobe_v1');
      if (raw) state.projects = JSON.parse(raw);
    } catch(_) { state.projects = []; }
    if (!state.projects.length) seedDemo();
  }

  function seedDemo() {
    state.projects = [
      {
        name: 'httpbin demo',
        slug: 'httpbin-demo',
        description: 'End-to-end HTTP test project using httpbin.org',
        createdAt: new Date().toISOString(),
        tasks: [
          {
            name: 'Get user info',
            slug: 'get-user-info',
            description: 'Fetch basic user data',
            method: 'GET',
            url: 'https://httpbin.org/get',
            headers: [
              { key: 'Accept', value: 'application/json', enabled: true }
            ],
            query: [
              { key: 'user_id', value: '42', enabled: true },
              { key: 'token', value: 'secret-api-token', enabled: true }
            ],
            body: [],
            bodyType: 'none',
            bodyRaw: '',
          },
          {
            name: 'Create user',
            slug: 'create-user',
            description: 'POST a new user as JSON',
            method: 'POST',
            url: 'https://httpbin.org/post',
            headers: [
              { key: 'Content-Type', value: 'application/json', enabled: true },
              { key: 'Accept', value: 'application/json', enabled: true }
            ],
            query: [],
            body: [],
            bodyType: 'raw',
            bodyRaw: '{\n  "username": "jox_developer",\n  "password": "my-super-secret-pass",\n  "email": "jox@example.com",\n  "role": "admin"\n}',
          },
          {
            name: 'Delete resource',
            slug: 'delete-resource',
            description: 'Delete by resource ID',
            method: 'DELETE',
            url: 'https://httpbin.org/delete',
            headers: [
              { key: 'X-API-Key', value: 'delete-key-99999', enabled: true }
            ],
            query: [
              { key: 'resource_type', value: 'user', enabled: true },
              { key: 'resource_id', value: 'resource-object-999', enabled: true }
            ],
            body: [],
            bodyType: 'none',
            bodyRaw: '',
          },
        ]
      }
    ];
    state.activeProjectIdx = 0;
    state.activeTaskIdx = 0;
    save();
  }

  // ── GETTERS ──────────────────────────────────────────────
  function activeProject() {
    return state.activeProjectIdx !== null ? state.projects[state.activeProjectIdx] : null;
  }
  function activeTask() {
    const p = activeProject();
    return (p && state.activeTaskIdx !== null) ? p.tasks[state.activeTaskIdx] : null;
  }

  // ── RENDER ───────────────────────────────────────────────
  function render() {
    renderSidebar();
    renderMain();
  }

  function renderSidebar() {
    const projectList = document.getElementById('project-list');
    projectList.innerHTML = '';
    state.projects.forEach((p, pi) => {
      const el = document.createElement('a');
      el.className = 'sidebar-item' + (pi === state.activeProjectIdx ? ' active' : '');
      el.innerHTML = `
        <span class="sidebar-item-dot"></span>
        <span class="sidebar-item-name">${esc(p.name)}</span>
        <span class="sidebar-item-count">${p.tasks.length}</span>
      `;
      el.addEventListener('click', () => {
        state.activeProjectIdx = pi;
        state.activeTaskIdx = p.tasks.length ? 0 : null;
        state.lastResponse = null;
        state.activeTab = 'headers';
        render();
      });
      projectList.appendChild(el);
    });

    const taskList = document.getElementById('task-list');
    taskList.innerHTML = '';
    const proj = activeProject();
    if (proj) {
      document.getElementById('tasks-section').style.display = '';
      document.getElementById('tasks-project-name').textContent = proj.name;
      proj.tasks.forEach((t, ti) => {
        const el = document.createElement('div');
        el.className = 'task-item' + (ti === state.activeTaskIdx ? ' active' : '');
        el.innerHTML = `
          <span class="method-badge method-${t.method}">${t.method}</span>
          <span class="task-name">${esc(t.name)}</span>
        `;
        el.addEventListener('click', () => {
          state.activeTaskIdx = ti;
          state.lastResponse = null;
          state.activeTab = 'headers';
          render();
        });
        taskList.appendChild(el);
      });
    } else {
      document.getElementById('tasks-section').style.display = 'none';
    }
  }

  function renderMain() {
    const task = activeTask();
    const proj = activeProject();

    if (!proj) {
      document.getElementById('main-content').innerHTML = `
        <div class="empty-state" style="height:100vh">
          <svg width="48" height="48" viewBox="0 0 48 48" fill="none" class="response-placeholder-icon">
            <rect x="4" y="8" width="40" height="32" rx="4" stroke="currentColor" stroke-width="2" fill="none"/>
            <path d="M4 16h40M14 8v8" stroke="currentColor" stroke-width="2"/>
            <circle cx="10" cy="12" r="2" fill="currentColor"/>
            <circle cx="17" cy="12" r="2" fill="currentColor"/>
            <circle cx="24" cy="12" r="2" fill="currentColor"/>
          </svg>
          <div class="empty-state-title">No project selected</div>
          <div class="empty-state-sub">Create a project to start sending HTTP requests</div>
          <button class="btn-primary" onclick="App.openNewProject()">New project</button>
        </div>`;
      return;
    }

    if (!task) {
      renderProjectOverview(proj);
      return;
    }

    renderTaskEditor(task, proj);
  }

  function renderProjectOverview(proj) {
    const methodCounts = {};
    proj.tasks.forEach(t => { methodCounts[t.method] = (methodCounts[t.method]||0)+1; });
    const tasksHtml = proj.tasks.map((t, ti) => `
      <div class="task-card" onclick="App.selectTask(${ti})">
        <div class="task-card-top">
          <span class="method-badge method-${t.method}">${t.method}</span>
          <span class="task-card-name">${esc(t.name)}</span>
        </div>
        <div class="task-card-url">${esc(t.url)}</div>
        ${t.description ? `<div class="task-card-desc">${esc(t.description)}</div>` : ''}
      </div>
    `).join('');

    document.getElementById('main-content').innerHTML = `
      <div class="project-overview">
        <div class="project-header">
          <div class="project-avatar">&#128196;</div>
          <div class="project-meta">
            <div class="project-name">${esc(proj.name)}</div>
            <div class="project-desc">${esc(proj.description || 'No description')}</div>
            <span class="project-slug">${esc(proj.slug)}</span>
          </div>
          <div style="display:flex;gap:6px">
            <button class="btn-cancel" onclick="App.openNewTask()">+ Add task</button>
            <button class="btn-cancel" style="color:var(--red);border-color:var(--red)" onclick="App.deleteProject()">Delete</button>
          </div>
        </div>
        <div class="stats-row">
          <div class="stat-box"><div class="stat-num">${proj.tasks.length}</div><div class="stat-label">Tasks</div></div>
          <div class="stat-box"><div class="stat-num">${Object.keys(methodCounts).length}</div><div class="stat-label">Methods</div></div>
          <div class="stat-box"><div class="stat-num">${proj.tasks.filter(t=>t.headers&&t.headers.length).length}</div><div class="stat-label">With headers</div></div>
        </div>
        ${proj.tasks.length ? `
          <div class="section-label">Tasks</div>
          <div class="tasks-grid">${tasksHtml}</div>
        ` : `
          <div class="empty-state" style="min-height:200px">
            <div class="empty-state-title">No tasks yet</div>
            <div class="empty-state-sub">Add your first HTTP task to this project</div>
            <button class="btn-primary" onclick="App.openNewTask()">Add task</button>
          </div>
        `}
      </div>`;
  }

  function renderTaskEditor(task, proj) {
    const hCount = (task.headers||[]).filter(h=>h.enabled).length;
    const qCount = (task.query||[]).filter(q=>q.enabled).length;
    const bCount = task.bodyType === 'raw' ? (task.bodyRaw ? 1 : 0) : (task.body||[]).length;

    document.getElementById('main-content').innerHTML = `
      <!-- REQUEST BAR -->
      <div class="request-bar">
        <div class="method-select-wrap">
          <select class="method-select" id="method-select" onchange="App.updateMethod(this.value)">
            ${['GET','POST','PUT','PATCH','DELETE','HEAD'].map(m=>`<option ${task.method===m?'selected':''} value="${m}">${m}</option>`).join('')}
          </select>
          <span class="method-select-arrow">&#9660;</span>
        </div>
        <input class="url-input" id="url-input" type="text" value="${esc(task.url)}" placeholder="https://api.example.com/endpoint" oninput="App.updateUrl(this.value)">
        <button class="btn-send" id="send-btn" onclick="App.sendRequest()">
          <svg width="13" height="13" viewBox="0 0 13 13" fill="none"><path d="M1 6.5h11M7 2l4.5 4.5L7 11" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
          Send
        </button>
      </div>

      <!-- TABS -->
      <div class="tabs-row">
        <a class="tab ${state.activeTab==='headers'?'active':''}" onclick="App.setTab('headers')">Headers <span class="tab-count">${hCount}</span></a>
        <a class="tab ${state.activeTab==='query'?'active':''}" onclick="App.setTab('query')">Query <span class="tab-count">${qCount}</span></a>
        <a class="tab ${state.activeTab==='body'?'active':''}" onclick="App.setTab('body')">Body <span class="tab-count">${bCount}</span></a>
        <a class="tab ${state.activeTab==='response'?'active':''}" onclick="App.setTab('response')">Response ${state.lastResponse ? `<span class="tab-count">${state.lastResponse.status}</span>` : ''}</a>
        <span class="tabs-spacer"></span>
        <span class="task-info-pill">${esc(task.name)}</span>
        <button class="btn-icon" onclick="App.openNewTask()" title="Add task" style="margin-left:6px">
          <svg width="14" height="14" viewBox="0 0 14 14" fill="none"><path d="M7 2v10M2 7h10" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
        </button>
        <button class="btn-icon" onclick="App.deleteTask()" title="Delete task" style="color:var(--red)">
          <svg width="14" height="14" viewBox="0 0 14 14" fill="none"><path d="M2 4h10M5 4V3a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v1M11 4l-.8 7.2a1 1 0 0 1-1 .8H4.8a1 1 0 0 1-1-.8L3 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </button>
      </div>

      <!-- WORKSPACE -->
      <div class="workspace">
        <div class="workspace-pane" id="left-pane">
          ${renderLeftPane(task)}
        </div>
        <div class="workspace-pane" id="right-pane">
          ${renderRightPane()}
        </div>
      </div>
    `;

    // Style method select color
    updateMethodSelectColor(task.method);
  }

  function renderLeftPane(task) {
    if (state.activeTab === 'headers') return renderParamTable(task.headers||[], 'headers');
    if (state.activeTab === 'query')   return renderParamTable(task.query||[], 'query');
    if (state.activeTab === 'body')    return renderBodyPane(task);
    if (state.activeTab === 'response') return renderResponsePane();
    return '';
  }

  function renderParamTable(params, type) {
    const rows = params.map((p, i) => `
      <tr>
        <td style="width:28px">
          <input type="checkbox" ${p.enabled?'checked':''} onchange="App.toggleParam('${type}',${i},this.checked)" style="cursor:pointer;accent-color:var(--accent)">
        </td>
        <td><input class="param-input" value="${esc(p.key)}" placeholder="Key" oninput="App.updateParam('${type}',${i},'key',this.value)"></td>
        <td><input class="param-input" value="${esc(p.value)}" placeholder="Value" oninput="App.updateParam('${type}',${i},'value',this.value)"></td>
        <td style="width:32px">
          <button class="param-remove" onclick="App.removeParam('${type}',${i})" title="Remove">
            <svg width="12" height="12" viewBox="0 0 12 12" fill="none"><path d="M2 2l8 8M10 2l-8 8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/></svg>
          </button>
        </td>
      </tr>
    `).join('');

    const label = type === 'headers' ? 'Request headers' : 'Query parameters';
    return `
      <div class="pane-title"><span class="pane-title-dot"></span>${label}</div>
      <table class="param-table">
        <thead><tr><th></th><th>Key</th><th>Value</th><th></th></tr></thead>
        <tbody>${rows}</tbody>
      </table>
      <button class="btn-add-param" onclick="App.addParam('${type}')">
        <svg width="12" height="12" viewBox="0 0 12 12" fill="none"><path d="M6 1v10M1 6h10" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
        Add ${type === 'headers' ? 'header' : 'parameter'}
      </button>
    `;
  }

  function renderBodyPane(task) {
    const types = ['none','raw','form-data','urlencoded'];
    const typeBtns = types.map(t => `<button class="body-type-btn ${task.bodyType===t?'active':''}" onclick="App.setBodyType('${t}')">${t}</button>`).join('');

    let content = '';
    if (task.bodyType === 'raw') {
      content = `
        <textarea class="body-textarea" placeholder='{"key": "value"}' oninput="App.updateBodyRaw(this.value)">${esc(task.bodyRaw||'')}</textarea>
      `;
    } else if (task.bodyType === 'form-data' || task.bodyType === 'urlencoded') {
      content = renderParamTable(task.body||[], 'body');
    } else {
      content = `<div class="response-placeholder" style="min-height:120px"><span class="response-placeholder-text">No body (GET / DELETE / HEAD)</span></div>`;
    }

    return `
      <div class="pane-title"><span class="pane-title-dot"></span>Request body</div>
      <div class="body-type-row">${typeBtns}</div>
      ${content}
    `;
  }

  function renderRightPane() {
    return `
      <div class="pane-title"><span class="pane-title-dot" style="background:var(--green)"></span>Response</div>
      ${renderResponsePane()}
    `;
  }

  function renderResponsePane() {
    const r = state.lastResponse;
    if (!r) {
      return `
        <div class="response-placeholder">
          <svg class="response-placeholder-icon" width="40" height="40" viewBox="0 0 40 40" fill="none">
            <circle cx="20" cy="20" r="16" stroke="currentColor" stroke-width="1.5" fill="none"/>
            <path d="M13 20h14M20 13l7 7-7 7" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
          <span class="response-placeholder-text">Hit Send to see the response</span>
        </div>
      `;
    }

    const codeClass = r.status < 300 ? 'status-2xx' : r.status < 400 ? 'status-3xx' : r.status < 500 ? 'status-4xx' : 'status-5xx';
    const headersHtml = Object.entries(r.headers||{}).slice(0, 20).map(([k,v]) => `
      <tr><td>${esc(k)}</td><td>${esc(v)}</td></tr>
    `).join('');

    let bodyHtml = '';
    try {
      const parsed = JSON.parse(r.body);
      bodyHtml = syntaxHighlight(JSON.stringify(parsed, null, 2));
    } catch(_) {
      bodyHtml = esc(r.body || '');
    }

    return `
      <div class="response-status-bar">
        <span class="status-code ${codeClass}">${r.status}</span>
        <span class="status-label">${r.statusText}</span>
        <div class="status-meta">
          <span class="status-meta-item">Time: <span>${r.time}ms</span></span>
          <span class="status-meta-item">Size: <span>${formatBytes(r.size)}</span></span>
        </div>
      </div>
      <div class="section-label">Body</div>
      <div class="response-body">${bodyHtml}</div>
      <div class="section-label mt-12">Headers (${Object.keys(r.headers||{}).length})</div>
      <table class="response-headers-table"><tbody>${headersHtml}</tbody></table>
    `;
  }

  // ── ACTIONS ──────────────────────────────────────────────
  function setTab(tab) {
    state.activeTab = tab;
    renderMain();
  }

  function updateMethod(v) {
    const task = activeTask();
    if (!task) return;
    task.method = v;
    updateMethodSelectColor(v);
    save();
    renderSidebar();
  }

  function updateUrl(v) {
    const task = activeTask();
    if (!task) return;
    task.url = v;
    save();
  }

  function addParam(type) {
    const task = activeTask();
    if (!task) return;
    if (!task[type]) task[type] = [];
    task[type].push({ key: '', value: '', enabled: true });
    save();
    rerenderLeftPane(task);
  }

  function removeParam(type, idx) {
    const task = activeTask();
    if (!task || !task[type]) return;
    task[type].splice(idx, 1);
    save();
    rerenderLeftPane(task);
  }

  function updateParam(type, idx, field, val) {
    const task = activeTask();
    if (!task || !task[type] || !task[type][idx]) return;
    task[type][idx][field] = val;
    save();
  }

  function toggleParam(type, idx, checked) {
    const task = activeTask();
    if (!task || !task[type]) return;
    task[type][idx].enabled = checked;
    save();
  }

  function setBodyType(t) {
    const task = activeTask();
    if (!task) return;
    task.bodyType = t;
    save();
    rerenderLeftPane(task);
  }

  function updateBodyRaw(v) {
    const task = activeTask();
    if (!task) return;
    task.bodyRaw = v;
    save();
  }

  function rerenderLeftPane(task) {
    const pane = document.getElementById('left-pane');
    if (pane) pane.innerHTML = renderLeftPane(task);
    // update tab counts
    const hTab = document.querySelector('.tab:nth-child(1) .tab-count');
    const qTab = document.querySelector('.tab:nth-child(2) .tab-count');
    const bTab = document.querySelector('.tab:nth-child(3) .tab-count');
    if (hTab) hTab.textContent = (task.headers||[]).filter(h=>h.enabled).length;
    if (qTab) qTab.textContent = (task.query||[]).filter(q=>q.enabled).length;
    if (bTab) bTab.textContent = task.bodyType==='raw' ? (task.bodyRaw?1:0) : (task.body||[]).length;
  }

  async function sendRequest() {
    const task = activeTask();
    if (!task || state.sending) return;

    state.sending = true;
    const btn = document.getElementById('send-btn');
    if (btn) {
      btn.innerHTML = '<div class="spinner"></div> Sending';
      btn.classList.add('loading');
    }

    const t0 = Date.now();
    try {
      // Build URL with query params
      let url = task.url;
      const qParams = (task.query||[]).filter(q=>q.enabled && q.key);
      if (qParams.length) {
        const qs = qParams.map(q => `${encodeURIComponent(q.key)}=${encodeURIComponent(q.value)}`).join('&');
        url += (url.includes('?') ? '&' : '?') + qs;
      }

      // Build headers
      const headers = {};
      (task.headers||[]).filter(h=>h.enabled && h.key).forEach(h => { headers[h.key] = h.value; });

      // Build body
      let body = undefined;
      if (task.method !== 'GET' && task.method !== 'HEAD') {
        if (task.bodyType === 'raw' && task.bodyRaw) {
          body = task.bodyRaw;
          if (!headers['Content-Type']) headers['Content-Type'] = 'application/json';
        } else if (task.bodyType === 'urlencoded') {
          const fd = (task.body||[]).filter(b=>b.key&&b.enabled);
          body = fd.map(b=>`${encodeURIComponent(b.key)}=${encodeURIComponent(b.value)}`).join('&');
          headers['Content-Type'] = 'application/x-www-form-urlencoded';
        }
      }

      const res = await fetch(url, {
        method: task.method,
        headers,
        body,
      });

      const time = Date.now() - t0;
      const text = await res.text();
      const resHeaders = {};
      res.headers.forEach((v, k) => { resHeaders[k] = v; });

      state.lastResponse = {
        status: res.status,
        statusText: res.statusText,
        headers: resHeaders,
        body: text,
        time,
        size: new Blob([text]).size,
      };

      showToast(`${res.status} ${res.statusText}`, res.ok ? 'success' : 'error');
      state.activeTab = 'response';
    } catch(err) {
      state.lastResponse = {
        status: 0,
        statusText: 'Network error',
        headers: {},
        body: err.message,
        time: Date.now() - t0,
        size: 0,
      };
      showToast('Request failed: ' + err.message, 'error');
      state.activeTab = 'response';
    }

    state.sending = false;
    renderMain();
  }

  function selectTask(ti) {
    state.activeTaskIdx = ti;
    state.lastResponse = null;
    state.activeTab = 'headers';
    render();
  }

  function deleteTask() {
    const proj = activeProject();
    if (!proj || state.activeTaskIdx === null) return;
    if (!confirm(`Delete task "${proj.tasks[state.activeTaskIdx].name}"?`)) return;
    proj.tasks.splice(state.activeTaskIdx, 1);
    state.activeTaskIdx = proj.tasks.length ? Math.min(state.activeTaskIdx, proj.tasks.length-1) : null;
    state.lastResponse = null;
    save();
    render();
  }

  function deleteProject() {
    const proj = activeProject();
    if (!proj) return;
    if (!confirm(`Delete project "${proj.name}" and all its tasks?`)) return;
    state.projects.splice(state.activeProjectIdx, 1);
    state.activeProjectIdx = state.projects.length ? 0 : null;
    state.activeTaskIdx = null;
    save();
    render();
  }

  // ── MODALS ───────────────────────────────────────────────
  function openNewProject() {
    document.getElementById('project-modal').classList.add('open');
    document.getElementById('proj-name-input').focus();
  }

  function closeNewProject() {
    document.getElementById('project-modal').classList.remove('open');
    document.getElementById('proj-name-input').value = '';
    document.getElementById('proj-desc-input').value = '';
  }

  function submitNewProject() {
    const name = document.getElementById('proj-name-input').value.trim();
    if (!name) { alert('Project name is required'); return; }
    const desc = document.getElementById('proj-desc-input').value.trim();
    const slug = name.toLowerCase().replace(/[^a-z0-9\s-]/g,'').replace(/\s+/g,'-').replace(/-+/g,'-');
    const proj = { name, slug, description: desc, createdAt: new Date().toISOString(), tasks: [] };
    state.projects.push(proj);
    state.activeProjectIdx = state.projects.length - 1;
    state.activeTaskIdx = null;
    save();
    closeNewProject();
    render();
  }

  function openNewTask() {
    if (activeProject() === null) return;
    document.getElementById('task-modal').classList.add('open');
    document.getElementById('task-name-input').focus();
  }

  function closeNewTask() {
    document.getElementById('task-modal').classList.remove('open');
    document.getElementById('task-name-input').value = '';
    document.getElementById('task-url-input').value = '';
    document.getElementById('task-desc-input').value = '';
  }

  function submitNewTask() {
    const proj = activeProject();
    if (!proj) return;
    const name = document.getElementById('task-name-input').value.trim();
    const url  = document.getElementById('task-url-input').value.trim();
    if (!name || !url) { alert('Name and URL are required'); return; }
    const method = document.getElementById('task-method-select').value;
    const desc   = document.getElementById('task-desc-input').value.trim();
    const slug   = name.toLowerCase().replace(/[^a-z0-9\s-]/g,'').replace(/\s+/g,'-');
    const task   = { name, slug, description: desc, method, url, headers: [], query: [], body: [], bodyType: 'none', bodyRaw: '' };
    proj.tasks.push(task);
    state.activeTaskIdx = proj.tasks.length - 1;
    state.lastResponse = null;
    state.activeTab = 'headers';
    save();
    closeNewTask();
    render();
  }

  // ── HELPERS ──────────────────────────────────────────────
  function esc(s) {
    return String(s||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
  }

  function updateMethodSelectColor(method) {
    const sel = document.getElementById('method-select');
    if (!sel) return;
    const colors = { GET: 'var(--method-get)', POST: 'var(--method-post)', PUT: 'var(--method-put)', PATCH: 'var(--method-patch)', DELETE: 'var(--method-delete)', HEAD: 'var(--method-head)' };
    sel.style.color = colors[method] || 'var(--text-1)';
  }

  function formatBytes(b) {
    if (b < 1024) return b + ' B';
    if (b < 1048576) return (b/1024).toFixed(1) + ' KB';
    return (b/1048576).toFixed(2) + ' MB';
  }

  function syntaxHighlight(json) {
    return json
      .replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;')
      .replace(/("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?)/g, match => {
        let cls = 'json-num';
        if (/^"/.test(match)) cls = /:$/.test(match) ? 'json-key' : 'json-str';
        else if (/true|false/.test(match)) cls = 'json-bool';
        else if (/null/.test(match)) cls = 'json-null';
        return `<span class="${cls}">${match}</span>`;
      });
  }

  function showToast(msg, type='') {
    const t = document.getElementById('toast');
    t.textContent = msg;
    t.className = 'toast ' + type;
    t.classList.add('show');
    setTimeout(() => t.classList.remove('show'), 3000);
  }

  // ── INIT ─────────────────────────────────────────────────
  function init() {
    load();
    render();

    // Modal close on overlay click
    document.getElementById('project-modal').addEventListener('click', e => {
      if (e.target === e.currentTarget) closeNewProject();
    });
    document.getElementById('task-modal').addEventListener('click', e => {
      if (e.target === e.currentTarget) closeNewTask();
    });

    // Keyboard shortcuts
    document.addEventListener('keydown', e => {
      if (e.key === 'Escape') { closeNewProject(); closeNewTask(); }
      if ((e.ctrlKey||e.metaKey) && e.key === 'Enter') sendRequest();
    });
  }

  return {
    init, render, setTab, sendRequest,
    updateMethod, updateUrl,
    addParam, removeParam, updateParam, toggleParam,
    setBodyType, updateBodyRaw,
    selectTask, deleteTask, deleteProject,
    openNewProject, closeNewProject, submitNewProject,
    openNewTask, closeNewTask, submitNewTask,
  };
})();

document.addEventListener('DOMContentLoaded', App.init);
