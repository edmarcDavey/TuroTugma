@php
  $isEdit = isset($subject) && $subject;
  $action = $isEdit ? route('admin.it.subjects.update', $subject) : route('admin.it.subjects.store');
  $availGrades = old('grade_levels', $isEdit && isset($subject->gradeLevels) ? $subject->gradeLevels->pluck('id')->toArray() : []);
@endphp

<form method="POST" action="{{ $action }}">
  @csrf
  @if($isEdit) @method('PUT') @endif

  <div class="grid grid-cols-2 gap-4">
    <div>
      <label class="block text-sm font-medium">Code</label>
      <input name="code" value="{{ old('code', $subject->code ?? '') }}" class="mt-1 block w-full border p-2" />
    </div>
    <div>
      <label class="block text-sm font-medium">Type / Category</label>
      <select name="type" class="mt-1 block w-full border p-2">
        @php
          $types = ['jhs_core'=>'JHS Core','tle'=>'TLE/TVL','spa'=>'Special Program in the Arts','journalism'=>'Journalism','shs_core'=>'SHS Core','shs_applied'=>'SHS Applied','shs_strand'=>'SHS Strand'];
        @endphp
        <option value="">-- select --</option>
        @foreach($types as $k=>$v)
          <option value="{{ $k }}" @if(old('type', $subject->type ?? '') == $k) selected @endif>{{ $v }}</option>
        @endforeach
      </select>
    </div>

    <div class="col-span-2">
      <label class="block text-sm font-medium">Name</label>
      <input name="name" required value="{{ old('name', $subject->name ?? '') }}" class="mt-1 block w-full border p-2" />
    </div>

    <div class="col-span-2">
      <label class="block text-sm font-medium">Applicable Grade Levels</label>
      <div class="mt-1 grid grid-cols-3 gap-2 p-2 border rounded bg-white">
        @foreach($gradeLevels as $g)
          <label class="inline-flex items-center mr-2">
            <input type="checkbox" name="grade_levels[]" value="{{ $g->id }}" class="mr-2" @if(in_array($g->id, $availGrades)) checked @endif />
            <span class="text-sm">{{ $g->name }}</span>
          </label>
        @endforeach
      </div>
    </div>

    <div class="col-span-2">
      <label class="block text-sm font-medium">Description / Notes</label>
      <textarea name="description" class="mt-1 block w-full border p-2" rows="3">{{ old('description', $subject->description ?? '') }}</textarea>
    </div>
  </div>

  <div class="mt-4">
    <button class="px-4 py-2 bg-[#3b4197] text-white rounded">{{ $isEdit ? 'Save' : 'Create' }}</button>
    <a href="{{ route('admin.it.subjects.index') }}" class="ml-2 text-sm">Cancel</a>
  </div>
</form>
