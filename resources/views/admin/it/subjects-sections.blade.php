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
                                    <div>
                                        <input name="count" data-year="{{ $yr }}" data-grade-id="{{ $gid }}" type="number" min="0" class="w-full border rounded px-2 py-1" value="{{ $planned }}">
                                    </div>
                                    
                                <div class="md:col-span-4 mt-2">
                                    <div class="preview-area" data-year="{{ $yr }}" data-grade-id="{{ $gid }}"></div>
                                    @php $savedSections = ($grade && $grade->sections) ? $grade->sections->sortBy('ordinal') : collect(); @endphp
                                    @if($savedSections->count())
                                        <div class="mt-3">
                                            <div class="text-sm font-medium mb-2">Saved sections</div>
                                            <div class="flex flex-wrap gap-2">
                                                @foreach($savedSections as $sec)
                                                    <span class="px-2 py-1 bg-gray-100 rounded text-sm">{{ $sec->name }}</span>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
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
                                        <div>
                                            <input name="count" data-year="{{ $yr }}" data-grade-id="{{ $gid }}" type="number" min="0" class="w-full border rounded px-2 py-1" value="{{ $planned }}">
                                        </div>
                                    
                                <div class="md:col-span-4 mt-2">
                                    <div class="preview-area" data-year="{{ $yr }}" data-grade-id="{{ $gid }}"></div>
                                        @php $savedSections = ($grade && $grade->sections) ? $grade->sections->sortBy('ordinal') : collect(); @endphp
                                        @if($savedSections->count())
                                            <div class="mt-3">
                                                <div class="text-sm font-medium mb-2">Saved sections</div>
                                                <div class="flex flex-wrap gap-2">
                                                    @foreach($savedSections as $sec)
                                                        <span class="px-2 py-1 bg-gray-100 rounded text-sm">{{ $sec->name }}</span>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
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

            <!-- Unified Add Subject form -->
            <div class="mb-4">
                <form id="add-subject" class="space-y-3">
                    <div>
                        <label class="block text-xs mb-1">Subject Name(s)</label>
                        <textarea id="subject-name" name="name" rows="4" class="w-full border rounded px-2 py-1" placeholder="e.g. Mathematics\nScience\nEnglish (one per line)"></textarea>
                        <div class="text-xs text-slate-500 mt-1">You may add multiple subjects at once by entering one subject per line. Commas are allowed inside titles.</div>
                    </div>
                    <div>
                        <label class="block text-xs mb-1">Classification</label>
                        <select id="subject-classification" name="classification" class="w-full border rounded px-2 py-1">
                            <option value="abm">ABM Subjects</option>
                            <option value="humss">HUMSS</option>
                            <option value="stem">STEM</option>
                            <option value="tvl">TVL</option>
                            <option value="gas">GAS</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs mb-1">Grades</label>
                        <div class="space-y-2">
                            <div class="flex items-center gap-3">
                                <label class="inline-flex items-center gap-2 group-toggle-mini"><input type="checkbox" id="select-junior" data-years="7,8,9,10"> <span class="ml-1 font-medium">Junior High (7-10)</span></label>
                                <div class="flex items-center gap-2 ml-4">
                                    @foreach(collect($gradeLevels)->sortBy('year') as $g)
                                        @if($g->year >= 7 && $g->year <= 10)
                                            <label class="inline-flex items-center gap-1 text-xs"><input type="checkbox" class="grade-checkbox mini" data-year="{{ $g->year }}" name="grades[]" value="{{ $g->id }}"> <span>{{ $g->name }}</span></label>
                                        @endif
                                    @endforeach
                                </div>
                            </div>

                            <div class="flex items-center gap-3">
                                <label class="inline-flex items-center gap-2 group-toggle-mini"><input type="checkbox" id="select-senior" data-years="11,12"> <span class="ml-1 font-medium">Senior High (11-12)</span></label>
                                <div class="flex items-center gap-2 ml-4">
                                    @foreach(collect($gradeLevels)->sortBy('year') as $g)
                                        @if($g->year >= 11 && $g->year <= 12)
                                            <label class="inline-flex items-center gap-1 text-xs"><input type="checkbox" class="grade-checkbox mini" data-year="{{ $g->year }}" name="grades[]" value="{{ $g->id }}"> <span>{{ $g->name }}</span></label>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <button type="submit" class="bg-indigo-600 text-white px-3 py-1 rounded">Add Subject</button>
                    </div>
                </form>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Junior High Subjects Card (empty state / management) -->
                <div class="border rounded p-4 bg-white">
                    <div class="flex items-center justify-between mb-3">
                        <div class="font-medium">Junior High Subjects</div>
                        <div class="text-sm text-slate-500">Grades 7 &ndash; 10</div>
                    </div>

                    <div class="mb-4">
                        <!-- Management area: list Junior High subjects (Grades 7-10) -->
                        @php
                            $juniorSubjects = $subjects->filter(function($s){
                                return $s->gradeLevels->pluck('year')->filter(function($y){ return $y >= 7 && $y <= 10; })->count();
                            });
                        @endphp
                        @if($juniorSubjects->count())
                            @php
                                $juniorGroups = $juniorSubjects->groupBy(function($s){ return $s->type ?: 'core'; });
                                $typeLabels = [
                                    'core' => 'Core Subjects',
                                    'abm' => 'ABM',
                                    'humss' => 'HUMSS',
                                    'stem' => 'STEM',
                                    'tvl' => 'TVL',
                                    'gas' => 'GAS',
                                    'shs_core' => 'SHS Core',
                                ];
                            @endphp
                            <div class="space-y-3 text-sm">
                                @foreach($juniorGroups as $type => $items)
                                    <div class="border rounded">
                                        <button type="button" class="group-toggle w-full text-left px-3 py-2 flex items-center justify-between bg-gray-50 hover:bg-gray-100" data-target="junior-{{ $type }}">
                                            <span class="font-medium">{{ $typeLabels[$type] ?? strtoupper($type) }}</span>
                                            <span class="text-xs text-slate-500">{{ $items->count() }} item(s)</span>
                                        </button>
                                        <div id="junior-{{ $type }}" class="group-body px-3 py-2">
                                            <ul class="space-y-1">
                                                @foreach($items as $sub)
                                                    <li class="py-1"><div class="font-medium">{{ $sub->name }}</div></li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-sm text-slate-500">No subjects yet for Junior High.</div>
                        @endif
                    </div>
                </div>

                <!-- Senior High Subjects Card -->
                <div class="border rounded p-4 bg-white">
                    <div class="flex items-center justify-between mb-3">
                        <div class="font-medium">Senior High Subjects</div>
                        <div class="text-sm text-slate-500">Grades 11 &ndash; 12</div>
                    </div>

                    <div class="mb-4">
                        <!-- Management area: list Senior High subjects (Grades 11-12) -->
                        @php
                            $seniorSubjects = $subjects->filter(function($s){
                                return $s->gradeLevels->pluck('year')->filter(function($y){ return $y >= 11 && $y <= 12; })->count();
                            });
                        @endphp
                        @if($seniorSubjects->count())
                            @php
                                $seniorGroups = $seniorSubjects->groupBy(function($s){ return $s->type ?: 'core'; });
                            @endphp
                            <div class="space-y-3 text-sm">
                                @foreach($seniorGroups as $type => $items)
                                    <div class="border rounded">
                                        <button type="button" class="group-toggle w-full text-left px-3 py-2 flex items-center justify-between bg-gray-50 hover:bg-gray-100" data-target="senior-{{ $type }}">
                                            <span class="font-medium">{{ $typeLabels[$type] ?? strtoupper($type) }}</span>
                                            <span class="text-xs text-slate-500">{{ $items->count() }} item(s)</span>
                                        </button>
                                        <div id="senior-{{ $type }}" class="group-body px-3 py-2">
                                            <ul class="space-y-1">
                                                @foreach($items as $sub)
                                                    <li class="py-1"><div class="font-medium">{{ $sub->name }}</div></li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-sm text-slate-500">No subjects yet for Senior High.</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<!-- Toggle pill styles for Regular / Special -->
<style>
    /* container for previous pill kept for backwards compat */
    .toggle-pill{ display:inline-flex; align-items:center; gap:1rem; border-radius:9999px; padding:6px 12px; cursor:pointer; user-select:none; border:1px solid #e6eef6; background:#f8fafc; }

    /* Segmented two-button control (Regular / Special) */
    .seg-toggle{ display:flex; position:relative; align-items:stretch; border-radius:9999px; overflow:visible; border:1px solid #d7e1e6; }
    /* center divider drawn as pseudo-element so there's no layout gap between buttons */
    /* make it thinner and sit above the button background but under the focus ring */
    .seg-toggle::before{ content:''; position:absolute; left:50%; top:8px; bottom:8px; width:2px; background:rgba(59,65,151,0.08); transform:translateX(-50%); border-radius:2px; z-index:3; pointer-events:none; }
    /* focus visuals intentionally removed per user preference */
    .seg-toggle .seg-btn{ flex:1 1 0; padding:10px 16px; font-size:0.95rem; background:transparent; border:0; cursor:pointer; color:#334155; margin:0; line-height:1; display:inline-flex; align-items:center; justify-content:center; font-weight:600; position:relative; z-index:2; }
    /* active uses brand blue and white text; keep weight consistent */
    .seg-toggle .seg-btn.active{ background:#3B4197; color:#ffffff; }
    .seg-toggle .seg-btn:not(.active){ opacity:0.95; }
    /* remove per-button box-shadow focus which produced rectangular artifact; rely on container focus ring instead */
    .seg-toggle .seg-btn:focus{ outline:0; }
    /* rounded halves so active background respects the capsule ends */
    .seg-toggle .seg-left{ border-radius:9999px 0 0 9999px; }
    .seg-toggle .seg-right{ border-radius:0 9999px 9999px 0; }
    .seg-toggle.pill-small .seg-btn{ padding:6px 10px; font-size:0.82rem; }

    /* keep old pill styles for any remaining references */
    .toggle-pill .pill-label{ font-size:0.85rem; padding:4px 8px; color:#475569; display:inline-block; position:relative; z-index:3; transition:color .12s ease, opacity .12s ease; }
</style>
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
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token, 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
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

        // Delegated handler: segmented two-button Regular / Special control
        document.addEventListener('click', function(e){
            // if a segment button was clicked
            const segBtn = e.target.closest ? e.target.closest('.seg-btn') : null;
            if(!segBtn) return;
            const container = segBtn.closest('.btn-toggle-special');
            if(!container) return;
            const val = segBtn.getAttribute('data-value') === '1' ? '1' : '0';
            container.dataset.special = val;
            container.setAttribute('aria-pressed', val === '1' ? 'true' : 'false');
            // update active classes on children
            const children = Array.from(container.querySelectorAll('.seg-btn'));
            children.forEach(c => c.classList.toggle('active', c === segBtn));
        });

        // Ensure toggles default to Regular when not explicitly set by server
        function ensureToggleDefaults(container){
            if(!container) container = document;
            const toggles = Array.from(container.querySelectorAll('.btn-toggle-special'));
            toggles.forEach(t=>{
                // if dataset.special is missing/empty, set default to '0' (Regular)
                if(typeof t.dataset.special === 'undefined' || t.dataset.special === null || t.dataset.special === ''){
                    t.dataset.special = '0';
                }
                // update visual state based on dataset.special
                const isSpecial = t.dataset.special === '1';
                t.setAttribute('aria-pressed', isSpecial ? 'true' : 'false');
                const left = t.querySelector('.seg-left');
                const right = t.querySelector('.seg-right');
                if(left) left.classList.toggle('active', !isSpecial);
                if(right) right.classList.toggle('active', isSpecial);
            });
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
                        const payload = (typeof n === 'string') ? { name: n } : n || {};
                        const name = payload.name ?? '';
                        const isSpecial = payload.is_special == 1 || payload.is_special === true ? true : false;
                        const row = document.createElement('div');
                        row.className = 'flex items-center gap-2';
                        row.innerHTML = `<input data-idx="${idx}" class="w-full border rounded px-2 py-1 section-name-input" value="${name}"/>` +
                                        `<div class="btn-toggle-special seg-toggle ml-2 pill-small" data-idx="${idx}" data-special="${isSpecial ? '1' : '0'}" role="tablist" aria-pressed="${isSpecial ? 'true' : 'false'}">` +
                                            `<button type="button" class="seg-btn seg-left ${isSpecial ? '' : 'active'}" data-value="0">Regular</button>` +
                                            `<button type="button" class="seg-btn seg-right ${isSpecial ? 'active' : ''}" data-value="1">Special</button>` +
                                        `</div>`;
                        list.appendChild(row);
                    });
                    area.appendChild(list);
                    ensureToggleDefaults(area);
                    // (removed per-list mark-all controls — use per-row toggle buttons)
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
                                const payload = (typeof item === 'string') ? { name: item } : item || {};
                                const name = payload.name ?? '';
                                const isSpecial = payload.is_special == 1 || payload.is_special === true ? true : false;
                                const row = document.createElement('div');
                                row.className = 'flex items-center gap-2 py-1';
                                row.innerHTML = `<input data-idx="${idx}" class="flex-1 border rounded px-2 py-1 section-name-input" value="${name}"/>` +
                                                `<div class="btn-toggle-special seg-toggle ml-2 pill-small" data-idx="${idx}" data-special="${isSpecial ? '1' : '0'}" role="tablist" aria-pressed="${isSpecial ? 'true' : 'false'}">` +
                                                    `<button type="button" class="seg-btn seg-left ${isSpecial ? '' : 'active'}" data-value="0">Regular</button>` +
                                                    `<button type="button" class="seg-btn seg-right ${isSpecial ? 'active' : ''}" data-value="1">Special</button>` +
                                                `</div>`;
                                list.appendChild(row);
                            });
                            area.appendChild(list);
                            ensureToggleDefaults(area);
                            // (removed per-list mark-all controls — use per-row toggle buttons)
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
                const items = Array.from(area.querySelectorAll('.section-name-input')).map(i=>{
                    const idx = i.getAttribute('data-idx');
                    const name = i.value.trim();
                    const btn = area.querySelector('.btn-toggle-special[data-idx="'+idx+'"]');
                    return { name, is_special: btn && btn.dataset && btn.dataset.special === '1' ? 1 : 0 };
                }).filter(it=>it.name);
                if(!items.length){ alert('No section names to save'); return; }
                const resp = await postJSON(`/admin/it/grade-levels/${gid}/sections/bulk-create`, { items });
                if(resp && (resp.sections || resp.success)){
                    const countSpan = document.querySelector('.grade-count[data-grade-id="'+gid+'"]');
                    const newCount = Array.isArray(resp.sections) ? resp.sections.length : (resp.created_count ?? items.length);
                    if(countSpan) countSpan.textContent = newCount;
                    alert('Sections saved');
                } else {
                    alert('Failed to save sections');
                }
            });
        });

        

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
                                        const payload = (typeof item === 'string') ? { name: item } : item || {};
                                        const name = payload.name ?? '';
                                        const isSpecial = payload.is_special == 1 || payload.is_special === true ? true : false;
                                        const row = document.createElement('div');
                                            row.className = 'flex items-center gap-2 py-1';
                                                row.innerHTML = `<input data-idx="${idx}" class="flex-1 border rounded px-2 py-1 section-name-input" value="${name}"/>` +
                                                                `<div class="btn-toggle-special seg-toggle ml-2 pill-small" data-idx="${idx}" data-special="${isSpecial ? '1' : '0'}" role="tablist" aria-pressed="${isSpecial ? 'true' : 'false'}">` +
                                                                    `<button type="button" class="seg-btn seg-left ${isSpecial ? '' : 'active'}" data-value="0">Regular</button>` +
                                                                    `<button type="button" class="seg-btn seg-right ${isSpecial ? 'active' : ''}" data-value="1">Special</button>` +
                                                                `</div>`;
                                        list.appendChild(row);
                                    });
                                    area.appendChild(list);
                                    ensureToggleDefaults(area);
                                    // (removed per-list mark-all controls — use per-row toggle buttons)
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
                            const payload = (typeof item === 'string') ? { name: item } : item || {};
                            const name = payload.name ?? '';
                            const isSpecial = payload.is_special == 1 || payload.is_special === true ? true : false;
                            const row = document.createElement('div');
                                row.className = 'flex items-center gap-2 py-1';
                                    row.innerHTML = `<input data-idx="${idx}" class="flex-1 border rounded px-2 py-1 section-name-input" value="${name}"/>` +
                                                    `<div class="btn-toggle-special seg-toggle ml-2 pill-small" data-idx="${idx}" data-special="${isSpecial ? '1' : '0'}" role="tablist" aria-pressed="${isSpecial ? 'true' : 'false'}">` +
                                                        `<button type="button" class="seg-btn seg-left ${isSpecial ? '' : 'active'}" data-value="0">Regular</button>` +
                                                        `<button type="button" class="seg-btn seg-right ${isSpecial ? 'active' : ''}" data-value="1">Special</button>` +
                                                    `</div>`;
                            list.appendChild(row);
                        });
                        area.appendChild(list);
                        ensureToggleDefaults(area);
                        // (removed per-list mark-all controls — use per-row toggle buttons)
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
                    const items = Array.from(area.querySelectorAll('.section-name-input')).map(i=>{
                        const idx = i.getAttribute('data-idx');
                        const name = i.value.trim();
                        const btn = area.querySelector('.btn-toggle-special[data-idx="'+idx+'"]');
                        return { name, is_special: btn && btn.dataset && btn.dataset.special === '1' ? 1 : 0 };
                    }).filter(it=>it.name);
                    if(!items.length) continue;

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
                        const saveResp = await postJSON(url, { items: items });
                        const created = Array.isArray(saveResp.sections) ? saveResp.sections.length : (saveResp.created_count ?? items.length);
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

        // Unified Add Subject form (handles both Junior and Senior submissions)
        const addSubjectForm = document.getElementById('add-subject');
        if(addSubjectForm){
            addSubjectForm.addEventListener('submit', async function(e){
                e.preventDefault();
                // allow multiple subject names (comma or newline separated)
                const raw = document.getElementById('subject-name').value || '';
                const classification = document.getElementById('subject-classification').value;
                const grades = Array.from(addSubjectForm.querySelectorAll('input[name="grades[]"]:checked')).map(i=>i.value);
                // split only on newlines so commas inside titles are preserved
                const names = raw.split(/\r?\n+/).map(s=>s.trim()).filter(Boolean);
                if(!names.length) return alert('Provide at least one subject name');
                if(!classification) return alert('Select a classification');
                if(!grades.length) return alert('Select at least one grade');

                const results = { created: 0, existing: 0, failed: 0 };
                for(const nm of names){
                    try{
                        // map classification -> type and grades -> grade_levels for backend
                        const payload = { name: nm, type: classification, grade_levels: grades };
                        const resp = await postJSON(subjectStoreUrl, payload);
                        if(resp && resp.success){
                            if(resp.created === true) results.created++;
                            else results.existing++;
                        } else {
                            results.failed++;
                        }
                    } catch(err){
                        console.error('add subject error', err);
                        results.failed++;
                    }
                }

                

                // Show summary
                let msg = [];
                if(results.created) msg.push(`${results.created} added`);
                if(results.existing) msg.push(`${results.existing} already existed`);
                if(results.failed) msg.push(`${results.failed} failed`);
                if(msg.length) {
                    alert(msg.join(', '));
                    window.location.reload();
                } else {
                    alert('No subjects were added');
                }
            });
        }

    // Grade group selectors: toggle ranges (Junior / Senior)
    const selectJunior = document.getElementById('select-junior');
    const selectSenior = document.getElementById('select-senior');
    const gradeCheckboxes = Array.from(document.querySelectorAll('.grade-checkbox'));

        function setGroupChecked(yearsCsv, checked){
            const years = String(yearsCsv).split(',').map(s=>s.trim());
            gradeCheckboxes.forEach(cb => {
                const y = cb.getAttribute('data-year');
                if(years.indexOf(String(y)) !== -1){ cb.checked = checked; }
            });
        }

        if(selectJunior){
            selectJunior.addEventListener('change', function(){ setGroupChecked(this.dataset.years, this.checked); updateGroupStates(); });
        }
        if(selectSenior){
            selectSenior.addEventListener('change', function(){ setGroupChecked(this.dataset.years, this.checked); updateGroupStates(); });
        }

        function updateGroupStates(){
            const juniorYears = (selectJunior && selectJunior.dataset.years) ? selectJunior.dataset.years.split(',') : [];
            const seniorYears = (selectSenior && selectSenior.dataset.years) ? selectSenior.dataset.years.split(',') : [];

            if(selectJunior){
                selectJunior.checked = gradeCheckboxes.filter(cb => juniorYears.indexOf(cb.getAttribute('data-year'))!==-1).every(cb=>cb.checked);
            }
            if(selectSenior){
                selectSenior.checked = gradeCheckboxes.filter(cb => seniorYears.indexOf(cb.getAttribute('data-year'))!==-1).every(cb=>cb.checked);
            }
        }

        gradeCheckboxes.forEach(cb => cb.addEventListener('change', updateGroupStates));

        // Collapsible groups for subject classifications
        document.querySelectorAll('.group-toggle').forEach(btn => {
            const target = btn.dataset.target;
            const body = document.getElementById(target);
            if(!body) return;
            // default: expanded
            body.style.display = 'block';
            btn.addEventListener('click', () => {
                const isHidden = body.style.display === 'none';
                body.style.display = isHidden ? 'block' : 'none';
            });
        });

        // No UI for creating default grade levels per user's preference.
    })();
</script>
@endsection


