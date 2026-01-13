@php
  $isEdit = isset($subject) && $subject;
  $action = $isEdit ? route('admin.subjects.update', $subject) : route('admin.subjects.store');
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
      <select id="subject-type-frag" name="type" class="mt-1 block w-full border p-2">
        @php
          $types = ['core'=>'Core','special'=>'Special','abm'=>'ABM','humss'=>'HUMSS','stem'=>'STEM','tvl'=>'TVL','gas'=>'GAS','jhs_core'=>'JHS Core','tle'=>'TLE/TVL','spa'=>'Special Program in the Arts','journalism'=>'Journalism','shs_core'=>'SHS Core','shs_applied'=>'SHS Applied','shs_strand'=>'SHS Strand'];
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
            <input type="checkbox" name="grade_levels[]" value="{{ $g->id }}" data-year="{{ $g->year }}" class="mr-2" @if(in_array($g->id, $availGrades)) checked @endif />
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
    <button type="submit" class="px-4 py-2 bg-[#3b4197] text-white rounded">{{ $isEdit ? 'Save' : 'Create' }}</button>
    <a href="{{ route('admin.subjects.index') }}" class="ml-2 text-sm">Cancel</a>
  </div>
</form>

<script>
  (function(){
    const form = document.currentScript ? document.currentScript.parentElement.querySelector('form') : document.querySelector('form');
    if(!form) return;
    const select = form.querySelector('#subject-type-frag');
    const gradeCbs = Array.from(form.querySelectorAll('input[name="grade_levels[]"]'));
    const submitBtn = form.querySelector('button[type="submit"]');

    function hasJunior(){ return gradeCbs.some(cb=>{ const y = cb.getAttribute('data-year'); return y && Number(y) >=7 && Number(y) <=10 && cb.checked; }); }
    function hasSenior(){ return gradeCbs.some(cb=>{ const y = cb.getAttribute('data-year'); return y && Number(y) >=11 && Number(y) <=12 && cb.checked; }); }

    function update(){
      if(!select) return;
      const junior = hasJunior(); const senior = hasSenior();
      Array.from(select.options).forEach(opt=>{
        const v = (opt.value||'').toLowerCase();
        if(v === 'core') opt.disabled = false;
        else if(v === 'special') opt.disabled = !junior;
        else if(['abm','humss','stem','tvl','gas'].indexOf(v) !== -1) opt.disabled = !senior;
        else opt.disabled = false;
      });
      const cur = (select.value||'').toLowerCase();
      const curOpt = Array.from(select.options).find(o=> (o.value||'').toLowerCase()===cur );
      if(curOpt && curOpt.disabled){ select.value = 'core'; }
      if(submitBtn) submitBtn.disabled = !gradeCbs.some(cb=>cb.checked);
    }

    gradeCbs.forEach(cb=>cb.addEventListener('change', update));
    update();
  })();
</script>
@php
  $isEdit = isset($subject) && $subject;
  $action = $isEdit ? route('admin.subjects.update', $subject) : route('admin.subjects.store');
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
      <select id="subject-type-frag" name="type" class="mt-1 block w-full border p-2">
        @php
          $types = ['core'=>'Core','special'=>'Special','abm'=>'ABM','humss'=>'HUMSS','stem'=>'STEM','tvl'=>'TVL','gas'=>'GAS','jhs_core'=>'JHS Core','tle'=>'TLE/TVL','spa'=>'Special Program in the Arts','journalism'=>'Journalism','shs_core'=>'SHS Core','shs_applied'=>'SHS Applied','shs_strand'=>'SHS Strand'];
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
            <input type="checkbox" name="grade_levels[]" value="{{ $g->id }}" data-year="{{ $g->year }}" class="mr-2" @if(in_array($g->id, $availGrades)) checked @endif />
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
    <button type="submit" class="px-4 py-2 bg-[#3b4197] text-white rounded">{{ $isEdit ? 'Save' : 'Create' }}</button>
    <a href="{{ route('admin.subjects.index') }}" class="ml-2 text-sm">Cancel</a>
  </div>
</form>
