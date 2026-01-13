@php
  $isEdit = isset($teacher) && $teacher;
  $action = $isEdit ? route('admin.teachers.update', $teacher) : route('admin.teachers.store');
  // ensure $subjects is available even if the parent view forgot to pass it (fallback to DB)
  $subjects = isset($subjects) ? $subjects : \App\Models\Subject::orderBy('name')->get();
  $selectedSubjects = old('subjects', $isEdit && isset($teacher) && isset($teacher->subjects) ? $teacher->subjects->pluck('id')->toArray() : []);
  // ensure $gradeLevels is available (fallback)
  $gradeLevels = isset($gradeLevels) ? $gradeLevels : \App\Models\GradeLevel::orderBy('id')->get();
  $selectedGrades = old('grade_levels', $isEdit && isset($teacher) && isset($teacher->gradeLevels) ? $teacher->gradeLevels->pluck('id')->toArray() : []);
  $sections = isset($sections) ? $sections : \App\Models\Section::orderBy('name')->get();
@endphp

<form id="teacher-form" method="POST" action="{{ $action }}">
  @csrf
  @if($isEdit) @method('PUT') @endif

  <!-- Validation Feedback Panel -->
  <div id="validationPanel" class="mb-4 hidden">
    <div class="bg-amber-50 border border-amber-200 rounded-md p-3">
      <div class="flex items-start gap-2">
        <span class="text-lg">⚠️</span>
        <div class="flex-1">
          <h4 class="text-sm font-medium text-amber-900">Validation Issues</h4>
          <ul id="validationList" class="mt-1 text-xs text-amber-800 space-y-1 list-disc list-inside"></ul>
        </div>
      </div>
    </div>
  </div>

  <div class="space-y-4">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
    <div class="md:col-span-2">
    <label for="name" class="block text-sm font-medium">Name <span class="text-red-600">*</span></label>
  <input id="name" name="name" required value="{{ old('name', $teacher->name ?? '') }}" class="mt-1 block w-full border border-gray-300 rounded-md p-2 h-11 bg-white box-border validation-field" />
      </div>

    <div class="md:col-span-1">
    <label for="sex" class="block text-sm font-medium">Sex</label>
  <select id="sex" name="sex" class="mt-1 block w-full border border-gray-300 rounded-md p-2 h-11 bg-white box-border" title="Sex">
          <option value="">-- select --</option>
          <option value="male" @if(old('sex', $teacher->sex ?? '') === 'male') selected @endif>Male</option>
          <option value="female" @if(old('sex', $teacher->sex ?? '') === 'female') selected @endif>Female</option>
          <option value="other" @if(old('sex', $teacher->sex ?? '') === 'other') selected @endif>Other</option>
        </select>
      </div>

  <div class="md:col-span-1">
    <label for="staff_id" class="block text-sm font-medium">Employee ID <span class="text-red-600">*</span></label>
  <input id="staff_id" name="staff_id" required value="{{ old('staff_id', $teacher->staff_id ?? '') }}" class="mt-1 block w-full border border-gray-300 rounded-md p-2 h-11 bg-white box-border validation-field" />
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
    <div>
    <label for="designation" class="block text-sm font-medium">Designation / Position <span class="text-red-600">*</span></label>
  <select id="designation" name="designation" required class="mt-1 block w-full border border-gray-300 rounded-md p-2 h-11 bg-white box-border validation-field">
        <option value="">-- select --</option>
        @foreach(config('teachers.designations', []) as $d)
          <option value="{{ $d }}" @if(old('designation', $teacher->designation ?? '') === $d) selected @endif>{{ $d }}</option>
        @endforeach
      </select>
      </div>

  <div>
    <label for="status_of_appointment" class="block text-sm font-medium">Status of Appointment <span class="text-red-600">*</span></label>
  <select id="status_of_appointment" name="status_of_appointment" required class="mt-1 block w-full border border-gray-300 rounded-md p-2 h-11 bg-white box-border">
        <option value="">-- select --</option>
        @foreach(config('teachers.statuses', []) as $s)
          <option value="{{ $s }}" @if(old('status_of_appointment', $teacher->status_of_appointment ?? '') === $s) selected @endif>{{ $s }}</option>
        @endforeach
      </select>
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
  <div>
    <label for="email" class="block text-sm font-medium">Email <span class="text-red-600">*</span></label>
    <input id="email" name="email" required value="{{ old('email', $teacher->email ?? '') }}" class="mt-1 block w-full border border-gray-300 rounded-md p-2 h-11 bg-white box-border" />
  </div>

  <div>
    <label for="phone" class="block text-sm font-medium">Phone / Contact <span class="text-red-600">*</span></label>
    <input id="phone" name="contact" required value="{{ old('contact', $teacher->contact ?? '') }}" class="mt-1 block w-full border border-gray-300 rounded-md p-2 h-11 bg-white box-border" />
  </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
  <div>
    <label for="course_degree" class="block text-sm font-medium">Course / Degree</label>
  <input id="course_degree" name="course_degree" value="{{ old('course_degree', $teacher->course_degree ?? '') }}" class="mt-1 block w-full border border-gray-300 rounded-md p-2 h-11 bg-white box-border" />
      </div>

  <div>
    <label for="course_major" class="block text-sm font-medium">Course Major</label>
  <input id="course_major" name="course_major" value="{{ old('course_major', $teacher->course_major ?? '') }}" class="mt-1 block w-full border border-gray-300 rounded-md p-2 h-11 bg-white box-border" />
      </div>

  <div>
    <label for="course_minor" class="block text-sm font-medium">Course Minor</label>
  <input id="course_minor" name="course_minor" value="{{ old('course_minor', $teacher->course_minor ?? '') }}" class="mt-1 block w-full border border-gray-300 rounded-md p-2 h-11 bg-white box-border" />
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
      <div>
    <label for="ancillary_assignments" class="block text-sm font-medium">Ancillary / Special Assignments</label>
  <textarea id="ancillary_assignments" name="ancillary_assignments" placeholder="e.g. Class sponsor, dept. coordinator" class="mt-1 block w-full border border-gray-300 rounded-md p-2 h-11 bg-white resize-none box-border" rows="2">{{ old('ancillary_assignments', $teacher->ancillary_assignments ?? '') }}</textarea>
        <p class="text-xs text-slate-500 mt-1">Keep it short — a brief note about additional duties (optional).</p>
      </div>

      <div>
  <label for="avail-control" class="block text-sm font-medium">Unavailable Periods</label>
      <input type="hidden" id="advisory-input" name="advisory" value="{{ old('advisory', $teacher->advisory ?? '') }}">

      {{-- reuse the ms-control look for periods; selections sync back into the hidden CSV advisory input --}}
  <select id="availability-native" name="availability[]" multiple class="ms-native-hidden" title="Unavailable periods">
        @php
          $selAvail = old('availability', $teacher->availability ?? []);
          if (is_string($selAvail)) $selAvail = explode(',', $selAvail);
        @endphp
        @for($i=1;$i<=9;$i++)
          @php
            $val = (string)$i;
            $label = $i.'th Period';
            if($i==1) $label='1st Period';
            if($i==2) $label='2nd Period';
            if($i==3) $label='3rd Period';
          @endphp
          <option value="{{ $val }}" @if(in_array($val, $selAvail)) selected @endif>{{ $label }}</option>
        @endfor
      </select>

      <div class="ms-container mt-1">
    <div class="ms-control" id="avail-control" tabindex="0" role="combobox" aria-expanded="false" aria-haspopup="listbox" aria-label="Unavailable Periods">
          <div id="avail-tokens" class="ms-tokens"></div>
          <button type="button" id="avail-toggle" class="ms-button" aria-label="Toggle availability dropdown">▾</button>
        </div>

        <div class="ms-dropdown" role="listbox" aria-multiselectable="true">
          <div class="ms-search"><input aria-label="Search periods" placeholder="Search..." class="w-full border rounded px-2 py-1" /></div>
          
          <div class="ms-list">
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
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
        <div>
      <label class="block text-sm font-medium">Subject Expertise <span class="text-red-600">*</span></label>

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
        .ms-native-hidden{ display:none !important; }
        .ms-tokens{ display:flex; gap:8px; flex-wrap:wrap; }
      </style>

      {{-- hidden native select to submit values --}}
  <select id="subjects-native" name="subjects[]" multiple required class="ms-native-hidden" title="Subjects">
        @foreach($subjects as $s)
          <option value="{{ $s->id }}" @if(in_array($s->id, $selectedSubjects)) selected @endif>{{ $s->name }}</option>
        @endforeach
      </select>

      <div class="ms-container mt-1">
      <div class="ms-control" id="subjects-control" tabindex="0" role="combobox" aria-expanded="false" aria-haspopup="listbox" aria-label="Subject Assignment">
              <div id="ms-tokens" class="ms-tokens">
            {{-- tokens inserted here --}}
          </div>
          <button type="button" id="ms-toggle" class="ms-button" aria-label="Toggle subjects dropdown">▾</button>
        </div>

        <div class="ms-dropdown" role="listbox" aria-multiselectable="true">
            <div class="ms-search"><input aria-label="Search subjects" placeholder="Search..." class="w-full border rounded px-2 py-1" /></div>
            
            <div class="ms-list">
              @foreach($subjects as $s)
                <div class="ms-item" data-id="{{ $s->id }}" role="option" aria-selected="{{ in_array($s->id,$selectedSubjects) ? 'true' : 'false' }}">
                  <input type="checkbox" class="ms-checkbox" data-id="{{ $s->id }}" @if(in_array($s->id,$selectedSubjects)) checked @endif />
                  <div>{{ $s->name }} @if(!empty($s->code)) <small class="text-slate-400">({{ $s->code }})</small> @endif</div>
                </div>
              @endforeach
            </div>
          </div>
        </div>
      </div>

    <div>
  <label class="block text-sm font-medium">Grade Level Assignment <span class="text-red-600">*</span></label>

      {{-- native select for grade levels (submitted as grade_levels[]) --}}
  <select id="grade-levels-native" name="grade_levels[]" multiple required class="ms-native-hidden" title="Grade levels">
        @foreach($gradeLevels as $g)
          <option value="{{ $g->id }}" @if(in_array($g->id, $selectedGrades)) selected @endif>{{ $g->name }}</option>
        @endforeach
      </select>

      <div class="ms-container mt-1">
      <div class="ms-control" id="grade-control" tabindex="0" role="combobox" aria-expanded="false" aria-haspopup="listbox" aria-label="Grade Level Assignment">
              <div id="grade-tokens" class="ms-tokens"></div>
          <button type="button" id="grade-toggle" class="ms-button" aria-label="Toggle grade levels dropdown">▾</button>
        </div>

        <div class="ms-dropdown" role="listbox" aria-multiselectable="true">
            <div class="ms-search"><input aria-label="Search grade levels" placeholder="Search..." class="w-full border rounded px-2 py-1" /></div>
            <div class="ms-select-all"><input id="grade-select-all" type="checkbox" /> <label for="grade-select-all">Select all</label></div>
            <div class="ms-list">
              @foreach($gradeLevels as $g)
                <div class="ms-item" data-id="{{ $g->id }}" role="option" aria-selected="{{ in_array($g->id,$selectedGrades) ? 'true' : 'false' }}">
                  <input type="checkbox" class="ms-checkbox" data-id="{{ $g->id }}" @if(in_array($g->id,$selectedGrades)) checked @endif />
                  <div>{{ $g->name }}</div>
                </div>
              @endforeach
            </div>
          </div>
        </div>
      
      </div>

    <div class="pt-4">
      <button type="submit" class="px-4 py-2 bg-[#3b4197] text-white rounded">{{ $isEdit ? 'Save' : 'Create' }}</button>
      <a href="{{ route('admin.teachers.index') }}" class="ml-2 text-sm">Cancel</a>
      @if($isEdit)
        <button type="button" id="btn-delete-teacher" data-id="{{ $teacher->id }}" class="ml-2 px-3 py-1 bg-red-600 text-white rounded text-sm">Remove</button>
      @endif
    </div>
  </div>
</form>

<script>
  // Enforce numeric-only phone input and limit unavailable periods to max 3 selections.
  // This initializer runs immediately (useful when the partial is injected via AJAX)
  (function(){
    function initTeacherFormEnforcements(){
      const phone = document.getElementById('phone');
      if (phone) {
        phone.setAttribute('inputmode', 'tel');
        // Allow only digits and + symbol at start
        phone.addEventListener('input', function(){
          const pos = this.selectionStart;
          // Allow + only at the beginning, followed by digits
          let newVal = this.value;
          if (newVal.startsWith('+')) {
            newVal = '+' + newVal.slice(1).replace(/\D+/g, '');
          } else {
            newVal = newVal.replace(/\D+/g, '');
          }
          if (this.value !== newVal) {
            this.value = newVal;
            try { this.setSelectionRange(pos, pos); } catch(e){}
          }
        });
      }
      
      // Normalize phone number before form submission
      const form = document.getElementById('teacher-form');
      if (form && phone) {
        form.addEventListener('submit', function(e) {
          const phoneValue = phone.value.trim();
          if (phoneValue) {
            // Normalize to +639XXXXXXXXX format
            let normalized = phoneValue;
            
            if (/^9\d{9}$/.test(phoneValue)) {
              // Format: 9XXXXXXXXX (10 digits)
              normalized = '+63' + phoneValue;
            } else if (/^09\d{9}$/.test(phoneValue)) {
              // Format: 09XXXXXXXXX (11 digits)
              normalized = '+63' + phoneValue.slice(1);
            } else if (/^63\d{10}$/.test(phoneValue)) {
              // Format: 63XXXXXXXXXX (12 digits)
              normalized = '+' + phoneValue;
            } else if (/^\+63\d{10}$/.test(phoneValue)) {
              // Already in correct format
              normalized = phoneValue;
            }
            
            phone.value = normalized;
          }
        });
      }

      const availControl = document.getElementById('avail-control');
      if (!availControl) return;
      const container = availControl.closest('.ms-container');
      if (!container) return;

      // delegated change handler for checkboxes (works for dynamic items)
      container.addEventListener('change', function(ev){
        const target = ev.target;
        const native = container.querySelector('#availability-native');
        const selectAll = container.querySelector('#avail-select-all');
        // if select-all toggle used
        if (target && target.id === 'avail-select-all') {
          if (target.checked) {
            // select up to 3 items (first 3 in list)
            const items = Array.from(container.querySelectorAll('.ms-checkbox'));
            let count = 0;
            items.forEach(cb => {
              if (count < 3) { cb.checked = true; count++; } else cb.checked = false;
            });
          } else {
            // clear all
            container.querySelectorAll('.ms-checkbox').forEach(cb => cb.checked = false);
          }
          // fall through to sync native select
        }

        if (target && target.classList && target.classList.contains('ms-checkbox')) {
          const checked = container.querySelectorAll('.ms-checkbox:checked').length;
          if (checked > 3) {
            // revert the change that caused overflow
            target.checked = false;
            if (typeof showToast === 'function') showToast('You can select up to 3 unavailable periods', 'error');
            else alert('You can select up to 3 unavailable periods');
          }
        }

        // sync native select values
        if (native) {
          const vals = Array.from(container.querySelectorAll('.ms-checkbox:checked')).map(i => i.dataset.id);
          Array.from(native.options).forEach(opt => opt.selected = vals.indexOf(opt.value) !== -1);
        }
      }, true);
    }

    // Validation feedback system
    function initValidation() {
      const form = document.getElementById('teacher-form');
      const validationPanel = document.getElementById('validationPanel');
      const validationList = document.getElementById('validationList');
      
      function validateForm() {
        const issues = [];
        
        // Check required fields
        const nameInput = document.getElementById('name');
        if (!nameInput.value.trim()) issues.push('Name is required');
        
        const staffIdInput = document.getElementById('staff_id');
        if (!staffIdInput.value.trim()) issues.push('Staff ID is required');
        
        const designationSelect = document.getElementById('designation');
        if (!designationSelect.value) issues.push('Designation is required');
        
        const emailInput = document.getElementById('email');
        if (!emailInput.value.trim()) issues.push('Email is required');
        
        const phoneInput = document.getElementById('phone');
        const phoneValue = phoneInput.value.trim();
        if (!phoneValue) {
          issues.push('Phone is required');
        } else {
          // Validate Philippine phone formats
          const isValid = /^9\d{9}$/.test(phoneValue) ||        // 9XXXXXXXXX (10 digits)
                         /^09\d{9}$/.test(phoneValue) ||       // 09XXXXXXXXX (11 digits)
                         /^63\d{10}$/.test(phoneValue) ||      // 63XXXXXXXXXX (12 digits)
                         /^\+63\d{10}$/.test(phoneValue);      // +63XXXXXXXXXX (13 chars)
          if (!isValid) {
            issues.push('Phone must be a valid Philippine number (e.g., 09XXXXXXXXX or +639XXXXXXXXX)');
          }
        }
        
        // Check relationships
        const subjectsNative = document.getElementById('subjects-native');
        const selectedSubjects = Array.from(subjectsNative.options).filter(o => o.selected);
        if (selectedSubjects.length === 0) issues.push('At least one subject expertise must be selected');
        
        const gradesNative = document.getElementById('grade-levels-native');
        const selectedGrades = Array.from(gradesNative.options).filter(o => o.selected);
        if (selectedGrades.length === 0) issues.push('At least one grade level must be assigned');
        
        // Display or hide validation panel
        if (issues.length > 0) {
          validationList.innerHTML = issues.map(issue => `<li>${issue}</li>`).join('');
          validationPanel.classList.remove('hidden');
        } else {
          validationPanel.classList.add('hidden');
        }
      }
      
      // Validate on input/change
      document.querySelectorAll('.validation-field').forEach(field => {
        field.addEventListener('input', validateForm);
        field.addEventListener('change', validateForm);
      });
      
      document.querySelectorAll('select[name="subjects[]"], select[name="grade_levels[]"], input[name="availability[]"]').forEach(field => {
        field.addEventListener('change', validateForm);
      });
      
      // Don't validate on page load - only on user interaction or submit
    }

    // run validation immediately
    try { initValidation(); } catch(e){ console.error('initValidation failed', e); }
    
    // Initialize multi-select dropdowns (subjects, grade levels, availability)
    function initMultiSelects() {
      // Subject expertise dropdown
      const subjectsControl = document.getElementById('subjects-control');
      const subjectsDropdown = subjectsControl?.parentElement.querySelector('.ms-dropdown');
      const subjectsToggle = document.getElementById('ms-toggle');
      const subjectsNative = document.getElementById('subjects-native');
      const subjectsTokens = document.getElementById('ms-tokens');
      const subjectsSearch = subjectsControl?.parentElement.querySelector('.ms-search input');
      
      if (subjectsToggle && subjectsDropdown) {
        subjectsToggle.addEventListener('click', (e) => {
          e.preventDefault();
          subjectsDropdown.style.display = subjectsDropdown.style.display === 'none' ? 'block' : 'none';
          if (subjectsSearch) subjectsSearch.focus();
        });
      }
      
      // Grade levels dropdown
      const gradeControl = document.getElementById('grade-control');
      const gradeDropdown = gradeControl?.parentElement.querySelector('.ms-dropdown');
      const gradeToggle = document.getElementById('grade-toggle');
      const gradeNative = document.getElementById('grade-levels-native');
      const gradeTokens = document.getElementById('grade-tokens');
      const gradeSearch = gradeControl?.parentElement.querySelector('.ms-search input');
      
      if (gradeToggle && gradeDropdown) {
        gradeToggle.addEventListener('click', (e) => {
          e.preventDefault();
          gradeDropdown.style.display = gradeDropdown.style.display === 'none' ? 'block' : 'none';
          if (gradeSearch) gradeSearch.focus();
        });
      }
      
      // Availability periods dropdown
      const availControl = document.getElementById('avail-control');
      const availDropdown = availControl?.parentElement.querySelector('.ms-dropdown');
      const availToggle = document.getElementById('avail-toggle');
      const availNative = document.getElementById('availability-native');
      
      if (availToggle && availDropdown) {
        availToggle.addEventListener('click', (e) => {
          e.preventDefault();
          availDropdown.style.display = availDropdown.style.display === 'none' ? 'block' : 'none';
        });
      }
      
      // Update tokens display for subjects
      function updateSubjectsDisplay() {
        if (!subjectsTokens || !subjectsNative) return;
        const selected = Array.from(subjectsNative.options).filter(o => o.selected);
        subjectsTokens.innerHTML = selected.map(o => `
          <div class="ms-token">
            ${o.text}
            <button type="button" data-value="${o.value}" class="remove-token">✕</button>
          </div>
        `).join('');
      }
      
      // Update tokens display for grades
      function updateGradesDisplay() {
        if (!gradeTokens || !gradeNative) return;
        const selected = Array.from(gradeNative.options).filter(o => o.selected);
        gradeTokens.innerHTML = selected.map(o => `
          <div class="ms-token">
            ${o.text}
            <button type="button" data-value="${o.value}" class="remove-token">✕</button>
          </div>
        `).join('');
      }
      
      // Subject checkbox changes
      if (subjectsControl) {
        subjectsControl.addEventListener('change', (e) => {
          if (e.target.classList.contains('ms-checkbox')) {
            const id = e.target.dataset.id;
            const option = Array.from(subjectsNative.options).find(o => o.value === id);
            if (option) option.selected = e.target.checked;
            updateSubjectsDisplay();
          }
        });
      }
      
      // Grade checkbox changes
      if (gradeControl) {
        gradeControl.addEventListener('change', (e) => {
          if (e.target.classList.contains('ms-checkbox')) {
            const id = e.target.dataset.id;
            const option = Array.from(gradeNative.options).find(o => o.value === id);
            if (option) option.selected = e.target.checked;
            updateGradesDisplay();
          } else if (e.target.id === 'grade-select-all') {
            Array.from(gradeNative.options).forEach(o => o.selected = e.target.checked);
            gradeControl.querySelectorAll('.ms-checkbox').forEach(cb => cb.checked = e.target.checked);
            updateGradesDisplay();
          }
        });
      }
      
      // Close dropdowns on outside click
      document.addEventListener('click', (e) => {
        if (subjectsControl && !subjectsControl.parentElement.contains(e.target) && subjectsDropdown) {
          subjectsDropdown.style.display = 'none';
        }
        if (gradeControl && !gradeControl.parentElement.contains(e.target) && gradeDropdown) {
          gradeDropdown.style.display = 'none';
        }
        if (availControl && !availControl.parentElement.contains(e.target) && availDropdown) {
          availDropdown.style.display = 'none';
        }
      });
      
      // Search filter for subjects
      if (subjectsSearch && subjectsControl) {
        subjectsSearch.addEventListener('input', (e) => {
          const term = e.target.value.toLowerCase();
          subjectsControl.parentElement.querySelectorAll('.ms-item').forEach(item => {
            const text = item.textContent.toLowerCase();
            item.style.display = text.includes(term) ? '' : 'none';
          });
        });
      }
      
      // Search filter for grades
      if (gradeSearch && gradeControl) {
        gradeSearch.addEventListener('input', (e) => {
          const term = e.target.value.toLowerCase();
          gradeControl.parentElement.querySelectorAll('.ms-item').forEach(item => {
            const text = item.textContent.toLowerCase();
            item.style.display = text.includes(term) ? '' : 'none';
          });
        });
      }
      
      // Initial display
      updateSubjectsDisplay();
      updateGradesDisplay();
    }
    
    try { initMultiSelects(); } catch(e){ console.error('initMultiSelects failed', e); }
    
    // run immediately
    try { initTeacherFormEnforcements(); } catch(e){ console.error('initTeacherFormEnforcements failed', e); }
    // also run on DOMContentLoaded if not yet executed
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', function() { 
      initTeacherFormEnforcements(); 
      initValidation();
    });
  })();
</script>

@if($isEdit)
<script>
  (function(){
    const del = document.getElementById('btn-delete-teacher');
    if (!del) return;
    del.addEventListener('click', async function(){
      const ok = (typeof confirmDialog === 'function') ? await confirmDialog('Remove this teacher? This action cannot be undone.') : window.confirm('Remove this teacher? This action cannot be undone.');
      if (!ok) return;
      const id = this.dataset.id;
      if (!id) return;
      try{
        const hooks = document.getElementById('teachers-urls');
        const csrf = hooks ? hooks.dataset.csrf : '';
        const base = hooks ? hooks.dataset.baseUrl : '/admin/teachers';
        const res = await fetch(base + '/' + id, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': csrf, 'X-Requested-With': 'XMLHttpRequest' } });
        if (!res.ok) throw new Error('Delete failed');
        const li = document.querySelector(`#teacher-list [data-id='${id}']`);
        if (li) li.remove();
        const pane = document.getElementById('detail-pane');
        if (pane && typeof loadFragment === 'function') {
          const html = await loadFragment(TEACHERS_FRAGMENT_URL);
          pane.innerHTML = html;
        } else if (pane) {
          pane.innerHTML = 'Teacher removed.';
        }
      } catch(e){ console.error(e); if(typeof showToast === 'function') showToast('Failed to delete teacher','error'); else alert('Failed to delete teacher'); }
    });
  })();
</script>
@endif
