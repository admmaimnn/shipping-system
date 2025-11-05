<x-app-layout>
    {{-- Alpine.js filter --}}
    {{-- Tambahkan 'showMobileFilter' ke data Alpine --}}
    <div class="sm:py-6 max-w-6xl mx-auto" x-data="{ filterStatus: 'All', showMobileFilter: false }">
        <div class="bg-white shadow-sm rounded-lg p-6">
            
            {{-- Header with Add & Filter Buttons --}}
            <div class="flex justify-between items-center border-b pb-3 border-gray-200">
                <div class="hidden md:block">
                    <p class="font-semibold text-xl text-gray-900 leading-tight">
                        {{ __('Task Management') }} 
                    </p>
                    <p class="text-sm text-gray-500 mt-1 mb-1">
                        {{ __('Organize and track your team is tasks efficiently.') }} <span class="text-blue-500 font-semibold"> "{{ $totalTasks }}</span> tasks"
                    </p>
                </div>

                {{-- Kontainer untuk Tombol Filter dan Tambah --}}
                <div class="flex space-x-2 w-full md:w-auto justify-end">
                    
                    {{-- Filter Button Group for Desktop (md and up) --}}
                    <div class="hidden md:flex bg-gray-100 p-1 rounded-lg text-xs font-semibold">
                        @php
                            $statuses = ['All', 'Pending', 'In Progress', 'Completed'];
                        @endphp
                        @foreach($statuses as $status)
                            <button 
                                @click="filterStatus = '{{ $status }}'"
                                :class="filterStatus === '{{ $status }}' ? 'bg-blue-600 text-white' : 'text-gray-700 hover:bg-white'"
                                class="px-3 py-2 rounded-md transition-colors duration-150"
                            >
                                {{ $status }}
                            </button>
                        @endforeach
                    </div>

                    {{-- Filter Button for Mobile with Dropdown --}}
                    <div class="relative inline-block text-left md:hidden flex-shrink-0">
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
                            {{-- Status Filter (Menggunakan x-model) --}}
                            <div class="py-1">
                                <label for="mobileFilterStatus" class="block text-xs font-medium text-gray-700 mb-1">Status</label>
                                <select 
                                    id="mobileFilterStatus"
                                    x-model="filterStatus"
                                    @change="showMobileFilter = false" {{-- Tutup setelah memilih --}}
                                    class="w-full text-xs py-1.5 px-2 border border-gray-200 rounded-md focus:ring-blue-500 focus:border-blue-500 text-gray-900"
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
                    {{-- End Mobile Filter --}}

                    <button onclick="openCreateModal()" class="p-2 border border-gray-300 rounded-lg text-gray-400 hover:bg-gray-100 flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                    </button>
                </div>
                {{-- End Flex Space-x-2 --}}
            </div>
            {{-- Task List --}}
            <div class="divide-y divide-gray-200">
                @if($tasks->isEmpty())
                    <div class="px-6 py-20 text-center">
                        <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-100 rounded-full mb-4">
                            <svg class="w-10 h-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">No Tasks Yet</h3>
                        <p class="text-gray-600 text-sm mb-6 max-w-sm mx-auto">Get started by creating your first task and assign it to team members.</p>
                    </div>
                @else
                    @foreach($tasks as $task)
                        @php
                            $isCompleted = ($task->status === 'Completed');
                            $statusColors = [
                                'Pending' => 'text-yellow-500',
                                'In Progress' => 'text-blue-600',
                                'Completed' => 'text-gray-700'
                            ];
                            $statusColor = $statusColors[$task->status] ?? 'bg-gray-100 text-gray-800 border-gray-200';
                        @endphp
                        {{-- Penggunaan x-show tetap sama untuk filter --}}
                        <div class="px-6 py-3 hover:bg-blue-50 transition-colors duration-150 cursor-pointer"
                            x-show="filterStatus === 'All' || filterStatus === '{{ $task->status }}'"
                            onclick="openEditModal(
                                {{ $task->id }},
                                '{{ addslashes($task->title) }}',
                                '{{ addslashes($task->description ?? '') }}',
                                '{{ $task->due_date ?? '' }}',
                                '{{ $task->status }}',
                                '{{ $task->assigned_to }}',
                                '{{ $task->attachment ?? '' }}'
                            )">
                            
                            <div class="flex items-start gap-4">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between gap-1">
                                        <div class="flex-1 space-y-1">
                                            <p class="text-sm font-semibold text-gray-900 truncate {{ $isCompleted ? 'line-through text-gray-400' : '' }}">
                                                {{ $task->title }}
                                            </p>
                                            @if($task->description)
                                                <p class="text-xs text-gray-600 mt-1 line-clamp-2 {{ $isCompleted ? 'line-through text-gray-400' : '' }}">
                                                    {{ $task->description }}
                                                </p>
                                            @endif
                                        </div>
                                        
                                        <span class="inline-flex items-center text-xs font-medium {{ $statusColor }} flex-shrink-0">
                                            {{ $task->status }}...
                                        </span>
                                    </div>

                                    <div class="flex flex-wrap items-center gap-4 mt-2 text-sm">
                                        <div class="flex items-center gap-2">
                                            <div class="w-6 h-6 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center">
                                                <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"></path>
                                                </svg>
                                            </div>
                                            <span class="text-xs font-medium text-gray-900 truncate">{{ $task->user->name ?? 'Unassigned' }}</span>
                                        </div>

                                        @if($task->due_date)
                                            <div class="flex items-center gap-1 text-gray-600 text-xs font-medium">
                                                <svg class="mb-0.5 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                @php
                                                    try {
                                                        $dueDate = \Carbon\Carbon::parse($task->due_date);
                                                        echo $dueDate->isTomorrow() ? 'Tomorrow' : $dueDate->format('M j, Y');
                                                    } catch (\Exception $e) {
                                                        echo $task->due_date ?? '-';
                                                    }
                                                @endphp
                                            </div>
                                        @endif

                                        @if($task->attachment)
                                            <div class="flex items-center gap-1 text-blue-600">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                                </svg>
                                                <span class="text-xs font-medium">Attachment</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
            
        </div>
    </div>

    {{-- Modal --}}
    <div id="taskModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-40">
        <div class="flex items-center justify-center p-4 mt-4">
            <div class="bg-white rounded-3xl shadow-lg w-full max-w-xl relative p-0 overflow-hidden">
                <div class="p-10">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h2 id="modalTitle" class="text-lg font-black text-gray-900">Add a new task</h2>
                            <p class="text-xs text-gray-500">Effortlessly manage your to-do list: add a new task</p>
                        </div>
                        <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 focus:outline-none p-1 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <form id="taskForm" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <input type="hidden" name="_method" id="formMethod" value="POST">

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold mb-1 text-gray-700">Due Date</label>
                                <input type="date" name="due_date" id="taskDueDate" class="w-full text-xs py-2 border-gray-200 rounded-md focus:ring-blue-500 focus:border-blue-500 text-gray-900">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold mb-1 text-gray-700">Status</label>
                                <select name="status" id="taskStatus" class=" text-xs w-full py-2 border-gray-200 rounded-md focus:ring-blue-500 focus:border-blue-500 text-gray-900">
                                    <option value="Pending">To Do</option>
                                    <option value="In Progress">In Progress</option>
                                    <option value="Completed">Completed</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold mb-1 text-gray-700">Title</label>
                                <input type="text" name="title" id="taskTitle" placeholder="e.g letter" class="w-full text-xs py-2 border-gray-200 rounded-md focus:ring-blue-500 focus:border-blue-500 text-gray-900" required>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold mb-1 text-gray-700">Assignees</label>
                                <select name="assigned_to" id="taskAssignedTo" class="text-xs py-2 w-full border-gray-200 rounded-md focus:ring-blue-500 focus:border-blue-500 text-gray-900">
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="pt-2">
                            <label class="block text-xs font-semibold mb-1 text-gray-700">Description</label>
                            <textarea name="description" id="taskDescription" class="w-full border-gray-200 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-gray-900 h-24"></textarea>
                        </div>

                        {{-- Attachment (Dynamic display) --}}
                        <div class="bg-gray-50 border border-gray-200 rounded-lg w-full">
                            <div class="flex items-baseline p-3">
                                <span class="text-xs font-semibold text-gray-900 mr-3">Attachments</span>
                                <input type="file" name="attachment" id="taskAttachment" class="sr-only">
                                <label for="taskAttachment" class="text-xs border-l pl-3 text-blue-600 font-medium cursor-pointer hover:text-blue-700">Upload file</label>
                            </div>
                            
                            {{-- Perubahan: Mengubah mb-4 ke pb-3 dan menghilangkan space-x-4 --}}
                            <div class="flex items-center px-3 pb-3">
                                {{-- Kontainer untuk file, agar dapat menampung satu atau lebih file dengan rapi --}}
                                <div id="attachedFile" class="flex flex-wrap gap-2 text-sm max-w-full"></div>
                            </div>
                        </div>

                        <div class="flex justify-between items-end pt-4">
                            <div>
                                <button type="button" id="deleteBtn" onclick="deleteTask()" class="hidden text-xs font-bold px-3 py-2 border border-gray-300 text-red-600 rounded-md hover:bg-gray-50">Delete</button>
                            </div>
                            <div class="flex space-x-3">
                                <button type="button" onclick="closeModal()" class="text-xs font-bold px-3 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50">Cancel</button>
                                <button type="submit" id="saveBtn" class="bg-blue-600 text-xs text-white px-3 py-2 font-bold rounded-md hover:bg-blue-700">Create Task</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div id="deleteConfirm" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50">
        <div class="flex items-center justify-center h-full">
            <div class="bg-white rounded-2xl shadow-lg p-6 w-full max-w-sm text-center">
                <h3 class="text-lg font-bold text-gray-900 mb-2">Delete this task?</h3>
                <p class="text-xs text-gray-500 mb-6">This action cannot be undone.</p>
                <div class="flex justify-center space-x-4">
                    <button onclick="cancelDelete()" class="px-3 py-2 text-xs font-bold text-gray-700 border border-gray-300 rounded-md hover:bg-gray-100">
                        Cancel
                    </button>
                    <button onclick="confirmDelete()" class="bg-blue-600 text-xs text-white px-3 py-2 font-bold rounded-md hover:bg-blue-700">
                        Yes, Delete
                    </button>
                </div>
            </div>
        </div>
    </div>


    {{-- Script --}}
    <script>
        let currentTaskId = null;

        function openCreateModal() {
            currentTaskId = null;
            document.getElementById('modalTitle').textContent = "Assign New Task";
            document.getElementById('taskForm').action = "{{ route('tasks.store') }}"; 
            document.getElementById('formMethod').value = "POST";
            document.getElementById('deleteBtn').classList.add('hidden');

            document.getElementById('taskTitle').value = '';
            document.getElementById('taskDescription').value = '';
            document.getElementById('taskDueDate').value = '';
            document.getElementById('taskStatus').value = 'Pending';
            document.getElementById('taskAssignedTo').selectedIndex = 0;

            document.getElementById('attachedFile').innerHTML = ''; // kosongkan paparan fail
            document.getElementById('taskModal').classList.remove('hidden');
        }

        function openEditModal(id, title, description, due_date, status, assigned_to, attachment = null) {
            currentTaskId = id;
            document.getElementById('modalTitle').textContent = "Edit Task";
            document.getElementById('taskForm').action = `/tasks/${id}`;
            document.getElementById('formMethod').value = "PUT";
            document.getElementById('deleteBtn').classList.remove('hidden');

            document.getElementById('taskTitle').value = title;
            document.getElementById('taskDescription').value = description;
            document.getElementById('taskDueDate').value = due_date;
            document.getElementById('taskStatus').value = status;
            document.getElementById('taskAssignedTo').value = assigned_to;

            const fileDiv = document.getElementById('attachedFile');
            fileDiv.innerHTML = "";
            if (attachment) {
                const fileUrl = `/storage/${attachment}`;
                const fileName = attachment.split('/').pop();
                // FIX: Tambah min-w-0 dan truncate pada span nama file, dan max-w-full pada <a>
                fileDiv.innerHTML = `
                    <a href="${fileUrl}" target="_blank" class="flex items-center space-x-1 p-2 bg-gray-50 rounded-lg border border-gray-100 hover:bg-gray-100 max-w-full block">
                        <svg class="h-4 w-4 text-blue-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                            d="M21.44 11.05l-9.19 9.19a5 5 0 01-7.07-7.07l9.19-9.19a3 3 0 014.24 4.24L9.88 17.44a1 1 0 01-1.41-1.41l8.48-8.48" />
                        </svg>
                        <span class="text-gray-700 min-w-0 truncate">${fileName}</span>
                    </a>
                `;
            }

            document.getElementById('taskModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('taskModal').classList.add('hidden');
        }


        function deleteTask() {
            // buka popup custom
            document.getElementById('deleteConfirm').classList.remove('hidden');
        }

        function cancelDelete() {
            // tutup popup
            document.getElementById('deleteConfirm').classList.add('hidden');
        }

        function confirmDelete() {
            // bila tekan "Yes, Delete"
            document.getElementById('deleteConfirm').classList.add('hidden');

            const form = document.getElementById('taskForm');
            form.action = `/tasks/${currentTaskId}`;
            document.getElementById('formMethod').value = "DELETE";
            form.submit();
        }


        document.getElementById('taskAttachment').addEventListener('change', function(event) {
            const fileDiv = document.getElementById('attachedFile');
            fileDiv.innerHTML = ""; // kosongkan dulu
            const file = event.target.files[0];

            if (file) {
                const fileName = file.name;
                // FIX: Tambah min-w-0 dan truncate pada span nama file, dan max-w-full pada <div>
                fileDiv.innerHTML = `
                    <div class="flex items-center space-x-1 p-2 bg-gray-50 rounded-lg border border-gray-100 max-w-full">
                        <svg class="h-4 w-4 text-blue-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                            d="M21.44 11.05l-9.19 9.19a5 5 0 01-7.07-7.07l9.19-9.19a3 3 0 014.24 4.24L9.88 17.44a1 1 0 01-1.41-1.41l8.48-8.48" />
                        </svg>
                        <span class="text-gray-700 min-w-0 truncate">${fileName}</span>
                    </div>
                `;
            }
        });
    </script>
</x-app-layout>