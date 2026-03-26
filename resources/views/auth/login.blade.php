<x-guest-layout>
    <div class="min-h-screen flex bg-gradient-to-br from-blue-900 via-blue-800 to-blue-950 relative overflow-hidden">
        <!-- Animated Background Pattern -->
        <div class="absolute inset-0 opacity-15">
            <div class="absolute top-0 left-0 w-96 h-96 bg-blue-400 rounded-full mix-blend-multiply filter blur-3xl animate-pulse"></div>
            <div class="absolute bottom-0 right-0 w-96 h-96 bg-yellow-400 rounded-full mix-blend-multiply filter blur-3xl animate-pulse" style="animation-delay: 2s;"></div>
        </div>

        <!-- Left Side - Branding -->
        <div class="hidden lg:flex w-1/2 items-center justify-center relative z-10 px-12">
            <div class="text-center">
                <!-- Logo -->
                <div class="mb-8 flex justify-center">
                    <img src="{{ asset('logo.png') }}" alt="LNU Logo" class="w-32 h-32 object-contain drop-shadow-lg">
                </div>

                <!-- System Title -->
                <div>
                    <h1 class="text-7xl font-black text-white mb-4 drop-shadow-lg leading-tight">SmartCEMES</h1>
                    <p class="text-yellow-400 font-bold text-sm mb-6">Leyte Normal University</p>
                    <p class="text-blue-100 text-lg font-medium leading-relaxed max-w-md mx-auto">Community Extension Services Monitoring & Evaluation System</p>
                </div>
            </div>
        </div>

        <!-- Right Side - Login Form -->
        <div class="w-full lg:w-1/2 flex flex-col items-center justify-center px-6 py-12 relative z-10">
            <div class="w-full max-w-md">
                <!-- Mobile Header -->
                <div class="lg:hidden mb-8 text-center">
                    <div class="mb-4 flex justify-center">
                        <img src="{{ asset('logo.png') }}" alt="LNU Logo" class="w-16 h-16 object-contain drop-shadow-lg">
                    </div>
                    <h1 class="text-4xl font-black text-white mb-2 drop-shadow-lg">SmartCEMES</h1>
                    <p class="text-yellow-400 font-bold text-xs">Leyte Normal University</p>
                </div>

                <!-- Login Card -->
                <div class="bg-white bg-opacity-98 backdrop-blur-lg rounded-3xl shadow-2xl p-10 border border-blue-100 w-full">
                    <!-- Session Status -->
                    <x-auth-session-status class="mb-6" :status="session('status')" />

                    <form method="POST" action="{{ route('login') }}" class="space-y-6">
                        @csrf

                        <!-- Email Address -->
                        <div>
                            <x-input-label for="email" :value="__('Email Address')" class="text-gray-800 font-bold text-sm" />
                            <x-text-input id="email" 
                                class="block mt-2 w-full px-5 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition duration-200 bg-gray-50 hover:bg-white text-gray-900" 
                                type="email" 
                                name="email" 
                                :value="old('email')" 
                                required 
                                autofocus 
                                autocomplete="username"
                                placeholder="cesoadmin@lnu.com" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <!-- Password -->
                        <div>
                            <x-input-label for="password" :value="__('Password')" class="text-gray-800 font-bold text-sm" />
                            <x-text-input id="password" 
                                class="block mt-2 w-full px-5 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:outline-none transition duration-200 bg-gray-50 hover:bg-white text-gray-900"
                                type="password"
                                name="password"
                                required 
                                autocomplete="current-password"
                                placeholder="••••••••" />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <!-- Remember Me -->
                        <div class="flex items-center justify-between">
                            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-blue-900 shadow-sm focus:ring-blue-900 w-4 h-4" name="remember">
                                <span class="ms-2 text-sm text-gray-700 font-medium">{{ __('Remember me') }}</span>
                            </label>
                            @if (Route::has('password.request'))
                                <a class="text-sm text-blue-900 hover:text-blue-700 font-semibold transition" href="{{ route('password.request') }}">
                                    {{ __('Forgot password?') }}
                                </a>
                            @endif
                        </div>

                        <!-- Login Button -->
                        <button type="submit" class="w-full bg-gradient-to-r from-blue-900 to-blue-800 text-white font-bold py-3 px-4 rounded-xl hover:from-blue-800 hover:to-blue-700 hover:text-yellow-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-900 transition duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 active:translate-y-0 text-lg">
                            {{ __('Sign In') }}
                        </button>
                    </form>


                </div>

                <!-- Footer -->
                <div class="mt-8 text-center w-full">
                    <p class="text-blue-100 text-xs font-medium">
                        © 2026 Leyte Normal University • Community Extension Services
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>

