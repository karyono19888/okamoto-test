<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <!-- Demo Credentials Info -->
    <div class="mb-4 p-4 bg-blue-50 border-l-4 border-blue-500 text-blue-700">
        <p class="text-sm font-bold">🔧 Mode Uji Coba (Testing Mode)</p>
        <p class="text-xs mt-1">Silahkan klik <strong>Log In</strong> langsung. Kredensial sudah otomatis terisi:</p>
        <ul class="text-xs list-disc list-inside mt-1">
            <li>Email: <span class="font-mono">admin@okamoto.com</span></li>
            <li>Password: <span class="font-mono">password</span></li>
        </ul>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email') ?: 'admin@okamoto.com'" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4" x-data="{ show: false }">
            <x-input-label for="password" :value="__('Password')" />

            <div class="relative">
                <x-text-input id="password" class="block mt-1 w-full pr-10"
                                type="password"
                                x-bind:type="show ? 'text' : 'password'"
                                name="password"
                                value="password"
                                required autocomplete="current-password" />

                <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center" @click="show = !show">
                    <!-- Eye Open Icon (shows when password hidden) -->
                    <svg x-show="!show" class="h-5 w-5 text-gray-400 hover:text-gray-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <!-- Eye Slashed Icon (shows when password visible) -->
                    <svg x-show="show" style="display: none;" class="h-5 w-5 text-gray-400 hover:text-gray-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                    </svg>
                </button>
            </div>

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
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
