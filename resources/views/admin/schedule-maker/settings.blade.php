@extends('admin.layout')

@section('title','Schedule Maker - Settings')
@section('heading','Schedule Maker - Settings')

@section('content')

<style>

</style>
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

    <!-- Tab Content: Senior High - Coming Soon -->
    <div id="tab-sh" class="settings-tab-content hidden p-6">
      <div style="display: flex; align-items: center; justify-content: center; min-height: 500px;">
        <div class="text-center">
          <div class="text-6xl mb-4">🚀</div>
          <h3 class="text-2xl font-bold text-slate-900 mb-2">Senior High Configuration</h3>
          <p class="text-slate-600 text-lg">Coming Soon</p>
          <p class="text-slate-500 text-sm mt-4">Senior High scheduling configuration is currently in development and will be available in the next update.</p>
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
      <p class="text-xs text-slate-500 mb-4">Configure scheduling constraints and priorities</p>

      <!-- Faculty Restrictions -->
      <div class="border rounded-lg p-4 bg-white mb-4">
        <div class="flex items-center justify-between mb-3">
          <h4 class="font-semibold text-slate-800">👥 Faculty Restrictions</h4>
          <span class="text-xs text-slate-500">Active: <span class="font-bold text-blue-600" id="restriction-count">1</span></span>
        </div>
        <div class="space-y-2 mb-3" id="restriction-list">
          <div class="restriction-item bg-slate-50 p-3 rounded flex items-center justify-between hover:bg-blue-50 hover:border-blue-300 border border-transparent transition cursor-pointer group">
            <div class="flex-1">
              <div class="font-semibold text-sm group-hover:text-blue-700">Dept. Heads</div>
              <div class="text-xs text-slate-600">Cannot teach P1</div>
            </div>
            <div class="flex gap-1 items-center">
              <span class="px-2 py-1 bg-red-100 text-red-700 rounded text-xs font-semibold">P1</span>
              <svg class="w-4 h-4 text-slate-400 group-hover:text-blue-600 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
              </svg>
            </div>
          </div>
        </div>
        <button type="button" id="add-restriction-btn" class="px-3 py-1.5 text-xs bg-blue-500 text-white rounded hover:bg-blue-600">+ Add Restriction</button>
      </div>

      <!-- Add Restriction Modal -->
      <div id="restriction-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50">
        <div class="flex items-center justify-center min-h-screen">
          <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4 shadow-xl">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-slate-900">➕ Add Faculty Restriction</h3>
            <button type="button" id="close-restriction-modal" class="text-slate-400 hover:text-slate-600">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
              </svg>
            </button>
          </div>

          <form id="restriction-form" class="space-y-4">
            <div>
              <label class="block text-sm font-semibold text-slate-700 mb-2">Who to restrict?</label>
              <select id="restriction-type" class="w-full border border-slate-300 rounded px-3 py-2 text-sm">
                <option value="">-- Select --</option>
                <option value="teacher">Specific Teacher</option>
                <option value="all-ancillary">All Teachers with Ancillary Tasks</option>
                @if($ancillaryRoles->count() > 0)
                  <option value="" disabled style="font-weight: bold; background-color: #f3f4f6;">── Ancillary Roles ──</option>
                  @foreach($ancillaryRoles as $role)
                    <option value="ancillary-{{ strtolower(str_replace(' ', '-', $role)) }}">&nbsp;&nbsp;{{ $role }}</option>
                  @endforeach
                @endif
              </select>
            </div>

            <div id="teacher-select-container" class="hidden">
              <label class="block text-sm font-semibold text-slate-700 mb-2">Select Teacher</label>
              <select id="teacher-select" class="w-full border border-slate-300 rounded px-3 py-2 text-sm">
                <option value="">-- Select Teacher --</option>
                <option value="Ruby Toledo">Ruby Toledo</option>
                <option value="Juan Dela Cruz">Juan Dela Cruz</option>
                <option value="Maria Santos">Maria Santos</option>
                <option value="Pedro Garcia">Pedro Garcia</option>
              </select>
            </div>

            <div>
              <label class="block text-sm font-semibold text-slate-700 mb-2">Cannot teach periods:</label>
              <div class="grid grid-cols-3 gap-2">
                <label class="flex items-center gap-2 p-2 border rounded hover:bg-blue-50 cursor-pointer">
                  <input type="checkbox" name="period" value="1" class="rounded">
                  <span class="text-sm">P1</span>
                </label>
                <label class="flex items-center gap-2 p-2 border rounded hover:bg-blue-50 cursor-pointer">
                  <input type="checkbox" name="period" value="2" class="rounded">
                  <span class="text-sm">P2</span>
                </label>
                <label class="flex items-center gap-2 p-2 border rounded hover:bg-blue-50 cursor-pointer">
                  <input type="checkbox" name="period" value="3" class="rounded">
                  <span class="text-sm">P3</span>
                </label>
                <label class="flex items-center gap-2 p-2 border rounded hover:bg-blue-50 cursor-pointer">
                  <input type="checkbox" name="period" value="4" class="rounded">
                  <span class="text-sm">P4</span>
                </label>
                <label class="flex items-center gap-2 p-2 border rounded hover:bg-blue-50 cursor-pointer">
                  <input type="checkbox" name="period" value="5" class="rounded">
                  <span class="text-sm">P5</span>
                </label>
                <label class="flex items-center gap-2 p-2 border rounded hover:bg-blue-50 cursor-pointer">
                  <input type="checkbox" name="period" value="6" class="rounded">
                  <span class="text-sm">P6</span>
                </label>
                <label class="flex items-center gap-2 p-2 border rounded hover:bg-blue-50 cursor-pointer">
                  <input type="checkbox" name="period" value="7" class="rounded">
                  <span class="text-sm">P7</span>
                </label>
                <label class="flex items-center gap-2 p-2 border rounded hover:bg-blue-50 cursor-pointer">
                  <input type="checkbox" name="period" value="8" class="rounded">
                  <span class="text-sm">P8</span>
                </label>
                <label class="flex items-center gap-2 p-2 border rounded hover:bg-blue-50 cursor-pointer">
                  <input type="checkbox" name="period" value="9" class="rounded">
                  <span class="text-sm">P9</span>
                </label>
              </div>
            </div>

            <div class="flex gap-2 pt-4 border-t">
              <button type="button" id="delete-restriction-btn" class="hidden px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 text-sm font-semibold">
                🗑️ Delete
              </button>
              <button type="button" id="cancel-restriction-btn" class="flex-1 px-4 py-2 bg-slate-200 text-slate-700 rounded hover:bg-slate-300 text-sm">
                Cancel
              </button>
              <button type="submit" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm font-semibold">
                💾 Save Restriction
              </button>
            </div>
          </form>
        </div>
        </div>
      </div>

      <!-- Subject Constraints -->
      <div class="border rounded-lg p-4 bg-white mb-4">
        <div class="flex items-center justify-between mb-3">
          <h4 class="font-semibold text-slate-800">📚 Subject Constraints</h4>
          <span class="text-xs text-slate-500">Active: <span class="font-bold text-blue-600" id="constraint-count">1</span></span>
        </div>
        <div class="space-y-2 mb-3" id="constraint-list">
          <div class="constraint-item bg-slate-50 p-3 rounded flex items-center justify-between hover:bg-blue-50 hover:border-blue-300 border border-transparent transition cursor-pointer group">
            <div class="flex-1">
              <div class="font-semibold text-sm group-hover:text-blue-700">Physical Education</div>
              <div class="text-xs text-slate-600">Avoid P1-P2 (heat concerns)</div>
            </div>
            <div class="flex gap-1 items-center">
              <span class="px-2 py-1 bg-red-100 text-red-700 rounded text-xs font-semibold">P1</span>
              <span class="px-2 py-1 bg-red-100 text-red-700 rounded text-xs font-semibold">P2</span>
              <svg class="w-4 h-4 text-slate-400 group-hover:text-blue-600 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
              </svg>
            </div>
          </div>
        </div>
        <button type="button" id="add-constraint-btn" class="px-3 py-1.5 text-xs bg-blue-500 text-white rounded hover:bg-blue-600">+ Add Constraint</button>
      </div>

      <!-- Add Subject Constraint Modal -->
      <div id="constraint-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50">
        <div class="flex items-center justify-center min-h-screen">
          <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4 shadow-xl">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-slate-900">➕ Add Subject Constraint</h3>
            <button type="button" id="close-constraint-modal" class="text-slate-400 hover:text-slate-600">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
              </svg>
            </button>
          </div>

          <form id="constraint-form" class="space-y-4">
            <div>
              <label class="block text-sm font-semibold text-slate-700 mb-2">Subject Name</label>
              <div class="relative">
                <input type="text" id="subject-search" class="w-full border border-slate-300 rounded px-3 py-2 text-sm" placeholder="Type to search or select a subject">
                <select id="subject-name" class="hidden">
                  <option value="">-- Select a subject --</option>
                  @foreach($subjects as $subject)
                    <option value="{{ $subject->id }}" data-name="{{ $subject->name }}">{{ $subject->name }}</option>
                  @endforeach
                </select>
                <div id="subject-dropdown" class="hidden absolute top-full left-0 right-0 mt-1 border border-slate-300 bg-white rounded shadow-lg z-50 max-h-48 overflow-y-auto">
                </div>
              </div>
            </div>

            <div>
              <label class="block text-sm font-semibold text-slate-700 mb-2">Avoid these periods:</label>
              <div class="grid grid-cols-3 gap-2">
                <label class="flex items-center gap-2 p-2 border rounded hover:bg-blue-50 cursor-pointer">
                  <input type="checkbox" name="constraint-period" value="1" class="rounded">
                  <span class="text-sm">P1</span>
                </label>
                <label class="flex items-center gap-2 p-2 border rounded hover:bg-blue-50 cursor-pointer">
                  <input type="checkbox" name="constraint-period" value="2" class="rounded">
                  <span class="text-sm">P2</span>
                </label>
                <label class="flex items-center gap-2 p-2 border rounded hover:bg-blue-50 cursor-pointer">
                  <input type="checkbox" name="constraint-period" value="3" class="rounded">
                  <span class="text-sm">P3</span>
                </label>
                <label class="flex items-center gap-2 p-2 border rounded hover:bg-blue-50 cursor-pointer">
                  <input type="checkbox" name="constraint-period" value="4" class="rounded">
                  <span class="text-sm">P4</span>
                </label>
                <label class="flex items-center gap-2 p-2 border rounded hover:bg-blue-50 cursor-pointer">
                  <input type="checkbox" name="constraint-period" value="5" class="rounded">
                  <span class="text-sm">P5</span>
                </label>
                <label class="flex items-center gap-2 p-2 border rounded hover:bg-blue-50 cursor-pointer">
                  <input type="checkbox" name="constraint-period" value="6" class="rounded">
                  <span class="text-sm">P6</span>
                </label>
                <label class="flex items-center gap-2 p-2 border rounded hover:bg-blue-50 cursor-pointer">
                  <input type="checkbox" name="constraint-period" value="7" class="rounded">
                  <span class="text-sm">P7</span>
                </label>
                <label class="flex items-center gap-2 p-2 border rounded hover:bg-blue-50 cursor-pointer">
                  <input type="checkbox" name="constraint-period" value="8" class="rounded">
                  <span class="text-sm">P8</span>
                </label>
                <label class="flex items-center gap-2 p-2 border rounded hover:bg-blue-50 cursor-pointer">
                  <input type="checkbox" name="constraint-period" value="9" class="rounded">
                  <span class="text-sm">P9</span>
                </label>
              </div>
            </div>

            <div>
              <label class="block text-sm font-semibold text-slate-700 mb-2">Reason (optional)</label>
              <input type="text" id="constraint-reason" class="w-full border border-slate-300 rounded px-3 py-2 text-sm" placeholder="e.g., heat concerns, lab availability">
            </div>

            <div class="flex gap-2 pt-4 border-t">
              <button type="button" id="delete-constraint-btn" class="hidden px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 text-sm font-semibold">
                🗑️ Delete
              </button>
              <button type="button" id="cancel-constraint-btn" class="flex-1 px-4 py-2 bg-slate-200 text-slate-700 rounded hover:bg-slate-300 text-sm">
                Cancel
              </button>
              <button type="submit" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm font-semibold">
                💾 Save Constraint
              </button>
            </div>
          </form>
        </div>
        </div>
      </div>

      <!-- Load Balancing -->
      <div class="border rounded-lg p-4 bg-white mb-4">
        <h4 class="font-semibold mb-4 text-slate-800">⚖️ Load Balancing Framework</h4>

        <!-- Teaching Load Limits Section -->
        <div class="border rounded-lg mb-4 bg-slate-50">
          <button type="button" onclick="toggleSection(this)" class="w-full flex items-center justify-between p-4 hover:bg-slate-100 transition cursor-pointer">
            <div class="flex items-center gap-2">
              <span class="text-lg">📊</span>
              <span class="font-semibold text-slate-700">Teaching Load Limits</span>
            </div>
            <span class="section-chevron text-slate-500 text-xl" style="display: inline-block; transition: transform 0.3s ease; transform: rotate(0deg);">▶</span>
          </button>
          <div class="section-content border-t border-slate-200 bg-white transition-all overflow-hidden" style="max-height: 0px; padding: 0; border: none;">
            <div class="grid grid-cols-2 gap-4 mb-4 p-4">
              <div>
                <label class="text-xs font-semibold text-slate-600 block mb-2">Minimum Units/Year</label>
                <input type="number" value="12" class="border border-slate-300 rounded px-3 py-2 w-full text-sm" min="6" max="30" />
                <p class="text-xs text-slate-500 mt-1">Minimum teaching load expected per faculty member</p>
              </div>
              <div>
                <label class="text-xs font-semibold text-slate-600 block mb-2">Maximum Units/Year</label>
                <input type="number" value="18" class="border border-slate-300 rounded px-3 py-2 w-full text-sm" min="12" max="30" />
                <p class="text-xs text-slate-500 mt-1">Maximum comfortable teaching load per faculty member</p>
              </div>
            </div>
            <div class="p-3 bg-blue-50 rounded-lg mb-4 border border-blue-200">
              <div class="text-xs font-semibold text-blue-900 mb-2">Safe Load Zone: 12–18 units</div>
              <div class="w-full bg-slate-300 rounded-full h-2 overflow-hidden">
                <div class="bg-gradient-to-r from-amber-400 via-green-500 to-amber-400 h-full" style="width: 50%;"></div>
              </div>
              <div class="flex justify-between text-xs text-slate-600 mt-1">
                <span>Min (12)</span>
                <span class="font-semibold text-green-700">Optimal</span>
                <span>Max (18)</span>
                <span class="text-red-600">⚠ Overload</span>
              </div>
            </div>
            <div class="grid grid-cols-2 gap-3">
              <div>
                <label class="text-xs text-slate-600 block mb-1">Max Consecutive Periods</label>
                <div class="flex items-center gap-2">
                  <input type="number" value="4" class="border border-slate-300 rounded px-3 py-2 flex-1 text-sm" min="2" max="9" />
                  <span class="text-xs text-slate-500 whitespace-nowrap">periods</span>
                </div>
              </div>
              <div>
                <label class="text-xs text-slate-600 block mb-1">Max Teaching Days/Week</label>
                <div class="flex items-center gap-2">
                  <input type="number" value="5" class="border border-slate-300 rounded px-3 py-2 flex-1 text-sm" min="1" max="6" />
                  <span class="text-xs text-slate-500 whitespace-nowrap">days</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Assignment Rules Section -->
        <div class="border rounded-lg mb-4 bg-slate-50">
          <button type="button" onclick="toggleSection(this)" class="w-full flex items-center justify-between p-4 hover:bg-slate-100 transition cursor-pointer">
            <div class="flex items-center gap-2">
              <span class="text-lg">📋</span>
              <span class="font-semibold text-slate-700">Assignment Rules</span>
            </div>
            <span class="section-chevron text-slate-500 text-xl" style="display: inline-block; transition: transform 0.3s ease; transform: rotate(0deg);">▶</span>
          </button>
          <div class="section-content border-t border-slate-200 bg-white transition-all overflow-hidden" style="max-height: 0px; padding: 0; border: none;">
            <div class="space-y-3 p-4">
              <label class="flex items-center gap-3 p-3 border border-slate-200 rounded-lg hover:bg-slate-50 cursor-pointer transition">
                <input type="checkbox" checked class="rounded w-4 h-4" />
                <div class="flex-1">
                  <div class="font-semibold text-sm text-slate-800">Specialization-First Matching</div>
                  <div class="text-xs text-slate-500">Assign subjects teachers are qualified/experienced in first</div>
                </div>
              </label>
              <label class="flex items-center gap-3 p-3 border border-slate-200 rounded-lg hover:bg-slate-50 cursor-pointer transition">
                <input type="checkbox" checked class="rounded w-4 h-4" />
                <div class="flex-1">
                  <div class="font-semibold text-sm text-slate-800">Fair Workload Distribution</div>
                  <div class="text-xs text-slate-500">Balance teaching load evenly across all faculty members</div>
                </div>
              </label>
              <label class="flex items-center gap-3 p-3 border border-slate-200 rounded-lg hover:bg-slate-50 cursor-pointer transition">
                <input type="checkbox" checked class="rounded w-4 h-4" />
                <div class="flex-1">
                  <div class="font-semibold text-sm text-slate-800">Minimize Schedule Gaps</div>
                  <div class="text-xs text-slate-500">Cluster teaching periods to reduce unproductive time</div>
                </div>
              </label>
              <label class="flex items-center gap-3 p-3 border border-slate-200 rounded-lg hover:bg-slate-50 cursor-pointer transition">
                <input type="checkbox" class="rounded w-4 h-4" />
                <div class="flex-1">
                  <div class="font-semibold text-sm text-slate-800">Honor Seniority Preferences</div>
                  <div class="text-xs text-slate-500">Consider tenure and experience in assignment priority</div>
                </div>
              </label>
              <label class="flex items-center gap-3 p-3 border border-slate-200 rounded-lg hover:bg-slate-50 cursor-pointer transition">
                <input type="checkbox" class="rounded w-4 h-4" />
                <div class="flex-1">
                  <div class="font-semibold text-sm text-slate-800">Core Subjects in AM Periods</div>
                  <div class="text-xs text-slate-500">Schedule Mathematics, Science, and English in morning periods</div>
                </div>
              </label>
            </div>
          </div>
        </div>

        <!-- Distribution Method Section -->
        <div class="border rounded-lg mb-4 bg-slate-50">
          <button type="button" onclick="toggleSection(this)" class="w-full flex items-center justify-between p-4 hover:bg-slate-100 transition cursor-pointer">
            <div class="flex items-center gap-2">
              <span class="text-lg">🔄</span>
              <span class="font-semibold text-slate-700">Distribution Algorithm</span>
            </div>
            <span class="section-chevron text-slate-500 text-xl" style="display: inline-block; transition: transform 0.3s ease; transform: rotate(0deg);">▶</span>
          </button>
          <div class="section-content border-t border-slate-200 bg-white transition-all overflow-hidden" style="max-height: 0px; padding: 0; border: none;">
            <div class="space-y-3 p-4">
              <label class="flex items-center gap-3 p-3 border-2 border-blue-300 rounded-lg bg-blue-50 cursor-pointer transition">
                <input type="radio" name="algorithm" value="round-robin" checked class="rounded w-4 h-4" />
                <div class="flex-1">
                  <div class="font-semibold text-sm text-slate-800">Round-Robin Distribution</div>
                  <div class="text-xs text-slate-600">Cycle through faculty sequentially, ensuring fair rotation</div>
                </div>
              </label>
              <label class="flex items-center gap-3 p-3 border border-slate-200 rounded-lg hover:bg-slate-50 cursor-pointer transition">
                <input type="radio" name="algorithm" value="weighted" class="rounded w-4 h-4" />
                <div class="flex-1">
                  <div class="font-semibold text-sm text-slate-800">Weighted Distribution</div>
                  <div class="text-xs text-slate-600">Use specialization, seniority, and preferences as weights</div>
                </div>
              </label>
              <label class="flex items-center gap-3 p-3 border border-slate-200 rounded-lg hover:bg-slate-50 cursor-pointer transition">
                <input type="radio" name="algorithm" value="demand-driven" class="rounded w-4 h-4" />
                <div class="flex-1">
                  <div class="font-semibold text-sm text-slate-800">Demand-Driven Allocation</div>
                  <div class="text-xs text-slate-600">Prioritize sections with high enrollment or critical needs</div>
                </div>
              </label>
            </div>
          </div>
        </div>

        <!-- Conflict Handling Section -->
        <div class="border rounded-lg mb-4 bg-slate-50">
          <button type="button" onclick="toggleSection(this)" class="w-full flex items-center justify-between p-4 hover:bg-slate-100 transition cursor-pointer">
            <div class="flex items-center gap-2">
              <span class="text-lg">⚠️</span>
              <span class="font-semibold text-slate-700">Conflict Handling</span>
            </div>
            <span class="section-chevron text-slate-500 text-xl" style="display: inline-block; transition: transform 0.3s ease; transform: rotate(0deg);">▶</span>
          </button>
          <div class="section-content border-t border-slate-200 bg-white transition-all overflow-hidden" style="max-height: 0px; padding: 0; border: none;">
            <div class="mb-3 p-4">
              <p class="text-xs text-slate-600 mb-3">See <span class="font-semibold">Conflict Detection & Resolution</span> section below for detailed conflict rules and handling strategies.</p>
              <div class="p-3 bg-amber-50 border border-amber-200 rounded-lg">
                <div class="text-xs font-semibold text-amber-900">Load Balancing Conflict Priority</div>
                <div class="text-xs text-amber-800 mt-1">Conflicts in achieving optimal load (12-18 units) are secondary to critical scheduling conflicts.</div>
              </div>
            </div>
          </div>
        </div>

        <!-- Metrics & Tracking Section -->
        <div class="border rounded-lg bg-slate-50">
          <button type="button" onclick="toggleSection(this)" class="w-full flex items-center justify-between p-4 hover:bg-slate-100 transition cursor-pointer">
            <div class="flex items-center gap-2">
              <span class="text-lg">📈</span>
              <span class="font-semibold text-slate-700">Metrics & Tracking</span>
            </div>
            <span class="section-chevron text-slate-500 text-xl" style="display: inline-block; transition: transform 0.3s ease; transform: rotate(0deg);">▶</span>
          </button>
          <div class="section-content border-t border-slate-200 bg-white transition-all overflow-hidden" style="max-height: 0px; padding: 0; border: none;">
            <div class="space-y-2 text-sm p-4">
              <div class="flex items-center gap-2 p-2 bg-slate-100 rounded">
                <span class="text-lg">✓</span>
                <span class="text-xs text-slate-700"><strong>Average teaching load:</strong> Mean units per faculty member</span>
              </div>
              <div class="flex items-center gap-2 p-2 bg-slate-100 rounded">
                <span class="text-lg">✓</span>
                <span class="text-xs text-slate-700"><strong>Load variance:</strong> Std deviation from average (lower = fairer)</span>
              </div>
              <div class="flex items-center gap-2 p-2 bg-slate-100 rounded">
                <span class="text-lg">✓</span>
                <span class="text-xs text-slate-700"><strong>Faculty within safe zone:</strong> % of teachers in 12-18 unit range</span>
              </div>
              <div class="flex items-center gap-2 p-2 bg-slate-100 rounded">
                <span class="text-lg">✓</span>
                <span class="text-xs text-slate-700"><strong>Specialization match rate:</strong> % of assignments matching qualifications</span>
              </div>
              <div class="flex items-center gap-2 p-2 bg-slate-100 rounded">
                <span class="text-lg">✓</span>
                <span class="text-xs text-slate-700"><strong>Schedule efficiency:</strong> Avg gap time (lower = better continuity)</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Conflict Detection & Resolution -->
      <div class="border rounded-lg p-4 bg-white mb-4">
        <h4 class="font-semibold mb-3 text-slate-800">🚨 Conflict Detection & Resolution</h4>
        <div class="space-y-2">
          <div class="flex items-center gap-3 p-3 bg-red-50 rounded hover:bg-red-100 transition">
            <input type="checkbox" checked class="rounded" />
            <div class="w-8 h-8 bg-red-500 text-white rounded-full flex items-center justify-center font-bold text-sm flex-shrink-0">!</div>
            <div class="flex-1">
              <div class="font-semibold text-sm">Critical Conflicts</div>
              <div class="text-xs text-slate-600">Double-booking, room conflicts, missing teacher</div>
            </div>
            <select class="text-xs border border-slate-300 rounded px-2 py-1 bg-white">
              <option>Block Save</option>
              <option>Warn Only</option>
            </select>
          </div>
          <div class="flex items-center gap-3 p-3 bg-amber-50 rounded hover:bg-amber-100 transition">
            <input type="checkbox" checked class="rounded" />
            <div class="w-8 h-8 bg-amber-500 text-white rounded-full flex items-center justify-center font-bold text-sm flex-shrink-0">⚠</div>
            <div class="flex-1">
              <div class="font-semibold text-sm">High Priority</div>
              <div class="text-xs text-slate-600">Excessive consecutive periods, break violations</div>
            </div>
            <select class="text-xs border border-slate-300 rounded px-2 py-1 bg-white">
              <option>Warn & Allow</option>
              <option>Block Save</option>
              <option>Ignore</option>
            </select>
          </div>
          <div class="flex items-center gap-3 p-3 bg-blue-50 rounded hover:bg-blue-100 transition">
            <input type="checkbox" checked class="rounded" />
            <div class="w-8 h-8 bg-blue-500 text-white rounded-full flex items-center justify-center font-bold text-sm flex-shrink-0">i</div>
            <div class="flex-1">
              <div class="font-semibold text-sm">Medium Priority</div>
              <div class="text-xs text-slate-600">Period preferences, minor workload imbalances</div>
            </div>
            <select class="text-xs border border-slate-300 rounded px-2 py-1 bg-white">
              <option>Suggest</option>
              <option>Warn</option>
              <option>Ignore</option>
            </select>
          </div>
        </div>
      </div>

      <!-- Advanced Settings -->
      <div class="border rounded-lg p-4 bg-white mb-4">
        <h4 class="font-semibold mb-3 text-slate-800">⚙️ Advanced Settings</h4>
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="text-xs text-slate-600 block mb-1">Auto-save interval</label>
            <select class="w-full border border-slate-300 rounded px-3 py-2 text-sm bg-white">
              <option>Every change</option>
              <option>Every 30 seconds</option>
              <option>Every minute</option>
              <option>Manual only</option>
            </select>
          </div>
          <div>
            <label class="text-xs text-slate-600 block mb-1">Conflict resolution priority</label>
            <select class="w-full border border-slate-300 rounded px-3 py-2 text-sm bg-white">
              <option>Teacher availability first</option>
              <option>Room availability first</option>
              <option>Balanced approach</option>
            </select>
          </div>
        </div>
      </div>

      <!-- Save Button -->
      <div class="border-t pt-4 mt-4 flex justify-between items-center gap-3">
        <div class="text-xs text-slate-500">
          <span class="font-semibold">Note:</span> Changes are currently saved locally (demo mode)
        </div>
        <div class="flex gap-2">
          <button type="button" class="px-4 py-2 bg-slate-200 text-slate-700 rounded hover:bg-slate-300 text-sm">
            Reset to Defaults
          </button>
          <button type="button" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm font-semibold">
            💾 Save Constraints
          </button>
        </div>
      </div>
    </div>

  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  if ('scrollRestoration' in window.history) {
    window.history.scrollRestoration = 'manual';
  }

  // Toggle Collapsible Sections (Load Balancing)
  function toggleSection(button) {
    const content = button.nextElementSibling;
    const icon = button.querySelector('.section-chevron');
    
    if (content.style.maxHeight === '0px' || !content.style.maxHeight) {
      content.style.maxHeight = content.scrollHeight + 'px';
      icon.style.transform = 'rotate(90deg)';
    } else {
      content.style.maxHeight = '0px';
      icon.style.transform = 'rotate(0deg)';
    }
  }

  // Make toggleSection available globally
  window.toggleSection = toggleSection;

  const tabs = document.querySelectorAll('.settings-tab');
  const tabContents = document.querySelectorAll('.settings-tab-content');

  function switchTab(tabName) {
    // Scroll to top
    window.scrollTo(0, 0);
    document.documentElement.scrollTop = 0;
    document.body.scrollTop = 0;

    // Update tab styles
    tabs.forEach(t => {
      t.classList.remove('border-b-2', 'border-blue-600', 'border-green-600', 'text-blue-600', 'text-green-600');
      t.classList.add('text-slate-500');
    });

    const activeTab = document.querySelector(`[data-tab="${tabName}"]`);
    if (activeTab) {
      activeTab.classList.remove('text-slate-500');
      if (tabName === 'sh') {
        activeTab.classList.add('border-b-2', 'border-green-600', 'text-green-600');
      } else {
        activeTab.classList.add('border-b-2', 'border-blue-600', 'text-blue-600');
      }
    }

    // Hide all, show one
    tabContents.forEach(content => content.classList.add('hidden'));
    const tab = document.getElementById('tab-' + tabName);
    if (tab) tab.classList.remove('hidden');

    // Scroll again after layout
    setTimeout(() => window.scrollTo(0, 0), 10);
  }

  tabs.forEach(tab => {
    tab.addEventListener('click', function() {
      switchTab(this.getAttribute('data-tab'));
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

  // ============================================
  // Add Restriction Modal Functionality
  // ============================================
  const restrictionModal = document.getElementById('restriction-modal');
  const addRestrictionBtn = document.getElementById('add-restriction-btn');
  const closeRestrictionModal = document.getElementById('close-restriction-modal');
  const cancelRestrictionBtn = document.getElementById('cancel-restriction-btn');
  const deleteRestrictionBtn = document.getElementById('delete-restriction-btn');
  const restrictionForm = document.getElementById('restriction-form');
  const restrictionType = document.getElementById('restriction-type');
  const teacherSelectContainer = document.getElementById('teacher-select-container');
  const teacherSelect = document.getElementById('teacher-select');
  const restrictionList = document.getElementById('restriction-list');
  const restrictionCount = document.getElementById('restriction-count');
  
  let editingItem = null; // Track which item we're editing

  // Function to update restriction count
  function updateRestrictionCount() {
    const currentCount = restrictionList.querySelectorAll('.restriction-item').length;
    restrictionCount.textContent = currentCount;
  }

  // Function to extract periods from an item
  function extractPeriodsFromItem(item) {
    const periods = [];
    item.querySelectorAll('.bg-red-100').forEach(badge => {
      const match = badge.textContent.match(/P(\d+)/);
      if (match) {
        periods.push(parseInt(match[1]));
      }
    });
    return periods;
  }

  // Function to extract restriction data from item
  function extractRestrictionData(item) {
    const name = item.querySelector('.font-semibold').textContent.trim();
    const periods = extractPeriodsFromItem(item);
    
    // Determine type based on name
    let type = '';
    let teacherName = '';
    let ancillaryRole = '';
    
    if (name === 'All Teachers with Ancillary Tasks') {
      type = 'all-ancillary';
    } else if (name.includes('Department Head') || name.includes('ICT Coordinator') || name.includes('Coordinator') || name.includes('Head') || name.includes('Director') || name.includes('Adviser')) {
      // It's an ancillary role
      type = 'ancillary-' + name.toLowerCase().replace(/ /g, '-');
      ancillaryRole = name;
    } else {
      type = 'teacher';
      teacherName = name;
    }
    
    return { type, teacherName, ancillaryRole, periods };
  }

  // Function to open modal in add mode
  function openAddMode() {
    editingItem = null;
    restrictionForm.reset();
    teacherSelectContainer.classList.add('hidden');
    deleteRestrictionBtn.classList.add('hidden');
    restrictionModal.classList.remove('hidden');
  }

  // Function to open modal in edit mode
  function openEditMode(item) {
    editingItem = item;
    const data = extractRestrictionData(item);
    
    // Set restriction type
    restrictionType.value = data.type;
    
    // Show/set teacher select if needed
    if (data.type === 'teacher') {
      teacherSelectContainer.classList.remove('hidden');
      teacherSelect.value = data.teacherName;
    } else {
      teacherSelectContainer.classList.add('hidden');
    }
    
    // Check the periods
    document.querySelectorAll('input[name="period"]').forEach(cb => {
      cb.checked = data.periods.includes(parseInt(cb.value));
    });
    
    // Show delete button
    deleteRestrictionBtn.classList.remove('hidden');
    restrictionModal.classList.remove('hidden');
  }

  // Function to attach click handler to restriction items
  function attachClickHandler(item) {
    item.addEventListener('click', function(e) {
      openEditMode(this);
    });
  }

  // Attach click handlers to existing restrictions
  document.querySelectorAll('#restriction-list .restriction-item').forEach(item => {
    attachClickHandler(item);
  });

  // Open modal in add mode
  addRestrictionBtn.addEventListener('click', openAddMode);

  // Close modal
  function closeModal() {
    restrictionModal.classList.add('hidden');
    restrictionForm.reset();
    editingItem = null;
    deleteRestrictionBtn.classList.add('hidden');
  }

  closeRestrictionModal.addEventListener('click', closeModal);
  cancelRestrictionBtn.addEventListener('click', closeModal);

  // Handle delete button
  deleteRestrictionBtn.addEventListener('click', function() {
    if (editingItem) {
      const name = editingItem.querySelector('.font-semibold').textContent;
      const description = editingItem.querySelector('.text-xs').textContent;
      
      if (confirm(`Delete this restriction?\n\n${name}\n${description}`)) {
        editingItem.remove();
        updateRestrictionCount();
        closeModal();
      }
    }
  });

  // Close modal when clicking backdrop
  restrictionModal.addEventListener('click', function(e) {
    if (e.target === restrictionModal) {
      closeModal();
    }
  });

  // Show/hide teacher select based on restriction type
  restrictionType.addEventListener('change', function() {
    if (this.value === 'teacher') {
      teacherSelectContainer.classList.remove('hidden');
    } else {
      teacherSelectContainer.classList.add('hidden');
    }
  });

  // Handle form submission
  restrictionForm.addEventListener('submit', function(e) {
    e.preventDefault();

    const type = restrictionType.value;
    if (!type) {
      alert('Please select who to restrict');
      return;
    }

    // Get selected periods
    const selectedPeriods = [];
    document.querySelectorAll('input[name="period"]:checked').forEach(cb => {
      selectedPeriods.push(parseInt(cb.value));
    });

    if (selectedPeriods.length === 0) {
      alert('Please select at least one period');
      return;
    }

    // Determine the display name
    let displayName = '';
    let description = '';

    if (type === 'all-ancillary') {
      displayName = 'All Teachers with Ancillary Tasks';
      description = 'Cannot teach ' + formatPeriods(selectedPeriods);
    } else if (type === 'teacher') {
      const teacherName = teacherSelect.value;
      if (!teacherName) {
        alert('Please select a teacher');
        return;
      }
      displayName = teacherName;
      description = 'Cannot teach ' + formatPeriods(selectedPeriods);
    } else if (type.startsWith('ancillary-')) {
      // Extract role name from value (e.g., "ancillary-department-head" -> "Department Head")
      const roleSlug = type.replace('ancillary-', '');
      const roleName = roleSlug.split('-').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ');
      displayName = roleName;
      description = 'Cannot teach ' + formatPeriods(selectedPeriods);
    }

    // Create HTML for restriction
    const periodBadges = selectedPeriods.map(p => 
      `<span class="px-2 py-1 bg-red-100 text-red-700 rounded text-xs font-semibold">P${p}</span>`
    ).join(' ');

    const restrictionHTML = `
      <div class="flex-1">
        <div class="font-semibold text-sm group-hover:text-blue-700">${displayName}</div>
        <div class="text-xs text-slate-600">${description}</div>
      </div>
      <div class="flex gap-1 items-center">
        ${periodBadges}
        <svg class="w-4 h-4 text-slate-400 group-hover:text-blue-600 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
      </div>
    `;

    if (editingItem) {
      // Update existing item
      editingItem.innerHTML = restrictionHTML;
    } else {
      // Create new item
      const newRestriction = document.createElement('div');
      newRestriction.className = 'restriction-item bg-slate-50 p-3 rounded flex items-center justify-between hover:bg-blue-50 hover:border-blue-300 border border-transparent transition cursor-pointer group';
      newRestriction.innerHTML = restrictionHTML;
      
      // Add to list
      restrictionList.appendChild(newRestriction);
      
      // Attach click handler to new item
      attachClickHandler(newRestriction);
      
      // Update count
      updateRestrictionCount();
    }

    // Close modal
    closeModal();
  });

  // Helper function to format periods
  function formatPeriods(periods) {
    periods.sort((a, b) => a - b);
    if (periods.length === 1) {
      return 'P' + periods[0];
    } else if (periods.length === 2) {
      return 'P' + periods[0] + ' and P' + periods[1];
    } else if (isConsecutive(periods)) {
      return 'P' + periods[0] + '-P' + periods[periods.length - 1];
    } else {
      return 'P' + periods.join(', P');
    }
  }

  function isConsecutive(arr) {
    for (let i = 1; i < arr.length; i++) {
      if (arr[i] !== arr[i - 1] + 1) return false;
    }
    return true;
  }

  // ============================================
  // Subject Constraint Modal Functionality
  // ============================================
  const constraintModal = document.getElementById('constraint-modal');
  const addConstraintBtn = document.getElementById('add-constraint-btn');
  const closeConstraintModalBtn = document.getElementById('close-constraint-modal');
  const cancelConstraintBtn = document.getElementById('cancel-constraint-btn');
  const deleteConstraintBtn = document.getElementById('delete-constraint-btn');
  const constraintForm = document.getElementById('constraint-form');
  const subjectNameInput = document.getElementById('subject-name');
  const subjectSearchInput = document.getElementById('subject-search');
  const subjectDropdown = document.getElementById('subject-dropdown');
  const constraintReasonInput = document.getElementById('constraint-reason');
  const constraintList = document.getElementById('constraint-list');
  const constraintCount = document.getElementById('constraint-count');
  
  let editingConstraintItem = null;

  // Subject search functionality
  function populateSubjectDropdown(filter = '') {
    const options = subjectNameInput.querySelectorAll('option');
    subjectDropdown.innerHTML = '';
    
    options.forEach(option => {
      if (option.value === '') return; // Skip empty option
      
      const name = option.dataset.name || option.textContent;
      if (filter === '' || name.toLowerCase().includes(filter.toLowerCase())) {
        const div = document.createElement('div');
        div.className = 'px-3 py-2 hover:bg-blue-100 cursor-pointer text-sm border-b last:border-b-0';
        div.textContent = name;
        div.dataset.value = option.value;
        div.dataset.name = name;
        
        div.addEventListener('click', function() {
          subjectNameInput.value = this.dataset.value;
          subjectSearchInput.value = this.dataset.name;
          subjectDropdown.classList.add('hidden');
        });
        
        subjectDropdown.appendChild(div);
      }
    });
    
    // Show dropdown if there are results
    if (subjectDropdown.children.length > 0) {
      subjectDropdown.classList.remove('hidden');
    } else {
      subjectDropdown.classList.add('hidden');
    }
  }

  // Search input event listener
  subjectSearchInput.addEventListener('input', function(e) {
    populateSubjectDropdown(e.target.value);
  });

  // Open dropdown on focus
  subjectSearchInput.addEventListener('focus', function() {
    populateSubjectDropdown(this.value);
  });

  // Close dropdown when clicking outside
  document.addEventListener('click', function(e) {
    if (e.target !== subjectSearchInput && e.target !== subjectDropdown && !subjectDropdown.contains(e.target)) {
      subjectDropdown.classList.add('hidden');
    }
  });


  // Function to update constraint count
  function updateConstraintCount() {
    const currentCount = constraintList.querySelectorAll('.constraint-item').length;
    constraintCount.textContent = currentCount;
  }

  // Function to extract constraint data from item
  function extractConstraintData(item) {
    const subjectName = item.querySelector('.font-semibold').textContent.trim();
    const descriptionText = item.querySelector('.text-xs').textContent.trim();
    
    // Extract reason from description (format: "Avoid P1-P2 (reason)")
    let reason = '';
    const reasonMatch = descriptionText.match(/\(([^)]+)\)/);
    if (reasonMatch) {
      reason = reasonMatch[1];
    }
    
    // Extract periods
    const periods = [];
    item.querySelectorAll('.bg-red-100').forEach(badge => {
      const match = badge.textContent.match(/P(\d+)/);
      if (match) {
        periods.push(parseInt(match[1]));
      }
    });
    
    return { subjectName, reason, periods };
  }

  // Function to open constraint modal in add mode
  function openConstraintAddMode() {
    editingConstraintItem = null;
    constraintForm.reset();
    subjectSearchInput.value = '';
    subjectNameInput.value = '';
    subjectDropdown.classList.add('hidden');
    deleteConstraintBtn.classList.add('hidden');
    constraintModal.classList.remove('hidden');
  }

  // Function to open constraint modal in edit mode
  function openConstraintEditMode(item) {
    editingConstraintItem = item;
    const data = extractConstraintData(item);
    
    // Set subject name - find the option by text and select it
    let found = false;
    for (let i = 0; i < subjectNameInput.options.length; i++) {
      if (subjectNameInput.options[i].dataset.name === data.subjectName) {
        subjectNameInput.value = subjectNameInput.options[i].value;
        subjectSearchInput.value = data.subjectName;
        found = true;
        break;
      }
    }
    
    // If subject not found in dropdown, just clear the values
    if (!found) {
      subjectNameInput.value = '';
      subjectSearchInput.value = '';
    }
    
    // Set reason
    constraintReasonInput.value = data.reason;
    
    // Check the periods
    document.querySelectorAll('input[name="constraint-period"]').forEach(cb => {
      cb.checked = data.periods.includes(parseInt(cb.value));
    });
    
    // Show delete button
    deleteConstraintBtn.classList.remove('hidden');
    constraintModal.classList.remove('hidden');
  }

  // Function to attach click handler to constraint items
  function attachConstraintClickHandler(item) {
    item.addEventListener('click', function(e) {
      openConstraintEditMode(this);
    });
  }

  // Attach click handlers to existing constraints
  document.querySelectorAll('#constraint-list .constraint-item').forEach(item => {
    attachConstraintClickHandler(item);
  });

  // Open modal in add mode
  addConstraintBtn.addEventListener('click', openConstraintAddMode);

  // Close constraint modal
  function closeConstraintModalFunc() {
    constraintModal.classList.add('hidden');
    constraintForm.reset();
    editingConstraintItem = null;
    deleteConstraintBtn.classList.add('hidden');
  }

  closeConstraintModalBtn.addEventListener('click', closeConstraintModalFunc);
  cancelConstraintBtn.addEventListener('click', closeConstraintModalFunc);

  // Close modal when clicking backdrop
  constraintModal.addEventListener('click', function(e) {
    if (e.target === constraintModal) {
      closeConstraintModalFunc();
    }
  });

  // Handle delete button
  deleteConstraintBtn.addEventListener('click', function() {
    if (editingConstraintItem) {
      const subjectName = editingConstraintItem.querySelector('.font-semibold').textContent;
      const description = editingConstraintItem.querySelector('.text-xs').textContent;
      
      if (confirm(`Delete this constraint?\n\n${subjectName}\n${description}`)) {
        editingConstraintItem.remove();
        updateConstraintCount();
        closeConstraintModalFunc();
      }
    }
  });

  // Handle constraint form submission
  constraintForm.addEventListener('submit', function(e) {
    e.preventDefault();

    const subjectId = subjectNameInput.value.trim();
    if (!subjectId) {
      alert('Please select a subject');
      return;
    }

    const subjectName = subjectSearchInput.value.trim();

    // Get selected periods
    const selectedPeriods = [];
    document.querySelectorAll('input[name="constraint-period"]:checked').forEach(cb => {
      selectedPeriods.push(parseInt(cb.value));
    });

    if (selectedPeriods.length === 0) {
      alert('Please select at least one period');
      return;
    }

    // Build description
    const reason = constraintReasonInput.value.trim();
    let description = 'Avoid ' + formatPeriods(selectedPeriods);
    if (reason) {
      description += ' (' + reason + ')';
    }

    // Create HTML for constraint
    const periodBadges = selectedPeriods.map(p => 
      `<span class="px-2 py-1 bg-red-100 text-red-700 rounded text-xs font-semibold">P${p}</span>`
    ).join(' ');

    const constraintHTML = `
      <div class="flex-1">
        <div class="font-semibold text-sm group-hover:text-blue-700">${subjectName}</div>
        <div class="text-xs text-slate-600">${description}</div>
      </div>
      <div class="flex gap-1 items-center">
        ${periodBadges}
        <svg class="w-4 h-4 text-slate-400 group-hover:text-blue-600 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
      </div>
    `;

    if (editingConstraintItem) {
      // Update existing item
      editingConstraintItem.innerHTML = constraintHTML;
    } else {
      // Create new item
      const newConstraint = document.createElement('div');
      newConstraint.className = 'constraint-item bg-slate-50 p-3 rounded flex items-center justify-between hover:bg-blue-50 hover:border-blue-300 border border-transparent transition cursor-pointer group';
      newConstraint.innerHTML = constraintHTML;
      
      // Add to list
      constraintList.appendChild(newConstraint);
      
      // Attach click handler to new item
      attachConstraintClickHandler(newConstraint);
      
      // Update count
      updateConstraintCount();
    }

    // Close modal
    closeConstraintModalFunc();
  });
});
</script>
@endsection
