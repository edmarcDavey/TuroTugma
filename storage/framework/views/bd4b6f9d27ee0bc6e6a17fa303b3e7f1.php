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
        generatedSchedule: {}, // Will store: section_id_period_day -> {subject_id, teacher_id}
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
      
      // Helper function to filter subjects based on section type
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
      
      // Helper function to check if a subject is specialized
      function isSpecializedSubject(subjectId) {
        const subject = window.schedulerData.subjects.find(s => s.id == subjectId);
        return subject && window.schedulerData.specializedSubjectCodes.includes(subject.code);
      }
      
      // Helper function to count specialized subjects in a section row
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
      
      // Function to initialize all dropdowns
      window.initializeDropdowns = function() {
        console.log('=== INITIALIZING DROPDOWNS ===');
        console.log('Subject constraints:', window.schedulerData.subjectConstraints);
        console.log('Number of constraints:', Object.keys(window.schedulerData.subjectConstraints || {}).length);
        
        // Debug: Show all constraint details
        for (const key in window.schedulerData.subjectConstraints) {
          const c = window.schedulerData.subjectConstraints[key];
          console.log(`Constraint ${key}: subject_id=${c.subject_id} (type: ${typeof c.subject_id}), name="${c.subject_name}", periods=[${c.periods}]`);
        }
        
        // Debug: Show first few subjects from scheduler data
        console.log('First 3 subjects in schedulerData:', window.schedulerData.subjects.slice(0, 3).map(s => `${s.name} (id=${s.id}, type=${typeof s.id})`));
        
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
            
            // Check if subject is restricted for this period
            const isRestricted = window.isSubjectRestricted(subject, periodNumber);
            const constraintReason = window.getSubjectConstraintReason(subject, periodNumber);
            
            // Debug logging for period 1 only to reduce noise
            if (periodNumber === 1 && (isRestricted || Object.keys(window.schedulerData.subjectConstraints || {}).length > 0)) {
              console.log(`P${periodNumber} Subject: "${subject.name}" (id=${subject.id}, type=${typeof subject.id}) → restricted=${isRestricted}`);
            }
            
            if (isRestricted) {
              // Show but disable restricted subjects
              option.textContent = subject.name + ' ⛔ (RESTRICTED)';
              option.disabled = true;
              option.style.color = '#a1a1a1';
              option.title = `Subject Constraint: ${constraintReason}`;
              option.className = 'restricted-option';
            } else {
              option.textContent = subject.name;
            }
            
            if (subject.id == currentSubject) {
              option.selected = true;
            }
            subjectDropdown.appendChild(option);
          });
        });
        
        console.log('=== DROPDOWNS INITIALIZED ===');
      };
      
      // Load subject constraints from database
      fetch('<?php echo e(route("admin.schedule-maker.settings.get-subject-constraints")); ?>')
        .then(response => response.json())
        .then(data => {
          if (data.success && data.constraints) {
            // Convert array to object if needed (PHP returns JSON arrays as arrays, not objects)
            let constraints = data.constraints;
            if (Array.isArray(constraints)) {
              const constraintsObj = {};
              constraints.forEach((constraint, index) => {
                constraintsObj[index] = constraint;
              });
              constraints = constraintsObj;
            }
            window.schedulerData.subjectConstraints = constraints;
            console.log('Subject constraints loaded:', Object.keys(constraints).length, 'constraints');
            
            // Re-initialize all dropdowns now that constraints are loaded
            window.initializeDropdowns();
          } else {
            window.schedulerData.subjectConstraints = {};
            window.initializeDropdowns();
          }
        })
        .catch(error => {
          console.error('Error loading subject constraints:', error);
          window.schedulerData.subjectConstraints = {};
          window.initializeDropdowns();
        });
      
      // Listen for constraint updates from settings page
      window.addEventListener('constraintsUpdated', function(e) {
        window.schedulerData.subjectConstraints = e.detail;
        console.log('Subject constraints updated:', e.detail);
        // Re-initialize dropdowns to reflect new constraints
        window.initializeDropdowns();
      });

      // Global function to check if a teacher is restricted for a period
      window.isTeacherRestricted = function(teacher, period) {
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
      };

      // Global function to get restriction reason for a teacher
      window.getRestrictionReason = function(teacher, period) {
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
      };

      // Global function to check if a subject is restricted for a period
      window.isSubjectRestricted = function(subject, period) {
        const constraints = window.schedulerData.subjectConstraints;
        
        if (!constraints || Object.keys(constraints).length === 0) {
          return false;
        }
        
        // Normalize subject.id to number
        const subjectId = parseInt(subject.id);
        const periodNum = parseInt(period);
        
        // Check all constraints
        for (const constraintId in constraints) {
          const constraint = constraints[constraintId];
          
          // Normalize constraint.subject_id to number for comparison
          const constraintSubjectId = parseInt(constraint.subject_id);
          
          // Check if this constraint applies to this subject and period
          const subjectIdMatch = constraintSubjectId === subjectId;
          const periodMatch = constraint.periods && Array.isArray(constraint.periods) && constraint.periods.map(p => parseInt(p)).includes(periodNum);
          
          // Debug logging for matching subjects
          if (subjectIdMatch) {
            console.log(`🔍 Match found for "${subject.name}" (id=${subjectId}): constraint.subject_id=${constraintSubjectId}, period=${periodNum}, periods=${JSON.stringify(constraint.periods)}, periodMatch=${periodMatch}`);
          }
          
          if (subjectIdMatch && periodMatch) {
            console.log(`✅ Subject "${subject.name}" IS RESTRICTED for period ${periodNum}`);
            return true;
          }
        }
        
        return false;
      };

      // Global function to get constraint reason for a subject
      window.getSubjectConstraintReason = function(subject, period) {
        const constraints = window.schedulerData.subjectConstraints;
        
        if (!constraints || Object.keys(constraints).length === 0) {
          return '';
        }
        
        // Normalize IDs to numbers
        const subjectId = parseInt(subject.id);
        const periodNum = parseInt(period);
        
        // Check all constraints
        for (const constraintId in constraints) {
          const constraint = constraints[constraintId];
          
          // Normalize constraint.subject_id to number for comparison
          const constraintSubjectId = parseInt(constraint.subject_id);
          
          // Check if this constraint applies to this subject and period
          const subjectIdMatch = constraintSubjectId === subjectId;
          const periodMatch = constraint.periods && Array.isArray(constraint.periods) && constraint.periods.map(p => parseInt(p)).includes(periodNum);
          
          if (subjectIdMatch && periodMatch) {
            return constraint.reason || 'Restricted by subject constraint';
          }
        }
        
        return '';
      };
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
            <div class="mb-2 bg-slate-50 border border-slate-200 rounded-lg p-2">
              <h3 class="text-base font-bold text-slate-900 mb-2">📊 Master Schedule - SY 2024-2025</h3>
              
              <!-- Session Selection Panel -->
              <div class="mb-2 p-2 bg-white border border-blue-200 rounded-lg">
                <div class="flex items-center justify-between mb-2">
                  <h4 class="text-sm font-semibold text-slate-700">📋 Select Schedule to Generate</h4>
                  <span id="sessionStatus" class="text-xs font-semibold px-2 py-1 bg-yellow-100 text-yellow-800 rounded">⚙️ Not configured</span>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mb-2">
                  <!-- Level Selector -->
                  <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">School Level</label>
                    <select id="scheduleLevelSelector" class="w-full px-3 py-2 border border-slate-300 rounded text-sm font-semibold">
                      <option value="">-- Select Level --</option>
                      <option value="junior_high">Junior High (Full Year)</option>
                      <option value="senior_high_sem1" disabled title="Coming Soon">Senior High Sem 1 (Coming Soon)</option>
                      <option value="senior_high_sem2" disabled title="Coming Soon - Requires SHS Sem1 completion">Senior High Sem 2 (Coming Soon)</option>
                    </select>
                  </div>
                  
                  <!-- Action Buttons -->
                  <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">&nbsp;</label>
                    <div class="flex gap-2">
                      <button id="generateScheduleBtn" class="flex-1 px-3 py-2 bg-blue-600 text-white rounded text-sm font-semibold hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                        ✨ Generate Schedule
                      </button>
                      <button id="saveScheduleBtn" class="flex-1 px-3 py-2 bg-green-600 text-white rounded text-sm font-semibold hover:bg-green-700 disabled:opacity-50 disabled:cursor-not-allowed" disabled title="Save the generated schedule">
                        💾 Save Schedule
                      </button>
                      <button id="viewDraftsBtn" class="flex-1 px-3 py-2 bg-purple-600 text-white rounded text-sm font-semibold hover:bg-purple-700" title="View saved schedules">
                        📋 View Drafts
                      </button>
                    </div>
                  </div>
                </div>

              </div>
            </div>
            
            <div class="mb-2 flex flex-wrap gap-2 items-center">
              <!-- Session Display Filter -->
              <div>
                <label class="text-xs text-slate-600 block mb-1">Display Session (view only)</label>
                <select id="sessionTypeFilter" class="px-3 py-2 border border-slate-300 rounded text-sm">
                  <option value="regular" selected>Regular Session</option>
                  <option value="shortened">Shortened Session</option>
                </select>
              </div>

              <!-- Grade Filter -->
              <div>
                <label class="text-xs text-slate-600 block mb-1">Filter View by Grade</label>
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
              
              <button id="exportBtn" class="px-4 py-2 bg-slate-200 text-slate-700 rounded text-sm font-semibold hover:bg-slate-300">
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
  const gradeLevelFilter = document.getElementById('gradeLevelFilter');
  
  // Session selector logic
  const scheduleLevelSelector = document.getElementById('scheduleLevelSelector');
  const generateScheduleBtn = document.getElementById('generateScheduleBtn');
  const saveScheduleBtn = document.getElementById('saveScheduleBtn');
  const viewDraftsBtn = document.getElementById('viewDraftsBtn');
  const sessionStatus = document.getElementById('sessionStatus');
  const exportBtn = document.getElementById('exportBtn');
  
  // Handle schedule level selection
  scheduleLevelSelector.addEventListener('change', function() {
    const selectedLevel = this.value;
    
    if (!selectedLevel) {
      generateScheduleBtn.disabled = true;
      sessionStatus.className = 'text-xs font-semibold px-2 py-1 bg-yellow-100 text-yellow-800 rounded';
      sessionStatus.textContent = '⚙️ Not configured';
      return;
    }
    
    // Check if SHS Sem2 and disable if SHS Sem1 not complete
    if (selectedLevel === 'senior_high_sem2') {
      generateScheduleBtn.disabled = true;
      sessionStatus.className = 'text-xs font-semibold px-2 py-1 bg-red-100 text-red-800 rounded';
      sessionStatus.textContent = '🔒 Requires SHS Sem1 completion';
      alert('Senior High Sem 2 schedule generation requires SHS Sem 1 to be completed first.');
      scheduleLevelSelector.value = '';
      return;
    }
    
    generateScheduleBtn.disabled = false;
    sessionStatus.className = 'text-xs font-semibold px-2 py-1 bg-green-100 text-green-800 rounded';
    sessionStatus.textContent = `✅ Ready: ${selectedLevel.replace(/_/g, ' ')}`;
  });
  
  // Handle generate schedule button
  generateScheduleBtn.addEventListener('click', function() {
    const selectedLevel = scheduleLevelSelector.value;
    
    if (!selectedLevel) {
      alert('Please select a schedule level first');
      return;
    }
    
    generateScheduleBtn.disabled = true;
    generateScheduleBtn.textContent = '⏳ Generating...';
    
    // Call backend to generate schedule
    fetch('<?php echo e(route("admin.schedule-maker.generate")); ?>', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
      },
      body: JSON.stringify({
        level: selectedLevel,
        year: '2024-2025'
      })
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        sessionStatus.className = 'text-xs font-semibold px-2 py-1 bg-green-100 text-green-800 rounded';
        sessionStatus.textContent = `✅ Generated: ${data.entries_created} assignments`;
        
        // Fetch and display the generated schedule
        fetch(`<?php echo e(route('admin.schedule-maker.settings.get-schedule-entries')); ?>?run_id=${data.run_id}`)
          .then(res => res.json())
          .then(scheduleData => {
            if (scheduleData.success && scheduleData.entries) {
              // Store entries indexed by key: section_period_day
              scheduleData.entries.forEach(entry => {
                const key = `${entry.section_id}_${entry.period}_${entry.day}`;
                window.schedulerData.generatedSchedule[key] = {
                  subject_id: entry.subject_id,
                  teacher_id: entry.teacher_id
                };
              });
              
              // Populate all cells with generated data
              document.querySelectorAll('.schedule-cell').forEach(cell => {
                const sectionRow = cell.closest('tr');
                const sectionId = parseInt(sectionRow.dataset.sectionId);
                const period = parseInt(cell.getAttribute('data-period'));
                
                // For now, using day 1 (Monday) - in future, support multiple days
                const key = `${sectionId}_${period}_1`;
                const assignment = window.schedulerData.generatedSchedule[key];
                
                if (assignment && assignment.subject_id) {
                  const subjectDropdown = cell.querySelector('.subject-dropdown');
                  const teacherDropdown = cell.querySelector('.teacher-dropdown');
                  
                  if (subjectDropdown && teacherDropdown) {
                    // Get subject data
                    const subject = window.schedulerData.subjects.find(s => s.id == assignment.subject_id);
                    
                    if (subject) {
                      // Set subject value
                      subjectDropdown.value = assignment.subject_id;
                      subjectDropdown.classList.remove('text-slate-400');
                      
                      // Populate teacher dropdown with teachers from this subject
                      const teachers = subject.teachers || [];
                      teacherDropdown.innerHTML = '<option value="">Assign Teacher</option>';
                      teachers.forEach(teacher => {
                        const option = document.createElement('option');
                        option.value = teacher.id;
                        option.textContent = teacher.name;
                        teacherDropdown.appendChild(option);
                      });
                      
                      // Set teacher value if available
                      if (assignment.teacher_id) {
                        teacherDropdown.value = assignment.teacher_id;
                        teacherDropdown.classList.remove('text-slate-400');
                      }
                    }
                  }
                }
              });
              
              console.log('Schedule populated with', Object.keys(window.schedulerData.generatedSchedule).length, 'entries');
              
              // Enable Save button now that schedule is generated
              saveScheduleBtn.disabled = false;
            }
          })
          .catch(err => console.error('Error loading schedule entries:', err));
        
        // Optionally reload page after delay
        // setTimeout(() => location.reload(), 2000);
      } else {
        throw new Error(data.message || 'Generation failed');
      }
    })
    .catch(error => {
      console.error('Error:', error);
      alert(`Error: ${error.message}`);
      sessionStatus.className = 'text-xs font-semibold px-2 py-1 bg-red-100 text-red-800 rounded';
      sessionStatus.textContent = '❌ Generation failed';
    })
    .finally(() => {
      generateScheduleBtn.disabled = false;
      generateScheduleBtn.textContent = '✨ Generate Schedule';
    });
  });
  
  // Export button
  exportBtn.addEventListener('click', function() {
    alert('Export feature coming soon');
  });
  
  // Save Schedule button
  saveScheduleBtn.addEventListener('click', function() {
    const selectedLevel = scheduleLevelSelector.value;
    
    if (!selectedLevel) {
      alert('Please select a schedule level first');
      return;
    }
    
    if (Object.keys(window.schedulerData.generatedSchedule).length === 0) {
      alert('No schedule has been generated yet');
      return;
    }
    
    saveScheduleBtn.disabled = true;
    saveScheduleBtn.textContent = '⏳ Saving...';
    
    // Call backend to save the schedule (update status from draft to locked)
    fetch('<?php echo e(route("admin.schedule-maker.save")); ?>', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
      },
      body: JSON.stringify({
        level: selectedLevel,
        year: '2024-2025'
      })
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        sessionStatus.className = 'text-xs font-semibold px-2 py-1 bg-green-100 text-green-800 rounded';
        sessionStatus.textContent = `✅ Saved: ${data.run_id ? 'Run #' + data.run_id : 'Schedule saved'}`;
        alert('Schedule saved successfully!');
      } else {
        throw new Error(data.message || 'Save failed');
      }
    })
    .catch(error => {
      console.error('Error:', error);
      alert(`Error: ${error.message}`);
      sessionStatus.className = 'text-xs font-semibold px-2 py-1 bg-red-100 text-red-800 rounded';
      sessionStatus.textContent = '❌ Save failed';
    })
    .finally(() => {
      saveScheduleBtn.disabled = false;
      saveScheduleBtn.textContent = '💾 Save Schedule';
    });
  });
  
  // View Drafts button
  viewDraftsBtn.addEventListener('click', function() {
    const selectedLevel = scheduleLevelSelector.value || 'junior_high';
    
    // Fetch all drafts for this level
    fetch(`<?php echo e(route('admin.schedule-maker.get-drafts')); ?>?level=${selectedLevel}`)
      .then(response => response.json())
      .then(data => {
        if (data.success && data.drafts && data.drafts.length > 0) {
          // Display drafts in a modal or list
          let draftList = 'Available Drafts:\n\n';
          data.drafts.forEach((draft, index) => {
            draftList += `${index + 1}. ${draft.name}\n   Created: ${new Date(draft.created_at).toLocaleString()}\n   Entries: ${draft.meta?.entries_created || 0}\n\n`;
          });
          alert(draftList);
        } else {
          alert('No saved drafts found for this level');
        }
      })
      .catch(error => {
        console.error('Error:', error);
        alert('Error loading drafts');
      });
  });
  
  // Helper functions already defined above in the script block
  
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
    const gradeLevel = gradeLevelFilter.value;
    const sectionRows = document.querySelectorAll('tbody tr[data-section-id]');
    
    let visibleCount = 0;
    
    sectionRows.forEach(row => {
      let showRow = true;
      
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
  // NOTE: Subject dropdowns are populated by window.initializeDropdowns() after constraints load
  document.querySelectorAll('.schedule-cell').forEach(cell => {
    const subjectDropdown = cell.querySelector('.subject-dropdown');
    const teacherDropdown = cell.querySelector('.teacher-dropdown');
    
    if (!subjectDropdown || !teacherDropdown) return;
    
    // Get section info from parent row
    const sectionRow = cell.closest('tr');
    const isSpecial = sectionRow.dataset.isSpecial === '1';
    const sectionId = sectionRow.dataset.sectionId;
    
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
            const isRestricted = window.isTeacherRestricted(teacher, periodNumber);
            const restrictionReason = window.getRestrictionReason(teacher, periodNumber);
            
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