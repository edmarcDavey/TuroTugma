<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
  </head>
  <body class="bg-white text-slate-800 min-h-screen">
    <div class="max-w-4xl mx-auto px-6 py-8">
      <header class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold">Admin Dashboard</h1>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-sm text-slate-600">Logout</button>
        </form>
      </header>

      <main class="mt-8">
        <div class="p-6 border rounded-lg">
          <h2 class="font-semibold">Welcome, {{ auth()->user()->name }}</h2>
          <p class="mt-2 text-sm text-slate-600">You are signed in as the Admin (ID: {{ auth()->user()->email }}).</p>

          <div class="mt-4 p-4 bg-slate-50 rounded">
            <strong>Placeholder:</strong>
            <p class="mt-2 text-sm">This is the IT Coordinator dashboard. Replace with live controls and reports.</p>
          </div>
        </div>
      </main>
    </div>
  </body>
</html>
