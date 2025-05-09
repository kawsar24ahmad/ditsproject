@section('title', 'Login')
<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
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
    <!-- <div class="flex items-center justify-center mt-4">
            <div class="row mb-0">
                <div class="col-md-6  offset-md-4 mt-2">
                        <x-nav-link href="{{ route('auth.facebook') }}"  class="bg-blue-600 text-white rounded">
                        <svg  class="me-2  xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 32 32">
                            <path fill="#FFFFFF" d="M32 16.084c0-8.837-7.162-16-16-16S0 7.247 0 16.084c0 8.013 5.873 14.644 13.568 15.843v-11.22h-4.087v-4.623h4.087v-3.489c0-4.041 2.423-6.279 6.118-6.279 1.775 0 3.628.312 3.628.312v3.985h-2.046c-2.017 0-2.637 1.255-2.637 2.539v2.931h4.467l-.713 4.623h-3.754v11.22C26.127 30.728 32 24.097 32 16.084z"/>
                        </svg>
                        Login with Facebook
                        </x-nav-link>

                </div>
            </div>
        </div> -->
</x-guest-layout>
