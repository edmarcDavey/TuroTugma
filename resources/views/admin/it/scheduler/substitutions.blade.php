@extends('admin.it.layout')

@section('title','Substitutions')
@section('heading','Substitutions & Absences')

@section('content')
  <div class="p-6 bg-white border rounded">
    <div class="flex items-center justify-between mb-4">
      <div>
        <h3 class="text-lg font-semibold">Substitutions</h3>
        <div class="text-sm text-slate-500">Recent absences and applied substitutions.</div>
      </div>
      <a href="{{ route('admin.it.scheduler.run') }}" class="px-3 py-1 border rounded bg-white">Back</a>
    </div>

    @if($absences->count())
      <div class="space-y-3">
        @foreach($absences as $a)
          <div class="p-3 border rounded">
            <div class="flex items-center justify-between">
              <div>
                <div class="font-medium">{{ $a->teacher->name }} — {{ $a->date->format('Y-m-d') }} @if($a->period) (P{{ $a->period }}) @endif</div>
                <div class="text-xs text-slate-600">Reason: {{ $a->reason ?? '—' }}</div>
              </div>
              <div class="text-sm">
                @if($a->substitutions && $a->substitutions->count())
                  <div class="text-xs text-slate-600">Substituted by:</div>
                  @foreach($a->substitutions as $s)
                    <div class="text-sm">{{ $s->substitute->name ?? '—' }} @if($s->applied_at) • {{ $s->applied_at->format('Y-m-d H:i') }} @endif</div>
                  @endforeach
                @else
                  <div class="text-xs text-slate-400">No substitution assigned</div>
                @endif
              </div>
            </div>
          </div>
        @endforeach
      </div>
    @else
      <div class="text-sm text-slate-500">No recent absences found.</div>
    @endif
  </div>
@endsection
