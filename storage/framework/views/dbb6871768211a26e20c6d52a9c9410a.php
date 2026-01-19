<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Scheduler Dashboard</title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
  </head>
  <body class="bg-white text-slate-800 min-h-screen">
    <div class="max-w-4xl mx-auto px-6 py-8">
      <header class="flex items-center justify-between">
        <h1 class="text-2xl font-semibold">Scheduler Dashboard</h1>
        <form method="POST" action="<?php echo e(route('logout')); ?>">
            <?php echo csrf_field(); ?>
            <button type="submit" class="text-sm text-slate-600">Logout</button>
        </form>
      </header>

      <main class="mt-8">
        <div class="p-6 border rounded-lg">
          <h2 class="font-semibold">Welcome, <?php echo e(auth()->user()->name); ?></h2>
          <p class="mt-2 text-sm text-slate-600">You are signed in as the Scheduler (ID: <?php echo e(auth()->user()->email); ?>).</p>

          <div class="mt-4 p-4 bg-slate-50 rounded">
            <strong>Placeholder:</strong>
            <p class="mt-2 text-sm">This is the Scheduler dashboard. Replace with role-specific tools.</p>
          </div>
        </div>
      </main>
    </div>
  </body>
</html>
<?php /**PATH C:\Users\Admin\TuroTugma\resources\views\dashboard_scheduler.blade.php ENDPATH**/ ?>