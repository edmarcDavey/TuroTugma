@extends('admin.it.layout')

@section('title','Teacher Substitutes')
@section('heading','Substitute Suggestions')

@section('content')
  <div class="p-6 bg-white border rounded">
    <div class="flex items-center justify-between mb-4">
      <div>
        <h3 class="text-lg font-semibold">Substitute Suggestions — {{ $teacher->name }}</h3>
        <div class="text-sm text-slate-500">Available substitute teachers per slot for run: {{ $run->name ?? $run->id }}</div>
      </div>
      <a href="{{ route('admin.it.scheduler.teacher', ['run'=>$run->id,'teacher'=>$teacher->id]) }}" class="px-3 py-1 border rounded bg-white">Back</a>
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
                @php $slotKey = "{$d}:{$p}"; $entry = $entriesBySlot[$d][$p] ?? null; @endphp
                @if($entry)
                  <div class="font-medium">{{ $entry->subject->name ?? '—' }}</div>
                  <div class="text-xs text-slate-600">Section: {{ $entry->section->name ?? '—' }}</div>
                  <div class="mt-1">
                    @if(isset($substitutesBySlot[$slotKey]) && count($substitutesBySlot[$slotKey]))
                      <div class="text-xs text-slate-700 font-medium">Candidates:</div>
                      <ul class="text-xs list-disc ml-4">
                        @foreach($substitutesBySlot[$slotKey] as $s)
                          <li>{{ $s['name'] }}</li>
                        @endforeach
                      </ul>
                    @else
                      <div class="text-xs text-slate-400">No available substitutes</div>
                    @endif
                  </div>
                @else
                  <div class="text-xs text-slate-400">No assignment for this teacher in this slot</div>
                @endif
              </td>
            @endfor
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
@endsection
