

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 p-6">
  <div class="max-w-7xl mx-auto">
    
    <!-- Header -->
    <div class="mb-6">
      <a href="#" class="text-blue-600 text-sm font-semibold mb-3 inline-block">← Back to Schedules</a>
      <h1 class="text-3xl font-bold text-slate-900">👨‍🏫 Teacher Schedule: Mr. Cruz</h1>
      <p class="text-slate-600 mt-1">Mathematics Teacher | All Assignments | SY 2024-2025 (Published)</p>
    </div>

    <!-- Teacher Info Card -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6 grid grid-cols-1 md:grid-cols-5 gap-6">
      <div>
        <div class="text-sm text-slate-600 font-semibold">Employee ID</div>
        <div class="text-lg font-bold text-slate-900 mt-1">T12345</div>
      </div>
      <div>
        <div class="text-sm text-slate-600 font-semibold">Designation</div>
        <div class="text-lg font-bold text-slate-900 mt-1">Teacher I</div>
      </div>
      <div>
        <div class="text-sm text-slate-600 font-semibold">Subject</div>
        <div class="text-lg font-bold text-blue-600 mt-1">Mathematics</div>
      </div>
      <div>
        <div class="text-sm text-slate-600 font-semibold">Workload</div>
        <div class="text-lg font-bold text-green-600 mt-1">18/24 hrs</div>
        <div class="text-xs text-slate-500 mt-1">75% capacity</div>
      </div>
      <div>
        <div class="text-sm text-slate-600 font-semibold">Class Adviser</div>
        <div class="text-lg font-bold text-purple-600 mt-1">Grade 7-Rizal</div>
        <div class="text-xs text-slate-500 mt-1">Period 1 duty</div>
      </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow p-4 mb-6 flex items-center gap-4">
      <div>
        <label class="text-sm font-semibold text-slate-700">Teacher</label>
        <select class="mt-1 px-3 py-2 border border-slate-300 rounded">
          <option selected>Mr. Cruz</option>
          <option>Ms. Santos</option>
          <option>Mr. Reyes</option>
          <option>Ms. Garcia</option>
        </select>
      </div>
      <div>
        <label class="text-sm font-semibold text-slate-700">Days</label>
        <select class="mt-1 px-3 py-2 border border-slate-300 rounded">
          <option selected>Monday - Friday</option>
          <option>Monday - Thursday</option>
          <option>Monday Only</option>
        </select>
      </div>
      <div>
        <label class="text-sm font-semibold text-slate-700">Session</label>
        <select class="mt-1 px-3 py-2 border border-slate-300 rounded">
          <option selected>Regular</option>
          <option>Shortened</option>
        </select>
      </div>
      <div class="flex-1"></div>
      <button class="px-4 py-2 bg-blue-600 text-white rounded font-semibold hover:bg-blue-700">
        📥 Export
      </button>
    </div>

    <!-- Teacher Schedule Grid -->
    <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
      <div class="overflow-x-auto">
        <table class="w-full text-sm">
          <thead>
            <tr class="bg-slate-100 border-b">
              <th class="px-4 py-3 text-left font-bold text-slate-700 w-32">Time / Period</th>
              <th class="px-4 py-3 text-center font-bold text-slate-700 bg-blue-50">Monday</th>
              <th class="px-4 py-3 text-center font-bold text-slate-700">Tuesday</th>
              <th class="px-4 py-3 text-center font-bold text-slate-700 bg-blue-50">Wednesday</th>
              <th class="px-4 py-3 text-center font-bold text-slate-700">Thursday</th>
              <th class="px-4 py-3 text-center font-bold text-slate-700 bg-blue-50">Friday</th>
            </tr>
          </thead>
          <tbody>
            <!-- Period 1 -->
            <tr class="border-b hover:bg-blue-50">
              <td class="px-4 py-3 font-semibold text-slate-900">
                <div class="text-base">Period 1</div>
                <div class="text-xs text-slate-500">08:00-08:45</div>
              </td>
              <td class="px-4 py-3 text-center">
                <div class="bg-blue-100 rounded-lg p-3 text-blue-900">
                  <div class="font-bold">Grade 7-Rizal</div>
                  <div class="text-xs font-semibold">Mathematics</div>
                  <div class="text-xs text-blue-700 font-bold mt-1">[Adviser]</div>
                </div>
              </td>
              <td class="px-4 py-3 text-center">
                <div class="bg-blue-100 rounded-lg p-3 text-blue-900">
                  <div class="font-bold">Grade 7-Rizal</div>
                  <div class="text-xs font-semibold">Mathematics</div>
                </div>
              </td>
              <td class="px-4 py-3 text-center">
                <div class="bg-blue-100 rounded-lg p-3 text-blue-900">
                  <div class="font-bold">Grade 7-Rizal</div>
                  <div class="text-xs font-semibold">Mathematics</div>
                </div>
              </td>
              <td class="px-4 py-3 text-center">
                <div class="bg-blue-100 rounded-lg p-3 text-blue-900">
                  <div class="font-bold">Grade 7-Rizal</div>
                  <div class="text-xs font-semibold">Mathematics</div>
                </div>
              </td>
              <td class="px-4 py-3 text-center">
                <div class="bg-blue-100 rounded-lg p-3 text-blue-900">
                  <div class="font-bold">Grade 7-Rizal</div>
                  <div class="text-xs font-semibold">Mathematics</div>
                </div>
              </td>
            </tr>

            <!-- Period 2 -->
            <tr class="border-b hover:bg-slate-50">
              <td class="px-4 py-3 font-semibold text-slate-900">
                <div class="text-base">Period 2</div>
                <div class="text-xs text-slate-500">08:45-09:30</div>
              </td>
              <td class="px-4 py-3 text-center">
                <div class="bg-slate-100 rounded-lg p-3 text-slate-600">
                  <div class="font-bold">Free</div>
                  <div class="text-xs font-semibold">No class</div>
                </div>
              </td>
              <td class="px-4 py-3 text-center">
                <div class="bg-indigo-100 rounded-lg p-3 text-indigo-900">
                  <div class="font-bold">Grade 8-Aguinaldo</div>
                  <div class="text-xs font-semibold">Mathematics</div>
                </div>
              </td>
              <td class="px-4 py-3 text-center">
                <div class="bg-indigo-100 rounded-lg p-3 text-indigo-900">
                  <div class="font-bold">Grade 8-Aguinaldo</div>
                  <div class="text-xs font-semibold">Mathematics</div>
                </div>
              </td>
              <td class="px-4 py-3 text-center">
                <div class="bg-indigo-100 rounded-lg p-3 text-indigo-900">
                  <div class="font-bold">Grade 8-Aguinaldo</div>
                  <div class="text-xs font-semibold">Mathematics</div>
                </div>
              </td>
              <td class="px-4 py-3 text-center">
                <div class="bg-slate-100 rounded-lg p-3 text-slate-600">
                  <div class="font-bold">Free</div>
                  <div class="text-xs font-semibold">No class</div>
                </div>
              </td>
            </tr>

            <!-- Period 3 -->
            <tr class="border-b hover:bg-slate-50">
              <td class="px-4 py-3 font-semibold text-slate-900">
                <div class="text-base">Period 3</div>
                <div class="text-xs text-slate-500">09:30-10:15</div>
              </td>
              <td class="px-4 py-3 text-center">
                <div class="bg-purple-100 rounded-lg p-3 text-purple-900">
                  <div class="font-bold">Grade 9-Mabini</div>
                  <div class="text-xs font-semibold">Mathematics</div>
                </div>
              </td>
              <td class="px-4 py-3 text-center">
                <div class="bg-purple-100 rounded-lg p-3 text-purple-900">
                  <div class="font-bold">Grade 9-Mabini</div>
                  <div class="text-xs font-semibold">Mathematics</div>
                </div>
              </td>
              <td class="px-4 py-3 text-center">
                <div class="bg-purple-100 rounded-lg p-3 text-purple-900">
                  <div class="font-bold">Grade 9-Mabini</div>
                  <div class="text-xs font-semibold">Mathematics</div>
                </div>
              </td>
              <td class="px-4 py-3 text-center">
                <div class="bg-slate-100 rounded-lg p-3 text-slate-600">
                  <div class="font-bold">Free</div>
                  <div class="text-xs font-semibold">No class</div>
                </div>
              </td>
              <td class="px-4 py-3 text-center">
                <div class="bg-purple-100 rounded-lg p-3 text-purple-900">
                  <div class="font-bold">Grade 9-Mabini</div>
                  <div class="text-xs font-semibold">Mathematics</div>
                </div>
              </td>
            </tr>

            <!-- Period 4 -->
            <tr class="border-b hover:bg-slate-50">
              <td class="px-4 py-3 font-semibold text-slate-900">
                <div class="text-base">Period 4</div>
                <div class="text-xs text-slate-500">10:15-11:00</div>
              </td>
              <td class="px-4 py-3 text-center">
                <div class="bg-pink-100 rounded-lg p-3 text-pink-900">
                  <div class="font-bold">Grade 10-Lapu</div>
                  <div class="text-xs font-semibold">Mathematics</div>
                </div>
              </td>
              <td class="px-4 py-3 text-center">
                <div class="bg-pink-100 rounded-lg p-3 text-pink-900">
                  <div class="font-bold">Grade 10-Lapu</div>
                  <div class="text-xs font-semibold">Mathematics</div>
                </div>
              </td>
              <td class="px-4 py-3 text-center">
                <div class="bg-pink-100 rounded-lg p-3 text-pink-900">
                  <div class="font-bold">Grade 10-Lapu</div>
                  <div class="text-xs font-semibold">Mathematics</div>
                </div>
              </td>
              <td class="px-4 py-3 text-center">
                <div class="bg-pink-100 rounded-lg p-3 text-pink-900">
                  <div class="font-bold">Grade 10-Lapu</div>
                  <div class="text-xs font-semibold">Mathematics</div>
                </div>
              </td>
              <td class="px-4 py-3 text-center">
                <div class="bg-pink-100 rounded-lg p-3 text-pink-900">
                  <div class="font-bold">Grade 10-Lapu</div>
                  <div class="text-xs font-semibold">Mathematics</div>
                </div>
              </td>
            </tr>

            <!-- LUNCH BREAK -->
            <tr class="border-b bg-slate-200">
              <td class="px-4 py-3 font-bold text-slate-700 text-center" colspan="6">
                🍽️ LUNCH BREAK (11:00 - 12:30)
              </td>
            </tr>

            <!-- Period 5 -->
            <tr class="border-b hover:bg-slate-50">
              <td class="px-4 py-3 font-semibold text-slate-900">
                <div class="text-base">Period 5</div>
                <div class="text-xs text-slate-500">12:30-01:15</div>
              </td>
              <td class="px-4 py-3 text-center">
                <div class="bg-slate-100 rounded-lg p-3 text-slate-600">
                  <div class="font-bold">Free</div>
                  <div class="text-xs font-semibold">No class</div>
                </div>
              </td>
              <td class="px-4 py-3 text-center">
                <div class="bg-slate-100 rounded-lg p-3 text-slate-600">
                  <div class="font-bold">Free</div>
                  <div class="text-xs font-semibold">No class</div>
                </div>
              </td>
              <td class="px-4 py-3 text-center">
                <div class="bg-slate-100 rounded-lg p-3 text-slate-600">
                  <div class="font-bold">Free</div>
                  <div class="text-xs font-semibold">No class</div>
                </div>
              </td>
              <td class="px-4 py-3 text-center">
                <div class="bg-slate-100 rounded-lg p-3 text-slate-600">
                  <div class="font-bold">Free</div>
                  <div class="text-xs font-semibold">No class</div>
                </div>
              </td>
              <td class="px-4 py-3 text-center">
                <div class="bg-slate-100 rounded-lg p-3 text-slate-600">
                  <div class="font-bold">Free</div>
                  <div class="text-xs font-semibold">No class</div>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Workload Summary -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      
      <!-- Summary Statistics -->
      <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-bold text-slate-900 mb-4">📊 Workload Summary</h3>
        
        <div class="space-y-4">
          <div>
            <div class="flex justify-between text-sm mb-2">
              <span class="text-slate-600">Total Weekly Load</span>
              <span class="font-bold text-slate-900">18 hours</span>
            </div>
            <div class="w-full bg-slate-200 rounded-full h-3 overflow-hidden">
              <div class="h-full bg-green-500" style="width: 75%"></div>
            </div>
            <div class="text-xs text-slate-500 mt-1">75% of 24-hour capacity</div>
          </div>

          <div>
            <div class="flex justify-between text-sm mb-2">
              <span class="text-slate-600">Sections Taught</span>
              <span class="font-bold text-slate-900">4 sections</span>
            </div>
            <div class="flex gap-2 mt-1">
              <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded">Grade 7-Rizal</span>
              <span class="bg-indigo-100 text-indigo-800 text-xs px-2 py-1 rounded">Grade 8-Aguinaldo</span>
              <span class="bg-purple-100 text-purple-800 text-xs px-2 py-1 rounded">Grade 9-Mabini</span>
              <span class="bg-pink-100 text-pink-800 text-xs px-2 py-1 rounded">Grade 10-Lapu</span>
            </div>
          </div>

          <div>
            <div class="flex justify-between text-sm mb-2">
              <span class="text-slate-600">Free Periods Per Week</span>
              <span class="font-bold text-slate-900">7 periods</span>
            </div>
            <div class="text-xs text-slate-500">Available for preparation/admin</div>
          </div>

          <div>
            <div class="flex justify-between text-sm mb-2">
              <span class="text-slate-600">Consecutive Classes (Max)</span>
              <span class="font-bold text-slate-900">2 periods</span>
            </div>
            <div class="text-xs text-slate-500">Well balanced, good for pedagogy</div>
          </div>
        </div>
      </div>

      <!-- Class Details -->
      <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-bold text-slate-900 mb-4">👥 Class Assignments</h3>
        
        <div class="space-y-3">
          <div class="border rounded-lg p-3">
            <div class="flex justify-between items-center">
              <div>
                <div class="font-bold text-slate-900">Grade 7-Rizal</div>
                <div class="text-sm text-slate-600">Period 1-5 (5 hrs/week)</div>
              </div>
              <div class="text-green-600 font-bold text-xs">[Adviser]</div>
            </div>
          </div>
          <div class="border rounded-lg p-3">
            <div class="font-bold text-slate-900">Grade 8-Aguinaldo</div>
            <div class="text-sm text-slate-600">Period 2-4 (3 hrs/week)</div>
          </div>
          <div class="border rounded-lg p-3">
            <div class="font-bold text-slate-900">Grade 9-Mabini</div>
            <div class="text-sm text-slate-600">Period 3 (3 hrs/week)</div>
          </div>
          <div class="border rounded-lg p-3">
            <div class="font-bold text-slate-900">Grade 10-Lapu</div>
            <div class="text-sm text-slate-600">Period 4 (5 hrs/week)</div>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\TuroTugma\resources\views\admin\scheduling\teacher-schedule.blade.php ENDPATH**/ ?>