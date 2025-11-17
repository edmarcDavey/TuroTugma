@php
  $isEdit = isset($teacher) && $teacher;
  $action = $isEdit ? route('admin.it.teachers.update', $teacher) : route('admin.it.teachers.store');
  // ensure $subjects is available even if the parent view forgot to pass it (fallback to DB)
  $subjects = isset($subjects) ? $subjects : \App\Models\Subject::orderBy('name')->get();
  $selectedSubjects = old('subjects', $isEdit && isset($teacher) && isset($teacher->subjects) ? $teacher->subjects->pluck('id')->toArray() : []);
@endphp

<form method="POST" action="{{ $action }}">
  @csrf
  @if($isEdit) @method('PUT') @endif

  <div class="space-y-4">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
  <div class="md:col-span-2">
    <label for="name" class="block text-sm font-medium">Name</label>
  <input id="name" name="name" required value="{{ old('name', $teacher->name ?? '') }}" class="mt-1 block w-full border border-gray-300 rounded-md p-2 h-11 bg-white box-border" />
      </div>

  <div class="md:col-span-1">
    <label for="sex" class="block text-sm font-medium">Sex</label>
  <select id="sex" name="sex" class="mt-1 block w-full border border-gray-300 rounded-md p-2 h-11 bg-white box-border" title="Sex">
          <option value="">-- select --</option>
          <option value="male" @if(old('sex', $teacher->sex ?? '') === 'male') selected @endif>Male</option>
          <option value="female" @if(old('sex', $teacher->sex ?? '') === 'female') selected @endif>Female</option>
          <option value="other" @if(old('sex', $teacher->sex ?? '') === 'other') selected @endif>Other</option>
        </select>
      </div>

  <div class="md:col-span-1">
    <label for="staff_id" class="block text-sm font-medium">Employee ID</label>
  <input id="staff_id" name="staff_id" value="{{ old('staff_id', $teacher->staff_id ?? '') }}" class="mt-1 block w-full border border-gray-300 rounded-md p-2 h-11 bg-white box-border" />
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
  <div>
    <label for="designation" class="block text-sm font-medium">Designation / Position</label>
  <input id="designation" name="designation" value="{{ old('designation', $teacher->designation ?? '') }}" class="mt-1 block w-full border border-gray-300 rounded-md p-2 h-11 bg-white box-border" />
      </div>

  <div>
    <label for="status_of_appointment" class="block text-sm font-medium">Status of Appointment</label>
  <input id="status_of_appointment" name="status_of_appointment" value="{{ old('status_of_appointment', $teacher->status_of_appointment ?? '') }}" class="mt-1 block w-full border border-gray-300 rounded-md p-2 h-11 bg-white box-border" />
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
  <div>
    <label for="course_degree" class="block text-sm font-medium">Course / Degree</label>
  <input id="course_degree" name="course_degree" value="{{ old('course_degree', $teacher->course_degree ?? '') }}" class="mt-1 block w-full border border-gray-300 rounded-md p-2 h-11 bg-white box-border" />
      </div>

  <div>
    <label for="course_major" class="block text-sm font-medium">Course Major</label>
  <input id="course_major" name="course_major" value="{{ old('course_major', $teacher->course_major ?? '') }}" class="mt-1 block w-full border border-gray-300 rounded-md p-2 h-11 bg-white box-border" />
      </div>

  <div>
    <label for="course_minor" class="block text-sm font-medium">Course Minor</label>
  <input id="course_minor" name="course_minor" value="{{ old('course_minor', $teacher->course_minor ?? '') }}" class="mt-1 block w-full border border-gray-300 rounded-md p-2 h-11 bg-white box-border" />
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
      <div>
    <label for="ancillary_assignments" class="block text-sm font-medium">Ancillary / Special Assignments</label>
  <textarea id="ancillary_assignments" name="ancillary_assignments" placeholder="e.g. Class sponsor, dept. coordinator" class="mt-1 block w-full border border-gray-300 rounded-md p-2 h-11 bg-white resize-none box-border" rows="2">{{ old('ancillary_assignments', $teacher->ancillary_assignments ?? '') }}</textarea>
        <p class="text-xs text-slate-500 mt-1">Keep it short — a brief note about additional duties (optional).</p>
      </div>

  <div>
    <label for="number_handled_per_week" class="block text-sm font-medium">Number of Handled Class per Week (max 8)</label>
  <input id="number_handled_per_week" name="number_handled_per_week" type="number" min="0" max="8" value="{{ old('number_handled_per_week', $teacher->number_handled_per_week ?? '') }}" class="mt-1 block w-full border border-gray-300 rounded-md p-2 h-11 bg-white box-border" />
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
      <div>
  <label class="block text-sm font-medium">Subject Assignment</label>

      <style>
        .ms-container{ position:relative; }
        .ms-control{ display:flex; align-items:center; gap:8px; flex-wrap:wrap; padding:8px; border:1px solid #d1d5db; border-radius:6px; min-height:44px; background:#fff; }
        .ms-token{ background:#eef2ff; color:#1e293b; padding:6px 8px; border-radius:9999px; font-size:0.9rem; display:inline-flex; gap:6px; align-items:center; }
        .ms-token button{ background:transparent; border:0; color:#334155; cursor:pointer; padding:0 4px; }
        .ms-button{ margin-left:auto; background:transparent; border:0; cursor:pointer; color:#334155; }
        .ms-dropdown{ position:absolute; left:0; right:0; top:calc(100% + 6px); background:#fff; border:1px solid #e6eef6; box-shadow:0 6px 18px rgba(16,24,40,0.08); border-radius:8px; z-index:50; max-height:280px; overflow:auto; display:none; }
        .ms-search{ padding:10px; border-bottom:1px solid #eef2ff; }
        .ms-list{ padding:8px; }
        .ms-item{ display:flex; align-items:center; gap:8px; padding:8px; border-radius:6px; cursor:pointer; }
        .ms-item:hover{ background:#f8fafc; }
        .ms-select-all{ display:flex; align-items:center; gap:8px; padding:8px 10px; border-bottom:1px solid #f1f5f9; }
        .ms-empty{ padding:12px; color:#64748b; }
        .ms-native-hidden{ display:none !important; }
        .ms-tokens{ display:flex; gap:8px; flex-wrap:wrap; }
      </style>

      {{-- hidden native select to submit values --}}
  <select id="subjects-native" name="subjects[]" multiple class="ms-native-hidden" title="Subjects">
        @foreach($subjects as $s)
          <option value="{{ $s->id }}" @if(in_array($s->id, $selectedSubjects)) selected @endif>{{ $s->name }}</option>
        @endforeach
      </select>

      <div class="ms-container mt-1">
      <div class="ms-control" id="subjects-control" tabindex="0" role="combobox" aria-expanded="false" aria-haspopup="listbox" aria-label="Subject Assignment">
              <div id="ms-tokens" class="ms-tokens">
            {{-- tokens inserted here --}}
          </div>
          <button type="button" id="ms-toggle" class="ms-button" aria-label="Toggle subjects dropdown">▾</button>
        </div>

        <div class="ms-dropdown" role="listbox" aria-multiselectable="true">
            <div class="ms-search"><input aria-label="Search subjects" placeholder="Search..." class="w-full border rounded px-2 py-1" /></div>
            <div class="ms-select-all"><input id="subjects-select-all" type="checkbox" /> <label for="subjects-select-all">Select all</label></div>
            <div class="ms-list">
              @foreach($subjects as $s)
                <div class="ms-item" data-id="{{ $s->id }}" role="option" aria-selected="{{ in_array($s->id,$selectedSubjects) ? 'true' : 'false' }}">
                  <input type="checkbox" class="ms-checkbox" data-id="{{ $s->id }}" @if(in_array($s->id,$selectedSubjects)) checked @endif />
                  <div>{{ $s->name }} @if(!empty($s->code)) <small class="text-slate-400">({{ $s->code }})</small> @endif</div>
                </div>
              @endforeach
            </div>
          </div>
        </div>
      </div>

  <div>
  <label for="avail-control" class="block text-sm font-medium">Unavailable Periods</label>
      <input type="hidden" id="advisory-input" name="advisory" value="{{ old('advisory', $teacher->advisory ?? '') }}">

      {{-- reuse the ms-control look for periods; selections sync back into the hidden CSV advisory input --}}
  <select id="availability-native" multiple class="ms-native-hidden" title="Unavailable periods">
        @for($i=1;$i<=9;$i++)
          @php
            $val = (string)$i;
            $label = $i.'th Period';
            if($i==1) $label='1st Period';
            if($i==2) $label='2nd Period';
            if($i==3) $label='3rd Period';
          @endphp
          <option value="{{ $val }}" @if(in_array($val, explode(',', old('advisory', $teacher->advisory ?? '')))) selected @endif>{{ $label }}</option>
        @endfor
      </select>

      <div class="ms-container mt-1">
    <div class="ms-control" id="avail-control" tabindex="0" role="combobox" aria-expanded="false" aria-haspopup="listbox" aria-label="Unavailable Periods">
          <div id="avail-tokens" class="ms-tokens"></div>
          <button type="button" id="avail-toggle" class="ms-button" aria-label="Toggle availability dropdown">▾</button>
        </div>

        <div class="ms-dropdown" role="listbox" aria-multiselectable="true">
          <div class="ms-search"><input aria-label="Search periods" placeholder="Search..." class="w-full border rounded px-2 py-1" /></div>
          <div class="ms-select-all"><input id="avail-select-all" type="checkbox" /> <label for="avail-select-all">Select all</label></div>
          <div class="ms-list">
            @for($i=1;$i<=9;$i++)
              @php
                $val = (string)$i;
                $label = $i.'th Period';
                if($i==1) $label='1st Period';
                if($i==2) $label='2nd Period';
                if($i==3) $label='3rd Period';
              @endphp
              <div class="ms-item" data-id="{{ $val }}" role="option" aria-selected="false">
                <input type="checkbox" class="ms-checkbox" data-id="{{ $val }}" />
                <div>{{ $label }}</div>
              </div>
            @endfor
          </div>
        </div>
      </div>

      <p class="text-xs text-slate-500 mt-1">Optional — pick one or more periods the teacher cannot teach.</p>

      
    </div>

    <div class="pt-4">
      <button type="submit" class="px-4 py-2 bg-[#3b4197] text-white rounded">{{ $isEdit ? 'Save' : 'Create' }}</button>
      <a href="{{ route('admin.it.teachers.index') }}" class="ml-2 text-sm">Cancel</a>
    </div>
  </div>
</form>
