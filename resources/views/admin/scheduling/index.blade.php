@extends('admin.layout')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 p-6">
  <div class="max-w-7xl mx-auto">
    
    <!-- Header -->
    <div class="mb-8">
      <h1 class="text-4xl font-bold text-slate-900 mb-2">📅 Scheduler Dashboard</h1>
      <p class="text-slate-600">Manage timetables, assign teachers, and resolve conflicts</p>
    </div>

    <!-- Quick Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
      <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
        <div class="text-sm text-slate-600 font-medium">Active Scheduling Runs</div>
        <div class="text-3xl font-bold text-blue-600 mt-2">3</div>
        <div class="text-xs text-slate-500 mt-1">1 Generated, 2 Drafts</div>
      </div>
      <div class="bg-white rounded-lg shadow p-6 border-l-4 border-amber-500">
        <div class="text-sm text-slate-600 font-medium">Unassigned Slots</div>
        <div class="text-3xl font-bold text-amber-600 mt-2">12</div>
        <div class="text-xs text-slate-500 mt-1">Needs attention</div>
      </div>
      <div class="bg-white rounded-lg shadow p-6 border-l-4 border-red-500">
        <div class="text-sm text-slate-600 font-medium">Conflicts Detected</div>
        <div class="text-3xl font-bold text-red-600 mt-2">5</div>
        <div class="text-xs text-slate-500 mt-1">Overlap & overload</div>
      </div>
      <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
        <div class="text-sm text-slate-600 font-medium">Published Schedules</div>
        <div class="text-3xl font-bold text-green-600 mt-2">2</div>
        <div class="text-xs text-slate-500 mt-1">Active schedules</div>
      </div>
    </div>

    <!-- Main Content: Two Column Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      
      <!-- Left Sidebar: Scheduling Runs List -->
      <div class="lg:col-span-1">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
          <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white p-4 flex items-center justify-between">
            <h2 class="text-lg font-bold">📋 Scheduling Runs</h2>
            <button class="bg-white text-blue-600 px-3 py-1 rounded text-sm font-semibold hover:bg-blue-50">
              + New
            </button>
          </div>
          
          <div class="divide-y">
            <!-- Run Item -->
            <div class="p-4 hover:bg-slate-50 cursor-pointer border-l-4 border-green-500 bg-green-50">
              <div class="flex items-start justify-between">
                <div class="flex-1">
                  <div class="font-semibold text-slate-900">SY 2024-2025 Regular</div>
                  <div class="text-xs text-slate-500 mt-1">Status: <span class="font-bold text-green-600">Published</span></div>
                  <div class="text-xs text-slate-500">Sections: 32 | Teachers: 48</div>
                  <div class="text-xs text-slate-500 mt-1">Published: Jan 3, 2026</div>
                </div>
                <div class="text-2xl">✓</div>
              </div>
              <div class="flex gap-2 mt-3">
                <button class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded hover:bg-blue-200">View</button>
                <button class="text-xs bg-gray-100 text-gray-700 px-2 py-1 rounded hover:bg-gray-200">Export</button>
              </div>
            </div>

            <!-- Run Item -->
            <div class="p-4 hover:bg-slate-50 cursor-pointer border-l-4 border-blue-500 bg-blue-50">
              <div class="flex items-start justify-between">
                <div class="flex-1">
                  <div class="font-semibold text-slate-900">SY 2024-2025 Shortened</div>
                  <div class="text-xs text-slate-500 mt-1">Status: <span class="font-bold text-blue-600">Generated</span></div>
                  <div class="text-xs text-slate-500">Sections: 32 | Teachers: 48</div>
                  <div class="text-xs text-slate-500 mt-1">Unassigned: 8 | Conflicts: 3</div>
                </div>
                <div class="text-2xl">⚙️</div>
              </div>
              <div class="flex gap-2 mt-3">
                <button class="text-xs bg-amber-100 text-amber-700 px-2 py-1 rounded hover:bg-amber-200">Resolve</button>
                <button class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded hover:bg-green-200">Publish</button>
              </div>
            </div>

            <!-- Run Item -->
            <div class="p-4 hover:bg-slate-50 cursor-pointer border-l-4 border-slate-400">
              <div class="flex items-start justify-between">
                <div class="flex-1">
                  <div class="font-semibold text-slate-900">SY 2024-2025 (Draft)</div>
                  <div class="text-xs text-slate-500 mt-1">Status: <span class="font-bold text-slate-600">Draft</span></div>
                  <div class="text-xs text-slate-500">Sections: 0 | Teachers: 0</div>
                  <div class="text-xs text-slate-500 mt-1">Created: Jan 2, 2026</div>
                </div>
                <div class="text-2xl">✏️</div>
              </div>
              <div class="flex gap-2 mt-3">
                <button class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded hover:bg-blue-200">Edit</button>
                <button class="text-xs bg-red-100 text-red-700 px-2 py-1 rounded hover:bg-red-200">Delete</button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Right Content Area: Tabs -->
      <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
          <!-- Tabs Navigation -->
          <div class="border-b bg-slate-50 flex">
            <button class="flex-1 px-6 py-4 font-semibold text-slate-700 border-b-2 border-blue-600 text-blue-600 hover:bg-blue-50">
              📊 Master Schedule
            </button>
            <button class="flex-1 px-6 py-4 font-semibold text-slate-500 hover:text-slate-700 hover:bg-slate-100">
              🏫 Sections
            </button>
            <button class="flex-1 px-6 py-4 font-semibold text-slate-500 hover:text-slate-700 hover:bg-slate-100">
              👥 Teachers
            </button>
            <button class="flex-1 px-6 py-4 font-semibold text-slate-500 hover:text-slate-700 hover:bg-slate-100">
              ⚠️ Conflicts
            </button>
          </div>

          <!-- Tab Content: Master Schedule -->
          <div class="p-6">
            <h3 class="text-lg font-bold text-slate-900 mb-4">📊 Master Schedule - SY 2024-2025 (Regular)</h3>
            
            <div class="mb-4 flex gap-2">
              <select class="px-3 py-2 border border-slate-300 rounded text-sm">
                <option>All Sections</option>
                <option selected>Junior High</option>
                <option>Senior High</option>
              </select>
              <select class="px-3 py-2 border border-slate-300 rounded text-sm">
                <option selected>All Grades</option>
                <option>Grade 7</option>
                <option>Grade 8</option>
              </select>
              <button class="px-4 py-2 bg-blue-600 text-white rounded text-sm font-semibold hover:bg-blue-700">
                🔄 Refresh
              </button>
              <button class="px-4 py-2 bg-slate-200 text-slate-700 rounded text-sm font-semibold hover:bg-slate-300">
                📥 Export
              </button>
            </div>

            <!-- Schedule Grid Table -->
            <div class="overflow-x-auto border rounded-lg">
              <table class="w-full text-sm">
                <thead class="bg-slate-100 border-b">
                  <tr>
                    <th class="px-4 py-2 text-left font-bold text-slate-700 w-40">Section</th>
                    <th class="px-4 py-2 text-center font-bold text-slate-700 bg-blue-50">P1 (8:00)</th>
                    <th class="px-4 py-2 text-center font-bold text-slate-700">P2 (8:45)</th>
                    <th class="px-4 py-2 text-center font-bold text-slate-700 bg-blue-50">P3 (9:30)</th>
                    <th class="px-4 py-2 text-center font-bold text-slate-700">P4 (10:15)</th>
                    <th class="px-4 py-2 text-center font-bold text-slate-700 bg-blue-50">P5 (11:00)</th>
                  </tr>
                </thead>
                <tbody>
                  <!-- Row -->
                  <tr class="border-b hover:bg-blue-50">
                    <td class="px-4 py-3 font-semibold text-slate-900">Grade 7-Rizal</td>
                    <td class="px-4 py-3 text-center">
                      <div class="bg-green-100 text-green-800 text-xs font-bold p-1 rounded">
                        Mr. Cruz<br/>Math<br/>[Adviser]
                      </div>
                    </td>
                    <td class="px-4 py-3 text-center">
                      <div class="bg-blue-100 text-blue-800 text-xs font-bold p-1 rounded">
                        Ms. Santos<br/>English
                      </div>
                    </td>
                    <td class="px-4 py-3 text-center">
                      <div class="bg-purple-100 text-purple-800 text-xs font-bold p-1 rounded">
                        Mr. Reyes<br/>Science
                      </div>
                    </td>
                    <td class="px-4 py-3 text-center">
                      <div class="bg-amber-100 text-amber-800 text-xs font-bold p-1 rounded">
                        Ms. Garcia<br/>Filipino
                      </div>
                    </td>
                    <td class="px-4 py-3 text-center">
                      <div class="bg-red-100 text-red-800 text-xs font-bold p-1 rounded">
                        ❌ Unassigned
                      </div>
                    </td>
                  </tr>
                  <!-- Row -->
                  <tr class="border-b hover:bg-blue-50">
                    <td class="px-4 py-3 font-semibold text-slate-900">Grade 7-Bonifacio</td>
                    <td class="px-4 py-3 text-center">
                      <div class="bg-indigo-100 text-indigo-800 text-xs font-bold p-1 rounded">
                        Ms. Ramos<br/>Math<br/>[Adviser]
                      </div>
                    </td>
                    <td class="px-4 py-3 text-center">
                      <div class="bg-blue-100 text-blue-800 text-xs font-bold p-1 rounded">
                        Mr. Lopez<br/>English
                      </div>
                    </td>
                    <td class="px-4 py-3 text-center">
                      <div class="bg-yellow-100 text-yellow-800 text-xs font-bold p-1 rounded">
                        Ms. Navarro<br/>Science
                      </div>
                    </td>
                    <td class="px-4 py-3 text-center">
                      <div class="bg-pink-100 text-pink-800 text-xs font-bold p-1 rounded">
                        Mr. Gonzales<br/>Filipino
                      </div>
                    </td>
                    <td class="px-4 py-3 text-center">
                      <div class="bg-orange-100 text-orange-800 text-xs font-bold p-1 rounded">
                        ⚠️ Conflict
                      </div>
                    </td>
                  </tr>
                  <!-- Row -->
                  <tr class="border-b hover:bg-blue-50">
                    <td class="px-4 py-3 font-semibold text-slate-900">Grade 8-Aguinaldo</td>
                    <td class="px-4 py-3 text-center">
                      <div class="bg-teal-100 text-teal-800 text-xs font-bold p-1 rounded">
                        Mr. Fernandez<br/>Math<br/>[Adviser]
                      </div>
                    </td>
                    <td class="px-4 py-3 text-center">
                      <div class="bg-cyan-100 text-cyan-800 text-xs font-bold p-1 rounded">
                        Ms. Mendoza<br/>English
                      </div>
                    </td>
                    <td class="px-4 py-3 text-center">
                      <div class="bg-lime-100 text-lime-800 text-xs font-bold p-1 rounded">
                        Mr. Torres<br/>Science
                      </div>
                    </td>
                    <td class="px-4 py-3 text-center">
                      <div class="bg-violet-100 text-violet-800 text-xs font-bold p-1 rounded">
                        Ms. Silva<br/>Filipino
                      </div>
                    </td>
                    <td class="px-4 py-3 text-center">
                      <div class="bg-slate-100 text-slate-600 text-xs font-bold p-1 rounded">
                        Mr. Santos<br/>PE
                      </div>
                    </td>
                  </tr>
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
        </div>
      </div>
    </div>

    <!-- Section at Bottom: Quick Actions -->
    <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
      <div class="bg-white rounded-lg shadow p-6 border-t-4 border-blue-500">
        <h3 class="text-lg font-bold text-slate-900 mb-3">🏫 Manage Sections</h3>
        <p class="text-sm text-slate-600 mb-4">Setup section subjects and allocations</p>
        <button class="w-full bg-blue-600 text-white py-2 rounded font-semibold hover:bg-blue-700">
          Go to Sections
        </button>
      </div>
      <div class="bg-white rounded-lg shadow p-6 border-t-4 border-purple-500">
        <h3 class="text-lg font-bold text-slate-900 mb-3">⚙️ Auto-Generate Schedule</h3>
        <p class="text-sm text-slate-600 mb-4">Start scheduling run with auto-assignment</p>
        <button class="w-full bg-purple-600 text-white py-2 rounded font-semibold hover:bg-purple-700">
          Generate
        </button>
      </div>
      <div class="bg-white rounded-lg shadow p-6 border-t-4 border-red-500">
        <h3 class="text-lg font-bold text-slate-900 mb-3">⚠️ Resolve Conflicts</h3>
        <p class="text-sm text-slate-600 mb-4">View and fix schedule conflicts</p>
        <button class="w-full bg-red-600 text-white py-2 rounded font-semibold hover:bg-red-700">
          Go to Conflicts (5)
        </button>
      </div>
    </div>

  </div>
</div>
@endsection
