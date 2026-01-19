<?php $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
  <?php
    $t = $item['teacher'] ?? $item;
    $workload = $item['workload'] ?? ['current' => 0, 'max' => 24, 'percentage' => 0];
    $status = $item['status'] ?? ['status' => 'active', 'label' => 'Active', 'color' => 'green'];
    $issues = $item['validationIssues'] ?? [];
  ?>
  <li data-id="<?php echo e($t->id); ?>" class="p-3 border rounded cursor-pointer teacher-row bg-white hover:bg-slate-50 transition">
    <div class="flex items-start justify-between gap-2">
      <div class="flex-1">
        <div class="font-medium flex items-center gap-2">
          <?php echo e($t->name); ?>

          <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-semibold"
                style="background-color: rgba(<?php echo e($status['color'] === 'green' ? '34,197,94' : ($status['color'] === 'yellow' ? '234,179,8' : ($status['color'] === 'blue' ? '59,130,246' : ($status['color'] === 'red' ? '239,68,68' : '249,115,22')))); ?>, 0.15); color: <?php echo e($status['color'] === 'green' ? '#22c55e' : ($status['color'] === 'yellow' ? '#eab308' : ($status['color'] === 'blue' ? '#3b82f6' : ($status['color'] === 'red' ? '#ef4444' : '#f97316')))); ?>;">
            <?php echo e($status['label']); ?>

          </span>
        </div>
        <div class="text-xs text-slate-600"><?php echo e($t->staff_id); ?></div>
        
        <?php if(!empty($issues)): ?>
          <div class="mt-2 text-xs text-orange-600 space-y-1">
            <?php $__currentLoopData = $issues; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $issue): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <div class="flex items-center gap-1">
                <span>⚠️</span>
                <span><?php echo e($issue); ?></span>
              </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </div>
        <?php endif; ?>
        
        <div class="mt-2">
          <div class="flex justify-between text-xs text-slate-600 mb-1">
            <span>Workload</span>
            <span><?php echo e($workload['current']); ?>/<?php echo e($workload['max']); ?> hrs</span>
          </div>
          <div class="w-full bg-slate-200 rounded-full h-2 overflow-hidden">
            <?php
              $barColor = $workload['percentage'] <= 70 ? '#10b981' : ($workload['percentage'] <= 90 ? '#f59e0b' : '#ef4444');
            ?>
            <div class="h-full transition-all" style="width: <?php echo e(min($workload['percentage'], 100)); ?>%; background-color: <?php echo e($barColor); ?>;"></div>
          </div>
        </div>
      </div>
    </div>
  </li>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php /**PATH C:\Users\Admin\TuroTugma\resources\views\admin\teachers\_list.blade.php ENDPATH**/ ?>