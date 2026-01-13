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
  let _closeTimer = null;
  // close dropdown when mouse leaves the container after a short delay
  container.addEventListener('mouseenter', function(){ if(_closeTimer){ clearTimeout(_closeTimer); _closeTimer = null; } });
  container.addEventListener('mouseleave', function(){ if(dropdown){ if(_closeTimer) clearTimeout(_closeTimer); _closeTimer = setTimeout(function(){ closeDropdown(); _closeTimer = null; }, 350); } });

  function renderTokens(){
    tokens.innerHTML = '';
    const checked = Array.from(native.options).filter(o=>o.selected);
    if(!checked.length){ const ph = document.createElement('div'); ph.className='text-slate-500';
      let placeholder = 'Select...';
      if (native.name && native.name.indexOf('subjects')!==-1) placeholder = 'Select subjects...';
      else if (native.name && native.name.indexOf('grade')!==-1) placeholder = 'Select grade levels...';
      else if (native.name && (native.name.indexOf('availability')!==-1 || native.name.indexOf('period')!==-1)) placeholder = 'Select periods...';
      ph.textContent = placeholder; tokens.appendChild(ph); }
    checked.forEach(o=>{
      const t = document.createElement('span'); t.className='ms-token'; t.textContent = o.text;
      const rem = document.createElement('button'); rem.type='button'; rem.className='ms-token-remove'; rem.setAttribute('aria-label','Remove'); rem.innerHTML='✕'; rem.addEventListener('click', function(){ deselect(o.value); });
      t.appendChild(rem); tokens.appendChild(t);
    });
  }

  // sync native <select> from list-item selections (not using checkboxes)
  function syncNativeFromList(){
    // set native selected based on hidden ms-item-selected class or option state
    Array.from(native.options).forEach(opt => {
      const id = String(opt.value);
      const item = list.querySelector('.ms-item[data-id="'+id+'"]');
      if (item && item.classList.contains('ms-item-selected')) {
        opt.selected = true;
      } else {
        // keep whatever is set, but ensure not-selected items are false
        if (item && !item.classList.contains('ms-item-selected')) opt.selected = false;
      }
    });
    renderTokens();
  }

  function syncListFromNative(){
    // if native option is selected, hide the list item and mark selected
    Array.from(list.querySelectorAll('.ms-item')).forEach(it => {
      const id = it.dataset.id;
      const opt = native.querySelector('option[value="'+id+'"]');
      if (opt && opt.selected) {
        it.style.display = 'none';
        it.classList.add('ms-item-selected');
      } else {
        it.style.display = '';
        it.classList.remove('ms-item-selected');
      }
    });
    renderTokens();
  }

  function deselect(val){
    const opt = native.querySelector('option[value="'+val+'"]'); if(opt) opt.selected = false;
    // show the item again
    const it = list.querySelector('.ms-item[data-id="'+val+'"]'); if(it){ it.style.display = ''; it.classList.remove('ms-item-selected'); }
    // refresh tokens
    renderTokens();
    updateSelectAllState();
  }

  // initialize tokens from native select
  renderTokens();
  // ensure list checkboxes reflect native select initial state
  try{ syncListFromNative(); } catch(e){}

  try { console.debug('[admin-teacher-form] rendered tokens for', native.name || native.id); } catch(e){}

  if (toggle) toggle.addEventListener('click', function(e){ e.stopPropagation(); if(dropdown && dropdown.style.display==='block') closeDropdown(); else openDropdown(); });
  if (control) control.addEventListener('click', function(e){ if(e.target===toggle) return; openDropdown(); if(search) search.focus(); });

  document.addEventListener('click', function(e){ if(!e.target.closest('.ms-container')) closeDropdown(); });

  // clicking item selects it (list-only UI). Add token and hide item.
  list.addEventListener('click', function(e){
    const it = e.target.closest('.ms-item');
    if(!it) return;
    const id = it.dataset.id;
    const opt = native.querySelector('option[value="'+id+'"]');
    if (opt) {
      // enforce max 3 selections
      const currentlySelected = Array.from(native.options).filter(o=>o.selected).length;
      if (currentlySelected >= 3) {
        if (typeof showToast === 'function') showToast('You can select up to 3 items', 'error');
        else alert('You can select up to 3 items');
        return;
      }
      opt.selected = true;
      it.style.display = 'none';
      it.classList.add('ms-item-selected');
      renderTokens();
      updateSelectAllState();
    }
  });

  // search
  if (search) search.addEventListener('input', function(){ const q = (this.value||'').toLowerCase(); Array.from(list.querySelectorAll('.ms-item')).forEach(it=>{ const txt = it.textContent.toLowerCase(); it.style.display = txt.indexOf(q) === -1 ? 'none' : 'flex'; }); updateSelectAllState(); });

  function updateSelectAllState(){ const all = Array.from(list.querySelectorAll('.ms-checkbox')).filter(cb=>cb.closest('.ms-item').style.display!=='none'); const checked = all.filter(cb=>cb.checked); if(selectAll) selectAll.checked = all.length>0 && checked.length===all.length; }
  if (selectAll) selectAll.addEventListener('change', function(){
    // when selecting all, only select up to 3 visible items
    const visible = Array.from(list.querySelectorAll('.ms-item')).filter(it=>it.style.display!=='none');
    if (selectAll.checked) {
      let count = 0;
      visible.forEach(it=>{
        const id = it.dataset.id;
        const opt = native.querySelector('option[value="'+id+'"]');
        if (opt && count < 3) { opt.selected = true; it.style.display='none'; it.classList.add('ms-item-selected'); count++; }
        else { if (opt) opt.selected = false; it.style.display=''; it.classList.remove('ms-item-selected'); }
      });
    } else {
      visible.forEach(it=>{
        const id = it.dataset.id;
        const opt = native.querySelector('option[value="'+id+'"]');
        if (opt) opt.selected = false;
        it.style.display=''; it.classList.remove('ms-item-selected');
      });
    }
    syncNativeFromList();
  });

  // initialize select-all state
  updateSelectAllState();

  // ensure native select is synced before parent form submits and submit via AJAX so the form stays open
  const parentForm = container.closest('form');
  if (parentForm && !parentForm.__ms_submit_inited) {
    parentForm.__ms_submit_inited = true;
    parentForm.addEventListener('submit', async function(e){
      e.preventDefault();
      try {
        // Ensure all ms-container native <select>s reflect their UI state before submit.
        Array.from(document.querySelectorAll('.ms-container')).forEach(c => {
          try {
            const n = c.querySelector('select[multiple]');
            const l = c.querySelector('.ms-list');
            if (!n || !l) return;
            Array.from(n.options).forEach(opt => {
              const id = String(opt.value);
              const item = l.querySelector('.ms-item[data-id="'+id+'"]');
              const cb = l.querySelector('.ms-checkbox[data-id="'+id+'"]');
              if (item && item.classList.contains('ms-item-selected')) {
                opt.selected = true;
              } else if (cb) {
                opt.selected = !!cb.checked;
              } else {
                opt.selected = false;
              }
            });
          } catch (e) { /* ignore per-container errors */ }
        });
        console.debug && console.debug('[admin-teacher-form] synced all ms-container native selects before AJAX submit');

        // prepare form data and send via fetch
        const action = parentForm.getAttribute('action') || window.location.href;
        const method = (parentForm.getAttribute('method') || 'POST').toUpperCase();
        const formData = new FormData(parentForm);

        // send as AJAX so server may return fragment or JSON; keep the form open on success
        const resp = await fetch(action, { method: method, body: formData, credentials: 'same-origin', headers: { 'X-Requested-With': 'XMLHttpRequest' } });
        if (!resp.ok) {
          // try to show error message from response
          let txt = await resp.text().catch(()=>null);
          console.error('[admin-teacher-form] save failed', resp.status, txt);
          alert('Save failed. See console for details.');
          return;
        }

        const ctype = resp.headers.get('content-type') || '';
        if (ctype.indexOf('application/json') !== -1) {
          const data = await resp.json();
          if (data.html) {
            // replace detail pane content with returned fragment (keeps form visible)
            const pane = document.getElementById('detail-pane'); if (pane) pane.innerHTML = data.html;
          }
          if (data.success) {
            // show a transient saved indicator
            showSavedIndicator(parentForm);
          }
          // update left list entry name if provided
          if (data.teacher && data.teacher.id) {
            const li = document.querySelector(`#teacher-list [data-id='${data.teacher.id}']`);
            if (li) li.querySelector('.font-medium').textContent = data.teacher.name || li.querySelector('.font-medium').textContent;
          }
        } else {
          // HTML response — replace fragment in detail pane but do not navigate away
          const txt = await resp.text();
          const pane = document.getElementById('detail-pane'); if (pane) pane.innerHTML = txt;
          showSavedIndicator(parentForm);
        }
      } catch (err) { console.error('[admin-teacher-form] submit failed', err); alert('Save failed (client error). See console for details.'); }
    });
  }

  // transient saved indicator helper
  function showSavedIndicator(form){
    try{
      let el = form.querySelector('.ms-save-indicator');
      if(!el){ el = document.createElement('div'); el.className='ms-save-indicator text-sm text-green-700 mt-2'; el.style.transition='opacity 0.2s'; form.appendChild(el); }
      el.textContent = 'Saved'; el.style.opacity = '1'; setTimeout(()=>{ if(el) el.style.opacity='0'; }, 2000);
    } catch(e){ }
  }

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
    // if the native option is pre-selected, mark checkbox checked
    try { if (native.querySelector('option[value="'+String(s.id)+'"]') && native.querySelector('option[value="'+String(s.id)+'"]').selected) cb.checked = true; } catch(e) {}
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
