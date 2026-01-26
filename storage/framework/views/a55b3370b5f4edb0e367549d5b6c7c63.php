<?php $__env->startSection('title','Dashboard'); ?>
<?php $__env->startSection('heading','Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="grid grid-cols-1 sm:grid-cols-3 gap-8 mb-8">
  <?php if (isset($component)) { $__componentOriginal6787d12cf91691b0002d6d0db371a00e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6787d12cf91691b0002d6d0db371a00e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.metric-card','data' => ['title' => 'Faculty Management','value' => $teachersCount ?? 0,'description' => 'Total registered faculty']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.metric-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Faculty Management','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($teachersCount ?? 0),'description' => 'Total registered faculty']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6787d12cf91691b0002d6d0db371a00e)): ?>
<?php $attributes = $__attributesOriginal6787d12cf91691b0002d6d0db371a00e; ?>
<?php unset($__attributesOriginal6787d12cf91691b0002d6d0db371a00e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6787d12cf91691b0002d6d0db371a00e)): ?>
<?php $component = $__componentOriginal6787d12cf91691b0002d6d0db371a00e; ?>
<?php unset($__componentOriginal6787d12cf91691b0002d6d0db371a00e); ?>
<?php endif; ?>
  <?php if (isset($component)) { $__componentOriginal6787d12cf91691b0002d6d0db371a00e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6787d12cf91691b0002d6d0db371a00e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.metric-card','data' => ['title' => 'Sections','value' => $sectionsCount ?? 0,'description' => 'Total sections']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.metric-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Sections','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($sectionsCount ?? 0),'description' => 'Total sections']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6787d12cf91691b0002d6d0db371a00e)): ?>
<?php $attributes = $__attributesOriginal6787d12cf91691b0002d6d0db371a00e; ?>
<?php unset($__attributesOriginal6787d12cf91691b0002d6d0db371a00e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6787d12cf91691b0002d6d0db371a00e)): ?>
<?php $component = $__componentOriginal6787d12cf91691b0002d6d0db371a00e; ?>
<?php unset($__componentOriginal6787d12cf91691b0002d6d0db371a00e); ?>
<?php endif; ?>
  <?php if (isset($component)) { $__componentOriginal6787d12cf91691b0002d6d0db371a00e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6787d12cf91691b0002d6d0db371a00e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.admin.metric-card','data' => ['title' => 'Subjects','value' => $subjectsCount ?? 0,'description' => 'Total subjects']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('admin.metric-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => 'Subjects','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($subjectsCount ?? 0),'description' => 'Total subjects']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6787d12cf91691b0002d6d0db371a00e)): ?>
<?php $attributes = $__attributesOriginal6787d12cf91691b0002d6d0db371a00e; ?>
<?php unset($__attributesOriginal6787d12cf91691b0002d6d0db371a00e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6787d12cf91691b0002d6d0db371a00e)): ?>
<?php $component = $__componentOriginal6787d12cf91691b0002d6d0db371a00e; ?>
<?php unset($__componentOriginal6787d12cf91691b0002d6d0db371a00e); ?>
<?php endif; ?>
</div>

<!-- Workload Summary -->
<div class="mt-8 p-6 border rounded-xl bg-blue-50">
  <h3 class="font-bold text-[#3b4197] text-2xl mb-2">📊 Workload Summary</h3>
  <p class="mb-4 text-base text-blue-800">Total teaching hours per faculty; highlights overloaded staff.</p>
  <div class="bg-white p-4 rounded-xl border border-blue-200">
    <?php if($workloadSummary && count($workloadSummary) > 0): ?>
      <div class="overflow-x-auto">
        <table class="w-full text-base">
          <thead class="bg-blue-100">
            <tr>
              <th class="text-left p-3 font-semibold">Faculty</th>
              <th class="text-center p-3 font-semibold">Hours/Week</th>
              <th class="text-center p-3 font-semibold">Max Load</th>
              <th class="text-center p-3 font-semibold">Status</th>
            </tr>
          </thead>
          <tbody id="workloadTableBody">
            <?php $__currentLoopData = array_slice($workloadSummary, 0, 10); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr class="border-b <?php echo e($item['overloaded'] ? 'bg-red-50' : ''); ?>">
              <td class="p-3"><?php echo e($item['name']); ?></td>
              <td class="text-center p-3"><?php echo e($item['hours']); ?></td>
              <td class="text-center p-3"><?php echo e($item['max_load'] ?? 'N/A'); ?></td>
              <td class="text-center p-3">
                <?php if($item['overloaded']): ?>
                  <span class="text-red-600 font-semibold">⚠️ Overloaded</span>
                <?php else: ?>
                  <span class="text-green-600 font-semibold">✓ Normal</span>
                <?php endif; ?>
              </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <tbody id="workloadTableHidden" class="hidden">
            <?php $__currentLoopData = array_slice($workloadSummary, 10); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr class="border-b <?php echo e($item['overloaded'] ? 'bg-red-50' : ''); ?>">
              <td class="p-3"><?php echo e($item['name']); ?></td>
              <td class="text-center p-3"><?php echo e($item['hours']); ?></td>
              <td class="text-center p-3"><?php echo e($item['max_load'] ?? 'N/A'); ?></td>
              <td class="text-center p-3">
                <?php if($item['overloaded']): ?>
                  <span class="text-red-600 font-semibold">⚠️ Overloaded</span>
                <?php else: ?>
                  <span class="text-green-600 font-semibold">✓ Normal</span>
                <?php endif; ?>
              </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
          </tbody>
        </table>
      </div>
      <?php if(count($workloadSummary) > 10): ?>
      <div class="mt-4">
        <button id="toggleWorkloadBtn" onclick="toggleWorkloadTable()" class="px-5 py-2 bg-blue-600 text-white text-base rounded hover:bg-blue-700">
          View All <?php echo e(count($workloadSummary)); ?> Faculty
        </button>
      </div>
      <script>
        function toggleWorkloadTable() {
          const hidden = document.getElementById('workloadTableHidden');
          const btn = document.getElementById('toggleWorkloadBtn');
          if (hidden.classList.contains('hidden')) {
            hidden.classList.remove('hidden');
            btn.textContent = 'Show Less';
          } else {
            hidden.classList.add('hidden');
            btn.textContent = 'View All <?php echo e(count($workloadSummary)); ?> Faculty';
          }
        }
      </script>
      <?php endif; ?>
    <?php else: ?>
      <p class="text-slate-600 text-base">No workload data available yet.</p>
    <?php endif; ?>
  </div>
</div>

<!-- Subject Load Balance -->
<div class="mt-8 p-6 border rounded-xl bg-green-50">
  <h3 class="font-bold text-green-900 text-2xl mb-2">📚 Subject Load Balance</h3>
  <p class="mb-4 text-base text-green-800">Distribution of subjects and faculty assignments across grade levels.</p>
  <?php if($subjectLoadBalance): ?>
    <!-- Junior High School -->
    <?php if($subjectLoadBalance['junior_high'] && count($subjectLoadBalance['junior_high']['subjects']) > 0): ?>
    <div class="mt-6">
      <h4 class="font-semibold text-green-800 border-b-2 border-green-400 pb-2 text-lg">🏫 Junior High School</h4>
      <div class="mt-4 overflow-x-auto bg-white rounded-xl border border-green-200">
        <table class="w-full text-base">
          <thead class="bg-green-100">
            <tr>
              <th class="text-left p-3 font-semibold">Subject</th>
              <?php $__currentLoopData = $subjectLoadBalance['junior_high']['grades']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $grade): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <th class="text-center p-3 font-semibold"><?php echo e($grade->name); ?></th>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tr>
          </thead>
          <tbody>
            <?php $__currentLoopData = $subjectLoadBalance['junior_high']['subjects']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr class="border-b hover:bg-green-50">
              <td class="p-3 font-medium text-slate-700"><?php echo e($row['subject']); ?></td>
              <?php $__currentLoopData = $subjectLoadBalance['junior_high']['grades']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $grade): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <td class="text-center p-3">
                  <span class="inline-block bg-green-100 text-green-900 px-4 py-2 rounded-full text-base font-semibold">
                    <?php echo e($row['counts'][$grade->name] ?? 0); ?>

                  </span>
                </td>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </tbody>
        </table>
      </div>
    </div>
    <?php endif; ?>
    <!-- Senior High School -->
    <?php if($subjectLoadBalance['senior_high'] && count($subjectLoadBalance['senior_high']['subjects']) > 0): ?>
    <div class="mt-6">
      <h4 class="font-semibold text-green-800 border-b-2 border-green-400 pb-2 text-lg">🎓 Senior High School</h4>
      <?php $__currentLoopData = $subjectLoadBalance['senior_high']['grades_by_year']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $year => $gradesInYear): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <div class="mt-4">
        <h5 class="font-semibold text-green-700 text-base bg-green-50 p-2 rounded">Grade <?php echo e($year); ?></h5>
        <div class="mt-2 overflow-x-auto bg-white rounded-xl border border-green-200">
          <table class="w-full text-base">
            <thead class="bg-green-100">
              <tr>
                <th class="text-left p-3 font-semibold">Subject</th>
                <?php $__currentLoopData = $gradesInYear; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $grade): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <th class="text-center p-3 font-semibold"><?php echo e($grade->name); ?></th>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </tr>
            </thead>
            <tbody>
              <?php $__currentLoopData = $subjectLoadBalance['senior_high']['subjects']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <tr class="border-b hover:bg-green-50">
                <td class="p-3 font-medium text-slate-700"><?php echo e($row['subject']); ?></td>
                <?php $__currentLoopData = $gradesInYear; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $grade): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <td class="text-center p-3">
                    <span class="inline-block bg-green-100 text-green-900 px-4 py-2 rounded-full text-base font-semibold">
                      <?php echo e($row['counts'][$grade->name] ?? 0); ?>

                    </span>
                  </td>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </tr>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
          </table>
        </div>
      </div>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <?php endif; ?>
    <?php if(!($subjectLoadBalance['junior_high'] && count($subjectLoadBalance['junior_high']['subjects']) > 0) && !($subjectLoadBalance['senior_high'] && count($subjectLoadBalance['senior_high']['subjects']) > 0)): ?>
    <div class="mt-4 bg-white p-4 rounded-xl border border-green-200">
      <p class="text-slate-600 text-base">No grade levels or subject data available yet.</p>
    </div>
    <?php endif; ?>
  <?php else: ?>
  <div class="mt-4 bg-white p-4 rounded-xl border border-green-200">
    <p class="text-slate-600 text-base">No grade levels or subject data available yet.</p>
  </div>
  <?php endif; ?>
</div>

<!-- Scheduling Status -->
<div class="mt-8 p-6 border rounded-xl bg-purple-50">
  <h3 class="font-bold text-purple-900 text-2xl mb-2">⏱️ Scheduling Status</h3>
  <p class="mb-4 text-base text-purple-800">Current state of the generated schedule.</p>
  <div class="bg-white p-4 rounded-xl border border-purple-200">
    <?php if($schedulingStatus['exists']): ?>
      <div class="space-y-4">
        <div class="flex items-center justify-between">
          <span class="text-base font-medium text-slate-700">Run:</span>
          <span class="text-base text-slate-900 font-semibold"><?php echo e($schedulingStatus['run_name']); ?></span>
        </div>
        <div class="flex items-center justify-between">
          <span class="text-base font-medium text-slate-700">Status:</span>
          <span class="inline-block px-4 py-2 rounded-full text-base font-semibold
            <?php echo e($schedulingStatus['status_badge'] === 'success' ? 'bg-green-100 text-green-900' :
              ($schedulingStatus['status_badge'] === 'warning' ? 'bg-yellow-100 text-yellow-900' :
              ($schedulingStatus['status_badge'] === 'info' ? 'bg-blue-100 text-blue-900' :
              'bg-slate-100 text-slate-900'))); ?>"
          >
            <?php echo e($schedulingStatus['status']); ?>

          </span>
        </div>
        <div class="flex items-center justify-between">
          <span class="text-base font-medium text-slate-700">Published:</span>
          <span class="text-base">
            <?php if($schedulingStatus['is_published']): ?>
              <span class="text-green-600 font-semibold">✓ Yes</span>
              <?php if($schedulingStatus['published_at']): ?>
                <span class="text-xs text-slate-600">(<?php echo e($schedulingStatus['published_at']->format('M d, Y H:i')); ?>)</span>
              <?php endif; ?>
            <?php else: ?>
              <span class="text-orange-600 font-semibold">✗ No (Draft)</span>
            <?php endif; ?>
          </span>
        </div>
        <div class="border-t border-slate-200 pt-4 mt-4">
          <div class="grid grid-cols-3 gap-4">
            <div class="bg-slate-50 p-4 rounded-xl text-center">
              <div class="text-xl font-bold text-purple-700"><?php echo e($schedulingStatus['total_entries']); ?></div>
              <div class="text-base text-slate-600">Slots Filled</div>
            </div>
            <div class="bg-slate-50 p-4 rounded-xl text-center">
              <div class="text-xl font-bold <?php echo e($schedulingStatus['conflict_free'] ? 'text-green-700' : 'text-red-700'); ?>">
                <?php echo e($schedulingStatus['conflicts']); ?>

              </div>
              <div class="text-base text-slate-600">Conflicts</div>
            </div>
            <div class="bg-slate-50 p-4 rounded-xl text-center">
              <div class="text-base font-semibold text-slate-700"><?php echo e($schedulingStatus['created_at']->format('M d, Y')); ?></div>
              <div class="text-base text-slate-600">Generated</div>
            </div>
          </div>
        </div>
        <div class="mt-4 flex gap-2">
          <a href="<?php echo e(route('admin.scheduler.show', $schedulingStatus['run_id'])); ?>" class="flex-1 px-4 py-3 bg-purple-600 text-white text-base rounded-xl hover:bg-purple-700 text-center font-medium">
            View Schedule
          </a>
        </div>
      </div>
    <?php else: ?>
      <div class="text-center py-6">
        <p class="text-slate-600 text-base mb-3">No schedule has been generated yet.</p>
        <a href="<?php echo e(route('admin.scheduler.index')); ?>" class="inline-block px-5 py-3 bg-purple-600 text-white text-base rounded-xl hover:bg-purple-700 font-medium">
          Generate Schedule
        </a>
      </div>
    <?php endif; ?>
  </div>
</div>

<!-- Conflict Report -->
<div class="mt-8 p-6 border rounded-xl bg-orange-50">
  <h3 class="font-bold text-orange-900 text-2xl mb-2">⚡ Conflict Report</h3>
  <p class="mb-4 text-base text-orange-800">Scheduling conflicts detected (same faculty in 2 places, overlapping schedules, etc.)</p>
  <div class="bg-white p-4 rounded-xl border border-orange-200">
    <?php if($conflictReport['exists']): ?>
      <?php if($conflictReport['conflict_free']): ?>
        <div class="text-center py-8">
          <div class="text-5xl mb-3">✅</div>
          <p class="text-green-700 font-semibold text-xl">No Conflicts Detected!</p>
          <p class="text-base text-slate-600 mt-1">The current schedule is conflict-free.</p>
        </div>
      <?php else: ?>
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl">
          <div class="flex items-center gap-3">
            <span class="text-2xl">⚠️</span>
            <div>
              <p class="font-semibold text-red-900 text-lg"><?php echo e($conflictReport['total_conflicts']); ?> Conflict<?php echo e($conflictReport['total_conflicts'] > 1 ? 's' : ''); ?> Found</p>
              <p class="text-base text-red-700">These conflicts need to be resolved before publishing the schedule.</p>
            </div>
          </div>
        </div>
        <?php if(count($conflictReport['conflicts']) > 0): ?>
        <div class="overflow-x-auto">
          <table class="w-full text-base">
            <thead class="bg-orange-100">
              <tr>
                <th class="text-left p-3 font-semibold">Faculty</th>
                <th class="text-left p-3 font-semibold">Subject</th>
                <th class="text-left p-3 font-semibold">Section</th>
                <th class="text-center p-3 font-semibold">Day/Period</th>
                <th class="text-left p-3 font-semibold">Conflict Type</th>
              </tr>
            </thead>
            <tbody>
              <?php $__currentLoopData = $conflictReport['conflicts']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $conflict): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <tr class="border-b hover:bg-orange-50">
                <td class="p-3 font-medium text-slate-700"><?php echo e($conflict['teacher']); ?></td>
                <td class="p-3 text-slate-700"><?php echo e($conflict['subject']); ?></td>
                <td class="p-3 text-slate-700"><?php echo e($conflict['section']); ?></td>
                <td class="text-center p-3 text-slate-600 text-base">
                  Day <?php echo e($conflict['day']); ?>, Period <?php echo e($conflict['period']); ?>

                </td>
                <td class="p-3 text-red-600 text-base"><?php echo e($conflict['conflict_type']); ?></td>
              </tr>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
          </table>
        </div>
        <?php if($conflictReport['has_more']): ?>
        <p class="text-base text-slate-600 mt-4 text-center">
          Showing 10 of <?php echo e($conflictReport['total_conflicts']); ?> conflicts.
          <a href="<?php echo e(route('admin.scheduler.show', $conflictReport['run_id'])); ?>" class="text-orange-700 hover:underline font-medium">View all in scheduler →</a>
        </p>
        <?php endif; ?>
        <?php endif; ?>
        <div class="mt-6">
          <a href="<?php echo e(route('admin.scheduler.show', $conflictReport['run_id'])); ?>" class="block text-center px-5 py-3 bg-orange-600 text-white text-base rounded-xl hover:bg-orange-700 font-medium">
            Resolve Conflicts in Scheduler
          </a>
        </div>
      <?php endif; ?>
    <?php else: ?>
      <p class="text-slate-600 text-base text-center py-6">No schedule available. Generate a schedule first to detect conflicts.</p>
    <?php endif; ?>
  </div>
</div>

<!-- Faculty Availability -->
<div class="mt-8 p-6 border rounded-xl bg-indigo-50">
  <h3 class="font-bold text-indigo-900 text-2xl mb-2">👥 Faculty Availability</h3>
  <p class="mb-4 text-base text-indigo-800">Quick status of faculty availability and scheduling restrictions.</p>
  <div class="bg-white p-4 rounded-xl border border-indigo-200">
    <div class="grid grid-cols-2 gap-6 mb-6">
      <div class="bg-green-50 p-4 rounded-xl border border-green-200 text-center">
        <div class="text-3xl font-bold text-green-700"><?php echo e($teacherAvailability['fully_available']); ?></div>
        <div class="text-base text-green-800 mt-2">Fully Available</div>
      </div>
      <div class="bg-yellow-50 p-4 rounded-xl border border-yellow-200 text-center">
        <div class="text-3xl font-bold text-yellow-700"><?php echo e($teacherAvailability['restricted']); ?></div>
        <div class="text-base text-yellow-800 mt-2">With Restrictions</div>
      </div>
    </div>
    <?php if(count($teacherAvailability['restricted_teachers']) > 0): ?>
    <div class="mt-6">
      <h4 class="font-semibold text-indigo-800 text-base mb-3">Faculty with Availability Restrictions:</h4>
      <div class="space-y-3">
        <?php $__currentLoopData = $teacherAvailability['restricted_teachers']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="flex items-center justify-between bg-slate-50 p-3 rounded-xl text-base">
          <span class="font-medium text-slate-700"><?php echo e($teacher['name']); ?></span>
          <span class="text-base text-yellow-700 bg-yellow-100 px-3 py-2 rounded"><?php echo e($teacher['restrictions']); ?></span>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </div>
      <?php if($teacherAvailability['has_more']): ?>
      <p class="text-base text-slate-600 mt-4 text-center">
        Showing 10 of <?php echo e($teacherAvailability['restricted']); ?> faculty with restrictions.
        <a href="<?php echo e(route('admin.teachers.index')); ?>" class="text-indigo-700 hover:underline font-medium">View all faculty →</a>
      </p>
      <?php endif; ?>
    </div>
    <?php else: ?>
    <p class="text-slate-600 text-base text-center py-3">All faculty are fully available with no restrictions.</p>
    <?php endif; ?>
    <div class="mt-6 pt-6 border-t border-indigo-100">
      <p class="text-base text-slate-600">
        <strong>Note:</strong> Faculty marked as "With Restrictions" have limited availability on certain days or periods. 
        Review their profiles before scheduling.
      </p>
    </div>
  </div>
</div>

<!-- Recent Activity -->
<div class="mt-8 p-6 border rounded-xl bg-yellow-50">
  <h3 class="font-bold text-yellow-900 text-2xl mb-2">📝 Recent Activity</h3>
  <p class="mb-4 text-base text-yellow-800">Last changes made across the system.</p>
  <div class="bg-white p-4 rounded-xl border border-yellow-200">
    <?php if($recentActivity && count($recentActivity) > 0): ?>
      <div class="space-y-4">
        <?php $__currentLoopData = $recentActivity; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="flex items-start gap-4 p-3 rounded-xl hover:bg-yellow-50 transition">
          <div class="text-2xl flex-shrink-0"><?php echo e($activity['icon']); ?></div>
          <div class="flex-1 min-w-0">
            <div class="flex items-start justify-between gap-3">
              <div>
                <p class="text-base font-semibold text-slate-800"><?php echo e($activity['title']); ?></p>
                <p class="text-base text-slate-600 truncate"><?php echo e($activity['description']); ?></p>
              </div>
              <span class="text-xs text-slate-500 whitespace-nowrap">
                <?php echo e($activity['timestamp']->diffForHumans()); ?>

              </span>
            </div>
            <?php if($activity['link']): ?>
            <a href="<?php echo e($activity['link']); ?>" class="text-base text-yellow-700 hover:underline mt-2 inline-block">
              View details →
            </a>
            <?php endif; ?>
          </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </div>
    <?php else: ?>
      <p class="text-slate-600 text-base text-center py-6">No recent activity found.</p>
    <?php endif; ?>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\TuroTugma\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>