<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>IT Coordinator - @yield('title', 'Dashboard')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
  </head>
  <body class="bg-white text-slate-800 min-h-screen">
    <div class="flex">
      <aside class="w-64 p-6 border-r bg-slate-50 min-h-screen">
        <a href="{{ route('admin.it.dashboard') }}" class="text-xl font-bold text-[#3b4197]">IT Coordinator</a>
        <nav class="mt-6 space-y-3 text-base">
          <a href="{{ route('admin.it.dashboard') }}" class="block text-slate-700 hover:text-[#3b4197]">Overview</a>
          <a href="{{ route('admin.it.subjects-sections') }}" class="block text-slate-700 hover:text-[#3b4197]">Sections and Subjects</a>
          <a href="{{ route('admin.it.teachers.index') }}" class="block text-slate-700 hover:text-[#3b4197]">Teachers</a>
          <a href="{{ route('admin.it.scheduling.index') }}" class="block text-slate-700 hover:text-[#3b4197]">Scheduling Settings</a>
          <a href="{{ route('admin.it.scheduler.run') }}" class="block text-slate-700 hover:text-[#3b4197]">Scheduling</a>
          <a href="{{ route('admin.it.exports') }}" class="block text-slate-700 hover:text-[#3b4197]">Exports & Analytics</a>
          <a href="{{ route('admin.it.logs') }}" class="block text-slate-700 hover:text-[#3b4197]">Run History</a>
        </nav>
        <div class="mt-6">
          <form method="POST" action="{{ route('logout') }}">@csrf<button class="text-base text-slate-600">Logout</button></form>
        </div>
      </aside>

      <main class="flex-1 p-8">
        <h1 class="text-2xl font-semibold mb-4">@yield('heading', 'Overview')</h1>
        <div>
          @yield('content')
        </div>
      </main>
    </div>
    @yield('scripts')
  </body>
</html>
