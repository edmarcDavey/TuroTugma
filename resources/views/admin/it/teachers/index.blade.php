@extends('admin.it.layout')

@section('title','Teachers')
@section('heading','Teachers')

@section('content')
  <div class="grid grid-cols-4 gap-4">
    <div class="col-span-1">
      <div class="flex items-center justify-between mb-2">
        <h2 class="text-lg font-semibold">Teachers</h2>
        <button id="btn-add" class="px-3 py-1 bg-[#3b4197] text-white rounded text-sm">+ Add</button>
      </div>

      <form method="GET" class="mb-3" id="teacher-search-form" onsubmit="return false;">
        <div class="flex gap-2">
          <input id="teacher-search-input" name="q" value="{{ old('q', $q ?? request('q')) }}" placeholder="Search teachers by name or staff ID" class="block w-full border border-gray-300 rounded-md p-2 h-9" autocomplete="off" />
          <button id="teacher-search-button" type="button" class="px-3 py-1 bg-slate-200 rounded">Search</button>
        </div>
      </form>

      <div class="overflow-y-auto" style="max-height:70vh;">
        <ul id="teacher-list" class="space-y-2">
          @foreach($teachers as $t)
            <li data-id="{{ $t->id }}" class="p-2 border rounded cursor-pointer teacher-row bg-white hover:bg-slate-50">
              <div class="font-medium">{{ $t->name }}</div>
              <div class="text-xs text-slate-600">{{ $t->staff_id }}</div>
            </li>
          @endforeach
        </ul>
      </div>

      <div class="mt-4" id="teacher-pagination" style="display:none">{{ $teachers->links() }}</div>
    </div>

    <div class="col-span-3">
      <div id="detail-pane" class="p-4 border rounded min-h-[400px]">
        {{-- Render the create form inline so it's visible immediately (no AJAX required) --}}
        @include('admin.it.teachers._form')
      </div>
    </div>
  </div>

    <div id="teachers-urls" style="display:none"
      data-fragment-url="{{ url('admin/it/teachers/fragment') }}"
      data-base-url="{{ url('admin/it/teachers') }}"
      data-list-url="{{ url('admin/it/teachers/list') }}"
      data-next-url="{{ $teachers->nextPageUrl() }}"
      data-csrf="{{ csrf_token() }}"></div>

  <script>
    // Read server-generated URLs from data attributes to avoid embedding Blade inside JS expressions
    const _teachersHooks = document.getElementById('teachers-urls');
    const TEACHERS_FRAGMENT_URL = _teachersHooks ? _teachersHooks.dataset.fragmentUrl : '/admin/it/teachers/fragment';
    const TEACHERS_BASE_URL = _teachersHooks ? _teachersHooks.dataset.baseUrl : '/admin/it/teachers';
    // Global helper to fetch HTML fragments
    async function loadFragment(url) {
      console.log('loadFragment: fetching', url);
      const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
      if (!res.ok) throw new Error('Failed to load');
      return await res.text();
    }

    // Load teacher fragment into detail pane and mark selected
    async function loadTeacher(id) {
      const pane = document.getElementById('detail-pane');
      pane.innerHTML = 'Loading...';

      // mark selected in left list
      document.querySelectorAll('.teacher-row').forEach(el => el.classList.remove('bg-slate-100'));
      const row = document.querySelector(`#teacher-list [data-id='${id}']`);
      if (row) row.classList.add('bg-slate-100');

      try {
  const html = await loadFragment(TEACHERS_BASE_URL + '/' + id + '/fragment');
        pane.innerHTML = html;
        window.scrollTo(0,0);
      } catch (e) {
        pane.innerHTML = '<div class="text-red-600">Unable to load teacher.</div>';
        console.error(e);
      }
    }

    // DOM ready: hook add button and auto-load first teacher if present
    window.addEventListener('DOMContentLoaded', function() {
      const addBtn = document.getElementById('btn-add');
      if (addBtn) {
        addBtn.addEventListener('click', async function(){
          console.log('Add button clicked');
          const pane = document.getElementById('detail-pane');
          pane.innerHTML = 'Loading...';
            try {
            const html = await loadFragment(TEACHERS_FRAGMENT_URL);
            pane.innerHTML = html;
          } catch (e) {
            pane.innerHTML = '<div class="text-red-600">Unable to load form.</div>';
            console.error(e);
          }
        });
      }

      // Attach click handlers to teacher rows so loadTeacher is invoked when a row is clicked
      function attachRowHandlers(root){
        const rows = (root || document).querySelectorAll('.teacher-row');
        rows.forEach(el => {
          if (el.dataset.attached) return;
          el.addEventListener('click', function(){
            const id = this.getAttribute('data-id');
            if (id) loadTeacher(id);
          });
          el.dataset.attached = '1';
        });
      }
      attachRowHandlers();

      // Load create form by default so the admin sees an empty form immediately
      (async function loadInitialForm(){
        const pane = document.getElementById('detail-pane');
        pane.innerHTML = 'Loading form...';
        try{
          const html = await loadFragment(TEACHERS_FRAGMENT_URL);
          pane.innerHTML = html;
        } catch(e){
          pane.innerHTML = '<div class="text-red-600">Unable to load form.</div>';
          console.error(e);
        }
      })();

      // Infinite scroll loader for the left panel
      (function initInfiniteScroll(){
        const hooks = document.getElementById('teachers-urls');
        if(!hooks) return;
        const listUrl = hooks.dataset.listUrl || '/admin/it/teachers/list';
        let nextUrl = hooks.dataset.nextUrl || null;
        const container = document.querySelector('.overflow-y-auto');
        const list = document.getElementById('teacher-list');
        let loading = false;

        async function loadNext(){
          if (!nextUrl || loading) return;
          loading = true;
          try{
            const res = await fetch(nextUrl, { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } });
            if (!res.ok) throw new Error('Failed to load next page');
            const data = await res.json();
            if (data.html) {
              // append nodes
              const tmp = document.createElement('div'); tmp.innerHTML = data.html;
              Array.from(tmp.children).forEach(ch => list.appendChild(ch));
              // reattach click handlers for newly added rows
              document.querySelectorAll('.teacher-row').forEach(el => {
                if (!el.dataset.attached) {
                  el.addEventListener('click', function(){ const id = this.getAttribute('data-id'); if(id) loadTeacher(id); });
                  el.dataset.attached = '1';
                }
              });
            }
            nextUrl = data.next || null;
            if (!nextUrl) {
              // hide pagination fallback
              const pag = document.getElementById('teacher-pagination'); if(pag) pag.style.display='none';
            }
          } catch(e){ console.error('Infinite scroll load failed', e); }
          loading = false;
        }

        if(container){
          container.addEventListener('scroll', function(){
            if (!nextUrl || loading) return;
            const threshold = 120; // px from bottom
            if (container.scrollTop + container.clientHeight >= container.scrollHeight - threshold){
              loadNext();
            }
          });
        }
      })();

      // Live search: debounce input and fetch first page of filtered list
      (function initLiveSearch(){
        const hooks = document.getElementById('teachers-urls');
        if(!hooks) return;
        const listUrlBase = hooks.dataset.listUrl || '/admin/it/teachers/list';
        const searchInput = document.getElementById('teacher-search-input');
        const searchBtn = document.getElementById('teacher-search-button');
        const list = document.getElementById('teacher-list');
        const container = document.querySelector('.overflow-y-auto');
        let debounceTimer = null;

        async function fetchAndReplace(q){
          try{
            const url = new URL(listUrlBase, window.location.origin);
            if(q) url.searchParams.set('q', q);
            const res = await fetch(url.toString(), { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } });
            if(!res.ok) throw new Error('Search fetch failed');
            const data = await res.json();
            if(data.html){
              list.innerHTML = data.html;
              // update next url for infinite scroll
              hooks.dataset.nextUrl = data.next || '';
              // reattach click handlers
              document.querySelectorAll('.teacher-row').forEach(el => {
                el.addEventListener('click', function(){ const id = this.getAttribute('data-id'); if(id) loadTeacher(id); });
              });
              // reset scroll to top of panel
              if(container) container.scrollTop = 0;
              // update browser url (replace state)
              const newUrl = new URL(window.location.href);
              if(q) newUrl.searchParams.set('q', q); else newUrl.searchParams.delete('q');
              history.replaceState({}, '', newUrl.toString());
            }
          } catch(e){ console.error('Live search error', e); }
        }

        function scheduleFetch(){
          const q = (searchInput && searchInput.value) ? searchInput.value.trim() : '';
          clearTimeout(debounceTimer);
          debounceTimer = setTimeout(()=> fetchAndReplace(q), 300);
        }

        if(searchInput){
          searchInput.addEventListener('input', scheduleFetch);
          searchInput.addEventListener('keydown', function(e){ if(e.key==='Enter'){ e.preventDefault(); scheduleFetch(); } });
        }
        if(searchBtn){ searchBtn.addEventListener('click', scheduleFetch); }
      })();
    });
  </script>

@endsection
