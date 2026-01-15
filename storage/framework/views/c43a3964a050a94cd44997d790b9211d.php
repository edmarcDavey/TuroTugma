<?php
  $isEdit = isset($subject) && $subject;
  $action = $isEdit ? route('admin.subjects.update', $subject) : route('admin.subjects.store');
  $availGrades = old('grade_levels', $isEdit && isset($subject->gradeLevels) ? $subject->gradeLevels->pluck('id')->toArray() : []);
?>

<form method="POST" action="<?php echo e($action); ?>">
  <?php echo csrf_field(); ?>
  <?php if($isEdit): ?> <?php echo method_field('PUT'); ?> <?php endif; ?>

  <div class="grid grid-cols-2 gap-4">
    <div>
      <label class="block text-sm font-medium">Code</label>
      <input name="code" value="<?php echo e(old('code', $subject->code ?? '')); ?>" class="mt-1 block w-full border p-2" />
    </div>
    <div>
      <label class="block text-sm font-medium">Type / Category</label>
      <select id="subject-type-frag" name="type" class="mt-1 block w-full border p-2">
        <?php
          $types = ['core'=>'Core','special'=>'Special','abm'=>'ABM','humss'=>'HUMSS','stem'=>'STEM','tvl'=>'TVL','gas'=>'GAS','jhs_core'=>'JHS Core','tle'=>'TLE/TVL','spa'=>'Special Program in the Arts','journalism'=>'Journalism','shs_core'=>'SHS Core','shs_applied'=>'SHS Applied','shs_strand'=>'SHS Strand'];
        ?>
        <option value="">-- select --</option>
        <?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <option value="<?php echo e($k); ?>" <?php if(old('type', $subject->type ?? '') == $k): ?> selected <?php endif; ?>><?php echo e($v); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </select>
    </div>

    <div class="col-span-2">
      <label class="block text-sm font-medium">Name</label>
      <input name="name" required value="<?php echo e(old('name', $subject->name ?? '')); ?>" class="mt-1 block w-full border p-2" />
    </div>

    <div class="col-span-2">
      <label class="block text-sm font-medium">Applicable Grade Levels</label>
      <div class="mt-1 grid grid-cols-3 gap-2 p-2 border rounded bg-white">
        <?php $__currentLoopData = $gradeLevels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $g): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <label class="inline-flex items-center mr-2">
            <input type="checkbox" name="grade_levels[]" value="<?php echo e($g->id); ?>" data-year="<?php echo e($g->year); ?>" class="mr-2" <?php if(in_array($g->id, $availGrades)): ?> checked <?php endif; ?> />
            <span class="text-sm"><?php echo e($g->name); ?></span>
          </label>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </div>
    </div>

    <div class="col-span-2">
      <label class="block text-sm font-medium">Description / Notes</label>
      <textarea name="description" class="mt-1 block w-full border p-2" rows="3"><?php echo e(old('description', $subject->description ?? '')); ?></textarea>
    </div>
  </div>

  <div class="mt-4">
    <button type="submit" class="px-4 py-2 bg-[#3b4197] text-white rounded"><?php echo e($isEdit ? 'Save' : 'Create'); ?></button>
    <a href="<?php echo e(route('admin.subjects.index')); ?>" class="ml-2 text-sm">Cancel</a>
  </div>
</form>

<script>
  (function(){
    const form = document.currentScript ? document.currentScript.parentElement.querySelector('form') : document.querySelector('form');
    if(!form) return;
    const select = form.querySelector('#subject-type-frag');
    const gradeCbs = Array.from(form.querySelectorAll('input[name="grade_levels[]"]'));
    const submitBtn = form.querySelector('button[type="submit"]');

    function hasJunior(){ return gradeCbs.some(cb=>{ const y = cb.getAttribute('data-year'); return y && Number(y) >=7 && Number(y) <=10 && cb.checked; }); }
    function hasSenior(){ return gradeCbs.some(cb=>{ const y = cb.getAttribute('data-year'); return y && Number(y) >=11 && Number(y) <=12 && cb.checked; }); }

    function update(){
      if(!select) return;
      const junior = hasJunior(); const senior = hasSenior();
      Array.from(select.options).forEach(opt=>{
        const v = (opt.value||'').toLowerCase();
        if(v === 'core') opt.disabled = false;
        else if(v === 'special') opt.disabled = !junior;
        else if(['abm','humss','stem','tvl','gas'].indexOf(v) !== -1) opt.disabled = !senior;
        else opt.disabled = false;
      });
      const cur = (select.value||'').toLowerCase();
      const curOpt = Array.from(select.options).find(o=> (o.value||'').toLowerCase()===cur );
      if(curOpt && curOpt.disabled){ select.value = 'core'; }
      if(submitBtn) submitBtn.disabled = !gradeCbs.some(cb=>cb.checked);
    }

    gradeCbs.forEach(cb=>cb.addEventListener('change', update));
    update();
  })();
</script>
<?php
  $isEdit = isset($subject) && $subject;
  $action = $isEdit ? route('admin.subjects.update', $subject) : route('admin.subjects.store');
  $availGrades = old('grade_levels', $isEdit && isset($subject->gradeLevels) ? $subject->gradeLevels->pluck('id')->toArray() : []);
?>

<form method="POST" action="<?php echo e($action); ?>">
  <?php echo csrf_field(); ?>
  <?php if($isEdit): ?> <?php echo method_field('PUT'); ?> <?php endif; ?>

  <div class="grid grid-cols-2 gap-4">
    <div>
      <label class="block text-sm font-medium">Code</label>
      <input name="code" value="<?php echo e(old('code', $subject->code ?? '')); ?>" class="mt-1 block w-full border p-2" />
    </div>
    <div>
      <label class="block text-sm font-medium">Type / Category</label>
      <select id="subject-type-frag" name="type" class="mt-1 block w-full border p-2">
        <?php
          $types = ['core'=>'Core','special'=>'Special','abm'=>'ABM','humss'=>'HUMSS','stem'=>'STEM','tvl'=>'TVL','gas'=>'GAS','jhs_core'=>'JHS Core','tle'=>'TLE/TVL','spa'=>'Special Program in the Arts','journalism'=>'Journalism','shs_core'=>'SHS Core','shs_applied'=>'SHS Applied','shs_strand'=>'SHS Strand'];
        ?>
        <option value="">-- select --</option>
        <?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k=>$v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <option value="<?php echo e($k); ?>" <?php if(old('type', $subject->type ?? '') == $k): ?> selected <?php endif; ?>><?php echo e($v); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </select>
    </div>

    <div class="col-span-2">
      <label class="block text-sm font-medium">Name</label>
      <input name="name" required value="<?php echo e(old('name', $subject->name ?? '')); ?>" class="mt-1 block w-full border p-2" />
    </div>

    <div class="col-span-2">
      <label class="block text-sm font-medium">Applicable Grade Levels</label>
      <div class="mt-1 grid grid-cols-3 gap-2 p-2 border rounded bg-white">
        <?php $__currentLoopData = $gradeLevels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $g): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <label class="inline-flex items-center mr-2">
            <input type="checkbox" name="grade_levels[]" value="<?php echo e($g->id); ?>" data-year="<?php echo e($g->year); ?>" class="mr-2" <?php if(in_array($g->id, $availGrades)): ?> checked <?php endif; ?> />
            <span class="text-sm"><?php echo e($g->name); ?></span>
          </label>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </div>
    </div>

    <div class="col-span-2">
      <label class="block text-sm font-medium">Description / Notes</label>
      <textarea name="description" class="mt-1 block w-full border p-2" rows="3"><?php echo e(old('description', $subject->description ?? '')); ?></textarea>
    </div>
  </div>

  <div class="mt-4">
    <button type="submit" class="px-4 py-2 bg-[#3b4197] text-white rounded"><?php echo e($isEdit ? 'Save' : 'Create'); ?></button>
    <a href="<?php echo e(route('admin.subjects.index')); ?>" class="ml-2 text-sm">Cancel</a>
  </div>
</form>
<?php /**PATH C:\xampp\htdocs\TuroTugma\resources\views\admin\subjects\_form.blade.php ENDPATH**/ ?>