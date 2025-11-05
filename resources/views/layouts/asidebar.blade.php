<div 
    x-data="{ sidebarOpen: false, dashboardOpen: false }" 
    class="flex min-h-screen transition-all duration-500 ease-in-out"
>
    <!-- SIDEBAR -->
    <div 
        @mouseenter="sidebarOpen = true" 
        @mouseleave="sidebarOpen = false"
        :class="sidebarOpen ? 'w-64' : 'w-16'"
        class="bg-white border-r border-gray-200 transition-all duration-500 ease-in-out flex flex-col shadow-sm"
    >
        <!-- MENU -->
        <nav class="flex-1 overflow-y-auto py-4">
            <!-- Menu Title -->
            <div class="px-3 mb-2">
                <p x-show="sidebarOpen" x-transition.opacity.duration.300ms 
                   class="text-xs font-semibold text-gray-400 uppercase tracking-wider">
                    Menu
                </p>
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
                        <span x-show="sidebarOpen" x-transition.opacity.duration.300ms class="ml-3 text-sm font-medium">
                            Dashboard
                        </span>
                        <svg x-show="sidebarOpen" x-transition.opacity.duration.300ms 
                            :class="dashboardOpen ? 'rotate-180' : ''" 
                            xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-auto transition-transform duration-300 ease-in-out" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <!-- Dropdown Smooth -->
                    <div 
                        x-show="dashboardOpen" 
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform scale-95"
                        x-transition:enter-end="opacity-100 transform scale-100"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 transform scale-100"
                        x-transition:leave-end="opacity-0 transform scale-95"
                        class="mt-1 space-y-1 bg-gray-50 rounded-lg overflow-hidden"
                    >
                        <a href="{{ route('admin.dashboard') }}" 
                            class="flex items-center p-2.5 pl-10 text-gray-700 hover:bg-indigo-100 hover:text-indigo-600 rounded transition-all duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0-01-2-2z" />
                            </svg>
                            <span x-show="sidebarOpen" x-transition.opacity.duration.300ms class="ml-3 text-sm font-medium">Overview</span>
                        </a>

                        <a href="{{ route('admin.financial') }}" 
                            class="flex items-center p-2.5 pl-10 text-gray-700 hover:bg-indigo-100 hover:text-indigo-600 rounded transition-all duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span x-show="sidebarOpen" x-transition.opacity.duration.300ms class="ml-3 text-sm font-medium">Profit Analytics</span>
                        </a>
                    </div>
                </li>

                <!-- Calendar -->


                <!-- Task -->
                <li>
                    <a href="{{ route('tasks.index') }}" class="flex items-center p-2.5 text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg transition-all duration-300 ease-in-out">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"> 
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /> 
                        </svg>
                        <span x-show="sidebarOpen" x-transition.opacity.duration.300ms class="ml-3 text-sm font-medium">Task</span>
                    </a>
                </li>
            </ul>

            <!-- SUPPORT SECTION -->
            <div class="px-3 mt-6 mb-2">
                <p x-show="sidebarOpen" x-transition.opacity.duration.300ms class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Support</p>
            </div>

            <ul class="space-y-1 px-2">
                <!-- User Profile -->
                <li>
                    <a href="{{ route('profile.edit') }}" class="flex items-center p-2.5 text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg transition-all duration-300 ease-in-out">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        <span x-show="sidebarOpen" x-transition.opacity.duration.300ms class="ml-3 text-sm font-medium">User Profile</span>
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
                        <span x-show="sidebarOpen" x-transition.opacity.duration.300ms class="ml-3 text-sm font-medium">User Management</span>
                    </a>
                </li>

            </ul>
        </nav>
    </div>
</div>
