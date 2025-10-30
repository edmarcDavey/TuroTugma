<x-app-layout>
    <div class="bg-white">
        <div class="max-w-7xl mx-auto px-6 py-16" style="font-family: 'Poppins', ui-sans-serif, system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial;">
            <!-- Hero -->
            <section class="mb-12">
                <h1 class="text-4xl lg:text-5xl font-extrabold text-[#3b4197]">About Us</h1>
                <p class="mt-4 text-lg text-gray-700">TuroTugma automates schedule generation to reduce human error, save administrative time, and promote efficient teaching load management through intelligent automation and clear analytics.</p>
            </section>

            <!-- Who we are (single-column) -->
            <section class="mb-12">
                <div class="prose max-w-none">
                    <h2 class="text-2xl font-semibold text-slate-800 mb-3">Who we are</h2>
                    <p class="text-gray-700 mb-4">We are a small team of engineers and education specialists building tools to simplify timetable creation and teacher workload management. Our focus is on creating practical, easy-to-use interfaces and reliable automation to reduce administrative burden in schools.</p>
                    <p class="text-gray-700">We design TuroTugma to be extensible and transparent so school administrators can review and adjust generated schedules with confidence.</p>
                </div>
            </section>

            <!-- Vision & Mission -->
            <section class="mb-12">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-white border rounded-lg p-6 shadow-sm">
                        <h3 class="text-xl font-semibold text-[#3b4197]">Vision</h3>
                        <p class="text-gray-700 mt-3">To reduce human error, save administrative time, and promote efficient teaching load management through AI-driven automation and data transparency.</p>
                    </div>

                    <div class="bg-white border rounded-lg p-6 shadow-sm">
                        <h3 class="text-xl font-semibold text-[#3b4197]">Mission</h3>
                        <p class="text-gray-700 mt-3">To streamline timetable creation and make teacher workload management fairer, faster, and transparent through intelligent automation and clear analytics.</p>
                    </div>
                </div>
            </section>

            <!-- Partnership -->
            <section class="mb-6">
                <h3 class="text-lg font-semibold mb-4">In partnership with</h3>
                <div class="flex items-center gap-6">
                    @if (file_exists(public_path('dnhs_logo.jpg')))
                        <img src="{{ asset('dnhs_logo.jpg') }}" alt="DNHS" class="h-16 object-contain">
                    @else
                        <div class="h-16 w-40 bg-gray-100 rounded flex items-center justify-center text-sm text-gray-500">DNHS logo</div>
                    @endif

                    <p class="text-gray-700">We collaborate with the Diadi National High School (DNHS) to pilot scheduling workflows and gather feedback from real-world users to improve fairness and efficiency.</p>
                </div>
            </section>
        </div>
    </div>
</x-app-layout>