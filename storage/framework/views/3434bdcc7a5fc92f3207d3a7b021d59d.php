

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
      <h3 class="text-2xl font-bold text-slate-900 mb-2">📘 Junior High Configuration</h3>
      <p class="text-sm text-slate-600 mb-6">Configure scheduling parameters for Junior High (Grades 7-10)</p>

      <!-- Session Type Selector -->
      <div class="mb-8 p-6 bg-gradient-to-r from-blue-50 to-indigo-50 border-2 border-blue-200 rounded-lg">
        <label class="block text-sm font-bold text-slate-700 mb-3">Select Session Type to Configure:</label>
        <div class="flex gap-4">
          <label class="flex-1 cursor-pointer">
            <input type="radio" name="jh-session" value="regular" checked class="peer hidden" />
            <div class="p-4 border-2 rounded-lg bg-white transition-all peer-checked:border-blue-600 peer-checked:bg-blue-50 peer-checked:shadow-lg hover:border-blue-400">
              <div class="font-bold text-lg text-slate-900">📚 Regular Sessions</div>
              <div class="text-sm text-slate-600">Tuesday - Friday (full day)</div>
            </div>
          </label>
          <label class="flex-1 cursor-pointer">
            <input type="radio" name="jh-session" value="shortened" class="peer hidden" />
            <div class="p-4 border-2 rounded-lg bg-white transition-all peer-checked:border-blue-600 peer-checked:bg-blue-50 peer-checked:shadow-lg hover:border-blue-400">
              <div class="font-bold text-lg text-slate-900">⚡ Shortened Sessions</div>
              <div class="text-sm text-slate-600">Monday only (condensed schedule)</div>
            </div>
          </label>
        </div>
      </div>

      <!-- Regular Session Configuration -->
      <div id="jh-regular-config" class="session-config space-y-6">
        
        <!-- School Calendar -->
        <div class="border rounded-lg p-6 bg-white shadow-sm">
          <h4 class="text-lg font-semibold mb-4 text-slate-800">📅 School Calendar</h4>
          <div class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-3">Operating Days for Regular Sessions</label>
              <div class="flex gap-3">
                <div class="w-16 h-16 rounded-full border-2 bg-slate-100 border-slate-300 text-slate-400 flex items-center justify-center font-medium cursor-not-allowed" title="Monday is Shortened Session Day">
                  Mon
                </div>
                <div class="w-16 h-16 rounded-full border-2 bg-blue-500 border-blue-600 text-white flex items-center justify-center font-medium">
                  Tue
                </div>
                <div class="w-16 h-16 rounded-full border-2 bg-blue-500 border-blue-600 text-white flex items-center justify-center font-medium">
                  Wed
                </div>
                <div class="w-16 h-16 rounded-full border-2 bg-blue-500 border-blue-600 text-white flex items-center justify-center font-medium">
                  Thu
                </div>
                <div class="w-16 h-16 rounded-full border-2 bg-blue-500 border-blue-600 text-white flex items-center justify-center font-medium">
                  Fri
                </div>
                <div class="w-16 h-16 rounded-full border-2 bg-white border-slate-300 text-slate-500 flex items-center justify-center font-medium cursor-not-allowed" title="Weekend - Not Operating">
                  Sat
                </div>
                <div class="w-16 h-16 rounded-full border-2 bg-white border-slate-300 text-slate-500 flex items-center justify-center font-medium cursor-not-allowed" title="Weekend - Not Operating">
                  Sun
                </div>
              </div>
              <p class="text-xs text-slate-500 mt-2">
                <span class="inline-block w-3 h-3 bg-blue-500 border-blue-600 rounded-full mr-1"></span> Active
                <span class="inline-block w-3 h-3 bg-slate-100 border-slate-300 rounded-full ml-3 mr-1"></span> Shortened Session Day
                <span class="inline-block w-3 h-3 bg-white border-slate-300 rounded-full ml-3 mr-1"></span> Not Operating
              </p>
            </div>
          </div>
        </div>

        <!-- Period Structure -->
        <div class="border rounded-lg p-6 bg-white shadow-sm">
          <h4 class="text-lg font-semibold mb-4 text-slate-800">🕐 Period Structure</h4>
          <div class="grid grid-cols-2 gap-6 mb-6">
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-2">Class Duration</label>
              <div class="flex items-center gap-3">
                <input type="number" value="60" disabled class="input w-32 bg-slate-50 cursor-not-allowed" />
                <span class="text-sm text-slate-600">minutes</span>
              </div>
              <p class="text-xs text-slate-500 mt-1">Length of each class period</p>
            </div>
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-2">Number of Periods</label>
              <input type="number" value="9" disabled class="input w-32 bg-slate-50 cursor-not-allowed" />
              <p class="text-xs text-slate-500 mt-1">Total periods in the day</p>
            </div>
          </div>
          
          <div class="bg-slate-50 p-4 rounded border">
            <h5 class="text-sm font-semibold text-slate-700 mb-3">📊 Calculated Period Schedule</h5>
            <div class="grid grid-cols-3 gap-3">
              <div class="text-xs"><span class="font-semibold">P1:</span> 7:30 - 8:30 AM</div>
              <div class="text-xs"><span class="font-semibold">P2:</span> 8:30 - 9:30 AM</div>
              <div class="text-xs"><span class="font-semibold">P3:</span> 9:30 - 10:30 AM</div>
              <div class="text-xs text-amber-700 bg-amber-50 px-2 py-1 rounded col-span-3">☕ Morning Break (20 min) - 10:30 - 10:50 AM</div>
              <div class="text-xs"><span class="font-semibold">P4:</span> 10:50 - 11:50 AM</div>
              <div class="text-xs"><span class="font-semibold">P5:</span> 11:50 AM - 12:50 PM</div>
              <div class="text-xs text-orange-700 bg-orange-50 px-2 py-1 rounded col-span-3">🍱 Lunch Break (60 min) - 12:50 - 1:50 PM</div>
              <div class="text-xs"><span class="font-semibold">P6:</span> 1:50 - 2:50 PM</div>
              <div class="text-xs"><span class="font-semibold">P7:</span> 2:50 - 3:50 PM</div>
              <div class="text-xs"><span class="font-semibold">P8:</span> 3:50 - 4:50 PM</div>
              <div class="text-xs"><span class="font-semibold">P9:</span> 4:50 - 5:50 PM</div>
            </div>
            <p class="text-xs text-slate-500 mt-3">Times automatically calculated based on class duration and break schedule</p>
          </div>
        </div>

        <!-- Break Schedule -->
        <div class="border rounded-lg p-6 bg-white shadow-sm">
          <h4 class="text-lg font-semibold mb-4 text-slate-800">☕ Break Schedule</h4>
          <div class="space-y-4">
            <div class="grid grid-cols-2 gap-6">
              <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Morning Break</label>
                <div class="flex items-center gap-2">
                  <span class="text-sm text-slate-600">After Period</span>
                  <input type="number" value="3" disabled class="input w-20 bg-slate-50 cursor-not-allowed" />
                  <span class="text-sm text-slate-600">for</span>
                  <input type="number" value="20" disabled class="input w-20 bg-slate-50 cursor-not-allowed" />
                  <span class="text-sm text-slate-600">min</span>
                </div>
              </div>
              <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Lunch Break</label>
                <div class="flex items-center gap-2">
                  <span class="text-sm text-slate-600">After Period</span>
                  <input type="number" value="5" disabled class="input w-20 bg-slate-50 cursor-not-allowed" />
                  <span class="text-sm text-slate-600">for</span>
                  <input type="number" value="60" disabled class="input w-20 bg-slate-50 cursor-not-allowed" />
                  <span class="text-sm text-slate-600">min</span>
                </div>
              </div>
            </div>
            <div>
              <label class="flex items-center gap-2">
                <input type="checkbox" disabled class="rounded bg-slate-50 cursor-not-allowed" />
                <span class="text-sm text-slate-700">Include Afternoon Break</span>
              </label>
              <p class="text-xs text-slate-500 ml-6 mt-1">Junior High does not have afternoon break</p>
            </div>
          </div>
        </div>

        <!-- Session-Specific Rules -->
        <div class="border rounded-lg p-6 bg-white shadow-sm">
          <h4 class="text-lg font-semibold mb-4 text-slate-800">📋 Session-Specific Rules</h4>
          <div class="space-y-3">
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-2">Maximum Consecutive Periods</label>
              <input type="number" value="4" disabled class="input w-32 bg-slate-50 cursor-not-allowed" />
              <p class="text-xs text-slate-500 mt-1">Maximum periods a teacher can teach without break</p>
            </div>
            <div>
              <label class="flex items-center gap-2">
                <input type="checkbox" checked disabled class="rounded bg-slate-50 cursor-not-allowed" />
                <span class="text-sm text-slate-700">Ensure minimum 3 periods before morning break</span>
              </label>
            </div>
          </div>
        </div>

      </div>

      <!-- Shortened Session Configuration -->
      <div id="jh-shortened-config" class="session-config space-y-6 hidden">
        
        <!-- School Calendar -->
        <div class="border rounded-lg p-6 bg-white shadow-sm">
          <h4 class="text-lg font-semibold mb-4 text-slate-800">📅 School Calendar</h4>
          <div class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-3">Operating Days for Shortened Sessions</label>
              <div class="flex gap-3">
                <div class="w-16 h-16 rounded-full border-2 bg-amber-500 border-amber-600 text-white flex items-center justify-center font-medium">
                  Mon
                </div>
                <div class="w-16 h-16 rounded-full border-2 bg-white border-slate-300 text-slate-500 flex items-center justify-center font-medium cursor-not-allowed" title="Tuesday is Regular Session Day">
                  Tue
                </div>
                <div class="w-16 h-16 rounded-full border-2 bg-white border-slate-300 text-slate-500 flex items-center justify-center font-medium cursor-not-allowed" title="Wednesday is Regular Session Day">
                  Wed
                </div>
                <div class="w-16 h-16 rounded-full border-2 bg-white border-slate-300 text-slate-500 flex items-center justify-center font-medium cursor-not-allowed" title="Thursday is Regular Session Day">
                  Thu
                </div>
                <div class="w-16 h-16 rounded-full border-2 bg-white border-slate-300 text-slate-500 flex items-center justify-center font-medium cursor-not-allowed" title="Friday is Regular Session Day">
                  Fri
                </div>
                <div class="w-16 h-16 rounded-full border-2 bg-white border-slate-300 text-slate-500 flex items-center justify-center font-medium cursor-not-allowed" title="Weekend - Not Operating">
                  Sat
                </div>
                <div class="w-16 h-16 rounded-full border-2 bg-white border-slate-300 text-slate-500 flex items-center justify-center font-medium cursor-not-allowed" title="Weekend - Not Operating">
                  Sun
                </div>
              </div>
              <p class="text-xs text-slate-500 mt-2">
                <span class="inline-block w-3 h-3 bg-amber-500 border-amber-600 rounded-full mr-1"></span> Shortened Session Day
                <span class="inline-block w-3 h-3 bg-white border-slate-300 rounded-full ml-3 mr-1"></span> Regular Session / Not Operating
              </p>
            </div>
          </div>
        </div>

        <!-- Period Structure -->
        <div class="border rounded-lg p-6 bg-white shadow-sm">
          <h4 class="text-lg font-semibold mb-4 text-slate-800">🕐 Period Structure</h4>
          <div class="grid grid-cols-2 gap-6 mb-6">
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-2">Class Duration</label>
              <div class="flex items-center gap-3">
                <input type="number" value="50" disabled class="input w-32 bg-slate-50 cursor-not-allowed" />
                <span class="text-sm text-slate-600">minutes</span>
              </div>
              <p class="text-xs text-slate-500 mt-1">Shortened class period length</p>
            </div>
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-2">Number of Periods</label>
              <input type="number" value="9" disabled class="input w-32 bg-slate-50 cursor-not-allowed" />
              <p class="text-xs text-slate-500 mt-1">Total periods (same as regular)</p>
            </div>
          </div>
          
          <div class="bg-slate-50 p-4 rounded border">
            <h5 class="text-sm font-semibold text-slate-700 mb-3">📊 Calculated Period Schedule (Monday)</h5>
            <div class="grid grid-cols-3 gap-3">
              <div class="text-xs"><span class="font-semibold">P1:</span> 7:30 - 8:20 AM</div>
              <div class="text-xs"><span class="font-semibold">P2:</span> 8:20 - 9:10 AM</div>
              <div class="text-xs"><span class="font-semibold">P3:</span> 9:10 - 10:00 AM</div>
              <div class="text-xs text-amber-700 bg-amber-50 px-2 py-1 rounded col-span-3">☕ Morning Break (20 min) - 10:00 - 10:20 AM</div>
              <div class="text-xs"><span class="font-semibold">P4:</span> 10:20 - 11:10 AM</div>
              <div class="text-xs"><span class="font-semibold">P5:</span> 11:10 AM - 12:00 PM</div>
              <div class="text-xs text-orange-700 bg-orange-50 px-2 py-1 rounded col-span-3">🍱 Lunch Break (60 min) - 12:00 - 1:00 PM</div>
              <div class="text-xs"><span class="font-semibold">P6:</span> 1:00 - 1:50 PM</div>
              <div class="text-xs"><span class="font-semibold">P7:</span> 1:50 - 2:40 PM</div>
              <div class="text-xs"><span class="font-semibold">P8:</span> 2:40 - 3:30 PM</div>
              <div class="text-xs"><span class="font-semibold">P9:</span> 3:30 - 4:20 PM</div>
            </div>
            <p class="text-xs text-slate-500 mt-3">Condensed schedule fits all 9 periods with shorter class times</p>
          </div>
        </div>

        <!-- Break Schedule -->
        <div class="border rounded-lg p-6 bg-white shadow-sm">
          <h4 class="text-lg font-semibold mb-4 text-slate-800">☕ Break Schedule</h4>
          <div class="space-y-4">
            <div class="grid grid-cols-2 gap-6">
              <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Morning Break</label>
                <div class="flex items-center gap-2">
                  <span class="text-sm text-slate-600">After Period</span>
                  <input type="number" value="3" disabled class="input w-20 bg-slate-50 cursor-not-allowed" />
                  <span class="text-sm text-slate-600">for</span>
                  <input type="number" value="20" disabled class="input w-20 bg-slate-50 cursor-not-allowed" />
                  <span class="text-sm text-slate-600">min</span>
                </div>
              </div>
              <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Lunch Break</label>
                <div class="flex items-center gap-2">
                  <span class="text-sm text-slate-600">After Period</span>
                  <input type="number" value="5" disabled class="input w-20 bg-slate-50 cursor-not-allowed" />
                  <span class="text-sm text-slate-600">for</span>
                  <input type="number" value="60" disabled class="input w-20 bg-slate-50 cursor-not-allowed" />
                  <span class="text-sm text-slate-600">min</span>
                </div>
              </div>
            </div>
            <div>
              <label class="flex items-center gap-2">
                <input type="checkbox" disabled class="rounded bg-slate-50 cursor-not-allowed" />
                <span class="text-sm text-slate-700">Include Afternoon Break</span>
              </label>
              <p class="text-xs text-slate-500 ml-6 mt-1">Junior High does not have afternoon break (same as regular sessions)</p>
            </div>
          </div>
        </div>

        <!-- Session-Specific Rules -->
        <div class="border rounded-lg p-6 bg-white shadow-sm">
          <h4 class="text-lg font-semibold mb-4 text-slate-800">📋 Session-Specific Rules</h4>
          <div class="space-y-3">
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-2">Periods Before Morning Break</label>
              <input type="number" value="3" disabled class="input w-32 bg-slate-50 cursor-not-allowed" />
              <p class="text-xs text-slate-500 mt-1">Must have exactly 3 periods before break on Monday</p>
            </div>
            <div>
              <label class="flex items-center gap-2">
                <input type="checkbox" checked disabled class="rounded bg-slate-50 cursor-not-allowed" />
                <span class="text-sm text-slate-700">Prioritize core subjects in morning slots</span>
              </label>
            </div>
          </div>
        </div>

      </div>

      <!-- Save Button -->
      <div class="border-t pt-6 flex justify-end gap-3">
        <button type="button" class="px-6 py-3 bg-slate-300 text-slate-500 rounded-lg cursor-not-allowed" disabled title="Demo only - not functional">
          💾 Save JH Configuration
        </button>
      </div>
    </div>

    <!-- Tab Content: Senior High -->
    <div id="tab-sh" class="settings-tab-content hidden p-6">
      <h3 class="text-2xl font-bold text-slate-900 mb-2">📗 Senior High Configuration</h3>
      <p class="text-sm text-slate-600 mb-6">Configure scheduling parameters for Senior High (Grades 11-12)</p>

      <!-- Session Type Selector -->
      <div class="mb-8 p-6 bg-gradient-to-r from-green-50 to-emerald-50 border-2 border-green-200 rounded-lg">
        <label class="block text-sm font-bold text-slate-700 mb-3">Select Session Type to Configure:</label>
        <div class="flex gap-4">
          <label class="flex-1 cursor-pointer">
            <input type="radio" name="sh-session" value="regular" checked class="peer hidden" />
            <div class="p-4 border-2 rounded-lg bg-white transition-all peer-checked:border-green-600 peer-checked:bg-green-50 peer-checked:shadow-lg hover:border-green-400">
              <div class="font-bold text-lg text-slate-900">📚 Regular Sessions</div>
              <div class="text-sm text-slate-600">Monday - Thursday (full day)</div>
            </div>
          </label>
          <label class="flex-1 cursor-pointer">
            <input type="radio" name="sh-session" value="shortened" class="peer hidden" />
            <div class="p-4 border-2 rounded-lg bg-white transition-all peer-checked:border-green-600 peer-checked:bg-green-50 peer-checked:shadow-lg hover:border-green-400">
              <div class="font-bold text-lg text-slate-900">⚡ Shortened Sessions</div>
              <div class="text-sm text-slate-600">Friday only (condensed schedule)</div>
            </div>
          </label>
        </div>
      </div>

      <!-- Regular Session Configuration -->
      <div id="sh-regular-config" class="session-config space-y-6">
        
        <!-- School Calendar -->
        <div class="border rounded-lg p-6 bg-white shadow-sm">
          <h4 class="text-lg font-semibold mb-4 text-slate-800">📅 School Calendar</h4>
          <div class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-3">Operating Days for Regular Sessions</label>
              <div class="flex gap-3">
                <div class="w-16 h-16 rounded-full border-2 bg-green-500 border-green-600 text-white flex items-center justify-center font-medium">
                  Mon
                </div>
                <div class="w-16 h-16 rounded-full border-2 bg-green-500 border-green-600 text-white flex items-center justify-center font-medium">
                  Tue
                </div>
                <div class="w-16 h-16 rounded-full border-2 bg-green-500 border-green-600 text-white flex items-center justify-center font-medium">
                  Wed
                </div>
                <div class="w-16 h-16 rounded-full border-2 bg-green-500 border-green-600 text-white flex items-center justify-center font-medium">
                  Thu
                </div>
                <div class="w-16 h-16 rounded-full border-2 bg-slate-100 border-slate-300 text-slate-400 flex items-center justify-center font-medium cursor-not-allowed" title="Friday is Shortened Session Day">
                  Fri
                </div>
                <div class="w-16 h-16 rounded-full border-2 bg-white border-slate-300 text-slate-500 flex items-center justify-center font-medium cursor-not-allowed" title="Weekend - Not Operating">
                  Sat
                </div>
                <div class="w-16 h-16 rounded-full border-2 bg-white border-slate-300 text-slate-500 flex items-center justify-center font-medium cursor-not-allowed" title="Weekend - Not Operating">
                  Sun
                </div>
              </div>
              <p class="text-xs text-slate-500 mt-2">
                <span class="inline-block w-3 h-3 bg-green-500 border-green-600 rounded-full mr-1"></span> Active
                <span class="inline-block w-3 h-3 bg-slate-100 border-slate-300 rounded-full ml-3 mr-1"></span> Shortened Session Day
                <span class="inline-block w-3 h-3 bg-white border-slate-300 rounded-full ml-3 mr-1"></span> Not Operating
              </p>
            </div>
          </div>
        </div>

        <!-- Period Structure -->
        <div class="border rounded-lg p-6 bg-white shadow-sm">
          <h4 class="text-lg font-semibold mb-4 text-slate-800">🕐 Period Structure</h4>
          <div class="grid grid-cols-2 gap-6 mb-6">
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-2">Class Duration</label>
              <div class="flex items-center gap-3">
                <input type="number" value="60" disabled class="input w-32 bg-slate-50 cursor-not-allowed" />
                <span class="text-sm text-slate-600">minutes</span>
              </div>
              <p class="text-xs text-slate-500 mt-1">Length of each class period</p>
            </div>
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-2">Number of Periods</label>
              <input type="number" value="9" disabled class="input w-32 bg-slate-50 cursor-not-allowed" />
              <p class="text-xs text-slate-500 mt-1">Total periods in the day</p>
            </div>
          </div>
          
          <div class="bg-slate-50 p-4 rounded border">
            <h5 class="text-sm font-semibold text-slate-700 mb-3">📊 Calculated Period Schedule</h5>
            <div class="grid grid-cols-3 gap-3">
              <div class="text-xs"><span class="font-semibold">P1:</span> 7:30 - 8:30 AM</div>
              <div class="text-xs"><span class="font-semibold">P2:</span> 8:30 - 9:30 AM</div>
              <div class="text-xs"><span class="font-semibold">P3:</span> 9:30 - 10:30 AM</div>
              <div class="text-xs text-amber-700 bg-amber-50 px-2 py-1 rounded col-span-3">☕ Morning Break (20 min) - 10:30 - 10:50 AM</div>
              <div class="text-xs"><span class="font-semibold">P4:</span> 10:50 - 11:50 AM</div>
              <div class="text-xs"><span class="font-semibold">P5:</span> 11:50 AM - 12:50 PM</div>
              <div class="text-xs text-orange-700 bg-orange-50 px-2 py-1 rounded col-span-3">🍱 Lunch Break (60 min) - 12:50 - 1:50 PM</div>
              <div class="text-xs"><span class="font-semibold">P6:</span> 1:50 - 2:50 PM</div>
              <div class="text-xs"><span class="font-semibold">P7:</span> 2:50 - 3:50 PM</div>
              <div class="text-xs text-purple-700 bg-purple-50 px-2 py-1 rounded col-span-3">🌆 Afternoon Break (15 min) - 3:50 - 4:05 PM</div>
              <div class="text-xs"><span class="font-semibold">P8:</span> 4:05 - 5:05 PM</div>
              <div class="text-xs"><span class="font-semibold">P9:</span> 5:05 - 6:05 PM</div>
            </div>
            <p class="text-xs text-slate-500 mt-3">Senior High includes afternoon break, different from Junior High</p>
          </div>
        </div>

        <!-- Break Schedule -->
        <div class="border rounded-lg p-6 bg-white shadow-sm">
          <h4 class="text-lg font-semibold mb-4 text-slate-800">☕ Break Schedule</h4>
          <div class="space-y-4">
            <div class="grid grid-cols-3 gap-6">
              <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Morning Break</label>
                <div class="flex items-center gap-2">
                  <span class="text-sm text-slate-600">After Period</span>
                  <input type="number" value="3" disabled class="input w-20 bg-slate-50 cursor-not-allowed" />
                  <span class="text-sm text-slate-600">for</span>
                  <input type="number" value="20" disabled class="input w-20 bg-slate-50 cursor-not-allowed" />
                  <span class="text-sm text-slate-600">min</span>
                </div>
              </div>
              <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Lunch Break</label>
                <div class="flex items-center gap-2">
                  <span class="text-sm text-slate-600">After Period</span>
                  <input type="number" value="5" disabled class="input w-20 bg-slate-50 cursor-not-allowed" />
                  <span class="text-sm text-slate-600">for</span>
                  <input type="number" value="60" disabled class="input w-20 bg-slate-50 cursor-not-allowed" />
                  <span class="text-sm text-slate-600">min</span>
                </div>
              </div>
              <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Afternoon Break</label>
                <div class="flex items-center gap-2">
                  <span class="text-sm text-slate-600">After Period</span>
                  <input type="number" value="7" disabled class="input w-20 bg-slate-50 cursor-not-allowed" />
                  <span class="text-sm text-slate-600">for</span>
                  <input type="number" value="15" disabled class="input w-20 bg-slate-50 cursor-not-allowed" />
                  <span class="text-sm text-slate-600">min</span>
                </div>
              </div>
            </div>
            <div>
              <label class="flex items-center gap-2">
                <input type="checkbox" checked disabled class="rounded bg-slate-50 cursor-not-allowed" />
                <span class="text-sm text-slate-700">Include Afternoon Break</span>
              </label>
              <p class="text-xs text-slate-500 ml-6 mt-1">Senior High has 3 breaks: morning, lunch, and afternoon</p>
            </div>
          </div>
        </div>

        <!-- Session-Specific Rules -->
        <div class="border rounded-lg p-6 bg-white shadow-sm">
          <h4 class="text-lg font-semibold mb-4 text-slate-800">📋 Session-Specific Rules</h4>
          <div class="space-y-3">
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-2">Maximum Consecutive Periods</label>
              <input type="number" value="3" disabled class="input w-32 bg-slate-50 cursor-not-allowed" />
              <p class="text-xs text-slate-500 mt-1">Maximum periods before requiring a break (due to 3 breaks)</p>
            </div>
            <div>
              <label class="flex items-center gap-2">
                <input type="checkbox" checked disabled class="rounded bg-slate-50 cursor-not-allowed" />
                <span class="text-sm text-slate-700">Balance track-specific subjects across all periods</span>
              </label>
            </div>
          </div>
        </div>

      </div>

      <!-- Shortened Session Configuration -->
      <div id="sh-shortened-config" class="session-config space-y-6 hidden">
        
        <!-- School Calendar -->
        <div class="border rounded-lg p-6 bg-white shadow-sm">
          <h4 class="text-lg font-semibold mb-4 text-slate-800">📅 School Calendar</h4>
          <div class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-3">Operating Days for Shortened Sessions</label>
              <div class="flex gap-3">
                <div class="w-16 h-16 rounded-full border-2 bg-white border-slate-300 text-slate-500 flex items-center justify-center font-medium cursor-not-allowed" title="Monday is Regular Session Day">
                  Mon
                </div>
                <div class="w-16 h-16 rounded-full border-2 bg-white border-slate-300 text-slate-500 flex items-center justify-center font-medium cursor-not-allowed" title="Tuesday is Regular Session Day">
                  Tue
                </div>
                <div class="w-16 h-16 rounded-full border-2 bg-white border-slate-300 text-slate-500 flex items-center justify-center font-medium cursor-not-allowed" title="Wednesday is Regular Session Day">
                  Wed
                </div>
                <div class="w-16 h-16 rounded-full border-2 bg-white border-slate-300 text-slate-500 flex items-center justify-center font-medium cursor-not-allowed" title="Thursday is Regular Session Day">
                  Thu
                </div>
                <div class="w-16 h-16 rounded-full border-2 bg-amber-500 border-amber-600 text-white flex items-center justify-center font-medium">
                  Fri
                </div>
                <div class="w-16 h-16 rounded-full border-2 bg-white border-slate-300 text-slate-500 flex items-center justify-center font-medium cursor-not-allowed" title="Weekend - Not Operating">
                  Sat
                </div>
                <div class="w-16 h-16 rounded-full border-2 bg-white border-slate-300 text-slate-500 flex items-center justify-center font-medium cursor-not-allowed" title="Weekend - Not Operating">
                  Sun
                </div>
              </div>
              <p class="text-xs text-slate-500 mt-2">
                <span class="inline-block w-3 h-3 bg-amber-500 border-amber-600 rounded-full mr-1"></span> Shortened Session Day
                <span class="inline-block w-3 h-3 bg-white border-slate-300 rounded-full ml-3 mr-1"></span> Regular Session / Not Operating
              </p>
            </div>
          </div>
        </div>

        <!-- Period Structure -->
        <div class="border rounded-lg p-6 bg-white shadow-sm">
          <h4 class="text-lg font-semibold mb-4 text-slate-800">🕐 Period Structure</h4>
          <div class="grid grid-cols-2 gap-6 mb-6">
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-2">Class Duration</label>
              <div class="flex items-center gap-3">
                <input type="number" value="50" disabled class="input w-32 bg-slate-50 cursor-not-allowed" />
                <span class="text-sm text-slate-600">minutes</span>
              </div>
              <p class="text-xs text-slate-500 mt-1">Shortened class period length</p>
            </div>
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-2">Number of Periods</label>
              <input type="number" value="9" disabled class="input w-32 bg-slate-50 cursor-not-allowed" />
              <p class="text-xs text-slate-500 mt-1">Total periods (same as regular)</p>
            </div>
          </div>
          
          <div class="bg-slate-50 p-4 rounded border">
            <h5 class="text-sm font-semibold text-slate-700 mb-3">📊 Calculated Period Schedule (Friday)</h5>
            <div class="grid grid-cols-3 gap-3">
              <div class="text-xs"><span class="font-semibold">P1:</span> 7:30 - 8:20 AM</div>
              <div class="text-xs"><span class="font-semibold">P2:</span> 8:20 - 9:10 AM</div>
              <div class="text-xs"><span class="font-semibold">P3:</span> 9:10 - 10:00 AM</div>
              <div class="text-xs text-amber-700 bg-amber-50 px-2 py-1 rounded col-span-3">☕ Morning Break (20 min) - 10:00 - 10:20 AM</div>
              <div class="text-xs"><span class="font-semibold">P4:</span> 10:20 - 11:10 AM</div>
              <div class="text-xs"><span class="font-semibold">P5:</span> 11:10 AM - 12:00 PM</div>
              <div class="text-xs text-orange-700 bg-orange-50 px-2 py-1 rounded col-span-3">🍱 Lunch Break (60 min) - 12:00 - 1:00 PM</div>
              <div class="text-xs"><span class="font-semibold">P6:</span> 1:00 - 1:50 PM</div>
              <div class="text-xs"><span class="font-semibold">P7:</span> 1:50 - 2:40 PM</div>
              <div class="text-xs text-purple-700 bg-purple-50 px-2 py-1 rounded col-span-3">🌆 Afternoon Break (15 min) - 2:40 - 2:55 PM</div>
              <div class="text-xs"><span class="font-semibold">P8:</span> 2:55 - 3:45 PM</div>
              <div class="text-xs"><span class="font-semibold">P9:</span> 3:45 - 4:35 PM</div>
            </div>
            <p class="text-xs text-slate-500 mt-3">Condensed Friday schedule with all 3 breaks maintained</p>
          </div>
        </div>

        <!-- Break Schedule -->
        <div class="border rounded-lg p-6 bg-white shadow-sm">
          <h4 class="text-lg font-semibold mb-4 text-slate-800">☕ Break Schedule</h4>
          <div class="space-y-4">
            <div class="grid grid-cols-3 gap-6">
              <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Morning Break</label>
                <div class="flex items-center gap-2">
                  <span class="text-sm text-slate-600">After Period</span>
                  <input type="number" value="3" disabled class="input w-20 bg-slate-50 cursor-not-allowed" />
                  <span class="text-sm text-slate-600">for</span>
                  <input type="number" value="20" disabled class="input w-20 bg-slate-50 cursor-not-allowed" />
                  <span class="text-sm text-slate-600">min</span>
                </div>
              </div>
              <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Lunch Break</label>
                <div class="flex items-center gap-2">
                  <span class="text-sm text-slate-600">After Period</span>
                  <input type="number" value="5" disabled class="input w-20 bg-slate-50 cursor-not-allowed" />
                  <span class="text-sm text-slate-600">for</span>
                  <input type="number" value="60" disabled class="input w-20 bg-slate-50 cursor-not-allowed" />
                  <span class="text-sm text-slate-600">min</span>
                </div>
              </div>
              <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Afternoon Break</label>
                <div class="flex items-center gap-2">
                  <span class="text-sm text-slate-600">After Period</span>
                  <input type="number" value="7" disabled class="input w-20 bg-slate-50 cursor-not-allowed" />
                  <span class="text-sm text-slate-600">for</span>
                  <input type="number" value="15" disabled class="input w-20 bg-slate-50 cursor-not-allowed" />
                  <span class="text-sm text-slate-600">min</span>
                </div>
              </div>
            </div>
            <div>
              <label class="flex items-center gap-2">
                <input type="checkbox" checked disabled class="rounded bg-slate-50 cursor-not-allowed" />
                <span class="text-sm text-slate-700">Include Afternoon Break</span>
              </label>
              <p class="text-xs text-slate-500 ml-6 mt-1">All breaks maintained even on shortened days</p>
            </div>
          </div>
        </div>

        <!-- Session-Specific Rules -->
        <div class="border rounded-lg p-6 bg-white shadow-sm">
          <h4 class="text-lg font-semibold mb-4 text-slate-800">📋 Session-Specific Rules</h4>
          <div class="space-y-3">
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-2">Maximum Consecutive Periods</label>
              <input type="number" value="3" disabled class="input w-32 bg-slate-50 cursor-not-allowed" />
              <p class="text-xs text-slate-500 mt-1">Maximum periods before requiring a break</p>
            </div>
            <div>
              <label class="flex items-center gap-2">
                <input type="checkbox" checked disabled class="rounded bg-slate-50 cursor-not-allowed" />
                <span class="text-sm text-slate-700">Use Friday for review and assessment activities</span>
              </label>
            </div>
          </div>
        </div>

      </div>

      <!-- Save Button -->
      <div class="border-t pt-6 flex justify-end gap-3">
        <button type="button" class="px-6 py-3 bg-slate-300 text-slate-500 rounded-lg cursor-not-allowed" disabled title="Demo only - not functional">
          💾 Save SH Configuration
        </button>
      </div>
    </div>

    <!-- Tab Content: Constraints & Rules -->
    <div id="tab-constraints" class="settings-tab-content hidden p-6">
      <h3 class="text-2xl font-bold text-slate-900 mb-2">⚠️ Constraints & Rules</h3>
      <p class="text-sm text-slate-600 mb-6">General scheduling constraints that apply across all levels</p>

      <!-- Faculty Restrictions -->
      <div class="border rounded-lg p-6 bg-white shadow-sm mb-6">
        <h4 class="text-lg font-semibold mb-4 text-slate-800">👥 Faculty Period Restrictions</h4>
        <p class="text-sm text-slate-600 mb-4">Prevent specific faculty roles from teaching during certain periods</p>
        
        <div class="bg-slate-50 p-4 rounded border mb-4">
          <div class="flex items-center justify-between mb-3">
            <div>
              <span class="font-semibold text-sm">Department Heads</span>
              <p class="text-xs text-slate-600">Cannot teach during P1 (administrative meeting time)</p>
            </div>
            <div class="flex gap-1">
              <div class="w-8 h-8 bg-red-100 border border-red-400 rounded flex items-center justify-center text-xs font-bold text-red-800">P1</div>
            </div>
          </div>
        </div>

        <button type="button" class="px-4 py-2 text-sm bg-slate-300 text-slate-500 rounded cursor-not-allowed" disabled title="Demo only">
          + Add Faculty Restriction
        </button>
      </div>

      <!-- Subject Period Constraints -->
      <div class="border rounded-lg p-6 bg-white shadow-sm mb-6">
        <h4 class="text-lg font-semibold mb-4 text-slate-800">📚 Subject Period Constraints</h4>
        <p class="text-sm text-slate-600 mb-4">Restrict specific subjects from being scheduled in certain periods</p>
        
        <div class="bg-slate-50 p-4 rounded border mb-4">
          <div class="flex items-center justify-between mb-3">
            <div>
              <span class="font-semibold text-sm">Physical Education</span>
              <p class="text-xs text-slate-600">Avoid scheduling during P1-P2 (heat considerations)</p>
            </div>
            <div class="flex gap-1">
              <div class="w-8 h-8 bg-red-100 border border-red-400 rounded flex items-center justify-center text-xs font-bold text-red-800">P1</div>
              <div class="w-8 h-8 bg-red-100 border border-red-400 rounded flex items-center justify-center text-xs font-bold text-red-800">P2</div>
            </div>
          </div>
        </div>

        <button type="button" class="px-4 py-2 text-sm bg-slate-300 text-slate-500 rounded cursor-not-allowed" disabled title="Demo only">
          + Add Subject Constraint
        </button>
      </div>

      <!-- Load Balancing -->
      <div class="border rounded-lg p-6 bg-white shadow-sm mb-6">
        <h4 class="text-lg font-semibold mb-4 text-slate-800">⚖️ Load Balancing & Optimization</h4>
        <div class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">Max Consecutive Periods (Teacher)</label>
            <input type="number" value="4" disabled class="input w-32 bg-slate-50 cursor-not-allowed" />
            <p class="text-xs text-slate-500 mt-1">Maximum continuous teaching periods without break</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">Max Teaching Days per Week</label>
            <input type="number" value="6" disabled class="input w-32 bg-slate-50 cursor-not-allowed" />
            <p class="text-xs text-slate-500 mt-1">Maximum days a teacher should work</p>
          </div>
          <div class="space-y-2">
            <label class="flex items-center gap-2">
              <input type="checkbox" checked disabled class="rounded bg-slate-50 cursor-not-allowed" />
              <span class="text-sm text-slate-700">Automatically balance teacher workload</span>
            </label>
            <label class="flex items-center gap-2">
              <input type="checkbox" checked disabled class="rounded bg-slate-50 cursor-not-allowed" />
              <span class="text-sm text-slate-700">Minimize gaps in teacher schedules</span>
            </label>
            <label class="flex items-center gap-2">
              <input type="checkbox" disabled class="rounded bg-slate-50 cursor-not-allowed" />
              <span class="text-sm text-slate-700">Respect teacher period preferences when possible</span>
            </label>
          </div>
        </div>
      </div>

      <!-- Conflict Types -->
      <div class="border rounded-lg p-6 bg-white shadow-sm">
        <h4 class="text-lg font-semibold mb-4 text-slate-800">🚨 Conflict Severity Levels</h4>
        <div class="space-y-3">
          <div class="flex items-start gap-3 p-3 bg-red-50 border border-red-200 rounded">
            <div class="w-12 h-12 bg-red-500 text-white rounded-full flex items-center justify-center font-bold flex-shrink-0">!</div>
            <div>
              <div class="font-semibold text-red-900">Critical</div>
              <p class="text-sm text-red-700">Must never occur: Teacher double-booking, room conflicts, student schedule overlaps</p>
            </div>
          </div>
          <div class="flex items-start gap-3 p-3 bg-amber-50 border border-amber-200 rounded">
            <div class="w-12 h-12 bg-amber-500 text-white rounded-full flex items-center justify-center font-bold flex-shrink-0">⚠</div>
            <div>
              <div class="font-semibold text-amber-900">High Priority</div>
              <p class="text-sm text-amber-700">Should avoid: Excessive consecutive periods, poor workload distribution, required break violations</p>
            </div>
          </div>
          <div class="flex items-start gap-3 p-3 bg-blue-50 border border-blue-200 rounded">
            <div class="w-12 h-12 bg-blue-500 text-white rounded-full flex items-center justify-center font-bold flex-shrink-0">i</div>
            <div>
              <div class="font-semibold text-blue-900">Medium Priority</div>
              <p class="text-sm text-blue-700">Preferably avoid: Period preference violations, minor distribution imbalances, suboptimal subject sequencing</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Save Button -->
      <div class="border-t pt-6 mt-6 flex justify-end gap-3">
        <button type="button" class="px-6 py-3 bg-slate-300 text-slate-500 rounded-lg cursor-not-allowed" disabled title="Demo only - not functional">
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
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\TuroTugma\resources\views/admin/schedule-maker/settings.blade.php ENDPATH**/ ?>