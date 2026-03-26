<nav x-data="{ open: false }" class="bg-gradient-to-r from-blue-900 to-blue-800 border-b border-blue-700 shadow-lg">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="/" class="flex items-center space-x-3">
                        <x-application-logo class="block h-9 w-auto fill-current text-white" />
                        <span class="text-white font-bold text-lg hidden md:inline">SmartCEMES</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-1 sm:-my-px sm:ms-10 sm:flex">
                    @if(auth()->user()->role === 'admin')
                        <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')" class="text-white hover:text-yellow-400 hover:drop-shadow-lg">
                            {{ __('Dashboard') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.extension-programs')" :active="request()->routeIs('admin.extension-programs')" class="text-white hover:text-yellow-400 hover:drop-shadow-lg">
                            {{ __('Extension Programs') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.reports')" :active="request()->routeIs('admin.reports')" class="text-white hover:text-yellow-400 hover:drop-shadow-lg">
                            {{ __('Reports') }}
                        </x-nav-link>
                    @else
                        <x-nav-link :href="route('secretary.dashboard')" :active="request()->routeIs('secretary.dashboard')" class="text-white hover:text-yellow-400 hover:drop-shadow-lg">
                            {{ __('Dashboard') }}
                        </x-nav-link>
                        <x-nav-link :href="route('forms.index')" :active="request()->routeIs('forms.*')" class="text-white hover:text-yellow-400 hover:drop-shadow-lg">
                            {{ __('Forms') }}
                        </x-nav-link>
                        <x-nav-link :href="route('secretary.communities')" :active="request()->routeIs('secretary.communities')" class="text-white hover:text-yellow-400 hover:drop-shadow-lg">
                            {{ __('Communities') }}
                        </x-nav-link>
                        <x-nav-link :href="route('secretary.extension-programs')" :active="request()->routeIs('secretary.extension-programs')" class="text-white hover:text-yellow-400 hover:drop-shadow-lg">
                            {{ __('Extension Programs') }}
                        </x-nav-link>
                        <x-nav-link :href="route('secretary.beneficiaries')" :active="request()->routeIs('secretary.beneficiaries')" class="text-white hover:text-yellow-400 hover:drop-shadow-lg">
                            {{ __('Beneficiaries') }}
                        </x-nav-link>
                        <x-nav-link href="#" class="text-white hover:text-yellow-400 hover:drop-shadow-lg">
                            {{ __('Activities') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-800 hover:text-yellow-400 hover:drop-shadow-lg focus:outline-none transition ease-in-out duration-150">
                            <div class="flex items-center space-x-2">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                </svg>
                                <span>{{ Auth::user()->name }}</span>
                            </div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')" class="text-gray-900">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <hr class="border-gray-200" />

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')" class="text-gray-900"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-blue-100 hover:text-yellow-400 hover:drop-shadow-lg focus:outline-none transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            @if(auth()->user()->role === 'admin')
                <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')" class="text-white hover:text-yellow-400 hover:drop-shadow-lg">
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.extension-programs')" :active="request()->routeIs('admin.extension-programs')" class="text-white hover:text-yellow-400 hover:drop-shadow-lg">
                    {{ __('Extension Programs') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link href="#" class="text-white hover:text-yellow-400 hover:drop-shadow-lg">
                    {{ __('Reports') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link href="#" class="text-white hover:text-yellow-400 hover:drop-shadow-lg">
                    {{ __('Analytics') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link href="#" class="text-white hover:text-yellow-400 hover:drop-shadow-lg">
                    {{ __('Settings') }}
                </x-responsive-nav-link>
            @else
                <x-responsive-nav-link :href="route('secretary.dashboard')" :active="request()->routeIs('secretary.dashboard')" class="text-white hover:text-yellow-400 hover:drop-shadow-lg">
                    {{ __('Dashboard') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('forms.index')" :active="request()->routeIs('forms.*')" class="text-white hover:text-yellow-400 hover:drop-shadow-lg">
                    {{ __('Forms') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('secretary.communities')" :active="request()->routeIs('secretary.communities')" class="text-white hover:text-yellow-400 hover:drop-shadow-lg">
                    {{ __('Communities') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('secretary.extension-programs')" :active="request()->routeIs('secretary.extension-programs')" class="text-white hover:text-yellow-400 hover:drop-shadow-lg">
                    {{ __('Extension Programs') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('secretary.beneficiaries')" :active="request()->routeIs('secretary.beneficiaries')" class="text-white hover:text-yellow-400 hover:drop-shadow-lg">
                    {{ __('Beneficiaries') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link href="#" class="text-white hover:text-yellow-400 hover:drop-shadow-lg">
                    {{ __('Activities') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-blue-700">
            <div class="px-4">
                <div class="font-medium text-base text-white">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-blue-100">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')" class="text-white hover:text-yellow-400 hover:drop-shadow-lg">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')" class="text-white hover:text-yellow-400 hover:drop-shadow-lg"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
