@extends('admin.layout')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 p-6">
  <div class="max-w-7xl mx-auto">
    
    <!-- Header -->
    <div class="mb-6">
      <a href="#" class="text-blue-600 text-sm font-semibold mb-3 inline-block">← Back to Schedules</a>
      <h1 class="text-3xl font-bold text-slate-900">⚠️ Conflict Resolution</h1>
      <p class="text-slate-600 mt-1">SY 2024-2025 Shortened Session | Status: Generated</p>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
      <div class="bg-red-50 border border-red-200 rounded-lg p-4">
        <div class="text-sm text-red-700 font-semibold">Total Conflicts</div>
        <div class="text-3xl font-bold text-red-600 mt-1">5</div>
        <div class="text-xs text-red-600 mt-1">Need resolution</div>
      </div>
      <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
        <div class="text-sm text-amber-700 font-semibold">Unassigned Slots</div>
        <div class="text-3xl font-bold text-amber-600 mt-1">8</div>
        <div class="text-xs text-amber-600 mt-1">Need teachers</div>
      </div>
      <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
        <div class="text-sm text-blue-700 font-semibold">Overloaded Teachers</div>
        <div class="text-3xl font-bold text-blue-600 mt-1">3</div>
        <div class="text-xs text-blue-600 mt-1">Exceeding capacity</div>
      </div>
      <div class="bg-slate-50 border border-slate-200 rounded-lg p-4">
        <div class="text-sm text-slate-700 font-semibold">Resolved</div>
        <div class="text-3xl font-bold text-slate-600 mt-1">24</div>
        <div class="text-xs text-slate-600 mt-1">Successful assignments</div>
      </div>
    </div>

    <!-- Filter Tabs -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-6">
      <div class="flex border-b">
        <button class="flex-1 px-6 py-4 font-semibold text-red-600 border-b-2 border-red-600 hover:bg-red-50">
          ⚠️ Period Overlaps (3)
        </button>
        <button class="flex-1 px-6 py-4 font-semibold text-slate-600 hover:bg-slate-50">
          💼 Overload (3)
        </button>
        <button class="flex-1 px-6 py-4 font-semibold text-slate-600 hover:bg-slate-50">
          ❌ Unassigned (8)
        </button>
        <button class="flex-1 px-6 py-4 font-semibold text-slate-600 hover:bg-slate-50">
          🚫 Not Qualified (2)
        </button>
      </div>

      <!-- Tab Content: Period Overlaps -->
      <div class="p-6">
        <h3 class="text-lg font-bold text-slate-900 mb-4">⚠️ Period Overlaps</h3>
        <p class="text-slate-600 text-sm mb-6">Teachers assigned to teach two different classes in the same period. Select resolution action.</p>

        <div class="space-y-4">
          <!-- Conflict Card 1 -->
          <div class="border-l-4 border-red-600 bg-red-50 rounded-lg p-4">
            <div class="flex items-start justify-between gap-4">
              <div class="flex-1">
                <h4 class="font-bold text-slate-900 mb-2">🔴 CONFLICT: Period Overlap</h4>
                <div class="grid grid-cols-2 gap-3 text-sm">
                  <div>
                    <div class="text-slate-600 font-semibold">Teacher</div>
                    <div class="text-slate-900 font-bold">Ms. Ramos</div>
                  </div>
                  <div>
                    <div class="text-slate-600 font-semibold">Period</div>
                    <div class="text-slate-900 font-bold">Period 3 (09:30-10:15)</div>
                  </div>
                  <div class="col-span-2">
                    <div class="text-slate-600 font-semibold">Conflicting Assignments</div>
                    <div class="space-y-1 mt-1">
                      <div class="bg-white p-2 rounded text-slate-900 text-xs">
                        • Grade 7-Bonifacio (Math)
                      </div>
                      <div class="bg-white p-2 rounded text-slate-900 text-xs">
                        • Grade 8-Aguinaldo (Math)
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="flex flex-col gap-2">
                <button class="px-4 py-2 bg-blue-600 text-white rounded text-sm font-semibold hover:bg-blue-700 whitespace-nowrap">
                  Reassign
                </button>
                <button class="px-4 py-2 bg-slate-200 text-slate-700 rounded text-sm font-semibold hover:bg-slate-300 whitespace-nowrap">
                  Swap
                </button>
              </div>
            </div>
          </div>

          <!-- Conflict Card 2 -->
          <div class="border-l-4 border-red-600 bg-red-50 rounded-lg p-4">
            <div class="flex items-start justify-between gap-4">
              <div class="flex-1">
                <h4 class="font-bold text-slate-900 mb-2">🔴 CONFLICT: Period Overlap</h4>
                <div class="grid grid-cols-2 gap-3 text-sm">
                  <div>
                    <div class="text-slate-600 font-semibold">Teacher</div>
                    <div class="text-slate-900 font-bold">Mr. Fernandez</div>
                  </div>
                  <div>
                    <div class="text-slate-600 font-semibold">Period</div>
                    <div class="text-slate-900 font-bold">Period 4 (10:15-11:00)</div>
                  </div>
                  <div class="col-span-2">
                    <div class="text-slate-600 font-semibold">Conflicting Assignments</div>
                    <div class="space-y-1 mt-1">
                      <div class="bg-white p-2 rounded text-slate-900 text-xs">
                        • Grade 9-Mabini (Science)
                      </div>
                      <div class="bg-white p-2 rounded text-slate-900 text-xs">
                        • Grade 11-STEM (Physics)
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="flex flex-col gap-2">
                <button class="px-4 py-2 bg-blue-600 text-white rounded text-sm font-semibold hover:bg-blue-700 whitespace-nowrap">
                  Reassign
                </button>
                <button class="px-4 py-2 bg-slate-200 text-slate-700 rounded text-sm font-semibold hover:bg-slate-300 whitespace-nowrap">
                  Swap
                </button>
              </div>
            </div>
          </div>

          <!-- Conflict Card 3 -->
          <div class="border-l-4 border-red-600 bg-red-50 rounded-lg p-4">
            <div class="flex items-start justify-between gap-4">
              <div class="flex-1">
                <h4 class="font-bold text-slate-900 mb-2">🔴 CONFLICT: Period Overlap</h4>
                <div class="grid grid-cols-2 gap-3 text-sm">
                  <div>
                    <div class="text-slate-600 font-semibold">Teacher</div>
                    <div class="text-slate-900 font-bold">Ms. Santos</div>
                  </div>
                  <div>
                    <div class="text-slate-600 font-semibold">Period</div>
                    <div class="text-slate-900 font-bold">Period 2 (08:45-09:30)</div>
                  </div>
                  <div class="col-span-2">
                    <div class="text-slate-600 font-semibold">Conflicting Assignments</div>
                    <div class="space-y-1 mt-1">
                      <div class="bg-white p-2 rounded text-slate-900 text-xs">
                        • Grade 7-Rizal (English)
                      </div>
                      <div class="bg-white p-2 rounded text-slate-900 text-xs">
                        • Grade 7-Bonifacio (English)
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="flex flex-col gap-2">
                <button class="px-4 py-2 bg-blue-600 text-white rounded text-sm font-semibold hover:bg-blue-700 whitespace-nowrap">
                  Reassign
                </button>
                <button class="px-4 py-2 bg-slate-200 text-slate-700 rounded text-sm font-semibold hover:bg-slate-300 whitespace-nowrap">
                  Swap
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Legend -->
        <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
          <h4 class="font-bold text-blue-900 mb-2">💡 How to Resolve</h4>
          <ul class="text-sm text-blue-800 space-y-1 list-disc list-inside">
            <li><strong>Reassign:</strong> Choose a different teacher with same subject expertise and no conflicts</li>
            <li><strong>Swap:</strong> Exchange this assignment with another teacher's slot of same subject/grade</li>
            <li><strong>Alternative:</strong> Check suggested alternatives below</li>
          </ul>
        </div>
      </div>
    </div>

    <!-- Suggested Alternatives -->
    <div class="bg-white rounded-lg shadow-lg p-6">
      <h3 class="text-lg font-bold text-slate-900 mb-4">💡 Suggested Alternatives</h3>
      
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Suggestion 1 -->
        <div class="border rounded-lg p-4 hover:shadow-md transition">
          <div class="bg-green-50 rounded p-2 mb-3">
            <div class="font-bold text-green-900 text-sm">✓ Assign to: Ms. Navarro</div>
          </div>
          <div class="text-sm space-y-2">
            <div>
              <span class="text-slate-600">Conflict</span>
              <div class="font-semibold text-slate-900">Ms. Ramos - Grade 7-Bonifacio (P3)</div>
            </div>
            <div>
              <span class="text-slate-600">Alternative</span>
              <div class="font-semibold text-slate-900">Ms. Navarro (has Math, no P3 conflicts)</div>
            </div>
            <div>
              <span class="text-slate-600">Current Load</span>
              <div class="font-semibold text-slate-900">12/24 hrs (50%)</div>
            </div>
            <button class="w-full mt-3 px-3 py-2 bg-green-600 text-white rounded text-sm font-semibold hover:bg-green-700">
              Apply Suggestion
            </button>
          </div>
        </div>

        <!-- Suggestion 2 -->
        <div class="border rounded-lg p-4 hover:shadow-md transition">
          <div class="bg-green-50 rounded p-2 mb-3">
            <div class="font-bold text-green-900 text-sm">✓ Reassign to: Mr. Torres</div>
          </div>
          <div class="text-sm space-y-2">
            <div>
              <span class="text-slate-600">Conflict</span>
              <div class="font-semibold text-slate-900">Mr. Fernandez - Grade 9-Mabini (P4)</div>
            </div>
            <div>
              <span class="text-slate-600">Alternative</span>
              <div class="font-semibold text-slate-900">Mr. Torres (has Science, P4 available)</div>
            </div>
            <div>
              <span class="text-slate-600">Current Load</span>
              <div class="font-semibold text-slate-900">14/24 hrs (58%)</div>
            </div>
            <button class="w-full mt-3 px-3 py-2 bg-green-600 text-white rounded text-sm font-semibold hover:bg-green-700">
              Apply Suggestion
            </button>
          </div>
        </div>

        <!-- Suggestion 3 -->
        <div class="border rounded-lg p-4 hover:shadow-md transition">
          <div class="bg-green-50 rounded p-2 mb-3">
            <div class="font-bold text-green-900 text-sm">✓ Assign to: Mr. Mendoza</div>
          </div>
          <div class="text-sm space-y-2">
            <div>
              <span class="text-slate-600">Conflict</span>
              <div class="font-semibold text-slate-900">Ms. Santos - Grade 7-Bonifacio (P2)</div>
            </div>
            <div>
              <span class="text-slate-600">Alternative</span>
              <div class="font-semibold text-slate-900">Mr. Mendoza (has English, P2 open)</div>
            </div>
            <div>
              <span class="text-slate-600">Current Load</span>
              <div class="font-semibold text-slate-900">16/24 hrs (67%)</div>
            </div>
            <button class="w-full mt-3 px-3 py-2 bg-green-600 text-white rounded text-sm font-semibold hover:bg-green-700">
              Apply Suggestion
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Action Buttons -->
    <div class="mt-6 flex gap-3 justify-center">
      <button class="px-8 py-3 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700">
        🔄 Re-generate Schedule
      </button>
      <button class="px-8 py-3 bg-green-600 text-white rounded-lg font-semibold hover:bg-green-700">
        ✓ Resolve All & Publish
      </button>
      <button class="px-8 py-3 bg-slate-200 text-slate-700 rounded-lg font-semibold hover:bg-slate-300">
        Cancel
      </button>
    </div>

  </div>
</div>
@endsection
