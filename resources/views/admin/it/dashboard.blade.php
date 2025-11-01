@extends('admin.it.layout')

@section('title','Dashboard')
@section('heading','IT Coordinator — Overview')

@section('content')
  <div class="grid grid-cols-3 gap-4">
    <div class="p-4 border rounded">Teachers<br><span class="text-sm text-slate-500">Placeholder count</span></div>
    <div class="p-4 border rounded">Sections<br><span class="text-sm text-slate-500">Placeholder count</span></div>
    <div class="p-4 border rounded">Rooms<br><span class="text-sm text-slate-500">Placeholder count</span></div>
  </div>

  <div class="mt-6 p-4 border rounded bg-slate-50">
    <strong>Quick actions</strong>
    <ul class="mt-2 list-disc pl-5 text-sm text-slate-600">
      <li>Import teachers (placeholder)</li>
      <li>Run scheduler (placeholder)</li>
      <li>View last run (placeholder)</li>
    </ul>
  </div>
@endsection
