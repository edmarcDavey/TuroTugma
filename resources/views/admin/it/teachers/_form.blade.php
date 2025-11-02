@php
  $isEdit = isset($teacher) && $teacher;
  $action = $isEdit ? route('admin.it.teachers.update', $teacher) : route('admin.it.teachers.store');
  $selectedSubjects = old('subjects', $isEdit && isset($teacher) && isset($teacher->subjects) ? $teacher->subjects->pluck('id')->toArray() : []);
@endphp

<form method="POST" action="{{ $action }}">
  @csrf
  @if($isEdit) @method('PUT') @endif

  <div class="space-y-4">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
      <div class="md:col-span-2">
        <label class="block text-sm font-medium">Name</label>
  <input name="name" required value="{{ old('name', $teacher->name ?? '') }}" class="mt-1 block w-full border border-gray-300 rounded-md p-2 h-11 bg-white box-border" />
      </div>

      <div class="md:col-span-1">
        <label class="block text-sm font-medium">Sex</label>
  <select name="sex" class="mt-1 block w-full border border-gray-300 rounded-md p-2 h-11 bg-white box-border">
          <option value="">-- select --</option>
          <option value="male" @if(old('sex', $teacher->sex ?? '') === 'male') selected @endif>Male</option>
          <option value="female" @if(old('sex', $teacher->sex ?? '') === 'female') selected @endif>Female</option>
          <option value="other" @if(old('sex', $teacher->sex ?? '') === 'other') selected @endif>Other</option>
        </select>
      </div>

      <div class="md:col-span-1">
        <label class="block text-sm font-medium">Employee ID</label>
  <input name="staff_id" value="{{ old('staff_id', $teacher->staff_id ?? '') }}" class="mt-1 block w-full border border-gray-300 rounded-md p-2 h-11 bg-white box-border" />
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
      <div>
        <label class="block text-sm font-medium">Designation / Position</label>
  <input name="designation" value="{{ old('designation', $teacher->designation ?? '') }}" class="mt-1 block w-full border border-gray-300 rounded-md p-2 h-11 bg-white box-border" />
      </div>

      <div>
        <label class="block text-sm font-medium">Status of Appointment</label>
  <input name="status_of_appointment" value="{{ old('status_of_appointment', $teacher->status_of_appointment ?? '') }}" class="mt-1 block w-full border border-gray-300 rounded-md p-2 h-11 bg-white box-border" />
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
      <div>
        <label class="block text-sm font-medium">Course / Degree</label>
  <input name="course_degree" value="{{ old('course_degree', $teacher->course_degree ?? '') }}" class="mt-1 block w-full border border-gray-300 rounded-md p-2 h-11 bg-white box-border" />
      </div>

      <div>
        <label class="block text-sm font-medium">Course Major</label>
  <input name="course_major" value="{{ old('course_major', $teacher->course_major ?? '') }}" class="mt-1 block w-full border border-gray-300 rounded-md p-2 h-11 bg-white box-border" />
      </div>

      <div>
        <label class="block text-sm font-medium">Course Minor</label>
  <input name="course_minor" value="{{ old('course_minor', $teacher->course_minor ?? '') }}" class="mt-1 block w-full border border-gray-300 rounded-md p-2 h-11 bg-white box-border" />
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
      <div>
    <label class="block text-sm font-medium">Ancillary / Special Assignments</label>
  <textarea name="ancillary_assignments" placeholder="e.g. Class sponsor, dept. coordinator" class="mt-1 block w-full border border-gray-300 rounded-md p-2 h-11 bg-white resize-none box-border" rows="2">{{ old('ancillary_assignments', $teacher->ancillary_assignments ?? '') }}</textarea>
        <p class="text-xs text-slate-500 mt-1">Keep it short — a brief note about additional duties (optional).</p>
      </div>

      <div>
        <label class="block text-sm font-medium">Number of Handled Class per Week (max 8)</label>
  <input name="number_handled_per_week" type="number" min="0" max="8" value="{{ old('number_handled_per_week', $teacher->number_handled_per_week ?? '') }}" class="mt-1 block w-full border border-gray-300 rounded-md p-2 h-11 bg-white box-border" />
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
      <div>
  <label class="block text-sm font-medium">Subject Assignment</label>

      <style>
        .ms-container{ position:relative; }
        .ms-control{ display:flex; align-items:center; gap:8px; flex-wrap:wrap; padding:8px; border:1px solid #d1d5db; border-radius:6px; min-height:44px; background:#fff; }
        .ms-token{ background:#eef2ff; color:#1e293b; padding:6px 8px; border-radius:9999px; font-size:0.9rem; display:inline-flex; gap:6px; align-items:center; }
        .ms-token button{ background:transparent; border:0; color:#334155; cursor:pointer; padding:0 4px; }
        .ms-button{ margin-left:auto; background:transparent; border:0; cursor:pointer; color:#334155; }
        .ms-dropdown{ position:absolute; left:0; right:0; top:calc(100% + 6px); background:#fff; border:1px solid #e6eef6; box-shadow:0 6px 18px rgba(16,24,40,0.08); border-radius:8px; z-index:50; max-height:280px; overflow:auto; display:none; }
        .ms-search{ padding:10px; border-bottom:1px solid #eef2ff; }
        .ms-list{ padding:8px; }
        .ms-item{ display:flex; align-items:center; gap:8px; padding:8px; border-radius:6px; cursor:pointer; }
        .ms-item:hover{ background:#f8fafc; }
        .ms-select-all{ display:flex; align-items:center; gap:8px; padding:8px 10px; border-bottom:1px solid #f1f5f9; }
        .ms-empty{ padding:12px; color:#64748b; }
      </style>

      {{-- hidden native select to submit values --}}
      <select id="subjects-native" name="subjects[]" multiple style="display:none">
        @foreach($subjects as $s)
          <option value="{{ $s->id }}" @if(in_array($s->id, $selectedSubjects)) selected @endif>{{ $s->name }}</option>
        @endforeach
      </select>

      <div class="ms-container mt-1">
        <div class="ms-control" id="ms-control" tabindex="0" role="combobox" aria-expanded="false" aria-haspopup="listbox">
          <div id="ms-tokens" style="display:flex;gap:8px;flex-wrap:wrap">
            {{-- tokens inserted here --}}
          </div>
          <button type="button" id="ms-toggle" class="ms-button" aria-label="Toggle subjects dropdown">▾</button>
        </div>

        <div class="ms-dropdown" id="ms-dropdown" role="listbox" aria-multiselectable="true">
          <div class="ms-search"><input id="ms-search" placeholder="Search..." class="w-full border rounded px-2 py-1" /></div>
          <div class="ms-select-all"><input type="checkbox" id="ms-select-all" /> <label for="ms-select-all">Select all</label></div>
          <div class="ms-list" id="ms-list">
            @foreach($subjects as $s)
              <div class="ms-item" data-id="{{ $s->id }}" role="option" aria-selected="{{ in_array($s->id,$selectedSubjects) ? 'true' : 'false' }}">
                <input type="checkbox" class="ms-checkbox" data-id="{{ $s->id }}" @if(in_array($s->id,$selectedSubjects)) checked @endif />
                <div>{{ $s->name }} @if(!empty($s->code)) <small class="text-slate-400">({{ $s->code }})</small> @endif</div>
              </div>
            @endforeach
          </div>
        </div>
      </div>

      <script>
        (function(){
          const native = document.getElementById('subjects-native');
          const control = document.getElementById('ms-control');
          const toggle = document.getElementById('ms-toggle');
          const dropdown = document.getElementById('ms-dropdown');
          const list = document.getElementById('ms-list');
          const tokens = document.getElementById('ms-tokens');
          const search = document.getElementById('ms-search');
          const selectAll = document.getElementById('ms-select-all');

          function openDropdown(){ dropdown.style.display='block'; control.setAttribute('aria-expanded','true'); }
          function closeDropdown(){ dropdown.style.display='none'; control.setAttribute('aria-expanded','false'); }

          function renderTokens(){
            tokens.innerHTML = '';
            const checked = Array.from(native.options).filter(o=>o.selected);
            if(!checked.length){ const ph = document.createElement('div'); ph.className='text-slate-500'; ph.textContent='Select subjects...'; tokens.appendChild(ph); }
            checked.forEach(o=>{
              const t = document.createElement('span'); t.className='ms-token'; t.textContent = o.text;
              const rem = document.createElement('button'); rem.type='button'; rem.innerHTML='✕'; rem.addEventListener('click', function(){ deselect(o.value); });
              t.appendChild(rem); tokens.appendChild(t);
            });
          }

          function syncNativeFromList(){
            const checks = Array.from(list.querySelectorAll('.ms-checkbox'));
            checks.forEach(cb=>{
              const val = cb.dataset.id;
              const opt = native.querySelector('option[value="'+val+'"]');
              if(opt) opt.selected = cb.checked;
            });
            renderTokens();
          }

          function deselect(val){
            const cb = list.querySelector('.ms-checkbox[data-id="'+val+'"]'); if(cb){ cb.checked = false; }
            const opt = native.querySelector('option[value="'+val+'"]'); if(opt) opt.selected = false;
            renderTokens();
          }

          // init tokens from native select
          renderTokens();

          toggle.addEventListener('click', function(){ if(dropdown.style.display==='block') closeDropdown(); else openDropdown(); });
          control.addEventListener('click', function(e){ if(e.target===toggle) return; openDropdown(); search.focus(); });

          document.addEventListener('click', function(e){ if(!e.target.closest('.ms-container')) closeDropdown(); });

          // checkbox change
          list.addEventListener('change', function(e){ if(e.target.matches('.ms-checkbox')){ syncNativeFromList(); updateSelectAllState(); } });

          // clicking item toggles checkbox
          list.addEventListener('click', function(e){ const it = e.target.closest('.ms-item'); if(!it) return; const cb = it.querySelector('.ms-checkbox'); if(cb){ cb.checked = !cb.checked; cb.dispatchEvent(new Event('change')); } });

          // search
          search.addEventListener('input', function(){ const q = (this.value||'').toLowerCase(); Array.from(list.querySelectorAll('.ms-item')).forEach(it=>{ const txt = it.textContent.toLowerCase(); it.style.display = txt.indexOf(q) === -1 ? 'none' : 'flex'; }); });

          function updateSelectAllState(){ const all = Array.from(list.querySelectorAll('.ms-checkbox')).filter(cb=>cb.closest('.ms-item').style.display!=='none'); const checked = all.filter(cb=>cb.checked); selectAll.checked = all.length>0 && checked.length===all.length; }
          selectAll.addEventListener('change', function(){ const visible = Array.from(list.querySelectorAll('.ms-item')).filter(it=>it.style.display!=='none'); visible.forEach(it=>{ const cb = it.querySelector('.ms-checkbox'); if(cb) cb.checked = selectAll.checked; }); syncNativeFromList(); });

          // initialize select-all state
          updateSelectAllState();

          // keyboard: open on focus + key
          control.addEventListener('keydown', function(e){ if(e.key==='ArrowDown' || e.key==='Enter'){ e.preventDefault(); openDropdown(); search.focus(); } });
        })();
      </script>
      </div>

    <div>
      <label class="block text-sm font-medium">Unavailable Periods</label>
      <input type="hidden" id="advisory-input" name="advisory" value="{{ old('advisory', $teacher->advisory ?? '') }}">

      {{-- reuse the ms-control look for periods; selections sync back into the hidden CSV advisory input --}}
      <select id="availability-native" multiple style="display:none">
        @for($i=1;$i<=9;$i++)
          @php
            $val = (string)$i;
            $label = $i.'th Period';
            if($i==1) $label='1st Period';
            if($i==2) $label='2nd Period';
            if($i==3) $label='3rd Period';
          @endphp
          <option value="{{ $val }}" @if(in_array($val, explode(',', old('advisory', $teacher->advisory ?? '')))) selected @endif>{{ $label }}</option>
        @endfor
      </select>

      <div class="ms-container mt-1">
        <div class="ms-control" id="avail-control" tabindex="0" role="combobox" aria-expanded="false" aria-haspopup="listbox">
          <div id="avail-tokens" style="display:flex;gap:8px;flex-wrap:wrap"></div>
          <button type="button" id="avail-toggle" class="ms-button" aria-label="Toggle availability dropdown">▾</button>
        </div>

        <div class="ms-dropdown" id="avail-dropdown" role="listbox" aria-multiselectable="true">
          <div class="ms-search"><input id="avail-search" placeholder="Search..." class="w-full border rounded px-2 py-1" /></div>
          <div class="ms-select-all"><input type="checkbox" id="avail-select-all" /> <label for="avail-select-all">Select all</label></div>
          <div class="ms-list" id="avail-list">
            @for($i=1;$i<=9;$i++)
              @php
                $val = (string)$i;
                $label = $i.'th Period';
                if($i==1) $label='1st Period';
                if($i==2) $label='2nd Period';
                if($i==3) $label='3rd Period';
              @endphp
              <div class="ms-item" data-id="{{ $val }}" role="option" aria-selected="false">
                <input type="checkbox" class="ms-checkbox" data-id="{{ $val }}" />
                <div>{{ $label }}</div>
              </div>
            @endfor
          </div>
        </div>
      </div>

      <p class="text-xs text-slate-500 mt-1">Optional — pick one or more periods the teacher cannot teach.</p>

      <script>
        (function(){
          const hidden = document.getElementById('advisory-input');
          const native = document.getElementById('availability-native');
          const control = document.getElementById('avail-control');
          const toggle = document.getElementById('avail-toggle');
          const dropdown = document.getElementById('avail-dropdown');
          const list = document.getElementById('avail-list');
          const tokens = document.getElementById('avail-tokens');
          const search = document.getElementById('avail-search');
          const selectAll = document.getElementById('avail-select-all');

          function open(){ dropdown.style.display='block'; control.setAttribute('aria-expanded','true'); }
          function close(){ dropdown.style.display='none'; control.setAttribute('aria-expanded','false'); }

          function render(){
            tokens.innerHTML='';
            const selected = Array.from(native.options).filter(o=>o.selected).map(o=>o.value);
            if(!selected.length){ const ph=document.createElement('div'); ph.className='text-slate-500'; ph.textContent='Select periods...'; tokens.appendChild(ph); }
            selected.forEach(v=>{
              const opt = native.querySelector('option[value="'+v+'"]');
              if(!opt) return;
              const t=document.createElement('span'); t.className='ms-token'; t.textContent=opt.text;
              const rem=document.createElement('button'); rem.type='button'; rem.innerHTML='✕'; rem.addEventListener('click', ()=>{ deselect(v); });
              t.appendChild(rem); tokens.appendChild(t);
            });
            hidden.value = selected.join(',');
          }

          function sync(){
            const checks = Array.from(list.querySelectorAll('.ms-checkbox'));
            checks.forEach(cb=>{
              const val = cb.dataset.id;
              const opt = native.querySelector('option[value="'+val+'"]');
              if(opt) opt.selected = cb.checked;
            });
            render();
          }

          function deselect(val){ const cb=list.querySelector('.ms-checkbox[data-id="'+val+'"]'); if(cb){ cb.checked=false; } const opt=native.querySelector('option[value="'+val+'"]'); if(opt) opt.selected=false; render(); }

          // initialize from hidden advisory CSV
          (function init(){ const v=(hidden.value||'').trim(); if(v.length){ const parts=v.split(',').map(s=>s.trim()).filter(Boolean); parts.forEach(p=>{ const cb=list.querySelector('.ms-checkbox[data-id="'+p+'"]'); if(cb) cb.checked=true; const opt=native.querySelector('option[value="'+p+'"]'); if(opt) opt.selected=true; }); } render(); updateSelectAll(); })();

          toggle.addEventListener('click', ()=>{ if(dropdown.style.display==='block') close(); else { open(); search.focus(); } });
          list.addEventListener('click', function(e){ const it=e.target.closest('.ms-item'); if(!it) return; const cb=it.querySelector('.ms-checkbox'); if(cb){ cb.checked=!cb.checked; cb.dispatchEvent(new Event('change')); } });
          list.addEventListener('change', function(e){ if(e.target.matches('.ms-checkbox')){ sync(); updateSelectAll(); } });
          search.addEventListener('input', function(){ const q=(this.value||'').toLowerCase(); Array.from(list.querySelectorAll('.ms-item')).forEach(it=>{ const txt=it.textContent.toLowerCase(); it.style.display = txt.indexOf(q)===-1 ? 'none' : 'flex'; }); updateSelectAll(); });
          selectAll.addEventListener('change', function(){ const visible=Array.from(list.querySelectorAll('.ms-item')).filter(it=>it.style.display!=='none'); visible.forEach(it=>{ const cb=it.querySelector('.ms-checkbox'); if(cb) cb.checked=selectAll.checked; }); sync(); });

          function updateSelectAll(){ const all=Array.from(list.querySelectorAll('.ms-checkbox')).filter(cb=>cb.closest('.ms-item').style.display!=='none'); const checked=all.filter(cb=>cb.checked); selectAll.checked = all.length>0 && checked.length===all.length; }

          document.addEventListener('click', function(e){ if(!e.target.closest('.ms-container')) close(); });
        })();
      </script>
    </div>

    <div class="pt-4">
      <button class="px-4 py-2 bg-[#3b4197] text-white rounded">{{ $isEdit ? 'Save' : 'Create' }}</button>
      <a href="{{ route('admin.it.teachers.index') }}" class="ml-2 text-sm">Cancel</a>
    </div>
  </div>
</form>
