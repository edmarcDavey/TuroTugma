<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TuroTugma — Dashboard</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <style>
      :root{--primary:#3b4197}
      .logo-font{font-family:'Poppins', system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial}
    </style>
  </head>
  <body class="bg-white text-slate-800 min-h-screen">
    <div class="max-w-7xl mx-auto px-6 py-8">
      <header class="flex items-center justify-between">
        <div class="flex items-center gap-4">
          <div class="w-10 h-10 rounded-md flex items-center justify-center text-white" style="background:var(--primary)">
            <span class="logo-font font-semibold">TT</span>
          </div>
          <div>
            <div class="logo-font text-xl font-semibold">TuroTugma</div>
            <div class="text-sm text-slate-500">Dashboard</div>
          </div>
        </div>
        <a href="/" class="text-sm text-slate-600 hover:primary-text">Back to Landing</a>
      </header>

      <main class="mt-8">
        <!-- Final Timetable -->
        <div class="p-4 border rounded bg-green-50">
          <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-green-900">📋 Final Timetable</h3>
            <div class="flex items-center gap-4">
              <label for="yearFilter" class="text-sm font-medium text-slate-700">Filter by Grade Level:</label>
              <select id="yearFilter" class="px-3 py-2 border border-slate-300 rounded-md text-sm focus:ring-2 focus:border-green-500" onchange="filterTimetable()">
                <option value="all">All Levels</option>
                <option value="7">Grade 7</option>
                <option value="8">Grade 8</option>
                <option value="9">Grade 9</option>
                <option value="10">Grade 10</option>
                <option value="11">Grade 11</option>
                <option value="12">Grade 12</option>
              </select>
              
              <label for="sectionFilter" class="text-sm font-medium text-slate-700">Filter by Section:</label>
              <select id="sectionFilter" class="px-3 py-2 border border-slate-300 rounded-md text-sm focus:ring-2 focus:border-green-500" onchange="filterTimetable()" disabled>
                <option value="all">All Sections</option>
              </select>
            </div>
          </div>
          <p class="mt-2 text-sm text-green-800">Generated schedule showing sections, time periods, subjects, and assigned teachers.</p>
          <div class="mt-4 bg-white p-4 rounded border border-green-200">
            <?php if(isset($finalTimetable) && count($finalTimetable) > 0): ?>
              <?php
                // Group entries by section
                $sectionsByGrade = [];
                foreach($finalTimetable as $entry) {
                  $grade = $entry['grade_level'];
                  $section = $entry['section'];
                  if (!isset($sectionsByGrade[$grade])) {
                    $sectionsByGrade[$grade] = [];
                  }
                  if (!isset($sectionsByGrade[$grade][$section])) {
                    $sectionsByGrade[$grade][$section] = [];
                  }
                  $sectionsByGrade[$grade][$section][] = $entry;
                }
                
                // Create time periods array using the same logic as master schedule
                $periods = [];
                
                // Use the exact same calculatePeriodTimes function as the scheduler
                $periodsData = \App\Http\Controllers\Admin\SchedulingConfigController::calculatePeriodTimes('regular', 'junior_high');
                
                foreach ($periodsData as $periodData) {
                    $periods[$periodData['number']] = [
                        'time' => $periodData['start'] . ' - ' . $periodData['end'],
                        'label' => 'Period ' . $periodData['number']
                    ];
                }
                
                // Debug: Log the actual periods we got
                error_log("Dashboard periods: " . json_encode($periods));
                
                // Create days array - ensure Friday is always included
                $days = [1 => 'Monday', 2 => 'Tuesday', 3 => 'Wednesday', 4 => 'Thursday', 5 => 'Friday'];
                
                // Debug: Check what days actually have entries
                $daysWithData = [];
                foreach($finalTimetable as $entry) {
                    if (!isset($daysWithData[$entry['day']])) {
                        $daysWithData[$entry['day']] = [];
                    }
                    $daysWithData[$entry['day']][] = $entry['section'];
                }
                
                // Debug: Log sections by grade to see what we have
                error_log("Sections by grade: " . json_encode(array_keys($sectionsByGrade)));
                foreach($sectionsByGrade as $grade => $sections) {
                    error_log("Grade {$grade} has sections: " . json_encode(array_keys($sections)));
                    foreach($sections as $section => $entries) {
                        error_log("Section {$section} has " . count($entries) . " entries");
                        $entryDays = [];
                        foreach($entries as $entry) {
                            $entryDays[] = $entry['day'];
                        }
                        error_log("Section {$section} entries for days: " . json_encode(array_unique($entryDays)));
                    }
                }
              ?>
              
              <?php $__currentLoopData = $sectionsByGrade; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $grade => $sections): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="mb-6">
                  <h4 class="font-semibold text-green-800 mb-3">🎓 Grade <?php echo e($grade); ?></h4>
                  
                  <?php $__currentLoopData = $sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section => $entries): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="mb-4 border rounded-lg p-4 bg-gray-50">
                      <h5 class="font-medium text-slate-700 mb-3"><?php echo e($section); ?></h5>
                      
                      <table class="w-full text-sm border-collapse">
                        <thead class="bg-gray-100">
                          <tr>
                            <th class="text-center p-2 font-bold border border-gray-300">Period</th>
                            <th class="text-center p-2 font-semibold border border-gray-300">Time</th>
                            <?php $__currentLoopData = $days; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dayNum => $dayName): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                              <th class="text-center p-2 font-semibold border border-gray-300"><?php echo e($dayName); ?></th>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                          </tr>
                        </thead>
                        <tbody>
                          <?php $__currentLoopData = $periods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $periodNum => $periodInfo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="border-b hover:bg-gray-50">
                              <td class="p-2 font-bold text-center border border-gray-300"><?php echo e($periodNum); ?></td>
                              <td class="p-2 text-slate-600 text-xs text-center border border-gray-300 whitespace-nowrap"><?php echo e($periodInfo['time']); ?></td>
                              
                              <?php $__currentLoopData = $days; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dayNum => $dayName): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                  $cellEntry = null;
                                  foreach($entries as $entry) {
                                    if ($entry['day'] == $dayNum && $entry['period'] == $periodNum) {
                                      $cellEntry = $entry;
                                      break;
                                    }
                                  }
                                ?>
                                <td class="p-2 border border-gray-300 text-center">
                                  <?php if($cellEntry): ?>
                                    <div class="text-xs">
                                      <div class="font-bold text-slate-700"><?php echo e($cellEntry['subject']); ?></div>
                                      <div class="text-slate-600"><?php echo e($cellEntry['teacher']); ?></div>
                                    </div>
                                  <?php endif; ?>
                                </td>
                              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tr>
                          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                      </table>
                    </div>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php else: ?>
              <div class="text-center py-6">
                <div class="text-5xl mb-3">📅</div>
                <p class="text-green-700 font-semibold text-lg">No Schedule Generated Yet</p>
                <p class="text-sm text-slate-600 mt-1">Generate a schedule from the <a href="/admin/schedule-maker/scheduler" class="text-green-600 hover:underline font-medium">scheduler</a> to see the final timetable here.</p>
                <a href="/admin/schedule-maker/scheduler" class="inline-block mt-3 px-4 py-2 bg-green-600 text-white text-sm rounded hover:bg-green-700 font-medium">
                  Go to Scheduler →
                </a>
              </div>
            <?php endif; ?>
          </div>

          <div class="mt-6 flex gap-3">
            <button class="px-4 py-2 bg-[#3b4197] text-white rounded">Export PDF (placeholder)</button>
            <button class="px-4 py-2 border rounded">Open full timetable</button>
          </div>
        </div>
      </main>
      <script>
          function filterTimetable() {
            console.log('=== filterTimetable called ===');
            
            const selectedLevel = document.getElementById('yearFilter').value;
            const selectedSection = document.getElementById('sectionFilter').value;
            const gradeDivs = document.querySelectorAll('.mb-6');
            
            console.log('Selected level:', selectedLevel);
            console.log('Selected section:', selectedSection);
            console.log('Total grade divs to process:', gradeDivs.length);
            
            // Update section dropdown based on selected grade level
            updateSectionDropdown(selectedLevel, gradeDivs);
            
            // Simple filtering logic
            gradeDivs.forEach((div, index) => {
              const gradeTitle = div.querySelector('h4').textContent.trim();
              const sectionDivs = div.querySelectorAll('.mb-4.border.rounded-lg');
              
              // Hide everything first
              div.style.display = 'none';
              sectionDivs.forEach(sectionDiv => {
                sectionDiv.style.display = 'none';
              });
              
              // Show based on filters
              if (selectedLevel === 'all') {
                // Show all grades
                div.style.display = '';
                if (selectedSection === 'all') {
                  // Show all sections
                  sectionDivs.forEach(sectionDiv => {
                    sectionDiv.style.display = '';
                  });
                } else {
                  // Show specific section across all grades
                  sectionDivs.forEach(sectionDiv => {
                    const sectionTitle = sectionDiv.querySelector('h5').textContent.trim();
                    if (sectionTitle === selectedSection) {
                      sectionDiv.style.display = '';
                    }
                  });
                }
              } else {
                // Show specific grade
                if (gradeTitle.includes(`${selectedLevel}`)) {
                  div.style.display = '';
                  if (selectedSection === 'all') {
                    // Show all sections in this grade
                    sectionDivs.forEach(sectionDiv => {
                      sectionDiv.style.display = '';
                    });
                  } else {
                    // Show specific section within this grade
                    sectionDivs.forEach(sectionDiv => {
                      const sectionTitle = sectionDiv.querySelector('h5').textContent.trim();
                      if (sectionTitle === selectedSection) {
                        sectionDiv.style.display = '';
                      }
                    });
                  }
                }
              }
            });
          }
          
          function updateSectionDropdown(selectedLevel, gradeDivs) {
            const sectionDropdown = document.getElementById('sectionFilter');
            
            // Store current selection
            const currentSelection = sectionDropdown.value;
            
            // Clear dropdown
            sectionDropdown.innerHTML = '<option value="all">All Sections</option>';
            
            // Get sections for selected grade level
            let sections = [];
            
            if (selectedLevel === 'all') {
              // All sections from all grades
              gradeDivs.forEach(div => {
                const sectionDivs = div.querySelectorAll('.mb-4.border.rounded-lg');
                sectionDivs.forEach(sectionDiv => {
                  const sectionTitle = sectionDiv.querySelector('h5').textContent.trim();
                  if (sectionTitle && !sections.includes(sectionTitle)) {
                    sections.push(sectionTitle);
                  }
                });
              });
            } else {
              // Only sections from selected grade
              gradeDivs.forEach(div => {
                const gradeTitle = div.querySelector('h4').textContent.trim();
                if (gradeTitle.includes(`${selectedLevel}`)) {
                  const sectionDivs = div.querySelectorAll('.mb-4.border.rounded-lg');
                  sectionDivs.forEach(sectionDiv => {
                    const sectionTitle = sectionDiv.querySelector('h5').textContent.trim();
                    if (sectionTitle && !sections.includes(sectionTitle)) {
                      sections.push(sectionTitle);
                    }
                  });
                }
              });
            }
            
            // Add sections to dropdown
            sections.forEach(section => {
              sectionDropdown.innerHTML += `<option value="${section}">${section}</option>`;
            });
            
            // Restore previous selection if it still exists
            if (currentSelection && currentSelection !== 'all' && sections.includes(currentSelection)) {
              sectionDropdown.value = currentSelection;
            }
            
            // Always enable section dropdown
            sectionDropdown.disabled = false;
          }
        
        // Auto-filter on page load
        document.addEventListener('DOMContentLoaded', function() {
          console.log('=== DOM Content Loaded ===');
          filterTimetable();
        });
      </script>
    </div>
  </body>
</html>
<?php /**PATH C:\Users\Admin\TuroTugma\resources\views\dashboard.blade.php ENDPATH**/ ?>