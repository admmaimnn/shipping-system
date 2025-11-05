<x-app-layout>
    <div class="sm:py-6 max-w-6xl mx-auto">
        <div class="bg-white p-6 shadow-sm rounded-lg">
            <div class="flex justify-between">
                <div class="mb-3">
                    <p class="font-semibold text-xl text-gray-900 leading-tight">
                        {{ __('All Users') }}
                    </p>
                    <p class="text-sm text-gray-500 mt-1 hidden md:block">
                        {{ __('Manage your team members and their account permissions here.') }}
                    </p>
                </div>
                <div>
                    <a href="{{ route('register') }}">
                        <button class="p-2 border border-gray-300 rounded-lg text-gray-400 hover:bg-gray-100">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                        </button>
                    </a>
                </div>
            </div>     

            <div class="divide-y divide-gray-200">
                @foreach ($users as $user)
                <div class="px-6 py-4 hover:bg-gray-50 transition-colors duration-150">
                    <div class="flex items-center justify-between gap-4">
                        <!-- User Info -->
                        <div class="flex items-center gap-3 flex-1 min-w-0">
                            <!-- Avatar -->
                            <div class="h-11 w-11 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center flex-shrink-0 shadow-sm">
                                <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                    <path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 119 0 4.5 4.5 0 01-9 0zM3.751 20.105a8.25 8.25 0 0116.498 0 .75.75 0 01-.437.695A18.683 18.683 0 0112 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 01-.437-.695z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            
                            <!-- Name and Email -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-1">
                                    <p class="text-sm font-semibold text-gray-900 truncate">
                                        {{ $user->name }}
                                    </p>
                                    @if($user->role === 'admin')
                                        <svg class="h-4 w-4 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                        </svg>
                                    @endif
                                </div>
                                <p class="text-sm text-gray-600 truncate mt-0.5">
                                    {{ $user->email }}
                                </p>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center gap-2" x-data="{ open: false }">
                            <!-- Menu Button -->
                            <div class="relative">
                                <button 
                                    @click="open = !open" 
                                    class="p-2 rounded-lg hover:bg-gray-200 transition-colors duration-150 text-gray-600 hover:text-gray-900">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <circle cx="12" cy="6" r="1.5"/>
                                        <circle cx="12" cy="12" r="1.5"/>
                                        <circle cx="12" cy="18" r="1.5"/>
                                    </svg>
                                </button>

                                <!-- Dropdown Menu -->
                                <div
                                    x-show="open"
                                    @click.outside="open = false"
                                    x-transition:enter="transition ease-out duration-100"
                                    x-transition:enter-start="transform opacity-0 scale-95"
                                    x-transition:enter-end="transform opacity-100 scale-100"
                                    x-transition:leave="transition ease-in duration-75"
                                    x-transition:leave-start="transform opacity-100 scale-100"
                                    x-transition:leave-end="transform opacity-0 scale-95"
                                    class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-lg shadow-lg z-50 py-1"
                                    style="display: none;">
                                    
                                    <!-- Edit Role -->
                                    <form action="{{ route('users.updateRole', $user->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <button 
                                            type="submit" 
                                            name="role" 
                                            value="{{ $user->role === 'admin' ? 'user' : 'admin'}}"
                                            class="flex items-center gap-2 w-full text-left px-4 py-2.5 text-sm hover:bg-gray-50 text-gray-700 transition-colors duration-150">
                                            {{ $user->role === 'admin' ? 'Remove Admin' : 'Make Admin' }}
                                        </button>
                                    </form>

                                    <div class="border-t border-gray-100 my-1"></div>

                                    <!-- Delete User -->
                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.');">
                                        @csrf
                                        @method('DELETE')
                                        <button 
                                            type="submit"
                                            class="flex items-center gap-2 w-full text-left px-4 py-2.5 text-sm hover:bg-gray-50 text-red-600 transition-colors duration-150">
                                            Delete User
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Empty State (if no users) -->
            @if(count($users) === 0)
            <div class="px-6 py-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No users</h3>
                <p class="mt-1 text-sm text-gray-500">Get started by adding a new user.</p>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>