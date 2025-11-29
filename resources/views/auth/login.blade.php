<x-guest-layout>
    <!-- TuroTugma Login Card -->
    <div class="max-w-md mx-auto">
        <h1 class="text-2xl font-bold text-center mb-6">TuroTugma</h1>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <div class="bg-white shadow-md rounded-lg p-6">
            <!-- Admin login: only provide ID and Password -->

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- ID (single admin identifier) -->
                <div>
                    <x-input-label for="id" :value="__('ID')" />
                    <x-text-input id="id" class="block mt-1 w-full" type="text" name="id" :value="old('id')" required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('id')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="mt-4">
                    <x-input-label for="password" :value="__('Password')" />

                    <x-text-input id="password" class="block mt-1 w-full"
                                    type="password"
                                    name="password"
                                    required autocomplete="current-password" />

                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Remember Me -->
                <div class="block mt-4">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                        <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                    </label>
                </div>

                <div class="flex items-center justify-end mt-4">
                    <x-primary-button class="ms-3">
                        {{ __('Log in') }}
                    </x-primary-button>
                </div>
            </form>
        </div>

        <p class="text-center text-sm text-gray-600 mt-4">Not a TuroTugma Admin? <a href="{{ url('/') }}" class="underline">Return to Public Dashboard</a></p>
    </div>
    <script>
        // Small usability: focus the ID field on load
        (function(){
            var idInput = document.getElementById('id');
            if(idInput) idInput.focus();
        })();
    </script>
</x-guest-layout>
