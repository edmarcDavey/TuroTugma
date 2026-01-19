<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 p-6">
  <div class="max-w-7xl mx-auto">
    
    <!-- Header -->
    <div class="mb-6 flex items-center justify-between">
      <div>
        <h1 class="text-3xl font-bold text-slate-900">🏫 Sections & Subject Allocation</h1>
        <p class="text-slate-600 mt-1">Setup which subjects are assigned to each section and period</p>
      </div>
      <button class="px-6 py-3 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700">
        + New Section
      </button>
    </div>

    <!-- Two Column Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
      
      <!-- Left Sidebar: Sections List -->
      <div class="lg:col-span-1">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden sticky top-6">
          <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white p-4">
            <h2 class="font-bold">📋 Sections</h2>
            <p class="text-xs text-blue-100 mt-1">32 Total Sections</p>
          </div>
          
          <!-- Filter Tabs -->
          <div class="flex border-b">
            <button class="flex-1 px-3 py-2 text-xs font-semibold text-blue-600 border-b-2 border-blue-600 hover:bg-blue-50">
              All
            </button>
            <button class="flex-1 px-3 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50">
              JH
            </button>
            <button class="flex-1 px-3 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-50">
              SH
            </button>
          </div>

          <!-- Sections List -->
          <div class="divide-y max-h-96 overflow-y-auto">
            <!-- Section Item - Selected -->
            <div class="p-3 cursor-pointer bg-blue-50 border-l-4 border-blue-600">
              <div class="font-semibold text-slate-900 text-sm">Grade 7-Rizal</div>
              <div class="text-xs text-slate-500 mt-1">Regular Section</div>
              <div class="text-xs text-blue-600 font-bold mt-1">8/8 subjects assigned</div>
            </div>
            <!-- Section Item -->
            <div class="p-3 cursor-pointer hover:bg-slate-50">
              <div class="font-semibold text-slate-900 text-sm">Grade 7-Bonifacio</div>
              <div class="text-xs text-slate-500 mt-1">Regular Section</div>
              <div class="text-xs text-amber-600 font-bold mt-1">6/8 subjects assigned</div>
            </div>
            <!-- Section Item -->
            <div class="p-3 cursor-pointer hover:bg-slate-50">
              <div class="font-semibold text-slate-900 text-sm">Grade 7-Art</div>
              <div class="text-xs text-slate-500 mt-1">Special Section</div>
              <div class="text-xs text-green-600 font-bold mt-1">9/9 subjects assigned</div>
            </div>
            <!-- Section Item -->
            <div class="p-3 cursor-pointer hover:bg-slate-50">
              <div class="font-semibold text-slate-900 text-sm">Grade 8-Aguinaldo</div>
              <div class="text-xs text-slate-500 mt-1">Regular Section</div>
              <div class="text-xs text-green-600 font-bold mt-1">8/8 subjects assigned</div>
            </div>
            <!-- Section Item - SH -->
            <div class="p-3 cursor-pointer hover:bg-slate-50">
              <div class="font-semibold text-slate-900 text-sm">Grade 11-STEM</div>
              <div class="text-xs text-slate-500 mt-1">SH STEM Track</div>
              <div class="text-xs text-amber-600 font-bold mt-1">10/12 subjects assigned</div>
            </div>
            <!-- Section Item - SH -->
            <div class="p-3 cursor-pointer hover:bg-slate-50">
              <div class="font-semibold text-slate-900 text-sm">Grade 11-ABM</div>
              <div class="text-xs text-slate-500 mt-1">SH ABM Track</div>
              <div class="text-xs text-red-600 font-bold mt-1">5/12 subjects assigned</div>
            </div>
            <!-- Section Item - SH -->
            <div class="p-3 cursor-pointer hover:bg-slate-50">
              <div class="font-semibold text-slate-900 text-sm">Grade 12-STEM</div>
              <div class="text-xs text-slate-500 mt-1">SH STEM Track</div>
              <div class="text-xs text-green-600 font-bold mt-1">12/12 subjects assigned</div>
            </div>
          </div>
        </div>
      </div>

      <!-- Right Content Area: Section Details -->
      <div class="lg:col-span-3">
        <div class="bg-white rounded-lg shadow-lg">
          <!-- Section Header -->
          <div class="bg-gradient-to-r from-blue-50 to-blue-100 border-b p-6">
            <div class="flex items-center justify-between">
              <div>
                <h2 class="text-2xl font-bold text-slate-900">Grade 7-Rizal</h2>
                <p class="text-slate-600 mt-1">
                  <span class="inline-block mr-4">📚 Regular Section</span>
                  <span class="inline-block mr-4">👥 38 Students</span>
                  <span class="inline-block">🎓 Grade 7</span>
                </p>
              </div>
              <button class="px-4 py-2 bg-slate-200 text-slate-700 rounded font-semibold hover:bg-slate-300">
                ✏️ Edit
              </button>
            </div>
          </div>

          <!-- Section Content: Subject Allocation Table -->
          <div class="p-6">
            <h3 class="text-lg font-bold text-slate-900 mb-4">📍 Subject Allocation</h3>
            <p class="text-sm text-slate-600 mb-6">Assign subjects to periods. The teacher in Period 1 becomes the class adviser.</p>

            <div class="overflow-x-auto border rounded-lg">
              <table class="w-full text-sm">
                <thead class="bg-slate-100 border-b">
                  <tr>
                    <th class="px-4 py-3 text-left font-bold text-slate-700">Period</th>
                    <th class="px-4 py-3 text-left font-bold text-slate-700">Time</th>
                    <th class="px-4 py-3 text-left font-bold text-slate-700">Subject</th>
                    <th class="px-4 py-3 text-left font-bold text-slate-700">Status</th>
                    <th class="px-4 py-3 text-left font-bold text-slate-700">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <!-- Row -->
                  <tr class="border-b hover:bg-slate-50">
                    <td class="px-4 py-3 font-semibold">Period 1</td>
                    <td class="px-4 py-3 text-slate-600">08:00 - 08:45</td>
                    <td class="px-4 py-3">
                      <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs font-bold">Mathematics</span>
                    </td>
                    <td class="px-4 py-3">
                      <span class="inline-flex items-center gap-1 text-xs font-bold text-green-700">
                        ✓ Assigned <span class="text-green-600">[Adviser]</span>
                      </span>
                    </td>
                    <td class="px-4 py-3">
                      <button class="text-blue-600 hover:text-blue-800 text-xs font-semibold">Change</button>
                    </td>
                  </tr>
                  <!-- Row -->
                  <tr class="border-b hover:bg-slate-50">
                    <td class="px-4 py-3 font-semibold">Period 2</td>
                    <td class="px-4 py-3 text-slate-600">08:45 - 09:30</td>
                    <td class="px-4 py-3">
                      <span class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-xs font-bold">English Language</span>
                    </td>
                    <td class="px-4 py-3">
                      <span class="inline-flex items-center gap-1 text-xs font-bold text-green-700">
                        ✓ Assigned
                      </span>
                    </td>
                    <td class="px-4 py-3">
                      <button class="text-blue-600 hover:text-blue-800 text-xs font-semibold">Change</button>
                    </td>
                  </tr>
                  <!-- Row -->
                  <tr class="border-b hover:bg-slate-50">
                    <td class="px-4 py-3 font-semibold">Period 3</td>
                    <td class="px-4 py-3 text-slate-600">09:30 - 10:15</td>
                    <td class="px-4 py-3">
                      <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-bold">Science</span>
                    </td>
                    <td class="px-4 py-3">
                      <span class="inline-flex items-center gap-1 text-xs font-bold text-green-700">
                        ✓ Assigned
                      </span>
                    </td>
                    <td class="px-4 py-3">
                      <button class="text-blue-600 hover:text-blue-800 text-xs font-semibold">Change</button>
                    </td>
                  </tr>
                  <!-- Row -->
                  <tr class="border-b hover:bg-slate-50">
                    <td class="px-4 py-3 font-semibold">Period 4</td>
                    <td class="px-4 py-3 text-slate-600">10:15 - 11:00</td>
                    <td class="px-4 py-3">
                      <span class="bg-amber-100 text-amber-800 px-3 py-1 rounded-full text-xs font-bold">Filipino</span>
                    </td>
                    <td class="px-4 py-3">
                      <span class="inline-flex items-center gap-1 text-xs font-bold text-green-700">
                        ✓ Assigned
                      </span>
                    </td>
                    <td class="px-4 py-3">
                      <button class="text-blue-600 hover:text-blue-800 text-xs font-semibold">Change</button>
                    </td>
                  </tr>
                  <!-- Row -->
                  <tr class="border-b hover:bg-slate-50">
                    <td class="px-4 py-3 font-semibold">Period 5</td>
                    <td class="px-4 py-3 text-slate-600">11:00 - 11:45</td>
                    <td class="px-4 py-3">
                      <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-xs font-bold">Social Studies</span>
                    </td>
                    <td class="px-4 py-3">
                      <span class="inline-flex items-center gap-1 text-xs font-bold text-green-700">
                        ✓ Assigned
                      </span>
                    </td>
                    <td class="px-4 py-3">
                      <button class="text-blue-600 hover:text-blue-800 text-xs font-semibold">Change</button>
                    </td>
                  </tr>
                  <!-- Row -->
                  <tr class="border-b hover:bg-slate-50">
                    <td class="px-4 py-3 font-semibold">Period 6</td>
                    <td class="px-4 py-3 text-slate-600">11:45 - 12:30</td>
                    <td class="px-4 py-3">
                      <span class="bg-indigo-100 text-indigo-800 px-3 py-1 rounded-full text-xs font-bold">Physical Education</span>
                    </td>
                    <td class="px-4 py-3">
                      <span class="inline-flex items-center gap-1 text-xs font-bold text-green-700">
                        ✓ Assigned
                      </span>
                    </td>
                    <td class="px-4 py-3">
                      <button class="text-blue-600 hover:text-blue-800 text-xs font-semibold">Change</button>
                    </td>
                  </tr>
                  <!-- Row -->
                  <tr class="border-b hover:bg-slate-50">
                    <td class="px-4 py-3 font-semibold">Period 7</td>
                    <td class="px-4 py-3 text-slate-600">12:30 - 01:15</td>
                    <td class="px-4 py-3">
                      <span class="bg-cyan-100 text-cyan-800 px-3 py-1 rounded-full text-xs font-bold">Values Education</span>
                    </td>
                    <td class="px-4 py-3">
                      <span class="inline-flex items-center gap-1 text-xs font-bold text-green-700">
                        ✓ Assigned
                      </span>
                    </td>
                    <td class="px-4 py-3">
                      <button class="text-blue-600 hover:text-blue-800 text-xs font-semibold">Change</button>
                    </td>
                  </tr>
                  <!-- Row -->
                  <tr class="border-b hover:bg-slate-50">
                    <td class="px-4 py-3 font-semibold">Period 8</td>
                    <td class="px-4 py-3 text-slate-600">01:15 - 02:00</td>
                    <td class="px-4 py-3">
                      <span class="bg-emerald-100 text-emerald-800 px-3 py-1 rounded-full text-xs font-bold">TLE</span>
                    </td>
                    <td class="px-4 py-3">
                      <span class="inline-flex items-center gap-1 text-xs font-bold text-green-700">
                        ✓ Assigned
                      </span>
                    </td>
                    <td class="px-4 py-3">
                      <button class="text-blue-600 hover:text-blue-800 text-xs font-semibold">Change</button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <!-- Summary -->
            <div class="mt-6 p-4 bg-green-50 border border-green-200 rounded-lg flex items-start gap-3">
              <span class="text-2xl">✓</span>
              <div>
                <h4 class="font-bold text-green-900">All subjects assigned</h4>
                <p class="text-sm text-green-800 mt-1">8 out of 8 subjects are allocated to periods. Ready for teacher assignment.</p>
              </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-6 flex gap-3">
              <button class="px-6 py-2 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700">
                💾 Save
              </button>
              <button class="px-6 py-2 bg-slate-200 text-slate-700 rounded-lg font-semibold hover:bg-slate-300">
                Cancel
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Admin\TuroTugma\resources\views\admin\scheduling\sections.blade.php ENDPATH**/ ?>