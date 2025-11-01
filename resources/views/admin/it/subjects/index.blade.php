@extends('admin.it.layout')

@section('title','Subjects')
@section('heading','Subjects')

@section('content')
  <div class="grid grid-cols-4 gap-4">
    <div class="col-span-1">
      <div class="flex items-center justify-between mb-2">
        <h2 class="text-lg font-semibold">Subjects</h2>
        <button id="btn-add-subject" class="px-3 py-1 bg-[#3b4197] text-white rounded text-sm">+ Add</button>
      </div>

      <div class="overflow-y-auto" style="max-height:70vh;">
        <ul id="subject-list" class="space-y-2">
          @foreach($subjects as $s)
            <li data-id="{{ $s->id }}" class="p-2 border rounded cursor-pointer subject-row bg-white hover:bg-slate-50" onclick="loadSubject({{ $s->id }})">
              <div class="font-medium">{{ $s->name }}</div>
              <div class="text-xs text-slate-600">{{ $s->code }} • {{ $s->type }}</div>
            </li>
          @endforeach
        </ul>
      </div>

      <div class="mt-4">{{ $subjects->links() }}</div>
    </div>

    <div class="col-span-3">
      <div id="subject-detail" class="p-4 border rounded min-h-[400px]">
        <div class="text-sm text-slate-600">Select a subject to view/edit. Click "+ Add" to create a new subject.</div>
      </div>
    </div>
  </div>

  <script>
    async function loadFragment(url) {
      const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
      if (!res.ok) throw new Error('Failed to load');
      return await res.text();
    }

    async function loadSubject(id) {
      const pane = document.getElementById('subject-detail');
      pane.innerHTML = 'Loading...';
      document.querySelectorAll('.subject-row').forEach(el => el.classList.remove('bg-slate-100'));
      const row = document.querySelector(`#subject-list [data-id='${id}']`);
      if (row) row.classList.add('bg-slate-100');
      try {
        const html = await loadFragment('{{ url("admin/it/subjects") }}/' + id + '/fragment');
        pane.innerHTML = html;
      } catch (e) {
        pane.innerHTML = '<div class="text-red-600">Unable to load subject.</div>';
        console.error(e);
      }
    }

    window.addEventListener('DOMContentLoaded', function(){
      const addBtn = document.getElementById('btn-add-subject');
      if (addBtn) addBtn.addEventListener('click', async function(){
        const pane = document.getElementById('subject-detail');
        pane.innerHTML = 'Loading...';
        try {
          const html = await loadFragment('{{ url("admin/it/subjects/fragment") }}');
          pane.innerHTML = html;
        } catch (e) {
          pane.innerHTML = '<div class="text-red-600">Unable to load form.</div>';
          console.error(e);
        }
      });

      // Auto-load first subject
      const first = document.querySelector('#subject-list li[data-id]');
      if (first) loadSubject(first.getAttribute('data-id'));

      // Intercept form submits inside the detail pane to use AJAX
      const pane = document.getElementById('subject-detail');
      pane.addEventListener('submit', async function(e) {
        if (!(e.target instanceof HTMLFormElement)) return;
        e.preventDefault();
        const form = e.target;
        const url = form.action;
        const method = (form.querySelector('input[name="_method"]') || {}).value || form.method || 'POST';

        const fd = new FormData(form);
        try {
          const res = await fetch(url, {
            method: method.toUpperCase(),
            headers: {
              'X-Requested-With': 'XMLHttpRequest',
              'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: fd
          });

          if (res.status === 422) {
            const json = await res.json();
            // show validation errors at top of pane
            const errors = json.errors || {};
            let html = '<div class="p-3 mb-3 bg-red-50 text-red-700">';
            Object.keys(errors).forEach(k => {
              errors[k].forEach(msg => { html += '<div>'+msg+'</div>'; });
            });
            html += '</div>';
            pane.insertAdjacentHTML('afterbegin', html);
            return;
          }

          if (!res.ok) throw new Error('Save failed');

          const payload = await res.json();
          const s = payload.subject;

          // update or insert list item
          let li = document.querySelector(`#subject-list li[data-id='${s.id}']`);
          const liHtml = `<div class="font-medium">${s.name}</div><div class="text-xs text-slate-600">${s.code || ''} • ${s.type || ''}</div>`;
          if (li) {
            li.innerHTML = liHtml;
          } else {
            li = document.createElement('li');
            li.setAttribute('data-id', s.id);
            li.className = 'p-2 border rounded cursor-pointer subject-row bg-white hover:bg-slate-50';
            li.setAttribute('onclick', `loadSubject(${s.id})`);
            li.innerHTML = liHtml;
            const list = document.getElementById('subject-list');
            list.insertBefore(li, list.firstChild);
          }

          // highlight and load edit fragment to show saved state
          li.classList.add('bg-slate-100');
          loadSubject(s.id);

        } catch (err) {
          console.error(err);
          pane.insertAdjacentHTML('afterbegin', '<div class="p-3 mb-3 bg-red-50 text-red-700">Unable to save subject.</div>');
        }
      });
    });
  </script>

@endsection
@extends('admin.it.layout')

@section('title','Subjects')
@section('heading','Subjects')

@section('content')
  <div class="p-4 border rounded">
    <p class="text-sm text-slate-600">Placeholder list of subjects. CRUD UI will be implemented here.</p>
  </div>
@endsection
