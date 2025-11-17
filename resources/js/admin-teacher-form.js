// admin-teacher-form.js
// Generic initializer for any "ms-container" multi-select used in the teacher form.
// It handles both the Subjects multi-select and the Availability (periods) multi-select.

try { console.debug('[admin-teacher-form] loaded'); } catch(e){}

async function fetchSubjectsJson() {
  try {
    const resp = await fetch('/admin/it/teachers/subjects.json', { credentials: 'same-origin', headers: { 'Accept': 'application/json' } });
    if (!resp.ok) return null;
    const data = await resp.json();
    return data;
  } catch (e) {
    console.error('Failed to fetch subjects.json', e);
    return null;
  }
}

function initContainer(container) {
  if (!container || container.__ms_inited) return;
  container.__ms_inited = true;
  try { console.debug('[admin-teacher-form] initContainer', container); } catch(e){}

  let native = container.querySelector('select[multiple]');
  // In our markup the hidden native <select> is often placed just before the .ms-container
  // (sibling), so try to find it there if not present inside the container.
  if (!native) {
    try {
      const prev = container.previousElementSibling;
      if (prev && prev.matches && prev.matches('select[multiple]')) {
        native = prev;
      }
    } catch (e) {}
  }
  // fallback: search the parent form for a matching multiple select (subjects[] or advisory)
  if (!native && container.closest) {
    const parentForm = container.closest('form') || document;
    native = parentForm.querySelector('select[multiple]');
  }
  const control = container.querySelector('.ms-control');
  const toggle = container.querySelector('.ms-button');
  const dropdown = container.querySelector('.ms-dropdown');
  const list = container.querySelector('.ms-list');
  const tokens = container.querySelector('[id$="-tokens"]') || container.querySelector('#ms-tokens');
  const search = container.querySelector('.ms-search input');
  const selectAll = container.querySelector('.ms-select-all input[type="checkbox"]');

  if (!native || !control || !list || !tokens) {
    try { console.debug('[admin-teacher-form] initContainer aborted, missing required elements', { native: !!native, control: !!control, list: !!list, tokens: !!tokens }); } catch(e){}
    return;
  }

  function openDropdown(){ if(dropdown) { dropdown.style.display='block'; control.setAttribute('aria-expanded','true'); } }
  function closeDropdown(){ if(dropdown) { dropdown.style.display='none'; control.setAttribute('aria-expanded','false'); } }

  function renderTokens(){
    tokens.innerHTML = '';
    const checked = Array.from(native.options).filter(o=>o.selected);
    if(!checked.length){ const ph = document.createElement('div'); ph.className='text-slate-500'; ph.textContent=(native.name && native.name.indexOf('subjects')!==-1)?'Select subjects...':'Select periods...'; tokens.appendChild(ph); }
    checked.forEach(o=>{
      const t = document.createElement('span'); t.className='ms-token'; t.textContent = o.text;
      const rem = document.createElement('button'); rem.type='button'; rem.className='ms-token-remove'; rem.setAttribute('aria-label','Remove'); rem.innerHTML='✕'; rem.addEventListener('click', function(){ deselect(o.value); });
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

  // initialize tokens from native select
  renderTokens();

  try { console.debug('[admin-teacher-form] rendered tokens for', native.name || native.id); } catch(e){}

  if (toggle) toggle.addEventListener('click', function(e){ e.stopPropagation(); if(dropdown && dropdown.style.display==='block') closeDropdown(); else openDropdown(); });
  if (control) control.addEventListener('click', function(e){ if(e.target===toggle) return; openDropdown(); if(search) search.focus(); });

  document.addEventListener('click', function(e){ if(!e.target.closest('.ms-container')) closeDropdown(); });

  // checkbox change
  list.addEventListener('change', function(e){ if(e.target.matches('.ms-checkbox')){ syncNativeFromList(); updateSelectAllState(); } });

  // clicking item toggles checkbox
  list.addEventListener('click', function(e){ const it = e.target.closest('.ms-item'); if(!it) return; const cb = it.querySelector('.ms-checkbox'); if(cb){ cb.checked = !cb.checked; cb.dispatchEvent(new Event('change')); } });

  // search
  if (search) search.addEventListener('input', function(){ const q = (this.value||'').toLowerCase(); Array.from(list.querySelectorAll('.ms-item')).forEach(it=>{ const txt = it.textContent.toLowerCase(); it.style.display = txt.indexOf(q) === -1 ? 'none' : 'flex'; }); updateSelectAllState(); });

  function updateSelectAllState(){ const all = Array.from(list.querySelectorAll('.ms-checkbox')).filter(cb=>cb.closest('.ms-item').style.display!=='none'); const checked = all.filter(cb=>cb.checked); if(selectAll) selectAll.checked = all.length>0 && checked.length===all.length; }
  if (selectAll) selectAll.addEventListener('change', function(){ const visible = Array.from(list.querySelectorAll('.ms-item')).filter(it=>it.style.display!=='none'); visible.forEach(it=>{ const cb = it.querySelector('.ms-checkbox'); if(cb) cb.checked = selectAll.checked; }); syncNativeFromList(); });

  // initialize select-all state
  updateSelectAllState();

  // keyboard: open on focus + key
  control.addEventListener('keydown', function(e){ if(e.key==='ArrowDown' || e.key==='Enter'){ e.preventDefault(); openDropdown(); if(search) search.focus(); } });
}

function populateSubjectsInto(container, subjects){
  if(!container || !Array.isArray(subjects)) return;
  const native = container.querySelector('select[name="subjects[]"]');
  const list = container.querySelector('.ms-list');
  if(!native || !list) return;

  // clear existing
  native.innerHTML = '';
  list.innerHTML = '';

  subjects.forEach(s => {
    const opt = document.createElement('option'); opt.value = String(s.id); opt.textContent = s.name; native.appendChild(opt);

    const item = document.createElement('div'); item.className = 'ms-item'; item.setAttribute('data-id', String(s.id)); item.setAttribute('role','option'); item.setAttribute('aria-selected','false');
    const cb = document.createElement('input'); cb.type='checkbox'; cb.className='ms-checkbox'; cb.setAttribute('data-id', String(s.id));
    const label = document.createElement('div'); label.innerHTML = s.name + (s.short_code ? ' <small class="text-slate-400">('+s.short_code+')</small>' : '');
    item.appendChild(cb); item.appendChild(label);
    list.appendChild(item);
  });
}

function initAllOnPage(){
  const containers = document.querySelectorAll('.ms-container');
  containers.forEach(c=> initContainer(c));
}

// MutationObserver to detect injected fragments and initialize them
const observer = new MutationObserver(async (mutations)=>{
  for (const m of mutations) {
    for (const n of m.addedNodes) {
      if (!(n instanceof Element)) continue;
      const containers = n.querySelectorAll && n.querySelectorAll('.ms-container');
      if (containers && containers.length) {
        for (const c of containers) {
          // if this container is the subjects control and it's empty, fetch subjects
          const native = c.querySelector('select[name="subjects[]"]');
          if (native && native.children.length === 0) {
            try {
              console.debug('[admin-teacher-form] subjects empty in injected fragment — fetching subjects.json');
              const data = await fetchSubjectsJson();
              if (data && Array.isArray(data)) {
                console.debug('[admin-teacher-form] fetched subjects.json count=', data.length);
                populateSubjectsInto(c, data);
              } else {
                console.debug('[admin-teacher-form] subjects.json returned no data');
              }
            } catch(e){ console.error('[admin-teacher-form] error fetching subjects.json', e); }
          }
          initContainer(c);
        }
      }
    }
  }
});

if (typeof window !== 'undefined') {
  document.addEventListener('DOMContentLoaded', function(){
    initAllOnPage();
    observer.observe(document.body, { childList: true, subtree: true });
  });
}

export { initAllOnPage, populateSubjectsInto };
