

<?php $__env->startSection('title','Grade Levels & Sections'); ?>
<?php $__env->startSection('heading','Grade Levels & Sections'); ?>

<?php $__env->startSection('content'); ?>
  <div class="mb-6 p-4 bg-amber-50 border-l-4 border-amber-500 text-amber-900">
    <p class="font-semibold">⏳ Senior High Coming Soon</p>
    <p class="text-sm mt-1">Senior High (Grade 11-12) section management will be available in the future. Currently, only Junior High (Grade 7-10) sections can be managed.</p>
  </div>

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
        <?php $__currentLoopData = $gradeLevels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $g): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <?php if(in_array($g->name, ['Grade 7', 'Grade 8', 'Grade 9', 'Grade 10'])): ?>
            <tr>
              <td class="py-2 px-3"><?php echo e($g->name); ?></td>
              <td class="py-2 px-3"><?php echo e($g->section_naming ?? ''); ?></td>
              <td class="py-2 px-3"><?php echo e($g->sections ? $g->sections->count() : ($g->sections_count ?? 0)); ?></td>
            </tr>
          <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </tbody>
    </table>
  </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\TuroTugma\resources\views\admin\sections\index.blade.php ENDPATH**/ ?>