@extends('admin.it.layout')

@section('title','Scheduling')
@section('heading','Scheduling Workspace')

@section('content')
  <div id="runs-root" class="p-6 border rounded bg-white" data-base-url="{{ url('/admin/it/scheduler') }}">
    <div class="flex items-center justify-between mb-4">
      <div>
        <h2 class="text-lg font-semibold">Scheduling Runs</h2>
        <p class="text-sm text-slate-600">Select a run and view schedules by Class or Teacher. You can also generate and view substitutes.</p>
      </div>
      <div class="flex items-center gap-3">
        <form method="post" action="{{ route('admin.it.scheduler.store') }}">
          @csrf
          <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Create Draft Run</button>
        </form>
      </div>
    </div>

    @if(isset($runs) && $runs->count())
      <div class="space-y-2">
        @foreach($runs as $run)
          <div class="p-3 border rounded">
            <div class="flex items-center justify-between">
              <div>
                <div class="font-medium">{{ $run->name ?? ('Run #' . $run->id) }}</div>
                <div class="text-xs text-slate-500">Status: {{ ucfirst($run->status) }} • Created: {{ $run->created_at->format('Y-m-d H:i') }}</div>
              </div>
              <div class="flex items-center gap-2">
                <a href="{{ route('admin.it.scheduler.show', ['run'=>$run->id]) }}" class="px-3 py-1 border rounded text-sm bg-white">Detailed</a>
                <a href="{{ route('admin.it.scheduler.matrix', ['run'=>$run->id]) }}" class="px-3 py-1 border rounded text-sm bg-white">Matrix</a>
                <a href="{{ route('admin.it.scheduler.substitutions', ['run'=>$run->id]) }}" class="px-3 py-1 border rounded text-sm bg-white">Substitutions</a>
              </div>
            </div>

            <div class="mt-3 flex items-center gap-3">
              <div class="text-xs text-slate-600">View by teacher:</div>
              <form id="teacher-view-form-{{ $run->id }}" method="get" action="#">
                <select id="teacher-select-{{ $run->id }}" class="border px-2 py-1 text-sm">
                  <option value="">— Select Teacher —</option>
                  @foreach($teachers as $t)
                    <option value="{{ $t->id }}">{{ $t->name }}</option>
                  @endforeach
                </select>
                <button type="button" data-run-id="{{ $run->id }}" class="open-teacher-btn px-2 py-1 ml-2 border rounded text-sm bg-white">Open</button>
              </form>
            </div>
          </div>
        @endforeach
      </div>
    @else
      <div class="p-4 text-sm text-slate-600">No scheduling runs yet. Click <strong>Create Draft Run</strong> to start a new draft.</div>
    @endif
  </div>

  <script>
    function openTeacher(runId){
      var sel = document.getElementById('teacher-select-'+runId);
      if(!sel) return;
      var teacherId = sel.value;
      if(!teacherId){ alert('Please select a teacher'); return; }
      var base = document.getElementById('runs-root').dataset.baseUrl;
      var url = base + '/' + runId + '/teacher/' + encodeURIComponent(teacherId);
      window.location.href = url;
    }
    // attach click handlers for open buttons
    document.addEventListener('DOMContentLoaded', function(){
      document.querySelectorAll('.open-teacher-btn').forEach(btn => {
        btn.addEventListener('click', function(){
          const runId = this.dataset.runId;
          openTeacher(runId);
        });
      });
    });
  </script>
@endsection
