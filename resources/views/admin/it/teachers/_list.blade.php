@foreach($teachers as $t)
  <li data-id="{{ $t->id }}" class="p-2 border rounded cursor-pointer teacher-row bg-white hover:bg-slate-50">
    <div class="font-medium">{{ $t->name }}</div>
    <div class="text-xs text-slate-600">{{ $t->staff_id }}</div>
  </li>
@endforeach
