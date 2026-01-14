

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
            <div class="text-xs text-slate-500 session-day-label" data-config="jh-regular">Tue–Fri</div>
          </div>
        </label>
        <label class="flex-1 cursor-pointer">
          <input type="radio" name="jh-session" value="shortened" class="peer hidden" />
          <div class="p-3 border rounded-lg bg-white peer-checked:border-blue-600 peer-checked:bg-blue-50 hover:border-blue-400">
            <div class="font-semibold text-slate-900">⚡ Shortened</div>
            <div class="text-xs text-slate-500 session-day-label" data-config="jh-shortened">Mon</div>
          </div>
        </label>
      </div>

      <!-- Regular Session Configuration -->
      <div id="jh-regular-config" class="session-config space-y-4">
        
        <!-- School Calendar -->
        <div class="border rounded-lg p-4 bg-white">
          <div class="flex items-center justify-between mb-3">
            <h4 class="font-semibold text-slate-800">📅 School Calendar</h4>
            <div class="text-xs text-slate-500 font-semibold calendar-day-count" data-config="jh-regular">4 days</div>
          </div>
          <div class="flex gap-2 mb-3">
            <button type="button" class="calendar-day" data-config="jh-regular" data-day="0" data-active="false"><div class="w-12 h-12 rounded-full border-2 bg-white border-slate-300 text-slate-500 flex items-center justify-center text-xs font-medium cursor-pointer hover:bg-slate-50">Mon</div></button>
            <button type="button" class="calendar-day" data-config="jh-regular" data-day="1" data-active="true"><div class="w-12 h-12 rounded-full border-2 bg-blue-500 border-blue-600 text-white flex items-center justify-center text-xs font-medium cursor-pointer hover:bg-blue-600">Tue</div></button>
            <button type="button" class="calendar-day" data-config="jh-regular" data-day="2" data-active="true"><div class="w-12 h-12 rounded-full border-2 bg-blue-500 border-blue-600 text-white flex items-center justify-center text-xs font-medium cursor-pointer hover:bg-blue-600">Wed</div></button>
            <button type="button" class="calendar-day" data-config="jh-regular" data-day="3" data-active="true"><div class="w-12 h-12 rounded-full border-2 bg-blue-500 border-blue-600 text-white flex items-center justify-center text-xs font-medium cursor-pointer hover:bg-blue-600">Thu</div></button>
            <button type="button" class="calendar-day" data-config="jh-regular" data-day="4" data-active="true"><div class="w-12 h-12 rounded-full border-2 bg-blue-500 border-blue-600 text-white flex items-center justify-center text-xs font-medium cursor-pointer hover:bg-blue-600">Fri</div></button>
            <button type="button" class="calendar-day" data-config="jh-regular" data-day="5" data-active="false"><div class="w-12 h-12 rounded-full border-2 bg-slate-100 border-slate-300 text-slate-400 flex items-center justify-center text-xs font-medium cursor-pointer hover:bg-slate-200">Sat</div></button>
            <button type="button" class="calendar-day" data-config="jh-regular" data-day="6" data-active="false"><div class="w-12 h-12 rounded-full border-2 bg-slate-100 border-slate-300 text-slate-400 flex items-center justify-center text-xs font-medium cursor-pointer hover:bg-slate-200">Sun</div></button>
          </div>
        </div>

        <!-- Period Structure -->
        <div class="border rounded-lg p-4 bg-white">
          <div class="flex items-center justify-between mb-3">
            <h4 class="font-semibold text-slate-800">🕐 Period Structure</h4>
            <div class="flex items-center gap-2">
              <label class="text-xs text-slate-600">Mode:</label>
              <label class="flex items-center gap-1 text-xs cursor-pointer">
                <input type="radio" name="jh-regular-mode" value="auto" class="schedule-mode" data-config="jh-regular" checked />
                <span>Auto</span>
              </label>
              <label class="flex items-center gap-1 text-xs cursor-pointer">
                <input type="radio" name="jh-regular-mode" value="manual" class="schedule-mode" data-config="jh-regular" />
                <span>Manual</span>
              </label>
            </div>
          </div>
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
          <div class="bg-slate-50 p-4 rounded space-y-3" id="jh-regular-schedule">
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-2">
              <div class="p-2 rounded border bg-blue-50 border-l-4 border-blue-400 text-xs">
                <div class="font-semibold text-slate-900">P1</div>
                <div class="text-slate-600">7:30 AM - 8:30 AM</div>
              </div>
              <div class="p-2 rounded border bg-blue-50 border-l-4 border-blue-400 text-xs">
                <div class="font-semibold text-slate-900">P2</div>
                <div class="text-slate-600">8:30 AM - 9:30 AM</div>
              </div>
              <div class="p-2 rounded border bg-blue-50 border-l-4 border-blue-400 text-xs">
                <div class="font-semibold text-slate-900">P3</div>
                <div class="text-slate-600">9:30 AM - 10:30 AM</div>
              </div>
            </div>
            <div class="col-span-full flex items-center gap-2 px-3 py-2 rounded border-2 border-dashed bg-amber-100 border-amber-400 text-amber-800 font-semibold text-sm">
              <span>☕</span>
              <span>Morning Break</span>
              <span class="ml-auto text-xs opacity-75">20 min</span>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-2">
              <div class="p-2 rounded border bg-emerald-50 border-l-4 border-emerald-400 text-xs">
                <div class="font-semibold text-slate-900">P4</div>
                <div class="text-slate-600">10:50 AM - 11:50 AM</div>
              </div>
              <div class="p-2 rounded border bg-emerald-50 border-l-4 border-emerald-400 text-xs">
                <div class="font-semibold text-slate-900">P5</div>
                <div class="text-slate-600">11:50 AM - 12:50 PM</div>
              </div>
            </div>
            <div class="col-span-full flex items-center gap-2 px-3 py-2 rounded border-2 border-dashed bg-orange-100 border-orange-400 text-orange-800 font-semibold text-sm">
              <span>🍱</span>
              <span>Lunch Break</span>
              <span class="ml-auto text-xs opacity-75">60 min</span>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-2">
              <div class="p-2 rounded border bg-rose-50 border-l-4 border-rose-400 text-xs">
                <div class="font-semibold text-slate-900">P6</div>
                <div class="text-slate-600">1:50 PM - 2:50 PM</div>
              </div>
              <div class="p-2 rounded border bg-rose-50 border-l-4 border-rose-400 text-xs">
                <div class="font-semibold text-slate-900">P7</div>
                <div class="text-slate-600">2:50 PM - 3:50 PM</div>
              </div>
              <div class="p-2 rounded border bg-rose-50 border-l-4 border-rose-400 text-xs">
                <div class="font-semibold text-slate-900">P8</div>
                <div class="text-slate-600">3:50 PM - 4:50 PM</div>
              </div>
              <div class="p-2 rounded border bg-rose-50 border-l-4 border-rose-400 text-xs">
                <div class="font-semibold text-slate-900">P9</div>
                <div class="text-slate-600">4:50 PM - 5:50 PM</div>
              </div>
            </div>
          </div>
        </div>

        <!-- Break Schedule -->
        <div class="border rounded-lg p-4 bg-white">
          <h4 class="font-semibold mb-4 text-slate-800">☕ Breaks</h4>
          <div class="grid grid-cols-3 gap-4">
            <div class="border rounded-lg p-4 bg-slate-50">
              <div class="flex items-center gap-2 mb-3">
                <input type="checkbox" class="break-enabled" data-config="jh-regular" data-break="morning" checked />
                <label class="text-sm font-semibold text-slate-700">Morning Break</label>
              </div>
              <div class="space-y-2 text-sm">
                <div class="flex items-center gap-2">
                  <span class="text-slate-600 whitespace-nowrap">After P</span>
                  <input type="number" value="3" class="break-after input w-14 px-2 py-1 border border-slate-300 rounded" data-config="jh-regular" data-break="morning" min="1" max="9" />
                </div>
                <div class="flex items-center gap-2">
                  <span class="text-slate-600 whitespace-nowrap">Duration</span>
                  <input type="number" value="20" class="break-duration input w-14 px-2 py-1 border border-slate-300 rounded" data-config="jh-regular" data-break="morning" min="5" max="60" />
                  <span class="text-slate-600 text-xs">min</span>
                </div>
              </div>
            </div>
            <div class="border rounded-lg p-4 bg-slate-50">
              <div class="flex items-center gap-2 mb-3">
                <input type="checkbox" class="break-enabled" data-config="jh-regular" data-break="lunch" checked />
                <label class="text-sm font-semibold text-slate-700">Lunch Break</label>
              </div>
              <div class="space-y-2 text-sm">
                <div class="flex items-center gap-2">
                  <span class="text-slate-600 whitespace-nowrap">After P</span>
                  <input type="number" value="5" class="break-after input w-14 px-2 py-1 border border-slate-300 rounded" data-config="jh-regular" data-break="lunch" min="1" max="9" />
                </div>
                <div class="flex items-center gap-2">
                  <span class="text-slate-600 whitespace-nowrap">Duration</span>
                  <input type="number" value="60" class="break-duration input w-14 px-2 py-1 border border-slate-300 rounded" data-config="jh-regular" data-break="lunch" min="5" max="90" />
                  <span class="text-slate-600 text-xs">min</span>
                </div>
              </div>
            </div>
            <div class="border rounded-lg p-4 bg-slate-50">
              <div class="flex items-center gap-2 mb-3">
                <input type="checkbox" class="break-enabled" data-config="jh-regular" data-break="afternoon" />
                <label class="text-sm font-semibold text-slate-700">Afternoon Break</label>
              </div>
              <div class="space-y-2 text-sm">
                <div class="flex items-center gap-2">
                  <span class="text-slate-600 whitespace-nowrap">After P</span>
                  <input type="number" value="7" class="break-after input w-14 px-2 py-1 border border-slate-300 rounded" data-config="jh-regular" data-break="afternoon" min="1" max="9" />
                </div>
                <div class="flex items-center gap-2">
                  <span class="text-slate-600 whitespace-nowrap">Duration</span>
                  <input type="number" value="15" class="break-duration input w-14 px-2 py-1 border border-slate-300 rounded" data-config="jh-regular" data-break="afternoon" min="5" max="60" />
                  <span class="text-slate-600 text-xs">min</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Section Types -->
        <div class="border rounded-lg p-4 bg-white">
          <h4 class="font-semibold mb-3 text-slate-800">📋 Subject Assignments</h4>
          <div class="grid grid-cols-2 gap-3 mb-4">
            <div class="border rounded p-3 bg-blue-50">
              <div class="flex items-center gap-2 mb-2">
                <div class="w-8 h-8 bg-blue-500 text-white rounded flex items-center justify-center font-bold text-sm">R</div>
                <div>
                  <div class="font-semibold text-sm">Regular</div>
                  <div class="text-xs text-slate-500">8 periods</div>
                </div>
              </div>
              <div class="text-xs text-slate-600">7-Saturn, 8-Jupiter</div>
            </div>
            <div class="border rounded p-3 bg-purple-50">
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
          <div class="space-y-3">
            <!-- Core Subjects -->
            <div class="border rounded-lg p-3 bg-slate-50 hover:bg-slate-100 transition subject-row" data-config="jh-regular" data-subject="core">
              <div class="flex items-center justify-between">
                <div class="flex-1">
                  <div class="font-semibold text-sm text-slate-900">📚 Core Subjects</div>
                  <div class="text-xs text-slate-500">MATH, SCI, ENG, FIL, MAPEH, ESP, ARPAN, TLE</div>
                </div>
                <div class="flex gap-4">
                  <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" class="subject-checkbox" data-type="regular" checked />
                    <span class="text-xs font-semibold text-slate-600">Regular</span>
                  </label>
                  <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" class="subject-checkbox" data-type="special" checked />
                    <span class="text-xs font-semibold text-slate-600">Special</span>
                  </label>
                </div>
              </div>
            </div>

            <!-- Specialized Subjects -->
            <div class="border rounded-lg p-3 bg-slate-50 hover:bg-slate-100 transition subject-row" data-config="jh-regular" data-subject="specialized">
              <div class="flex items-center justify-between">
                <div class="flex-1">
                  <div class="font-semibold text-sm text-slate-900">✨ Specialized Subjects</div>
                  <div class="text-xs text-slate-500">SPA, SPJ</div>
                </div>
                <div class="flex gap-4">
                  <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" class="subject-checkbox" data-type="regular" />
                    <span class="text-xs font-semibold text-slate-600">Regular</span>
                  </label>
                  <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" class="subject-checkbox" data-type="special" checked />
                    <span class="text-xs font-semibold text-slate-600">Special</span>
                  </label>
                </div>
              </div>
            </div>
          </div>
          <div class="mt-3 p-2 bg-blue-50 border border-blue-200 rounded text-xs text-blue-700">
            ℹ️ 2 subject types × 8 periods = 16 total assignments available
          </div>
        </div>

      </div>

      <!-- Shortened Session Configuration -->
      <div id="jh-shortened-config" class="session-config space-y-4 hidden">
        
        <!-- School Calendar -->
        <div class="border rounded-lg p-4 bg-white">
          <div class="flex items-center justify-between mb-3">
            <h4 class="font-semibold text-slate-800">📅 School Calendar</h4>
            <div class="text-xs text-slate-500 font-semibold calendar-day-count" data-config="jh-shortened">1 day</div>
          </div>
          <div class="flex gap-2 mb-3">
            <button type="button" class="calendar-day" data-config="jh-shortened" data-day="0" data-active="true"><div class="w-12 h-12 rounded-full border-2 bg-blue-500 border-blue-600 text-white flex items-center justify-center text-xs font-medium cursor-pointer hover:bg-blue-600">Mon</div></button>
            <button type="button" class="calendar-day" data-config="jh-shortened" data-day="1" data-active="false"><div class="w-12 h-12 rounded-full border-2 bg-white border-slate-300 text-slate-500 flex items-center justify-center text-xs font-medium cursor-pointer hover:bg-slate-50">Tue</div></button>
            <button type="button" class="calendar-day" data-config="jh-shortened" data-day="2" data-active="false"><div class="w-12 h-12 rounded-full border-2 bg-white border-slate-300 text-slate-500 flex items-center justify-center text-xs font-medium cursor-pointer hover:bg-slate-50">Wed</div></button>
            <button type="button" class="calendar-day" data-config="jh-shortened" data-day="3" data-active="false"><div class="w-12 h-12 rounded-full border-2 bg-white border-slate-300 text-slate-500 flex items-center justify-center text-xs font-medium cursor-pointer hover:bg-slate-50">Thu</div></button>
            <button type="button" class="calendar-day" data-config="jh-shortened" data-day="4" data-active="false"><div class="w-12 h-12 rounded-full border-2 bg-white border-slate-300 text-slate-500 flex items-center justify-center text-xs font-medium cursor-pointer hover:bg-slate-50">Fri</div></button>
            <button type="button" class="calendar-day" data-config="jh-shortened" data-day="5" data-active="false"><div class="w-12 h-12 rounded-full border-2 bg-slate-100 border-slate-300 text-slate-400 flex items-center justify-center text-xs font-medium cursor-pointer hover:bg-slate-200">Sat</div></button>
            <button type="button" class="calendar-day" data-config="jh-shortened" data-day="6" data-active="false"><div class="w-12 h-12 rounded-full border-2 bg-slate-100 border-slate-300 text-slate-400 flex items-center justify-center text-xs font-medium cursor-pointer hover:bg-slate-200">Sun</div></button>
          </div>
        </div>

        <!-- Period Structure -->
        <div class="border rounded-lg p-4 bg-white">
          <div class="flex items-center justify-between mb-3">
            <h4 class="font-semibold text-slate-800">🕐 Period Structure</h4>
            <div class="flex items-center gap-2">
              <label class="text-xs text-slate-600">Mode:</label>
              <label class="flex items-center gap-1 text-xs cursor-pointer">
                <input type="radio" name="jh-shortened-mode" value="auto" class="schedule-mode" data-config="jh-shortened" checked />
                <span>Auto</span>
              </label>
              <label class="flex items-center gap-1 text-xs cursor-pointer">
                <input type="radio" name="jh-shortened-mode" value="manual" class="schedule-mode" data-config="jh-shortened" />
                <span>Manual</span>
              </label>
            </div>
          </div>
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
          <h4 class="font-semibold mb-4 text-slate-800">☕ Breaks</h4>
          <div class="grid grid-cols-3 gap-4">
            <div class="border rounded-lg p-4 bg-slate-50">
              <div class="flex items-center gap-2 mb-3">
                <input type="checkbox" class="break-enabled" data-config="jh-shortened" data-break="morning" checked />
                <label class="text-sm font-semibold text-slate-700">Morning Break</label>
              </div>
              <div class="space-y-2 text-sm">
                <div class="flex items-center gap-2">
                  <span class="text-slate-600 whitespace-nowrap">After P</span>
                  <input type="number" value="3" class="break-after input w-14 px-2 py-1 border border-slate-300 rounded" data-config="jh-shortened" data-break="morning" min="1" max="9" />
                </div>
                <div class="flex items-center gap-2">
                  <span class="text-slate-600 whitespace-nowrap">Duration</span>
                  <input type="number" value="20" class="break-duration input w-14 px-2 py-1 border border-slate-300 rounded" data-config="jh-shortened" data-break="morning" min="5" max="60" />
                  <span class="text-slate-600 text-xs">min</span>
                </div>
              </div>
            </div>
            <div class="border rounded-lg p-4 bg-slate-50">
              <div class="flex items-center gap-2 mb-3">
                <input type="checkbox" class="break-enabled" data-config="jh-shortened" data-break="lunch" checked />
                <label class="text-sm font-semibold text-slate-700">Lunch Break</label>
              </div>
              <div class="space-y-2 text-sm">
                <div class="flex items-center gap-2">
                  <span class="text-slate-600 whitespace-nowrap">After P</span>
                  <input type="number" value="5" class="break-after input w-14 px-2 py-1 border border-slate-300 rounded" data-config="jh-shortened" data-break="lunch" min="1" max="9" />
                </div>
                <div class="flex items-center gap-2">
                  <span class="text-slate-600 whitespace-nowrap">Duration</span>
                  <input type="number" value="60" class="break-duration input w-14 px-2 py-1 border border-slate-300 rounded" data-config="jh-shortened" data-break="lunch" min="5" max="90" />
                  <span class="text-slate-600 text-xs">min</span>
                </div>
              </div>
            </div>
            <div class="border rounded-lg p-4 bg-slate-50">
              <div class="flex items-center gap-2 mb-3">
                <input type="checkbox" class="break-enabled" data-config="jh-shortened" data-break="afternoon" />
                <label class="text-sm font-semibold text-slate-700">Afternoon Break</label>
              </div>
              <div class="space-y-2 text-sm">
                <div class="flex items-center gap-2">
                  <span class="text-slate-600 whitespace-nowrap">After P</span>
                  <input type="number" value="7" class="break-after input w-14 px-2 py-1 border border-slate-300 rounded" data-config="jh-shortened" data-break="afternoon" min="1" max="9" />
                </div>
                <div class="flex items-center gap-2">
                  <span class="text-slate-600 whitespace-nowrap">Duration</span>
                  <input type="number" value="15" class="break-duration input w-14 px-2 py-1 border border-slate-300 rounded" data-config="jh-shortened" data-break="afternoon" min="5" max="60" />
                  <span class="text-slate-600 text-xs">min</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Section Types -->
        <div class="border rounded-lg p-4 bg-white">
          <h4 class="font-semibold mb-3 text-slate-800">📋 Subject Assignments</h4>
          <div class="grid grid-cols-2 gap-3 mb-4">
            <div class="border rounded p-3 bg-blue-50">
              <div class="flex items-center gap-2 mb-2">
                <div class="w-8 h-8 bg-blue-500 text-white rounded flex items-center justify-center font-bold text-sm">R</div>
                <div>
                  <div class="font-semibold text-sm">Regular</div>
                  <div class="text-xs text-slate-500">8 periods</div>
                </div>
              </div>
              <div class="text-xs text-slate-600">7-Saturn, 8-Jupiter</div>
            </div>
            <div class="border rounded p-3 bg-purple-50">
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
          <div class="space-y-3">
            <!-- Core Subjects -->
            <div class="border rounded-lg p-3 bg-slate-50 hover:bg-slate-100 transition subject-row" data-config="jh-shortened" data-subject="core">
              <div class="flex items-center justify-between">
                <div class="flex-1">
                  <div class="font-semibold text-sm text-slate-900">📚 Core Subjects</div>
                  <div class="text-xs text-slate-500">MATH, SCI, ENG, FIL, MAPEH, ESP, ARPAN, TLE</div>
                </div>
                <div class="flex gap-4">
                  <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" class="subject-checkbox" data-type="regular" checked />
                    <span class="text-xs font-semibold text-slate-600">Regular</span>
                  </label>
                  <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" class="subject-checkbox" data-type="special" checked />
                    <span class="text-xs font-semibold text-slate-600">Special</span>
                  </label>
                </div>
              </div>
            </div>

            <!-- Specialized Subjects -->
            <div class="border rounded-lg p-3 bg-slate-50 hover:bg-slate-100 transition subject-row" data-config="jh-shortened" data-subject="specialized">
              <div class="flex items-center justify-between">
                <div class="flex-1">
                  <div class="font-semibold text-sm text-slate-900">✨ Specialized Subjects</div>
                  <div class="text-xs text-slate-500">SPA, SPJ</div>
                </div>
                <div class="flex gap-4">
                  <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" class="subject-checkbox" data-type="regular" />
                    <span class="text-xs font-semibold text-slate-600">Regular</span>
                  </label>
                  <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" class="subject-checkbox" data-type="special" checked />
                    <span class="text-xs font-semibold text-slate-600">Special</span>
                  </label>
                </div>
              </div>
            </div>
          </div>
          <div class="mt-3 p-2 bg-blue-50 border border-blue-200 rounded text-xs text-blue-700">
            ℹ️ 2 subject types × 8 periods = 16 total assignments available
          </div>
        </div>

      </div>
          </div>
        </div>
      </div>

      <!-- Hidden content for future use -->
      <div class="hidden">

      <!-- Regular Session Configuration -->
      <div id="sh-regular-config" class="session-config space-y-4">
        
        <!-- School Calendar -->
        <div class="border rounded-lg p-4 bg-white">
          <div class="flex items-center justify-between mb-3">
            <h4 class="font-semibold text-slate-800">📅 School Calendar</h4>
            <div class="text-xs text-slate-500 font-semibold calendar-day-count" data-config="sh-regular">4 days</div>
          </div>
          <div class="flex gap-2 mb-3">
            <button type="button" class="calendar-day" data-config="sh-regular" data-day="0" data-active="true"><div class="w-12 h-12 rounded-full border-2 bg-green-500 border-green-600 text-white flex items-center justify-center text-xs font-medium cursor-pointer hover:bg-green-600">Mon</div></button>
            <button type="button" class="calendar-day" data-config="sh-regular" data-day="1" data-active="true"><div class="w-12 h-12 rounded-full border-2 bg-green-500 border-green-600 text-white flex items-center justify-center text-xs font-medium cursor-pointer hover:bg-green-600">Tue</div></button>
            <button type="button" class="calendar-day" data-config="sh-regular" data-day="2" data-active="true"><div class="w-12 h-12 rounded-full border-2 bg-green-500 border-green-600 text-white flex items-center justify-center text-xs font-medium cursor-pointer hover:bg-green-600">Wed</div></button>
            <button type="button" class="calendar-day" data-config="sh-regular" data-day="3" data-active="true"><div class="w-12 h-12 rounded-full border-2 bg-green-500 border-green-600 text-white flex items-center justify-center text-xs font-medium cursor-pointer hover:bg-green-600">Thu</div></button>
            <button type="button" class="calendar-day" data-config="sh-regular" data-day="4" data-active="false"><div class="w-12 h-12 rounded-full border-2 bg-white border-slate-300 text-slate-500 flex items-center justify-center text-xs font-medium cursor-pointer hover:bg-slate-50">Fri</div></button>
            <button type="button" class="calendar-day" data-config="sh-regular" data-day="5" data-active="false"><div class="w-12 h-12 rounded-full border-2 bg-slate-100 border-slate-300 text-slate-400 flex items-center justify-center text-xs font-medium cursor-pointer hover:bg-slate-200">Sat</div></button>
            <button type="button" class="calendar-day" data-config="sh-regular" data-day="6" data-active="false"><div class="w-12 h-12 rounded-full border-2 bg-slate-100 border-slate-300 text-slate-400 flex items-center justify-center text-xs font-medium cursor-pointer hover:bg-slate-200">Sun</div></button>
          </div>
        </div>

        <!-- Period Structure -->
        <div class="border rounded-lg p-4 bg-white">
          <div class="flex items-center justify-between mb-3">
            <h4 class="font-semibold text-slate-800">🕐 Period Structure</h4>
            <div class="flex items-center gap-2">
              <label class="text-xs text-slate-600">Mode:</label>
              <label class="flex items-center gap-1 text-xs cursor-pointer">
                <input type="radio" name="sh-regular-mode" value="auto" class="schedule-mode" data-config="sh-regular" checked />
                <span>Auto</span>
              </label>
              <label class="flex items-center gap-1 text-xs cursor-pointer">
                <input type="radio" name="sh-regular-mode" value="manual" class="schedule-mode" data-config="sh-regular" />
                <span>Manual</span>
              </label>
            </div>
          </div>
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
          <h4 class="font-semibold mb-4 text-slate-800">☕ Breaks</h4>
          <div class="grid grid-cols-3 gap-4">
            <div class="border rounded-lg p-4 bg-slate-50">
              <div class="flex items-center gap-2 mb-3">
                <input type="checkbox" class="break-enabled" data-config="sh-regular" data-break="morning" checked />
                <label class="text-sm font-semibold text-slate-700">Morning Break</label>
              </div>
              <div class="space-y-2 text-sm">
                <div class="flex items-center gap-2">
                  <span class="text-slate-600 whitespace-nowrap">After P</span>
                  <input type="number" value="3" class="break-after input w-14 px-2 py-1 border border-slate-300 rounded" data-config="sh-regular" data-break="morning" min="1" max="9" />
                </div>
                <div class="flex items-center gap-2">
                  <span class="text-slate-600 whitespace-nowrap">Duration</span>
                  <input type="number" value="20" class="break-duration input w-14 px-2 py-1 border border-slate-300 rounded" data-config="sh-regular" data-break="morning" min="5" max="60" />
                  <span class="text-slate-600 text-xs">min</span>
                </div>
              </div>
            </div>
            <div class="border rounded-lg p-4 bg-slate-50">
              <div class="flex items-center gap-2 mb-3">
                <input type="checkbox" class="break-enabled" data-config="sh-regular" data-break="lunch" checked />
                <label class="text-sm font-semibold text-slate-700">Lunch Break</label>
              </div>
              <div class="space-y-2 text-sm">
                <div class="flex items-center gap-2">
                  <span class="text-slate-600 whitespace-nowrap">After P</span>
                  <input type="number" value="5" class="break-after input w-14 px-2 py-1 border border-slate-300 rounded" data-config="sh-regular" data-break="lunch" min="1" max="9" />
                </div>
                <div class="flex items-center gap-2">
                  <span class="text-slate-600 whitespace-nowrap">Duration</span>
                  <input type="number" value="60" class="break-duration input w-14 px-2 py-1 border border-slate-300 rounded" data-config="sh-regular" data-break="lunch" min="5" max="90" />
                  <span class="text-slate-600 text-xs">min</span>
                </div>
              </div>
            </div>
            <div class="border rounded-lg p-4 bg-slate-50">
              <div class="flex items-center gap-2 mb-3">
                <input type="checkbox" class="break-enabled" data-config="sh-regular" data-break="afternoon" checked />
                <label class="text-sm font-semibold text-slate-700">Afternoon Break</label>
              </div>
              <div class="space-y-2 text-sm">
                <div class="flex items-center gap-2">
                  <span class="text-slate-600 whitespace-nowrap">After P</span>
                  <input type="number" value="7" class="break-after input w-14 px-2 py-1 border border-slate-300 rounded" data-config="sh-regular" data-break="afternoon" min="1" max="9" />
                </div>
                <div class="flex items-center gap-2">
                  <span class="text-slate-600 whitespace-nowrap">Duration</span>
                  <input type="number" value="15" class="break-duration input w-14 px-2 py-1 border border-slate-300 rounded" data-config="sh-regular" data-break="afternoon" min="5" max="60" />
                  <span class="text-slate-600 text-xs">min</span>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

      <!-- Shortened Session Configuration -->
      <div id="sh-shortened-config" class="session-config space-y-4 hidden">
        
        <!-- School Calendar -->
        <div class="border rounded-lg p-4 bg-white">
          <div class="flex items-center justify-between mb-3">
            <h4 class="font-semibold text-slate-800">📅 School Calendar</h4>
            <div class="text-xs text-slate-500 font-semibold calendar-day-count" data-config="sh-shortened">1 day</div>
          </div>
          <div class="flex gap-2 mb-3">
            <button type="button" class="calendar-day" data-config="sh-shortened" data-day="0" data-active="false"><div class="w-12 h-12 rounded-full border-2 bg-white border-slate-300 text-slate-500 flex items-center justify-center text-xs font-medium cursor-pointer hover:bg-slate-50">Mon</div></button>
            <button type="button" class="calendar-day" data-config="sh-shortened" data-day="1" data-active="false"><div class="w-12 h-12 rounded-full border-2 bg-white border-slate-300 text-slate-500 flex items-center justify-center text-xs font-medium cursor-pointer hover:bg-slate-50">Tue</div></button>
            <button type="button" class="calendar-day" data-config="sh-shortened" data-day="2" data-active="false"><div class="w-12 h-12 rounded-full border-2 bg-white border-slate-300 text-slate-500 flex items-center justify-center text-xs font-medium cursor-pointer hover:bg-slate-50">Wed</div></button>
            <button type="button" class="calendar-day" data-config="sh-shortened" data-day="3" data-active="false"><div class="w-12 h-12 rounded-full border-2 bg-white border-slate-300 text-slate-500 flex items-center justify-center text-xs font-medium cursor-pointer hover:bg-slate-50">Thu</div></button>
            <button type="button" class="calendar-day" data-config="sh-shortened" data-day="4" data-active="true"><div class="w-12 h-12 rounded-full border-2 bg-green-500 border-green-600 text-white flex items-center justify-center text-xs font-medium cursor-pointer hover:bg-green-600">Fri</div></button>
            <button type="button" class="calendar-day" data-config="sh-shortened" data-day="5" data-active="false"><div class="w-12 h-12 rounded-full border-2 bg-slate-100 border-slate-300 text-slate-400 flex items-center justify-center text-xs font-medium cursor-pointer hover:bg-slate-200">Sat</div></button>
            <button type="button" class="calendar-day" data-config="sh-shortened" data-day="6" data-active="false"><div class="w-12 h-12 rounded-full border-2 bg-slate-100 border-slate-300 text-slate-400 flex items-center justify-center text-xs font-medium cursor-pointer hover:bg-slate-200">Sun</div></button>
          </div>
        </div>

        <!-- Period Structure -->
        <div class="border rounded-lg p-4 bg-white">
          <div class="flex items-center justify-between mb-3">
            <h4 class="font-semibold text-slate-800">🕐 Period Structure</h4>
            <div class="flex items-center gap-2">
              <label class="text-xs text-slate-600">Mode:</label>
              <label class="flex items-center gap-1 text-xs cursor-pointer">
                <input type="radio" name="sh-shortened-mode" value="auto" class="schedule-mode" data-config="sh-shortened" checked />
                <span>Auto</span>
              </label>
              <label class="flex items-center gap-1 text-xs cursor-pointer">
                <input type="radio" name="sh-shortened-mode" value="manual" class="schedule-mode" data-config="sh-shortened" />
                <span>Manual</span>
              </label>
            </div>
          </div>
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
          <h4 class="font-semibold mb-4 text-slate-800">☕ Breaks</h4>
          <div class="grid grid-cols-3 gap-4">
            <div class="border rounded-lg p-4 bg-slate-50">
              <div class="flex items-center gap-2 mb-3">
                <input type="checkbox" class="break-enabled" data-config="sh-shortened" data-break="morning" checked />
                <label class="text-sm font-semibold text-slate-700">Morning Break</label>
              </div>
              <div class="space-y-2 text-sm">
                <div class="flex items-center gap-2">
                  <span class="text-slate-600 whitespace-nowrap">After P</span>
                  <input type="number" value="3" class="break-after input w-14 px-2 py-1 border border-slate-300 rounded" data-config="sh-shortened" data-break="morning" min="1" max="9" />
                </div>
                <div class="flex items-center gap-2">
                  <span class="text-slate-600 whitespace-nowrap">Duration</span>
                  <input type="number" value="20" class="break-duration input w-14 px-2 py-1 border border-slate-300 rounded" data-config="sh-shortened" data-break="morning" min="5" max="60" />
                  <span class="text-slate-600 text-xs">min</span>
                </div>
              </div>
            </div>
            <div class="border rounded-lg p-4 bg-slate-50">
              <div class="flex items-center gap-2 mb-3">
                <input type="checkbox" class="break-enabled" data-config="sh-shortened" data-break="lunch" checked />
                <label class="text-sm font-semibold text-slate-700">Lunch Break</label>
              </div>
              <div class="space-y-2 text-sm">
                <div class="flex items-center gap-2">
                  <span class="text-slate-600 whitespace-nowrap">After P</span>
                  <input type="number" value="5" class="break-after input w-14 px-2 py-1 border border-slate-300 rounded" data-config="sh-shortened" data-break="lunch" min="1" max="9" />
                </div>
                <div class="flex items-center gap-2">
                  <span class="text-slate-600 whitespace-nowrap">Duration</span>
                  <input type="number" value="60" class="break-duration input w-14 px-2 py-1 border border-slate-300 rounded" data-config="sh-shortened" data-break="lunch" min="5" max="90" />
                  <span class="text-slate-600 text-xs">min</span>
                </div>
              </div>
            </div>
            <div class="border rounded-lg p-4 bg-slate-50">
              <div class="flex items-center gap-2 mb-3">
                <input type="checkbox" class="break-enabled" data-config="sh-shortened" data-break="afternoon" checked />
                <label class="text-sm font-semibold text-slate-700">Afternoon Break</label>
              </div>
              <div class="space-y-2 text-sm">
                <div class="flex items-center gap-2">
                  <span class="text-slate-600 whitespace-nowrap">After P</span>
                  <input type="number" value="7" class="break-after input w-14 px-2 py-1 border border-slate-300 rounded" data-config="sh-shortened" data-break="afternoon" min="1" max="9" />
                </div>
                <div class="flex items-center gap-2">
                  <span class="text-slate-600 whitespace-nowrap">Duration</span>
                  <input type="number" value="15" class="break-duration input w-14 px-2 py-1 border border-slate-300 rounded" data-config="sh-shortened" data-break="afternoon" min="5" max="60" />
                  <span class="text-slate-600 text-xs">min</span>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

      </div>
      <!-- End of hidden content -->
      
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
    let hours = Math.floor(minutes / 60);
    const mins = minutes % 60;
    const ampm = hours >= 12 ? 'PM' : 'AM';
    hours = hours % 12;
    hours = hours ? hours : 12; // the hour '0' should be '12'
    return `${hours}:${mins.toString().padStart(2, '0')} ${ampm}`;
  }

  function calculateSchedule(config) {
    const durationInput = document.querySelector(`.period-duration[data-config="${config}"]`);
    const countInput = document.querySelector(`.period-count[data-config="${config}"]`);
    const scheduleContainer = document.getElementById(`${config}-schedule`);
    
    if (!durationInput || !countInput || !scheduleContainer) return;

    const periodDuration = parseInt(durationInput.value) || 60;
    const periodCount = parseInt(countInput.value) || 9;

    // Get break configuration - only include enabled breaks
    const breakConfig = {};
    const breakInputs = document.querySelectorAll(`[data-config="${config}"].break-after`);
    breakInputs.forEach(input => {
      const breakType = input.getAttribute('data-break');
      const enabledCheckbox = document.querySelector(`.break-enabled[data-config="${config}"][data-break="${breakType}"]`);
      const durationInput = document.querySelector(`[data-config="${config}"][data-break="${breakType}"].break-duration`);
      
      // Only include break if checkbox is checked
      if (enabledCheckbox && enabledCheckbox.checked) {
        breakConfig[breakType] = {
          afterPeriod: parseInt(input.value) || 3,
          duration: parseInt(durationInput?.value) || 20
        };
      }
    });

    // Identify which periods fall in which section (for color coding)
    let breakPoints = [];
    Object.values(breakConfig).forEach(b => breakPoints.push(b.afterPeriod));
    breakPoints.sort((a, b) => a - b);

    // Start time: 7:30 AM = 450 minutes from midnight
    let currentTime = 7 * 60 + 30;
    let html = '';
    let periodRows = [];
    let currentRow = [];

    for (let i = 1; i <= periodCount; i++) {
      // Determine section (for color coding)
      let section = 0; // 0 = before first break, 1 = between first and second, 2 = after last break
      for (let j = 0; j < breakPoints.length; j++) {
        if (i > breakPoints[j]) {
          section = j + 1;
        }
      }

      // Check if we need a break after this period
      let breakAfterThisPeriod = null;
      Object.entries(breakConfig).forEach(([type, config]) => {
        if (i === config.afterPeriod) {
          breakAfterThisPeriod = { type, duration: config.duration };
        }
      });

      // Add period to current row
      currentRow.push({
        number: i,
        start: currentTime,
        end: currentTime + periodDuration,
        section: section
      });

      currentTime += periodDuration;

      // When we reach 4 periods or need a break, finalize the row
      if (currentRow.length === 4 || breakAfterThisPeriod || i === periodCount) {
        periodRows.push({
          periods: [...currentRow],
          breakAfter: breakAfterThisPeriod
        });
        currentRow = [];
        
        if (breakAfterThisPeriod) {
          currentTime += breakAfterThisPeriod.duration;
        }
      }
    }

    // Render all rows with consistent 4-column grid
    periodRows.forEach(row => {
      // Periods row
      html += '<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-2">';
      
      row.periods.forEach(p => {
        // Color code by section
        let bgColor = 'bg-blue-50 border-l-4 border-blue-400';
        if (p.section === 1) bgColor = 'bg-emerald-50 border-l-4 border-emerald-400';
        if (p.section === 2) bgColor = 'bg-rose-50 border-l-4 border-rose-400';
        
        html += `<div class="p-2 rounded border ${bgColor} text-xs">
                  <div class="font-semibold text-slate-900">P${p.number}</div>
                    <div class="text-slate-600">${formatTime(p.start)} - ${formatTime(p.end)}</div>
                </div>`;
      });
      
      // Fill remaining cells in 4-column grid if needed
      const remainingCells = 4 - (row.periods.length % 4);
      if (remainingCells < 4) {
        for (let i = 0; i < remainingCells; i++) {
          html += '<div></div>';
        }
      }
      
      html += '</div>';

      // Break row (if applicable)
      if (row.breakAfter) {
        const breakColor = row.breakAfter.type === 'morning' ? 'bg-amber-100 border-amber-400 text-amber-800' : 
                          (row.breakAfter.type === 'lunch' ? 'bg-orange-100 border-orange-400 text-orange-800' : 'bg-purple-100 border-purple-400 text-purple-800');
        const breakIcon = row.breakAfter.type === 'morning' ? '☕' : 
                         (row.breakAfter.type === 'lunch' ? '🍱' : '🌆');
        const breakLabel = row.breakAfter.type.charAt(0).toUpperCase() + row.breakAfter.type.slice(1) + ' Break';
        
        html += `<div class="col-span-full flex items-center gap-2 px-3 py-2 rounded border-2 border-dashed ${breakColor} font-semibold text-sm my-1">
                  <span>${breakIcon}</span>
                  <span>${breakLabel}</span>
                  <span class="ml-auto text-xs opacity-75">${row.breakAfter.duration} min</span>
                </div>`;
      }
    });

    scheduleContainer.innerHTML = html;
  }

  // Attach listeners to all period inputs and break checkboxes
  document.querySelectorAll('.period-duration, .period-count, .break-after, .break-duration, .break-enabled').forEach(input => {
    input.addEventListener('input', function() {
      const config = this.getAttribute('data-config');
      if (config) {
        calculateSchedule(config);
      }
    });
    
    // Also handle 'change' event for checkboxes
    if (input.type === 'checkbox') {
      input.addEventListener('change', function() {
        const config = this.getAttribute('data-config');
        if (config) {
          calculateSchedule(config);
        }
      });
    }
  });

  // Calendar Day Toggle Handler with Mutual Exclusivity
  const dayNames = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
  
  function getOppositeConfig(config) {
    // Return opposite session type
    if (config === 'jh-regular') return 'jh-shortened';
    if (config === 'jh-shortened') return 'jh-regular';
    if (config === 'sh-regular') return 'sh-shortened';
    if (config === 'sh-shortened') return 'sh-regular';
  }
  
  function updateDayDisplay(config) {
    // Get all active days for this config
    const activeDays = [];
    document.querySelectorAll(`.calendar-day[data-config="${config}"]`).forEach(btn => {
      if (btn.getAttribute('data-active') === 'true') {
        activeDays.push(parseInt(btn.getAttribute('data-day')));
      }
    });
    
    // Sort days numerically
    activeDays.sort((a, b) => a - b);
    
    // Format day text
    let dayText = '';
    if (activeDays.length === 0) {
      dayText = '(none)';
    } else if (activeDays.length === 1) {
      dayText = dayNames[activeDays[0]];
    } else if (activeDays.length === 2) {
      dayText = dayNames[activeDays[0]] + ', ' + dayNames[activeDays[1]];
    } else {
      // Check if days are consecutive
      let isConsecutive = true;
      for (let i = 1; i < activeDays.length; i++) {
        if (activeDays[i] !== activeDays[i - 1] + 1) {
          isConsecutive = false;
          break;
        }
      }
      
      if (isConsecutive) {
        dayText = dayNames[activeDays[0]] + '–' + dayNames[activeDays[activeDays.length - 1]];
      } else {
        // Show all days if not consecutive
        dayText = activeDays.map(day => dayNames[day]).join(', ');
      }
    }
    
    // Update session-day-label
    const sessionLabel = document.querySelector(`.session-day-label[data-config="${config}"]`);
    if (sessionLabel) {
      sessionLabel.textContent = dayText;
    }
    
    // Update day count
    const countEl = document.querySelector(`.calendar-day-count[data-config="${config}"]`);
    if (countEl) {
      const count = activeDays.length;
      countEl.textContent = count + (count === 1 ? ' day' : ' days');
    }
  }
  
  function updateDayVisuals(button, config, isActive) {
    const dayDiv = button.querySelector('div');
    if (isActive) {
      // Active state - colored background
      if (config.includes('jh')) {
        dayDiv.className = 'w-12 h-12 rounded-full border-2 bg-blue-500 border-blue-600 text-white flex items-center justify-center text-xs font-medium cursor-pointer hover:bg-blue-600';
      } else {
        dayDiv.className = 'w-12 h-12 rounded-full border-2 bg-green-500 border-green-600 text-white flex items-center justify-center text-xs font-medium cursor-pointer hover:bg-green-600';
      }
    } else {
      // Inactive state - gray background
      dayDiv.className = 'w-12 h-12 rounded-full border-2 bg-slate-100 border-slate-300 text-slate-400 flex items-center justify-center text-xs font-medium cursor-pointer hover:bg-slate-200';
    }
  }
  
  function uncheckDayInConfig(config, dayIndex) {
    const button = document.querySelector(`.calendar-day[data-config="${config}"][data-day="${dayIndex}"]`);
    if (button && button.getAttribute('data-active') === 'true') {
      button.setAttribute('data-active', 'false');
      updateDayVisuals(button, config, false);
      updateDayDisplay(config);
    }
  }
  
  document.querySelectorAll('.calendar-day').forEach(button => {
    button.addEventListener('click', function(e) {
      e.preventDefault();
      const config = this.getAttribute('data-config');
      const dayIndex = parseInt(this.getAttribute('data-day'));
      const isActive = this.getAttribute('data-active') === 'true';
      
      // Toggle the active state
      const newActive = !isActive;
      this.setAttribute('data-active', newActive ? 'true' : 'false');
      updateDayVisuals(this, config, newActive);
      
      // MUTUAL EXCLUSIVITY: If activating, deactivate same day in opposite session
      if (newActive) {
        const oppositeConfig = getOppositeConfig(config);
        uncheckDayInConfig(oppositeConfig, dayIndex);
      }
      
      // Update display for both configs
      updateDayDisplay(config);
      updateDayDisplay(getOppositeConfig(config));
    });
  });
  
  // Initialize all day displays
  ['jh-regular', 'jh-shortened', 'sh-regular', 'sh-shortened'].forEach(config => {
    updateDayDisplay(config);
  });

  // Initialize schedules
  ['jh-regular', 'jh-shortened', 'sh-regular', 'sh-shortened'].forEach(config => {
    calculateSchedule(config);
  });

  // Handle subject checkbox changes
  document.querySelectorAll('.subject-checkbox').forEach(checkbox => {
    checkbox.addEventListener('change', function() {
      const row = this.closest('.subject-row');
      const config = row.getAttribute('data-config');
      const subject = row.getAttribute('data-subject');
      const type = this.getAttribute('data-type');
      
      // Store state (in mock UI, this is just visual - no backend yet)
      const isChecked = this.checked;
      this.parentElement.classList.toggle('opacity-50', !isChecked);
      
      // Log for debugging
      console.log(`Subject config: ${config}, Subject: ${subject}, Type: ${type}, Enabled: ${isChecked}`);
    });
  });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\TuroTugma\resources\views/admin/schedule-maker/settings.blade.php ENDPATH**/ ?>