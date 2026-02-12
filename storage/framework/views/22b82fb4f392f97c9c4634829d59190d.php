<?php $__env->startSection('title','Teachers'); ?>
<?php $__env->startSection('heading','Teachers'); ?>

<?php $__env->startSection('content'); ?>
  <div class="grid grid-cols-4 gap-4">
    <div class="col-span-1">
      <div class="flex items-center justify-between mb-2">
        <?php
          $teacherCount = 0;
          if (isset($teachers)) {
            if (method_exists($teachers,'total')) $teacherCount = $teachers->total();
            else if (is_countable($teachers)) $teacherCount = count($teachers);
          }
        ?>
        <h2 class="text-lg font-semibold">Teachers (<span id="teacher-count"><?php echo e($teacherCount); ?></span>)</h2>
        <div class="flex gap-2">
          <button id="btn-export" class="px-3 py-1 bg-green-600 text-white rounded text-sm hover:bg-green-700">Export CSV</button>
          <button id="btn-import" class="px-3 py-1 bg-blue-600 text-white rounded text-sm hover:bg-blue-700">Import CSV</button>
          <button id="btn-add" class="px-3 py-1 bg-[#3b4197] text-white rounded text-sm">+ Add</button>
        </div>
      </div>

      <form method="GET" class="mb-3" id="teacher-search-form" onsubmit="return false;">
        <div class="mb-2">
          <div class="relative">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
              <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                <circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="2" fill="none" />
                <line x1="16.5" y1="16.5" x2="21" y2="21" stroke="currentColor" stroke-width="2" stroke-linecap="round" />
              </svg>
            </span>
            <input id="teacher-search-input" name="q" value="<?php echo e(old('q', $q ?? request('q'))); ?>" placeholder="Search by name or staff ID" class="block w-full border border-gray-300 rounded-md p-2 h-9 pl-10" autocomplete="off" />
          </div>
        </div>
        <div class="flex gap-2 items-center mb-2">
          <label for="sort-select" class="text-xs font-medium">Sort:</label>
          <select id="sort-select" name="sort" class="border border-gray-300 rounded-md p-2 h-9 text-sm bg-white">
            <option value="name_asc">Name (A-Z)</option>
            <option value="name_desc">Name (Z-A)</option>
            <option value="workload_desc">Workload (High-Low)</option>
            <option value="workload_asc">Workload (Low-High)</option>
          </select>
          <button type="button" id="toggle-filters" class="px-3 py-1 bg-slate-200 text-slate-700 rounded text-sm">Filters</button>
        </div>
        <div id="filters-section" class="space-y-2 hidden">
          <div class="relative mb-2">
            <div id="designation-dropdown" class="custom-dropdown" tabindex="0">
              <div class="dropdown-selected" id="designation-selected">Select Designation</div>
              <div class="dropdown-menu hidden" id="designation-menu">
                <?php $__currentLoopData = $designations ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $designation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <div class="dropdown-item" data-value="<?php echo e($designation); ?>" data-label="<?php echo e($designation); ?>"><?php echo e($designation); ?></div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </div>
            </div>
          </div>
          <div class="relative mb-2">
            <div id="status-dropdown" class="custom-dropdown" tabindex="0">
              <div class="dropdown-selected" id="status-selected">Select Status</div>
              <div class="dropdown-menu hidden" id="status-menu">
                <?php $__currentLoopData = config('teachers.statuses', []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <div class="dropdown-item" data-value="<?php echo e($status); ?>" data-label="<?php echo e($status); ?>"><?php echo e($status); ?></div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </div>
            </div>
          </div>
          <div class="relative mb-2">
            <div id="subject-dropdown" class="custom-dropdown" tabindex="0">
              <div class="dropdown-selected" id="subject-selected">Select Subject</div>
              <div class="dropdown-menu hidden" id="subject-menu">
                <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <div class="dropdown-item" data-value="<?php echo e($subject->id); ?>" data-label="<?php echo e($subject->name); ?>"><?php echo e($subject->name); ?></div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </div>
            </div>
          </div>
          <div class="relative mb-2">
            <div id="grade-dropdown" class="custom-dropdown" tabindex="0">
              <div class="dropdown-selected" id="grade-selected">Select Grade Level</div>
              <div class="dropdown-menu hidden" id="grade-menu">
                <?php $__currentLoopData = $gradeLevels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gradeLevel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <div class="dropdown-item" data-value="<?php echo e($gradeLevel->id); ?>" data-label="<?php echo e($gradeLevel->name); ?>"><?php echo e($gradeLevel->name); ?></div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </div>
            </div>
          </div>
          <button id="clear-filters-button" type="button" class="w-full px-3 py-1 bg-slate-100 text-slate-600 rounded text-sm hover:bg-slate-200">
              <div id="active-filter-chips" class="flex flex-wrap gap-2 mt-2"></div>
                <style>
                  .custom-dropdown { position: relative; width: 100%; cursor: pointer; }
                  .dropdown-selected { border: 1px solid #cbd5e1; border-radius: 0.375rem; padding: 0.5rem; background: #fff; min-height: 2.25rem; }
                  .dropdown-menu { position: absolute; left: 0; right: 0; top: 100%; background: #fff; border: 1px solid #cbd5e1; border-radius: 0.375rem; z-index: 10; max-height: 180px; overflow-y: auto; box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
                  .dropdown-item { padding: 0.5rem; cursor: pointer; }
                  .dropdown-item:hover { background: #e0e7ff; }
                  .dropdown-item.selected { background: #3b82f6; color: #fff; }
                </style>
            Clear Filters
          </button>
        </div>
      </form>
      <script>

              // Use global arrays for filter state
              const designationValues = [];
              const statusValues = [];
              const subjectValues = [];
              const gradeValues = [];

              function setupDropdown(dropdownId, menuId, selectedId, valuesArr) {
                const dropdown = document.getElementById(dropdownId);
                const menu = document.getElementById(menuId);
                const selected = document.getElementById(selectedId);
                dropdown.addEventListener('click', function(e) {
                  menu.classList.toggle('hidden');
                });
                // Only close menu when clicking outside
                document.addEventListener('mousedown', function(e) {
                  if (!dropdown.contains(e.target) && !menu.contains(e.target)) {
                    menu.classList.add('hidden');
                  }
                });
                menu.querySelectorAll('.dropdown-item').forEach(item => {
                  item.addEventListener('click', function(e) {
                    e.stopPropagation(); // Prevent menu from closing
                    const val = this.dataset.value;
                    const idx = valuesArr.indexOf(val);
                    if (idx !== -1) {
                      valuesArr.splice(idx, 1);
                      this.classList.remove('selected');
                    } else {
                      valuesArr.push(val);
                      this.classList.add('selected');
                    }
                    selected.textContent = valuesArr.length ? valuesArr.join(', ') : selected.getAttribute('data-placeholder') || 'Select';
                    renderFilterChips();
                    applyFilters();
                  });
                });
              }

              setupDropdown('designation-dropdown', 'designation-menu', 'designation-selected', designationValues);
              setupDropdown('status-dropdown', 'status-menu', 'status-selected', statusValues);
              setupDropdown('subject-dropdown', 'subject-menu', 'subject-selected', subjectValues);
              setupDropdown('grade-dropdown', 'grade-menu', 'grade-selected', gradeValues);

              function getDesignation() { return designationValues; }
              function getStatus() { return statusValues; }
              function getSubject() { return subjectValues; }
              function getGrade() { return gradeValues; }

              function renderFilterChips() {
                const container = document.getElementById('active-filter-chips');
                container.innerHTML = '';
                const fields = [
                  {name:'designation', values:getDesignation(), menu:'designation-menu'},
                  {name:'status', values:getStatus(), menu:'status-menu'},
                  {name:'subject', values:getSubject(), menu:'subject-menu'},
                  {name:'grade_level', values:getGrade(), menu:'grade-menu'}
                ];
                fields.forEach(field => {
                  field.values.forEach(val => {
                    let label = val;
                    // For subject and grade_level, get label from dropdown
                    if (field.menu && (field.name === 'subject' || field.name === 'grade_level')) {
                      const menu = document.getElementById(field.menu);
                      if (menu) {
                        const item = menu.querySelector(`.dropdown-item[data-value='${val}']`);
                        if (item && item.dataset.label) label = item.dataset.label;
                      }
                    }
                    const chip = document.createElement('span');
                    chip.className = 'bg-blue-100 text-blue-800 px-3 py-1 rounded-full flex items-center mr-2 mb-2';
                    chip.innerHTML = `${label} <button class='ml-2 text-blue-500 hover:text-blue-700' onclick='removeChip("${field.name}", "${val}")'>&times;</button>`;
                    container.appendChild(chip);
                  });
                });
              }

              window.removeChip = function(field, value) {
                let menu;
                if (field === 'designation') {
                  menu = document.getElementById('designation-menu');
                  const idx = designationValues.indexOf(value);
                  if (idx !== -1) designationValues.splice(idx, 1);
                } else if (field === 'status') {
                  menu = document.getElementById('status-menu');
                  const idx = statusValues.indexOf(value);
                  if (idx !== -1) statusValues.splice(idx, 1);
                } else if (field === 'subject') {
                  menu = document.getElementById('subject-menu');
                  const idx = subjectValues.indexOf(value);
                  if (idx !== -1) subjectValues.splice(idx, 1);
                } else if (field === 'grade_level') {
                  menu = document.getElementById('grade-menu');
                  const idx = gradeValues.indexOf(value);
                  if (idx !== -1) gradeValues.splice(idx, 1);
                }
                if (menu) {
                  menu.querySelectorAll('.dropdown-item').forEach(item => {
                    if (item.dataset.value === value) {
                      item.classList.remove('selected');
                    }
                  });
                }
                renderFilterChips();
                applyFilters();
              }

              // Update filter logic to use new dropdowns
              async function applyFilters() {
                const q = searchInput ? searchInput.value : '';
                const designation = getDesignation();
                const status = getStatus();
                const subject = getSubject();
                const gradeLevel = getGrade();
                const sortVal = document.getElementById('sort-select')?.value || 'name_asc';

                const params = new URLSearchParams();
                if (q) params.set('q', q);
                designation.forEach(val => { if(val) params.append('designation[]', val); });
                status.forEach(val => { if(val) params.append('status_of_appointment[]', val); });
                subject.forEach(val => { if(val) params.append('subject[]', val); });
                gradeLevel.forEach(val => { if(val) params.append('grade_level[]', val); });
                if (sortVal) params.set('sort', sortVal);

                const url = listUrl + '?' + params.toString();

                try {
                  const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                  if (!res.ok) throw new Error('Filter failed');
                  const data = await res.json();
                  const teacherList = document.getElementById('teacher-list');
                  if (teacherList) {
                    teacherList.innerHTML = data.html || '';
                    if (data.html) attachRowHandlers();
                  }
                  // Update teacher count if present
                  if (typeof data.count !== 'undefined') {
                    const countEl = document.getElementById('teacher-count');
                    if (countEl) countEl.textContent = data.count;
                  }
                } catch (e) {
                  console.error('Filter failed:', e);
                }
              }
        // Toggle filters section
        const toggleFiltersBtn = document.getElementById('toggle-filters');
        const filtersSection = document.getElementById('filters-section');
        if (toggleFiltersBtn && filtersSection) {
          toggleFiltersBtn.addEventListener('click', function() {
            filtersSection.classList.toggle('hidden');
          });
        }
        // Sorting functionality
        const sortSelect = document.getElementById('sort-select');
        if (sortSelect) {
          sortSelect.addEventListener('change', function() {
            applyFilters();
          });
        }
      </script>

      <div class="overflow-y-auto" style="max-height:calc(100vh - 140px);">
        <ul id="teacher-list" class="space-y-2">
          <?php echo $__env->make('admin.teachers._list', ['teachers' => $teachers], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </ul>
      </div>

      <div class="mt-4" id="teacher-pagination" style="display:block"><?php echo e($teachers->links()); ?></div>
    </div>

    <div class="col-span-3">
      <div id="detail-pane" class="p-4 border rounded min-h-[400px]">
        
        <?php echo $__env->make('admin.teachers._form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
      </div>
    </div>
  </div>

    <div id="teachers-urls" style="display:none"
      data-fragment-url="<?php echo e(url('admin/teachers/fragment')); ?>"
      data-base-url="<?php echo e(url('admin/teachers')); ?>"
      data-list-url="<?php echo e(url('admin/teachers/list')); ?>"
      data-next-url="<?php echo e($teachers->nextPageUrl()); ?>"
      data-csrf="<?php echo e(csrf_token()); ?>"></div>

  <script>
    const _teachersHooks = document.getElementById('teachers-urls');
    const TEACHERS_FRAGMENT_URL = _teachersHooks ? _teachersHooks.dataset.fragmentUrl : '/admin/teachers/fragment';
    const TEACHERS_BASE_URL = _teachersHooks ? _teachersHooks.dataset.baseUrl : '/admin/teachers';

    async function loadFragment(url) {
      const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
      if (!res.ok) throw new Error('Failed to load');
      return await res.text();
    }

    async function loadTeacher(id) {
      const pane = document.getElementById('detail-pane');
      pane.innerHTML = 'Loading...';
      document.querySelectorAll('.teacher-row').forEach(el => el.classList.remove('bg-slate-100'));
      const row = document.querySelector(`#teacher-list [data-id='${id}']`);
      if (row) row.classList.add('bg-slate-100');
      try {
        const html = await loadFragment(TEACHERS_BASE_URL + '/' + id + '/fragment');
        pane.innerHTML = html;
        // execute any inline scripts returned with the fragment
        Array.from(pane.querySelectorAll('script')).forEach(old => {
          const s = document.createElement('script');
          if (old.src) s.src = old.src; else s.textContent = old.textContent;
          document.head.appendChild(s);
          old.parentNode.removeChild(old);
        });
        window.scrollTo(0,0);
      } catch (e) {
        pane.innerHTML = '<div class="text-red-600">Unable to load teacher.</div>';
        console.error(e);
      }
    }

    window.addEventListener('DOMContentLoaded', function() {
      const addBtn = document.getElementById('btn-add');
      if (addBtn) addBtn.addEventListener('click', async function(){
        const pane = document.getElementById('detail-pane');
        pane.innerHTML = 'Loading...';
        try {
          const html = await loadFragment(TEACHERS_FRAGMENT_URL);
          pane.innerHTML = html;
          Array.from(pane.querySelectorAll('script')).forEach(old => {
            const s = document.createElement('script');
            if (old.src) s.src = old.src; else s.textContent = old.textContent;
            document.head.appendChild(s);
            old.parentNode.removeChild(old);
          });
        } catch(e){ pane.innerHTML = '<div class="text-red-600">Unable to load form.</div>'; console.error(e);} 
      });

      function attachRowHandlers(root){
        const rows = (root || document).querySelectorAll('.teacher-row');
        rows.forEach(el => {
          if (el.dataset.attached) return;
          el.addEventListener('click', function(){ const id = this.getAttribute('data-id'); if (id) loadTeacher(id); });
          el.dataset.attached = '1';
        });
      }
      attachRowHandlers();

      // Search and filter functionality
      const searchBtn = document.getElementById('teacher-search-button');
      const searchInput = document.getElementById('teacher-search-input');
      const filterDesignation = document.getElementById('filter-designation');
      const filterSubject = document.getElementById('filter-subject');
      const filterGradeLevel = document.getElementById('filter-grade-level');
      const filterStatus = document.getElementById('filter-status');
      const clearFiltersBtn = document.getElementById('clear-filters-button');
      const listUrl = _teachersHooks ? _teachersHooks.dataset.listUrl : '/admin/teachers/list';

      async function applyFilters() {
        const q = searchInput ? searchInput.value : '';
        const designation = filterDesignation ? Array.from(filterDesignation.selectedOptions).map(opt => opt.value) : [];
        const status = filterStatus ? Array.from(filterStatus.selectedOptions).map(opt => opt.value) : [];
        const subject = filterSubject ? Array.from(filterSubject.selectedOptions).map(opt => opt.value) : [];
        const gradeLevel = filterGradeLevel ? Array.from(filterGradeLevel.selectedOptions).map(opt => opt.value) : [];
        const sortVal = window._sortVal || document.getElementById('sort-select')?.value || '';

        const params = new URLSearchParams();
        if (q) params.set('q', q);
        designation.forEach(val => { if(val) params.append('designation[]', val); });
        status.forEach(val => { if(val) params.append('status_of_appointment[]', val); });
        subject.forEach(val => { if(val) params.append('subject[]', val); });
        gradeLevel.forEach(val => { if(val) params.append('grade_level[]', val); });
        if (sortVal) params.set('sort', sortVal);

        const url = listUrl + '?' + params.toString();

        // Render chips
        renderFilterChips({designation, status, subject, gradeLevel});

        try {
          const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
          if (!res.ok) throw new Error('Filter failed');
          const data = await res.json();
          
          const teacherList = document.getElementById('teacher-list');
          if (teacherList && data.html) {
            teacherList.innerHTML = data.html;
            attachRowHandlers();
          }
          // Update teacher count if present
          if (typeof data.count !== 'undefined') {
            const countEl = document.getElementById('teacher-count');
            if (countEl) countEl.textContent = data.count;
          }
        } catch (e) {
          console.error('Filter failed:', e);
        }
      }

      function renderFilterChips(filters) {
        const container = document.getElementById('active-filter-chips');
        container.innerHTML = '';
        Object.entries(filters).forEach(([field, values]) => {
          values.forEach(val => {
            if (val) {
              const chip = document.createElement('span');
              chip.className = 'bg-blue-100 text-blue-800 px-3 py-1 rounded-full flex items-center mr-2 mb-2';
              chip.innerHTML = `${val} <button class='ml-2 text-blue-500 hover:text-blue-700' onclick='removeChip("${field}", "${val}")'>&times;</button>`;
              container.appendChild(chip);
            }
          });
        });
      }

      window.removeChip = function(field, value) {
        let select;
        if (field === 'designation') select = filterDesignation;
        else if (field === 'status') select = filterStatus;
        else if (field === 'subject') select = filterSubject;
        else if (field === 'gradeLevel') select = filterGradeLevel;
        if (select) {
          Array.from(select.options).forEach(opt => {
            if (opt.value === value) opt.selected = false;
          });
        }
        applyFilters();
      }

      if (searchInput) {
        let debounceTimer = null;
        searchInput.addEventListener('input', function() {
          clearTimeout(debounceTimer);
          debounceTimer = setTimeout(applyFilters, 250);
        });
        searchInput.addEventListener('keypress', function(e) {
          if (e.key === 'Enter') {
            e.preventDefault();
            applyFilters();
          }
        });
      }

      if (filterDesignation) {
        filterDesignation.addEventListener('change', applyFilters);
      }


      if (filterStatus) {
        filterStatus.addEventListener('change', applyFilters);
      }
      if (filterSubject) {
        filterSubject.addEventListener('change', applyFilters);
      }
      if (filterGradeLevel) {
        filterGradeLevel.addEventListener('change', applyFilters);
      }

      if (clearFiltersBtn) {
        clearFiltersBtn.addEventListener('click', function() {
          if (searchInput) searchInput.value = '';
          if (filterDesignation) filterDesignation.value = '';
          if (filterStatus) filterStatus.value = '';
          if (filterSubject) filterSubject.value = '';
          if (filterGradeLevel) filterGradeLevel.value = '';
          applyFilters();
        });
      }

      (async function loadInitialForm(){
        const pane = document.getElementById('detail-pane');
        pane.innerHTML = 'Loading form...';
        try{
          const html = await loadFragment(TEACHERS_FRAGMENT_URL);
          pane.innerHTML = html;
          Array.from(pane.querySelectorAll('script')).forEach(old => {
            const s = document.createElement('script');
            if (old.src) s.src = old.src; else s.textContent = old.textContent;
            document.head.appendChild(s);
            old.parentNode.removeChild(old);
          });
        } catch(e){ pane.innerHTML = '<div class="text-red-600">Unable to load form.</div>'; console.error(e); }
      })();

      (function initInfiniteScroll(){
        const hooks = document.getElementById('teachers-urls'); if(!hooks) return; const listUrl = hooks.dataset.listUrl || '/admin/teachers/list'; let nextUrl = hooks.dataset.nextUrl || null; const container = document.querySelector('.overflow-y-auto'); const list = document.getElementById('teacher-list'); let loading = false;
        async function loadNext(){ if (!nextUrl || loading) return; loading = true; try{ const res = await fetch(nextUrl, { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } }); if (!res.ok) throw new Error('Failed to load next page'); const data = await res.json(); if (data.html) { const tmp = document.createElement('div'); tmp.innerHTML = data.html; Array.from(tmp.children).forEach(ch => list.appendChild(ch)); document.querySelectorAll('.teacher-row').forEach(el => { if (!el.dataset.attached) { el.addEventListener('click', function(){ const id = this.getAttribute('data-id'); if(id) loadTeacher(id); }); el.dataset.attached = '1'; } }); } nextUrl = data.next || null; if (!nextUrl) { const pag = document.getElementById('teacher-pagination'); if(pag) pag.style.display='none'; } } catch(e){ console.error('Infinite scroll load failed', e); } loading = false; }
        if(container){ container.addEventListener('scroll', function(){ if (!nextUrl || loading) return; const threshold = 120; if (container.scrollTop + container.clientHeight >= container.scrollHeight - threshold){ loadNext(); } }); }
      })();

      (function initLiveSearch(){
        const hooks = document.getElementById('teachers-urls'); if(!hooks) return; const listUrlBase = hooks.dataset.listUrl || '/admin/teachers/list'; const searchInput = document.getElementById('teacher-search-input'); const searchBtn = document.getElementById('teacher-search-button'); const list = document.getElementById('teacher-list'); const container = document.querySelector('.overflow-y-auto'); let debounceTimer = null;
        async function fetchAndReplace(q){ try{ const url = new URL(listUrlBase, window.location.origin); if(q) url.searchParams.set('q', q); const res = await fetch(url.toString(), { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } }); if(!res.ok) throw new Error('Search fetch failed'); const data = await res.json(); if(data.html){ list.innerHTML = data.html; hooks.dataset.nextUrl = data.next || ''; document.querySelectorAll('.teacher-row').forEach(el => { el.addEventListener('click', function(){ const id = this.getAttribute('data-id'); if(id) loadTeacher(id); }); }); if(container) container.scrollTop = 0; const newUrl = new URL(window.location.href); if(q) newUrl.searchParams.set('q', q); else newUrl.searchParams.delete('q'); history.replaceState({}, '', newUrl.toString()); } } catch(e){ console.error('Live search error', e); } }
        function scheduleFetch(){ const q = (searchInput && searchInput.value) ? searchInput.value.trim() : ''; clearTimeout(debounceTimer); debounceTimer = setTimeout(()=> fetchAndReplace(q), 300); }
        if(searchInput){ searchInput.addEventListener('input', scheduleFetch); searchInput.addEventListener('keydown', function(e){ if(e.key==='Enter'){ e.preventDefault(); scheduleFetch(); } }); }
        if(searchBtn){ searchBtn.addEventListener('click', scheduleFetch); }
      })();

      // Export CSV functionality
      const exportBtn = document.getElementById('btn-export');
      if (exportBtn) {
        exportBtn.addEventListener('click', function() {
          window.location.href = '<?php echo e(route("admin.teachers.export")); ?>';
        });
      }

      // Import CSV functionality
      const importBtn = document.getElementById('btn-import');
      if (importBtn) {
        importBtn.addEventListener('click', function() {
          document.getElementById('import-modal').classList.remove('hidden');
        });
      }
    });
  </script>

  <!-- Import Modal -->
  <div id="import-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-2xl w-full mx-4">
      <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold">Import Teachers from CSV</h3>
        <button id="close-import-modal" class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
      </div>

      <div class="mb-4">
        <p class="text-sm text-gray-600 mb-2">Upload a CSV file to bulk import teachers. The file should have the following columns:</p>
        <div class="bg-gray-50 p-3 rounded text-xs font-mono overflow-x-auto">
          staff_id,name,sex,designation,status_of_appointment,email,phone,course_degree,course_major,course_minor,subjects,grade_levels
        </div>
        <p class="text-xs text-gray-500 mt-2">
          <strong>Note:</strong> subjects and grade_levels should be comma-separated IDs or names
        </p>
      </div>

      <form id="import-form" method="POST" action="<?php echo e(route('admin.teachers.import')); ?>" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <div class="mb-4">
          <label class="block text-sm font-medium mb-2">Select CSV File</label>
          <input type="file" name="csv_file" accept=".csv" required class="block w-full border border-gray-300 rounded-md p-2" />
        </div>

        <div class="flex gap-2">
          <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Upload & Import</button>
          <button type="button" id="cancel-import" class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300">Cancel</button>
          <a href="<?php echo e(route('admin.teachers.download-template')); ?>" class="ml-auto px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Download Template</a>
        </div>
      </form>
    </div>
  </div>

  <script>
    const importModal = document.getElementById('import-modal');
    const closeImportModal = document.getElementById('close-import-modal');
    const cancelImport = document.getElementById('cancel-import');

    if (closeImportModal) {
      closeImportModal.addEventListener('click', () => importModal.classList.add('hidden'));
    }
    if (cancelImport) {
      cancelImport.addEventListener('click', () => importModal.classList.add('hidden'));
    }
  </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\TuroTugma\resources\views/admin/teachers/index.blade.php ENDPATH**/ ?>