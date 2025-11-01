@extends('admin.it.layout')

@section('title','Sections & Subjects')
@section('heading','Sections & Subjects')

@section('content')
<div class="container mx-auto p-6">

    <!-- Tabs -->
    <div class="mb-4">
        <div class="inline-flex rounded-md border bg-white">
            <button type="button" id="tab-sections" class="px-4 py-2 text-sm font-medium border-r" aria-pressed="true">Sections</button>
            <button type="button" id="tab-subjects" class="px-4 py-2 text-sm font-medium" aria-pressed="false">Subjects</button>
        </div>
    </div>

    <div id="ss-grid" class="grid grid-cols-1 gap-6">
        <!-- Sections panel -->
        <div id="panel-sections">
            

            <div class="space-y-6">
                <!-- Junior High container -->
                <div class="border rounded p-4">
                    <div class="flex items-center justify-between mb-3">
                        <div class="font-medium">Junior High School</div>
                        <div class="text-sm text-slate-500">Grades 7 &ndash; 10</div>
                    </div>

                    <div class="space-y-3">
                        @foreach(range(7,10) as $yr)
                            @php
                                $grade = $gradeLevels->firstWhere('year', $yr);
                                $name = $grade->name ?? 'Grade '.$yr;
                                $gid = $grade->id ?? '';
                                $theme = $grade->section_naming ?? '';
                                $planned = $grade->section_naming_options['planned_sections'] ?? 0;
                            @endphp
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-3 items-center py-2 border-b">
                                <div class="md:col-span-1 font-medium">{{ $name }}</div>
                                    <div>
                                        <input name="count" data-year="{{ $yr }}" data-grade-id="{{ $gid }}" type="number" min="0" class="w-full border rounded px-2 py-1" value="{{ $planned }}">
                                    </div>
                                    <div>
                                        @php
                                            $allThemes = config('section_themes.themes');
                                            // default to a random theme key if none stored (guard when list empty)
                                            $selectedThemeKey = $theme ?: (count($allThemes) ? array_rand($allThemes) : '');
                                        @endphp
                                        <select name="theme" data-year="{{ $yr }}" data-grade-id="{{ $gid }}" class="w-full border rounded px-2 py-1">
                                            @foreach($allThemes as $tkey => $t)
                                                <option value="{{ $tkey }}" data-label="{{ $t['label'] }}" {{ ($tkey === $selectedThemeKey) ? 'selected' : '' }}>{{ $t['label'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                <div class="md:col-span-4 mt-2">
                                    <div class="preview-area" data-year="{{ $yr }}" data-grade-id="{{ $gid }}"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Senior High container -->
                <div class="border rounded p-4">
                    <div class="flex items-center justify-between mb-3">
                        <div class="font-medium">Senior High School</div>
                        <div class="text-sm text-slate-500">Grades 11 &ndash; 12</div>
                    </div>

                    <div class="space-y-3">
                        @foreach(range(11,12) as $yr)
                            @php
                                $grade = $gradeLevels->firstWhere('year', $yr);
                                $name = $grade->name ?? 'Grade '.$yr;
                                $gid = $grade->id ?? '';
                                $theme = $grade->section_naming ?? '';
                                $planned = $grade->section_naming_options['planned_sections'] ?? 0;
                            @endphp
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-3 items-center py-2 border-b">
                                <div class="md:col-span-1 font-medium">{{ $name }}</div>
                                    <div>
                                        <input name="count" data-year="{{ $yr }}" data-grade-id="{{ $gid }}" type="number" min="0" class="w-full border rounded px-2 py-1" value="{{ $planned }}">
                                    </div>
                                    <div>
                                        @php
                                            $allThemes = config('section_themes.themes');
                                            $selectedThemeKey = $theme ?: (count($allThemes) ? array_rand($allThemes) : '');
                                        @endphp
                                        <select name="theme" data-year="{{ $yr }}" data-grade-id="{{ $gid }}" class="w-full border rounded px-2 py-1">
                                            @foreach($allThemes as $tkey => $t)
                                                <option value="{{ $tkey }}" data-label="{{ $t['label'] }}" {{ ($tkey === $selectedThemeKey) ? 'selected' : '' }}>{{ $t['label'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                <div class="md:col-span-4 mt-2">
                                    <div class="preview-area" data-year="{{ $yr }}" data-grade-id="{{ $gid }}"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                
                <!-- Global toolbar placed below Senior High (single Save button) -->
                <div class="mt-4 flex justify-end">
                    <button id="save-all" type="button" class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700 w-36 text-center">Save</button>
                </div>
            </div>
        </div>

        <!-- Subjects panel -->
        <div id="panel-subjects" class="hidden bg-white border rounded p-4">
            <h2 class="font-semibold mb-3">Subjects</h2>
            <p class="text-sm text-slate-600 mb-4">Manage subjects for Junior High and Senior High separately. No seeded subjects are shown by default.</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Junior High Subjects Card -->
                <div class="border rounded p-4 bg-white">
                    <div class="flex items-center justify-between mb-3">
                        <div class="font-medium">Junior High Subjects</div>
                        <div class="text-sm text-slate-500">Grades 7 &ndash; 10</div>
                    </div>

                    <div class="mb-4">
                        <form id="add-subject-jr" class="space-y-3">
                            <div>
                                <label class="block text-xs mb-1">Subject Name</label>
                                <input id="subject-name-jr" name="name" class="w-full border rounded px-2 py-1" placeholder="e.g. Mathematics">
                            </div>
                            <div>
                                <label class="block text-xs mb-1">Classification</label>
                                <select id="subject-classification-jr" name="classification" class="w-full border rounded px-2 py-1">
                                    <option value="core">Core</option>
                                    <option value="__add__">Add classification...</option>
                                </select>
                                <input id="subject-classification-jr-new" name="classification_new" type="text" placeholder="Type new classification (e.g. Specialized)" class="w-full border rounded px-2 py-1 mt-2 hidden">
                            </div>
                            @foreach($gradeLevels->whereIn('year', range(7,10)) as $g)
                                <input type="hidden" name="grades[]" value="{{ $g->id }}">
                            @endforeach
                            <div>
                                <button type="submit" class="bg-indigo-600 text-white px-3 py-1 rounded">Add Subject (Junior High)</button>
                            </div>
                        </form>
                    </div>

                    <div>
                        <div class="font-semibold mb-2">Regular Subjects</div>
                        <ul class="list-disc pl-5 space-y-1 text-sm text-slate-700">
                            <li>Filipino</li>
                            <li>English</li>
                            <li>Mathematics</li>
                            <li>Science</li>
                            <li>Araling Panlipunan (Social Studies)</li>
                            <li>Edukasyon sa Pagpapakatao (Values Education)</li>
                            <li>Technology &amp; Livelihood Education (TLE)</li>
                            <li>Music, Arts, Physical Education, and Health (MAPEH)</li>
                        </ul>
                    </div>
                </div>

                <!-- Senior High Subjects Card -->
                <div class="border rounded p-4 bg-white">
                    <div class="flex items-center justify-between mb-3">
                        <div class="font-medium">Senior High Subjects</div>
                        <div class="text-sm text-slate-500">Grades 11 &ndash; 12</div>
                    </div>

                    <div class="mb-4">
                        <form id="add-subject-sr" class="space-y-3">
                            <div>
                                <label class="block text-xs mb-1">Subject Name</label>
                                <input id="subject-name-sr" name="name" class="w-full border rounded px-2 py-1" placeholder="e.g. Research">
                            </div>
                            <div>
                                <label class="block text-xs mb-1">Classification</label>
                                <select id="subject-classification-sr" name="classification" class="w-full border rounded px-2 py-1">
                                    <option value="core">Core</option>
                                    <option value="__add__">Add classification...</option>
                                </select>
                                <input id="subject-classification-sr-new" name="classification_new" type="text" placeholder="Type new classification (e.g. Specialized)" class="w-full border rounded px-2 py-1 mt-2 hidden">
                            </div>
                            @foreach($gradeLevels->whereIn('year', range(11,12)) as $g)
                                <input type="hidden" name="grades[]" value="{{ $g->id }}">
                            @endforeach
                            <div>
                                <button type="submit" class="bg-indigo-600 text-white px-3 py-1 rounded">Add Subject (Senior High)</button>
                            </div>
                        </form>
                    </div>

                    <div class="text-sm text-slate-500">No subjects yet for Senior High.</div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<div id="ss-hooks" style="display:none"
    data-preview-url="{{ route('admin.it.grade-levels.preview') }}"
    data-subject-store-url="{{ route('admin.it.subjects.store') }}"
></div>
<script>
    (function(){
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const hooks = document.getElementById('ss-hooks');
        console.log('subjects-sections script loaded');
        const previewUrl = hooks.dataset.previewUrl;
        const subjectStoreUrl = hooks.dataset.subjectStoreUrl;
        const toggleBase = '/admin/it/subjects';

        function getInputsForGrade(gradeId, container=null){
            container = container || document;
            const themeEl = container.querySelector('select[name="theme"][data-grade-id="'+gradeId+'"]');
            const theme = themeEl ? (themeEl.value || '').trim() : '';
            const count = parseInt(container.querySelector('input[name="count"][data-grade-id="'+gradeId+'"]').value || '0', 10);
            return { theme, count };
        }

        async function postJSON(url, body){
            const res = await fetch(url, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token },
                body: JSON.stringify(body)
            });
            return res.json();
        }

        // helper to set a select[name="theme"] based on server response (tries key first, then label)
        // returns true if it successfully selected an option
        function setThemeSelect(selectEl, resp){
            if(!selectEl || !resp) return false;
            // server may return a theme key
            if(resp.theme){
                const opt = Array.from(selectEl.options).find(o => o.value === resp.theme);
                if(opt){ selectEl.value = resp.theme; return true; }
            }
            const label = resp.theme_label || resp.theme || null;
            if(label){
                const opt2 = Array.from(selectEl.options).find(o => (o.text === label) || (o.getAttribute('data-label') === label));
                if(opt2){ selectEl.value = opt2.value; return true; }
            }
            return false;
        }

        // Preview for inline theme (anonymous preview)
        document.querySelectorAll('.btn-preview').forEach(btn => {
            btn.addEventListener('click', async (e) => {
                const gid = btn.getAttribute('data-grade-id');
                const { theme, count } = getInputsForGrade(gid);
                if(!theme || !count){
                    alert('Please provide a naming theme and a count > 0');
                    return;
                }
                const payload = { theme, count };
                const resp = await postJSON(previewUrl, payload);
                const area = document.querySelector('.preview-area[data-grade-id="'+gid+'"]');
                area.innerHTML = '';
                const names = Array.isArray(resp) ? resp : (resp.preview ?? resp.names ?? []);
                if (Array.isArray(names) && names.length) {
                    const list = document.createElement('div');
                    list.className = 'space-y-1';
                    names.forEach((n, idx) => {
                        const name = (typeof n === 'string') ? n : (n.name ?? '');
                        const row = document.createElement('div');
                        row.className = 'flex items-center gap-2';
                        row.innerHTML = `<input data-idx="${idx}" class="w-full border rounded px-2 py-1 section-name-input" value="${name}"/>`;
                        list.appendChild(row);
                    });
                    area.appendChild(list);
                }
            });
        });

        // Auto-generate: per-grade generate button handler (requests preview for that grade)
        document.querySelectorAll('.btn-generate').forEach(btn => {
            btn.addEventListener('click', async (e) => {
                const gid = btn.getAttribute('data-grade-id');
                const year = btn.getAttribute('data-year');
                const themeInput = document.querySelector('select[name="theme"][data-grade-id="'+gid+'"]') || document.querySelector('select[name="theme"][data-year="'+year+'"]');
                const countInput = document.querySelector('input[name="count"][data-grade-id="'+gid+'"]') || document.querySelector('input[name="count"][data-year="'+year+'"]');
                const theme = themeInput ? themeInput.value.trim() : '';
                const count = countInput ? parseInt(countInput.value||'0',10) : 0;
                if(!count){ alert('Please set a number of sections greater than 0'); return; }
                try{
                    const payload = { theme: theme, count: count, name: 'Grade ' + year };
                    const resp = await postJSON(previewUrl, payload);
                    const preview = resp.preview ?? resp.names ?? resp;
                    const area = document.querySelector('.preview-area[data-grade-id="'+gid+'"]') || document.querySelector('.preview-area[data-year="'+year+'"]');
                    if(themeInput){ const ok = setThemeSelect(themeInput, resp); if(!ok){
                            // fallback: pick a random real option (skip empty placeholder)
                            const opts = Array.from(themeInput.options).filter(o=>o.value && o.value.trim()!=='');
                            if(opts.length){ const pick = opts[Math.floor(Math.random()*opts.length)]; themeInput.value = pick.value; }
                        }
                    }
                    if(area){
                        area.innerHTML = '';
                        if(Array.isArray(preview) && preview.length){
                            const list = document.createElement('div');
                            list.className = 'space-y-1';
                            preview.forEach((item, idx) => {
                                const name = (typeof item === 'string') ? item : (item.name ?? '');
                                const row = document.createElement('div');
                                row.className = 'py-1';
                                row.innerHTML = `<input data-idx="${idx}" class="w-full border rounded px-2 py-1 section-name-input" value="${name}"/>`;
                                list.appendChild(row);
                            });
                            area.appendChild(list);
                        }
                    }
                } catch(err){
                    console.error('generate error', err);
                    alert('Preview failed');
                }
            });
        });

        // Save to grade (bulk-create)
        document.querySelectorAll('.btn-save').forEach(btn => {

        // Auto-populate theme selects with a server-chosen random theme when empty.
        (async function autoPopulateThemes(){
            const themeSelects = Array.from(document.querySelectorAll('select[name="theme"]'));
            for(const sel of themeSelects){
                try{
                    const year = sel.getAttribute('data-year') || '';
                    if(sel.value && sel.value.trim() !== '') continue; // skip non-empty
                    const payload = { theme: '', count: 0, name: 'Grade ' + year };
                    const resp = await postJSON(previewUrl, payload);
                    if(resp){ const ok = setThemeSelect(sel, resp); if(!ok){
                            // fallback to client-side random pick
                            const opts = Array.from(sel.options).filter(o=>o.value && o.value.trim()!=='');
                            if(opts.length){ const pick = opts[Math.floor(Math.random()*opts.length)]; sel.value = pick.value; }
                        }
                    }
                } catch(err){
                    console.error('autopopulate theme error', err);
                }
            }
        })();
            btn.addEventListener('click', async (e) => {
                const gid = btn.getAttribute('data-grade-id');
                const area = document.querySelector('.preview-area[data-grade-id="'+gid+'"]');
                const inputs = Array.from(area.querySelectorAll('.section-name-input')).map(i=>i.value.trim()).filter(Boolean);
                if(!inputs.length){ alert('No section names to save'); return; }
                const resp = await postJSON(`/admin/it/grade-levels/${gid}/sections/bulk-create`, { names: inputs });
                if(resp && (resp.sections || resp.success)){
                    const countSpan = document.querySelector('.grade-count[data-grade-id="'+gid+'"]');
                    const newCount = Array.isArray(resp.sections) ? resp.sections.length : (resp.created_count ?? inputs.length);
                    if(countSpan) countSpan.textContent = newCount;
                    alert('Sections saved');
                } else {
                    alert('Failed to save sections');
                }
            });
        });

        // Add subject (Junior & Senior forms)
        const jrForm = document.getElementById('add-subject-jr');
        if(jrForm){
            jrForm.addEventListener('submit', async function(e){
                e.preventDefault();
                const form = e.target;
                const name = document.getElementById('subject-name-jr').value.trim();
                const selectEl = document.getElementById('subject-classification-jr');
                const newInput = document.getElementById('subject-classification-jr-new');
                let classification = '';
                if(selectEl){
                    if(selectEl.value === '__add__'){
                        classification = newInput ? (newInput.value || '').trim() : '';
                    } else {
                        classification = selectEl.value;
                    }
                } else {
                    classification = newInput ? (newInput.value || '').trim() : '';
                }
                const grades = Array.from(form.querySelectorAll('input[name="grades[]"]')).map(i=>i.value);
                if(!name) return alert('Provide a subject name');
                if(!classification) return alert('Provide or type a classification');
                const resp = await postJSON(subjectStoreUrl, { name, classification, grades });
                if(resp && resp.id){
                    alert('Subject added (Junior High)');
                    window.location.reload();
                } else {
                    alert('Failed to add subject');
                }
            });

            // toggle new-classification input
            const jrSelect = document.getElementById('subject-classification-jr');
            const jrNew = document.getElementById('subject-classification-jr-new');
            if(jrSelect && jrNew){
                jrSelect.addEventListener('change', function(){
                    if(this.value === '__add__'){
                        jrNew.classList.remove('hidden');
                        jrNew.focus();
                    } else {
                        jrNew.classList.add('hidden');
                        jrNew.value = '';
                    }
                });
            }
        }

        const srForm = document.getElementById('add-subject-sr');
        if(srForm){
            srForm.addEventListener('submit', async function(e){
                e.preventDefault();
                const form = e.target;
                const name = document.getElementById('subject-name-sr').value.trim();
                const selectEl = document.getElementById('subject-classification-sr');
                const newInput = document.getElementById('subject-classification-sr-new');
                let classification = '';
                if(selectEl){
                    if(selectEl.value === '__add__'){
                        classification = newInput ? (newInput.value || '').trim() : '';
                    } else {
                        classification = selectEl.value;
                    }
                } else {
                    classification = newInput ? (newInput.value || '').trim() : '';
                }
                const grades = Array.from(form.querySelectorAll('input[name="grades[]"]')).map(i=>i.value);
                if(!name) return alert('Provide a subject name');
                if(!classification) return alert('Provide or type a classification');
                const resp = await postJSON(subjectStoreUrl, { name, classification, grades });
                if(resp && resp.id){
                    alert('Subject added (Senior High)');
                    window.location.reload();
                } else {
                    alert('Failed to add subject');
                }
            });

            // toggle new-classification input
            const srSelect = document.getElementById('subject-classification-sr');
            const srNew = document.getElementById('subject-classification-sr-new');
            if(srSelect && srNew){
                srSelect.addEventListener('change', function(){
                    if(this.value === '__add__'){
                        srNew.classList.remove('hidden');
                        srNew.focus();
                    } else {
                        srNew.classList.add('hidden');
                        srNew.value = '';
                    }
                });
            }
        }

        // Subject grade list toggles & checkbox handlers
        document.querySelectorAll('.subject-toggle-open').forEach(btn => {
            btn.addEventListener('click', function(){
                const sid = this.dataset.subjectId;
                const list = document.querySelector('.subject-grade-list[data-subject-id="'+sid+'"]');
                if(list) list.classList.toggle('hidden');
            });
        });

        document.querySelectorAll('.subject-grade-checkbox').forEach(cb => {
            cb.addEventListener('change', async function(){
                const subjectId = this.dataset.subjectId;
                const gradeId = this.dataset.gradeId;
                const checked = this.checked;
                const url = `${toggleBase}/${subjectId}/toggle-grade/${gradeId}`;
                try {
                    const res = await fetch(url, { method: 'POST', headers: { 'X-CSRF-TOKEN': token, 'Content-Type': 'application/json', 'Accept':'application/json' }, body: JSON.stringify({ attach: checked }) });
                    const json = await res.json();
                    if(!(json && json.success)){
                        alert('Toggle failed');
                        this.checked = !checked; // revert UI
                    }
                } catch(err){
                    console.error(err);
                    alert('Network error');
                    this.checked = !checked;
                }
            });
        });

        // Tabs handlers (switch panels)
        const tabSections = document.getElementById('tab-sections');
        const tabSubjects = document.getElementById('tab-subjects');
        const panelSections = document.getElementById('panel-sections');
        const panelSubjects = document.getElementById('panel-subjects');

        function showSections(){
            panelSections.classList.remove('hidden');
            panelSubjects.classList.add('hidden');
            tabSections.setAttribute('aria-pressed','true');
            tabSubjects.setAttribute('aria-pressed','false');
        }
        function showSubjects(){
            panelSections.classList.add('hidden');
            panelSubjects.classList.remove('hidden');
            tabSections.setAttribute('aria-pressed','false');
            tabSubjects.setAttribute('aria-pressed','true');
        }

        tabSections.addEventListener('click', showSections);
        tabSubjects.addEventListener('click', showSubjects);

        // Global generate button: request preview for each grade-year that has a count > 0
        const globalGenerate = document.getElementById('global-generate');
        if(globalGenerate){
            globalGenerate.addEventListener('click', async function(){
                const counts = Array.from(document.querySelectorAll('input[name="count"]'));
                for (const inp of counts) {
                    const cnt = parseInt(inp.value || '0', 10);
                    const year = inp.getAttribute('data-year');
                    if (!year) continue;
                    const themeInput = document.querySelector('select[name="theme"][data-year="'+year+'"]');
                    const theme = themeInput ? themeInput.value.trim() : '';
                    if (cnt > 0) {
                        try {
                            const payload = { theme: theme, count: cnt, name: 'Grade ' + year };
                            const resp = await postJSON(previewUrl, payload);
                            const preview = resp.preview ?? resp.names ?? resp;
                            // if server returned the resolved theme, populate the theme input so UI reflects it
                            if (themeInput) setThemeSelect(themeInput, resp);
                            const area = document.querySelector('.preview-area[data-year="'+year+'"]');
                            if(area){
                                area.innerHTML = '';
                                if(Array.isArray(preview) && preview.length){
                                    const list = document.createElement('div');
                                    list.className = 'space-y-1';
                                    preview.forEach((item, idx) => {
                                        const name = (typeof item === 'string') ? item : (item.name ?? '');
                                        const row = document.createElement('div');
                                        row.className = 'py-1';
                                        row.innerHTML = `<input data-idx="${idx}" class="w-full border rounded px-2 py-1 section-name-input" value="${name}"/>`;
                                        list.appendChild(row);
                                    });
                                    area.appendChild(list);
                                }
                            }
                        } catch(err){
                            console.error('preview error', err);
                            // continue to next
                        }
                    }
                }
            });
        }

        // Debounce helper to avoid excessive requests while typing
        function debounce(fn, wait){
            let t;
            return function(...args){
                clearTimeout(t);
                t = setTimeout(()=>fn.apply(this, args), wait);
            };
        }

        // Live preview: when number of sections input changes, auto-generate preview for that year
        const countInputs = Array.from(document.querySelectorAll('input[name="count"]'));
        countInputs.forEach(inp => {
            const year = inp.getAttribute('data-year');
            const handler = debounce(async function(e){
                const cnt = parseInt(inp.value || '0', 10);
                const themeInput = document.querySelector('select[name="theme"][data-year="'+year+'"]');
                const theme = themeInput ? themeInput.value.trim() : '';
                const area = document.querySelector('.preview-area[data-year="'+year+'"]');
                if(!area) return;
                if(!cnt){
                    // clear preview if count is zero or empty
                    area.innerHTML = '';
                    return;
                }
                try{
                    const payload = { theme: theme, count: cnt, name: 'Grade ' + year };
                    const resp = await postJSON(previewUrl, payload);
                    const preview = resp.preview ?? resp.names ?? resp;
                    // populate theme input if server tells us which theme was used
                    if (resp) {
                        const themeInputLocal = document.querySelector('select[name="theme"][data-year="'+year+'"]');
                        if (themeInputLocal) setThemeSelect(themeInputLocal, resp);
                    }
                    area.innerHTML = '';
                    if(Array.isArray(preview) && preview.length){
                        const list = document.createElement('div');
                        list.className = 'space-y-1';
                        preview.forEach((item, idx) => {
                            const name = (typeof item === 'string') ? item : (item.name ?? '');
                            const row = document.createElement('div');
                            row.className = 'py-1';
                            row.innerHTML = `<input data-idx="${idx}" class="w-full border rounded px-2 py-1 section-name-input" value="${name}"/>`;
                            list.appendChild(row);
                        });
                        area.appendChild(list);
                    }
                } catch(err){
                    console.error('live preview error', err);
                }
            }, 450);

            inp.addEventListener('input', handler);

            // also regenerate when theme changes for the same year
            const themeInput = document.querySelector('select[name="theme"][data-year="'+year+'"]');
            if(themeInput){
                themeInput.addEventListener('change', debounce(function(){
                    // trigger the same handler as if count changed
                    handler();
                }, 450));
            }
        });

        // Save All: create grade if missing, then bulk-create sections for each previewed grade
        const saveAllBtn = document.getElementById('save-all');
        if (saveAllBtn) {
            saveAllBtn.addEventListener('click', async function(){
                if(!confirm('Save all generated sections for all grades? This will persist sections to the database.')) return;
                const previewAreas = Array.from(document.querySelectorAll('.preview-area'));
                let summary = [];
                for (const area of previewAreas){
                    const year = area.getAttribute('data-year');
                    const gidAttr = area.getAttribute('data-grade-id');
                    const inputs = Array.from(area.querySelectorAll('.section-name-input')).map(i=>i.value.trim()).filter(Boolean);
                    if(!inputs.length) continue;

                    let gradeId = gidAttr || null;
                    // if grade not persisted yet, create it
                    if(!gradeId){
                        const themeInput = document.querySelector('select[name="theme"][data-year="'+year+'"]');
                        const countInput = document.querySelector('input[name="count"][data-year="'+year+'"]');
                        const theme = themeInput ? themeInput.value.trim() : null;
                        const planned = countInput ? parseInt(countInput.value||'0',10) : 0;
                        try{
                            const createResp = await postJSON('/admin/it/grade-levels', { name: 'Grade ' + year, section_naming: theme, section_naming_options: { planned_sections: planned } });
                            if(createResp && createResp.grade && createResp.grade.id){
                                gradeId = createResp.grade.id;
                                // annotate DOM so future saves know id
                                area.setAttribute('data-grade-id', gradeId);
                            } else {
                                console.warn('failed to create grade for year', year);
                                summary.push({ year, created: 0, error: true });
                                continue;
                            }
                        } catch(err){
                            console.error('create grade error', err);
                            summary.push({ year, created: 0, error: true });
                            continue;
                        }
                    }

                    // now bulk-create sections
                    try{
                        const url = `/admin/it/grade-levels/${gradeId}/sections/bulk-create`;
                        const saveResp = await postJSON(url, { names: inputs });
                        const created = Array.isArray(saveResp.sections) ? saveResp.sections.length : (saveResp.created_count ?? inputs.length);
                        summary.push({ year, created });
                    } catch(err){
                        console.error('bulk create error', err);
                        summary.push({ year, created: 0, error: true });
                    }
                }

                // show a short summary
                const ok = summary.every(s => !s.error);
                if(ok){
                    alert('Sections saved for ' + summary.length + ' grades');
                    window.location.reload();
                } else {
                    alert('Some grades failed to save. Check console for details.');
                }
            });
        }

        // No UI for creating default grade levels per user's preference.
    })();
</script>
@endsection


