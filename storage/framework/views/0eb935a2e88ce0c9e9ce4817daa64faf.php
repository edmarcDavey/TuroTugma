<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <!-- Full-bleed blue background with decorative circles -->
    <div class="relative overflow-hidden min-h-screen flex flex-col" style="font-family: 'Poppins', ui-sans-serif, system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial;">
        <div class="absolute inset-0 z-0" aria-hidden="true">
            <!-- Background color -->
            <div class="h-full w-full" style="background: linear-gradient(180deg, #3b4197 0%, #2f3270 100%);"></div>
            <!-- decorative large circles -->
            <svg class="pointer-events-none absolute inset-0 w-full h-full" preserveAspectRatio="xMidYMid slice" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <radialGradient id="g1" cx="30%" cy="10%">
                        <stop offset="0%" stop-color="#4650a8" stop-opacity="0.35" />
                        <stop offset="100%" stop-color="#4650a8" stop-opacity="0" />
                    </radialGradient>
                    <radialGradient id="g2" cx="90%" cy="80%">
                        <stop offset="0%" stop-color="#6b6fcf" stop-opacity="0.18" />
                        <stop offset="100%" stop-color="#6b6fcf" stop-opacity="0" />
                    </radialGradient>
                </defs>
                <rect width="100%" height="100%" fill="none"></rect>
                <circle cx="18%" cy="12%" r="400" fill="url(#g1)" />
                <circle cx="88%" cy="82%" r="480" fill="url(#g2)" />
            </svg>
        </div>

    <div class="relative max-w-7xl mx-auto px-8 py-20 z-10">
            <header class="mb-8">
                <h1 class="text-4xl lg:text-5xl font-extrabold text-white mb-4">Key Features</h1>
                <p class="text-white/90">TuroTugma provides a compact, powerful toolset to automate timetable generation and simplify teacher workload management. Overview below.</p>
            </header>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="font-semibold text-slate-800">Teacher Profiling</h3>
                    <p class="text-sm text-gray-600 mt-2">Add teachers, assign subjects and grade levels, and set availability/non-teaching periods.</p>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="font-semibold text-slate-800">Data Setup</h3>
                    <p class="text-sm text-gray-600 mt-2">Configure grades, sections, subjects, and rooms quickly.</p>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="font-semibold text-slate-800">Scheduling Logic</h3>
                    <p class="text-sm text-gray-600 mt-2">Support for regular and shortened periods, breaks, and lunch.</p>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="font-semibold text-slate-800">Automated Generation</h3>
                    <p class="text-sm text-gray-600 mt-2">AI optimization assigns teachers while minimizing conflicts.</p>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="font-semibold text-slate-800">Manual Editing</h3>
                    <p class="text-sm text-gray-600 mt-2">Schedulers can adjust timetables and resolve highlighted conflicts.</p>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="font-semibold text-slate-800">Exports & Dashboard</h3>
                    <p class="text-sm text-gray-600 mt-2">Export timetables (PDF/Excel/CSV) and view analytics for workload and room utilization.</p>
                </div>
            </div>
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH C:\Users\Admin\TuroTugma\resources\views\features.blade.php ENDPATH**/ ?>