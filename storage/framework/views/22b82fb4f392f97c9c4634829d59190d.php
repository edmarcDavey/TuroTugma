

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
        <h2 class="text-lg font-semibold">Teachers (<?php echo e($teacherCount); ?>)</h2>
        <div class="flex gap-2">
          <button id="btn-export" class="px-3 py-1 bg-green-600 text-white rounded text-sm hover:bg-green-700">Export CSV</button>
          <button id="btn-import" class="px-3 py-1 bg-blue-600 text-white rounded text-sm hover:bg-blue-700">Import CSV</button>
          <button id="btn-add" class="px-3 py-1 bg-[#3b4197] text-white rounded text-sm">+ Add</button>
        </div>
      </div>

      <form method="GET" class="mb-3" id="teacher-search-form" onsubmit="return false;">
        <div class="flex gap-2 mb-2">
          <input id="teacher-search-input" name="q" value="<?php echo e(old('q', $q ?? request('q'))); ?>" placeholder="Search by name or staff ID" class="block w-full border border-gray-300 rounded-md p-2 h-9" autocomplete="off" />
          <button id="teacher-search-button" type="button" class="px-3 py-1 bg-slate-200 rounded whitespace-nowrap">Search</button>
        </div>

        <!-- Filters -->
        <div class="space-y-2">
          <select id="filter-designation" name="designation" class="block w-full border border-gray-300 rounded-md p-2 h-9 text-sm bg-white">
            <option value="">All Designations</option>
            <?php $__currentLoopData = $designations ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $designation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <option value="<?php echo e($designation); ?>" <?php if(($filterDesignation ?? '') === $designation): ?> selected <?php endif; ?>><?php echo e($designation); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </select>

          <select id="filter-subject" name="subject" class="block w-full border border-gray-300 rounded-md p-2 h-9 text-sm bg-white">
            <option value="">All Subjects</option>
            <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <option value="<?php echo e($subject->id); ?>" <?php if(($filterSubject ?? '') == $subject->id): ?> selected <?php endif; ?>><?php echo e($subject->name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </select>

          <select id="filter-grade-level" name="grade_level" class="block w-full border border-gray-300 rounded-md p-2 h-9 text-sm bg-white">
            <option value="">All Grade Levels</option>
            <?php $__currentLoopData = $gradeLevels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gradeLevel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <option value="<?php echo e($gradeLevel->id); ?>" <?php if(($filterGradeLevel ?? '') == $gradeLevel->id): ?> selected <?php endif; ?>><?php echo e($gradeLevel->name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </select>

          <button id="clear-filters-button" type="button" class="w-full px-3 py-1 bg-slate-100 text-slate-600 rounded text-sm hover:bg-slate-200">
            Clear Filters
          </button>
        </div>
      </form>

      <div class="overflow-y-auto" style="max-height:calc(100vh - 140px);">
        <ul id="teacher-list" class="space-y-2">
          <?php $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li data-id="<?php echo e($t->id); ?>" class="p-2 border rounded cursor-pointer teacher-row bg-white hover:bg-slate-50">
              <div class="font-medium"><?php echo e($t->name); ?></div>
              <div class="text-xs text-slate-600"><?php echo e($t->staff_id); ?></div>
            </li>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
      </div>

      <div class="mt-4" id="teacher-pagination" style="display:none"><?php echo e($teachers->links()); ?></div>
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
      const clearFiltersBtn = document.getElementById('clear-filters-button');
      const listUrl = _teachersHooks ? _teachersHooks.dataset.listUrl : '/admin/teachers/list';

      async function applyFilters() {
        const q = searchInput ? searchInput.value : '';
        const designation = filterDesignation ? filterDesignation.value : '';
        const subject = filterSubject ? filterSubject.value : '';
        const gradeLevel = filterGradeLevel ? filterGradeLevel.value : '';

        const params = new URLSearchParams();
        if (q) params.set('q', q);
        if (designation) params.set('designation', designation);
        if (subject) params.set('subject', subject);
        if (gradeLevel) params.set('grade_level', gradeLevel);

        const url = listUrl + '?' + params.toString();

        try {
          const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
          if (!res.ok) throw new Error('Filter failed');
          const data = await res.json();
          
          const teacherList = document.getElementById('teacher-list');
          if (teacherList && data.html) {
            teacherList.innerHTML = data.html;
            attachRowHandlers();
          }
        } catch (e) {
          console.error('Filter failed:', e);
        }
      }

      if (searchBtn) {
        searchBtn.addEventListener('click', applyFilters);
      }

      if (searchInput) {
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
  <div id="import-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
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