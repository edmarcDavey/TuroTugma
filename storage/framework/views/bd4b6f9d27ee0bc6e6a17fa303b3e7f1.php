<?php $__env->startSection('title','Schedule Maker - Scheduler'); ?>
<?php $__env->startSection('heading','Schedule Maker — Scheduler'); ?>

<?php $__env->startSection('content'); ?>
<style>
  /* Hide dropdown arrows */
  .subject-dropdown, .teacher-dropdown {
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    background-image: none;
  }
  
  /* Hide period 9 for regular sections */
  tr[data-is-special="0"] .schedule-cell[data-period="9"] {
    display: none;
  }
  
  /* Style for restricted teacher options */
  .teacher-dropdown option:disabled {
    background-color: #f3f4f6;
    color: #a1a1a1;
    font-style: italic;
  }
  
  /* Highlight cell if restricted teacher is selected (warning state) */
  .schedule-cell.warning {
    background-color: #fef3c7;
    border: 1px solid #fcd34d;
  }
  
  .schedule-cell.warning .teacher-dropdown {
    background-color: #fef3c7;
    border-color: #fcd34d;
  }
</style>

<div class="min-h-screen bg-white p-6">
  <div class="max-w-full mx-auto">

    
    <script>
      // @ts-nocheck
      window.schedulerData = {
        subjects: <?php echo json_encode($subjects, 15, 512) ?>,
        teachers: <?php echo json_encode($teachers, 15, 512) ?>,
        periodsRegular: <?php echo json_encode($periodsRegular, 15, 512) ?>,
        periodsShortened: <?php echo json_encode($periodsShortened, 15, 512) ?>,
        sections: <?php echo json_encode($sections, 15, 512) ?>,
        specializedSubjectCodes: ['SPA', 'SPJ'], // Specialized subjects for special sections
        // Build teacher ancillary assignments map for restriction checking
        teacherAncillaries: (function() {
          const map = {};
          const teachers = <?php echo json_encode($teachers, 15, 512) ?>;
          teachers.forEach(teacher => {
            if (teacher.ancillary_assignments) {
              try {
                // Parse JSON if it's a string
                const ancillaries = typeof teacher.ancillary_assignments === 'string' 
                  ? JSON.parse(teacher.ancillary_assignments) 
                  : teacher.ancillary_assignments;
                map[teacher.id] = Array.isArray(ancillaries) ? ancillaries : [ancillaries];
              } catch (e) {
                map[teacher.id] = [teacher.ancillary_assignments];
              }
            } else {
              map[teacher.id] = [];
            }
          });
          return map;
        })(),
        // Faculty Restrictions: will be loaded from database
        facultyRestrictions: {},
        // Subject Constraints: will be loaded from database
        subjectConstraints: {}
      };
      
      // Load restrictions from database
      fetch('<?php echo e(route("admin.schedule-maker.settings.get-faculty-restrictions")); ?>')
        .then(response => response.json())
        .then(data => {
          if (data.success && data.restrictions) {
            window.schedulerData.facultyRestrictions = data.restrictions;
            console.log('Faculty restrictions loaded from database:', data.restrictions);
          }
        })
        .catch(error => {
          console.error('Error loading restrictions:', error);
          // Fallback to localStorage
          const cached = localStorage.getItem('facultyRestrictions');
          if (cached) {
            window.schedulerData.facultyRestrictions = JSON.parse(cached);
            console.log('Using cached restrictions from localStorage');
          }
        });

      // Load subject constraints from database
      fetch('<?php echo e(route("admin.schedule-maker.settings.get-subject-constraints")); ?>')
        .then(response => response.json())
        .then(data => {
          if (data && data.success && Array.isArray(data.constraints)) {
            window.schedulerData.subjectConstraints = data.constraints;
            try { localStorage.setItem('subjectConstraints', JSON.stringify(data.constraints)); } catch(e) {}
            window.dispatchEvent(new CustomEvent('subjectConstraintsLoaded', { detail: data.constraints }));
            console.log('Subject constraints loaded:', data.constraints);
          }
        })
        .catch(error => {
          console.error('Error loading subject constraints:', error);
          // Fallback to localStorage
          try {
            const cached = JSON.parse(localStorage.getItem('subjectConstraints') || '[]');
            if (Array.isArray(cached)) {
              window.schedulerData.subjectConstraints = cached;
              window.dispatchEvent(new CustomEvent('subjectConstraintsLoaded', { detail: cached }));
              console.log('Using cached subject constraints from localStorage');
            }
          } catch(e) {}
        });
    </script>

    <!-- Main Content: Full Width Schedule -->
    <!-- Tabs Navigation -->
          <div class="border-b bg-slate-50 flex">
            <button data-tab="master" class="tab-button flex-1 px-6 py-4 font-semibold border-b-2 border-blue-600 text-blue-600 hover:bg-blue-50">
              📊 Master Schedule
            </button>
            <button data-tab="sections" class="tab-button flex-1 px-6 py-4 font-semibold text-slate-500 hover:text-slate-700 hover:bg-slate-100">
              🏫 Sections
            </button>
            <button data-tab="teachers" class="tab-button flex-1 px-6 py-4 font-semibold text-slate-500 hover:text-slate-700 hover:bg-slate-100">
              👥 Teachers
            </button>
            <button data-tab="conflicts" class="tab-button flex-1 px-6 py-4 font-semibold text-slate-500 hover:text-slate-700 hover:bg-slate-100">
              ⚠️ Conflicts
            </button>
          </div>

          <!-- Tab Content: Master Schedule -->
          <div id="tab-master" class="tab-content p-6">
            <h3 class="text-lg font-bold text-slate-900 mb-4">📊 Master Schedule - SY 2024-2025</h3>
            
            <div class="mb-4 flex flex-wrap gap-2 items-center">
              <!-- Session Type Selector -->
              <div>
                <label class="text-xs text-slate-600 block mb-1">Session Type</label>
                <select id="sessionTypeFilter" class="px-3 py-2 border border-slate-300 rounded text-sm font-semibold">
                  <option value="regular" selected>Regular Session</option>
                  <option value="shortened">Shortened Session</option>
                </select>
              </div>
              
              <!-- Level Filter -->
              <div>
                <label class="text-xs text-slate-600 block mb-1">School Level</label>
                <select id="schoolLevelFilter" class="px-3 py-2 border border-slate-300 rounded text-sm">
                  <option value="all" selected>All Levels</option>
                  <option value="jh">Junior High Only</option>
                  <option value="sh" disabled class="text-slate-400">Senior High Only (Coming Soon)</option>
                </select>
              </div>
              
              <!-- Grade Filter -->
              <div>
                <label class="text-xs text-slate-600 block mb-1">Grade Level</label>
                <select id="gradeLevelFilter" class="px-3 py-2 border border-slate-300 rounded text-sm">
                  <option value="all" selected>All Grades</option>
                  <option value="7" data-level="jh">Grade 7</option>
                  <option value="8" data-level="jh">Grade 8</option>
                  <option value="9" data-level="jh">Grade 9</option>
                  <option value="10" data-level="jh">Grade 10</option>
                  <option value="11" data-level="sh" disabled class="text-slate-400">Grade 11 (Coming Soon)</option>
                  <option value="12" data-level="sh" disabled class="text-slate-400">Grade 12 (Coming Soon)</option>
                </select>
              </div>
              
              <div class="flex-1"></div>
              
              <button class="px-4 py-2 bg-blue-600 text-white rounded text-sm font-semibold hover:bg-blue-700">
                ✨ Autofill
              </button>
              <button class="px-4 py-2 bg-slate-200 text-slate-700 rounded text-sm font-semibold hover:bg-slate-300">
                📥 Export
              </button>
            </div>

            <!-- Schedule Grid Table -->
            <div class="overflow-x-auto border rounded-lg">
              <table class="w-full text-sm">
                <thead class="bg-slate-100 border-b">
                  <tr id="period-header-row">
                    <th class="px-4 py-2 text-left font-bold text-slate-700 w-40">Section</th>
                    
                  </tr>
                </thead>
                <tbody>
                  <?php $__currentLoopData = $sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <!-- Row for <?php echo e($section->name); ?> -->
                  <tr class="border-b hover:bg-blue-50" data-section-id="<?php echo e($section->id); ?>" data-is-special="<?php echo e($section->is_special ? '1' : '0'); ?>" data-grade-level="<?php echo e($section->grade_level_id + 6); ?>" data-school-level="jh">
                    <td class="px-4 py-3 font-semibold text-slate-900"><?php echo e($section->grade_level_id + 6); ?>-<?php echo e($section->name); ?></td>
                    <?php $__currentLoopData = $periodsRegular; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $period): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <td class="px-2 py-2 text-center schedule-cell" data-section="<?php echo e($section->name); ?>" data-period="<?php echo e($period['number']); ?>">
                      <div class="p-1">
                        <select class="subject-dropdown w-full px-1 py-1 text-xs text-center border border-slate-300 rounded mb-1 text-slate-400">
                          <option value="">Select Subject</option>
                          <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($subject->id); ?>"><?php echo e($subject->name); ?></option>
                          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <select class="teacher-dropdown w-full px-1 py-1 text-xs text-center border border-slate-300 rounded text-slate-400">
                          <option value="">Assign Teacher</option>
                        </select>
                      </div>
                    </td>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                  </tr>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
              </table>
            </div>

            <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded text-sm text-blue-800">
              ℹ️ <strong>Showing 3 of 32 sections.</strong> Scroll down to see more | 
              <span class="text-green-700 font-bold">✓ 25 assigned</span> | 
              <span class="text-red-700 font-bold">❌ 8 unassigned</span> | 
              <span class="text-orange-700 font-bold">⚠️ 3 conflicts</span>
            </div>
          </div>

          <!-- Tab Content: Sections -->
          <div id="tab-sections" class="tab-content p-6 hidden">
            <h3 class="text-lg font-bold text-slate-900 mb-4">🏫 Sections View - SY 2024-2025</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
              <!-- Section Card -->
              <div class="bg-white border-2 border-slate-200 rounded-lg p-4 hover:border-blue-500 hover:shadow-lg transition">
                <div class="flex items-start justify-between mb-3">
                  <div>
                    <h4 class="font-bold text-slate-900">Grade 7-Rizal</h4>
                    <p class="text-xs text-slate-500">Junior High | 42 students</p>
                  </div>
                  <span class="bg-green-100 text-green-700 text-xs px-2 py-1 rounded font-semibold">Complete</span>
                </div>
                <div class="space-y-1 text-sm">
                  <div class="flex justify-between">
                    <span class="text-slate-600">Adviser:</span>
                    <span class="font-semibold">Mr. Cruz</span>
                  </div>
                  <div class="flex justify-between">
                    <span class="text-slate-600">Subjects:</span>
                    <span class="font-semibold">8/8</span>
                  </div>
                  <div class="flex justify-between">
                    <span class="text-slate-600">Assigned:</span>
                    <span class="text-green-600 font-bold">100%</span>
                  </div>
                </div>
                <button class="mt-3 w-full bg-blue-600 text-white py-2 rounded text-sm font-semibold hover:bg-blue-700">
                  View Schedule
                </button>
              </div>

              <!-- Section Card -->
              <div class="bg-white border-2 border-slate-200 rounded-lg p-4 hover:border-blue-500 hover:shadow-lg transition">
                <div class="flex items-start justify-between mb-3">
                  <div>
                    <h4 class="font-bold text-slate-900">Grade 7-Bonifacio</h4>
                    <p class="text-xs text-slate-500">Junior High | 40 students</p>
                  </div>
                  <span class="bg-amber-100 text-amber-700 text-xs px-2 py-1 rounded font-semibold">Incomplete</span>
                </div>
                <div class="space-y-1 text-sm">
                  <div class="flex justify-between">
                    <span class="text-slate-600">Adviser:</span>
                    <span class="font-semibold">Ms. Ramos</span>
                  </div>
                  <div class="flex justify-between">
                    <span class="text-slate-600">Subjects:</span>
                    <span class="font-semibold">8/8</span>
                  </div>
                  <div class="flex justify-between">
                    <span class="text-slate-600">Assigned:</span>
                    <span class="text-amber-600 font-bold">87%</span>
                  </div>
                </div>
                <button class="mt-3 w-full bg-blue-600 text-white py-2 rounded text-sm font-semibold hover:bg-blue-700">
                  View Schedule
                </button>
              </div>

              <!-- Section Card -->
              <div class="bg-white border-2 border-slate-200 rounded-lg p-4 hover:border-blue-500 hover:shadow-lg transition">
                <div class="flex items-start justify-between mb-3">
                  <div>
                    <h4 class="font-bold text-slate-900">Grade 11-STEM-A</h4>
                    <p class="text-xs text-slate-500">Senior High | 38 students</p>
                  </div>
                  <span class="bg-green-100 text-green-700 text-xs px-2 py-1 rounded font-semibold">Complete</span>
                </div>
                <div class="space-y-1 text-sm">
                  <div class="flex justify-between">
                    <span class="text-slate-600">Adviser:</span>
                    <span class="font-semibold">Dr. Santos</span>
                  </div>
                  <div class="flex justify-between">
                    <span class="text-slate-600">Subjects:</span>
                    <span class="font-semibold">9/9</span>
                  </div>
                  <div class="flex justify-between">
                    <span class="text-slate-600">Assigned:</span>
                    <span class="text-green-600 font-bold">100%</span>
                  </div>
                </div>
                <button class="mt-3 w-full bg-blue-600 text-white py-2 rounded text-sm font-semibold hover:bg-blue-700">
                  View Schedule
                </button>
              </div>
            </div>

            <div class="mt-4 p-3 bg-slate-50 border border-slate-200 rounded text-sm text-slate-700">
              ℹ️ <strong>Showing 3 of 32 sections.</strong> Click on a section to view detailed schedule.
            </div>
          </div>

          <!-- Tab Content: Teachers -->
          <div id="tab-teachers" class="tab-content p-6 hidden">
            <h3 class="text-lg font-bold text-slate-900 mb-4">👥 Teachers Schedule - SY 2024-2025</h3>
            
            <div class="overflow-x-auto border rounded-lg">
              <table class="w-full text-sm">
                <thead class="bg-slate-100 border-b">
                  <tr>
                    <th class="px-4 py-2 text-left font-bold text-slate-700">Teacher</th>
                    <th class="px-4 py-2 text-left font-bold text-slate-700">Designation</th>
                    <th class="px-4 py-2 text-center font-bold text-slate-700">Subjects</th>
                    <th class="px-4 py-2 text-center font-bold text-slate-700">Sections</th>
                    <th class="px-4 py-2 text-center font-bold text-slate-700">Workload</th>
                    <th class="px-4 py-2 text-center font-bold text-slate-700">Status</th>
                    <th class="px-4 py-2 text-center font-bold text-slate-700">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <tr class="border-b hover:bg-blue-50">
                    <td class="px-4 py-3">
                      <div class="font-semibold text-slate-900">Mr. Cruz, Juan</div>
                      <div class="text-xs text-slate-500">Staff ID: T-001</div>
                    </td>
                    <td class="px-4 py-3 text-slate-600">Teacher III</td>
                    <td class="px-4 py-3 text-center">Math</td>
                    <td class="px-4 py-3 text-center">5</td>
                    <td class="px-4 py-3">
                      <div class="text-xs text-slate-600 mb-1">18/24 hrs</div>
                      <div class="w-full bg-slate-200 rounded-full h-2">
                        <div class="bg-green-500 h-2 rounded-full" style="width: 75%"></div>
                      </div>
                    </td>
                    <td class="px-4 py-3 text-center">
                      <span class="bg-green-100 text-green-700 text-xs px-2 py-1 rounded font-semibold">Active</span>
                    </td>
                    <td class="px-4 py-3 text-center">
                      <button class="text-blue-600 hover:text-blue-800 text-xs font-semibold">View Schedule</button>
                    </td>
                  </tr>
                  <tr class="border-b hover:bg-blue-50">
                    <td class="px-4 py-3">
                      <div class="font-semibold text-slate-900">Ms. Santos, Maria</div>
                      <div class="text-xs text-slate-500">Staff ID: T-002</div>
                    </td>
                    <td class="px-4 py-3 text-slate-600">Teacher II</td>
                    <td class="px-4 py-3 text-center">English</td>
                    <td class="px-4 py-3 text-center">6</td>
                    <td class="px-4 py-3">
                      <div class="text-xs text-slate-600 mb-1">22/24 hrs</div>
                      <div class="w-full bg-slate-200 rounded-full h-2">
                        <div class="bg-amber-500 h-2 rounded-full" style="width: 92%"></div>
                      </div>
                    </td>
                    <td class="px-4 py-3 text-center">
                      <span class="bg-green-100 text-green-700 text-xs px-2 py-1 rounded font-semibold">Active</span>
                    </td>
                    <td class="px-4 py-3 text-center">
                      <button class="text-blue-600 hover:text-blue-800 text-xs font-semibold">View Schedule</button>
                    </td>
                  </tr>
                  <tr class="border-b hover:bg-blue-50">
                    <td class="px-4 py-3">
                      <div class="font-semibold text-slate-900">Mr. Reyes, Pedro</div>
                      <div class="text-xs text-slate-500">Staff ID: T-003</div>
                    </td>
                    <td class="px-4 py-3 text-slate-600">Teacher I</td>
                    <td class="px-4 py-3 text-center">Science</td>
                    <td class="px-4 py-3 text-center">4</td>
                    <td class="px-4 py-3">
                      <div class="text-xs text-slate-600 mb-1">15/24 hrs</div>
                      <div class="w-full bg-slate-200 rounded-full h-2">
                        <div class="bg-green-500 h-2 rounded-full" style="width: 62%"></div>
                      </div>
                    </td>
                    <td class="px-4 py-3 text-center">
                      <span class="bg-green-100 text-green-700 text-xs px-2 py-1 rounded font-semibold">Active</span>
                    </td>
                    <td class="px-4 py-3 text-center">
                      <button class="text-blue-600 hover:text-blue-800 text-xs font-semibold">View Schedule</button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <div class="mt-4 p-3 bg-slate-50 border border-slate-200 rounded text-sm text-slate-700">
              ℹ️ <strong>Showing 3 of 48 teachers.</strong> Click "View Schedule" to see individual teacher timetable.
            </div>
          </div>

          <!-- Tab Content: Conflicts -->
          <div id="tab-conflicts" class="tab-content p-6 hidden">
            <h3 class="text-lg font-bold text-slate-900 mb-4">⚠️ Schedule Conflicts - SY 2024-2025</h3>
            
            <div class="space-y-4">
              <!-- Conflict Item -->
              <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded">
                <div class="flex items-start justify-between">
                  <div class="flex-1">
                    <div class="flex items-center gap-2 mb-2">
                      <span class="bg-red-100 text-red-700 text-xs px-2 py-1 rounded font-bold">OVERLAP</span>
                      <span class="text-sm font-semibold text-slate-900">Period 5 - Friday</span>
                    </div>
                    <p class="text-sm text-slate-700 mb-2">
                      <strong>Ms. Santos</strong> is assigned to both <strong>Grade 7-Rizal (English)</strong> and <strong>Grade 8-Bonifacio (English)</strong> at the same time.
                    </p>
                    <div class="text-xs text-slate-500">
                      Detected: Jan 3, 2026 | Priority: High
                    </div>
                  </div>
                  <button class="ml-4 bg-red-600 text-white px-3 py-2 rounded text-xs font-semibold hover:bg-red-700">
                    Resolve
                  </button>
                </div>
              </div>

              <!-- Conflict Item -->
              <div class="bg-amber-50 border-l-4 border-amber-500 p-4 rounded">
                <div class="flex items-start justify-between">
                  <div class="flex-1">
                    <div class="flex items-center gap-2 mb-2">
                      <span class="bg-amber-100 text-amber-700 text-xs px-2 py-1 rounded font-bold">OVERLOAD</span>
                      <span class="text-sm font-semibold text-slate-900">Teacher Workload</span>
                    </div>
                    <p class="text-sm text-slate-700 mb-2">
                      <strong>Mr. Lopez</strong> has 26 hours assigned, exceeding the maximum workload of 24 hours.
                    </p>
                    <div class="text-xs text-slate-500">
                      Detected: Jan 3, 2026 | Priority: Medium
                    </div>
                  </div>
                  <button class="ml-4 bg-amber-600 text-white px-3 py-2 rounded text-xs font-semibold hover:bg-amber-700">
                    Resolve
                  </button>
                </div>
              </div>

              <!-- Conflict Item -->
              <div class="bg-orange-50 border-l-4 border-orange-500 p-4 rounded">
                <div class="flex items-start justify-between">
                  <div class="flex-1">
                    <div class="flex items-center gap-2 mb-2">
                      <span class="bg-orange-100 text-orange-700 text-xs px-2 py-1 rounded font-bold">UNASSIGNED</span>
                      <span class="text-sm font-semibold text-slate-900">Period 5 - Grade 7-Bonifacio</span>
                    </div>
                    <p class="text-sm text-slate-700 mb-2">
                      <strong>Social Studies</strong> has no teacher assigned for this period.
                    </p>
                    <div class="text-xs text-slate-500">
                      Detected: Jan 3, 2026 | Priority: High
                    </div>
                  </div>
                  <button class="ml-4 bg-orange-600 text-white px-3 py-2 rounded text-xs font-semibold hover:bg-orange-700">
                    Assign
                  </button>
                </div>
              </div>
            </div>

            <div class="mt-4 p-3 bg-red-50 border border-red-200 rounded text-sm text-red-800">
              ⚠️ <strong>3 conflicts detected.</strong> Please resolve conflicts before publishing the schedule.
            </div>
          </div>

  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  const sessionTypeFilter = document.getElementById('sessionTypeFilter');
  const schoolLevelFilter = document.getElementById('schoolLevelFilter');
  const gradeLevelFilter = document.getElementById('gradeLevelFilter');
  
  // Function to get filtered subjects for a section
  function getFilteredSubjects(isSpecial) {
    const specializedCodes = window.schedulerData.specializedSubjectCodes;
    
    if (isSpecial) {
      // Special sections: show all subjects (core + specialized)
      return window.schedulerData.subjects;
    } else {
      // Regular sections: show only core subjects (exclude SPA, SPJ)
      return window.schedulerData.subjects.filter(subject => 
        !specializedCodes.includes(subject.code)
      );
    }
  }
  
  // Function to check if a subject is specialized
  function isSpecializedSubject(subjectId) {
    const subject = window.schedulerData.subjects.find(s => s.id == subjectId);
    return subject && window.schedulerData.specializedSubjectCodes.includes(subject.code);
  }

  // Subject constraints helpers
  function isSubjectBlockedAtPeriod(subjectId, periodNumber) {
    if (!subjectId || !periodNumber) return false;
    const constraints = window.schedulerData.subjectConstraints;
    if (!constraints) return false;
    if (Array.isArray(constraints)) {
      const entry = constraints.find(c => parseInt(c.subject_id) === parseInt(subjectId));
      if (!entry) return false;
      const periods = Array.isArray(entry.periods) ? entry.periods : [];
      return periods.includes(parseInt(periodNumber));
    }
    // object-map fallback: { [subjectId]: [periods] }
    const arr = constraints[String(subjectId)] || constraints[parseInt(subjectId)] || [];
    return Array.isArray(arr) && arr.includes(parseInt(periodNumber));
  }

  function applySubjectConstraintsToDropdown(subjectDropdown, periodNumber) {
    if (!subjectDropdown) return;
    Array.from(subjectDropdown.options).forEach(opt => {
      const sid = parseInt(opt.value || '');
      if (!sid) return;
      const blocked = isSubjectBlockedAtPeriod(sid, periodNumber);
      if (blocked) {
        opt.disabled = true;
        opt.title = `Blocked by Subject Constraints for P${periodNumber}`;
        opt.classList.add('text-slate-400');
      } else {
        opt.disabled = false;
        opt.title = '';
        opt.classList.remove('text-slate-400');
      }
    });
  }
  
  // Function to count specialized subjects in a section row
  function countSpecializedSubjects(sectionRow) {
    let count = 0;
    const subjectDropdowns = sectionRow.querySelectorAll('.subject-dropdown');
    subjectDropdowns.forEach(dropdown => {
      if (dropdown.value && isSpecializedSubject(parseInt(dropdown.value))) {
        count++;
      }
    });
    return count;
  }
  
  // Function to render period headers
  function renderPeriodHeaders(sessionType) {
    const headerRow = document.getElementById('period-header-row');
    const periods = sessionType === 'regular' ? window.schedulerData.periodsRegular : window.schedulerData.periodsShortened;
    
    // Remove existing period headers (keep Section header)
    const periodHeaders = headerRow.querySelectorAll('[data-period]');
    periodHeaders.forEach(h => h.remove());
    
    // Check if any visible section is special (has period 9)
    const hasSpecialSections = document.querySelector('tr[data-is-special="1"]');
    
    // Add new period headers
    periods.forEach((period, index) => {
      // Skip period 9 if no special sections are visible
      if (period.number === 9 && !hasSpecialSections) {
        return;
      }
      
      const bgClass = index % 2 === 0 ? 'bg-blue-50' : '';
      const th = document.createElement('th');
      th.className = `px-4 py-2 text-center font-bold text-slate-700 ${bgClass}`;
      th.setAttribute('data-period', period.number);
      th.innerHTML = `P${period.number}<br/><span class=\"text-xs font-normal period-time whitespace-nowrap\">${period.start}-${period.end}</span>`;
      headerRow.appendChild(th);
    });
  }
  
  // Initialize period headers on page load
  renderPeriodHeaders('regular');
  
  // Handle session type changes (Regular vs Shortened)
  sessionTypeFilter.addEventListener('change', function() {
    const sessionType = this.value;
    renderPeriodHeaders(sessionType);
  });
  
  // Function to apply filters to section rows
  function applyFilters() {
    const schoolLevel = schoolLevelFilter.value;
    const gradeLevel = gradeLevelFilter.value;
    const sectionRows = document.querySelectorAll('tbody tr[data-section-id]');
    
    let visibleCount = 0;
    
    sectionRows.forEach(row => {
      let showRow = true;
      
      // Filter by school level
      if (schoolLevel !== 'all') {
        const rowSchoolLevel = row.getAttribute('data-school-level');
        if (rowSchoolLevel !== schoolLevel) {
          showRow = false;
        }
      }
      
      // Filter by grade level
      if (gradeLevel !== 'all') {
        const rowGradeLevel = row.getAttribute('data-grade-level');
        if (rowGradeLevel !== gradeLevel) {
          showRow = false;
        }
      }
      
      // Show or hide the row
      if (showRow) {
        row.style.display = '';
        visibleCount++;
      } else {
        row.style.display = 'none';
      }
    });
    
    // Update period headers after filtering (to show/hide period 9)
    const sessionType = sessionTypeFilter.value;
    renderPeriodHeaders(sessionType);
    
    return visibleCount;
  }
  
  // Handle school level changes (JH/SH filtering)
  schoolLevelFilter.addEventListener('change', function() {
    const selectedLevel = this.value;
    const gradeOptions = gradeLevelFilter.querySelectorAll('option[data-level]');
    
    // Reset to "All Grades"
    gradeLevelFilter.value = 'all';
    
    // Show/hide grade options based on school level
    gradeOptions.forEach(option => {
      if (selectedLevel === 'all') {
        // Show all grades
        option.style.display = '';
      } else if (option.dataset.level === selectedLevel) {
        // Show matching grades (JH: 7-10, SH: 11-12)
        option.style.display = '';
      } else {
        // Hide non-matching grades
        option.style.display = 'none';
      }
    });
    
    // Apply filters to sections
    applyFilters();
  });
  
  // Handle grade level changes
  gradeLevelFilter.addEventListener('change', function() {
    applyFilters();
  });
  
  // Handle tab switching
  const tabButtons = document.querySelectorAll('.tab-button');
  const tabContents = document.querySelectorAll('.tab-content');
  
  tabButtons.forEach(button => {
    button.addEventListener('click', function() {
      const targetTab = this.dataset.tab;
      
      // Remove active state from all buttons
      tabButtons.forEach(btn => {
        btn.classList.remove('border-blue-600', 'text-blue-600');
        btn.classList.add('text-slate-500');
      });
      
      // Add active state to clicked button
      this.classList.remove('text-slate-500');
      this.classList.add('border-blue-600', 'text-blue-600');
      
      // Hide all tab contents
      tabContents.forEach(content => {
        content.classList.add('hidden');
      });
      
      // Show selected tab content
      document.getElementById('tab-' + targetTab).classList.remove('hidden');
    });
  });
  
  // Function to style dropdowns based on selection
  function styleDropdown(dropdown, isSubject) {
    if (dropdown.value) {
      // Has selection - look like text
      dropdown.classList.remove('border', 'border-slate-300', 'text-slate-400');
      dropdown.classList.add('border-0');
      if (isSubject) {
        dropdown.classList.add('text-blue-600', 'font-bold');
        dropdown.classList.remove('text-slate-900', 'font-normal');
      } else {
        dropdown.classList.add('text-slate-900', 'font-normal');
        dropdown.classList.remove('text-blue-600', 'font-bold');
      }
    } else {
      // No selection - show border
      dropdown.classList.add('border', 'border-slate-300', 'text-slate-400');
      dropdown.classList.remove('border-0', 'text-blue-600', 'text-slate-900', 'font-bold', 'font-normal');
    }
  }
  
  // Initialize all schedule cells with dropdown functionality
  document.querySelectorAll('.schedule-cell').forEach(cell => {
    const subjectDropdown = cell.querySelector('.subject-dropdown');
    const teacherDropdown = cell.querySelector('.teacher-dropdown');
    
    if (!subjectDropdown || !teacherDropdown) return;
    
    // Get section info from parent row
    const sectionRow = cell.closest('tr');
    const isSpecial = sectionRow.dataset.isSpecial === '1';
    const sectionId = sectionRow.dataset.sectionId;
    
    // Populate subject dropdown based on section type
    const filteredSubjects = getFilteredSubjects(isSpecial);
    const currentSubject = subjectDropdown.value;
    const periodNumber = parseInt(cell.getAttribute('data-period'));
    
    subjectDropdown.innerHTML = '<option value="">Select Subject</option>';
    filteredSubjects.forEach(subject => {
      const option = document.createElement('option');
      option.value = subject.id;
      option.textContent = subject.name;
      if (subject.id == currentSubject) {
        option.selected = true;
      }
      subjectDropdown.appendChild(option);
    });

    // Apply subject constraints (disable blocked subjects for this period)
    applySubjectConstraintsToDropdown(subjectDropdown, periodNumber);
    
    // Function to check if a teacher is restricted for a period
    function isTeacherRestricted(teacher, period) {
      const restrictions = window.schedulerData.facultyRestrictions;
      const teacherAncillaries = window.schedulerData.teacherAncillaries[teacher.id] || [];
      
      // Check all restrictions
      for (const restrictionId in restrictions) {
        const restriction = restrictions[restrictionId];
        
        // Skip if periods don't include this period
        if (!restriction.periods || !restriction.periods.includes(period)) {
          continue;
        }
        
        // Check restriction type
        if (restriction.type === 'teacher') {
          // Specific teacher restriction
          if (restriction.metadata.teacherId === teacher.id) {
            return true;
          }
        } else if (restriction.type === 'all-ancillary') {
          // All teachers with ANY ancillary task
          if (teacherAncillaries.length > 0) {
            return true;
          }
        } else if (restriction.type === 'ancillary-role') {
          // Specific ancillary role
          const roleName = restriction.metadata.roleName;
          if (teacherAncillaries.includes(roleName)) {
            return true;
          }
        }
      }
      
      return false;
    }
    
    // Function to get restriction reason for a teacher
    function getRestrictionReason(teacher, period) {
      const restrictions = window.schedulerData.facultyRestrictions;
      const teacherAncillaries = window.schedulerData.teacherAncillaries[teacher.id] || [];
      
      // Check all restrictions
      for (const restrictionId in restrictions) {
        const restriction = restrictions[restrictionId];
        
        // Skip if periods don't include this period
        if (!restriction.periods || !restriction.periods.includes(period)) {
          continue;
        }
        
        // Check if this restriction applies to this teacher
        let applies = false;
        
        if (restriction.type === 'teacher') {
          if (restriction.metadata.teacherId === teacher.id) {
            applies = true;
          }
        } else if (restriction.type === 'all-ancillary') {
          if (teacherAncillaries.length > 0) {
            applies = true;
          }
        } else if (restriction.type === 'ancillary-role') {
          const roleName = restriction.metadata.roleName;
          if (teacherAncillaries.includes(roleName)) {
            applies = true;
          }
        }
        
        if (applies) {
          return restriction.reason;
        }
      }
      
      return '';
    }
    
    // Function to update teacher dropdown based on selected subject
    function updateTeacherDropdown() {
      const selectedSubjectId = parseInt(subjectDropdown.value);
      const currentTeacher = teacherDropdown.value;
      const periodNumber = parseInt(cell.getAttribute('data-period'));
      
      teacherDropdown.innerHTML = '<option value="">Assign Teacher</option>';
      
      if (selectedSubjectId) {
        const selectedSubject = window.schedulerData.subjects.find(s => s.id === selectedSubjectId);
        
        if (selectedSubject && selectedSubject.teachers) {
          selectedSubject.teachers.forEach(teacher => {
            const option = document.createElement('option');
            option.value = teacher.id;
            
            // Check if teacher is restricted for this period
            const isRestricted = isTeacherRestricted(teacher, periodNumber);
            const restrictionReason = getRestrictionReason(teacher, periodNumber);
            
            if (isRestricted) {
              // Show but disable restricted teachers
              option.textContent = teacher.name + ' ❌ (RESTRICTED)';
              option.disabled = true;
              option.style.color = '#a1a1a1';
              option.title = `Restriction: ${restrictionReason}`;
              option.className = 'restricted-option';
            } else {
              option.textContent = teacher.name;
            }
            
            if (teacher.id == currentTeacher) {
              option.selected = true;
            }
            teacherDropdown.appendChild(option);
          });
        }
      }
      
      // Style the teacher dropdown
      styleDropdown(teacherDropdown, false);
    }
    
    // Initialize teacher dropdown on page load
    updateTeacherDropdown();
    
    // Style dropdowns on page load
    styleDropdown(subjectDropdown, true);
    styleDropdown(teacherDropdown, false);
    
    // Handle subject change with validation
    subjectDropdown.addEventListener('change', function() {
      const selectedSubjectId = parseInt(this.value);
      
      // Validate specialized subject limit for special sections
      if (isSpecial && selectedSubjectId && isSpecializedSubject(selectedSubjectId)) {
        const specializedCount = countSpecializedSubjects(sectionRow);
        
        if (specializedCount > 1) {
          alert('Special sections can only have 1 specialized subject (SPA or SPJ) across all periods.');
          this.value = ''; // Reset selection
          styleDropdown(subjectDropdown, true);
          return;
        }
      }
      
      updateTeacherDropdown();
      styleDropdown(subjectDropdown, true);
      styleDropdown(teacherDropdown, false);
    });
    
    // Handle teacher change
    teacherDropdown.addEventListener('change', function() {
      styleDropdown(teacherDropdown, false);
    });
    
    // When subject changes, update teacher dropdown
    subjectDropdown.addEventListener('change', function() {
      updateTeacherDropdown();
      styleDropdown(subjectDropdown, true);
      styleDropdown(teacherDropdown, false);
      saveScheduleChange(cell, subjectDropdown, teacherDropdown);
    });
    
    // When teacher changes, save
    teacherDropdown.addEventListener('change', function() {
      styleDropdown(teacherDropdown, false);
      saveScheduleChange(cell, subjectDropdown, teacherDropdown);
    });
  });

  // Once subject constraints are loaded, apply to all subject dropdowns
  window.addEventListener('subjectConstraintsLoaded', function() {
    document.querySelectorAll('.schedule-cell').forEach(cell => {
      const subjectDropdown = cell.querySelector('.subject-dropdown');
      if (!subjectDropdown) return;
      const periodNumber = parseInt(cell.getAttribute('data-period'));
      applySubjectConstraintsToDropdown(subjectDropdown, periodNumber);
    });
  });
  
  // Function to save schedule changes
  function saveScheduleChange(cell, subjectDropdown, teacherDropdown) {
    const subjectId = subjectDropdown.value;
    const teacherId = teacherDropdown.value;
    
    if (!subjectId || !teacherId) return;
    
    const subject = window.schedulerData.subjects.find(s => s.id == subjectId);
    const teacher = window.schedulerData.teachers.find(t => t.id == teacherId);
    
    console.log('Schedule updated:', {
      section: cell.dataset.section,
      period: cell.dataset.period,
      subjectId: subjectId,
      subject: subject?.name,
      teacherId: teacherId,
      teacher: teacher?.name
    });
    
    // TODO: Save to database via AJAX
  }
  
  // Listen for restrictions updates from settings page
  window.addEventListener('restrictionsUpdated', function(event) {
    window.schedulerData.facultyRestrictions = event.detail;
    console.log('Faculty restrictions updated:', event.detail);
    
    // Refresh all teacher dropdowns to show/hide restricted options
    document.querySelectorAll('.teacher-dropdown').forEach(dropdown => {
      if (dropdown.value) {
        // Trigger change event to refresh the dropdown
        dropdown.dispatchEvent(new Event('change', { bubbles: true }));
      }
    });
  });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\TuroTugma\resources\views\admin\schedule-maker\scheduler.blade.php ENDPATH**/ ?>