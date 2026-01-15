<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TuroTugma — Dashboard (Placeholder)</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <style>
      :root{--primary:#3b4197}
      .logo-font{font-family:'Poppins', system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial}
    </style>
  </head>
  <body class="bg-white text-slate-800 min-h-screen">
    <div class="max-w-7xl mx-auto px-6 py-8">
      <header class="flex items-center justify-between">
        <div class="flex items-center gap-4">
          <div class="w-10 h-10 rounded-md flex items-center justify-center text-white" style="background:var(--primary)">
            <span class="logo-font font-semibold">TT</span>
          </div>
          <div>
            <div class="logo-font text-xl font-semibold">TuroTugma</div>
            <div class="text-sm text-slate-500">Dashboard (placeholder)</div>
          </div>
        </div>
        <a href="/" class="text-sm text-slate-600 hover:primary-text">Back to Landing</a>
      </header>

      <main class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="p-6 border rounded-lg">
          <h2 class="font-semibold">School Room Assignment View</h2>
          <p class="mt-2 text-sm text-slate-600">This scaffold shows how teacher-room assignments will be displayed. Replace with live data when backend is ready.</p>

          <div class="mt-4 space-y-3">
            <div class="p-3 bg-slate-50 rounded">
              <div class="flex justify-between text-sm">
                <div><strong>Mr. Reyes</strong> — Math</div>
                <div class="text-slate-400">Room 203</div>
              </div>
            </div>
            <div class="p-3 bg-slate-50 rounded">
              <div class="flex justify-between text-sm">
                <div><strong>Ms. Cruz</strong> — Science</div>
                <div class="text-slate-400">Room 110</div>
              </div>
            </div>
            <div class="p-3 bg-slate-50 rounded">
              <div class="flex justify-between text-sm">
                <div><strong>Mrs. Santos</strong> — English</div>
                <div class="text-slate-400">Room 101</div>
              </div>
            </div>
          </div>
        </div>

        <div class="p-6 border rounded-lg">
          <h2 class="font-semibold">Final Timetable (School Year)</h2>
          <p class="mt-2 text-sm text-slate-600">A simplified timetable preview. This will later show full weekly grid and printable export.</p>

          <div class="mt-4 overflow-auto">
            <table class="w-full text-sm border-collapse">
              <thead>
                <tr class="text-left text-slate-500">
                  <th class="p-2">Time</th>
                  <th class="p-2">Mon</th>
                  <th class="p-2">Tue</th>
                  <th class="p-2">Wed</th>
                </tr>
              </thead>
              <tbody>
                <tr class="border-t">
                  <td class="p-2">8:00 - 9:00</td>
                  <td class="p-2">Math — 2A</td>
                  <td class="p-2">English — 3B</td>
                  <td class="p-2">Science — 4C</td>
                </tr>
                <tr class="border-t">
                  <td class="p-2">9:00 - 10:00</td>
                  <td class="p-2">Filipino — 1A</td>
                  <td class="p-2">Math — 2B</td>
                  <td class="p-2">English — 3A</td>
                </tr>
              </tbody>
            </table>
          </div>

          <div class="mt-6 flex gap-3">
            <button class="px-4 py-2 bg-[#3b4197] text-white rounded">Export PDF (placeholder)</button>
            <button class="px-4 py-2 border rounded">Open full timetable</button>
          </div>
        </div>
      </main>
    </div>
  </body>
</html>
<?php /**PATH C:\xampp\htdocs\TuroTugma\resources\views\dashboard.blade.php ENDPATH**/ ?>