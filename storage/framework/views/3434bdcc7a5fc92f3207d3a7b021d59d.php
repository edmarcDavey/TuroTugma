

<?php $__env->startSection('title','Schedule Maker - Settings'); ?>
<?php $__env->startSection('heading','Schedule Maker - Settings'); ?>

<?php $__env->startSection('content'); ?>
<?php ($allDays = [1=>'Mon',2=>'Tue',3=>'Wed',4=>'Thu',5=>'Fri',6=>'Sat',7=>'Sun']); ?>

<div class="min-h-screen bg-white">
  <div class="max-w-full mx-auto">

    <!-- Tabs Navigation -->
    <div class="border-b bg-slate-50 flex">
      <button data-tab="general" class="settings-tab flex-1 px-6 py-4 font-semibold border-b-2 border-blue-600 text-blue-600 hover:bg-blue-50">
        ⚙️ General Rules
      </button>
      <button data-tab="jh" class="settings-tab flex-1 px-6 py-4 font-semibold text-slate-500 hover:text-slate-700 hover:bg-slate-100">
        📘 Junior High
      </button>
      <button data-tab="sh" class="settings-tab flex-1 px-6 py-4 font-semibold text-slate-500 hover:text-slate-700 hover:bg-slate-100">
        📗 Senior High
      </button>
      <button data-tab="qualifications" class="settings-tab flex-1 px-6 py-4 font-semibold text-slate-500 hover:text-slate-700 hover:bg-slate-100">
        👥 Qualifications
      </button>
    </div>

    <!-- Tab Content: General Rules -->
    <div id="tab-general" class="settings-tab-content p-6">
      <h3 class="text-lg font-bold text-slate-900 mb-6">⚙️ General Scheduling Rules</h3>

      <!-- Faculty Restrictions Form -->
      <div class="mb-8 border rounded-lg p-6 bg-white shadow">
        <form method="POST" action="<?php echo e(route('admin.schedule-maker.settings.save')); ?>">
          <?php echo csrf_field(); ?>
          <input type="hidden" name="level" value="general" />
          <input type="hidden" name="section" value="faculty_restrictions" />

          <h4 class="text-md font-semibold mb-3 text-slate-800">👥 Faculty Period Restrictions</h4>
          <p class="text-sm text-slate-600 mb-4">Prevent specific faculty roles from teaching during certain periods.</p>
          
          <div id="restrictions-container" class="space-y-4 mb-4">
            <!-- Dynamic restrictions will be added here -->
          </div>

          <button type="button" id="add-restriction" class="px-4 py-2 text-sm bg-slate-600 text-white rounded hover:bg-slate-700 mb-4">
            + Add Faculty Restriction
          </button>

          <div class="border-t pt-4 flex justify-end">
            <button type="submit" class="px-5 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 font-medium">
              Save Faculty Restrictions
            </button>
          </div>
        </form>
      </div>

      <!-- Subject Period Constraints Form -->
      <div class="mb-8 border rounded-lg p-6 bg-white shadow">
        <form method="POST" action="<?php echo e(route('admin.schedule-maker.settings.save')); ?>">
          <?php echo csrf_field(); ?>
          <input type="hidden" name="level" value="general" />
          <input type="hidden" name="section" value="subject_constraints" />

          <h4 class="text-md font-semibold mb-3 text-slate-800">📚 Subject Period Constraints</h4>
          <p class="text-sm text-slate-600 mb-4">Restrict specific subjects from being scheduled in certain periods.</p>
          
          <div id="subject-constraints-container" class="space-y-4 mb-4">
            <!-- Dynamic constraints will be added here -->
          </div>

          <button type="button" id="add-subject-constraint" class="px-4 py-2 text-sm bg-slate-600 text-white rounded hover:bg-slate-700 mb-4">
            + Add Subject Constraint
          </button>

          <div class="border-t pt-4 flex justify-end">
            <button type="submit" class="px-5 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 font-medium">
              Save Subject Constraints
            </button>
          </div>
        </form>
      </div>

      <!-- Load Balancing & Optimization Form -->
      <div class="mb-8 border rounded-lg p-6 bg-white shadow">
        <form method="POST" action="<?php echo e(route('admin.schedule-maker.settings.save')); ?>">
          <?php echo csrf_field(); ?>
          <input type="hidden" name="level" value="general" />
          <input type="hidden" name="section" value="optimization" />

          <h4 class="text-md font-semibold mb-3 text-slate-800">⚖️ Load Balancing & Optimization</h4>
          <p class="text-sm text-slate-600 mb-4">Configure workload distribution and optimization rules.</p>
          
          <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-2">Max Consecutive Periods</label>
              <input type="number" name="optimization_settings[max_consecutive_periods]" 
                     value="<?php echo e($generalConfig->optimization_settings['max_consecutive_periods'] ?? 4); ?>" 
                     min="1" max="9" class="input w-full" />
              <p class="text-xs text-slate-500 mt-1">Maximum continuous teaching periods without break</p>
            </div>

            <div>
              <label class="block text-sm font-medium text-slate-700 mb-2">Max Teaching Days/Week</label>
              <input type="number" name="optimization_settings[max_teaching_days_per_week]" 
                     value="<?php echo e($generalConfig->optimization_settings['max_teaching_days_per_week'] ?? 6); ?>" 
                     min="1" max="7" class="input w-full" />
              <p class="text-xs text-slate-500 mt-1">Maximum days a teacher should work</p>
            </div>

            <div>
              <label class="block text-sm font-medium text-slate-700 mb-2">Load Distribution Threshold</label>
              <input type="number" name="optimization_settings[load_distribution_threshold]" 
                     value="<?php echo e($generalConfig->optimization_settings['load_distribution_threshold'] ?? 3.5); ?>" 
                     min="0.5" max="10" step="0.5" class="input w-full" />
              <p class="text-xs text-slate-500 mt-1">Acceptable workload variation between teachers</p>
            </div>
          </div>

          <div class="space-y-3 mb-6">
            <label class="flex items-center gap-3">
              <input type="checkbox" name="optimization_settings[balance_workload]" value="1" 
                     <?php echo e(($generalConfig->optimization_settings['balance_workload'] ?? true) ? 'checked' : ''); ?> 
                     class="rounded" />
              <span class="text-sm text-slate-700">Automatically balance teacher workload</span>
            </label>

            <label class="flex items-center gap-3">
              <input type="checkbox" name="optimization_settings[minimize_gaps]" value="1" 
                     <?php echo e(($generalConfig->optimization_settings['minimize_gaps'] ?? true) ? 'checked' : ''); ?> 
                     class="rounded" />
              <span class="text-sm text-slate-700">Minimize gaps in teacher schedules</span>
            </label>

            <label class="flex items-center gap-3">
              <input type="checkbox" name="optimization_settings[respect_preferences]" value="1" 
                     <?php echo e(($generalConfig->optimization_settings['respect_preferences'] ?? false) ? 'checked' : ''); ?> 
                     class="rounded" />
              <span class="text-sm text-slate-700">Respect teacher period preferences when possible</span>
            </label>
          </div>

          <div class="border-t pt-4 flex justify-end">
            <button type="submit" class="px-5 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 font-medium">
              Save Optimization Settings
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Tab Content: Junior High -->
    <div id="tab-jh" class="settings-tab-content hidden p-6">
      <h3 class="text-lg font-bold text-slate-900 mb-6">📘 Junior High Configuration</h3>

      <!-- School Days & Sessions -->
      <div class="mb-8 border rounded-lg p-6 bg-white shadow">
        <form method="POST" action="<?php echo e(route('admin.schedule-maker.settings.save')); ?>">
          <?php echo csrf_field(); ?>
          <input type="hidden" name="level" value="junior_high" />
          <input type="hidden" name="section" value="calendar" />

          <h4 class="text-md font-semibold mb-3 text-slate-800">📅 School Days</h4>
          <p class="text-sm text-slate-600 mb-4">Select active school days for Junior High.</p>
          
          <div class="flex gap-3 mb-6">
            <?php $__currentLoopData = $allDays; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <label class="flex flex-col items-center gap-2 cursor-pointer">
                <input type="checkbox" name="days[<?php echo e($d); ?>][is_active]" value="1" class="peer hidden" />
                <div class="w-16 h-16 rounded-full border-2 flex items-center justify-center font-medium transition-all peer-checked:bg-blue-500 peer-checked:text-white peer-checked:border-blue-600 hover:border-blue-400 bg-white border-slate-300">
                  <?php echo e($label); ?>

                </div>
              </label>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </div>

          <h4 class="text-md font-semibold mb-3 text-slate-800 mt-6">⏱️ Period Structure</h4>
          <div class="grid grid-cols-2 gap-6 mb-6">
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-2">Regular Session Periods</label>
              <input type="number" name="regular_period_count" value="<?php echo e($jh->regular_period_count ?? 8); ?>" min="1" max="12" class="input w-full" />
              <p class="text-xs text-slate-500 mt-1">Number of periods on regular days</p>
            </div>
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-2">Shortened Session Periods</label>
              <input type="number" name="shortened_period_count" value="<?php echo e($jh->shortened_period_count ?? 6); ?>" min="1" max="12" class="input w-full" />
              <p class="text-xs text-slate-500 mt-1">Number of periods on shortened days</p>
            </div>
          </div>

          <div class="border-t pt-4 flex justify-end">
            <button type="submit" class="px-5 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 font-medium">
              Save JH Calendar
            </button>
          </div>
        </form>
      </div>

      <!-- Workload Limits -->
      <div class="mb-8 border rounded-lg p-6 bg-white shadow">
        <form method="POST" action="<?php echo e(route('admin.schedule-maker.settings.save')); ?>">
          <?php echo csrf_field(); ?>
          <input type="hidden" name="level" value="junior_high" />
          <input type="hidden" name="section" value="workload" />

          <h4 class="text-md font-semibold mb-3 text-slate-800">📊 Teacher Workload Limits</h4>
          <p class="text-sm text-slate-600 mb-4">Set min/max constraints for JH teachers.</p>
          
          <div class="grid grid-cols-3 gap-6">
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-2">Max Subjects per Teacher</label>
              <input type="number" name="max_subjects" value="<?php echo e($jh->max_subjects ?? 3); ?>" min="1" max="8" class="input w-full" />
            </div>
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-2">Max Sections per Teacher</label>
              <input type="number" name="max_sections" value="<?php echo e($jh->max_sections ?? 6); ?>" min="1" max="15" class="input w-full" />
            </div>
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-2">Max Hours per Week</label>
              <input type="number" name="max_hours" value="<?php echo e($jh->max_hours ?? 30); ?>" min="1" max="60" class="input w-full" />
            </div>
          </div>

          <div class="border-t pt-4 flex justify-end mt-6">
            <button type="submit" class="px-5 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 font-medium">
              Save JH Workload
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Tab Content: Senior High -->
    <div id="tab-sh" class="settings-tab-content hidden p-6">
      <h3 class="text-lg font-bold text-slate-900 mb-6">📗 Senior High Configuration</h3>

      <!-- School Days & Sessions -->
      <div class="mb-8 border rounded-lg p-6 bg-white shadow">
        <form method="POST" action="<?php echo e(route('admin.schedule-maker.settings.save')); ?>">
          <?php echo csrf_field(); ?>
          <input type="hidden" name="level" value="senior_high" />
          <input type="hidden" name="section" value="calendar" />

          <h4 class="text-md font-semibold mb-3 text-slate-800">📅 School Days</h4>
          <p class="text-sm text-slate-600 mb-4">Select active school days for Senior High.</p>
          
          <div class="flex gap-3 mb-6">
            <?php $__currentLoopData = $allDays; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <label class="flex flex-col items-center gap-2 cursor-pointer">
                <input type="checkbox" name="days[<?php echo e($d); ?>][is_active]" value="1" class="peer hidden" />
                <div class="w-16 h-16 rounded-full border-2 flex items-center justify-center font-medium transition-all peer-checked:bg-green-500 peer-checked:text-white peer-checked:border-green-600 hover:border-green-400 bg-white border-slate-300">
                  <?php echo e($label); ?>

                </div>
              </label>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </div>

          <h4 class="text-md font-semibold mb-3 text-slate-800 mt-6">⏱️ Period Structure</h4>
          <div class="grid grid-cols-2 gap-6 mb-6">
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-2">Regular Session Periods</label>
              <input type="number" name="regular_period_count" value="<?php echo e($sh->regular_period_count ?? 6); ?>" min="1" max="12" class="input w-full" />
              <p class="text-xs text-slate-500 mt-1">Number of periods on regular days</p>
            </div>
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-2">Shortened Session Periods</label>
              <input type="number" name="shortened_period_count" value="<?php echo e($sh->shortened_period_count ?? 4); ?>" min="1" max="12" class="input w-full" />
              <p class="text-xs text-slate-500 mt-1">Number of periods on shortened days</p>
            </div>
          </div>

          <div class="border-t pt-4 flex justify-end">
            <button type="submit" class="px-5 py-2 bg-green-600 text-white rounded hover:bg-green-700 font-medium">
              Save SH Calendar
            </button>
          </div>
        </form>
      </div>

      <!-- Workload Limits -->
      <div class="mb-8 border rounded-lg p-6 bg-white shadow">
        <form method="POST" action="<?php echo e(route('admin.schedule-maker.settings.save')); ?>">
          <?php echo csrf_field(); ?>
          <input type="hidden" name="level" value="senior_high" />
          <input type="hidden" name="section" value="workload" />

          <h4 class="text-md font-semibold mb-3 text-slate-800">📊 Teacher Workload Limits</h4>
          <p class="text-sm text-slate-600 mb-4">Set min/max constraints for SH teachers.</p>
          
          <div class="grid grid-cols-3 gap-6">
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-2">Max Subjects per Teacher</label>
              <input type="number" name="max_subjects" value="<?php echo e($sh->max_subjects ?? 2); ?>" min="1" max="8" class="input w-full" />
            </div>
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-2">Max Sections per Teacher</label>
              <input type="number" name="max_sections" value="<?php echo e($sh->max_sections ?? 4); ?>" min="1" max="15" class="input w-full" />
            </div>
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-2">Max Hours per Week</label>
              <input type="number" name="max_hours" value="<?php echo e($sh->max_hours ?? 24); ?>" min="1" max="60" class="input w-full" />
            </div>
          </div>

          <div class="border-t pt-4 flex justify-end mt-6">
            <button type="submit" class="px-5 py-2 bg-green-600 text-white rounded hover:bg-green-700 font-medium">
              Save SH Workload
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Tab Content: Qualifications -->
    <div id="tab-qualifications" class="settings-tab-content hidden p-6">
      <h3 class="text-lg font-bold text-slate-900 mb-6">👥 Teacher Qualifications</h3>

      <div class="border rounded-lg p-6 bg-white shadow">
        <form method="POST" action="<?php echo e(route('admin.schedule-maker.settings.save')); ?>" class="space-y-6">
          <?php echo csrf_field(); ?>
          <input type="hidden" name="level" value="general" />
          <input type="hidden" name="section" value="teacher_qualifications" />

          <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
            <div>
              <h4 class="text-md font-semibold text-slate-800">Assign per teacher</h4>
              <p class="text-sm text-slate-600">Pick subjects and sections for each teacher. Use Ctrl/Cmd+click to multi-select.</p>
            </div>
            <div class="flex items-center gap-2 text-xs text-slate-500">
              <span class="font-semibold text-slate-700">Legend:</span>
              <span class="px-2 py-1 border rounded bg-slate-50">Subjects</span>
              <span class="px-2 py-1 border rounded bg-slate-50">Sections</span>
            </div>
          </div>

          <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-slate-800">
              <thead>
                <tr class="border-b bg-slate-50 text-left">
                  <th class="py-3 px-3 w-48">Teacher</th>
                  <th class="py-3 px-3">Subjects (multi-select)</th>
                  <th class="py-3 px-3">Sections (multi-select)</th>
                </tr>
              </thead>
              <tbody class="divide-y">
                <?php $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <tr class="align-top">
                    <td class="py-3 px-3 font-medium"><?php echo e($teacher->name); ?></td>
                    <td class="py-3 px-3">
                      <select name="teacher_subjects[<?php echo e($teacher->id); ?>][]" class="input w-full" multiple size="5">
                        <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                          <option value="<?php echo e($subject->id); ?>"><?php echo e($subject->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                      </select>
                    </td>
                    <td class="py-3 px-3">
                      <select name="teacher_sections[<?php echo e($teacher->id); ?>][]" class="input w-full" multiple size="5">
                        <optgroup label="Junior High">
                          <?php $__currentLoopData = $sections_jhs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($section->id); ?>"><?php echo e($section->name); ?></option>
                          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </optgroup>
                        <optgroup label="Senior High">
                          <?php $__currentLoopData = $sections_shs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($section->id); ?>"><?php echo e($section->name); ?></option>
                          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </optgroup>
                      </select>
                    </td>
                  </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </tbody>
            </table>
          </div>

          <div class="border-t pt-4 flex justify-end">
            <button type="submit" class="px-5 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 font-medium">
              Save Qualifications
            </button>
          </div>
        </form>
      </div>
    </div>

  </div>
</div>

<script>
  // Tab switching functionality
  document.addEventListener('DOMContentLoaded', function() {
    const tabs = document.querySelectorAll('.settings-tab');
    const tabContents = document.querySelectorAll('.settings-tab-content');

    tabs.forEach(tab => {
      tab.addEventListener('click', function() {
        const targetTab = this.getAttribute('data-tab');

        // Update tab styling
        tabs.forEach(t => {
          t.classList.remove('border-blue-600', 'text-blue-600');
          t.classList.add('text-slate-500');
        });
        this.classList.add('border-blue-600', 'text-blue-600');
        this.classList.remove('text-slate-500');

        // Show/hide content
        tabContents.forEach(content => {
          content.classList.add('hidden');
        });
        document.getElementById(`tab-${targetTab}`).classList.remove('hidden');
      });
    });

    // Period times for visual selectors
    const periodTimes = ['7:30', '8:30', '9:30', '10:30', '11:30', '1:00', '2:00', '3:00', '4:00'];
    
    // Faculty restriction row functionality
    let restrictionIndex = 0;
    
    function createRestrictionRow() {
      const row = document.createElement('div');
      row.className = 'restriction-row border rounded-lg p-4 bg-slate-50';
      row.innerHTML = `
        <div class="mb-4">
          <label class="block text-sm font-medium text-slate-700 mb-2">Faculty Role/Assignment</label>
          <input type="text" 
                 name="restrictions[${restrictionIndex}][type]" 
                 placeholder="e.g., Guidance Counselor, Librarian..." 
                 class="input w-full" 
                 required />
        </div>

        <div>
          <label class="block text-sm font-medium text-slate-700 mb-3">
            Restricted Periods
            <span class="text-xs font-normal text-slate-500 ml-2">(Click periods to mark as restricted)</span>
          </label>
          <div class="grid grid-cols-9 gap-2">
            ${Array.from({length: 9}, (_, i) => i + 1).map(p => `
              <label class="period-selector cursor-pointer">
                <input type="checkbox" name="restrictions[${restrictionIndex}][periods][]" value="${p}" class="peer hidden" />
                <div class="h-16 border-2 rounded-lg flex flex-col items-center justify-center transition-all peer-checked:bg-red-100 peer-checked:border-red-500 peer-checked:text-red-800 hover:border-blue-400 bg-white border-slate-300">
                  <div class="font-bold text-lg">P${p}</div>
                  <div class="text-xs text-slate-500 peer-checked:text-red-600">${periodTimes[p-1]}</div>
                </div>
              </label>
            `).join('')}
          </div>
          <div class="mt-2 text-xs text-slate-500">
            <span class="inline-block w-3 h-3 bg-white border-2 border-slate-300 rounded mr-1"></span> Available
            <span class="inline-block w-3 h-3 bg-red-100 border-2 border-red-500 rounded ml-3 mr-1"></span> Restricted
          </div>
        </div>

        <button type="button" class="remove-restriction mt-4 text-red-600 hover:text-red-800 text-sm font-medium" onclick="this.closest('.restriction-row').remove()">
          ✕ Remove Restriction
        </button>
      `;
      restrictionIndex++;
      return row;
    }

    document.getElementById('add-restriction').addEventListener('click', function() {
      document.getElementById('restrictions-container').appendChild(createRestrictionRow());
    });

    // Subject constraint row functionality
    let constraintIndex = 0;
    const subjectOptions = <?php echo json_encode($subjects, 15, 512) ?>;
    
    function createConstraintRow() {
      const row = document.createElement('div');
      row.className = 'constraint-row border rounded-lg p-4 bg-slate-50';
      row.innerHTML = `
        <div class="mb-4">
          <label class="block text-sm font-medium text-slate-700 mb-2">Subject</label>
          <select name="subject_constraints[${constraintIndex}][subject_id]" class="input w-full" required>
            <option value="">-- Select Subject --</option>
            ${subjectOptions.map(s => `<option value="${s.id}">${s.name}</option>`).join('')}
          </select>
        </div>

        <div>
          <label class="block text-sm font-medium text-slate-700 mb-3">
            Restricted Periods
            <span class="text-xs font-normal text-slate-500 ml-2">(Click periods to mark as restricted)</span>
          </label>
          <div class="grid grid-cols-9 gap-2">
            ${Array.from({length: 9}, (_, i) => i + 1).map(p => `
              <label class="period-selector cursor-pointer">
                <input type="checkbox" name="subject_constraints[${constraintIndex}][periods][]" value="${p}" class="peer hidden" />
                <div class="h-16 border-2 rounded-lg flex flex-col items-center justify-center transition-all peer-checked:bg-red-100 peer-checked:border-red-500 peer-checked:text-red-800 hover:border-blue-400 bg-white border-slate-300">
                  <div class="font-bold text-lg">P${p}</div>
                  <div class="text-xs text-slate-500 peer-checked:text-red-600">${periodTimes[p-1]}</div>
                </div>
              </label>
            `).join('')}
          </div>
          <div class="mt-2 text-xs text-slate-500">
            <span class="inline-block w-3 h-3 bg-white border-2 border-slate-300 rounded mr-1"></span> Available
            <span class="inline-block w-3 h-3 bg-red-100 border-2 border-red-500 rounded ml-3 mr-1"></span> Restricted
          </div>
        </div>

        <button type="button" class="remove-constraint mt-4 text-red-600 hover:text-red-800 text-sm font-medium" onclick="this.closest('.constraint-row').remove()">
          ✕ Remove Constraint
        </button>
      `;
      constraintIndex++;
      return row;
    }

    document.getElementById('add-subject-constraint').addEventListener('click', function() {
      document.getElementById('subject-constraints-container').appendChild(createConstraintRow());
    });
  });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\TuroTugma\resources\views/admin/schedule-maker/settings.blade.php ENDPATH**/ ?>