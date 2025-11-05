<nav x-data="{ open: false, dashboardOpen: false }" class="bg-[#0066FF] border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="self-center">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-36 h-auto">
                </div>
                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Overview') }}
                    </x-nav-link>
                    
                    <x-nav-link :href="route('shipments.create')" :active="request()->routeIs('shipments.create')">
                        {{ __('Upload') }}
                    </x-nav-link>

                    <x-nav-link :href="route('bookings.index')" :active="request()->routeIs('bookings.*')">
                        {{ __('Bookings') }}
                    </x-nav-link>
                    
                    <x-nav-link :href="route('tasks.my')" :active="request()->routeIs('tasks.my')">
                        {{ __('My Tasks') }}
                    </x-nav-link>
                    <x-nav-link :href="route('calendar.index')" :active="request()->routeIs('calendar.index')">
                        {{ __('Calander') }}
                    </x-nav-link>        
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center text-sm leading-4 font-medium text-gray-700 bg-transparent hover:text-gray-900 focus:outline-none transition ease-in-out duration-150">
                            <div class="flex flex-col items-end me-3">
                                <div class="font-semibold text-white">
                                    {{ Auth::user()->name }}
                                </div>
                                <div class="text-xs text-white mt-0.5">
                                    {{ Auth::user()->role }} 
                                </div>
                            </div>
                            <div class="h-10 w-10 rounded-full overflow-hidden flex items-center justify-center bg-[#e0e0e0] me-2">
                                <svg class="h-6 w-6 fill-current text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                    <path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 119 0 4.5 4.5 0 01-9 0zM3.751 20.105a8.25 8.25 0 0116.498 0 .75.75 0 01-.437.695A18.683 18.683 0 0112 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 01-.437-.695z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                            onclick="event.preventDefault();
                                                        this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>
            
            <!-- Hamburger -->
            <div class="md:hidden flex items-center">
                <button @click="open = !open" class="focus:outline-none transition-transform duration-300"
                    :class="{ 'rotate-90': open }">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div 
        x-show="open" 
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 transform -translate-y-2"
        x-transition:enter-end="opacity-100 transform translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 transform translate-y-0"
        x-transition:leave-end="opacity-0 transform -translate-y-2"
        class="sm:hidden bg-white backdrop-blur-sm text-gray-700"
        style="display: none;"
    >
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Overview') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('shipments.create')" :active="request()->routeIs('shipments.create')">
                {{ __('Upload') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('bookings.index')" :active="request()->routeIs('bookings.index')">
                {{ __('Booking') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('tasks.my')" :active="request()->routeIs('tasks.my')">
                {{ __('My Tasks') }}
            </x-responsive-nav-link>
        </div>

        <!-- Admin Navigation Section (from Sidebar) -->
        @if(Auth::check() && Auth::user()->role === 'admin')
        <div class="border-t border-gray-200 pt-4 pb-3">
            <div class="px-4 mb-3">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Management</p>
            </div>
            
            <ul class="space-y-1 px-2">
                <!-- Dashboard Dropdown -->
                <li>
                    <button 
                        @click="dashboardOpen = !dashboardOpen" 
                        class="w-full flex items-center p-2.5 rounded-lg group transition-all duration-300 ease-in-out"
                        :class="dashboardOpen ? 'bg-indigo-100 text-indigo-600' : 'hover:bg-indigo-50 hover:text-indigo-600 text-gray-700'"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" 
                            class="h-5 w-5 flex-shrink-0 transition-transform duration-300" 
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                        </svg>
                        <span class="ml-3 text-sm font-medium">Dashboard</span>
                        <svg :class="dashboardOpen ? 'rotate-180' : ''" 
                            xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-auto transition-transform duration-300 ease-in-out" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <!-- Dropdown -->
                    <div 
                        x-show="dashboardOpen" 
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform scale-95"
                        x-transition:enter-end="opacity-100 transform scale-100"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 transform scale-100"
                        x-transition:leave-end="opacity-0 transform scale-95"
                        class="mt-1 space-y-1 bg-gray-50 rounded-lg overflow-hidden"
                        style="display: none;"
                    >
                        <a href="{{ route('admin.dashboard') }}" 
                            class="flex items-center p-2.5 pl-10 text-gray-700 hover:bg-indigo-100 hover:text-indigo-600 rounded transition-all duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0-01-2-2z" />
                            </svg>
                            <span class="ml-3 text-sm font-medium">Overview</span>
                        </a>

                        <a href="{{ route('admin.financial') }}" 
                            class="flex items-center p-2.5 pl-10 text-gray-700 hover:bg-indigo-100 hover:text-indigo-600 rounded transition-all duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="ml-3 text-sm font-medium">Profit Analytics</span>
                        </a>
                    </div>
                </li>

                <!-- Calendar -->
                <li>
                    <a href="{{ route('calendar.index') }}" class="flex items-center p-2.5 text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg transition-all duration-300 ease-in-out">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span class="ml-3 text-sm font-medium">Calendar</span>
                    </a>
                </li>

                <!-- Task -->
                <li>
                    <a href="{{ route('tasks.index') }}" class="flex items-center p-2.5 text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg transition-all duration-300 ease-in-out">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"> 
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /> 
                        </svg>
                        <span class="ml-3 text-sm font-medium">Task</span>
                    </a>
                </li>
            </ul>

            <!-- SUPPORT SECTION -->
            <div class="px-3 mt-6 mb-2">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Support</p>
            </div>

            <ul class="space-y-1 px-2">
                <!-- User Profile -->
                <li>
                    <a href="{{ route('profile.edit') }}" class="flex items-center p-2.5 text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg transition-all duration-300 ease-in-out">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span class="ml-3 text-sm font-medium">User Profile</span>
                    </a>
                </li>

                <!-- User Management -->
                <li>
                    <a href="{{ route('users.index') }}" class="flex items-center p-2.5 text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg relative transition-all duration-300 ease-in-out">
                        <div class="relative">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-gray-700 absolute -bottom-0.5 -right-0.5 bg-white rounded-full p-[1px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.983 13.75a1.75 1.75 0 100-3.5 1.75 1.75 0 000 3.5z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.428 12.945a7.987 7.987 0 00.083-.945 7.987 7.987 0 00-.083-.945l2.11-1.654a.5.5 0 00.121-.638l-2-3.464a.5.5 0 00-.607-.214l-2.49 1a8.014 8.014 0 00-1.63-.945l-.378-2.65a.5.5 0 00-.497-.43h-4a.5.5 0 00-.497.43l-.378 2.65a8.014 8.014 0 00-1.63.945l-2.49-1a.5.5 0 00-.607.214l-2 3.464a.5.5 0 00.121.638l2.11 1.654a7.987 7.987 0 00-.083.945c0 .319.028.63.083.945l-2.11 1.654a.5.5 0 00-.121.638l2 3.464a.5.5 0 00.607.214l2.49-1a8.014 8.014 0 001.63.945l.378 2.65a.5.5 0 00.497.43h4a.5.5 0 00.497-.43l.378-2.65a8.014 8.014 0 001.63-.945l2.49 1a.5.5 0 00.607-.214l2-3.464a.5.5 0 00-.121-.638l-2.11-1.654z" />
                            </svg>
                        </div>
                        <span class="ml-3 text-sm font-medium">User Management</span>
                    </a>
                </li>
            </ul>
        </div>
        @endif

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-300">
            <div class="px-4 flex items-center">
                <div class="h-10 w-10 rounded-full overflow-hidden flex items-center justify-center bg-[#e0e0e0] me-3 flex-shrink-0">
                    <svg class="h-6 w-6 fill-current text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 119 0 4.5 4.5 0 01-9 0zM3.751 20.105a8.25 8.25 0 0116.498 0 .75.75 0 01-.437.695A18.683 18.683 0 0112 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 01-.437-.695z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="min-w-0">
                    <div class="font-medium text-base flex items-center gap-1">
                        <span>{{ Auth::user()->name }}</span>
                        @if(Auth::check() && Auth::user()->role === 'admin')
                            <svg class="h-4 w-4 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        @endif
                    </div>
                    <div class="font-medium text-sm">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>