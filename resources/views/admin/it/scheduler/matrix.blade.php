@extends('admin.it.layout')

@section('title','Scheduling Matrix')
@section('heading','Scheduling Matrix — Compact')

@section('content')
    <div id="matrix-root" class="bg-white border rounded text-xs"
      data-entry-substitutes-url="{{ route('admin.it.scheduler.entry.substitutes', ['run'=>$run->id, 'entry' => '__ENTRY__']) }}"
      data-entry-apply-url="{{ route('admin.it.scheduler.entry.apply_substitute', ['run'=>$run->id, 'entry' => '__ENTRY__']) }}"
      data-entry-get-url="{{ route('admin.it.scheduler.entry.get', ['run'=>$run->id, 'entry' => '__ENTRY__']) }}"
      data-teacher-substitutes-url="{{ route('admin.it.scheduler.teacher.substitutes', ['run'=>$run->id, 'teacher' => '__TEACHER__']) }}"
      data-teachers-for-slot-url="{{ route('admin.it.scheduler.teachers.for_slot', ['run'=>$run->id]) }}"
      data-subjects-stage-url="{{ route('admin.it.scheduler.subjects.stage', ['run'=>$run->id, 'stage' => '__STAGE__']) }}"
      data-entry-assign-url="{{ route('admin.it.scheduler.entry.assign', ['run'=>$run->id, 'entry' => '__ENTRY__']) }}"
      data-entry-create-url="{{ route('admin.it.scheduler.entry.create', ['run'=>$run->id]) }}"
      data-generate-url="{{ route('admin.it.scheduler.generate', ['run'=>$run->id]) }}"
      data-subjects='@json($subjectsByStage)'
      data-all-teachers='@json($allTeachers)'
      data-csrf="{{ csrf_token() }}"
      style="font-size:11px; width:100%; min-height:100vh; box-sizing:border-box; overflow:visible; padding:0;">

    <div id="matrix-content" style="height:100%; display:flex; flex-direction:column; gap:8px; overflow:hidden;">
      <div id="matrix-top-toolbar" style="padding:8px 12px; border-bottom:1px solid #f0f0f0; display:flex; justify-content:space-between; align-items:center; gap:8px; background:#fff;">
        <div class="text-xs text-slate-600">Run: {{ $run->name }}</div>
        <div>
          <form id="generateForm" method="POST" action="{{ route('admin.it.scheduler.generate', ['run' => $run->id]) }}" style="display:inline;">
            @csrf
            <button id="generateScheduleBtn" type="submit" class="px-3 py-1 bg-green-600 text-white rounded">Generate (auto-fill)</button>
          </form>
        </div>
        <div style="display:flex;align-items:center;gap:8px;">
          <div id="view-toggle" style="display:flex;gap:6px;align-items:center;">
            <button id="viewToggleCompact" type="button" class="view-toggle-button px-2 py-1 border rounded bg-white text-sm">Compact</button>
            <button id="viewToggleClass" type="button" class="view-toggle-button px-2 py-1 border rounded bg-white text-sm">Class schedule</button>
            <button id="viewToggleTeacher" type="button" class="view-toggle-button px-2 py-1 border rounded bg-white text-sm">Teacher schedule</button>
          </div>
        </div>
      </div>
      <div style="flex:1 1 auto; min-height:0; overflow:hidden;">
        <div id="matrix-scroll" style="width:100%;height:auto;overflow:visible;padding-bottom:0;box-sizing:border-box;">
          <table class="w-full text-xs border-collapse" style="table-layout:fixed; width:100%;">
            <thead>
                <tr>
                  <th style="width:180px;border:1px solid #ddd;padding:6px;text-align:left;">Year/Section - Period</th>
                @foreach($periods as $p)
                  <th style="border:1px solid #ddd;padding:6px;text-align:center;">P{{ $p }}</th>
                @endforeach
              </tr>
            </thead>
            <tbody>
                @foreach($gradeLevels as $grade)
                  @if(isset($grade->sections) && count($grade->sections))
                    @foreach($grade->sections as $section)
                      <tr class="section-row">
                        <td style="border:1px solid #eee;padding:6px;font-weight:600;">{{ $grade->year }} - {{ $section->name }}</td>
                        @foreach($periods as $p)
                          @php
                            $cell = null;
                            if(isset($grid[$grade->id][$p])){
                              foreach($grid[$grade->id][$p] as $c){ if(isset($c['section']) && $c['section']->id == $section->id){ $cell = $c; break; } }
                            }
                            $entry = $cell['entry'] ?? null;
                          @endphp
                          <td class="section-cell" data-grade-id="{{ $grade->id }}" data-grade-stage="{{ $grade->school_stage ?? 'jhs' }}" data-section-id="{{ $section->id }}" data-section-name="{{ $section->name }}" data-period="{{ $p }}" data-entry-id="{{ $entry ? $entry->id : '' }}" style="border:1px solid #f3f3f3;padding:6px;vertical-align:top;">
                                @php
                                  $hasSubjectConflict = false;
                                  $hasTeacherConflict = false;
                                  if(isset($entry) && $entry && $entry->conflict) {
                                    $confList = is_array($entry->conflict) ? $entry->conflict : (is_string($entry->conflict) ? json_decode($entry->conflict, true) : $entry->conflict);
                                    if(is_array($confList)){
                                        foreach($confList as $cf){
                                          $t = $cf['type'] ?? null;
                                          if(in_array($t, ['duplicate_subject'])) $hasSubjectConflict = true;
                                          if(in_array($t, ['double_booking','overload_day','overload_week'])) $hasTeacherConflict = true;
                                        }
                                    }
                                  }
                                @endphp
                                <div style="font-weight:600;font-size:12px;" class="subject-cell">
                                  @php
                                    $rawStage = strtolower($grade->school_stage ?? 'jhs');
                                    if(strpos($rawStage,'jhs') !== false || strpos($rawStage,'jun') !== false) {
                                      $stageKey = 'jhs';
                                    } elseif(strpos($rawStage,'shs') !== false || strpos($rawStage,'sen') !== false) {
                                      $stageKey = 'shs';
                                    } else {
                                      $stageKey = 'jhs';
                                    }
                                    // Prefer subjects explicitly attached to this grade level (if provided),
                                    // otherwise fall back to the stage-wide subjects list.
                                    $availableSubjects = $subjectsByGradeId[$grade->id] ?? ($subjectsByStage[$stageKey] ?? []);
                                  @endphp
                                  <select class="cell-subject {{ $hasSubjectConflict ? 'conflict-outline-subject' : '' }}" data-grade-stage="{{ $grade->school_stage ?? 'jhs' }}" data-selected-subject="{{ $entry && $entry->subject ? $entry->subject->id : '' }}" style="width:100%;box-sizing:border-box;padding:6px;font-size:12px;">
                                    <option value="">Select subject</option>
                                    @foreach($availableSubjects as $s)
                                      <option value="{{ $s['id'] }}" @if($entry && $entry->subject && $entry->subject->id == $s['id']) selected @endif>{{ $s['name'] }}</option>
                                    @endforeach
                                  </select>
                                  
                                </div>
                                <div>
                                  <select class="cell-teacher {{ $hasTeacherConflict ? 'conflict-outline-teacher' : '' }}" data-selected-teacher="{{ $entry && $entry->teacher ? $entry->teacher->id : '' }}" style="width:100%;box-sizing:border-box;padding:6px;font-size:12px;">
                                    <option value="">Assign Teacher</option>
                                    @foreach($allTeachers as $tch)
                                      <option value="{{ $tch['id'] }}" @if($entry && $entry->teacher && $entry->teacher->id == $tch['id']) selected @endif>{{ $tch['name'] }}</option>
                                    @endforeach
                                  </select>
                                  
                                </div>
                                <div class="mt-1 text-xs text-slate-700">
                                  <div class="inline-status text-slate-600" style="display:inline-block;margin-right:6px;">@if($entry && $entry->conflict) <span class="conflict-badge text-red-600">⚠</span> @endif</div>
                                </div>
                          </td>
                        @endforeach
                      </tr>
                    @endforeach
                  @endif
                @endforeach
            </tbody>
          </table>
          <!-- Non-destructive alternate views rendered from the compact matrix DOM -->
          <div id="classView" style="display:none;padding:12px;background:#fafafa;border:1px solid #f0f0f0;margin-top:8px;border-radius:6px;"></div>
          <div id="teacherView" style="display:none;padding:12px;background:#fafafa;border:1px solid #f0f0f0;margin-top:8px;border-radius:6px;"></div>
        </div>
      </div>
      <div id="matrix-bottom-generate" style="padding:8px 12px; border-top:1px solid #efefef; display:flex; justify-content:flex-end; align-items:center; gap:8px; background:#fff;">
        <div>
          <button id="saveMatrixBtnBottom" class="px-3 py-1 bg-blue-600 text-white rounded">Save</button>
        </div>
      </div>
    </div>

  <style>
    /* compact print styles */
    @media print {
      body { font-size: 9px; }
      .no-print { display: none; }
    }
    /* Improved select styling for compact matrix */
    #matrix-root { font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif; color: #0f172a; }
    .cell-subject, .cell-teacher {
      border: 1px solid #e6e9ee;
      border-radius: 8px;
      padding: 6px 10px;
      height: 36px;
      font-size: 13px;
      background: #ffffff;
      -webkit-appearance: none;
      appearance: none;
      outline: none;
      box-sizing: border-box;
    }
    .cell-subject { color: #1e40af; }
    .cell-teacher { color: #0f172a; }
    .cell-subject:focus, .cell-teacher:focus {
      box-shadow: 0 0 0 3px rgba(59,130,246,0.08);
      border-color: #3b82f6;
    }
    .cell-teacher:disabled {
      background: #f8fafc;
      color: #94a3b8;
      cursor: not-allowed;
      pointer-events: none;
    }
    .cell-teacher:enabled { cursor: pointer; }
      /* conflict outlines applied to the dropdown element only */
      .conflict-outline-subject { outline: 2px solid rgba(241, 71, 71, 0.95); box-shadow: 0 0 0 3px rgba(241,71,71,0.08); border-color: rgba(241,71,71,0.95) !important; }
      .conflict-outline-teacher { outline: 2px solid rgba(241, 71, 71, 0.95); box-shadow: 0 0 0 3px rgba(241,71,71,0.08); border-color: rgba(241,71,71,0.95) !important; }
      /* light indicator for slots that need a teacher but remain selectable */
      .needs-teacher { outline: 2px solid rgba(241,71,71,0.35); box-shadow: 0 0 0 3px rgba(241,71,71,0.04); border-color: rgba(241,71,71,0.35) !important; }
    .cell-note { font-size:11px; color:#64748b; margin-top:6px; display:none; }
    .cell-message { font-size:11px; color:#b91c1c; margin-top:6px; display:none; }
    .cell-dropdown-btn { background: transparent; border: 1px solid #e6e9ee; padding:4px 6px; border-radius:6px; font-size:12px; cursor:pointer; }
    .cell-dropdown { position: absolute; background: #fff; border:1px solid #e6e9ee; box-shadow: 0 6px 18px rgba(2,6,23,0.08); padding:8px; border-radius:8px; z-index:60; display:none; min-width:220px; }
    .cell-dropdown .dd-subject, .cell-dropdown .dd-teacher { width:100%; box-sizing:border-box; padding:6px 8px; margin-bottom:6px; border-radius:6px; border:1px solid #e6e9ee; font-size:13px; }
    .cell-dropdown .dd-subject { color: #1e40af; }
    .cell-dropdown .dd-teacher { color: #0f172a; }
    .cell-dropdown .dd-actions { display:flex; gap:8px; justify-content:flex-end; }
    td.section-cell { padding: 10px; }
    .subject-text { font-weight:700; color: #1e40af; font-size:13px; }
    .teacher-text { color: #0f172a; font-size:12px; }
    .inline-status { margin-top:6px; }
    /* constrain first column (Year/Section) to make grid denser */
    table thead th:first-child, table tbody td:first-child { width: 180px; max-width: 180px; padding-left:10px; padding-right:10px; }
  </style>

  <!-- Modal for cell details and substitute actions -->
  <div id="cellModal" class="fixed inset-0 hidden items-center justify-center bg-black bg-opacity-40 z-50">
    <div class="bg-white rounded p-4 w-11/12 max-w-2xl">
      <div class="flex items-center justify-between mb-2">
        <h4 id="modalTitle" class="font-semibold">Edit assignment</h4>
        <div>
          <button id="saveAssignment" onclick="saveAssignment()" class="px-3 py-1 bg-blue-600 text-white rounded mr-2">Save</button>
          <button onclick="closeCellModal()" class="px-2 py-1 border rounded">Close</button>
        </div>
      </div>
      <div id="modalBody">
        <div id="modalMessages" class="text-sm text-red-600 mb-2"></div>
        <div id="modalInfo" class="text-sm mb-3">
          <div class="mb-2"><strong id="modalSection"></strong></div>
          <div class="mb-2">
            <label class="text-sm">Subject</label>
            <select id="modalSubject" class="w-full border px-2 py-1 text-sm"></select>
          </div>
          <div class="mb-2">
            <label class="text-sm">Teacher</label>
            <select id="modalTeacher" class="w-full border px-2 py-1 text-sm"></select>
          </div>
        </div>
        <div id="modalSubstitutes" class="text-sm"></div>
      </div>
    </div>
  </div>

  <script>
    const _root = document.getElementById('matrix-root');
    const entrySubstitutesUrl = _root.dataset.entrySubstitutesUrl;
    const entryApplyUrl = _root.dataset.entryApplyUrl;
    const entryGetUrl = _root.dataset.entryGetUrl;
    const teacherSubstitutesUrl = _root.dataset.teacherSubstitutesUrl;
    const teachersForSlotUrl = _root.dataset.teachersForSlotUrl;
    const entryAssignUrl = _root.dataset.entryAssignUrl;
    const csrf = _root.dataset.csrf;
    const subjectsByStage = JSON.parse(_root.dataset.subjects || '{}');
    const allTeachers = JSON.parse(_root.getAttribute('data-all-teachers') || '[]');

    // Helper to normalize subject objects coming from Blade (Eloquent models or plain objects)
    function subjectId(subj){ return subj && (subj.id ?? subj['id'] ?? (subj.attributes ? subj.attributes.id : null)) || null; }
    function subjectName(subj){ return subj && (subj.name ?? subj['name'] ?? (subj.attributes ? subj.attributes.name : null)) || ''; }
    // Normalize stage values used across the app. Accepts values like 'junior'/'senior' or 'jhs'/'shs'
    function normalizeStage(s){
      if(!s) return 'jhs';
      s = String(s).toLowerCase();
      if(s === 'jhs' || s === 'shs') return s;
      if(s.startsWith('jun') || s === 'junior') return 'jhs';
      if(s.startsWith('sen') || s === 'senior') return 'shs';
      if(s.indexOf('jhs') !== -1) return 'jhs';
      if(s.indexOf('shs') !== -1) return 'shs';
      return 'jhs';
    }
    console.log('subjectsByStage parsed', Object.keys(subjectsByStage).reduce((acc,k)=>{ acc[k]=(subjectsByStage[k]||[]).length; return acc; }, {}), subjectsByStage);

    // Fetch subjects for a stage from the server if not present in the embedded JSON
    async function fetchSubjectsForStage(stage){
      if(!stage) return [];
      if(subjectsByStage[stage] && subjectsByStage[stage].length) return subjectsByStage[stage];
      try{
        const url = _root.dataset.subjectsStageUrl.replace('__STAGE__', encodeURIComponent(stage));
        const res = await fetch(url, { headers: { 'Accept': 'application/json' }, credentials: 'same-origin' });
        if(!res.ok) return [];
        const data = await res.json();
        // normalize into array of {id,name}
        subjectsByStage[stage] = (data.subjects || []).map(s => ({ id: s.id ?? s['id'], name: s.name ?? s['name'] }));
        console.log('fetched subjects for', stage, subjectsByStage[stage].length);
        return subjectsByStage[stage];
      } catch(err){ console.warn('failed to fetch subjects for stage', stage, err); return []; }
    }

    function fitToScreen(){
      const root = document.getElementById('matrix-root');
      const container = document.getElementById('matrix-scroll') || document.getElementById('matrix-content');
      // ensure container fills available width/height before measuring
      container.style.width = '100%';
      // do not force container height; allow it to size naturally so the page can scroll
      container.style.height = 'auto';
      container.style.boxSizing = 'border-box';
      // measure natural size
      const cw = container.scrollWidth || container.offsetWidth;
      const ch = container.scrollHeight || container.offsetHeight;
      // Use the matrix-root's inner size (available area) rather than the full window.
      // This avoids excessive downscaling when the app layout includes sidebars or gutters.
      const availableWidth = Math.max(root.clientWidth || root.getBoundingClientRect().width || window.innerWidth, 1);
      const availableHeight = Math.max(root.clientHeight || root.getBoundingClientRect().height || window.innerHeight, 1);
      // Disable automatic transform-based scaling so the table fills the container
      // and allow the page to scroll (the matrix will expand the page vertically).
      container.style.transform = '';
      container.style.transformOrigin = '';
      document.body.style.overflow = 'auto';
    }

    function toggleView(view){
      const classEl = document.getElementById('class');
      const teacherEl = document.getElementById('teacher');
      if(view === 'class'){
        classEl.style.display = '';
        teacherEl.style.display = 'none';
      } else if(view === 'teacher'){
        classEl.style.display = 'none';
        teacherEl.style.display = '';
      } else {
        classEl.style.display = '';
        teacherEl.style.display = '';
      }
      setTimeout(fitToScreen, 40);
    }

    async function loadTeacherSubstitutes(teacherId){
      const url = teacherSubstitutesUrl.replace('__TEACHER__', encodeURIComponent(teacherId));
      const res = await fetch(url, { headers: { 'Accept':'text/html' }, credentials: 'same-origin' });
      const html = await res.text();
      // show html inside modal
      const modal = document.getElementById('cellModal');
      document.getElementById('modalTitle').innerText = 'Teacher substitutes';
      document.getElementById('modalMessages').innerHTML = '';
      document.getElementById('modalInfo').innerHTML = html;
      document.getElementById('modalSubstitutes').innerHTML = '';
      modal.classList.remove('hidden');
      modal.classList.add('flex');
    }

    function openCellModal(entryId, period, subject, teacher, conflict){
      const modal = document.getElementById('cellModal');
      document.getElementById('modalTitle').innerText = `P${period}`;
      document.getElementById('modalMessages').innerHTML = '';
      document.getElementById('modalInfo').innerHTML = `<div class="text-xs text-slate-600">Loading slot details...</div>`;
      document.getElementById('modalSubstitutes').innerHTML = '';
      modal.classList.remove('hidden');
      modal.classList.add('flex');
      if(entryId){ fetchEntryDetails(entryId); }
    }

    async function fetchEntryDetails(entryId){
      const url = entryGetUrl.replace('__ENTRY__', encodeURIComponent(entryId));
      try{
        const res = await fetch(url, { headers: { 'Accept':'application/json' }, credentials: 'same-origin' });
        const data = await res.json();
        const entry = data.entry;
        if(entry){
          // show conflicts
          const msgs = (entry.conflict || []).map(c => `<div>${c.message}</div>`).join('');
          document.getElementById('modalMessages').innerHTML = msgs;
          document.getElementById('modalSection').innerText = entry.section ? (entry.section.name || entry.section_id) : '';
          // populate subject select based on grade stage
          const stage = (entry.section && entry.section.grade_level_id) ? (entry.section.grade_level_id && null) : null; // placeholder
          // determine stage from incoming button attribute instead (set by caller)
          // fill subjects later in caller flow
          // set current values
          // we'll populate selects using populateModal(entry)
          populateModal(entry);
        } else {
          document.getElementById('modalInfo').innerHTML = '<div class="text-xs text-slate-500">No entry details available.</div>';
        }
        // load substitutes after entry details
        fetchSubstitutes(entryId);
      } catch(err){
        document.getElementById('modalInfo').innerHTML = '<div class="text-xs text-red-600">Failed to load entry details</div>';
      }
    }

    function populateModal(entry){
      const subjSelect = document.getElementById('modalSubject');
      const teacherSelect = document.getElementById('modalTeacher');
      subjSelect.innerHTML = '';
      teacherSelect.innerHTML = '';
      // decide stage: use section.grade_level->school_stage if available in returned relations; fallback to jhs
      const stage = entry.section && entry.section.grade_level ? (entry.section.grade_level.school_stage ?? null) : null;
      // find the button with this entry id
      const btn = document.querySelector(`.edit-entry[data-entry-id='${entry.id}']`);
      const gradeStage = btn ? btn.getAttribute('data-grade-stage') : null;
      const useStage = normalizeStage(gradeStage || stage);
      const subjects = subjectsByStage[useStage] || [];
      subjects.forEach(s => {
        const opt = document.createElement('option'); opt.value = subjectId(s); opt.text = subjectName(s); subjSelect.appendChild(opt);
      });
      if(entry.subject) subjSelect.value = entry.subject.id;

      // now fetch teachers candidates for this slot
      const period = entry.period;
      const sectionId = (entry.section && entry.section.id) || (btn ? btn.getAttribute('data-section-id') : null);
      // store period and section on modal for later subject->teacher refreshes
      const modal = document.getElementById('cellModal');
      modal.setAttribute('data-editing-entry', entry.id);
      modal.setAttribute('data-editing-period', period);
      if(sectionId) modal.setAttribute('data-section-id', sectionId);

      // fetch initial teacher candidates for current subject
      fetchTeachersForModal(entry.subject_id || '', period, sectionId).then(() => {
        if(entry.teacher) teacherSelect.value = entry.teacher.id;
      }).catch(()=>{});
      // store current editing id already set above
    }

    // fetch teacher candidates for the modal and populate teacher select
    async function fetchTeachersForModal(subjectId, period, sectionId){
      const teacherSelect = document.getElementById('modalTeacher');
      teacherSelect.innerHTML = '';
      if(!subjectId){
        // no subject selected -> nothing to show
        const opt = document.createElement('option'); opt.value=''; opt.text='Select subject first'; teacherSelect.appendChild(opt);
        return [];
      }
      const params = new URLSearchParams({ subject_id: subjectId || '', period: period || '', section_id: sectionId || '' });
      const url = teachersForSlotUrl + '?' + params.toString();
      const res = await fetch(url, { headers: { 'Accept':'application/json' }, credentials: 'same-origin' });
      if(!res.ok) return [];
      const data = await res.json();
      const candidates = data.candidates || [];
      if(!candidates.length){
        const opt = document.createElement('option'); opt.value=''; opt.text='No candidates'; teacherSelect.appendChild(opt);
        return [];
      }
      candidates.forEach(c => {
        const opt = document.createElement('option'); opt.value = c.id; opt.text = c.name; teacherSelect.appendChild(opt);
      });
        // focus the teacher select so user can pick immediately
        try{ teacherSelect.focus(); }catch(e){}
      return candidates;
    }

    async function saveAssignment(){
      const modal = document.getElementById('cellModal');
      const entryId = modal.getAttribute('data-editing-entry');
      const subj = document.getElementById('modalSubject').value || null;
      const teacher = document.getElementById('modalTeacher').value || null;
      // if entryId present -> assign/update; otherwise create
      if(entryId){
        const url = entryAssignUrl.replace('__ENTRY__', entryId);
        try{
          const res = await fetch(url, {
            method: 'POST', credentials: 'same-origin', headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN': csrf, 'Accept':'application/json' }, body: JSON.stringify({ subject_id: subj || null, teacher_id: teacher || null })
          });
          if(res.status === 422){ const err = await res.json(); const msgs = []; if(err && err.errors){ Object.keys(err.errors).forEach(k => { err.errors[k].forEach(m => msgs.push(m)); }); } document.getElementById('modalMessages').innerHTML = msgs.map(m=>`<div>${m}</div>`).join(''); return; }
          const data = await res.json();
          if(data && data.success){
            // update the specific cell(s) showing this entry
            const entry = data.entry;
            // find td elements with this entry id and update their texts
            document.querySelectorAll(`td.section-cell[data-entry-id='${entry.id}']`).forEach(td => {
              const subjEl = td.querySelector('.subject-text'); const teachEl = td.querySelector('.teacher-text');
              if(subjEl) subjEl.innerText = entry.subject ? (entry.subject.name || '-') : '-';
              if(teachEl) teachEl.innerText = entry.teacher ? (entry.teacher.name || '-') : (entry.teacher === null ? 'Unassigned' : (entry.teacher && entry.teacher.name) || '-');
              const statusEl = td.querySelector('.inline-status'); if(statusEl) statusEl.innerHTML = entry.conflict ? '<span class="conflict-badge text-red-600">⚠</span>' : '';
            });
            closeCellModal();
          } else { document.getElementById('modalMessages').innerText = (data && data.message) ? data.message : 'Failed to save assignment'; }
        } catch(err){ document.getElementById('modalMessages').innerText = 'Failed to save assignment'; }
        return;
      }

      // create flow
      const createUrl = _root.dataset.entryCreateUrl;
      const period = modal.getAttribute('data-editing-period');
      const sectionId = modal.getAttribute('data-section-id');
      try{
        const res = await fetch(createUrl, { method: 'POST', credentials: 'same-origin', headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN': csrf, 'Accept':'application/json' }, body: JSON.stringify({ section_id: sectionId, period: period, subject_id: subj || null, teacher_id: teacher || null }) });
        if(res.status === 422){ const err = await res.json(); const msgs = []; if(err && err.errors){ Object.keys(err.errors).forEach(k => { err.errors[k].forEach(m => msgs.push(m)); }); } document.getElementById('modalMessages').innerHTML = msgs.map(m=>`<div>${m}</div>`).join(''); return; }
        const data = await res.json();
        if(data && data.success){
          const entry = data.entry;
          // find the matching td by section and period and update
          const td = document.querySelector(`td.section-cell[data-section-id='${sectionId}'][data-period='${period}']`);
          if(td){ td.setAttribute('data-entry-id', entry.id); const subjEl = td.querySelector('.subject-text'); const teachEl = td.querySelector('.teacher-text'); if(subjEl) subjEl.innerText = entry.subject ? (entry.subject.name || '-') : '-'; if(teachEl) teachEl.innerText = entry.teacher ? (entry.teacher.name || 'Unassigned') : (entry.teacher === null ? 'Unassigned' : '-'); const statusEl = td.querySelector('.inline-status'); if(statusEl) statusEl.innerHTML = entry.conflict ? '<span class="conflict-badge text-red-600">⚠</span>' : ''; }
          closeCellModal();
        } else { document.getElementById('modalMessages').innerText = (data && data.message) ? data.message : 'Failed to create entry'; }
      } catch(err){ document.getElementById('modalMessages').innerText = 'Failed to create entry (network)'; }
    }

    function closeCellModal(){
      const modal = document.getElementById('cellModal');
      modal.classList.add('hidden');
      modal.classList.remove('flex');
    }

    async function fetchSubstitutes(entryId){
      const url = entrySubstitutesUrl.replace('__ENTRY__', entryId);
      const res = await fetch(url, { headers: { 'Accept':'application/json' }, credentials: 'same-origin' });
      const data = await res.json();
      const container = document.getElementById('modalSubstitutes');
      if(!data.candidates || !data.candidates.length){ container.innerHTML = '<div class="text-xs text-slate-500">No substitutes available</div>'; return; }
      let html = '<div class="text-xs text-slate-700 font-medium mb-1">Candidates:</div><ul class="list-disc ml-4">';
      data.candidates.forEach(c => {
        html += `<li>${c.name} <button onclick="applySubstitute(${entryId},${c.id},this)" class=\"ml-2 px-2 py-0.5 text-xs border rounded\">Apply</button></li>`;
      });
      html += '</ul>';
      container.innerHTML = html;
    }

    async function applySubstitute(entryId, substituteId, btn){
      const url = entryApplyUrl.replace('__ENTRY__', encodeURIComponent(entryId));
      btn.disabled = true;
      const res = await fetch(url, {
        method: 'POST',
        credentials: 'same-origin',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrf,
          'Accept': 'application/json'
        },
        body: JSON.stringify({ substitute_id: substituteId })
      });
      const data = await res.json();
      if(data.success){
        // update the cell DOM
        const cells = document.querySelectorAll(`[data-entry-id='${entryId}']`);
        cells.forEach(c => {
          const nameEl = c.querySelector('.teacher');
          if(nameEl) nameEl.innerText = data.substitute_name || 'Updated';
          const badge = c.querySelector('.conflict-badge'); if(badge) badge.remove();
        });
        btn.innerText = 'Applied';
      } else {
        btn.disabled = false;
        alert('Failed to apply substitute');
      }
    }

    function attachCellHandlers(){
      // edit buttons -> open inline editor for existing entries
      document.querySelectorAll('.edit-entry').forEach(btn => {
        btn.addEventListener('click', (ev) => {
          ev.preventDefault();
          const entryId = btn.getAttribute('data-entry-id');
          const td = btn.closest('td.section-cell');
          const period = td ? td.getAttribute('data-period') : null;
          const sectionId = td ? td.getAttribute('data-section-id') : null;
          if(!entryId){
            // fallback to modal for empty slots (if needed)
            openCellModal(null, period, '', '', null);
            return;
          }
          enterInlineEditor(btn, entryId, period, sectionId);
        });
      });
      // No-op: selects are wired separately
    }

    // Initialize inline selects on each cell: populate subjects, wire teacher fetch and save
    function initCellSelects(){
      document.querySelectorAll('td.section-cell').forEach(td => {
        const subj = td.querySelector('.cell-subject');
        const teach = td.querySelector('.cell-teacher');
        const period = td.getAttribute('data-period');
        const sectionId = td.getAttribute('data-section-id');
        const entryId = td.getAttribute('data-entry-id') || null;

        // populate subject select from subjectsByStage
        if(subj){
          const stage = normalizeStage(subj.getAttribute('data-grade-stage'));
          subj.innerHTML = '';
          const placeholder = document.createElement('option'); placeholder.value=''; placeholder.text = 'Select subject'; subj.appendChild(placeholder);
          const list = subjectsByStage[stage] || [];
          list.forEach(s => { const o = document.createElement('option'); o.value = subjectId(s); o.text = subjectName(s); subj.appendChild(o); });
          // preselect if provided
          const preSub = subj.getAttribute('data-selected-subject') || '';
          if(preSub) subj.value = preSub;
        }

        // prepare teacher select
        if(teach){
          teach.innerHTML = '';
          const placeholderT = document.createElement('option'); placeholderT.value=''; placeholderT.text = 'Assign Teacher'; teach.appendChild(placeholderT);
          const preTeach = teach.getAttribute('data-selected-teacher') || '';
          if(preTeach && subj && subj.value){
            // if a subject is already selected, fetch candidates and preselect teacher
            populateTeachersForCell(subj.value, period, sectionId, teach, preTeach);
          } else {
            // do NOT lock the teacher select; mark as needing a teacher but allow manual choice
            teach.classList.add('needs-teacher');
            try{
              // populate with global teacher list so users can still pick someone manually
              (allTeachers || []).forEach(t => { const o = document.createElement('option'); o.value = t.id; o.text = t.name; teach.appendChild(o); });
              teach.disabled = false; teach.removeAttribute('disabled'); teach.style.pointerEvents = '';
            } catch(e){ /* ignore */ }
          }
        }

        // when subject changes -> load teachers
        if(subj){
          subj.addEventListener('change', async (ev) => {
            const subjectId = ev.target.value || '';
            const msg = td.querySelector('.cell-message'); if(msg){ msg.style.display='none'; msg.innerText=''; }
            if(!subjectId){ 
              if(teach){ 
                // clear and populate fallback teacher list so user can still pick one
                teach.innerHTML = '';
                const blank = document.createElement('option'); blank.value=''; blank.text='Assign Teacher'; teach.appendChild(blank);
                (allTeachers || []).forEach(t => { const o = document.createElement('option'); o.value = t.id; o.text = t.name; teach.appendChild(o); });
                teach.classList.add('needs-teacher');
                teach.disabled = false; teach.removeAttribute('disabled'); teach.style.pointerEvents = '';
              }
              return; 
            }
            await populateTeachersForCell(subjectId, period, sectionId, teach, null);
          });
        }

        // when teacher changes -> save (create or assign)
        if(teach){
          teach.addEventListener('change', async (ev) => {
            const teacherId = ev.target.value || null;
            const subjectId = subj ? subj.value || null : null;
            const cellMessage = td.querySelector('.cell-message') || null;
            if(cellMessage){ cellMessage.style.display='none'; cellMessage.innerText=''; }
            if(!subjectId && !teacherId) return;
            const url = entryId ? entryAssignUrl.replace('__ENTRY__', encodeURIComponent(entryId)) : _root.dataset.entryCreateUrl;
            const body = entryId ? { subject_id: subjectId, teacher_id: teacherId } : { section_id: sectionId, period: period, subject_id: subjectId, teacher_id: teacherId };
            // disable while saving
            if(subj) subj.disabled = true; if(teach) { teach.disabled = true; teach.style.pointerEvents = 'none'; }
            try{
              const res = await fetch(url, { method:'POST', credentials:'same-origin', headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN': csrf, 'Accept':'application/json' }, body: JSON.stringify(body) });
              if(res.status === 422){ const err = await res.json(); const msgs = (err && err.errors) ? Object.values(err.errors).flat() : ['Validation error']; let m = td.querySelector('.cell-message'); if(!m){ m = document.createElement('div'); m.className='cell-message'; td.appendChild(m); } m.innerText = msgs.join('\n'); m.style.display='block'; if(subj) subj.disabled = false; if(teach){ teach.disabled = false; teach.style.pointerEvents = ''; } return; }
              const data = await res.json();
              if(data && data.success){
                const e = data.entry;
                if(!entryId && e && e.id) td.setAttribute('data-entry-id', e.id);
                // update any visible text nodes if present
                const subjText = td.querySelector('.subject-text'); const teachText = td.querySelector('.teacher-text');
                if(subjText) subjText.innerText = e.subject ? (e.subject.name||'') : '';
                if(teachText) teachText.innerText = e.teacher ? (e.teacher.name||'') : '';
                const statusEl = td.querySelector('.inline-status'); if(statusEl) statusEl.innerHTML = e.conflict ? '<span class="conflict-badge text-red-600">⚠</span>' : '';
              } else { let m = td.querySelector('.cell-message'); if(!m){ m = document.createElement('div'); m.className='cell-message'; td.appendChild(m); } m.innerText = (data && data.message) ? data.message : 'Failed to save'; m.style.display='block'; }
            } catch(err){ let m = td.querySelector('.cell-message'); if(!m){ m = document.createElement('div'); m.className='cell-message'; td.appendChild(m); } m.innerText = 'Network error'; m.style.display='block'; }
            finally { if(subj) subj.disabled = false; if(teach){ teach.disabled = false; teach.style.pointerEvents = ''; } }
          });
        }
      });
    }

    // build and attach dropdown UI into a td
    function attachDropdownUI(td){
      if(td.querySelector('.cell-dropdown-btn')) return; // already attached
      td.style.position = 'relative';
      const btn = document.createElement('button'); btn.type='button'; btn.className='cell-dropdown-btn'; btn.innerText = '⋯'; btn.title='Edit slot';
      btn.style.position = 'absolute'; btn.style.right = '8px'; btn.style.top = '8px'; btn.style.zIndex = 50;
      const dropdown = document.createElement('div'); dropdown.className = 'cell-dropdown';
      // subject select
      const subj = document.createElement('select'); subj.className = 'dd-subject'; const opt0 = document.createElement('option'); opt0.value=''; opt0.text='Select subject'; subj.appendChild(opt0);
      // teacher select
      const teach = document.createElement('select'); teach.className = 'dd-teacher'; const t0 = document.createElement('option'); t0.value=''; t0.text='Assign Teacher'; teach.appendChild(t0); teach.disabled = true;
      // actions
      const actions = document.createElement('div'); actions.className = 'dd-actions';
      const save = document.createElement('button'); save.type='button'; save.className='px-2 py-1 bg-blue-600 text-white rounded'; save.innerText='Save';
      const close = document.createElement('button'); close.type='button'; close.className='px-2 py-1 border rounded'; close.innerText='Close';
      actions.appendChild(close); actions.appendChild(save);
      dropdown.appendChild(subj); dropdown.appendChild(teach); dropdown.appendChild(actions);
      td.appendChild(btn); td.appendChild(dropdown);

      // populate subjects when opened
      btn.addEventListener('click', (ev) => {
        ev.stopPropagation();
        // toggle
        const visible = dropdown.style.display === 'block'; document.querySelectorAll('.cell-dropdown').forEach(d=>d.style.display='none');
        if(visible){ dropdown.style.display='none'; return; }
          // populate subject options based on grade stage
          const stage = normalizeStage(td.getAttribute('data-grade-stage'));
          subj.innerHTML = ''; const placeholder = document.createElement('option'); placeholder.value=''; placeholder.text='Select subject'; subj.appendChild(placeholder);
          const list = subjectsByStage[stage] || [];
        list.forEach(s => { const o = document.createElement('option'); o.value = subjectId(s); o.text = subjectName(s); subj.appendChild(o); });
        // if existing entry, select current values
        const entryId = td.getAttribute('data-entry-id') || null; if(entryId){ fetch(entryGetUrl.replace('__ENTRY__', encodeURIComponent(entryId)), { headers:{'Accept':'application/json'}, credentials:'same-origin' }).then(r=>r.json()).then(d=>{ const e=d.entry; if(e && e.subject_id) subj.value = e.subject_id; if(e && e.subject_id) { populateTeachersForCell(e.subject_id, td.getAttribute('data-period'), td.getAttribute('data-section-id'), teach, e.teacher ? e.teacher.id : null); }}).catch(()=>{}); else { teach.innerHTML = '<option value="">Assign Teacher</option>'; teach.disabled = true; }
        // position and show
        dropdown.style.display = 'block';
      });

      // subject change -> load teachers
      subj.addEventListener('change', async (ev) => {
        const subjectId = ev.target.value || '';
        const period = td.getAttribute('data-period'); const sectionId = td.getAttribute('data-section-id');
        if(!subjectId){ teach.innerHTML = '<option value="">Assign Teacher</option>'; teach.disabled = true; return; }
        await populateTeachersForCell(subjectId, period, sectionId, teach, null);
      });

      // close action
      close.addEventListener('click', (ev) => { ev.stopPropagation(); dropdown.style.display='none'; });

      // save action: create or assign
      save.addEventListener('click', async (ev) => {
        ev.stopPropagation(); save.disabled = true; const subjectId = subj.value || null; const teacherId = teach.value || null; const entryId = td.getAttribute('data-entry-id') || null; const period = td.getAttribute('data-period'); const sectionId = td.getAttribute('data-section-id');
        let url, body;
        if(entryId){ url = entryAssignUrl.replace('__ENTRY__', encodeURIComponent(entryId)); body = { subject_id: subjectId, teacher_id: teacherId }; }
        else { url = _root.dataset.entryCreateUrl; body = { section_id: sectionId, period: period, subject_id: subjectId, teacher_id: teacherId }; }
        try{
          const res = await fetch(url, { method:'POST', credentials:'same-origin', headers:{ 'Content-Type':'application/json','X-CSRF-TOKEN': csrf, 'Accept':'application/json' }, body: JSON.stringify(body) });
          if(res.status === 422){ const err = await res.json(); const msgs = (err && err.errors) ? Object.values(err.errors).flat() : ['Validation error']; let m = td.querySelector('.cell-message'); if(!m){ m = document.createElement('div'); m.className='cell-message'; td.appendChild(m); } m.innerText = msgs.join('\n'); m.style.display='block'; save.disabled = false; return; }
          const data = await res.json();
          if(data && data.success){ const e = data.entry; // update cell
            if(!entryId && e && e.id) td.setAttribute('data-entry-id', e.id);
            const subjText = td.querySelector('.subject-text'); const teachText = td.querySelector('.teacher-text'); if(subjText) subjText.innerText = e.subject ? (e.subject.name||'') : ''; if(teachText) teachText.innerText = e.teacher ? (e.teacher.name||'') : '';
            const statusEl = td.querySelector('.inline-status'); if(statusEl) statusEl.innerHTML = e.conflict ? '<span class="conflict-badge text-red-600">⚠</span>' : '';
            dropdown.style.display='none';
          } else { let m = td.querySelector('.cell-message'); if(!m){ m = document.createElement('div'); m.className='cell-message'; td.appendChild(m); } m.innerText = (data && data.message) ? data.message : 'Failed to save'; m.style.display='block'; }
        } catch(err){ let m = td.querySelector('.cell-message'); if(!m){ m = document.createElement('div'); m.className='cell-message'; td.appendChild(m); } m.innerText = 'Failed (network)'; m.style.display='block'; }
        save.disabled = false;
      });

      // close dropdown when clicking outside
      document.addEventListener('click', (ev) => { if(!td.contains(ev.target)) dropdown.style.display='none'; });
    }

    async function populateTeachersForCell(subjectId, period, sectionId, teacherSelectEl, preselectId){
      console.log('populateTeachersForCell start', { subjectId: subjectId, period: period, sectionId: sectionId });
      try{ teacherSelectEl.disabled = true; teacherSelectEl.setAttribute('disabled','disabled'); teacherSelectEl.style.pointerEvents = 'none'; teacherSelectEl.innerHTML = '<option value="">Loading...</option>'; }catch(e){}
      const params = new URLSearchParams({ subject_id: subjectId || '', period: period || '', section_id: sectionId || '' });
      // include current teacher if provided so they remain selectable even if server filters them out
      if(preselectId) params.set('include_teacher', preselectId);
      const url = teachersForSlotUrl + '?' + params.toString();
      try{
        const res = await fetch(url, { headers: { 'Accept':'application/json' }, credentials: 'same-origin' });
        if(!res.ok){ teacherSelectEl.innerHTML = '<option>Error loading</option>'; teacherSelectEl.disabled = false; teacherSelectEl.removeAttribute('disabled'); teacherSelectEl.style.pointerEvents = 'auto'; return; }
        const data = await res.json();
        const candidates = data.candidates || [];
        console.log('populateTeachersForCell response', { status: res.status, candidates: candidates.length, payload: data });
        if(!candidates.length){
          // no candidates from server: show message but keep control enabled so user can pick manually
          teacherSelectEl.innerHTML = '';
          const blank = document.createElement('option'); blank.value=''; blank.text='No candidates (choose manually)'; teacherSelectEl.appendChild(blank);
          (allTeachers || []).forEach(t => { const o = document.createElement('option'); o.value = t.id; o.text = t.name; teacherSelectEl.appendChild(o); });
          teacherSelectEl.classList.add('needs-teacher');
          teacherSelectEl.disabled = false; teacherSelectEl.removeAttribute('disabled'); teacherSelectEl.style.pointerEvents = '';
          return;
        }
        teacherSelectEl.innerHTML = '';
        const blank = document.createElement('option'); blank.value=''; blank.text='Assign Teacher'; teacherSelectEl.appendChild(blank);
        candidates.forEach(c => { const o = document.createElement('option'); o.value = c.id; o.text = c.name; teacherSelectEl.appendChild(o); });
        if(preselectId){ teacherSelectEl.value = preselectId; }
        // enable the select
        teacherSelectEl.disabled = false; teacherSelectEl.removeAttribute('disabled'); teacherSelectEl.style.pointerEvents = '';
        try{ teacherSelectEl.focus(); }catch(e){}
        console.log('populateTeachersForCell enabled', { teacherSelectEl: teacherSelectEl, preselectId: preselectId });
      } catch(err){ teacherSelectEl.innerHTML = '<option>Error</option>'; teacherSelectEl.disabled = false; teacherSelectEl.removeAttribute('disabled'); teacherSelectEl.style.pointerEvents = 'auto'; }
    }

    // Create inline editor inside the cell's section-line
    async function enterInlineEditor(btn, entryId, period, sectionId){
      const wrapper = btn.closest('td.section-cell');
      if(!wrapper) return;
      // prevent duplicate editors (not applicable but keep guard)
      if(wrapper.querySelector('.inline-editor')) return;
      // store original content to allow cancel
      wrapper.setAttribute('data-original-html', wrapper.innerHTML);
      const gradeStage = normalizeStage(btn.getAttribute('data-grade-stage'));

      // we'll reuse existing hidden selects inside the td
      const subjSel = wrapper.querySelector('.cell-subject');
      const teacherSel = wrapper.querySelector('.cell-teacher');
      const subjText = wrapper.querySelector('.subject-text');
      const teacherText = wrapper.querySelector('.teacher-text');

      if(!subjSel || !teacherSel) return;

      // hide text and show selects
      if(subjText) subjText.style.display = 'none'; subjSel.style.display = '';
      if(teacherText) teacherText.style.display = 'none'; teacherSel.style.display = '';

      // populate subject options if empty
      if(subjSel.options.length <= 1){
        subjSel.innerHTML = '<option value="">-- select subject --</option>';
        const subjects = subjectsByStage[gradeStage] || [];
        subjects.forEach(s => { const o = document.createElement('option'); o.value = subjectId(s); o.text = subjectName(s); subjSel.appendChild(o); });
      }

      // if entryId, preselect from server
      if(entryId){
        try{
          const res = await fetch(entryGetUrl.replace('__ENTRY__', encodeURIComponent(entryId)), { headers: { 'Accept':'application/json' }, credentials: 'same-origin' });
          const d = await res.json();
          const e = d.entry;
          if(e && e.subject_id) subjSel.value = e.subject_id;
          if(e) await populateTeachersForCell(subjSel.value, period, sectionId, teacherSel, e.teacher ? e.teacher.id : null);
        } catch(err){}
      }

      // focus subject select
      subjSel.focus();

      // when subject changes, fetch teachers
      const onSubjChange = async (ev) => {
        const subjectId = ev.target.value || '';
        if(!subjectId){ teacherSel.innerHTML = '<option value="">Assign Teacher</option>'; teacherSel.disabled = true; teacherSel.setAttribute('disabled','disabled'); teacherSel.style.pointerEvents = 'none'; return; }
        await populateTeachersForCell(subjectId, period, sectionId, teacherSel, null);
      };
      subjSel.addEventListener('change', onSubjChange);

      // when teacher selected, save and close editor
      const onTeacherChange = async (ev) => {
        const teacherId = ev.target.value || null;
        const subjectId = subjSel.value || null;
        if(!subjectId && !teacherId) return;
        let url = null; let body = {};
        if(entryId){ url = entryAssignUrl.replace('__ENTRY__', encodeURIComponent(entryId)); body = { subject_id: subjectId, teacher_id: teacherId }; }
        else { url = _root.dataset.entryCreateUrl; body = { section_id: sectionId, period: period, subject_id: subjectId, teacher_id: teacherId }; }
        try{
          const res = await fetch(url, { method: 'POST', credentials: 'same-origin', headers: { 'Content-Type':'application/json','X-CSRF-TOKEN': csrf, 'Accept':'application/json' }, body: JSON.stringify(body) });
          if(res.status === 422){ const err = await res.json(); const msgs = (err && err.errors) ? Object.values(err.errors).flat() : ['Validation error']; alert(msgs.join('\n')); return; }
          const data = await res.json();
          if(data && data.success){
            // update displayed texts
            if(subjText) subjText.innerText = data.entry && data.entry.subject ? (data.entry.subject.name || '') : '';
            if(teacherText) teacherText.innerText = data.entry && data.entry.teacher ? (data.entry.teacher.name || '') : '';
            // hide selects and show texts
            subjSel.style.display = 'none'; teacherSel.style.display = 'none';
            if(subjText) subjText.style.display = ''; if(teacherText) teacherText.style.display = '';
            // if created, set data-entry-id
            if(!entryId && data.entry && data.entry.id){ wrapper.setAttribute('data-entry-id', data.entry.id); }
          } else { alert((data && data.message) ? data.message : 'Failed to save'); }
        } catch(err){ alert('Failed to save assignment'); }
      };
      teacherSel.addEventListener('change', onTeacherChange);

      // clean up function to remove listeners if needed (not used now)
      const cleanup = () => { subjSel.removeEventListener('change', onSubjChange); teacherSel.removeEventListener('change', onTeacherChange); };
    }

    window.addEventListener('resize', () => { setTimeout(fitToScreen, 80); });
    window.addEventListener('load', () => { fitToScreen(); attachCellHandlers(); initCellSelects();
      const printBtn = document.getElementById('btnPrint'); if(printBtn) printBtn.addEventListener('click', () => window.print());
      // wire subject -> teacher refresh in modal
      const subj = document.getElementById('modalSubject');
      if(subj){
        subj.addEventListener('change', async (ev) => {
          document.getElementById('modalMessages').innerHTML = '';
          const modal = document.getElementById('cellModal');
          const period = modal.getAttribute('data-editing-period');
          const sectionId = modal.getAttribute('data-section-id');
          const subjectId = ev.target.value || '';
          await fetchTeachersForModal(subjectId, period, sectionId);
        });
      }
      // generate helper used by both top and bottom buttons
      async function doGenerate(btn){
        if(!confirm('Generate schedule automatically (will overwrite unassigned slots)?')) return;
        if(btn) { btn.disabled = true; btn.innerText = 'Generating...'; }
        const genUrl = _root.dataset.generateUrl;
        try{
          const res = await fetch(genUrl, {
            method: 'POST', credentials: 'same-origin', headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN': csrf, 'Accept':'application/json' },
            body: JSON.stringify({ action: 'generate' })
          });
          const data = await res.json();
          if(res.ok && data.success){
            alert('Schedule generated — reloading to show results.');
            window.location.reload();
          } else {
            alert((data && data.message) ? data.message : 'Failed to generate schedule');
          }
        } catch(err){
          alert('Failed to contact server to generate schedule');
        } finally { if(btn) { btn.disabled = false; btn.innerText = 'Generate (auto-fill)'; } }
      }

      // generate button (top)
      const genBtn = document.getElementById('generateScheduleBtn');
      if(genBtn){ genBtn.addEventListener('click', (e) => { e.preventDefault(); doGenerate(genBtn); }); }

      // bottom save button: acts as a quick checkpoint (reload to pick up server state)
      const saveBtnBottom = document.getElementById('saveMatrixBtnBottom');
      if(saveBtnBottom){
        saveBtnBottom.addEventListener('click', (e) => {
          e.preventDefault();
          if(confirm('Save current schedule snapshot? (This will reload the page)')){
            window.location.reload();
          }
        });
      }

      // save button (entries are saved per-change; this will refresh and act as a checkpoint)
      const saveBtn = document.getElementById('saveMatrixBtn');
      if(saveBtn){
        saveBtn.addEventListener('click', (e) => {
          e.preventDefault();
          // entries are saved per-entry; provide a quick reload to ensure server state is current
          if(confirm('Refresh to validate and show latest schedule state?')){
            window.location.reload();
          }
        });
      }

      // export dropdown removed — guard old selectors in case they're referenced elsewhere
      const exportBtn = document.getElementById('exportBtn');
      const exportMenu = document.getElementById('exportMenu');
      if(exportBtn && exportMenu){
        exportBtn.addEventListener('click', (ev)=>{ ev.preventDefault(); exportMenu.classList.toggle('hidden'); });
        const exportCsvEl = document.getElementById('exportCsv');
        const exportPdfEl = document.getElementById('exportPdf');
        if(exportCsvEl) exportCsvEl.addEventListener('click', (ev)=>{ ev.preventDefault(); exportTableAsCSV(); exportMenu.classList.add('hidden'); });
        if(exportPdfEl) exportPdfEl.addEventListener('click', (ev)=>{ ev.preventDefault(); exportTableAsPdf(); exportMenu.classList.add('hidden'); });
      }
    });

    // build CSV from the table and trigger download
    function exportTableAsCSV(){
      const rows = [];
      const headerCells = Array.from(document.querySelectorAll('table thead th')).map(h => h.innerText.trim());
      rows.push(headerCells.join(','));
      const trs = document.querySelectorAll('table tbody tr');
      trs.forEach(tr => {
        const cols = [
          (tr.querySelector('td') ? tr.querySelector('td').innerText.trim().replace(/\n/g,' ') : '')
        ];
        const periodCells = tr.querySelectorAll('td[data-period]');
        periodCells.forEach(td => {
          // flatten subject + teacher
          const subj = td.querySelector('.subject-name-inline') ? td.querySelector('.subject-name-inline').innerText.trim() : (td.querySelector('.subject-name') ? td.querySelector('.subject-name').innerText.trim() : '');
          const teach = td.querySelector('.teacher-name') ? td.querySelector('.teacher-name').innerText.trim() : '';
          cols.push(`"${(subj + ' / ' + teach).replace(/"/g,'""')}"`);
        });
        rows.push(cols.join(','));
      });
      const csv = rows.join('\n');
      const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
      const url = URL.createObjectURL(blob);
      const a = document.createElement('a'); a.href = url; a.download = `schedule-run-{{ $run->id }}.csv`; document.body.appendChild(a); a.click(); a.remove(); URL.revokeObjectURL(url);
    }

    // simple PDF-ish export using print: open printable window with the table HTML
    function exportTableAsPdf(){
      const html = document.querySelector('table').outerHTML;
      const w = window.open('', '_blank');
      w.document.write('<html><head><title>Schedule - Print</title><style>table{width:100%;border-collapse:collapse;} td,th{border:1px solid #ddd;padding:6px;font-size:11px;} .subject{font-weight:600;}</style></head><body>');
      w.document.write('<h3>Schedule - Run: {{ addslashes($run->name) }}</h3>');
      w.document.write(html);
      w.document.write('</body></html>');
      w.document.close();
      setTimeout(()=>{ w.print(); }, 300);
    }

    // keep page-level scrolling disabled so only the matrix scrolls
    window.addEventListener('resize', () => { setTimeout(() => { fitToScreen(); }, 120); });
    window.addEventListener('load', () => { setTimeout(()=>{ fitToScreen(); }, 200); });

    // ----- Non-destructive alternate view renderers (read-only; built from the compact matrix DOM) -----
    (function(){
      function renderClassView(){
        const out = document.getElementById('classView'); if(!out) return; out.innerHTML = '';
        const rows = document.querySelectorAll('table tbody tr');
        rows.forEach(tr => {
          const header = tr.querySelector('td');
          const title = header ? header.innerText.trim() : 'Section';
          const container = document.createElement('div'); container.style.marginBottom = '10px'; container.style.paddingBottom='8px'; container.style.borderBottom='1px solid #eee';
          const h = document.createElement('div'); h.style.fontWeight='700'; h.style.marginBottom='6px'; h.innerText = title; container.appendChild(h);
          const cells = tr.querySelectorAll('td.section-cell');
          cells.forEach(td => {
            const p = td.getAttribute('data-period') || '?';
            const subj = td.querySelector('.subject-text') ? td.querySelector('.subject-text').innerText.trim() : (td.querySelector('.cell-subject') ? (td.querySelector('.cell-subject').selectedOptions[0]?.text || '') : '');
            const teach = td.querySelector('.teacher-text') ? td.querySelector('.teacher-text').innerText.trim() : (td.querySelector('.cell-teacher') ? (td.querySelector('.cell-teacher').selectedOptions[0]?.text || '') : '');
            const row = document.createElement('div'); row.style.fontSize='13px'; row.innerHTML = `<strong>P${p}:</strong> ${subj || '<em>—</em>'} <span style="color:#475569;">— ${teach || '<em>Unassigned</em>'}</span>`;
            container.appendChild(row);
          });
          out.appendChild(container);
        });
      }

      function renderTeacherView(){
        const out = document.getElementById('teacherView'); if(!out) return; out.innerHTML = '';
        const map = {}; // teacherName -> [{section, period, subject}]
        document.querySelectorAll('td.section-cell').forEach(td => {
          const section = td.getAttribute('data-section-name') || td.getAttribute('data-section-id') || 'Section';
          const period = td.getAttribute('data-period') || '?';
          const subj = td.querySelector('.subject-text') ? td.querySelector('.subject-text').innerText.trim() : (td.querySelector('.cell-subject') ? (td.querySelector('.cell-subject').selectedOptions[0]?.text || '') : '');
          const teach = td.querySelector('.teacher-text') ? td.querySelector('.teacher-text').innerText.trim() : (td.querySelector('.cell-teacher') ? (td.querySelector('.cell-teacher').selectedOptions[0]?.text || '') : '');
          if(!teach || teach === '' || teach === 'Assign Teacher' || teach.toLowerCase().includes('unassigned')) return;
          if(!map[teach]) map[teach] = [];
          map[teach].push({ section: section, period: period, subject: subj });
        });
        // sort teacher names
        Object.keys(map).sort().forEach(tn => {
          const block = document.createElement('div'); block.style.marginBottom='12px';
          const h = document.createElement('div'); h.style.fontWeight='700'; h.style.marginBottom='6px'; h.innerText = tn; block.appendChild(h);
          map[tn].sort((a,b)=> (a.period||'').localeCompare(b.period||'')).forEach(item => {
            const r = document.createElement('div'); r.innerHTML = `<strong>P${item.period}:</strong> ${item.subject || '<em>—</em>'} <span style="color:#475569">— ${item.section}</span>`; block.appendChild(r);
          });
          out.appendChild(block);
        });
        if(Object.keys(map).length === 0){ out.innerHTML = '<div class="text-sm text-slate-600">No assigned teachers found in current view.</div>'; }
      }

      // Bind toolbar buttons after DOM load to avoid timing issues
      function bindViewButtons(){
        const btnCompact = document.getElementById('viewToggleCompact');
        const btnClass = document.getElementById('viewToggleClass');
        const btnTeacher = document.getElementById('viewToggleTeacher');
        const matrixScroll = document.getElementById('matrix-scroll');
        const classView = document.getElementById('classView');
        const teacherView = document.getElementById('teacherView');
        if(btnCompact) btnCompact.addEventListener('click', () => { if(matrixScroll) matrixScroll.style.display = ''; if(classView) classView.style.display = 'none'; if(teacherView) teacherView.style.display = 'none'; setTimeout(fitToScreen,40); });
        if(btnClass) btnClass.addEventListener('click', () => { if(matrixScroll) matrixScroll.style.display = 'none'; if(classView) classView.style.display = ''; if(teacherView) teacherView.style.display = 'none'; renderClassView(); setTimeout(fitToScreen,40); });
        if(btnTeacher) btnTeacher.addEventListener('click', () => { if(matrixScroll) matrixScroll.style.display = 'none'; if(classView) classView.style.display = 'none'; if(teacherView) teacherView.style.display = ''; renderTeacherView(); setTimeout(fitToScreen,40); });
      }

      if(document.readyState === 'complete' || document.readyState === 'interactive'){
        bindViewButtons();
      } else {
        window.addEventListener('load', bindViewButtons);
      }
    })();
  </script>

@endsection
