@extends('admin.layout')

@section('title','Dashboard')
@section('heading','Dashboard')

@section('content')
<div class="grid grid-cols-1 sm:grid-cols-3 gap-8 mb-8">
  <x-admin.metric-card title="Faculty Management" :value="$teachersCount ?? 0" description="Total registered faculty" />
  <x-admin.metric-card title="Sections" :value="$sectionsCount ?? 0" description="Total sections" />
  <x-admin.metric-card title="Subjects" :value="$subjectsCount ?? 0" description="Total subjects" />
</div>

<!-- Workload Summary -->
<div class="mt-8 p-6 border rounded-xl bg-blue-50">
  <h3 class="font-bold text-[#3b4197] text-2xl mb-2">📊 Workload Summary</h3>
  <p class="mb-4 text-base text-blue-800">Total teaching hours per faculty; highlights overloaded staff.</p>
  <div class="bg-white p-4 rounded-xl border border-blue-200">
    @if($workloadSummary && count($workloadSummary) > 0)
      <div class="overflow-x-auto">
        <table class="w-full text-base">
          <thead class="bg-blue-100">
            <tr>
              <th class="text-left p-3 font-semibold">Faculty</th>
              <th class="text-center p-3 font-semibold">Hours/Week</th>
              <th class="text-center p-3 font-semibold">Max Load</th>
              <th class="text-center p-3 font-semibold">Status</th>
            </tr>
          </thead>
          <tbody id="workloadTableBody">
            @foreach(array_slice($workloadSummary, 0, 10) as $item)
            <tr class="border-b {{ $item['overloaded'] ? 'bg-red-50' : '' }}">
              <td class="p-3">{{ $item['name'] }}</td>
              <td class="text-center p-3">{{ $item['hours'] }}</td>
              <td class="text-center p-3">{{ $item['max_load'] ?? 'N/A' }}</td>
              <td class="text-center p-3">
                @if($item['overloaded'])
                  <span class="text-red-600 font-semibold">⚠️ Overloaded</span>
                @else
                  <span class="text-green-600 font-semibold">✓ Normal</span>
                @endif
              </td>
            </tr>
            @endforeach
            <tbody id="workloadTableHidden" class="hidden">
            @foreach(array_slice($workloadSummary, 10) as $item)
            <tr class="border-b {{ $item['overloaded'] ? 'bg-red-50' : '' }}">
              <td class="p-3">{{ $item['name'] }}</td>
              <td class="text-center p-3">{{ $item['hours'] }}</td>
              <td class="text-center p-3">{{ $item['max_load'] ?? 'N/A' }}</td>
              <td class="text-center p-3">
                @if($item['overloaded'])
                  <span class="text-red-600 font-semibold">⚠️ Overloaded</span>
                @else
                  <span class="text-green-600 font-semibold">✓ Normal</span>
                @endif
              </td>
            </tr>
            @endforeach
            </tbody>
          </tbody>
        </table>
      </div>
      @if(count($workloadSummary) > 10)
      <div class="mt-4">
        <button id="toggleWorkloadBtn" onclick="toggleWorkloadTable()" class="px-5 py-2 bg-blue-600 text-white text-base rounded hover:bg-blue-700">
          View All {{ count($workloadSummary) }} Faculty
        </button>
      </div>
      <script>
        function toggleWorkloadTable() {
          const hidden = document.getElementById('workloadTableHidden');
          const btn = document.getElementById('toggleWorkloadBtn');
          if (hidden.classList.contains('hidden')) {
            hidden.classList.remove('hidden');
            btn.textContent = 'Show Less';
          } else {
            hidden.classList.add('hidden');
            btn.textContent = 'View All {{ count($workloadSummary) }} Faculty';
          }
        }
      </script>
      @endif
    @else
      <p class="text-slate-600 text-base">No workload data available yet.</p>
    @endif
  </div>
</div>

<!-- Subject Load Balance -->
<div class="mt-8 p-6 border rounded-xl bg-green-50">
  <h3 class="font-bold text-green-900 text-2xl mb-2">📚 Subject Load Balance</h3>
  <p class="mb-4 text-base text-green-800">Distribution of subjects and faculty assignments across grade levels.</p>
  @if($subjectLoadBalance)
    <!-- Junior High School -->
    @if($subjectLoadBalance['junior_high'] && count($subjectLoadBalance['junior_high']['subjects']) > 0)
    <div class="mt-6">
      <h4 class="font-semibold text-green-800 border-b-2 border-green-400 pb-2 text-lg">🏫 Junior High School</h4>
      <div class="mt-4 overflow-x-auto bg-white rounded-xl border border-green-200">
        <table class="w-full text-base">
          <thead class="bg-green-100">
            <tr>
              <th class="text-left p-3 font-semibold">Subject</th>
              @foreach($subjectLoadBalance['junior_high']['grades'] as $grade)
                <th class="text-center p-3 font-semibold">{{ $grade->name }}</th>
              @endforeach
            </tr>
          </thead>
          <tbody>
            @foreach($subjectLoadBalance['junior_high']['subjects'] as $row)
            <tr class="border-b hover:bg-green-50">
              <td class="p-3 font-medium text-slate-700">{{ $row['subject'] }}</td>
              @foreach($subjectLoadBalance['junior_high']['grades'] as $grade)
                <td class="text-center p-3">
                  <span class="inline-block bg-green-100 text-green-900 px-4 py-2 rounded-full text-base font-semibold">
                    {{ $row['counts'][$grade->name] ?? 0 }}
                  </span>
                </td>
              @endforeach
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
    @endif
    <!-- Senior High School -->
    @if($subjectLoadBalance['senior_high'] && count($subjectLoadBalance['senior_high']['subjects']) > 0)
    <div class="mt-6">
      <h4 class="font-semibold text-green-800 border-b-2 border-green-400 pb-2 text-lg">🎓 Senior High School</h4>
      @foreach($subjectLoadBalance['senior_high']['grades_by_year'] as $year => $gradesInYear)
      <div class="mt-4">
        <h5 class="font-semibold text-green-700 text-base bg-green-50 p-2 rounded">Grade {{ $year }}</h5>
        <div class="mt-2 overflow-x-auto bg-white rounded-xl border border-green-200">
          <table class="w-full text-base">
            <thead class="bg-green-100">
              <tr>
                <th class="text-left p-3 font-semibold">Subject</th>
                @foreach($gradesInYear as $grade)
                  <th class="text-center p-3 font-semibold">{{ $grade->name }}</th>
                @endforeach
              </tr>
            </thead>
            <tbody>
              @foreach($subjectLoadBalance['senior_high']['subjects'] as $row)
              <tr class="border-b hover:bg-green-50">
                <td class="p-3 font-medium text-slate-700">{{ $row['subject'] }}</td>
                @foreach($gradesInYear as $grade)
                  <td class="text-center p-3">
                    <span class="inline-block bg-green-100 text-green-900 px-4 py-2 rounded-full text-base font-semibold">
                      {{ $row['counts'][$grade->name] ?? 0 }}
                    </span>
                  </td>
                @endforeach
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
      @endforeach
    </div>
    @endif
    @if(!($subjectLoadBalance['junior_high'] && count($subjectLoadBalance['junior_high']['subjects']) > 0) && !($subjectLoadBalance['senior_high'] && count($subjectLoadBalance['senior_high']['subjects']) > 0))
    <div class="mt-4 bg-white p-4 rounded-xl border border-green-200">
      <p class="text-slate-600 text-base">No grade levels or subject data available yet.</p>
    </div>
    @endif
  @else
  <div class="mt-4 bg-white p-4 rounded-xl border border-green-200">
    <p class="text-slate-600 text-base">No grade levels or subject data available yet.</p>
  </div>
  @endif
</div>

<!-- Scheduling Status -->
<div class="mt-8 p-6 border rounded-xl bg-purple-50">
  <h3 class="font-bold text-purple-900 text-2xl mb-2">⏱️ Scheduling Status</h3>
  <p class="mb-4 text-base text-purple-800">Current state of the generated schedule.</p>
  <div class="bg-white p-4 rounded-xl border border-purple-200">
    @if($schedulingStatus['exists'])
      <div class="space-y-4">
        <div class="flex items-center justify-between">
          <span class="text-base font-medium text-slate-700">Run:</span>
          <span class="text-base text-slate-900 font-semibold">{{ $schedulingStatus['run_name'] }}</span>
        </div>
        <div class="flex items-center justify-between">
          <span class="text-base font-medium text-slate-700">Status:</span>
          <span class="inline-block px-4 py-2 rounded-full text-base font-semibold
            {{
              $schedulingStatus['status_badge'] === 'success' ? 'bg-green-100 text-green-900' :
              ($schedulingStatus['status_badge'] === 'warning' ? 'bg-yellow-100 text-yellow-900' :
              ($schedulingStatus['status_badge'] === 'info' ? 'bg-blue-100 text-blue-900' :
              'bg-slate-100 text-slate-900'))
            }}"
          >
            {{ $schedulingStatus['status'] }}
          </span>
        </div>
        <div class="flex items-center justify-between">
          <span class="text-base font-medium text-slate-700">Published:</span>
          <span class="text-base">
            @if($schedulingStatus['is_published'])
              <span class="text-green-600 font-semibold">✓ Yes</span>
              @if($schedulingStatus['published_at'])
                <span class="text-xs text-slate-600">({{ $schedulingStatus['published_at']->format('M d, Y H:i') }})</span>
              @endif
            @else
              <span class="text-orange-600 font-semibold">✗ No (Draft)</span>
            @endif
          </span>
        </div>
        <div class="border-t border-slate-200 pt-4 mt-4">
          <div class="grid grid-cols-3 gap-4">
            <div class="bg-slate-50 p-4 rounded-xl text-center">
              <div class="text-xl font-bold text-purple-700">{{ $schedulingStatus['total_entries'] }}</div>
              <div class="text-base text-slate-600">Slots Filled</div>
            </div>
            <div class="bg-slate-50 p-4 rounded-xl text-center">
              <div class="text-xl font-bold {{ $schedulingStatus['conflict_free'] ? 'text-green-700' : 'text-red-700' }}">
                {{ $schedulingStatus['conflicts'] }}
              </div>
              <div class="text-base text-slate-600">Conflicts</div>
            </div>
            <div class="bg-slate-50 p-4 rounded-xl text-center">
              <div class="text-base font-semibold text-slate-700">{{ $schedulingStatus['created_at']->format('M d, Y') }}</div>
              <div class="text-base text-slate-600">Generated</div>
            </div>
          </div>
        </div>
        <div class="mt-4 flex gap-2">
          <a href="{{ route('admin.scheduler.show', $schedulingStatus['run_id']) }}" class="flex-1 px-4 py-3 bg-purple-600 text-white text-base rounded-xl hover:bg-purple-700 text-center font-medium">
            View Schedule
          </a>
        </div>
      </div>
    @else
      <div class="text-center py-6">
        <p class="text-slate-600 text-base mb-3">No schedule has been generated yet.</p>
        <a href="{{ route('admin.scheduler.index') }}" class="inline-block px-5 py-3 bg-purple-600 text-white text-base rounded-xl hover:bg-purple-700 font-medium">
          Generate Schedule
        </a>
      </div>
    @endif
  </div>
</div>

<!-- Conflict Report -->
<div class="mt-8 p-6 border rounded-xl bg-orange-50">
  <h3 class="font-bold text-orange-900 text-2xl mb-2">⚡ Conflict Report</h3>
  <p class="mb-4 text-base text-orange-800">Scheduling conflicts detected (same faculty in 2 places, overlapping schedules, etc.)</p>
  <div class="bg-white p-4 rounded-xl border border-orange-200">
    @if($conflictReport['exists'])
      @if($conflictReport['conflict_free'])
        <div class="text-center py-8">
          <div class="text-5xl mb-3">✅</div>
          <p class="text-green-700 font-semibold text-xl">No Conflicts Detected!</p>
          <p class="text-base text-slate-600 mt-1">The current schedule is conflict-free.</p>
        </div>
      @else
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl">
          <div class="flex items-center gap-3">
            <span class="text-2xl">⚠️</span>
            <div>
              <p class="font-semibold text-red-900 text-lg">{{ $conflictReport['total_conflicts'] }} Conflict{{ $conflictReport['total_conflicts'] > 1 ? 's' : '' }} Found</p>
              <p class="text-base text-red-700">These conflicts need to be resolved before publishing the schedule.</p>
            </div>
          </div>
        </div>
        @if(count($conflictReport['conflicts']) > 0)
        <div class="overflow-x-auto">
          <table class="w-full text-base">
            <thead class="bg-orange-100">
              <tr>
                <th class="text-left p-3 font-semibold">Faculty</th>
                <th class="text-left p-3 font-semibold">Subject</th>
                <th class="text-left p-3 font-semibold">Section</th>
                <th class="text-center p-3 font-semibold">Day/Period</th>
                <th class="text-left p-3 font-semibold">Conflict Type</th>
              </tr>
            </thead>
            <tbody>
              @foreach($conflictReport['conflicts'] as $conflict)
              <tr class="border-b hover:bg-orange-50">
                <td class="p-3 font-medium text-slate-700">{{ $conflict['teacher'] }}</td>
                <td class="p-3 text-slate-700">{{ $conflict['subject'] }}</td>
                <td class="p-3 text-slate-700">{{ $conflict['section'] }}</td>
                <td class="text-center p-3 text-slate-600 text-base">
                  Day {{ $conflict['day'] }}, Period {{ $conflict['period'] }}
                </td>
                <td class="p-3 text-red-600 text-base">{{ $conflict['conflict_type'] }}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        @if($conflictReport['has_more'])
        <p class="text-base text-slate-600 mt-4 text-center">
          Showing 10 of {{ $conflictReport['total_conflicts'] }} conflicts.
          <a href="{{ route('admin.scheduler.show', $conflictReport['run_id']) }}" class="text-orange-700 hover:underline font-medium">View all in scheduler →</a>
        </p>
        @endif
        @endif
        <div class="mt-6">
          <a href="{{ route('admin.scheduler.show', $conflictReport['run_id']) }}" class="block text-center px-5 py-3 bg-orange-600 text-white text-base rounded-xl hover:bg-orange-700 font-medium">
            Resolve Conflicts in Scheduler
          </a>
        </div>
      @endif
    @else
      <p class="text-slate-600 text-base text-center py-6">No schedule available. Generate a schedule first to detect conflicts.</p>
    @endif
  </div>
</div>

<!-- Faculty Availability -->
<div class="mt-8 p-6 border rounded-xl bg-indigo-50">
  <h3 class="font-bold text-indigo-900 text-2xl mb-2">👥 Faculty Availability</h3>
  <p class="mb-4 text-base text-indigo-800">Quick status of faculty availability and scheduling restrictions.</p>
  <div class="bg-white p-4 rounded-xl border border-indigo-200">
    <div class="grid grid-cols-2 gap-6 mb-6">
      <div class="bg-green-50 p-4 rounded-xl border border-green-200 text-center">
        <div class="text-3xl font-bold text-green-700">{{ $teacherAvailability['fully_available'] }}</div>
        <div class="text-base text-green-800 mt-2">Fully Available</div>
      </div>
      <div class="bg-yellow-50 p-4 rounded-xl border border-yellow-200 text-center">
        <div class="text-3xl font-bold text-yellow-700">{{ $teacherAvailability['restricted'] }}</div>
        <div class="text-base text-yellow-800 mt-2">With Restrictions</div>
      </div>
    </div>
    @if(count($teacherAvailability['restricted_teachers']) > 0)
    <div class="mt-6">
      <h4 class="font-semibold text-indigo-800 text-base mb-3">Faculty with Availability Restrictions:</h4>
      <div class="space-y-3">
        @foreach($teacherAvailability['restricted_teachers'] as $teacher)
        <div class="flex items-center justify-between bg-slate-50 p-3 rounded-xl text-base">
          <span class="font-medium text-slate-700">{{ $teacher['name'] }}</span>
          <span class="text-base text-yellow-700 bg-yellow-100 px-3 py-2 rounded">{{ $teacher['restrictions'] }}</span>
        </div>
        @endforeach
      </div>
      @if($teacherAvailability['has_more'])
      <p class="text-base text-slate-600 mt-4 text-center">
        Showing 10 of {{ $teacherAvailability['restricted'] }} faculty with restrictions.
        <a href="{{ route('admin.teachers.index') }}" class="text-indigo-700 hover:underline font-medium">View all faculty →</a>
      </p>
      @endif
    </div>
    @else
    <p class="text-slate-600 text-base text-center py-3">All faculty are fully available with no restrictions.</p>
    @endif
    <div class="mt-6 pt-6 border-t border-indigo-100">
      <p class="text-base text-slate-600">
        <strong>Note:</strong> Faculty marked as "With Restrictions" have limited availability on certain days or periods. 
        Review their profiles before scheduling.
      </p>
    </div>
  </div>
</div>

<!-- Recent Activity -->
<div class="mt-8 p-6 border rounded-xl bg-yellow-50">
  <h3 class="font-bold text-yellow-900 text-2xl mb-2">📝 Recent Activity</h3>
  <p class="mb-4 text-base text-yellow-800">Last changes made across the system.</p>
  <div class="bg-white p-4 rounded-xl border border-yellow-200">
    @if($recentActivity && count($recentActivity) > 0)
      <div class="space-y-4">
        @foreach($recentActivity as $activity)
        <div class="flex items-start gap-4 p-3 rounded-xl hover:bg-yellow-50 transition">
          <div class="text-2xl flex-shrink-0">{{ $activity['icon'] }}</div>
          <div class="flex-1 min-w-0">
            <div class="flex items-start justify-between gap-3">
              <div>
                <p class="text-base font-semibold text-slate-800">{{ $activity['title'] }}</p>
                <p class="text-base text-slate-600 truncate">{{ $activity['description'] }}</p>
              </div>
              <span class="text-xs text-slate-500 whitespace-nowrap">
                {{ $activity['timestamp']->diffForHumans() }}
              </span>
            </div>
            @if($activity['link'])
            <a href="{{ $activity['link'] }}" class="text-base text-yellow-700 hover:underline mt-2 inline-block">
              View details →
            </a>
            @endif
          </div>
        </div>
        @endforeach
      </div>
    @else
      <p class="text-slate-600 text-base text-center py-6">No recent activity found.</p>
    @endif
  </div>
</div>
@endsection
