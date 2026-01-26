<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin - <?php echo $__env->yieldContent('title', 'Dashboard'); ?></title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  </head>
  <body class="bg-white text-slate-800 min-h-screen">
    <div class="flex">
      <aside class="w-56 px-0 pt-8 pb-6 border-r bg-slate-50 min-h-screen flex flex-col items-center">
        <!-- Brand Text Only -->
        <div class="mb-8">
          <a href="<?php echo e(route('admin.dashboard')); ?>" class="block">
            <span class="text-2xl font-bold text-[#3b4197] tracking-wide">TuroTugma</span>
          </a>
        </div>
        <!-- Single Navigation -->
        <nav class="w-full flex-1">
          <ul class="space-y-4 px-6">
            <li><a href="<?php echo e(route('admin.dashboard')); ?>" class="flex items-center gap-3 py-2 px-3 rounded text-base font-semibold text-[#3b4197] hover:bg-blue-50"><span class="material-icons">dashboard</span><span>Dashboard</span></a></li>
            <li><a href="<?php echo e(route('admin.subjects-sections')); ?>" class="flex items-center gap-3 py-2 px-3 rounded text-base font-semibold text-[#3b4197] hover:bg-blue-50"><span class="material-icons">view_list</span><span>Sections & Subjects</span></a></li>
            <li><a href="<?php echo e(route('admin.teachers.index')); ?>" class="flex items-center gap-3 py-2 px-3 rounded text-base font-semibold text-[#3b4197] hover:bg-blue-50"><span class="material-icons">people</span><span>Faculty Management</span></a></li>
            <li><a href="<?php echo e(url('/admin/schedule-maker/scheduler')); ?>" class="flex items-center gap-3 py-2 px-3 rounded text-base font-semibold text-[#3b4197] hover:bg-blue-50"><span class="material-icons">calendar_today</span><span>Schedule Maker</span></a></li>
            <li><a href="<?php echo e(url('/admin/schedule-maker/settings')); ?>" class="flex items-center gap-3 py-2 px-3 rounded text-base font-semibold text-[#3b4197] hover:bg-blue-50"><span class="material-icons">settings</span><span>Scheduling Configurations</span></a></li>
            <li><a href="<?php echo e(url('/admin/substitution-finder')); ?>" class="flex items-center gap-3 py-2 px-3 rounded text-base font-semibold text-[#3b4197] hover:bg-blue-50"><span class="material-icons">swap_horiz</span><span>Substitution Finder</span></a></li>
            <li><a href="<?php echo e(route('admin.exports')); ?>" class="flex items-center gap-3 py-2 px-3 rounded text-base font-semibold text-[#3b4197] hover:bg-blue-50"><span class="material-icons">bar_chart</span><span>Exports & Analytics</span></a></li>
            <li><a href="<?php echo e(route('admin.logs')); ?>" class="flex items-center gap-3 py-2 px-3 rounded text-base font-semibold text-[#3b4197] hover:bg-blue-50"><span class="material-icons">history</span><span>Run History</span></a></li>
          </ul>
        </nav>
        <div class="w-full px-6 mt-8">
          <form method="POST" action="<?php echo e(route('logout')); ?>"><?php echo csrf_field(); ?>
            <button class="w-full flex items-center gap-3 py-2 px-3 rounded text-base font-semibold text-slate-600 hover:bg-blue-50"><span class="material-icons">logout</span><span>Logout</span></button>
          </form>
        </div>
      </aside>
      <main class="flex-1 p-8">
        <h1 class="text-2xl font-semibold mb-4 text-[#3b4197]"><?php echo $__env->yieldContent('heading', 'Overview'); ?></h1>
        <div>
          <?php echo $__env->yieldContent('content'); ?>
        </div>
      </main>
    </div>
      <!-- Toast container and confirm modal available to all admin pages -->
      <div id="global-toasts" aria-live="polite" style="position:fixed; right:16px; bottom:16px; z-index:60; display:flex; flex-direction:column; gap:8px;"></div>

      <div id="global-confirm" class="hidden fixed inset-0 z-70 items-center justify-center">
        <div class="absolute inset-0 bg-black opacity-30"></div>
        <div class="relative bg-white rounded shadow-lg w-full max-w-md mx-4 p-4">
          <div id="global-confirm-message" class="mb-4 text-sm"></div>
          <div class="flex justify-end gap-2">
            <button id="global-confirm-cancel" class="px-3 py-1 border rounded">Cancel</button>
            <button id="global-confirm-ok" class="px-3 py-1 bg-[#3b4197] text-white rounded">OK</button>
          </div>
        </div>
      </div>

      <script>
        function showToast(message, type='info', timeout=3000){
          try{
            const container = document.getElementById('global-toasts');
            if(!container) return;
            const el = document.createElement('div');
            el.className = 'rounded px-3 py-2 text-sm shadow';
            el.style.minWidth = '200px';
            el.style.boxShadow = '0 6px 18px rgba(16,24,40,0.08)';
            if(type === 'error') el.style.background = '#fee2e2', el.style.color='#7f1d1d';
            else if(type === 'success') el.style.background = '#ecfdf5', el.style.color='#065f46';
            else el.style.background = '#eef2ff', el.style.color='#0f172a';
            el.textContent = message;
            container.appendChild(el);
            setTimeout(()=>{ el.style.opacity = '0'; setTimeout(()=>el.remove(), 300); }, timeout);
          }catch(e){ console.error('showToast error', e); }
        }

        function confirmDialog(message){
          return new Promise(resolve => {
            try{
              const modal = document.getElementById('global-confirm');
              const msg = document.getElementById('global-confirm-message');
              const ok = document.getElementById('global-confirm-ok');
              const cancel = document.getElementById('global-confirm-cancel');
              if(!modal || !msg || !ok || !cancel){ resolve(window.confirm(message)); return; }
              msg.textContent = message;
              modal.classList.remove('hidden'); modal.classList.add('flex');
              function cleanup(result){
                ok.removeEventListener('click', okHandler);
                cancel.removeEventListener('click', cancelHandler);
                modal.classList.remove('flex'); modal.classList.add('hidden');
                resolve(result);
              }
              function okHandler(){ cleanup(true); }
              function cancelHandler(){ cleanup(false); }
              ok.addEventListener('click', okHandler);
              cancel.addEventListener('click', cancelHandler);
            }catch(e){ console.error('confirmDialog error', e); resolve(window.confirm(message)); }
          });
        }
      </script>

      <?php echo $__env->yieldContent('scripts'); ?>
  </body>
</html>
<?php /**PATH C:\xampp\htdocs\TuroTugma\resources\views/admin/layout.blade.php ENDPATH**/ ?>