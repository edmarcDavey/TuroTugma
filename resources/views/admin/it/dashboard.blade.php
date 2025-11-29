@extends('admin.it.layout')

@section('title','Dashboard')
@section('heading','Admin — Overview')

@section('content')
  <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
    <x-admin.metric-card title="Teachers" :value="$teachersCount ?? 0" description="Total registered teachers" />
    <x-admin.metric-card title="Sections" :value="$sectionsCount ?? 0" description="Total sections" />
    <x-admin.metric-card title="Subjects" :value="$subjectsCount ?? 0" description="Total subjects" />
  </div>

  <div class="mt-6 p-4 border rounded bg-slate-50">
    <strong>Data Analytics (placeholder)</strong>
    <p class="mt-2 text-sm text-slate-600">Charts and trend visualizations will appear here. Use the JSON endpoint <code>/admin/it/overview/data</code> to power charts.</p>
    <div class="mt-4 relative">
      <div id="overviewChartWrapper" class="w-full">
        <canvas id="overviewChart" width="600" height="240"></canvas>
      </div>

      <div id="overviewChartLoading" class="absolute inset-0 bg-white/60 backdrop-blur-sm hidden">
        <div class="flex items-center gap-3">
          <svg class="animate-spin h-6 w-6 text-slate-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
          </svg>
          <span class="text-sm text-slate-600">Loading chart…</span>
        </div>
      </div>

      <div id="overviewChartError" class="hidden absolute inset-0 bg-red-50">
        <div class="flex items-center justify-center w-full h-full">
          <div class="text-sm text-red-700">Unable to load analytics. Please try again later.</div>
        </div>
      </div>
    </div>
  </div>
@endsection
