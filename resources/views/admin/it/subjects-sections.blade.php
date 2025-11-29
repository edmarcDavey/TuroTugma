@extends('admin.it.layout')

@section('title','Sections & Subjects')
@section('heading','Sections & Subjects')

@section('content')
<div class="container mx-auto p-6">

    <!-- Tabs -->
    <div class="mb-4">
        <div class="inline-flex ss-tabs">
            <button type="button" id="tab-sections" class="px-4 py-2 text-sm font-medium ss-tab" aria-pressed="true" role="tab" aria-controls="panel-sections">Sections</button>
            <button type="button" id="tab-subjects" class="px-4 py-2 text-sm font-medium ss-tab" aria-pressed="false" role="tab" aria-controls="panel-subjects">Subjects</button>
        </div>
    </div>

            <div id="ss-grid" class="grid grid-cols-1 gap-6">
                <div id="panel-sections" role="tabpanel" aria-labelledby="tab-sections">
                @php
                    // themes config
                    $allThemes = config('section_themes.themes');
                    // $hasSections should be provided by the route; fallback to DB check if not set
                    $hasSections = $hasSections ?? \App\Models\Section::exists();
                @endphp

                @if(!$hasSections)
                    <div class="space-y-6">
                        <!-- Junior High builder -->
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
                                        // if theme not set, pick a random theme key so each grade may get a different theme
                                        $themeKeys = array_keys($allThemes ?: []);
                                        $selectedThemeKey = $theme ?: (count($themeKeys) ? $themeKeys[array_rand($themeKeys)] : '');
                                    @endphp
                                    <div class="grid grid-cols-1 md:grid-cols-4 gap-3 items-center py-2 border-b">
                                        <div class="md:col-span-1 font-medium">{{ $name }}</div>
                                        <div>
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
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Senior High builder -->
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
                                        $themeKeys = array_keys($allThemes ?: []);
                                        $selectedThemeKey = $theme ?: (count($themeKeys) ? $themeKeys[array_rand($themeKeys)] : '');
                                    @endphp
                                    <div class="grid grid-cols-1 md:grid-cols-4 gap-3 items-center py-2 border-b">
                                        <div class="md:col-span-1 font-medium">{{ $name }}</div>
                                        <div>
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
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="mt-4 flex justify-end">
                            <button id="save-all" type="button" class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700 w-36 text-center">Create Sections</button>
                        </div>
                    </div>
                @else
                    <div id="server-created-sections" class="space-y-6">
                        <div class="border rounded p-4 bg-white">
                            <div class="flex items-center justify-between mb-3">
                                <div class="font-medium">Junior High Sections</div>
                                <div class="text-sm text-slate-500">Grades 7 &ndash; 10</div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                @foreach(range(7,10) as $yr)
                                    @php $grade = $gradeLevels->firstWhere('year',$yr); 
                                        $secs = collect();
                                        if($grade && $grade->sections){
                                            // For Junior High, put special sections first then order by ordinal
                                            $secs = $grade->sections->sortBy(function($s){
                                                $key = ($s->is_special ? 0 : 1) * 100000 + (($s->ordinal !== null) ? $s->ordinal : 99999);
                                                return $key;
                                            });
                                        }
                                    @endphp
                                    <div class="border rounded p-3 bg-gray-50">
                                        <div class="flex items-center justify-between mb-2">
                                            <div class="font-medium">Grade {{ $yr }}</div>
                                            <div class="flex items-center gap-3">
                                                <div class="text-xs text-slate-500">{{ $secs->count() }} item(s)</div>
                                                @if($grade && $grade->id)
                                                    <button type="button" class="text-sm text-indigo-600 grade-edit-btn" data-grade-id="{{ $grade->id }}" data-year="{{ $yr }}">Edit</button>
                                                @endif
                                            </div>
                                        </div>
                                        @if($secs->count())
                                            <ul class="space-y-1">
                                                @foreach($secs as $s)
                                                    <li class="py-1" data-section-id="{{ $s->id }}">
                                                        <div class="flex items-center justify-between">
                                                            <div class="font-medium section-name">{{ $s->name }}</div>
                                                            <div class="text-xs text-slate-500 section-meta">{{ $s->track ?? ($s->is_special ? 'Special' : '') }}</div>
                                                        </div>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <div class="text-sm text-slate-500">No sections</div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="border rounded p-4 bg-white">
                            <div class="flex items-center justify-between mb-3">
                                <div class="font-medium">Senior High Sections</div>
                                <div class="text-sm text-slate-500">Grades 11 &ndash; 12</div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach(range(11,12) as $yr)
                                    @php $grade = $gradeLevels->firstWhere('year',$yr); 
                                        $secs = collect();
                                        if($grade && $grade->sections){
                                            // For Senior High we keep ordinal ordering; SHS tracks will be shown in meta
                                            $secs = $grade->sections->sortBy('ordinal');
                                        }
                                    @endphp
                                    <div class="border rounded p-3 bg-gray-50">
                                        <div class="flex items-center justify-between mb-2"><div class="font-medium">Grade {{ $yr }}</div><div class="text-xs text-slate-500">{{ $secs->count() }} item(s)</div></div>
                                        @if($secs->count())
                                            <ul class="space-y-1">
                                                @foreach($secs as $s)
                                                    <li class="py-1">
                                                        <div class="flex items-center justify-between">
                                                            <div class="font-medium">{{ $s->name }}</div>
                                                            <div class="text-xs text-slate-500">{{ $s->track ?? ($s->is_special ? 'Special' : '') }}</div>
                                                        </div>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <div class="text-sm text-slate-500">No sections</div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
                </div>
            </div>

        <!-- Subjects panel -->
        <div id="panel-subjects" class="hidden bg-white border rounded p-4" role="tabpanel" aria-labelledby="tab-subjects">
            <h2 class="font-semibold mb-3">Subjects</h2>
            <p class="text-sm text-slate-600 mb-4">Manage subjects for Junior High and Senior High separately. No seeded subjects are shown by default.</p>

            <!-- Unified Add Subject form -->
            <div class="mb-4">
                <form id="add-subject" class="space-y-3">
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
                        <label class="block text-xs mb-1">Classification</label>
                        <select id="subject-classification" name="classification" class="w-full border rounded px-2 py-1">
                            <option value="core">Core Subjects</option>
                            <option value="special">Special Subjects</option>
                            <option value="abm">ABM - Accountancy, Business and Management Strand</option>
                            <option value="humss">HUMSS - Humanity and Social Science Strand</option>
                            <option value="stem">STEM - Science, Technology, Engineering, and Mathematics Strand</option>
                            <option value="tvl">TVL - Technology-Vocational-Livelihood Strand</option>
                            <option value="gas">GAS - General Academic Strand</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs mb-1">Subject Name(s)</label>
                        <div class="relative">
                            <input id="subject-name" name="name" type="text" class="w-full border rounded px-2 py-1" placeholder="Type a subject name and press Enter">
                            <div id="subject-tags" class="mt-2 flex flex-wrap gap-2"></div>
                        </div>
                        <div class="text-xs text-slate-500 mt-1">Type a subject and press Enter to add. Click the × on a tag to remove it.</div>
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
                                                    <li class="py-1" data-subject-id="{{ $sub->id }}">
                                                        <div class="flex items-center justify-between">
                                                            <div class="font-medium subject-name">{{ $sub->name }}</div>
                                                            <div class="flex items-center gap-2">
                                                                <button type="button" class="text-xs text-blue-600 subject-edit-btn" data-id="{{ $sub->id }}">Edit</button>
                                                                <button type="button" class="text-xs text-red-600 subject-delete-btn" data-id="{{ $sub->id }}">Delete</button>
                                                            </div>
                                                        </div>
                                                    </li>
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
                                                    <li class="py-1" data-subject-id="{{ $sub->id }}">
                                                        <div class="flex items-center justify-between">
                                                            <div class="font-medium subject-name">{{ $sub->name }}</div>
                                                            <div class="flex items-center gap-2">
                                                                <button type="button" class="text-xs text-blue-600 subject-edit-btn" data-id="{{ $sub->id }}">Edit</button>
                                                                <button type="button" class="text-xs text-red-600 subject-delete-btn" data-id="{{ $sub->id }}">Delete</button>
                                                            </div>
                                                        </div>
                                                    </li>
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

    <!-- Grade Editor Modal -->
    <div id="grade-modal" class="hidden fixed inset-0 z-50 items-center justify-center">
        <div class="absolute inset-0 bg-black opacity-40"></div>
        <div class="relative bg-white rounded shadow-lg w-full max-w-3xl mx-4 p-4" role="dialog" aria-modal="true" aria-labelledby="grade-modal-title">
            <div class="flex items-center justify-between mb-3">
                <h3 id="grade-modal-title" class="text-lg font-medium">Edit Grade Sections</h3>
                <button type="button" id="grade-modal-close" class="text-gray-600 hover:text-gray-800">&times;</button>
            </div>
            <div id="grade-modal-body">Loading…</div>
        </div>
    </div>

<!-- Edit Subject Modal (inserted into page content so fragment HTML can target it) -->
<div id="subject-modal" class="hidden fixed inset-0 z-50 items-center justify-center">
    <div class="absolute inset-0 bg-black opacity-40"></div>
    <div class="relative bg-white rounded shadow-lg w-full max-w-2xl mx-4 p-4" role="dialog" aria-modal="true" aria-labelledby="subject-modal-title">
        <div class="flex items-center justify-between mb-3">
            <h3 id="subject-modal-title" class="text-lg font-medium">Edit Subject</h3>
            <button type="button" id="subject-modal-close" class="text-gray-600 hover:text-gray-800">&times;</button>
        </div>
        <div id="subject-modal-body">Loading…</div>
    </div>
</div>

@section('scripts')
<!-- Toggle pill styles for Regular / Special -->
<style>
    /* Tabs: underline indicator and hover state to match the provided design */
    .ss-tabs{ padding-bottom:8px; background:transparent; border:0; }
    .ss-tabs .ss-tab{ position:relative; background:transparent; border:0; margin:0; color:#374151; cursor:pointer; padding:8px 16px; }
    .ss-tabs .ss-tab:hover{ color:#3B4197; }
    .ss-tabs .ss-tab:focus{ outline:0; }
    /* underline indicator — centered and thin */
    .ss-tabs .ss-tab::after{ content:''; position:absolute; left:22%; right:22%; bottom:0; height:3px; background:transparent; border-radius:2px; transition:background-color .12s ease, transform .12s ease; }
    .ss-tabs .ss-tab[aria-pressed="true"]{ color:#3B4197; font-weight:600; }
    .ss-tabs .ss-tab[aria-pressed="true"]::after{ background:#3B4197; }

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

        const SHS_TRACK_OPTIONS = ['STEM','ABM','HUMSS','TVL','GAS'];

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

        // Ensure toggles default to Regular when not explicitly set by server
        function ensureToggleDefaults(container){
            if(!container) container = document;
            const toggles = Array.from(container.querySelectorAll('.btn-toggle-special'));
            toggles.forEach(t=>{
                if(typeof t.dataset.special === 'undefined' || t.dataset.special === null || t.dataset.special === ''){
                    t.dataset.special = '0';
                }
                const isSpecial = t.dataset.special === '1';
                t.setAttribute('aria-pressed', isSpecial ? 'true' : 'false');
                const left = t.querySelector('.seg-left');
                const right = t.querySelector('.seg-right');
                if(left) left.classList.toggle('active', !isSpecial);
                if(right) right.classList.toggle('active', isSpecial);
            });
        }

        // Delegated handler: segmented two-button Regular / Special control
        document.addEventListener('click', function(e){
            const segBtn = e.target.closest ? e.target.closest('.seg-btn') : null;
            if(!segBtn) return;
            const container = segBtn.closest('.btn-toggle-special');
            if(!container) return;
            const val = segBtn.getAttribute('data-value') === '1' ? '1' : '0';
            container.dataset.special = val;
            container.setAttribute('aria-pressed', val === '1' ? 'true' : 'false');
            const children = Array.from(container.querySelectorAll('.seg-btn'));
            children.forEach(c => c.classList.toggle('active', c === segBtn));
        });

        // (Builder-related preview/generate/save JS removed to slim page; subject handlers remain below)
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

        function activateTab(tab){
            if(!tab) return;
            if(tab === 'sections'){
                panelSections.classList.remove('hidden');
                panelSubjects.classList.add('hidden');
                tabSections.setAttribute('aria-pressed','true');
                tabSubjects.setAttribute('aria-pressed','false');
                tabSections.focus();
            } else {
                panelSections.classList.add('hidden');
                panelSubjects.classList.remove('hidden');
                tabSections.setAttribute('aria-pressed','false');
                tabSubjects.setAttribute('aria-pressed','true');
                tabSubjects.focus();
            }
            try{ localStorage.setItem('ss-active-tab', tab); }catch(e){}
        }

        function showSections(){ activateTab('sections'); }
        function showSubjects(){ activateTab('subjects'); }

        tabSections.addEventListener('click', showSections);
        tabSubjects.addEventListener('click', showSubjects);

        // initialize from localStorage (persist last active tab)
        try{
            const saved = localStorage.getItem('ss-active-tab');
            if(saved === 'subjects') activateTab('subjects'); else activateTab('sections');
        }catch(e){ /* ignore */ }

        // keyboard navigation: left/right arrows switch tabs
        const tabButtons = [tabSections, tabSubjects];
        tabButtons.forEach((btn, idx) => {
            btn.addEventListener('keydown', (ev) => {
                if(ev.key === 'ArrowRight' || ev.key === 'ArrowLeft'){
                    ev.preventDefault();
                    const nextIdx = ev.key === 'ArrowRight' ? (idx + 1) % tabButtons.length : (idx - 1 + tabButtons.length) % tabButtons.length;
                    tabButtons[nextIdx].focus();
                    // also activate on arrow navigation
                    const id = tabButtons[nextIdx].id === 'tab-sections' ? 'sections' : 'subjects';
                    activateTab(id);
                }
                if(ev.key === 'Enter' || ev.key === ' '){ ev.preventDefault(); btn.click(); }
            });
        });

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
                            // if theme is empty, pick a random theme from the select options for this year
                            let chosenTheme = theme;
                            if(!chosenTheme){
                                if (themeInput && themeInput.options && themeInput.options.length){
                                    // collect non-empty option values
                                    const opts = Array.from(themeInput.options).map(o=>o.value).filter(v=>v);
                                    if(opts.length) chosenTheme = opts[Math.floor(Math.random()*opts.length)];
                                }
                            }
                            const payload = { theme: chosenTheme, count: cnt, name: 'Grade ' + year };
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
                            const trackVal = payload.track || '';
                            const row = document.createElement('div');
                                row.className = 'flex items-center gap-2 py-1';
                                const year = (area && area.getAttribute) ? area.getAttribute('data-year') : null;
                                let html = `<input data-idx="${idx}" class="flex-1 border rounded px-2 py-1 section-name-input" value="${name}"/>`;
                                // show Regular/Special only for Junior High (years < 11)
                                if(!year || parseInt(year,10) < 11){
                                    html += `<div class="btn-toggle-special seg-toggle ml-2 pill-small" data-idx="${idx}" data-special="${isSpecial ? '1' : '0'}" role="tablist" aria-pressed="${isSpecial ? 'true' : 'false'}">` +
                                                `<button type="button" class="seg-btn seg-left ${isSpecial ? '' : 'active'}" data-value="0">Regular</button>` +
                                                `<button type="button" class="seg-btn seg-right ${isSpecial ? 'active' : ''}" data-value="1">Special</button>` +
                                            `</div>`;
                                }
                                // show Track selector only for Senior High (years >= 11)
                                if(year && parseInt(year,10) >= 11){
                                    let sel = `<select class="section-track-select border rounded px-2 py-1 ml-2" data-idx="${idx}"><option value="">— Track —</option>`;
                                    SHS_TRACK_OPTIONS.forEach(t => { sel += `<option value="${t}" ${trackVal === t ? 'selected' : ''}>${t}</option>`; });
                                    sel += `</select>`;
                                    html += sel;
                                }
                                row.innerHTML = html;
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
                    // if theme is empty, pick a random theme from the select options for this year
                    let chosenTheme = theme;
                    if(!chosenTheme){
                        const themeInputLocal = document.querySelector('select[name="theme"][data-year="'+year+'"]');
                        if(themeInputLocal && themeInputLocal.options && themeInputLocal.options.length){
                            const opts = Array.from(themeInputLocal.options).map(o=>o.value).filter(v=>v);
                            if(opts.length) chosenTheme = opts[Math.floor(Math.random()*opts.length)];
                        }
                    }
                    const payload = { theme: chosenTheme, count: cnt, name: 'Grade ' + year };
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
                            const trackVal = payload.track || '';
                            const row = document.createElement('div');
                                row.className = 'flex items-center gap-2 py-1';
                                const year = (area && area.getAttribute) ? area.getAttribute('data-year') : null;
                                let html = `<input data-idx="${idx}" class="flex-1 border rounded px-2 py-1 section-name-input" value="${name}"/>`;
                                // show Regular/Special only for Junior High (years < 11)
                                if(!year || parseInt(year,10) < 11){
                                    html += `<div class="btn-toggle-special seg-toggle ml-2 pill-small" data-idx="${idx}" data-special="${isSpecial ? '1' : '0'}" role="tablist" aria-pressed="${isSpecial ? 'true' : 'false'}">` +
                                                `<button type="button" class="seg-btn seg-left ${isSpecial ? '' : 'active'}" data-value="0">Regular</button>` +
                                                `<button type="button" class="seg-btn seg-right ${isSpecial ? 'active' : ''}" data-value="1">Special</button>` +
                                            `</div>`;
                                }
                                // show Track selector only for Senior High (years >= 11)
                                if(year && parseInt(year,10) >= 11){
                                    let sel = `<select class="section-track-select border rounded px-2 py-1 ml-2" data-idx="${idx}"><option value="">— Track —</option>`;
                                    SHS_TRACK_OPTIONS.forEach(t => { sel += `<option value="${t}" ${trackVal === t ? 'selected' : ''}>${t}</option>`; });
                                    sel += `</select>`;
                                    html += sel;
                                }
                                row.innerHTML = html;
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
        // Helper to render a view-only horizontal grid panel showing created sections grouped into Junior (7-10) and Senior (11-12)
        function renderCreatedSectionsView(createdMap, failedYears){
            // createdMap: { year: [ { name, is_special, id? } ] }
            // failedYears: Set or array of years that failed
            let container = document.getElementById('created-sections-view');
            if(!container){
                container = document.createElement('div');
                container.id = 'created-sections-view';
                container.className = 'mt-6';
                const toolbar = document.querySelector('.mt-4.flex.justify-end');
                if(toolbar && toolbar.parentNode){
                    toolbar.parentNode.insertBefore(container, toolbar);
                } else {
                    const panel = document.getElementById('panel-sections');
                    panel.insertBefore(container, panel.firstChild);
                }
            }
            container.innerHTML = '';

            // Create a horizontal grid for a set of years
            function makeHorizontalGrid(title, subtitle, years, cols){
                const wrapper = document.createElement('div');
                wrapper.className = 'border rounded p-4 mb-4 bg-white';
                const header = document.createElement('div');
                header.className = 'flex items-center justify-between mb-3';
                header.innerHTML = `<div class="font-medium">${title}</div><div class="text-sm text-slate-500">${subtitle}</div>`;
                wrapper.appendChild(header);

                // Use Tailwind grid cols via utility - if unavailable, fallback to flex
                const grid = document.createElement('div');
                grid.className = 'grid gap-4';
                grid.style.gridTemplateColumns = `repeat(${cols}, minmax(0, 1fr))`;

                years.forEach(y => {
                    const secList = createdMap[y] || [];
                    const col = document.createElement('div');
                    col.className = 'border rounded p-3 bg-gray-50 flex flex-col h-full';
                    const head = document.createElement('div');
                    head.className = 'flex items-center justify-between mb-2';
                    head.innerHTML = `<div class="font-medium">Grade ${y}</div><div class="text-xs text-slate-500">${secList.length} item(s)</div>`;
                    col.appendChild(head);

                    const body = document.createElement('div');
                    body.className = 'overflow-auto';
                    // default: expanded, show list
                    if(secList.length){
                        const ul = document.createElement('ul');
                        ul.className = 'space-y-1';
                        secList.forEach(s => {
                            const li = document.createElement('li');
                            li.className = 'py-1';
                            const label = document.createElement('div'); label.className = 'flex items-center justify-between';
                            const nm = document.createElement('div'); nm.className = 'font-medium'; nm.textContent = s.name;
                            const tag = document.createElement('div'); tag.className = 'text-xs text-slate-500'; tag.textContent = s.track ? s.track : (s.is_special ? 'Special' : '');
                            label.appendChild(nm); label.appendChild(tag);
                            li.appendChild(label);
                            ul.appendChild(li);
                        });
                        body.appendChild(ul);
                    } else {
                        const p = document.createElement('div'); p.className = 'text-sm text-slate-500'; p.textContent = failedYears && failedYears.indexOf(String(y)) !== -1 ? 'Failed to create sections for this grade' : 'No new sections';
                        body.appendChild(p);
                    }
                    col.appendChild(body);
                    grid.appendChild(col);
                });

                wrapper.appendChild(grid);
                return wrapper;
            }

            // Junior: 4 columns for grades 7-10
            const junior = makeHorizontalGrid('Junior High Sections', 'Grades 7 – 10', [7,8,9,10], 4);
            const senior = makeHorizontalGrid('Senior High Sections', 'Grades 11 – 12', [11,12], 2);
            container.appendChild(junior);
            container.appendChild(senior);

            container.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }

        // Hide builder inputs/preview for grades that have been created (do NOT render per-grade static lists here)
        // The canonical created sections view is the bottom horizontal grid; avoid duplicating lists in the grade rows.
        function updateBuilderForCreated(createdMap){
            try{
                Object.keys(createdMap || {}).forEach(year => {
                    const preview = document.querySelector('.preview-area[data-year="'+year+'"]');
                    // clear preview area (do not insert static list to avoid duplication)
                    if(preview){ preview.innerHTML = ''; }

                    // hide theme select and count input for the created grade
                    const themeSel = document.querySelector('select[name="theme"][data-year="'+year+'"]');
                    const countInp = document.querySelector('input[name="count"][data-year="'+year+'"]');
                    if(themeSel) themeSel.style.display = 'none';
                    if(countInp) countInp.style.display = 'none';

                    // hide any generate/preview/save buttons associated with this year
                    const btns = Array.from(document.querySelectorAll('button[data-year="'+year+'"], button[data-grade-id="'+year+'"]'));
                    btns.forEach(b => { try{ b.style.display = 'none'; }catch(e){} });
                });
            }catch(err){ console.error('updateBuilderForCreated error', err); }
        }

        if (saveAllBtn) {
            saveAllBtn.addEventListener('click', async function(){
                if(!confirm('Create all generated sections for all grades? This will persist sections to the database.')) return;
                const previewAreas = Array.from(document.querySelectorAll('.preview-area'));
                const createdMap = {}; // year -> [{name, is_special, id?}]
                const failedYears = [];

                // disable inputs while processing
                saveAllBtn.disabled = true; saveAllBtn.textContent = 'Creating…';
                const inputsToDisable = Array.from(document.querySelectorAll('select[name="theme"], input[name="count"], button.btn-generate, button.btn-preview, #save-all, #global-generate'));
                inputsToDisable.forEach(i => i.disabled = true);

                for (const area of previewAreas){
                    const year = area.getAttribute('data-year');
                    const gidAttr = area.getAttribute('data-grade-id');
                    let items = Array.from(area.querySelectorAll('.section-name-input')).map(i=>{
                        const idx = i.getAttribute('data-idx');
                        const name = i.value.trim();
                        const yearLocal = area.getAttribute('data-year');
                        const isJHS = !yearLocal || parseInt(yearLocal,10) < 11;
                        const btn = area.querySelector('.btn-toggle-special[data-idx="'+idx+'"]');
                        const trackSel = area.querySelector('.section-track-select[data-idx="'+idx+'"]');
                        const track = trackSel ? (trackSel.value || null) : null;
                        const is_special = isJHS ? (btn && btn.dataset && btn.dataset.special === '1' ? 1 : 0) : 0;
                        return { name, is_special, track };
                    }).filter(it=>it.name);

                    // If no preview items are present but a count input exists and >0, request a preview from the server
                    if(!items.length){
                        const countInputLocal = document.querySelector('input[name="count"][data-year="'+year+'"]');
                        const themeInputLocal = document.querySelector('select[name="theme"][data-year="'+year+'"]');
                        const cntLocal = countInputLocal ? parseInt(countInputLocal.value||'0',10) : 0;
                        const themeLocal = themeInputLocal ? (themeInputLocal.value || '') : '';
                        if(cntLocal > 0){
                            try{
                                const resp = await postJSON(previewUrl, { theme: themeLocal, count: cntLocal, name: 'Grade ' + year });
                                const preview = resp.preview ?? resp.names ?? resp;
                                    if(Array.isArray(preview) && preview.length){
                                    items = preview.map(it => (typeof it === 'string') ? { name: it, is_special: 0, track: null } : { name: it.name || '', is_special: it.is_special ? 1 : 0, track: it.track || null }).filter(it => it.name);
                                }
                            } catch(err){
                                console.error('preview before save error', err);
                            }
                        }
                    }
                    if(!items.length) continue;

                    let gradeId = gidAttr || null;
                    // if grade not persisted yet, create it
                    if(!gradeId){
                        const themeInput = document.querySelector('select[name="theme"][data-year="'+year+'"]');
                        const countInput = document.querySelector('input[name="count"][data-year="'+year+'"]');
                        const theme = themeInput ? themeInput.value.trim() : null;
                        const planned = countInput ? parseInt(countInput.value||'0',10) : 0;
                        try{
                            // if theme empty, choose random from options
                            let gradeTheme = theme;
                            if(!gradeTheme && themeInput && themeInput.options && themeInput.options.length){
                                const opts = Array.from(themeInput.options).map(o=>o.value).filter(v=>v);
                                if(opts.length) gradeTheme = opts[Math.floor(Math.random()*opts.length)];
                            }
                            const createResp = await postJSON('/admin/it/grade-levels', { name: 'Grade ' + year, section_naming: gradeTheme, section_naming_options: { planned_sections: planned } });
                            if(createResp && createResp.grade && createResp.grade.id){
                                gradeId = createResp.grade.id;
                                // annotate DOM so future saves know id
                                area.setAttribute('data-grade-id', gradeId);
                            } else {
                                console.warn('failed to create grade for year', year);
                                failedYears.push(year);
                                continue;
                            }
                        } catch(err){
                            console.error('create grade error', err);
                            failedYears.push(year);
                            continue;
                        }
                    }

                    // now bulk-create sections
                    try{
                        const url = `/admin/it/grade-levels/${gradeId}/sections/bulk-create`;
                        const saveResp = await postJSON(url, { items: items });
                        // prefer server-returned sections (with ids) else use submitted items
                        const sections = Array.isArray(saveResp.sections) && saveResp.sections.length ? saveResp.sections.map(s => ({ name: s.name || s, is_special: s.is_special || 0, track: s.track || null, id: s.id || null })) : items.map(i => ({ name: i.name, is_special: i.is_special || 0, track: i.track || null }));
                        createdMap[year] = sections;
                    } catch(err){
                        console.error('bulk create error', err);
                        failedYears.push(year);
                    }
                }

                // re-enable inputs
                saveAllBtn.disabled = false; saveAllBtn.textContent = 'Create Sections';
                inputsToDisable.forEach(i => i.disabled = false);

                // Show results inline and update builder areas
                renderCreatedSectionsView(createdMap, failedYears);
                try{ updateBuilderForCreated(createdMap); }catch(err){ console.error('updateBuilderForCreated error', err); }

                if(failedYears.length){
                    alert('Some grades failed to create. See the created sections panel for details.');
                } else {
                    alert('Sections created');
                    // reload so server-side $hasSections becomes true and BUILDER is hidden
                    location.reload();
                }
            });
        }

        // On page load: if the grade already has saved sections rendered server-side, hide the builder for that grade
        function hideBuilderIfSavedExists(){
            try{
                const previewAreas = Array.from(document.querySelectorAll('.preview-area'));
                previewAreas.forEach(area => {
                    const year = area.getAttribute('data-year');
                    // look for a sibling saved sections container (the server renders a flex.wrap container with saved section pills)
                    const parent = area.parentElement || area.parentNode;
                    if(!parent) return;
                    const saved = parent.querySelector('.flex.flex-wrap');
                    if(saved && saved.children && saved.children.length){
                        // hide inputs and preview and buttons for this year (do NOT copy saved pills into preview area)
                        const themeSel = document.querySelector('select[name="theme"][data-year="'+year+'"]');
                        const countInp = document.querySelector('input[name="count"][data-year="'+year+'"]');
                        if(themeSel) themeSel.style.display = 'none';
                        if(countInp) countInp.style.display = 'none';
                        area.innerHTML = '';
                        // hide any buttons associated with this year
                        const btns = Array.from(document.querySelectorAll('button[data-year="'+year+'"], button[data-grade-id="'+year+'"]'));
                        btns.forEach(b => { try{ b.style.display = 'none'; }catch(e){} });
                    }
                });
            }catch(err){ console.error('hideBuilderIfSavedExists error', err); }
        }

        // run initial hide pass
        hideBuilderIfSavedExists();

        // Unified Add Subject form (handles both Junior and Senior submissions)
        const addSubjectForm = document.getElementById('add-subject');
        if(addSubjectForm){
            // tag-style subject entry
            const subjectInput = document.getElementById('subject-name');
            const tagsContainer = document.getElementById('subject-tags');
            let subjectNames = [];

            function renderTags(){
                tagsContainer.innerHTML = '';
                subjectNames.forEach((nm, idx) => {
                    const tag = document.createElement('span');
                    tag.className = 'inline-flex items-center bg-gray-100 text-gray-800 px-2 py-0.5 rounded-full text-sm';
                    tag.innerHTML = `<span class="mr-2">${escapeHtml(nm)}</span><button type="button" data-idx="${idx}" class="ml-1 text-gray-500 hover:text-gray-700">&times;</button>`;
                    tagsContainer.appendChild(tag);
                });
                // attach remove handlers
                Array.from(tagsContainer.querySelectorAll('button[data-idx]')).forEach(b => {
                    b.addEventListener('click', function(){
                        const i = Number(this.getAttribute('data-idx'));
                        if(!isNaN(i)){
                            subjectNames.splice(i,1);
                            renderTags();
                        }
                    });
                });
            }

            function escapeHtml(s){ return String(s).replace(/[&<>"']/g, function(c){ return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;','\'':'&#39;'}[c]; }); }

            // add on Enter
            subjectInput.addEventListener('keydown', function(ev){
                if(ev.key === 'Enter'){
                    ev.preventDefault();
                    const val = (subjectInput.value || '').trim();
                    if(!val) return;
                    subjectNames.push(val);
                    subjectInput.value = '';
                    renderTags();
                }
            });

            // also allow pasting newline-separated list
            subjectInput.addEventListener('paste', function(ev){
                const text = (ev.clipboardData || window.clipboardData).getData('text') || '';
                if(text.indexOf('\n') !== -1){
                    ev.preventDefault();
                    const parts = text.split(/\r?\n+/).map(s=>s.trim()).filter(Boolean);
                    subjectNames = subjectNames.concat(parts);
                    subjectInput.value = '';
                    renderTags();
                }
            });

            addSubjectForm.addEventListener('submit', async function(e){
                e.preventDefault();
                const classification = document.getElementById('subject-classification').value;
                const grades = Array.from(addSubjectForm.querySelectorAll('input[name="grades[]"]:checked')).map(i=>i.value);

                // if no names from tags, fallback to single input value
                if(subjectNames.length === 0){
                    const v = (subjectInput.value || '').trim();
                    if(v) subjectNames.push(v);
                }

                if(!subjectNames.length) return alert('Provide at least one subject name');
                if(!classification) return alert('Select a classification');
                if(!grades.length) return alert('Select at least one grade');

                const results = { created: 0, existing: 0, failed: 0 };
                for(const nm of subjectNames){
                    try{
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

                // Show summary and reload
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

        // Classification visibility rules:
        // Core: always available
        // Special: only when any JHS grade (7-10) selected
        // Strand options (abm, humss, stem, tvl, gas): only when any SHS grade (11-12) selected
        const classificationSelect = document.getElementById('subject-classification');
        const addSubjectBtn = document.querySelector('#add-subject button[type="submit"]');

        function hasJuniorSelected(){
            return Array.from(document.querySelectorAll('input[name="grades[]"]:checked')).some(cb => {
                const y = cb.getAttribute('data-year');
                return y && Number(y) >= 7 && Number(y) <= 10;
            });
        }
        function hasSeniorSelected(){
            return Array.from(document.querySelectorAll('input[name="grades[]"]:checked')).some(cb => {
                const y = cb.getAttribute('data-year');
                return y && Number(y) >= 11 && Number(y) <= 12;
            });
        }

        function updateClassificationOptions(){
            if(!classificationSelect) return;
            const junior = hasJuniorSelected();
            const senior = hasSeniorSelected();

            // iterate options and enable/disable accordingly
            Array.from(classificationSelect.options).forEach(opt => {
                const v = (opt.value || '').toLowerCase();
                if(v === 'core'){ opt.disabled = false; }
                else if(v === 'special'){ opt.disabled = !junior; }
                else if(['abm','humss','stem','tvl','gas'].indexOf(v) !== -1){ opt.disabled = !senior; }
                else { /* other custom types remain enabled */ opt.disabled = false; }
            });

            // if current selection is now disabled, fallback to 'core'
            const cur = (classificationSelect.value || '').toLowerCase();
            const curOpt = Array.from(classificationSelect.options).find(o => (o.value||'').toLowerCase() === cur);
            if(curOpt && curOpt.disabled){
                classificationSelect.value = 'core';
                // brief alert to user
                try{ alert('Classification was reset to Core because selected grades changed.'); }catch(e){}
            }

            // enable/disable submit based on at least one grade selected
            const anyGrade = Array.from(document.querySelectorAll('input[name="grades[]"]:checked')).length > 0;
            if(addSubjectBtn) addSubjectBtn.disabled = !anyGrade;
        }

        // hook into grade checkbox changes
        document.querySelectorAll('input[name="grades[]"]').forEach(cb => cb.addEventListener('change', function(){ updateGroupStates(); updateClassificationOptions(); }));
        if(selectJunior) selectJunior.addEventListener('change', updateClassificationOptions);
        if(selectSenior) selectSenior.addEventListener('change', updateClassificationOptions);

        // initial run
        updateClassificationOptions();

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

        // Edit and Delete handlers for subjects (delegated)
        const subjectModal = document.getElementById('subject-modal');
        const subjectModalBody = document.getElementById('subject-modal-body');
        const subjectModalClose = document.getElementById('subject-modal-close');

        function showModal(){
            if(!subjectModal) return;
            subjectModal.classList.remove('hidden');
            subjectModal.classList.add('flex');
        }
        function hideModal(){
            if(!subjectModal) return;
            subjectModal.classList.remove('flex');
            subjectModal.classList.add('hidden');
            subjectModalBody.innerHTML = 'Loading…';
        }

        if(subjectModalClose){ subjectModalClose.addEventListener('click', hideModal); }
        // click outside modal to close
        if(subjectModal){ subjectModal.addEventListener('click', function(e){ if(e.target === subjectModal) hideModal(); }); }

        // Open edit fragment in modal
        async function openSubjectEdit(id){
            try{
                showModal();
                const url = `/admin/it/subjects/${id}/fragment`;
                const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                const html = await res.text();
                subjectModalBody.innerHTML = html;

                // Find form inside fragment and hijack submit
                const form = subjectModalBody.querySelector('form');
                if(form){
                    form.addEventListener('submit', async function(ev){
                        ev.preventDefault();
                        const fd = new FormData(form);
                        try{
                            const resp = await fetch(form.action, { method: 'POST', headers: { 'X-CSRF-TOKEN': token }, body: fd });
                            if(resp.ok){
                                // update DOM for the subject's name (best-effort) and close modal
                                const nameInput = form.querySelector('input[name="name"]');
                                const newName = nameInput ? nameInput.value.trim() : null;
                                const li = document.querySelector('li[data-subject-id="' + id + '"]');
                                if(li && newName){
                                    const nmEl = li.querySelector('.subject-name'); if(nmEl) nmEl.textContent = newName;
                                }
                                hideModal();
                            } else {
                                // replace modal body with server-returned HTML (validation errors)
                                const text = await resp.text();
                                subjectModalBody.innerHTML = text;
                            }
                        } catch(err){ console.error('update subject error', err); alert('Network error while updating subject'); }
                    });
                }
            } catch(err){ console.error('open fragment error', err); subjectModalBody.innerHTML = '<div class="text-red-600">Failed to load form</div>'; }
        }

        // Delegated clicks for edit/delete
        document.addEventListener('click', async function(e){
            const editBtn = e.target.closest ? e.target.closest('.subject-edit-btn') : null;
            if(editBtn){
                const id = editBtn.getAttribute('data-id');
                if(!id) return;
                // Inline rename: replace the subject name with an input + Save/Cancel
                const li = document.querySelector('li[data-subject-id="' + id + '"]');
                if(!li) return;
                // avoid creating multiple editors
                if(li.classList.contains('editing')) return;
                li.classList.add('editing');
                const nameEl = li.querySelector('.subject-name');
                const original = nameEl ? nameEl.textContent.trim() : '';
                // create input and controls
                const input = document.createElement('input');
                input.type = 'text'; input.value = original; input.className = 'border rounded px-2 py-1 w-72';
                const saveBtn = document.createElement('button'); saveBtn.type = 'button'; saveBtn.className = 'ml-2 px-2 py-1 bg-[#3b4197] text-white rounded text-sm'; saveBtn.textContent = 'Save';
                const cancelBtn = document.createElement('button'); cancelBtn.type = 'button'; cancelBtn.className = 'ml-2 px-2 py-1 border rounded text-sm'; cancelBtn.textContent = 'Cancel';
                // replace contents
                const left = li.querySelector('.subject-name');
                const right = li.querySelector('div.flex.items-center') || li.querySelector('div.flex.items-center.gap-2');
                if(left) left.style.display = 'none';
                if(right) right.style.display = 'none';
                const editContainer = document.createElement('div'); editContainer.className = 'flex items-center gap-2';
                editContainer.appendChild(input); editContainer.appendChild(saveBtn); editContainer.appendChild(cancelBtn);
                li.appendChild(editContainer);
                input.focus(); input.select();

                cancelBtn.addEventListener('click', function(){
                    editContainer.remove();
                    if(left) left.style.display = '';
                    if(right) right.style.display = '';
                    li.classList.remove('editing');
                });

                saveBtn.addEventListener('click', async function(){
                    const newName = (input.value || '').trim();
                    if(!newName) return alert('Name cannot be empty');
                    saveBtn.disabled = true; saveBtn.textContent = 'Saving…';
                    try{
                        const url = `/admin/it/subjects/${id}`;
                        const res = await fetch(url, { method: 'PUT', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token, 'Accept': 'application/json' }, body: JSON.stringify({ name: newName }) });
                        if(res.ok){
                            // update DOM
                            const nmEl = li.querySelector('.subject-name'); if(nmEl) nmEl.textContent = newName;
                            editContainer.remove();
                            if(left) left.style.display = '';
                            if(right) right.style.display = '';
                            li.classList.remove('editing');
                        } else {
                            const json = await res.json().catch(()=>null);
                            alert((json && json.message) ? json.message : 'Failed to update subject');
                            saveBtn.disabled = false; saveBtn.textContent = 'Save';
                        }
                    } catch(err){ console.error('inline update error', err); alert('Network error while saving'); saveBtn.disabled = false; saveBtn.textContent = 'Save'; }
                });

                return;
            }
            const delBtn = e.target.closest ? e.target.closest('.subject-delete-btn') : null;
            if(delBtn){
                const id = delBtn.getAttribute('data-id');
                if(!id) return;
                if(!confirm('Delete this subject? This cannot be undone.')) return;
                try{
                    const url = `/admin/it/subjects/${id}`;
                    const resp = await fetch(url, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': token, 'Accept': 'application/json' } });
                    if(resp.ok){
                        // remove list item
                        const li = document.querySelector('li[data-subject-id="' + id + '"]');
                        if(li) li.parentNode.removeChild(li);
                    } else {
                        const json = await resp.json().catch(()=>null);
                        alert((json && json.message) ? json.message : 'Failed to delete subject');
                    }
                } catch(err){ console.error('delete error', err); alert('Network error while deleting'); }
                return;
            }
        });

        // Grade editor: open modal, list/create/update/delete sections
        const gradeModal = document.getElementById('grade-modal');
        const gradeModalBody = document.getElementById('grade-modal-body');
        const gradeModalClose = document.getElementById('grade-modal-close');

        function showGradeModal(){ if(!gradeModal) return; gradeModal.classList.remove('hidden'); gradeModal.classList.add('flex'); }
        function hideGradeModal(){ if(!gradeModal) return; gradeModal.classList.remove('flex'); gradeModal.classList.add('hidden'); gradeModalBody.innerHTML = 'Loading…'; }
        if(gradeModalClose) gradeModalClose.addEventListener('click', hideGradeModal);
        if(gradeModal) gradeModal.addEventListener('click', function(e){ if(e.target === gradeModal) hideGradeModal(); });

        async function openGradeEditor(gradeId, year){
            showGradeModal();
            try{
                const url = `/admin/it/grade-levels/${gradeId}/sections`;
                const res = await fetch(url, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } });
                const json = await res.json();
                const sections = Array.isArray(json.sections) ? json.sections : [];
                renderGradeEditor(gradeId, year, sections);
            } catch(err){ console.error('openGradeEditor error', err); gradeModalBody.innerHTML = '<div class="text-red-600">Failed to load sections</div>'; }
        }

        function renderGradeEditor(gradeId, year, sections){
            gradeModalBody.innerHTML = '';
            const container = document.createElement('div');
            const header = document.createElement('div'); header.className = 'mb-3 flex items-center justify-between';
            header.innerHTML = `<div class="font-medium">Grade ${year} — Manage sections</div>`;
            container.appendChild(header);

            const list = document.createElement('div'); list.className = 'space-y-2';
            sections.forEach(s => list.appendChild(makeSectionRow(gradeId, year, s)));

            const addBtn = document.createElement('button'); addBtn.type='button'; addBtn.className='mt-3 px-3 py-1 bg-green-600 text-white rounded'; addBtn.textContent='Add Section';
            addBtn.addEventListener('click', function(){ list.appendChild(makeSectionRow(gradeId, year, null)); });

            container.appendChild(list);
            container.appendChild(addBtn);

            // Controls: single Save All and Close
            const controls = document.createElement('div'); controls.className = 'mt-3 flex items-center gap-2 justify-end';
            const saveAllBtn = document.createElement('button'); saveAllBtn.type = 'button'; saveAllBtn.className = 'px-3 py-1 bg-[#3b4197] text-white rounded'; saveAllBtn.textContent = 'Save All';
            const closeBtn = document.createElement('button'); closeBtn.type = 'button'; closeBtn.className = 'ml-2 px-3 py-1 border rounded text-sm'; closeBtn.textContent = 'Close';
            controls.appendChild(saveAllBtn); controls.appendChild(closeBtn);
            container.appendChild(controls);

            // Close handler
            closeBtn.addEventListener('click', function(){ hideGradeModal(); });

            // Batch save handler
            saveAllBtn.addEventListener('click', async function(){
                if(!confirm('Apply all changes for this grade?')) return;
                saveAllBtn.disabled = true; saveAllBtn.textContent = 'Saving…';
                const rows = Array.from(list.children);
                const promises = rows.map(row => (async () => {
                    try{
                        const nameInput = row.querySelector('input[type="text"]');
                        const name = nameInput ? (nameInput.value||'').trim() : '';
                        if(!name) return { success: false, message: 'Empty name', row };
                        const isJHS = Number(year) < 11;
                        let payload = { name };
                        if(isJHS){ const chk = row.querySelector('input[type="checkbox"]'); payload.is_special = chk && chk.checked ? 1 : 0; }
                        else { const sel = row.querySelector('select'); payload.track = sel ? (sel.value || null) : null; }

                        const sid = row.getAttribute('data-section-id');
                        if(sid){
                            const resp = await fetch(`/admin/it/sections/${sid}`, { method: 'PUT', headers: { 'Content-Type':'application/json','X-CSRF-TOKEN': token, 'Accept':'application/json' }, body: JSON.stringify(payload) });
                            if(!resp.ok){ const j = await resp.json().catch(()=>null); return { success:false, message: (j && j.message) ? j.message : 'Update failed', row }; }
                            const j = await resp.json(); return { success:true, section: j.section, row };
                        } else {
                            const resp = await fetch(`/admin/it/grade-levels/${gradeId}/sections`, { method: 'POST', headers: { 'Content-Type':'application/json','X-CSRF-TOKEN': token, 'Accept':'application/json' }, body: JSON.stringify(payload) });
                            if(!resp.ok){ const j = await resp.json().catch(()=>null); return { success:false, message: (j && j.message) ? j.message : 'Create failed', row }; }
                            const j = await resp.json(); return { success:true, section: j.section, row };
                        }
                    }catch(err){ return { success:false, message: err.message || 'Network error', row }; }
                })());
                const results = await Promise.all(promises);
                const failed = results.filter(r => !r.success);
                // On success: if created sections, add to main list; if updated, update main list text/meta and reposition JHS specials
                results.filter(r => r.success && r.section).forEach(r => {
                    const sec = r.section;
                    const row = r.row;
                    // ensure row has data-section-id
                    if(sec.id) row.setAttribute('data-section-id', sec.id);
                    // update main list: find list container for grade
                    try{
                        const gradePanel = document.querySelector('[data-grade-id="'+gradeId+'"]');
                        if(gradePanel){ const listUl = gradePanel.closest('.border').querySelector('ul');
                            if(listUl){
                                let mainLi = document.querySelector('[data-section-id="'+sec.id+'"]');
                                if(!mainLi){
                                    mainLi = document.createElement('li'); mainLi.className='py-1'; mainLi.setAttribute('data-section-id', sec.id);
                                    mainLi.innerHTML = `<div class="flex items-center justify-between"><div class="font-medium section-name">${esc(sec.name)}</div><div class="text-xs text-slate-500 section-meta">${sec.track ? esc(sec.track) : (sec.is_special ? 'Special' : '')}</div></div>`;
                                    if(Number(year) < 11 && sec.is_special){ listUl.insertBefore(mainLi, listUl.firstChild); } else { listUl.appendChild(mainLi); }
                                } else {
                                    const nEl = mainLi.querySelector('.section-name'); if(nEl) nEl.textContent = sec.name;
                                    const mEl = mainLi.querySelector('.section-meta'); if(mEl) mEl.textContent = sec.track ? sec.track : (sec.is_special ? 'Special' : '');
                                    // reposition for JHS
                                    if(Number(year) < 11){ const parentUl = mainLi.closest('ul'); if(parentUl){ if(sec.is_special){ const firstNonSpecial = Array.from(parentUl.querySelectorAll('li')).find(li => { const meta = li.querySelector('.section-meta'); return !(meta && meta.textContent.trim() === 'Special'); }); if(firstNonSpecial) parentUl.insertBefore(mainLi, firstNonSpecial); else parentUl.insertBefore(mainLi, parentUl.firstChild); } else { // move after last special
                                                    const lis = Array.from(parentUl.querySelectorAll('li'));
                                                    let insertAfter = null; for(let i=0;i<lis.length;i++){ const li = lis[i]; const meta = li.querySelector('.section-meta'); if(meta && meta.textContent.trim() === 'Special'){ insertAfter = li; } }
                                                    if(insertAfter && insertAfter !== mainLi) insertAfter.parentNode.insertBefore(mainLi, insertAfter.nextSibling);
                                                } } }
                                }
                            }
                        }
                    }catch(e){ console.error('update main list error', e); }
                });

                saveAllBtn.disabled = false; saveAllBtn.textContent = 'Save All';
                if(failed.length){
                    alert('Some rows failed to save. Please check and try again.');
                } else {
                    // everything saved — refresh to ensure server-side ordering and counts are consistent
                    location.reload();
                }
            });

            gradeModalBody.appendChild(container);
        }

        function makeSectionRow(gradeId, year, s){
            const row = document.createElement('div'); row.className = 'flex items-center gap-2';
            const nameInput = document.createElement('input'); nameInput.type='text'; nameInput.className='border rounded px-2 py-1 flex-1'; nameInput.value = s ? (s.name || '') : '';
            row.appendChild(nameInput);

            if(Number(year) < 11){
                const chk = document.createElement('input'); chk.type='checkbox'; chk.className='ml-2'; chk.checked = s ? !!s.is_special : false;
                const lbl = document.createElement('label'); lbl.className='text-sm ml-2'; lbl.appendChild(chk); lbl.insertAdjacentText('beforeend',' Special');
                row.appendChild(lbl);
            } else {
                const sel = document.createElement('select'); sel.className='border rounded px-2 py-1'; sel.innerHTML = `<option value="">— Track —</option>`;
                SHS_TRACK_OPTIONS.forEach(t => { const o = document.createElement('option'); o.value = t; o.textContent = t; if(s && s.track === t) o.selected = true; sel.appendChild(o); });
                row.appendChild(sel);
            }

            // Add a delete/remove button: Delete for persisted sections, Remove for new rows
            const delBtn = document.createElement('button'); delBtn.type='button'; delBtn.className='ml-2 px-2 py-1 border rounded text-sm text-red-600'; delBtn.textContent = s && s.id ? 'Delete' : 'Remove';
            row.appendChild(delBtn);

            if(s && s.id){
                // mark row with section id so batch save can detect persisted rows
                row.setAttribute('data-section-id', s.id);
                delBtn.addEventListener('click', async function(){
                    if(!confirm('Delete section?')) return;
                    try{
                        const resp = await fetch(`/admin/it/sections/${s.id}`, { method:'DELETE', headers: {'X-CSRF-TOKEN': token, 'Accept':'application/json'} });
                        if(resp.ok){ row.remove(); const mainLi = document.querySelector('[data-section-id="'+s.id+'"]'); if(mainLi) mainLi.remove(); }
                        else { const j = await resp.json().catch(()=>null); alert((j && j.message) ? j.message : 'Failed'); }
                    }catch(err){ console.error(err); alert('Network error'); }
                });
            } else {
                delBtn.addEventListener('click', function(){ row.remove(); });
            }

            return row;
        }

        function esc(s){ return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;'); }

        // Delegated grade-edit button handler
        document.addEventListener('click', function(e){
            const gbtn = e.target.closest ? e.target.closest('.grade-edit-btn') : null;
            if(gbtn){ const gid = gbtn.getAttribute('data-grade-id'); const yr = gbtn.getAttribute('data-year'); if(gid) openGradeEditor(gid, yr); }
        });
    })();
</script>
@endsection


