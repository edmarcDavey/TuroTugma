

<?php $__env->startSection('title','Schedule Maker - Settings'); ?>
<?php $__env->startSection('heading','Schedule Maker - Settings'); ?>

<?php $__env->startSection('content'); ?>

<div class="min-h-screen bg-white">
  <div class="max-w-7xl mx-auto p-6">

    <!-- Demo Notice -->
    <div class="mb-6 p-4 bg-amber-50 border border-amber-200 rounded-lg">
      <p class="text-sm text-amber-800"><strong>ℹ️ Demo/Mock UI:</strong> This configuration interface demonstrates how scheduling settings would work. No data is saved or enforced yet.</p>
    </div>

    <!-- Tabs Navigation -->
    <div class="border-b bg-slate-50 flex">
      <button data-tab="jh" class="settings-tab flex-1 px-6 py-4 font-semibold border-b-2 border-blue-600 text-blue-600 hover:bg-blue-50">
        📘 Junior High Configuration
      </button>
      <button data-tab="sh" class="settings-tab flex-1 px-6 py-4 font-semibold text-slate-500 hover:text-slate-700 hover:bg-slate-100">
        📗 Senior High Configuration
      </button>
      <button data-tab="constraints" class="settings-tab flex-1 px-6 py-4 font-semibold text-slate-500 hover:text-slate-700 hover:bg-slate-100">
        ⚠️ Constraints & Rules
      </button>
    </div>

    <!-- Tab Content: Junior High -->
    <div id="tab-jh" class="settings-tab-content p-6">
      <h3 class="text-lg font-bold text-slate-900 mb-4">📘 Junior High Configuration</h3>

      <!-- Session Type Selector -->
      <div class="mb-6 flex gap-3">
        <label class="flex-1 cursor-pointer">
          <input type="radio" name="jh-session" value="regular" checked class="peer hidden" />
          <div class="p-3 border rounded-lg bg-white peer-checked:border-blue-600 peer-checked:bg-blue-50 hover:border-blue-400">
            <div class="font-semibold text-slate-900">📚 Regular</div>
            <div class="text-xs text-slate-500">Tue-Fri</div>
          </div>
        </label>
        <label class="flex-1 cursor-pointer">
          <input type="radio" name="jh-session" value="shortened" class="peer hidden" />
          <div class="p-3 border rounded-lg bg-white peer-checked:border-blue-600 peer-checked:bg-blue-50 hover:border-blue-400">
            <div class="font-semibold text-slate-900">⚡ Shortened</div>
            <div class="text-xs text-slate-500">Monday</div>
          </div>
        </label>
      </div>

      <!-- Regular Session Configuration -->
      <div id="jh-regular-config" class="session-config space-y-4">
        
        <!-- School Calendar -->
        <div class="border rounded-lg p-4 bg-white">
          <h4 class="font-semibold mb-3 text-slate-800">📅 School Calendar</h4>
          <div class="flex gap-2">
            <div class="w-12 h-12 rounded-full border-2 bg-slate-100 border-slate-300 text-slate-400 flex items-center justify-center text-xs font-medium">Mon</div>
            <div class="w-12 h-12 rounded-full border-2 bg-blue-500 border-blue-600 text-white flex items-center justify-center text-xs font-medium">Tue</div>
            <div class="w-12 h-12 rounded-full border-2 bg-blue-500 border-blue-600 text-white flex items-center justify-center text-xs font-medium">Wed</div>
            <div class="w-12 h-12 rounded-full border-2 bg-blue-500 border-blue-600 text-white flex items-center justify-center text-xs font-medium">Thu</div>
            <div class="w-12 h-12 rounded-full border-2 bg-blue-500 border-blue-600 text-white flex items-center justify-center text-xs font-medium">Fri</div>
            <div class="w-12 h-12 rounded-full border-2 bg-white border-slate-300 text-slate-500 flex items-center justify-center text-xs font-medium">Sat</div>
            <div class="w-12 h-12 rounded-full border-2 bg-white border-slate-300 text-slate-500 flex items-center justify-center text-xs font-medium">Sun</div>
          </div>
        </div>

        <!-- Period Structure -->
        <div class="border rounded-lg p-4 bg-white">
          <h4 class="font-semibold mb-3 text-slate-800">🕐 Period Structure</h4>
          <div class="grid grid-cols-2 gap-4 mb-3">
            <div>
              <label class="text-xs text-slate-600">Class Duration</label>
              <div class="flex items-center gap-2">
                <input type="number" value="60" class="period-duration input w-20 text-sm" data-config="jh-regular" min="30" max="90" />
                <span class="text-xs text-slate-600">min</span>
              </div>
            </div>
            <div>
              <label class="text-xs text-slate-600">Total Periods</label>
              <input type="number" value="9" class="period-count input w-20 text-sm" data-config="jh-regular" min="5" max="12" />
            </div>
          </div>
          <div class="bg-slate-50 p-3 rounded text-xs space-y-1" id="jh-regular-schedule">
            <div class="grid grid-cols-3 gap-2">
              <div><span class="font-semibold">P1:</span> 7:30-8:30</div>
              <div><span class="font-semibold">P2:</span> 8:30-9:30</div>
              <div><span class="font-semibold">P3:</span> 9:30-10:30</div>
            </div>
            <div class="text-amber-700 bg-amber-50 px-2 py-1 rounded">☕ Break (20min)</div>
            <div class="grid grid-cols-2 gap-2">
              <div><span class="font-semibold">P4:</span> 10:50-11:50</div>
              <div><span class="font-semibold">P5:</span> 11:50-12:50</div>
            </div>
            <div class="text-orange-700 bg-orange-50 px-2 py-1 rounded">🍱 Lunch (60min)</div>
            <div class="grid grid-cols-4 gap-2">
              <div><span class="font-semibold">P6:</span> 1:50-2:50</div>
              <div><span class="font-semibold">P7:</span> 2:50-3:50</div>
              <div><span class="font-semibold">P8:</span> 3:50-4:50</div>
              <div><span class="font-semibold">P9:</span> 4:50-5:50</div>
            </div>
          </div>
        </div>

        <!-- Break Schedule -->
        <div class="border rounded-lg p-4 bg-white">
          <h4 class="font-semibold mb-3 text-slate-800">☕ Breaks</h4>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="text-xs text-slate-600">Morning</label>
              <div class="flex items-center gap-2 text-sm">
                <span>After P</span>
                <input type="number" value="3" class="break-after input w-12 text-xs" data-config="jh-regular" data-break="morning" min="1" max="9" />
                <span>for</span>
                <input type="number" value="20" class="break-duration input w-12 text-xs" data-config="jh-regular" data-break="morning" min="5" max="60" />
                <span class="text-xs">min</span>
              </div>
            </div>
            <div>
              <label class="text-xs text-slate-600">Lunch</label>
              <div class="flex items-center gap-2 text-sm">
                <span>After P</span>
                <input type="number" value="5" class="break-after input w-12 text-xs" data-config="jh-regular" data-break="lunch" min="1" max="9" />
                <span>for</span>
                <input type="number" value="60" class="break-duration input w-12 text-xs" data-config="jh-regular" data-break="lunch" min="5" max="90" />
                <span class="text-xs">min</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Section Types -->
        <div class="border rounded-lg p-4 bg-white">
          <h4 class="font-semibold mb-3 text-slate-800">📋 Section Types</h4>
          <div class="grid grid-cols-2 gap-3 mb-3">
            <div class="border rounded p-3">
              <div class="flex items-center gap-2 mb-2">
                <div class="w-8 h-8 bg-blue-500 text-white rounded flex items-center justify-center font-bold text-sm">R</div>
                <div>
                  <div class="font-semibold text-sm">Regular</div>
                  <div class="text-xs text-slate-500">8 periods</div>
                </div>
              </div>
              <div class="text-xs text-slate-600">7-Saturn, 8-Jupiter</div>
            </div>
            <div class="border rounded p-3">
              <div class="flex items-center gap-2 mb-2">
                <div class="w-8 h-8 bg-purple-500 text-white rounded flex items-center justify-center font-bold text-sm">S</div>
                <div>
                  <div class="font-semibold text-sm">Special</div>
                  <div class="text-xs text-slate-500">9 periods</div>
                </div>
              </div>
              <div class="text-xs text-slate-600">7-SPA, 8-SPJ</div>
            </div>
          </div>
          <div class="overflow-x-auto">
            <table class="w-full text-xs">
              <thead class="border-b">
                <tr>
                  <th class="text-left py-2">Subject Type</th>
                  <th class="text-center py-2">Regular</th>
                  <th class="text-center py-2">Special</th>
                </tr>
              </thead>
              <tbody>
                <tr class="border-b">
                  <td class="py-2">Core</td>
                  <td class="text-center"><span class="bg-green-100 text-green-700 px-2 py-1 rounded">✓</span></td>
                  <td class="text-center"><span class="bg-green-100 text-green-700 px-2 py-1 rounded">✓</span></td>
                </tr>
                <tr class="border-b">
                  <td class="py-2">Arts</td>
                  <td class="text-center"><span class="bg-red-100 text-red-700 px-2 py-1 rounded">✗</span></td>
                  <td class="text-center"><span class="bg-green-100 text-green-700 px-2 py-1 rounded">✓</span></td>
                </tr>
                <tr>
                  <td class="py-2">Journalism</td>
                  <td class="text-center"><span class="bg-red-100 text-red-700 px-2 py-1 rounded">✗</span></td>
                  <td class="text-center"><span class="bg-green-100 text-green-700 px-2 py-1 rounded">✓</span></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

      </div>

      <!-- Shortened Session Configuration -->
      <div id="jh-shortened-config" class="session-config space-y-4 hidden">
        
        <!-- School Calendar -->
        <div class="border rounded-lg p-4 bg-white">
          <h4 class="font-semibold mb-3 text-slate-800">📅 School Calendar</h4>
          <div class="flex gap-2">
            <div class="w-12 h-12 rounded-full border-2 bg-amber-500 border-amber-600 text-white flex items-center justify-center text-xs font-medium">Mon</div>
            <div class="w-12 h-12 rounded-full border-2 bg-slate-100 border-slate-300 text-slate-400 flex items-center justify-center text-xs font-medium">Tue</div>
            <div class="w-12 h-12 rounded-full border-2 bg-slate-100 border-slate-300 text-slate-400 flex items-center justify-center text-xs font-medium">Wed</div>
            <div class="w-12 h-12 rounded-full border-2 bg-slate-100 border-slate-300 text-slate-400 flex items-center justify-center text-xs font-medium">Thu</div>
            <div class="w-12 h-12 rounded-full border-2 bg-slate-100 border-slate-300 text-slate-400 flex items-center justify-center text-xs font-medium">Fri</div>
            <div class="w-12 h-12 rounded-full border-2 bg-white border-slate-300 text-slate-500 flex items-center justify-center text-xs font-medium">Sat</div>
            <div class="w-12 h-12 rounded-full border-2 bg-white border-slate-300 text-slate-500 flex items-center justify-center text-xs font-medium">Sun</div>
          </div>
        </div>

        <!-- Period Structure -->
        <div class="border rounded-lg p-4 bg-white">
          <h4 class="font-semibold mb-3 text-slate-800">🕐 Period Structure</h4>
          <div class="grid grid-cols-2 gap-4 mb-3">
            <div>
              <label class="text-xs text-slate-600">Class Duration</label>
              <div class="flex items-center gap-2">
                <input type="number" value="50" class="period-duration input w-20 text-sm" data-config="jh-shortened" min="30" max="90" />
                <span class="text-xs text-slate-600">min</span>
              </div>
            </div>
            <div>
              <label class="text-xs text-slate-600">Total Periods</label>
              <input type="number" value="9" class="period-count input w-20 text-sm" data-config="jh-shortened" min="5" max="12" />
            </div>
          </div>
          <div class="bg-slate-50 p-3 rounded text-xs space-y-1">
            <div class="grid grid-cols-3 gap-2">
              <div><span class="font-semibold">P1:</span> 7:30-8:20</div>
              <div><span class="font-semibold">P2:</span> 8:20-9:10</div>
              <div><span class="font-semibold">P3:</span> 9:10-10:00</div>
            </div>
            <div class="text-amber-700 bg-amber-50 px-2 py-1 rounded">☕ Break (20min)</div>
            <div class="grid grid-cols-2 gap-2">
              <div><span class="font-semibold">P4:</span> 10:20-11:10</div>
              <div><span class="font-semibold">P5:</span> 11:10-12:00</div>
            </div>
            <div class="text-orange-700 bg-orange-50 px-2 py-1 rounded">🍱 Lunch (60min)</div>
            <div class="grid grid-cols-4 gap-2">
              <div><span class="font-semibold">P6:</span> 1:00-1:50</div>
              <div><span class="font-semibold">P7:</span> 1:50-2:40</div>
              <div><span class="font-semibold">P8:</span> 2:40-3:30</div>
              <div><span class="font-semibold">P9:</span> 3:30-4:20</div>
            </div>
          </div>
        </div>

        <!-- Break Schedule -->
        <div class="border rounded-lg p-4 bg-white">
          <h4 class="font-semibold mb-3 text-slate-800">☕ Breaks</h4>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="text-xs text-slate-600">Morning</label>
              <div class="flex items-center gap-2 text-sm">
                <span>After P</span>
                <input type="number" value="3" disabled class="input w-12 bg-slate-50 text-xs" />
                <span>for</span>
                <input type="number" value="20" disabled class="input w-12 bg-slate-50 text-xs" />
                <span class="text-xs">min</span>
              </div>
            </div>
            <div>
              <label class="text-xs text-slate-600">Lunch</label>
              <div class="flex items-center gap-2 text-sm">
                <span>After P</span>
                <input type="number" value="5" disabled class="input w-12 bg-slate-50 text-xs" />
                <span>for</span>
                <input type="number" value="60" disabled class="input w-12 bg-slate-50 text-xs" />
                <span class="text-xs">min</span>
              </div>
            </div>
          </div>
        </div>

      </div>

      <!-- Save Button -->
      <div class="border-t pt-4 flex justify-end gap-3">
        <button type="button" class="px-4 py-2 bg-slate-300 text-slate-500 rounded cursor-not-allowed text-sm" disabled title="Demo only">
          💾 Save JH Configuration
        </button>
      </div>
    </div>

    <!-- Tab Content: Senior High -->
    <div id="tab-sh" class="settings-tab-content hidden p-6">
      <h3 class="text-xl font-bold text-slate-900 mb-1">📗 Senior High Configuration</h3>
      <p class="text-xs text-slate-500 mb-4">Grades 11-12 scheduling parameters</p>

      <!-- Session Type Selector -->
      <div class="mb-6">
        <div class="flex gap-3">
          <label class="flex-1 cursor-pointer">
            <input type="radio" name="sh-session" value="regular" checked class="peer hidden" />
            <div class="p-3 border rounded transition-all peer-checked:border-green-600 peer-checked:bg-green-50">
              <div class="font-semibold text-sm">📚 Regular</div>
              <div class="text-xs text-slate-500">Mon-Thu</div>
            </div>
          </label>
          <label class="flex-1 cursor-pointer">
            <input type="radio" name="sh-session" value="shortened" class="peer hidden" />
            <div class="p-3 border rounded transition-all peer-checked:border-green-600 peer-checked:bg-green-50">
              <div class="font-semibold text-sm">⚡ Shortened</div>
              <div class="text-xs text-slate-500">Friday</div>
            </div>
          </label>
        </div>
      </div>

      <!-- Regular Session Configuration -->
      <div id="sh-regular-config" class="session-config space-y-4">
        
        <!-- School Calendar -->
        <div class="border rounded-lg p-4 bg-white">
          <h4 class="font-semibold mb-3 text-slate-800">📅 School Calendar</h4>
          <div class="flex gap-2">
            <div class="w-12 h-12 rounded-full border-2 bg-green-500 border-green-600 text-white flex items-center justify-center text-xs font-medium">Mon</div>
            <div class="w-12 h-12 rounded-full border-2 bg-green-500 border-green-600 text-white flex items-center justify-center text-xs font-medium">Tue</div>
            <div class="w-12 h-12 rounded-full border-2 bg-green-500 border-green-600 text-white flex items-center justify-center text-xs font-medium">Wed</div>
            <div class="w-12 h-12 rounded-full border-2 bg-green-500 border-green-600 text-white flex items-center justify-center text-xs font-medium">Thu</div>
            <div class="w-12 h-12 rounded-full border-2 bg-slate-100 border-slate-300 text-slate-400 flex items-center justify-center text-xs font-medium">Fri</div>
            <div class="w-12 h-12 rounded-full border-2 bg-white border-slate-300 text-slate-500 flex items-center justify-center text-xs font-medium">Sat</div>
            <div class="w-12 h-12 rounded-full border-2 bg-white border-slate-300 text-slate-500 flex items-center justify-center text-xs font-medium">Sun</div>
          </div>
        </div>

        <!-- Period Structure -->
        <div class="border rounded-lg p-4 bg-white">
          <h4 class="font-semibold mb-3 text-slate-800">🕐 Period Structure</h4>
          <div class="grid grid-cols-2 gap-4 mb-3">
            <div>
              <label class="text-xs text-slate-600">Class Duration</label>
              <div class="flex items-center gap-2">
                <input type="number" value="60" class="period-duration input w-20 text-sm" data-config="sh-regular" min="30" max="90" />
                <span class="text-xs text-slate-600">min</span>
              </div>
            </div>
            <div>
              <label class="text-xs text-slate-600">Total Periods</label>
              <input type="number" value="9" class="period-count input w-20 text-sm" data-config="sh-regular" min="5" max="12" />
            </div>
          </div>
          <div class="bg-slate-50 p-3 rounded text-xs space-y-1" id="sh-regular-schedule">
            <div class="grid grid-cols-3 gap-2">
              <div><span class="font-semibold">P1:</span> 7:30-8:30</div>
              <div><span class="font-semibold">P2:</span> 8:30-9:30</div>
              <div><span class="font-semibold">P3:</span> 9:30-10:30</div>
            </div>
            <div class="text-amber-700 bg-amber-50 px-2 py-1 rounded">☕ Break (20min)</div>
            <div class="grid grid-cols-2 gap-2">
              <div><span class="font-semibold">P4:</span> 10:50-11:50</div>
              <div><span class="font-semibold">P5:</span> 11:50-12:50</div>
            </div>
            <div class="text-orange-700 bg-orange-50 px-2 py-1 rounded">🍱 Lunch (60min)</div>
            <div class="grid grid-cols-2 gap-2">
              <div><span class="font-semibold">P6:</span> 1:50-2:50</div>
              <div><span class="font-semibold">P7:</span> 2:50-3:50</div>
            </div>
            <div class="text-purple-700 bg-purple-50 px-2 py-1 rounded">🌆 Afternoon (15min)</div>
            <div class="grid grid-cols-2 gap-2">
              <div><span class="font-semibold">P8:</span> 4:05-5:05</div>
              <div><span class="font-semibold">P9:</span> 5:05-6:05</div>
            </div>
          </div>
        </div>

        <!-- Break Schedule -->
        <div class="border rounded-lg p-4 bg-white">
          <h4 class="font-semibold mb-3 text-slate-800">☕ Breaks</h4>
          <div class="grid grid-cols-3 gap-4">
            <div>
              <label class="text-xs text-slate-600">Morning</label>
              <div class="flex items-center gap-2 text-sm">
                <span>P</span>
                <input type="number" value="3" class="break-after input w-10 text-xs" data-config="sh-regular" data-break="morning" min="1" max="9" />
                <span class="text-xs">20m</span>
              </div>
            </div>
            <div>
              <label class="text-xs text-slate-600">Lunch</label>
              <div class="flex items-center gap-2 text-sm">
                <span>P</span>
                <input type="number" value="5" class="break-after input w-10 text-xs" data-config="sh-regular" data-break="lunch" min="1" max="9" />
                <span class="text-xs">60m</span>
              </div>
            </div>
            <div>
              <label class="text-xs text-slate-600">Afternoon</label>
              <div class="flex items-center gap-2 text-sm">
                <span>P</span>
                <input type="number" value="7" class="break-after input w-10 text-xs" data-config="sh-regular" data-break="afternoon" min="1" max="9" />
                <span class="text-xs">15m</span>
              </div>
            </div>
          </div>
        </div>

      </div>

      <!-- Shortened Session Configuration -->
      <div id="sh-shortened-config" class="session-config space-y-4 hidden">
        
        <!-- School Calendar -->
        <div class="border rounded-lg p-4 bg-white">
          <h4 class="font-semibold mb-3 text-slate-800">📅 School Calendar</h4>
          <div class="flex gap-2">
            <div class="w-12 h-12 rounded-full border-2 bg-slate-100 border-slate-300 text-slate-400 flex items-center justify-center text-xs font-medium">Mon</div>
            <div class="w-12 h-12 rounded-full border-2 bg-slate-100 border-slate-300 text-slate-400 flex items-center justify-center text-xs font-medium">Tue</div>
            <div class="w-12 h-12 rounded-full border-2 bg-slate-100 border-slate-300 text-slate-400 flex items-center justify-center text-xs font-medium">Wed</div>
            <div class="w-12 h-12 rounded-full border-2 bg-slate-100 border-slate-300 text-slate-400 flex items-center justify-center text-xs font-medium">Thu</div>
            <div class="w-12 h-12 rounded-full border-2 bg-amber-500 border-amber-600 text-white flex items-center justify-center text-xs font-medium">Fri</div>
            <div class="w-12 h-12 rounded-full border-2 bg-white border-slate-300 text-slate-500 flex items-center justify-center text-xs font-medium">Sat</div>
            <div class="w-12 h-12 rounded-full border-2 bg-white border-slate-300 text-slate-500 flex items-center justify-center text-xs font-medium">Sun</div>
          </div>
        </div>

        <!-- Period Structure -->
        <div class="border rounded-lg p-4 bg-white">
          <h4 class="font-semibold mb-3 text-slate-800">🕐 Period Structure</h4>
          <div class="grid grid-cols-2 gap-4 mb-3">
            <div>
              <label class="text-xs text-slate-600">Class Duration</label>
              <div class="flex items-center gap-2">
                <input type="number" value="50" class="period-duration input w-20 text-sm" data-config="sh-shortened" min="30" max="90" />
                <span class="text-xs text-slate-600">min</span>
              </div>
            </div>
            <div>
              <label class="text-xs text-slate-600">Total Periods</label>
              <input type="number" value="9" class="period-count input w-20 text-sm" data-config="sh-shortened" min="5" max="12" />
            </div>
          </div>
          <div id="sh-shortened-schedule" class="bg-slate-50 p-3 rounded text-xs space-y-1">
            <div class="grid grid-cols-3 gap-2">
              <div><span class="font-semibold">P1:</span> 7:30-8:20</div>
              <div><span class="font-semibold">P2:</span> 8:20-9:10</div>
              <div><span class="font-semibold">P3:</span> 9:10-10:00</div>
            </div>
            <div class="text-amber-700 bg-amber-50 px-2 py-1 rounded">☕ Break (20min)</div>
            <div class="grid grid-cols-2 gap-2">
              <div><span class="font-semibold">P4:</span> 10:20-11:10</div>
              <div><span class="font-semibold">P5:</span> 11:10-12:00</div>
            </div>
            <div class="text-orange-700 bg-orange-50 px-2 py-1 rounded">🍱 Lunch (60min)</div>
            <div class="grid grid-cols-2 gap-2">
              <div><span class="font-semibold">P6:</span> 1:00-1:50</div>
              <div><span class="font-semibold">P7:</span> 1:50-2:40</div>
            </div>
            <div class="text-purple-700 bg-purple-50 px-2 py-1 rounded">🌆 Afternoon (15min)</div>
            <div class="grid grid-cols-2 gap-2">
              <div><span class="font-semibold">P8:</span> 2:55-3:45</div>
              <div><span class="font-semibold">P9:</span> 3:45-4:35</div>
            </div>
          </div>
        </div>

        <!-- Break Schedule -->
        <div class="border rounded-lg p-4 bg-white">
          <h4 class="font-semibold mb-3 text-slate-800">☕ Breaks</h4>
          <div class="grid grid-cols-3 gap-4">
            <div>
              <label class="text-xs text-slate-600">Morning</label>
              <div class="flex items-center gap-2 text-sm">
                <span>P</span>
                <input type="number" value="3" class="break-after input w-10 text-xs" data-config="sh-shortened" data-break="morning" min="1" max="9" />
                <span class="text-xs">20m</span>
              </div>
            </div>
            <div>
              <label class="text-xs text-slate-600">Lunch</label>
              <div class="flex items-center gap-2 text-sm">
                <span>P</span>
                <input type="number" value="5" class="break-after input w-10 text-xs" data-config="sh-shortened" data-break="lunch" min="1" max="9" />
                <span class="text-xs">60m</span>
              </div>
            </div>
            <div>
              <label class="text-xs text-slate-600">Afternoon</label>
              <div class="flex items-center gap-2 text-sm">
                <span>P</span>
                <input type="number" value="7" class="break-after input w-10 text-xs" data-config="sh-shortened" data-break="afternoon" min="1" max="9" />
                <span class="text-xs">15m</span>
              </div>
            </div>
          </div>
        </div>

      </div>

      <!-- Save Button -->
      <div class="border-t pt-4 flex justify-end gap-3">
        <button type="button" class="px-4 py-2 bg-slate-300 text-slate-500 rounded cursor-not-allowed text-sm" disabled title="Demo only">
          💾 Save SH Configuration
        </button>
      </div>
    </div>

    <!-- Tab Content: Constraints & Rules -->
    <div id="tab-constraints" class="settings-tab-content hidden p-6">
      <h3 class="text-xl font-bold text-slate-900 mb-1">⚠️ Constraints & Rules</h3>
      <p class="text-xs text-slate-500 mb-4">General constraints across all levels</p>

      <!-- Faculty Restrictions -->
      <div class="border rounded-lg p-4 bg-white mb-4">
        <h4 class="font-semibold mb-3 text-slate-800">👥 Faculty Restrictions</h4>
        <div class="bg-slate-50 p-3 rounded mb-3">
          <div class="flex items-center justify-between">
            <div class="flex-1">
              <div class="font-semibold text-sm">Dept. Heads</div>
              <div class="text-xs text-slate-600">Cannot teach P1</div>
            </div>
            <div class="flex gap-1">
              <span class="px-2 py-1 bg-red-100 text-red-700 rounded text-xs font-semibold">P1</span>
            </div>
          </div>
        </div>
        <button type="button" class="px-3 py-1.5 text-xs bg-slate-300 text-slate-500 rounded cursor-not-allowed" disabled>+ Add</button>
      </div>

      <!-- Subject Constraints -->
      <div class="border rounded-lg p-4 bg-white mb-4">
        <h4 class="font-semibold mb-3 text-slate-800">📚 Subject Constraints</h4>
        <div class="bg-slate-50 p-3 rounded mb-3">
          <div class="flex items-center justify-between">
            <div class="flex-1">
              <div class="font-semibold text-sm">Physical Education</div>
              <div class="text-xs text-slate-600">Avoid P1-P2 (heat)</div>
            </div>
            <div class="flex gap-1">
              <span class="px-2 py-1 bg-red-100 text-red-700 rounded text-xs font-semibold">P1</span>
              <span class="px-2 py-1 bg-red-100 text-red-700 rounded text-xs font-semibold">P2</span>
            </div>
          </div>
        </div>
        <button type="button" class="px-3 py-1.5 text-xs bg-slate-300 text-slate-500 rounded cursor-not-allowed" disabled>+ Add</button>
      </div>

      <!-- Load Balancing -->
      <div class="border rounded-lg p-4 bg-white mb-4">
        <h4 class="font-semibold mb-3 text-slate-800">⚖️ Load Balancing</h4>
        <div class="grid grid-cols-2 gap-4 mb-3">
          <div>
            <label class="text-xs text-slate-600">Max Consecutive</label>
            <div class="flex items-center gap-2">
              <input type="number" value="4" class="period-duration input w-20 text-sm" data-config="sh-regular" min="30" max="90" />
              <span class="text-xs text-slate-600">periods</span>
            </div>
          </div>
          <div>
            <label class="text-xs text-slate-600">Max Days/Week</label>
            <div class="flex items-center gap-2">
              <input type="number" value="6" class="period-duration input w-20 text-sm" data-config="sh-regular" min="30" max="90" />
              <span class="text-xs text-slate-600">days</span>
            </div>
          </div>
        </div>
        <div class="space-y-2 text-sm">
          <label class="flex items-center gap-2">
            <input type="checkbox" checked disabled class="rounded bg-slate-50 cursor-not-allowed" />
            <span>Balance workload</span>
          </label>
          <label class="flex items-center gap-2">
            <input type="checkbox" checked disabled class="rounded bg-slate-50 cursor-not-allowed" />
            <span>Minimize gaps</span>
          </label>
        </div>
      </div>

      <!-- Conflict Severity -->
      <div class="border rounded-lg p-4 bg-white">
        <h4 class="font-semibold mb-3 text-slate-800">🚨 Conflict Severity</h4>
        <div class="space-y-2">
          <div class="flex items-center gap-3 p-2 bg-red-50 rounded">
            <div class="w-8 h-8 bg-red-500 text-white rounded-full flex items-center justify-center font-bold text-sm flex-shrink-0">!</div>
            <div class="flex-1">
              <div class="font-semibold text-sm">Critical</div>
              <div class="text-xs text-slate-600">Double-booking, room conflicts</div>
            </div>
          </div>
          <div class="flex items-center gap-3 p-2 bg-amber-50 rounded">
            <div class="w-8 h-8 bg-amber-500 text-white rounded-full flex items-center justify-center font-bold text-sm flex-shrink-0">⚠</div>
            <div class="flex-1">
              <div class="font-semibold text-sm">High</div>
              <div class="text-xs text-slate-600">Excessive periods, break violations</div>
            </div>
          </div>
          <div class="flex items-center gap-3 p-2 bg-blue-50 rounded">
            <div class="w-8 h-8 bg-blue-500 text-white rounded-full flex items-center justify-center font-bold text-sm flex-shrink-0">i</div>
            <div class="flex-1">
              <div class="font-semibold text-sm">Medium</div>
              <div class="text-xs text-slate-600">Period preferences, minor imbalances</div>
            </div>
          </div>
        </div>
      </div>

      <!-- Save Button -->
      <div class="border-t pt-4 mt-4 flex justify-end gap-3">
        <button type="button" class="px-4 py-2 bg-slate-300 text-slate-500 rounded cursor-not-allowed text-sm" disabled title="Demo only">
          💾 Save Constraints
        </button>
      </div>
    </div>

  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  // Tab Switching
  const tabs = document.querySelectorAll('.settings-tab');
  const tabContents = document.querySelectorAll('.settings-tab-content');

  tabs.forEach(tab => {
    tab.addEventListener('click', function() {
      const targetTab = this.getAttribute('data-tab');
      
      // Update tab buttons
      tabs.forEach(t => {
        t.classList.remove('border-blue-600', 'text-blue-600', 'border-green-600', 'text-green-600', 'border-b-2');
        t.classList.add('text-slate-500');
      });
      
      if (targetTab === 'sh') {
        this.classList.remove('text-slate-500');
        this.classList.add('border-green-600', 'text-green-600', 'border-b-2');
      } else {
        this.classList.remove('text-slate-500');
        this.classList.add('border-blue-600', 'text-blue-600', 'border-b-2');
      }
      
      // Update content
      tabContents.forEach(content => content.classList.add('hidden'));
      document.getElementById('tab-' + targetTab).classList.remove('hidden');
    });
  });

  // Junior High Session Type Switching
  const jhSessionRadios = document.querySelectorAll('input[name="jh-session"]');
  jhSessionRadios.forEach(radio => {
    radio.addEventListener('change', function() {
      if (this.value === 'regular') {
        document.getElementById('jh-regular-config').classList.remove('hidden');
        document.getElementById('jh-shortened-config').classList.add('hidden');
      } else {
        document.getElementById('jh-regular-config').classList.add('hidden');
        document.getElementById('jh-shortened-config').classList.remove('hidden');
      }
    });
  });

  // Senior High Session Type Switching
  const shSessionRadios = document.querySelectorAll('input[name="sh-session"]');
  shSessionRadios.forEach(radio => {
    radio.addEventListener('change', function() {
      if (this.value === 'regular') {
        document.getElementById('sh-regular-config').classList.remove('hidden');
        document.getElementById('sh-shortened-config').classList.add('hidden');
      } else {
        document.getElementById('sh-regular-config').classList.add('hidden');
        document.getElementById('sh-shortened-config').classList.remove('hidden');
      }
    });
  });

  // Period Structure Calculator
  function formatTime(minutes) {
    const hours = Math.floor(minutes / 60);
    const mins = minutes % 60;
    return `${hours}:${mins.toString().padStart(2, '0')}`;
  }

  function calculateSchedule(config) {
    const durationInput = document.querySelector(`.period-duration[data-config="${config}"]`);
    const countInput = document.querySelector(`.period-count[data-config="${config}"]`);
    const scheduleContainer = document.getElementById(`${config}-schedule`);
    
    if (!durationInput || !countInput || !scheduleContainer) return;

    const periodDuration = parseInt(durationInput.value) || 60;
    const periodCount = parseInt(countInput.value) || 9;

    // Get break configuration
    const breakConfig = {};
    const breakInputs = document.querySelectorAll(`[data-config="${config}"].break-after`);
    breakInputs.forEach(input => {
      const breakType = input.getAttribute('data-break');
      const durationInput = document.querySelector(`[data-config="${config}"][data-break="${breakType}"].break-duration`);
      breakConfig[breakType] = {
        afterPeriod: parseInt(input.value) || 3,
        duration: parseInt(durationInput?.value) || 20
      };
    });

    // Start time: 7:30 AM = 450 minutes from midnight
    let currentTime = 7 * 60 + 30;
    let html = '';
    let periodsBeforeBreak = [];
    let lastPeriod = 0;

    for (let i = 1; i <= periodCount; i++) {
      // Check if we need a break after this period
      let breakAfterThisPeriod = null;
      Object.entries(breakConfig).forEach(([type, config]) => {
        if (i === config.afterPeriod) {
          breakAfterThisPeriod = { type, duration: config.duration };
        }
      });

      // Add period to current row
      periodsBeforeBreak.push({
        number: i,
        start: currentTime,
        end: currentTime + periodDuration
      });

      currentTime += periodDuration;
      lastPeriod = i;

      // Render periods if break coming or last period
      if (breakAfterThisPeriod || i === periodCount) {
        const cols = periodsBeforeBreak.length <= 2 ? 2 : (periodsBeforeBreak.length <= 3 ? 3 : 4);
        html += `<div class="grid grid-cols-${cols} gap-2">`;
        periodsBeforeBreak.forEach(p => {
          html += `<div><span class="font-semibold">P${p.number}:</span> ${formatTime(p.start)}-${formatTime(p.end)}</div>`;
        });
        html += '</div>';

        // Add break if applicable
        if (breakAfterThisPeriod) {
          const breakColor = breakAfterThisPeriod.type === 'morning' ? 'amber' : 
                              (breakAfterThisPeriod.type === 'lunch' ? 'orange' : 'purple');
          const breakIcon = breakAfterThisPeriod.type === 'morning' ? '☕' : 
                            (breakAfterThisPeriod.type === 'lunch' ? '🍱' : '🌆');
          const breakLabel = breakAfterThisPeriod.type.charAt(0).toUpperCase() + breakAfterThisPeriod.type.slice(1);
          
          html += `<div class="text-${breakColor}-700 bg-${breakColor}-50 px-2 py-1 rounded">${breakIcon} ${breakLabel} (${breakAfterThisPeriod.duration}min)</div>`;
          currentTime += breakAfterThisPeriod.duration;
        }

        periodsBeforeBreak = [];
      }
    }

    scheduleContainer.innerHTML = html;
  }

  // Attach listeners to all period inputs
  document.querySelectorAll('.period-duration, .period-count, .break-after, .break-duration').forEach(input => {
    input.addEventListener('input', function() {
      const config = this.getAttribute('data-config');
      if (config) {
        calculateSchedule(config);
      }
    });
  });

  // Initialize schedules
  ['jh-regular', 'jh-shortened', 'sh-regular', 'sh-shortened'].forEach(config => {
    calculateSchedule(config);
  });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\TuroTugma\resources\views/admin/schedule-maker/settings.blade.php ENDPATH**/ ?>