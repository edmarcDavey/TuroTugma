<?php
  $isEdit = isset($teacher) && $teacher;
  $action = $isEdit ? route('admin.teachers.update', $teacher) : route('admin.teachers.store');
  // ensure $subjects is available even if the parent view forgot to pass it (fallback to DB)
  $subjects = isset($subjects) ? $subjects : \App\Models\Subject::orderBy('name')->get();
  $selectedSubjects = old('subjects', $isEdit && isset($teacher) && isset($teacher->subjects) ? $teacher->subjects->pluck('id')->toArray() : []);
  // ensure $gradeLevels is available (fallback)
  $gradeLevels = isset($gradeLevels) ? $gradeLevels : \App\Models\GradeLevel::orderBy('id')->get();
  $selectedGrades = old('grade_levels', $isEdit && isset($teacher) && isset($teacher->gradeLevels) ? $teacher->gradeLevels->pluck('id')->toArray() : []);
  $sections = isset($sections) ? $sections : \App\Models\Section::orderBy('name')->get();
?>

<form id="teacher-form" method="POST" action="<?php echo e($action); ?>">
  <?php echo csrf_field(); ?>
  <?php if($isEdit): ?> <?php echo method_field('PUT'); ?> <?php endif; ?>

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
  <input id="name" name="name" required value="<?php echo e(old('name', $teacher->name ?? '')); ?>" class="mt-1 block w-full border border-gray-300 rounded-md p-2 h-11 bg-white box-border validation-field" />
      </div>

    <div class="md:col-span-1">
    <label for="sex" class="block text-sm font-medium">Sex</label>
  <select id="sex" name="sex" class="mt-1 block w-full border border-gray-300 rounded-md p-2 h-11 bg-white box-border" title="Sex">
          <option value="">-- select --</option>
          <option value="male" <?php if(old('sex', $teacher->sex ?? '') === 'male'): ?> selected <?php endif; ?>>Male</option>
          <option value="female" <?php if(old('sex', $teacher->sex ?? '') === 'female'): ?> selected <?php endif; ?>>Female</option>
          <option value="other" <?php if(old('sex', $teacher->sex ?? '') === 'other'): ?> selected <?php endif; ?>>Other</option>
        </select>
      </div>

  <div class="md:col-span-1">
    <label for="staff_id" class="block text-sm font-medium">Employee ID <span class="text-red-600">*</span></label>
  <input id="staff_id" name="staff_id" required value="<?php echo e(old('staff_id', $teacher->staff_id ?? '')); ?>" class="mt-1 block w-full border border-gray-300 rounded-md p-2 h-11 bg-white box-border validation-field" />
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
    <div>
    <label for="designation" class="block text-sm font-medium">Designation / Position <span class="text-red-600">*</span></label>
  <select id="designation" name="designation" required class="mt-1 block w-full border border-gray-300 rounded-md p-2 h-11 bg-white box-border validation-field">
        <option value="">-- select --</option>
        <?php $__currentLoopData = config('teachers.designations', []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <option value="<?php echo e($d); ?>" <?php if(old('designation', $teacher->designation ?? '') === $d): ?> selected <?php endif; ?>><?php echo e($d); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </select>
      </div>

  <div>
    <label for="status_of_appointment" class="block text-sm font-medium">Status of Appointment <span class="text-red-600">*</span></label>
  <select id="status_of_appointment" name="status_of_appointment" required class="mt-1 block w-full border border-gray-300 rounded-md p-2 h-11 bg-white box-border">
        <option value="">-- select --</option>
        <?php $__currentLoopData = config('teachers.statuses', []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <option value="<?php echo e($s); ?>" <?php if(old('status_of_appointment', $teacher->status_of_appointment ?? '') === $s): ?> selected <?php endif; ?>><?php echo e($s); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </select>
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
  <div>
    <label for="email" class="block text-sm font-medium">Email <span class="text-red-600">*</span></label>
    <input id="email" name="email" required value="<?php echo e(old('email', $teacher->email ?? '')); ?>" class="mt-1 block w-full border border-gray-300 rounded-md p-2 h-11 bg-white box-border" />
  </div>

  <div>
    <label for="phone" class="block text-sm font-medium">Phone / Contact <span class="text-red-600">*</span></label>
    <input id="phone" name="phone" required value="<?php echo e(old('phone', $teacher->phone ?? '')); ?>" class="mt-1 block w-full border border-gray-300 rounded-md p-2 h-11 bg-white box-border" />
  </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
  <div>
    <label for="course_degree" class="block text-sm font-medium">Course / Degree</label>
  <input id="course_degree" name="course_degree" value="<?php echo e(old('course_degree', $teacher->course_degree ?? '')); ?>" class="mt-1 block w-full border border-gray-300 rounded-md p-2 h-11 bg-white box-border" />
      </div>

  <div>
    <label for="course_major" class="block text-sm font-medium">Course Major</label>
  <input id="course_major" name="course_major" value="<?php echo e(old('course_major', $teacher->course_major ?? '')); ?>" class="mt-1 block w-full border border-gray-300 rounded-md p-2 h-11 bg-white box-border" />
      </div>

  <div>
    <label for="course_minor" class="block text-sm font-medium">Course Minor</label>
  <input id="course_minor" name="course_minor" value="<?php echo e(old('course_minor', $teacher->course_minor ?? '')); ?>" class="mt-1 block w-full border border-gray-300 rounded-md p-2 h-11 bg-white box-border" />
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
      <div>
    <label for="ancillary_assignments" class="block text-sm font-medium">Ancillary / Special Assignments</label>
  <textarea id="ancillary_assignments" name="ancillary_assignments" placeholder="e.g. Class sponsor, dept. coordinator" class="mt-1 block w-full border border-gray-300 rounded-md p-2 h-11 bg-white resize-none box-border" rows="2"><?php echo e(old('ancillary_assignments', $teacher->ancillary_assignments ?? '')); ?></textarea>
        <p class="text-xs text-slate-500 mt-1">Keep it short — a brief note about additional duties (optional).</p>
      </div>

      <div class="bg-blue-50 border-2 border-blue-200 rounded-md p-3">
    <label for="advisory" class="block text-sm font-medium text-blue-900">📌 Class Advisory Assignment</label>
    <select id="advisory" name="advisory" class="mt-2 block w-full border border-blue-300 rounded-md p-2 h-11 bg-white box-border">
          <option value="">-- Not an Adviser --</option>
          <?php $__currentLoopData = $sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($section->id); ?>" <?php if(old('advisory', $teacher->advisory ?? '') == $section->id): ?> selected <?php endif; ?>><?php echo e($section->name); ?></option>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    <p class="text-xs text-blue-700 mt-1">👥 Assign this teacher as the class adviser for a specific section.</p>
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">      <div>
  <label for="avail-control" class="block text-sm font-medium">Unavailable Periods</label>
      <input type="hidden" id="advisory-input" name="advisory" value="<?php echo e(old('advisory', $teacher->advisory ?? '')); ?>">

      
  <select id="availability-native" name="availability[]" multiple class="ms-native-hidden" title="Unavailable periods">
        <?php
          $selAvail = old('availability', $teacher->availability ?? []);
          if (is_string($selAvail)) $selAvail = explode(',', $selAvail);
        ?>
        <?php for($i=1;$i<=9;$i++): ?>
          <?php
            $val = (string)$i;
            $label = $i.'th Period';
            if($i==1) $label='1st Period';
            if($i==2) $label='2nd Period';
            if($i==3) $label='3rd Period';
          ?>
          <option value="<?php echo e($val); ?>" <?php if(in_array($val, $selAvail)): ?> selected <?php endif; ?>><?php echo e($label); ?></option>
        <?php endfor; ?>
      </select>

      <div class="ms-container mt-1">
    <div class="ms-control" id="avail-control" tabindex="0" role="combobox" aria-expanded="false" aria-haspopup="listbox" aria-label="Unavailable Periods">
          <div id="avail-tokens" class="ms-tokens"></div>
          <button type="button" id="avail-toggle" class="ms-button" aria-label="Toggle availability dropdown">▾</button>
        </div>

        <div class="ms-dropdown" role="listbox" aria-multiselectable="true">
          <div class="ms-search"><input aria-label="Search periods" placeholder="Search..." class="w-full border rounded px-2 py-1" /></div>
          
          <div class="ms-list">
            <?php for($i=1;$i<=9;$i++): ?>
              <?php
                $val = (string)$i;
                $label = $i.'th Period';
                if($i==1) $label='1st Period';
                if($i==2) $label='2nd Period';
                if($i==3) $label='3rd Period';
              ?>
              <div class="ms-item" data-id="<?php echo e($val); ?>" role="option" aria-selected="false">
                <input type="checkbox" class="ms-checkbox" data-id="<?php echo e($val); ?>" />
                <div><?php echo e($label); ?></div>
              </div>
            <?php endfor; ?>
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

      
  <select id="subjects-native" name="subjects[]" multiple required class="ms-native-hidden" title="Subjects">
        <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <option value="<?php echo e($s->id); ?>" <?php if(in_array($s->id, $selectedSubjects)): ?> selected <?php endif; ?>><?php echo e($s->name); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </select>

      <div class="ms-container mt-1">
      <div class="ms-control" id="subjects-control" tabindex="0" role="combobox" aria-expanded="false" aria-haspopup="listbox" aria-label="Subject Assignment">
              <div id="ms-tokens" class="ms-tokens">
            
          </div>
          <button type="button" id="ms-toggle" class="ms-button" aria-label="Toggle subjects dropdown">▾</button>
        </div>

        <div class="ms-dropdown" role="listbox" aria-multiselectable="true">
            <div class="ms-search"><input aria-label="Search subjects" placeholder="Search..." class="w-full border rounded px-2 py-1" /></div>
            
            <div class="ms-list">
              <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="ms-item" data-id="<?php echo e($s->id); ?>" role="option" aria-selected="<?php echo e(in_array($s->id,$selectedSubjects) ? 'true' : 'false'); ?>">
                  <input type="checkbox" class="ms-checkbox" data-id="<?php echo e($s->id); ?>" <?php if(in_array($s->id,$selectedSubjects)): ?> checked <?php endif; ?> />
                  <div><?php echo e($s->name); ?> <?php if(!empty($s->code)): ?> <small class="text-slate-400">(<?php echo e($s->code); ?>)</small> <?php endif; ?></div>
                </div>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
          </div>
        </div>
      </div>

    <div>
  <label class="block text-sm font-medium">Grade Level Assignment <span class="text-red-600">*</span></label>

      
  <select id="grade-levels-native" name="grade_levels[]" multiple required class="ms-native-hidden" title="Grade levels">
        <?php $__currentLoopData = $gradeLevels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $g): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <option value="<?php echo e($g->id); ?>" <?php if(in_array($g->id, $selectedGrades)): ?> selected <?php endif; ?>><?php echo e($g->name); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
              <?php $__currentLoopData = $gradeLevels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $g): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="ms-item" data-id="<?php echo e($g->id); ?>" role="option" aria-selected="<?php echo e(in_array($g->id,$selectedGrades) ? 'true' : 'false'); ?>">
                  <input type="checkbox" class="ms-checkbox" data-id="<?php echo e($g->id); ?>" <?php if(in_array($g->id,$selectedGrades)): ?> checked <?php endif; ?> />
                  <div><?php echo e($g->name); ?></div>
                </div>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
          </div>
        </div>
      
      </div>

    <div class="pt-4">
      <button type="submit" class="px-4 py-2 bg-[#3b4197] text-white rounded"><?php echo e($isEdit ? 'Save' : 'Create'); ?></button>
      <a href="<?php echo e(route('admin.teachers.index')); ?>" class="ml-2 text-sm">Cancel</a>
      <?php if($isEdit): ?>
        <button type="button" id="btn-delete-teacher" data-id="<?php echo e($teacher->id); ?>" class="ml-2 px-3 py-1 bg-red-600 text-white rounded text-sm">Remove</button>
      <?php endif; ?>
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
        phone.setAttribute('inputmode', 'numeric');
        phone.setAttribute('pattern', '[0-9]*');
        // enforce numeric by filtering non-digits on input
        phone.addEventListener('input', function(){
          const pos = this.selectionStart;
          const newVal = this.value.replace(/\D+/g, '');
          if (this.value !== newVal) {
            this.value = newVal;
            try { this.setSelectionRange(pos-1, pos-1); } catch(e){}
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
        
        const phoneInput = document.getElementById('contact');
        if (!phoneInput.value.trim()) issues.push('Phone is required');
        
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
      
      // Initial validation
      validateForm();
    }

    // run validation immediately
    try { initValidation(); } catch(e){ console.error('initValidation failed', e); }
    
    // run immediately
    try { initTeacherFormEnforcements(); } catch(e){ console.error('initTeacherFormEnforcements failed', e); }
    // also run on DOMContentLoaded if not yet executed
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', function() { 
      initTeacherFormEnforcements(); 
      initValidation();
    });
  })();
</script>

<?php if($isEdit): ?>
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
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\TuroTugma\resources\views/admin/teachers/_form.blade.php ENDPATH**/ ?>