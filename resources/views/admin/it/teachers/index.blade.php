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

      <div class="overflow-y-auto" style="max-height:70vh;">
        <ul id="teacher-list" class="space-y-2">
          @foreach($teachers as $t)
            <li data-id="{{ $t->id }}" class="p-2 border rounded cursor-pointer teacher-row bg-white hover:bg-slate-50" onclick="loadTeacher({{ $t->id }})">
              <div class="font-medium">{{ $t->name }}</div>
              <div class="text-xs text-slate-600">{{ $t->staff_id }}</div>
            </li>
          @endforeach
        </ul>
      </div>

      <div class="mt-4">{{ $teachers->links() }}</div>
    </div>

    <div class="col-span-3">
      <div id="detail-pane" class="p-4 border rounded min-h-[400px]">
        {{-- initial: load create form fragment --}}
        <div class="text-sm text-slate-600">Select a teacher from the left to view or edit their profile. Click "+ Add" to create a new teacher.</div>
      </div>
    </div>
  </div>

  <script>
    // Global helper to fetch HTML fragments
    async function loadFragment(url) {
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
        const html = await loadFragment('{{ url("admin/it/teachers") }}/' + id + '/fragment');
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
          const pane = document.getElementById('detail-pane');
          pane.innerHTML = 'Loading...';
          try {
            const html = await loadFragment('{{ url("admin/it/teachers/fragment") }}');
            pane.innerHTML = html;
          } catch (e) {
            pane.innerHTML = '<div class="text-red-600">Unable to load form.</div>';
            console.error(e);
          }
        });
      }

      // Auto-load first teacher
      const first = document.querySelector('#teacher-list li[data-id]');
      if (first) {
        const id = first.getAttribute('data-id');
        loadTeacher(id);
      }
    });
  </script>

@endsection
