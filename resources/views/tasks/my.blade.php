<x-app-layout>
    {{-- Alpine.js filter --}}
    <div class="sm:py-6 max-w-6xl mx-auto" x-data="{ filterStatus: 'All', showMobileFilter: false }">
        <div class="bg-white shadow-sm rounded-lg p-6">
            
            {{-- Header with Filter --}}
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b pb-3 border-gray-200">
                <div class="hidden md:block">
                    <p class="font-semibold text-xl text-gray-900 leading-tight">
                        {{ __('My Tasks') }} 
                    </p>
                    <p class="text-sm text-gray-500 mt-1 mb-1">
                        {{ __('Organize tasks efficiently.') }} <span class="text-blue-500 font-semibold"> "{{ $totalTasks }}</span> tasks"
                    </p>
                </div>

                <div class="items-center self-end gap-2 sm:w-auto">
                    {{-- Filter Button for Mobile with Dropdown --}}
                    <div class="relative inline-block text-left sm:hidden">
                        <button 
                            @click="showMobileFilter = !showMobileFilter"
                            class="p-2 border border-gray-300 rounded-lg text-gray-400 hover:bg-gray-100 transition-all duration-150 w-full sm:w-auto flex-shrink-0"
                            :class="showMobileFilter ? 'bg-gray-100 text-gray-600' : ''"
                        >
                            <svg class="w-5 h-5 mx-auto sm:mx-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z">
                                </path>
                            </svg>
                        </button>

                        {{-- Dropdown Panel --}}
                        <div 
                            x-show="showMobileFilter"
                            @click.away="showMobileFilter = false"
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            class="absolute right-0 mt-2 w-48 rounded-lg shadow-xl bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-10 p-2"
                            style="display: none;"
                        >
                            {{-- Status Filter --}}
                            <div class="py-1">
                                <label for="filterStatus" class="block text-xs font-medium text-gray-700 mb-1">Status</label>
                                <select 
                                    id="filterStatus"
                                    x-model="filterStatus"
                                    class="w-full text-xs py-1.5 px-2 border border-gray-200 rounded-md focus:ring-blue-500 focus:border-blue-500"
                                >
                                    @php
                                        $statuses = ['All', 'Pending', 'In Progress', 'Completed'];
                                    @endphp
                                    @foreach($statuses as $status)
                                        <option value="{{ $status }}">{{ $status }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Buttons for Desktop --}}
                    <div class="hidden sm:flex bg-gray-100 p-1 rounded-lg text-xs font-semibold">
                        @php
                            $statuses = ['All', 'Pending', 'In Progress', 'Completed'];
                        @endphp
                        @foreach($statuses as $status)
                            <button 
                                @click="filterStatus = '{{ $status }}'"
                                :class="filterStatus === '{{ $status }}' ? 'bg-blue-600 text-white' : 'text-gray-700 hover:bg-white'"
                                class="px-3 py-2 rounded-md"
                            >
                                {{ $status }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Task List (2 Column Grid) --}}
            <div class="divide-y divide-gray-200">
                @forelse($tasks as $task)
                    @php
                        $isCompleted = ($task->status === 'Completed');
                                $statusColors = [
                                    'Pending' => 'text-xs px-2 py-1 rounded-full bg-yellow-100 text-yellow-700',
                                    'In Progress' => 'text-xs px-2 py-1 rounded-full bg-blue-100 text-blue-700',
                                    'Completed' => 'text-xs px-2 py-1 rounded-full bg-green-100 text-green-700'
                                ];
                                $statusColor = $statusColors[$task->status] ?? 'bg-gray-100 text-gray-800 border-gray-200 ';
                        $isCompleted = ($task->status === 'Completed');
                        $isOverdue = $task->due_date && \Carbon\Carbon::parse($task->due_date)->isPast() && !$isCompleted;
                    @endphp
                    <div id="task-{{ $task->id }}" 
                        class="px-6 py-3 hover:bg-blue-50 transition-colors duration-150 cursor-pointer"
                        x-show="filterStatus === 'All' || filterStatus === '{{ $task->status }}'"
                        onclick="openStatusModal({{ $task->id }}, '{{ $task->title }}', '{{ $task->status }}')">

                        {{-- Task Info --}}
                        <div class="flex items-start gap-4">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between gap-1">
                                    <div class="flex-1 space-y-1">
                                        <p class="text-sm font-semibold text-gray-900 truncate {{ $isCompleted ? 'line-through text-gray-400' : '' }}">
                                            {{ $task->title }}
                                        </p>
                                        @if($task->description)
                                            <p class="text-xs text-gray-600 mt-1 line-clamp-2 {{ $isCompleted ? 'line-through text-gray-400' : '' }}">
                                                {{ Str::limit($task->description, 100) }}
                                            </p>
                                        @endif
                                    </div>
                                    <!-- Status Badge -->
                                    <span class="inline-flex items-center text-xs font-medium {{ $statusColor }}">
                                        {{ $task->status }}
                                    </span>
                                </div>

                                <div class="flex flex-wrap items-center gap-4 mt-2 text-sm">
                                    <!-- Due Date -->
                                    @if($task->due_date)
                                        <div class="flex items-center gap-1 text-gray-600 text-xs font-medium {{ $isOverdue ? 'text-red-600 font-semibold' : '' }}">
                                            <svg class="mb-0.5 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            @php
                                                try {
                                                    $dueDate = \Carbon\Carbon::parse($task->due_date);
                                                    if ($dueDate->isToday()) {
                                                        echo 'Due Today';
                                                    } elseif ($dueDate->isTomorrow()) {
                                                        echo 'Due Tomorrow';
                                                    } elseif ($isOverdue) {
                                                        echo 'Overdue: ' . $dueDate->format('M j, Y');
                                                    } else {
                                                        echo 'Due: ' . $dueDate->format('M j, Y');
                                                    }
                                                } catch (\Exception $e) {
                                                    echo $task->due_date ?? '-';
                                                }
                                            @endphp
                                        </div>
                                    @endif
                                    @if($task->attachment)
                                        <a href="{{ asset('storage/' . $task->attachment) }}" 
                                        target="_blank" 
                                        class="flex items-center text-blue-600 hover:text-blue-700 hover:underline"
                                        @click.stop
                                        >
                                            <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M21.44 11.05l-9.19 9.19a5 5 0 01-7.07-7.07l9.19-9.19a3 3 0 014.24 4.24L9.88 17.44a1 1 0 01-1.41-1.41l8.48-8.48" />
                                            </svg>
                                            <span class="text-xs font-medium">Attachment</span>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-2 text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No tasks</h3>
                        <p class="mt-1 text-sm text-gray-500">You don't have any tasks assigned yet.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Status Update Modal --}}
    <div id="statusModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50">
        <div class="flex items-center justify-center h-full p-4">
            <div class="bg-white rounded-2xl shadow-lg w-full max-w-md overflow-hidden">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h2 class="text-lg font-bold text-gray-900">Update Task Status</h2>
                            <p id="taskTitleDisplay" class="text-sm text-gray-500 mt-1"></p>
                        </div>
                        <button onclick="closeStatusModal()" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <form id="statusForm" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="taskId" name="task_id">

                        <div>
                            <label class="block text-sm font-semibold mb-2 text-gray-700">Select Status</label>
                            <div class="space-y-2">
                                <label class="flex items-center p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition">
                                    <input type="radio" name="status" value="Pending" class="w-4 h-4 text-blue-600">
                                    <div class="ml-3">
                                        <span class="block text-sm font-medium text-gray-900">Pending</span>
                                        <span class="block text-xs text-gray-500">Task hasn't started yet</span>
                                    </div>
                                </label>
                                <label class="flex items-center p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition">
                                    <input type="radio" name="status" value="In Progress" class="w-4 h-4 text-blue-600">
                                    <div class="ml-3">
                                        <span class="block text-sm font-medium text-gray-900">In Progress</span>
                                        <span class="block text-xs text-gray-500">Currently working on it</span>
                                    </div>
                                </label>
                                <label class="flex items-center p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition">
                                    <input type="radio" name="status" value="Completed" class="w-4 h-4 text-blue-600">
                                    <div class="ml-3">
                                        <span class="block text-sm font-medium text-gray-900">Completed</span>
                                        <span class="block text-xs text-gray-500">Task is finished</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div class="flex justify-end space-x-3 pt-4">
                            <button type="button" onclick="closeStatusModal()" class="text-xs font-bold px-3 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50">Cancel</button>
                            <button type="submit" class="bg-blue-600 text-xs text-white px-3 py-2 font-bold rounded-md hover:bg-blue-700">Update Status</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openStatusModal(taskId, taskTitle, currentStatus) {
            document.getElementById('statusModal').classList.remove('hidden');
            document.getElementById('taskId').value = taskId;
            document.getElementById('taskTitleDisplay').textContent = taskTitle;
            document.getElementById('statusForm').action = `/tasks/${taskId}/update-status`;
            
            // Set current status as checked
            const radios = document.querySelectorAll('input[name="status"]');
            radios.forEach(radio => {
                if (radio.value === currentStatus) {
                    radio.checked = true;
                }
            });
        }

        function closeStatusModal() {
            document.getElementById('statusModal').classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('statusModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeStatusModal();
            }
        });
    </script>
</x-app-layout>