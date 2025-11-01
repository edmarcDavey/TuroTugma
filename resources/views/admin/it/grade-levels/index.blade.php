@extends('admin.it.layout')

@section('title','Grade Levels & Sections')
@section('heading','Grade Levels & Sections')

@section('content')
  <div class="bg-white p-4 rounded shadow-sm">
    <table class="w-full text-sm">
      <thead>
        <tr class="text-left text-slate-600">
          <th class="py-2 px-3">Year Level</th>
          <th class="py-2 px-3">Section Naming Theme</th>
          <th class="py-2 px-3">Section Count</th>
        </tr>
      </thead>
      <tbody>
        @foreach($gradeLevels as $g)
          <tr>
            <td class="py-2 px-3">{{ $g->name }}</td>
            <td class="py-2 px-3">{{ $g->section_naming ?? '' }}</td>
            <td class="py-2 px-3">{{ $g->sections ? $g->sections->count() : ($g->sections_count ?? 0) }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
@endsection
