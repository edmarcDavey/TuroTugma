@php
  // $teacher may be null for create. Ensure variables exist.
  $isEdit = isset($teacher) && $teacher;
  $action = $isEdit ? route('admin.it.teachers.update', $teacher) : route('admin.it.teachers.store');
  $method = $isEdit ? 'PUT' : 'POST';
  $avail = old('availability', $teacher->availability ?? []);
  $selectedSubjects = old('subjects', $isEdit && isset($teacher->subjects) ? $teacher->subjects->pluck('id')->toArray() : []);
  $selectedGrades = old('grade_levels', $isEdit && isset($teacher->gradeLevels) ? $teacher->gradeLevels->pluck('id')->toArray() : []);
  $days = ['mon','tue','wed','thu','fri'];
  $periods = range(1,8);
@endphp

<form method="POST" action="{{ $action }}">
  @csrf
  @if($isEdit) @method('PUT') @endif

  <div class="grid grid-cols-2 gap-4">
    <div>
      <label class="block text-sm font-medium">Employee ID</label>
      <input name="staff_id" value="{{ old('staff_id', $teacher->staff_id ?? '') }}" class="mt-1 block w-full border p-2" />
    </div>
    <div>
      <label class="block text-sm font-medium">Name</label>
      <input name="name" required value="{{ old('name', $teacher->name ?? '') }}" class="mt-1 block w-full border p-2" />
    </div>
    <div>
      <label class="block text-sm font-medium">Email</label>
      <input name="email" value="{{ old('email', $teacher->email ?? '') }}" class="mt-1 block w-full border p-2" />
    </div>
    <div>
      <label class="block text-sm font-medium">Contact</label>
      <input name="contact" value="{{ old('contact', $teacher->contact ?? '') }}" class="mt-1 block w-full border p-2" />
    </div>

    <div class="col-span-2">
      <label class="block text-sm font-medium">Subjects (pick one or more)</label>
      <div class="mt-1 grid grid-cols-2 gap-2 p-2 border rounded bg-white">
        @foreach($subjects as $s)
          <label class="inline-flex items-center mr-2">
            <input type="checkbox" name="subjects[]" value="{{ $s->id }}" class="mr-2" @if(in_array($s->id, $selectedSubjects)) checked @endif />
            <span class="text-sm">{{ $s->name }} <span class="text-xs text-slate-500">({{ $s->code }})</span></span>
          </label>
        @endforeach
      </div>
    </div>

    <div class="col-span-2">
      <label class="block text-sm font-medium">Grade Levels (pick one or more)</label>
      <div class="mt-1 grid grid-cols-3 gap-2 p-2 border rounded bg-white">
        @foreach($gradeLevels as $g)
          <label class="inline-flex items-center mr-2">
            <input type="checkbox" name="grade_levels[]" value="{{ $g->id }}" class="mr-2" @if(in_array($g->id, $selectedGrades)) checked @endif />
            <span class="text-sm">{{ $g->name }}</span>
          </label>
        @endforeach
      </div>
    </div>

    <div>
      <label class="block text-sm font-medium">Max load / week</label>
      <input name="max_load_per_week" type="number" value="{{ old('max_load_per_week', $teacher->max_load_per_week ?? '') }}" class="mt-1 block w-full border p-2" />
    </div>
    <div>
      <label class="block text-sm font-medium">Max load / day</label>
      <input name="max_load_per_day" type="number" value="{{ old('max_load_per_day', $teacher->max_load_per_day ?? '') }}" class="mt-1 block w-full border p-2" />
    </div>

    <div class="col-span-2">
      <label class="block text-sm font-medium">Availability — mark periods you CANNOT teach</label>
      <div class="mt-2 grid grid-cols-6 gap-2">
        <div class="col-span-6 text-sm text-slate-600">Periods: 1..8, Days: Mon..Fri</div>
        <div class="col-span-1 font-semibold">Day\Period</div>
        @foreach($periods as $p)
          <div class="text-sm font-semibold">P{{ $p }}</div>
        @endforeach

        @foreach($days as $d)
          <div class="font-medium">{{ strtoupper($d) }}</div>
          @foreach($periods as $p)
            <div>
              <label class="inline-flex items-center">
                <input type="checkbox" name="availability[{{ $d }}][]" value="{{ $p }}" class="mr-2" @if(in_array($p, $avail[$d] ?? [])) checked @endif />
                <span class="text-sm">X</span>
              </label>
            </div>
          @endforeach
        @endforeach
      </div>
    </div>

  </div>

  <div class="mt-4">
    <button class="px-4 py-2 bg-[#3b4197] text-white rounded">{{ $isEdit ? 'Save' : 'Create' }}</button>
    <a href="{{ route('admin.it.teachers.index') }}" class="ml-2 text-sm">Cancel</a>
  </div>
</form>
