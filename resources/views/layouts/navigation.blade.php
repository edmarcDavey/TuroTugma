<nav class="relative z-20 bg-white border-b">
	<div class="max-w-7xl mx-auto px-8">
		<div class="flex items-center h-14">
			<div class="flex-1">
				<a href="{{ url('/') }}" class="text-xl font-bold text-[#3b4197]">TuroTugma</a>
			</div>

			<div class="flex items-center gap-4 text-base lg:text-lg">
				<a href="{{ url('/features') }}" class="text-gray-700 hover:text-[#3b4197] font-semibold">Features</a>
				<a href="{{ url('/about') }}" class="text-gray-700 hover:text-[#3b4197] font-semibold">About Us</a>
				@auth
					@if(auth()->user()->role === 'it_coordinator')
						<a href="{{ route('admin.it.dashboard') }}" class="text-gray-700 hover:text-[#3b4197] font-semibold">IT Coordinator</a>
					@endif
				@endauth
			</div>
		</div>
	</div>
</nav>

