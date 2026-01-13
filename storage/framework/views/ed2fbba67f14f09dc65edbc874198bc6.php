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
	
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

	<div class="min-h-screen flex flex-col bg-white text-gray-900" style="font-family: 'Poppins', ui-sans-serif, system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial;">
		

		
		<main class="flex-1 flex items-center">
			<div class="max-w-7xl mx-auto px-8 py-8">
				<div class="flex items-center gap-12">
				
				<div class="flex-1">
					<h1 class="text-4xl lg:text-5xl font-extrabold text-[#3b4197] leading-tight mb-6 normal-case">Automated Scheduling System for Teaching Load Management</h1>
					<p class="text-lg text-gray-700 mb-8 max-w-2xl">TuroTugma automates timetable generation and helps administrators reduce manual errors, save time, and make teacher workload management transparent and fair through intelligent optimization and clear analytics.</p>

					<a href="<?php echo e(url('/dashboard')); ?>" class="inline-block px-8 py-3 rounded-md text-white font-semibold" style="background-color:#3b4197; box-shadow: 0 10px 30px rgba(59,65,151,0.14);">Let's Get Started</a>
				</div>

				
				<div class="w-80 flex-shrink-0 hidden lg:flex flex-col items-center justify-center">
					
					<div class="text-sm text-gray-500 text-center">&nbsp;</div>
				</div>
				</div>
			</div>
		</main>

		

		
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
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\TuroTugma\resources\views/landing.blade.php ENDPATH**/ ?>