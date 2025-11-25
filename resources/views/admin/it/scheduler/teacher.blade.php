@extends('admin.it.layout')

@section('title','Teacher Schedule')
@section('heading','Teacher Schedule')

@section('content')
  <div class="p-6 bg-white border rounded">
    <div class="flex items-center justify-between mb-4">
      <div>
        <h3 class="text-lg font-semibold">{{ $teacher->name }}</h3>
        <div class="text-sm text-slate-500">Schedule for run: {{ $run->name ?? $run->id }}</div>
      </div>
      <div class="flex items-center gap-2">
        <a href="{{ route('admin.it.scheduler.run') }}" class="px-3 py-1 border rounded bg-white">Back</a>
        <a href="{{ route('admin.it.scheduler.teacher.substitutes', ['run'=>$run->id,'teacher'=>$teacher->id]) }}" class="px-3 py-1 border rounded bg-white">Show Substitutes</a>
        <a href="{{ route('admin.it.scheduler.matrix', ['run'=>$run->id]) }}" class="px-3 py-1 border rounded bg-white">Compact Matrix</a>
      </div>
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
              <td class="border px-1 py-1 align-top">
                @if(isset($entriesBySlot[$d][$p]))
                  @php $e = $entriesBySlot[$d][$p]; @endphp
                  <div class="flex items-start justify-between gap-2">
                    <div>
                      <div class="font-medium">{{ $e->subject->name ?? '—' }}</div>
                      <div class="text-xs text-slate-600">Section: {{ $e->section->name ?? '—' }}</div>
                    </div>
                    @if($e->conflict)
                      <div class="text-xs text-red-600 font-medium" title="{{ collect($e->conflict)->pluck('message')->implode('; ') }}">⚠</div>
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
@endsection
