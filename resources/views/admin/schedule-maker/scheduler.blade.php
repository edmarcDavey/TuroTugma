@extends('admin.layout')

@section('title','Schedule Maker - Scheduler')
@section('heading','Schedule Maker — Scheduler')

@section('content')
<div class="min-h-screen bg-white p-6">
  <div class="max-w-full mx-auto">

    <!-- Main Content: Full Width Schedule -->
    <!-- Tabs Navigation -->
          <div class="border-b bg-slate-50 flex">
            <button data-tab="master" class="tab-button flex-1 px-6 py-4 font-semibold text-slate-700 border-b-2 border-blue-600 text-blue-600 hover:bg-blue-50">
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
                  <option value="sh">Senior High Only</option>
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
                  <option value="11" data-level="sh">Grade 11</option>
                  <option value="12" data-level="sh">Grade 12</option>
                </select>
              </div>
              
              <div class="flex-1"></div>
              
              <button class="px-4 py-2 bg-blue-600 text-white rounded text-sm font-semibold hover:bg-blue-700">
                🔄 Refresh
              </button>
              <button class="px-4 py-2 bg-slate-200 text-slate-700 rounded text-sm font-semibold hover:bg-slate-300">
                📥 Export
              </button>
            </div>

            <!-- Schedule Grid Table -->
            <div class="overflow-x-auto border rounded-lg">
              <table class="w-full table-fixed text-[13px]">
                <thead class="bg-slate-100 border-b">
                  <tr class="text-xs">
                    <th class="px-2 py-1 text-left font-bold text-slate-700 w-40">Section</th>
                    <th class="px-2 py-1 text-center font-bold text-slate-700 bg-blue-50">P1<br/><span class="text-[10px] font-normal period-time" data-regular="7:30" data-shortened="7:30">7:30</span></th>
                    <th class="px-2 py-1 text-center font-bold text-slate-700">P2<br/><span class="text-[10px] font-normal period-time" data-regular="8:30" data-shortened="8:20">8:30</span></th>
                    <th class="px-2 py-1 text-center font-bold text-slate-700 bg-blue-50">P3<br/><span class="text-[10px] font-normal period-time" data-regular="9:30" data-shortened="9:10">9:30</span></th>
                    <th class="px-2 py-1 text-center font-bold text-slate-700">P4<br/><span class="text-[10px] font-normal period-time" data-regular="10:30" data-shortened="10:10">10:30</span></th>
                    <th class="px-2 py-1 text-center font-bold text-slate-700 bg-blue-50">P5<br/><span class="text-[10px] font-normal period-time" data-regular="11:30" data-shortened="11:00">11:30</span></th>
                    <th class="px-2 py-1 text-center font-bold text-slate-700">P6<br/><span class="text-[10px] font-normal period-time" data-regular="1:00" data-shortened="12:50">1:00</span></th>
                    <th class="px-2 py-1 text-center font-bold text-slate-700 bg-blue-50">P7<br/><span class="text-[10px] font-normal period-time" data-regular="2:00" data-shortened="1:40">2:00</span></th>
                    <th class="px-2 py-1 text-center font-bold text-slate-700">P8<br/><span class="text-[10px] font-normal period-time" data-regular="3:00" data-shortened="2:30">3:00</span></th>
                    <th class="px-2 py-1 text-center font-bold text-slate-700 bg-blue-50">P9<br/><span class="text-[10px] font-normal period-time" data-regular="4:00" data-shortened="3:20">4:00</span></th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($sections as $section)
                    <tr class="border-b hover:bg-blue-50" data-level="{{ $section->gradeLevel->school_stage }}" data-grade="{{ $section->gradeLevel->year }}">
                      <td class="px-2 py-2 sm:px-3 sm:py-3 font-semibold text-slate-900 text-sm">{{ $section->gradeLevel->name }} - {{ $section->name }}</td>

                      @for($p = 1; $p <= 9; $p++)
                        <td class="px-2 py-2 text-center">
                          <div class="bg-slate-100 text-slate-600 text-[10px] font-semibold py-1 px-2 rounded inline-block">
                            Unassigned
                          </div>
                        </td>
                      @endfor
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>

            <div class="mt-4 p-2 bg-blue-50 border border-blue-200 rounded text-sm text-blue-800">
              ℹ️ <strong><span id="sectionsCount">{{ $sections->count() }}</span> sections shown.</strong>
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
  
  function updateVisibleSections() {
    const selectedLevel = schoolLevelFilter.value; // 'all', 'jh', 'sh'
    const selectedGrade = gradeLevelFilter.value; // 'all' or numeric string
    const rows = document.querySelectorAll('table tbody tr[data-level]');
    let visibleCount = 0;

    rows.forEach(row => {
      const rowLevel = row.dataset.level === 'junior' ? 'jh' : (row.dataset.level === 'senior' ? 'sh' : row.dataset.level);
      const rowGrade = row.dataset.grade;
      let show = true;

      if (selectedLevel !== 'all' && rowLevel !== selectedLevel) show = false;
      if (selectedGrade !== 'all' && rowGrade !== selectedGrade) show = false;

      row.style.display = show ? '' : 'none';
      if (show) visibleCount++;
    });

    const countEl = document.getElementById('sectionsCount');
    if (countEl) countEl.textContent = visibleCount;
  }
  
  // Handle session type changes (Regular vs Shortened)
  sessionTypeFilter.addEventListener('change', function() {
    const sessionType = this.value;
    const periodTimes = document.querySelectorAll('.period-time');
    
    periodTimes.forEach(timeSpan => {
      if (sessionType === 'regular') {
        timeSpan.textContent = timeSpan.dataset.regular;
      } else if (sessionType === 'shortened') {
        timeSpan.textContent = timeSpan.dataset.shortened;
      }
    });
  });
  
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

    // Update visible rows based on new filter
    updateVisibleSections();
  });

  // Update when grade filter changes
  gradeLevelFilter.addEventListener('change', function() {
    updateVisibleSections();
  });
  
  // Initialize filtered view
  updateVisibleSections();

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
});
</script>
@endsection
