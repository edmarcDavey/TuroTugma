@extends('admin.layout')

@section('title','Schedule Maker - Scheduler')
@section('heading','Schedule Maker — Scheduler')

@section('content')
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

    {{-- Pass subjects and teachers data to JavaScript --}}
    <script>
      // Plain JavaScript only. Removed TypeScript decorators.
      window.schedulerData = {
        subjects: JSON.parse('{!! addslashes(json_encode($subjects)) !!}'),
        teachers: JSON.parse('{!! addslashes(json_encode($teachers)) !!}'),
        periodsRegular: JSON.parse('{!! addslashes(json_encode($periodsRegular)) !!}'),
        periodsShortened: JSON.parse('{!! addslashes(json_encode($periodsShortened)) !!}'),
        sections: JSON.parse('{!! addslashes(json_encode($sections)) !!}'),
        specializedSubjectCodes: ['SPA', 'SPJ'], // Specialized subjects for special sections
        generatedSchedule: JSON.parse('{!! addslashes(json_encode($generatedSchedule ?? [])) !!}'), // Real schedule data
        days: JSON.parse('{!! addslashes(json_encode($days ?? [1,2,3,4,5])) !!}'), // Days for section schedule
        // Build teacher ancillary assignments map for restriction checking
        teacherAncillaries: (function() {
          const map = {};
          const teachers = JSON.parse('{!! addslashes(json_encode($teachers)) !!}');
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
      fetch('{{ route("admin.schedule-maker.settings.get-faculty-restrictions") }}')
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
      fetch('{{ route("admin.schedule-maker.settings.get-subject-constraints") }}')
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
            <button data-tab="master" class="tab-button flex-1 px-6 py-4 font-semibold border-b-2 border-transparent transition-colors duration-200" id="tab-master-btn">
              Master Schedule
            </button>
            <button data-tab="sections" class="tab-button flex-1 px-6 py-4 font-semibold border-b-2 border-transparent transition-colors duration-200" id="tab-sections-btn">
              Section Schedule
            </button>
            <button data-tab="teachers" class="tab-button flex-1 px-6 py-4 font-semibold border-b-2 border-transparent transition-colors duration-200" id="tab-teachers-btn">
              Teacher's Schedule
            </button>
            <button data-tab="conflicts" class="tab-button flex-1 px-6 py-4 font-semibold border-b-2 border-transparent transition-colors duration-200" id="tab-conflicts-btn">
              Conflicts
            </button>
          </div>

          <!-- Tab Content: Master Schedule -->
          <div id="tab-master" class="tab-content p-6">
            <div class="mb-2 bg-slate-50 border border-slate-200 rounded-lg p-2">
              <h3 class="text-base font-bold text-slate-900 mb-2">Master Schedule - SY 2024-2025</h3>
              <!-- Session Selection Panel -->
              <div class="mb-2 p-2 bg-white border border-blue-200 rounded-lg">
                <div class="flex items-center justify-between mb-2">
                  <h4 class="text-sm font-semibold text-slate-700">Select Schedule to Generate</h4>
                  <span id="sessionStatus" class="text-xs font-semibold px-2 py-1 bg-yellow-100 text-yellow-800 rounded">Not configured</span>
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
                  
                  <!-- Generate Button -->
                  <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">&nbsp;</label>
                    <div class="flex gap-2">
                      <button id="generateScheduleBtn" class="w-full px-3 py-2 bg-blue-600 text-white rounded text-sm font-semibold hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                        ✨ Generate Schedule
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
              
              <button id="saveScheduleBtn" class="px-4 py-2 bg-green-600 text-white rounded text-sm font-semibold hover:bg-green-700 disabled:opacity-50 disabled:cursor-not-allowed" disabled title="Save the generated schedule">
                💾 Save Schedule
              </button>
              <button id="viewDraftsBtn" class="px-4 py-2 bg-purple-600 text-white rounded text-sm font-semibold hover:bg-purple-700" title="View saved schedules">
                📋 View Drafts
              </button>
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
                    {{-- Period headers will be generated by JavaScript --}}
                  </tr>
                </thead>
                <tbody id="schedule-tbody">
                  {{-- Rows will be generated dynamically by JavaScript based on session type --}}
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

          <!-- Tab Content: Section Schedule -->
          <div id="tab-sections" class="tab-content p-6 hidden">
            <h3 class="text-lg font-bold text-slate-900 mb-4">🏫 Section Schedule View - SY 2024-2025</h3>
            <div id="section-schedule-list">
              <!-- Section schedules will be rendered here by JavaScript -->
            </div>
            <script>
            // Section Schedule: Printable, session-aware, config-driven tables
            (async function() {
              const sectionList = document.getElementById('section-schedule-list');
              if (!window.schedulerData || !window.schedulerData.sections) return;

              // Helper: Get teacher by ID
              function getTeacherName(id) {
                const t = window.schedulerData.teachers.find(x => x.id == id);
                return t ? t.name : '';
              }
              // Helper: Get subject by ID
              function getSubjectName(id) {
                const s = window.schedulerData.subjects.find(x => x.id == id);
                return s ? s.name : '';
              }
              // Helper: Get full day name from config (e.g., 1 => Monday)
              function getDayName(dayObj) {
                return dayObj && dayObj.name ? dayObj.name : '';
              }
              // Helper: Get day initial (e.g., 'M' for Monday)
              function getDayInitial(dayObj) {
                return dayObj && dayObj.name ? dayObj.name.charAt(0) : '';
              }
              // Helper: UPPERCASE subject, italic teacher
              function formatCell(subj, tchr) {
                if (!subj && !tchr) return '';
                return `<span style='font-weight:bold;'>${subj ? subj.toUpperCase() : ''}</span><br><span class='italic'>${tchr || ''}</span>`;
              }

              // Fetch config for days, session types, periods, and breaks
              // (Assume window.schedulerData.config contains all needed, or fetch via AJAX if not)
              // For this patch, we simulate config structure:
              // daysConfig = [ {name: 'Monday', session: 'regular'}, ... ]
              // periodsConfig = { regular: [ {number, start, end}, ... ], shortened: [...] }
              // breaksConfig = { regular: [ {after_period, name, duration}, ... ], shortened: [...] }

              // --- BEGIN: Simulated config (replace with real config as needed) ---
              // Example: 5 days, Mon-Fri, regular session
              const daysConfig = [
                {name: 'Monday', session: 'regular'},
                {name: 'Tuesday', session: 'regular'},
                {name: 'Wednesday', session: 'regular'},
                {name: 'Thursday', session: 'regular'},
                {name: 'Friday', session: 'regular'}
              ];
              // Use periods from schedulerData
              const periodsConfig = {
                regular: window.schedulerData.periodsRegular,
                shortened: window.schedulerData.periodsShortened
              };
              // Simulate breaks (replace with real config)
              const breaksConfig = {
                regular: [
                  {after_period: 2, name: 'Recess', duration: 15},
                  {after_period: 4, name: 'Lunch', duration: 60}
                ],
                shortened: [
                  {after_period: 2, name: 'Recess', duration: 10},
                  {after_period: 4, name: 'Lunch', duration: 40}
                ]
              };
              // --- END: Simulated config ---

              // For each section, render a printable table
              window.schedulerData.sections.forEach(section => {
                // Find first period assignment for this section
                let firstPeriodObj = null, firstPeriodDayIdx = null;
                for (let dIdx = 0; dIdx < daysConfig.length; dIdx++) {
                  let session = daysConfig[dIdx].session || 'regular';
                  let periodsArr = periodsConfig[session] || [];
                  if (periodsArr.length > 0) {
                    let periodNum = periodsArr[0].number;
                    let schedKey = `${section.id}_${periodNum}_${dIdx+1}`;
                    let sched = window.schedulerData.generatedSchedule[schedKey];
                    if (sched && sched.teacher_id) {
                      firstPeriodObj = sched;
                      firstPeriodDayIdx = dIdx;
                      break;
                    }
                  }
                }
                let adviser = firstPeriodObj ? getTeacherName(firstPeriodObj.teacher_id) : '';
                let specialization = '';
                let ancillary = '';
                if (firstPeriodObj && firstPeriodObj.teacher_id) {
                  let teacherId = firstPeriodObj.teacher_id;
                  // Find all subjects this teacher teaches in this section
                  let subjectIds = [];
                  Object.keys(window.schedulerData.generatedSchedule).forEach(key => {
                    // key format: sectionId_period_dayIndex
                    if (key.startsWith(section.id + '_')) {
                      let sched = window.schedulerData.generatedSchedule[key];
                      if (sched && sched.teacher_id == teacherId && sched.subject_id) {
                        if (!subjectIds.includes(sched.subject_id)) subjectIds.push(sched.subject_id);
                      }
                    }
                  });
                  specialization = subjectIds.map(sid => getSubjectName(sid)).join(', ');
                  // Get ancillary from teacher data
                  let teacher = window.schedulerData.teachers.find(t => t.id == teacherId);
                  if (teacher && teacher.ancillary_assignments) {
                    let anc = teacher.ancillary_assignments;
                    if (typeof anc === 'string') {
                      try { anc = JSON.parse(anc); } catch(e) {}
                    }
                    if (Array.isArray(anc)) ancillary = anc.join(', ');
                    else if (anc) ancillary = anc;
                  }
                }

                // Build card container for each section
                let html = `<div class="overflow-x-auto border rounded-lg bg-white p-4 mb-8">`;
                let gradeLevel = '';
                if (section.grade_level) {
                  if (typeof section.grade_level === 'object' && section.grade_level.name) {
                    // Remove 'Grade' if present, keep only digit
                    gradeLevel = section.grade_level.name.replace(/Grade\s*/i, '').trim();
                  } else if (typeof section.grade_level === 'string') {
                    gradeLevel = section.grade_level.replace(/Grade\s*/i, '').trim();
                  } else {
                    gradeLevel = section.grade_level;
                  }
                }
                let sectionDisplayName = gradeLevel ? `${gradeLevel}-${section.name}` : section.name;
                html += `<table class=\"w-full text-xs border border-slate-400 mb-4\">\n` +
                  `  <tr><td colspan=\"${daysConfig.length+2}\" class=\"text-center font-bold text-base py-2\">CLASS PROGRAM</td></tr>\n` +
                  `  <tr>\n` +
                  `    <td class=\"font-bold\">Section:</td>\n` +
                  `    <td colspan=\"${daysConfig.length-1}\" class=\"font-bold\">${sectionDisplayName || ''}</td>\n` +
                  `    <td>Name of Adviser</td>\n` +
                  `    <td class=\"font-bold\">${adviser}</td>\n` +
                  `  </tr>\n` +
                  `  <tr>\n` +
                  `    <td class=\"font-bold\">School:</td>\n` +
                  `    <td colspan=\"${daysConfig.length-1}\" class=\"font-bold\">Diadi National High School</td>\n` +
                  `    <td>Specialization</td>\n` +
                  `    <td class=\"font-bold\">${specialization}</td>\n` +
                  `  </tr>\n` +
                  `  <tr>\n` +
                  `    <td class=\"font-bold\">District:</td>\n` +
                  `    <td colspan=\"${daysConfig.length-1}\" class=\"font-bold\">Diadi</td>\n` +
                  `    <td>Ancillary</td>\n` +
                  `    <td class=\"font-bold\">${ancillary}</td>\n` +
                  `  </tr>\n` +
                  `</table>`;

                // Main schedule table
                html += `<table class=\"w-full text-xs border border-slate-400 mb-4\">`;
                // Header row: Time (Regular/Shortened) | Monday | Tuesday | ...
                let hasShortened = (periodsConfig.shortened && periodsConfig.shortened.length > 0);
                let hasRegular = (periodsConfig.regular && periodsConfig.regular.length > 0);
                let timeColspan = (hasRegular && hasShortened) ? 2 : 1;
                html += `<tr class=\"bg-slate-100\">`;
                if (hasRegular && hasShortened) {
                  html += `<th colspan=\"2\" class=\"border border-slate-400 px-2 py-1 text-center\">Time</th>`;
                } else {
                  html += `<th class=\"border border-slate-400 px-2 py-1 text-center\">Time</th>`;
                }
                daysConfig.forEach(day => {
                  html += `<th class=\"border border-slate-400 px-2 py-1 text-center\">${day.name}</th>`;
                });
                html += `</tr>`;

                // Subheader: Session initials (R/S)
                html += `<tr class=\"bg-slate-50\">`;
                // Build initials for regular and shortened sessions
                let regularDays = daysConfig.filter(day => (day.session || 'regular') === 'regular');
                let shortenedDays = daysConfig.filter(day => (day.session || 'regular') === 'shortened');
                let getInitial = dayName => dayName === 'Thursday' ? 'Th' : (dayName === 'Sunday' ? 'Su' : dayName.charAt(0));
                if (hasRegular && hasShortened) {
                  html += `<td class=\"border border-slate-400 px-2 py-1 text-center font-bold\">${regularDays.map(d => getInitial(d.name)).join(', ')}</td>`;
                  html += `<td class=\"border border-slate-400 px-2 py-1 text-center font-bold\">${shortenedDays.map(d => getInitial(d.name)).join(', ')}</td>`;
                } else if (hasRegular) {
                  html += `<td class=\"border border-slate-400 px-2 py-1 text-center font-bold\">${regularDays.map(d => getInitial(d.name)).join(', ')}</td>`;
                } else if (hasShortened) {
                  html += `<td class=\"border border-slate-400 px-2 py-1 text-center font-bold\">${shortenedDays.map(d => getInitial(d.name)).join(', ')}</td>`;
                }
                // All other subheader cells are empty
                daysConfig.forEach(() => {
                  html += `<td class=\"border border-slate-400 px-2 py-1 text-center font-bold\"></td>`;
                });
                html += `</tr>`;

                // Subheader: Period times (no period numbers)
                let maxPeriods = Math.max(
                  hasRegular ? periodsConfig.regular.length : 0,
                  hasShortened ? periodsConfig.shortened.length : 0
                );
                // Helper to convert 24hr to 12hr format
                function to12Hour(timeStr) {
                  if (!timeStr) return '';
                  let [h, m] = timeStr.split(':').map(Number);
                  let suffix = h >= 12 ? 'PM' : 'AM';
                  h = h % 12;
                  if (h === 0) h = 12;
                  return `${h}:${m.toString().padStart(2, '0')}${suffix}`;
                }
                for (let i = 0; i < maxPeriods; i++) {
                  html += `<tr>`;
                  // Time columns
                  if (hasRegular && hasShortened) {
                    html += `<td class=\"border border-slate-400 px-2 py-1 text-center whitespace-nowrap\">${periodsConfig.regular[i] ? to12Hour(periodsConfig.regular[i].start) + ' - ' + to12Hour(periodsConfig.regular[i].end) : ''}</td>`;
                    html += `<td class=\"border border-slate-400 px-2 py-1 text-center whitespace-nowrap\">${periodsConfig.shortened[i] ? to12Hour(periodsConfig.shortened[i].start) + ' - ' + to12Hour(periodsConfig.shortened[i].end) : ''}</td>`;
                  } else if (hasRegular) {
                    html += `<td class=\"border border-slate-400 px-2 py-1 text-center whitespace-nowrap\">${periodsConfig.regular[i] ? to12Hour(periodsConfig.regular[i].start) + ' - ' + to12Hour(periodsConfig.regular[i].end) : ''}</td>`;
                  } else if (hasShortened) {
                    html += `<td class=\"border border-slate-400 px-2 py-1 text-center whitespace-nowrap\">${periodsConfig.shortened[i] ? to12Hour(periodsConfig.shortened[i].start) + ' - ' + to12Hour(periodsConfig.shortened[i].end) : ''}</td>`;
                  }
                  // Assignment cells for each day
                  daysConfig.forEach((day, dIdx) => {
                    // Map to generatedSchedule key: sectionId_period_dayIndex+1
                    // Use regular or shortened period number depending on session
                    let session = day.session || 'regular';
                    let periodsArr = periodsConfig[session] || [];
                    let periodObj = periodsArr[i];
                    let schedKey = periodObj ? `${section.id}_${periodObj.number}_${dIdx+1}` : null;
                    let sched = schedKey ? window.schedulerData.generatedSchedule[schedKey] : null;
                    let cell = '';
                    if (sched) {
                      cell = formatCell(getSubjectName(sched.subject_id), getTeacherName(sched.teacher_id));
                    }
                    html += `<td class=\"border border-slate-400 px-2 py-1 text-center\">${cell}</td>`;
                  });
                  html += `</tr>`;
                }
                html += `</table>`;
                html += `</div>`;
                sectionList.insertAdjacentHTML('beforeend', html);
              });
            })();
            </script>
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
    // Tab switching UI improvement
    document.addEventListener('DOMContentLoaded', function() {
      const tabButtons = document.querySelectorAll('.tab-button');
      const tabContents = document.querySelectorAll('.tab-content');
      function setActiveTab(tabName) {
        tabButtons.forEach(b => {
          if (b.getAttribute('data-tab') === tabName) {
            b.classList.add('border-blue-600', 'text-blue-700', 'bg-blue-50');
            b.classList.remove('border-transparent');
          } else {
            b.classList.remove('border-blue-600', 'text-blue-700', 'bg-blue-50');
            b.classList.add('border-transparent');
          }
        });
        tabContents.forEach(content => {
          if (content.id === 'tab-' + tabName) {
            content.style.display = '';
          } else {
            content.style.display = 'none';
          }
        });
      }
      tabButtons.forEach(btn => {
        btn.addEventListener('click', function() {
          setActiveTab(btn.getAttribute('data-tab'));
        });
      });
      // Set initial active tab (default to master)
      setActiveTab('master');
    });
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
  
  // Function to load existing schedule from database
  function loadExistingSchedule(level = 'junior_high') {
    fetch(`{{ route('admin.schedule-maker.get-latest-schedule') }}?level=${level}`)
      .then(response => response.json())
      .then(data => {
        if (data.success && data.run_id && data.entries && data.entries.length > 0) {
          console.log(`Loading existing ${data.status} schedule, run_id=${data.run_id}, ${data.entries.length} entries`);
          
          // Store entries indexed by key
          window.schedulerData.generatedSchedule = {};
          data.entries.forEach(entry => {
            const key = `${entry.section_id}_${entry.period}_${entry.day}`;
            window.schedulerData.generatedSchedule[key] = {
              subject_id: entry.subject_id,
              teacher_id: entry.teacher_id
            };
          });
          
          // Populate UI
          populateScheduleUI();
          
          // Update status display
          sessionStatus.className = 'text-xs font-semibold px-2 py-1 bg-blue-100 text-blue-800 rounded';
          sessionStatus.textContent = `📋 Loaded: ${data.status} schedule (${data.entries.length} entries)`;
          
          // Enable save button if draft
          if (data.status === 'draft') {
            saveScheduleBtn.disabled = false;
          }
        } else {
          console.log('No existing schedule found for', level);
        }
      })
      .catch(error => {
        console.error('Error loading existing schedule:', error);
      });
  }
  
  // Function to populate schedule UI from generatedSchedule data
  function populateScheduleUI() {
    const selectedDay = 1; // Default to Monday or handle as needed
    
    document.querySelectorAll('.schedule-cell').forEach(cell => {
      const sectionRow = cell.closest('tr');
      const sectionId = parseInt(sectionRow.dataset.sectionId);
      const period = parseInt(cell.getAttribute('data-period'));
      
      // Use the selected day instead of hardcoded day 1
      const key = `${sectionId}_${period}_${selectedDay}`;
      const assignment = window.schedulerData.generatedSchedule[key];
      
      if (assignment && assignment.subject_id) {
        const subjectDropdown = cell.querySelector('.subject-dropdown');
        const teacherDropdown = cell.querySelector('.teacher-dropdown');
        
        if (subjectDropdown && teacherDropdown) {
          const subject = window.schedulerData.subjects.find(s => s.id == assignment.subject_id);
          
          if (subject) {
            subjectDropdown.value = assignment.subject_id;
            styleDropdown(subjectDropdown, true);
            
            const teachers = subject.teachers || [];
            teacherDropdown.innerHTML = '<option value="">Assign Teacher</option>';
            teachers.forEach(teacher => {
              const option = document.createElement('option');
              option.value = teacher.id;
              option.textContent = teacher.name;
              teacherDropdown.appendChild(option);
            });
            
            if (assignment.teacher_id) {
              teacherDropdown.value = assignment.teacher_id;
              styleDropdown(teacherDropdown, false);
            }
          }
        }
      }
    });
  }
  
  // Load existing schedule on page load
  setTimeout(() => loadExistingSchedule('junior_high'), 500);
  
  // Handle day filter change

  
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
    fetch('{{ route("admin.schedule-maker.generate") }}', {
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
        fetch(`{{ route('admin.schedule-maker.settings.get-schedule-entries') }}?run_id=${data.run_id}`)
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
    fetch('{{ route("admin.schedule-maker.save") }}', {
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
    fetch(`{{ route('admin.schedule-maker.get-drafts') }}?level=${selectedLevel}`)
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
  
  // Function to initialize dropdowns for a cell
  function initializeCellDropdowns(cell) {
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
      
      styleDropdown(teacherDropdown, false);
    }
    
    updateTeacherDropdown();
    styleDropdown(subjectDropdown, true);
    styleDropdown(teacherDropdown, false);
    
    // Handle subject change with validation
    subjectDropdown.addEventListener('change', function() {
      const selectedSubjectId = parseInt(this.value);
      
      if (isSpecial && selectedSubjectId && isSpecializedSubject(selectedSubjectId)) {
        const specializedCount = countSpecializedSubjects(sectionRow);
        
        if (specializedCount > 1) {
          alert('Special sections can only have 1 specialized subject (SPA or SPJ) across all periods.');
          this.value = '';
          styleDropdown(subjectDropdown, true);
          return;
        }
      }
      
      updateTeacherDropdown();
      styleDropdown(subjectDropdown, true);
      styleDropdown(teacherDropdown, false);
    });
    
    teacherDropdown.addEventListener('change', function() {
      styleDropdown(teacherDropdown, false);
    });
    
    subjectDropdown.addEventListener('change', function() {
      updateTeacherDropdown();
      styleDropdown(subjectDropdown, true);
      styleDropdown(teacherDropdown, false);
      saveScheduleChange(cell, subjectDropdown, teacherDropdown);
    });
    
    teacherDropdown.addEventListener('change', function() {
      styleDropdown(teacherDropdown, false);
      saveScheduleChange(cell, subjectDropdown, teacherDropdown);
    });
  }
  
  // Function to render schedule table with dynamic period count
  function renderScheduleTable(sessionType) {
    const tbody = document.getElementById('schedule-tbody');
    const periods = sessionType === 'regular' ? window.schedulerData.periodsRegular : window.schedulerData.periodsShortened;
    const sections = window.schedulerData.sections;
    const subjects = window.schedulerData.subjects;
    
    tbody.innerHTML = ''; // Clear existing rows
    
    // Generate rows for each section
    sections.forEach(section => {
      const tr = document.createElement('tr');
      tr.className = 'border-b hover:bg-blue-50';
      tr.setAttribute('data-section-id', section.id);
      tr.setAttribute('data-is-special', section.is_special ? '1' : '0');
      tr.setAttribute('data-grade-level', section.grade_level_id + 6);
      tr.setAttribute('data-school-level', 'jh');
      
      // Add section name cell
      const sectionTd = document.createElement('td');
      sectionTd.className = 'px-4 py-3 font-semibold text-slate-900';
      sectionTd.textContent = `${section.grade_level_id + 6}-${section.name}`;
      tr.appendChild(sectionTd);
      
      // Add period cells
      periods.forEach(period => {
        // Skip period 9 for non-special sections
        if (period.number === 9 && !section.is_special) {
          return;
        }
        
        const td = document.createElement('td');
        td.className = 'px-2 py-2 text-center schedule-cell';
        td.setAttribute('data-section', section.name);
        td.setAttribute('data-period', period.number);
        
        // Create cell content with dropdowns
        const div = document.createElement('div');
        div.className = 'p-1';
        
        // Subject dropdown
        const subjectDropdown = document.createElement('select');
        subjectDropdown.className = 'subject-dropdown w-full px-1 py-1 text-xs text-center border border-slate-300 rounded mb-1 text-slate-400';
        
        const subjectOption = document.createElement('option');
        subjectOption.value = '';
        subjectOption.textContent = 'Select Subject';
        subjectDropdown.appendChild(subjectOption);
        
        subjects.forEach(subject => {
          const option = document.createElement('option');
          option.value = subject.id;
          option.textContent = subject.name;
          subjectDropdown.appendChild(option);
        });
        
        // Teacher dropdown
        const teacherDropdown = document.createElement('select');
        teacherDropdown.className = 'teacher-dropdown w-full px-1 py-1 text-xs text-center border border-slate-300 rounded text-slate-400';
        
        const teacherOption = document.createElement('option');
        teacherOption.value = '';
        teacherOption.textContent = 'Assign Teacher';
        teacherDropdown.appendChild(teacherOption);
        
        div.appendChild(subjectDropdown);
        div.appendChild(teacherDropdown);
        td.appendChild(div);
        
        tr.appendChild(td);
        
        // Initialize dropdown functionality for this cell
        initializeCellDropdowns(td);
      });
      
      tbody.appendChild(tr);
    });
  }
  
  // Helper function to format time to 12-hour AM/PM format
  function formatTime12Hour(time24) {
    const [hours, minutes] = time24.split(':');
    let hour = parseInt(hours);
    const ampm = hour >= 12 ? 'PM' : 'AM';
    hour = hour % 12 || 12; // Convert 0 to 12 for midnight, 13-23 to 1-11
    return `${hour}:${minutes} ${ampm}`;
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
      
      // Format times to 12-hour AM/PM format
      const startFormatted = formatTime12Hour(period.start);
      const endFormatted = formatTime12Hour(period.end);
      
      th.innerHTML = `P${period.number}<br/><span class=\"text-xs font-normal period-time whitespace-nowrap\">${startFormatted} - ${endFormatted}</span>`;
      headerRow.appendChild(th);
    });
  }
  
  // Initialize table and period headers on page load
  renderScheduleTable('regular');
  renderPeriodHeaders('regular');
  
  // Handle session type changes (Regular vs Shortened)
  sessionTypeFilter.addEventListener('change', function() {
    const sessionType = this.value;
    renderScheduleTable(sessionType);
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
  
  // NOTE: Cell dropdowns are now initialized dynamically in renderScheduleTable()
  // and initializeCellDropdowns() function
  
  /* OLD CODE - REPLACED BY DYNAMIC INITIALIZATION
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
  */
  
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
@endsection
