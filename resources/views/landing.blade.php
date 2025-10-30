<x-app-layout>
	{{-- Poppins font (local landing include) --}}
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

	<div class="min-h-screen flex flex-col bg-white text-gray-900" style="font-family: 'Poppins', ui-sans-serif, system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial;">
		{{-- Top nav provided by global layout (layouts.navigation) --}}

		{{-- HERO (desktop-first two-column) --}}
		<main class="flex-1 flex items-center">
			<div class="max-w-7xl mx-auto px-8 py-8">
				<div class="flex items-center gap-12">
				{{-- Left: tagline + description + CTA --}}
				<div class="flex-1">
					<h1 class="text-4xl lg:text-5xl font-extrabold text-[#3b4197] leading-tight mb-6 normal-case">Automated Scheduling System for Teaching Load Management</h1>
					<p class="text-lg text-gray-700 mb-8 max-w-2xl">TuroTugma automates timetable generation and helps administrators reduce manual errors, save time, and make teacher workload management transparent and fair through intelligent optimization and clear analytics.</p>

					<a href="{{ url('/dashboard') }}" class="inline-block px-8 py-3 rounded-md text-white font-semibold" style="background-color:#3b4197; box-shadow: 0 10px 30px rgba(59,65,151,0.14);">Let's Get Started</a>
				</div>

				{{-- Right: decorative area (partner badge removed) --}}
				<div class="w-80 flex-shrink-0 hidden lg:flex flex-col items-center justify-center">
					{{-- partner badge removed per request --}}
					<div class="text-sm text-gray-500 text-center">&nbsp;</div>
				</div>
				</div>
			</div>
		</main>

		{{-- Footer moved to global app layout to keep pages consistent --}}

		{{-- Features/About are separate pages now (see /features and /about) --}}
	</div>
</x-app-layout>
