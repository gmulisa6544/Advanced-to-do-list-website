// Starter JS for the To-Do app: basic API calls and UI updates
console.log('To-Do starter script loaded');

function fetchJSON(url, options) {
	return fetch(url, options).then(r => r.json());
}

var tasksCache = [];
var currentProjectId = '';

function loadTasks() {
	fetchJSON('api/tasks.php?action=list')
		.then(data => {
			if (!data.success) { if (document.getElementById('tasksList')) document.getElementById('tasksList').innerText = 'Error loading tasks'; return; }
			tasksCache = data.tasks;
			populateCategories(data.categories);
			applyFiltersAndRender();
		}).catch(err => { console.error(err); if (document.getElementById('tasksList')) document.getElementById('tasksList').innerText = 'Error'; });
}

function populateCategories(categories) {
	var sel = document.getElementById('categorySelect');
	var editSel = document.getElementById('editCategorySelect');
	var filterSel = document.getElementById('filterCategory');
	if (!categories) categories = [];
	[sel, editSel, filterSel].forEach(function(s){ if (!s) return; s.innerHTML = '<option value="">(None)</option>'; });
	categories.forEach(function(c){
		var opt = document.createElement('option'); opt.value = c.id; opt.textContent = c.name;
		if (sel) sel.appendChild(opt.cloneNode(true));
		if (editSel) editSel.appendChild(opt.cloneNode(true));
		if (filterSel) filterSel.appendChild(opt.cloneNode(true));
	});
	// Also populate sidebar project list
	var projectList = document.getElementById('projectList');
	if (projectList) {
		projectList.innerHTML = '';
		// Inbox
		var li = document.createElement('li'); li.className = 'project-item' + (currentProjectId === '' ? ' active' : ''); li.dataset.id = '';
		li.textContent = 'Inbox'; projectList.appendChild(li);
		categories.forEach(function(c){
			var p = document.createElement('li'); p.className = 'project-item'; p.dataset.id = c.id; p.textContent = c.name; projectList.appendChild(p);
			if (String(c.id) === String(currentProjectId)) p.classList.add('active');
		});
	}
}

function applyFiltersAndRender() {
	var list = tasksCache.slice();
	var q = (document.getElementById('searchInput') || {}).value || '';
	var priority = (document.getElementById('filterPriority') || {}).value || '';
	var category = (document.getElementById('filterCategory') || {}).value || '';
	var completed = (document.getElementById('filterCompleted') || {}).value;
	if (q) {
		q = q.toLowerCase();
		list = list.filter(function(t){ return (t.title||'').toLowerCase().includes(q) || (t.description||'').toLowerCase().includes(q); });
	}
	if (priority) list = list.filter(function(t){ return t.priority === priority; });
	if (category) list = list.filter(function(t){ return String(t.category_id) === String(category); });
	// If a project is selected in the sidebar, filter by that project
	if (currentProjectId !== '') {
		list = list.filter(function(t){ return String(t.category_id) === String(currentProjectId); });
	}
	if (completed === '0' || completed === '1') list = list.filter(function(t){ return String(t.completed) === String(completed); });
	renderTasks(list);
}

function renderTasks(tasks) {
	var container = document.getElementById('tasksList');
	if (!container) return;
	container.innerHTML = '';
	if (tasks.length === 0) { container.innerText = 'No tasks match your filters.'; return; }
	tasks.forEach(function(t){
		var item = document.createElement('div'); item.className = 'task-item';
		if (t.completed == 1) item.classList.add('completed');
		var row = document.createElement('div'); row.className = 'task-row';

		var cb = document.createElement('input'); cb.type = 'checkbox'; cb.className = 'task-checkbox'; cb.checked = (t.completed == 1);
		cb.addEventListener('change', function(){ toggleComplete(t.id, t.completed); });

		var content = document.createElement('div'); content.className = 'task-title';
		var title = document.createElement('div'); title.className = 'task-title-text';
		title.innerHTML = (t.category_name ? ('<span class="project-dot" style="background:' + getColorForId(t.category_id) + '"></span>') : '') + '<strong>' + escapeHtml(t.title) + '</strong>';
		title.addEventListener('dblclick', function(){ openEditForm(t); });
		var desc = document.createElement('div'); desc.className = 'task-meta'; desc.textContent = t.description || '';
		content.appendChild(title); content.appendChild(desc);

		var right = document.createElement('div'); right.className = 'task-right';
		var pri = document.createElement('div'); pri.className = 'priority-badge priority-' + (t.priority || 'Medium'); pri.textContent = t.priority || 'Medium';
		var editBtn = document.createElement('button'); editBtn.textContent = 'Edit'; editBtn.addEventListener('click', function(){ openEditForm(t); });
		var delBtn = document.createElement('button'); delBtn.textContent = 'Delete'; delBtn.addEventListener('click', function(){ deleteTask(t.id); });
		right.appendChild(pri); right.appendChild(editBtn); right.appendChild(delBtn);

		row.appendChild(cb); row.appendChild(content); row.appendChild(right);
		item.appendChild(row);
		container.appendChild(item);
	});
}

// Utility: simple HTML escape
function escapeHtml(s) { return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;'); }

// Color palette for projects (deterministic from id)
function getColorForId(id) {
	var palette = ['#ef4444','#f97316','#f59e0b','#eab308','#84cc16','#10b981','#06b6d4','#3b82f6','#6366f1','#8b5cf6'];
	if (!id) return '#94a3b8';
	var i = Math.abs(parseInt(id,10)) % palette.length;
	return palette[i];
}

function openEditForm(task) {
	var wrapper = document.getElementById('editTaskForm');
	var form = document.getElementById('editForm');
	if (!wrapper || !form) return;
	form.id.value = task.id;
	form.title.value = task.title;
	form.description.value = task.description || '';
	form.priority.value = task.priority || 'Medium';
	form.due_date.value = task.due_date || '';
	form.category_id.value = task.category_id || '';
	wrapper.style.display = 'block';
}

function closeEditForm() { document.getElementById('editTaskForm').style.display = 'none'; }

function toggleComplete(id, current) {
	var formData = new FormData();
	formData.append('action','update');
	formData.append('id', id);
	formData.append('completed', current == 1 ? 0 : 1);
	fetchJSON('api/tasks.php', { method:'POST', body: formData }).then(function(res){ if (res.success) loadTasks(); else alert(res.error || 'Error'); });
}

function deleteTask(id) {
	if (!confirm('Delete this task?')) return;
	var fd = new FormData(); fd.append('action','delete'); fd.append('id', id);
	fetchJSON('api/tasks.php', { method:'POST', body: fd }).then(function(res){ if (res.success) loadTasks(); else alert(res.error || 'Error'); });
}

document.addEventListener('DOMContentLoaded', function(){
	var form = document.getElementById('addTaskForm');
	if (form) {
		form.addEventListener('submit', function(e){
			e.preventDefault();
			var fd = new FormData(form); fd.append('action','add');
			fetchJSON('api/tasks.php', { method: 'POST', body: fd }).then(function(res){
				if (res.success) { form.reset(); loadTasks(); } else { alert(res.error || 'Error adding'); }
			});
		});

		// Add category
		var addCatBtn = document.getElementById('addCategoryBtn');
		if (addCatBtn) {
			addCatBtn.addEventListener('click', function(){
				var name = (document.getElementById('newCategoryName') || {}).value || '';
				if (!name) { alert('Enter category name'); return; }
				var fd = new FormData(); fd.append('action','add_category'); fd.append('name', name);
				fetchJSON('api/tasks.php', { method:'POST', body: fd }).then(function(res){ if (res.success) { document.getElementById('newCategoryName').value = ''; loadTasks(); } else { alert(res.error || 'Error'); } });
			});
		}

		// Sidebar add category
		var addCatSidebar = document.getElementById('addCategoryBtnSidebar');
		if (addCatSidebar) {
			addCatSidebar.addEventListener('click', function(){
				var name = (document.getElementById('newCategoryNameSidebar') || {}).value || '';
				if (!name) { alert('Enter project name'); return; }
				var fd = new FormData(); fd.append('action','add_category'); fd.append('name', name);
				fetchJSON('api/tasks.php', { method:'POST', body: fd }).then(function(res){ if (res.success) { document.getElementById('newCategoryNameSidebar').value = ''; loadTasks(); } else { alert(res.error || 'Error'); } });
			});
		}

		// Project list click handling (delegation)
		var projectList = document.getElementById('projectList');
		if (projectList) {
			projectList.addEventListener('click', function(e){
				var item = e.target.closest('.project-item'); if (!item) return;
				// set current project
				currentProjectId = item.dataset.id || '';
				// set active class
				projectList.querySelectorAll('.project-item').forEach(function(p){ p.classList.remove('active'); });
				item.classList.add('active');
				// update title
				var title = document.getElementById('projectTitle'); if (title) title.textContent = item.textContent;
				applyFiltersAndRender();
			});
		}

		// Edit form handlers
		var editForm = document.getElementById('editForm');
		if (editForm) {
			editForm.addEventListener('submit', function(e){
				e.preventDefault();
				var fd = new FormData(editForm); fd.append('action','update');
				fetchJSON('api/tasks.php', { method:'POST', body: fd }).then(function(res){ if (res.success) { closeEditForm(); loadTasks(); } else { alert(res.error || 'Error saving'); } });
			});
			document.getElementById('cancelEdit').addEventListener('click', function(){ closeEditForm(); });
		}

		// Filters
		['searchInput','filterPriority','filterCategory','filterCompleted'].forEach(function(id){ var el = document.getElementById(id); if (el) el.addEventListener('input', applyFiltersAndRender); });

		var backupBtn = document.getElementById('backupBtn');
		if (backupBtn) {
			backupBtn.addEventListener('click', function(){
				backupBtn.disabled = true;
				fetchJSON('api/export.php?action=backup').then(function(res){
					backupBtn.disabled = false;
					if (res.success) { alert('Backup saved: ' + res.file); } else { alert('Backup failed'); }
				}).catch(function(){ backupBtn.disabled = false; alert('Backup failed'); });
			});
		}

		// Import JSON backup
		var importBtn = document.getElementById('importBtn');
		if (importBtn) {
			importBtn.addEventListener('click', function(){
				var fileInput = document.getElementById('importFile');
				if (!fileInput || !fileInput.files || !fileInput.files[0]) { alert('Choose a JSON file first'); return; }
				if (!confirm('Importing will add tasks/categories to your account. Proceed?')) return;
				var fd = new FormData(); fd.append('import_file', fileInput.files[0]);
				fetch('api/import.php', { method: 'POST', body: fd }).then(r => r.json()).then(function(res){
					if (res.success) { alert('Imported: ' + res.added + ' tasks'); loadTasks(); } else { alert('Import failed: ' + (res.error || 'unknown')); }
				}).catch(function(){ alert('Import failed'); });
			});
		}

		loadTasks();
	}
});

