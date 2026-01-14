<div <?php echo e($attributes->merge(['class' => 'p-4 border rounded bg-white shadow-sm'])); ?>>
  <div class="text-sm text-slate-500"><?php echo e($title); ?></div>
  <div class="mt-2 text-2xl font-semibold"><?php echo e($value); ?></div>
  <?php if(! empty($description)): ?>
    <div class="mt-1 text-xs text-slate-400"><?php echo e($description); ?></div>
  <?php endif; ?>
</div>
<?php /**PATH C:\xampp\htdocs\TuroTugma\resources\views/components/admin/metric-card.blade.php ENDPATH**/ ?>