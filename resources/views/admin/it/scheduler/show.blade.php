@extends('admin.it.layout')

@section('title','Scheduling — Run')
@section('heading','Scheduling Run')

@section('content')
  <div class="p-6 bg-white border rounded">
    <div class="flex items-center justify-between mb-4">
      <div>
        <h3 class="text-lg font-semibold">{{ $run->name ?? ('Run #' . $run->id) }}</h3>
        <div class="text-sm text-slate-500">Status: {{ ucfirst($run->status) }} • Created: {{ $run->created_at->format('Y-m-d H:i') }}</div>
      </div>
      <div class="flex items-center gap-2">
          <a href="{{ route('admin.it.scheduler.run') }}" class="px-3 py-1 border rounded bg-white">Back</a>
          <form method="post" action="{{ route('admin.it.scheduler.generate', ['run'=>$run->id]) }}">
            @csrf
            <button type="submit" class="px-3 py-1 border rounded bg-amber-100 text-sm">Generate Draft</button>
          </form>
          <a href="#" class="px-3 py-1 border rounded bg-white">Export</a>
      </div>
    </div>

    @foreach($gradeLevels as $grade)
      <div class="mb-6">
        <h4 class="font-semibold">{{ $grade->name }}</h4>
        <div class="grid gap-3 grid-cols-1 md:grid-cols-2 mt-2">
          @foreach($grade->sections as $section)
            <div class="p-3 border rounded">
              <div class="flex items-center justify-between mb-2">
                <div class="font-medium">{{ $section->name }}</div>
                <a href="{{ route('admin.it.scheduler.teacher', ['run'=>$run->id,'teacher'=>0]) }}" class="text-xs text-slate-500">View Section</a>
              </div>

              <table class="w-full text-sm table-fixed border-collapse">
                <thead>
                  <tr>
                    <th class="border px-1 py-1">Day / Period</th>
                    @for($p=1;$p<=$maxPeriod;$p++)
                      <th class="border px-1 py-1">P{{ $p }}</th>
                    @endfor
                  </tr>
                </thead>
                <tbody>
                  @foreach($days as $d)
                    <tr>
                      <td class="border px-1 py-1">Day {{ $d }}</td>
                      @for($p=1;$p<=$maxPeriod;$p++)
                        @php
                          $cell = null;
                          if(isset($entriesBySection[$section->id])) {
                              $cell = $entriesBySection[$section->id]->firstWhere('day', $d)->firstWhere('period', $p);
                          }
                        @endphp
                        <td class="border px-1 py-1 align-top">
                          @if($cell)
                            <div class="flex items-start justify-between gap-2">
                              <div>
                                <div class="text-sm font-medium">{{ $cell->subject->name ?? '—' }}</div>
                                <div class="text-xs text-slate-600">{{ $cell->teacher->name ?? '—' }}</div>
                              </div>
                              @if($cell->conflict)
                                <div class="text-xs text-red-600 font-medium" title="{{ collect($cell->conflict)->pluck('message')->implode('; ') }}">⚠</div>
                              @endif
                            </div>
                          @else
                            <div class="text-xs text-slate-400">—</div>
                          @endif
                        </td>
                      @endfor
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          @endforeach
        </div>
      </div>
    @endforeach
  </div>
@endsection
