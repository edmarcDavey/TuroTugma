<nav class="relative z-20 bg-white border-b">
	<div class="max-w-7xl mx-auto px-8">
		<div class="flex items-center h-14">
			<div class="flex-1">
				<a href="<?php echo e(url('/')); ?>" class="text-xl font-bold text-[#3b4197]">TuroTugma</a>
			</div>

			<div class="flex items-center gap-4 text-base lg:text-lg">
				<a href="<?php echo e(url('/features')); ?>" class="text-gray-700 hover:text-[#3b4197] font-semibold">Features</a>
				<a href="<?php echo e(url('/about')); ?>" class="text-gray-700 hover:text-[#3b4197] font-semibold">About Us</a>
				<?php if(auth()->guard()->check()): ?>
					<?php if(auth()->user()->role === 'it_coordinator'): ?>
						<a href="<?php echo e(route('admin.dashboard')); ?>" class="text-gray-700 hover:text-[#3b4197] font-semibold">Admin</a>
					<?php endif; ?>
				<?php endif; ?>
			</div>
		</div>
	</div>
</nav>

<?php /**PATH C:\xampp\htdocs\TuroTugma\resources\views\layouts\navigation.blade.php ENDPATH**/ ?>