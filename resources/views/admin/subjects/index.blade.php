@extends('admin.layout')

@section('title','Subjects Management')
@section('heading','Subjects Management')

@section('content')
  <div class="mb-6 p-4 bg-blue-50 border-l-4 border-blue-500 text-blue-900">
    <p class="font-semibold">📚 New Subject System (Placeholder UI)</p>
    <p class="text-sm mt-1">This is a visual mockup showing the new structure. Buttons/forms are non-functional until backend is ready.</p>
  </div>

  <div class="mb-6 p-4 bg-amber-50 border-l-4 border-amber-500 text-amber-900">
    <p class="font-semibold">⏳ Senior High Coming Soon</p>
    <p class="text-sm mt-1">Senior High (Grade 11-12) subject management will be available in the future. Currently, only Junior High (Grade 7-10) subjects can be managed.</p>
  </div>

  <!-- Filter & Action Bar -->
  <div class="mb-4 flex items-center justify-between gap-4">
    <div class="flex items-center gap-3">
      <select class="px-3 py-2 border rounded" disabled>
        <option>All Levels</option>
        <option>Grade 7</option>
        <option>Grade 11</option>
        <option>Grade 12</option>
      </select>
      <select class="px-3 py-2 border rounded" disabled>
        <option>All Strands</option>
        <option>STEM</option>
        <option>ABM</option>
        <option>HUMSS</option>
        <option>ICT</option>
      </select>
      <select class="px-3 py-2 border rounded" disabled>
        <option>All Types</option>
        <option>Core</option>
        <option>Specialized</option>
        <option>Applied</option>
      </select>
    </div>
    <button class="px-4 py-2 bg-[#3b4197] text-white rounded font-semibold hover:bg-[#2d3273]" disabled>
      + Add Subject
    </button>
  </div>

  <!-- Subjects Table -->
  <div class="bg-white border rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-slate-200">
      <thead class="bg-slate-50">
        <tr>
          <th class="px-4 py-3 text-left text-xs font-semibold text-slate-700 uppercase">Subject Name</th>
          <th class="px-4 py-3 text-left text-xs font-semibold text-slate-700 uppercase">Code</th>
          <th class="px-4 py-3 text-left text-xs font-semibold text-slate-700 uppercase">Grade Level</th>
          <th class="px-4 py-3 text-left text-xs font-semibold text-slate-700 uppercase">Strand</th>
          <th class="px-4 py-3 text-left text-xs font-semibold text-slate-700 uppercase">Type</th>
          <th class="px-4 py-3 text-left text-xs font-semibold text-slate-700 uppercase">Units/Hrs</th>
          <th class="px-4 py-3 text-left text-xs font-semibold text-slate-700 uppercase">Actions</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-slate-200">
        <!-- Sample Junior High Core Subject -->
        <tr class="hover:bg-slate-50">
          <td class="px-4 py-3 font-medium text-slate-900">English</td>
          <td class="px-4 py-3 text-sm text-slate-600">ENG7</td>
          <td class="px-4 py-3 text-sm text-slate-600">Grade 7-10</td>
          <td class="px-4 py-3 text-sm text-slate-500">—</td>
          <td class="px-4 py-3"><span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Core</span></td>
          <td class="px-4 py-3 text-sm text-slate-600">5 hrs/week</td>
          <td class="px-4 py-3">
            <button class="text-blue-600 hover:text-blue-800 text-sm mr-2" disabled>Edit</button>
            <button class="text-red-600 hover:text-red-800 text-sm" disabled>Delete</button>
          </td>
        </tr>

        <!-- Sample Senior High STEM Subject -->
        <tr class="hover:bg-slate-50">
          <td class="px-4 py-3 font-medium text-slate-900">Pre-Calculus</td>
          <td class="px-4 py-3 text-sm text-slate-600">PRECAL11</td>
          <td class="px-4 py-3 text-sm text-slate-600">Grade 11</td>
          <td class="px-4 py-3 text-sm"><span class="px-2 py-1 text-xs font-semibold rounded bg-purple-100 text-purple-800">STEM</span></td>
          <td class="px-4 py-3"><span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Specialized</span></td>
          <td class="px-4 py-3 text-sm text-slate-600">4 hrs/week</td>
          <td class="px-4 py-3">
            <button class="text-blue-600 hover:text-blue-800 text-sm mr-2" disabled>Edit</button>
            <button class="text-red-600 hover:text-red-800 text-sm" disabled>Delete</button>
          </td>
        </tr>

        <!-- Sample ABM Subject -->
        <tr class="hover:bg-slate-50">
          <td class="px-4 py-3 font-medium text-slate-900">Business Math</td>
          <td class="px-4 py-3 text-sm text-slate-600">BUSMATH11</td>
          <td class="px-4 py-3 text-sm text-slate-600">Grade 11</td>
          <td class="px-4 py-3 text-sm"><span class="px-2 py-1 text-xs font-semibold rounded bg-orange-100 text-orange-800">ABM</span></td>
          <td class="px-4 py-3"><span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Specialized</span></td>
          <td class="px-4 py-3 text-sm text-slate-600">4 hrs/week</td>
          <td class="px-4 py-3">
            <button class="text-blue-600 hover:text-blue-800 text-sm mr-2" disabled>Edit</button>
            <button class="text-red-600 hover:text-red-800 text-sm" disabled>Delete</button>
          </td>
        </tr>

        <!-- Sample HUMSS Subject -->
        <tr class="hover:bg-slate-50">
          <td class="px-4 py-3 font-medium text-slate-900">Philippine Politics</td>
          <td class="px-4 py-3 text-sm text-slate-600">POLSCI12</td>
          <td class="px-4 py-3 text-sm text-slate-600">Grade 12</td>
          <td class="px-4 py-3 text-sm"><span class="px-2 py-1 text-xs font-semibold rounded bg-teal-100 text-teal-800">HUMSS</span></td>
          <td class="px-4 py-3"><span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-8 00">Specialized</span></td>
          <td class="px-4 py-3 text-sm text-slate-600">3 hrs/week</td>
          <td class="px-4 py-3">
            <button class="text-blue-600 hover:text-blue-800 text-sm mr-2" disabled>Edit</button>
            <button class="text-red-600 hover:text-red-800 text-sm" disabled>Delete</button>
          </td>
        </tr>

        <!-- Empty state for actual data -->
        <tr>
          <td colspan="7" class="px-4 py-8 text-center text-slate-500 italic">
            Existing subjects will appear here once backend is implemented
          </td>
        </tr>
      </tbody>
    </table>
  </div>

  <!-- Add/Edit Form Modal Placeholder -->
  <div class="mt-6 p-6 bg-slate-50 border rounded-lg">
    <h3 class="font-semibold text-lg mb-4">📝 Subject Form (Preview)</h3>
    <div class="grid grid-cols-2 gap-4">
      <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Subject Name *</label>
        <input type="text" class="w-full px-3 py-2 border rounded" placeholder="e.g., Oral Communication" disabled>
      </div>
      <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Subject Code *</label>
        <input type="text" class="w-full px-3 py-2 border rounded" placeholder="e.g., ORALCOM11" disabled>
      </div>
      <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Grade Level *</label>
        <select class="w-full px-3 py-2 border rounded" disabled>
          <option>Select...</option>
          <option>Grade 7-10 (JH Core)</option>
          <option>Grade 11</option>
          <option>Grade 12</option>
        </select>
      </div>
      <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Strand (SH only)</label>
        <select class="w-full px-3 py-2 border rounded" disabled>
          <option>None (JH or SH Core)</option>
          <option>STEM</option>
          <option>ABM</option>
          <option>HUMSS</option>
          <option>ICT</option>
          <option>HE/Tech-Voc</option>
        </select>
      </div>
      <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Type *</label>
        <select class="w-full px-3 py-2 border rounded" disabled>
          <option>Core</option>
          <option>Specialized</option>
          <option>Applied</option>
          <option>Elective</option>
        </select>
      </div>
      <div>
        <label class="block text-sm font-medium text-slate-700 mb-1">Hours per Week *</label>
        <input type="number" class="w-full px-3 py-2 border rounded" placeholder="e.g., 4" disabled>
      </div>
    </div>
    <div class="mt-4 flex gap-2">
      <button class="px-4 py-2 bg-[#3b4197] text-white rounded font-semibold" disabled>Save Subject</button>
      <button class="px-4 py-2 bg-slate-300 text-slate-700 rounded font-semibold" disabled>Cancel</button>
    </div>
  </div>
@endsection
