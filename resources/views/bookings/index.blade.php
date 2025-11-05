<x-app-layout>
    <div class="sm:py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm rounded-lg p-4 sm:p-6">

            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center border-b pb-3 border-gray-200 gap-4">
                <div class="hidden md:block">
                    <p class="font-semibold text-lg sm:text-xl text-gray-900 leading-tight">
                        {{ __('Sea Freight Bookings') }}
                    </p>
                    <p class="text-sm text-gray-500 mt-1">
                        {{ __('Manage and track your shipment bookings.') }}
                        <span class="text-blue-500 font-semibold">"{{ $totalBookings }}"</span> bookings
                    </p>
                </div>
                

                <div class="flex justify-end sm:justify-normal flex-wrap sm:flex-nowrap items-center gap-2 sm:gap-3">
                    <div class="hidden sm:flex bg-gray-100 p-1 rounded-lg text-xs font-semibold gap-1 sm:w-auto overflow-x-auto no-scrollbar" id="statusFiltersDesktop">
                        @php
                            $statuses = ['All', 'Pending', 'Confirmed', 'Sailed', 'Delayed', 'Delivered'];
                        @endphp
                        @foreach($statuses as $status)
                            <button
                                onclick="setFilter('status', '{{ $status }}'); filterBookings()"
                                data-filter-status="{{ $status }}"
                                class="px-3 py-2 rounded-md flex-1 sm:flex-none text-center filter-btn-status transition-colors whitespace-nowrap
                                    {{ $status === 'All' ? 'bg-blue-600 text-white' : 'text-gray-700 hover:bg-white' }}">
                                {{ $status }}
                            </button>
                        @endforeach
                    </div>

                    <div class="relative inline-block text-left">
                        <button id="toggleFilterBtn" onclick="toggleDateFilters()"
                            class="p-2 border border-gray-300 rounded-lg text-gray-400 hover:bg-gray-100 transition-all duration-150 w-full sm:w-auto flex-shrink-0">
                            <svg class="w-5 h-5 mx-auto sm:mx-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z">
                                </path>
                            </svg>
                        </button>

                        <div id="dateFilters"
                            class="absolute right-0 mt-2 w-48 rounded-lg shadow-xl bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-10 hidden p-2">
                            
                            <div class="py-1 sm:hidden">
                                <label for="filterStatus" class="block text-xs font-medium text-gray-700 mb-1">Status</label>
                                <select id="filterStatus" onchange="setFilter('status', this.value); filterBookings()" class="w-full text-xs py-1.5 px-2 border border-gray-200 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                    @php
                                        $statuses = ['All', 'Pending', 'Confirmed', 'Sailed', 'Delayed', 'Delivered'];
                                    @endphp
                                    @foreach($statuses as $status)
                                        <option value="{{ $status }}" {{ $status === 'All' ? 'selected' : '' }}>{{ $status }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="sm:hidden my-2 border-gray-200"></div> <div class="py-1">
                                <div class="mb-2">
                                    <label for="filterMonth"
                                        class="block text-xs font-medium text-gray-700 mb-1">Month</label>
                                    <select id="filterMonth" onchange="filterBookings()"
                                        class="w-full text-xs py-1.5 px-2 border border-gray-200 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                        <option value="">All Months</option>
                                        @foreach(range(1, 12) as $m)
                                            <option value="{{ $m }}">{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="filterYear"
                                        class="block text-xs font-medium text-gray-700 mb-1">Year</label>
                                    <select id="filterYear" onchange="filterBookings()"
                                        class="w-full text-xs py-1.5 px-2 border border-gray-200 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                        @foreach(range(date('Y'), date('Y') - 5) as $y)
                                            <option value="{{ $y }}" {{ $y == date('Y') ? 'selected' : '' }}>{{ $y }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button onclick="openCreateModal()"
                        class="p-2 border border-gray-300 rounded-lg text-gray-400 hover:bg-gray-100 flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mx-auto sm:mx-0" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="divide-y divide-gray-200">
                @if($bookings->isEmpty())
                    <div class="px-6 py-20 text-center">
                        <div
                            class="inline-flex items-center justify-center w-20 h-20 bg-gray-100 rounded-full mb-4 mx-auto">
                            <svg class="w-10 h-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">No Bookings Yet</h3>
                        <p class="text-gray-600 text-sm mb-6 max-w-sm mx-auto">Create your first sea freight booking to
                            get started.</p>
                    </div>
                @else
                    @foreach($bookings as $booking)
                        <div class="px-4 py-4 sm:px-6 hover:bg-blue-50 transition-colors duration-150 cursor-pointer booking-item"
                            data-status="{{ $booking->status }}"
                            data-month="{{ $booking->created_at?->format('n') ?? '' }}"
                            data-year="{{ $booking->created_at?->format('Y') ?? '' }}"
                            onclick="openEditModal(
                                {{ $booking->id }},
                                '{{ addslashes($booking->shipper_name) }}',
                                '{{ addslashes($booking->vessel_name ?? '') }}',
                                '{{ $booking->status }}',
                                '{{ $booking->booking_reference ?? '' }}',
                                {{ $booking->volume ?? 0 }},
                                '{{ addslashes($booking->container_damage_assessment ?? '') }}',
                                '{{ $booking->vsl_date ?? '' }}',
                                '{{ addslashes($booking->port_of_discharge ?? '') }}',
                                {{ $booking->vol_40ft ?? 0 }},
                                {{ $booking->hq_20ft ?? 0 }},
                                '{{ addslashes($booking->remarks ?? '') }}'
                            )">
                            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-2">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between gap-1">
                                        <div class="flex-1 space-y-1">
                                            <p
                                                class="text-sm font-semibold text-gray-900 truncate {{ $booking->status === 'Delivered' ? 'line-through' : '' }}">
                                                {{ $booking->booking_no }}
                                            </p>
                                            <p
                                                class="text-xs text-gray-600 mt-1 {{ $booking->status === 'Delivered' ? 'line-through' : '' }}">
                                                Shipper: {{ $booking->shipper_name }}
                                            </p>
                                        </div>
                                        <span
                                            class="inline-flex items-center text-xs font-medium px-2 py-1 rounded-full flex-shrink-0
                                            {{ $booking->status === 'Pending' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                            {{ $booking->status === 'Confirmed' ? 'bg-blue-100 text-blue-700' : '' }}
                                            {{ $booking->status === 'Sailed' ? 'bg-purple-100 text-purple-700' : '' }}
                                            {{ $booking->status === 'Delayed' ? 'bg-red-100 text-red-700' : '' }}
                                            {{ $booking->status === 'Delivered' ? 'bg-green-100 text-green-700' : '' }}">
                                            {{ $booking->status }}
                                        </span>
                                    </div>

                                    <div class="flex flex-wrap items-center gap-3 mt-3 text-xs sm:text-sm">
                                        @if($booking->vessel_name)
                                            <div class="flex items-center gap-1">
                                                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M12 2v6m0 0l-2-2m2 2l2-2M5 12l-2 8h18l-2-8M5 12h14M5 12l2-4h10l2 4" />
                                                </svg>
                                                <span class="font-medium text-gray-900">{{ $booking->vessel_name }}</span>
                                            </div>
                                        @endif
                                        @if($booking->vsl_date)
                                            <div class="flex items-center gap-1 text-gray-600 font-medium">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                {{ $booking->vsl_date->format('M j, Y') }}
                                            </div>
                                        @endif
                                        @if($booking->port_of_discharge)
                                            <div class="flex items-center gap-1 text-gray-700 font-semibold">
                                                POD: {{ $booking->port_of_discharge }}
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
    <!-- Modal -->
    <div id="bookingModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 ">
        <div class="flex items-center justify-center p-4">
            <div class="bg-white rounded-3xl shadow-lg w-full max-w-2xl relative p-0 overflow-hidden">
                <div class="p-10">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h2 id="modalTitle" class="text-lg font-black text-gray-900">Create New Booking</h2>
                            <p class="text-xs text-gray-500">Add a new sea freight booking</p>
                        </div>
                        <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 focus:outline-none p-1 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <form id="bookingForm" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <input type="hidden" name="_method" id="formMethod" value="POST">

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold mb-1 text-gray-700">Shipper Name *</label>
                                <input type="text" name="shipper_name" id="shipperName" class="w-full text-xs py-2 border border-gray-200 rounded-md focus:ring-blue-500 focus:border-blue-500 text-gray-900" required>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold mb-1 text-gray-700">Vessel Name</label>
                                <input type="text" name="vessel_name" id="vesselName" class="w-full text-xs py-2 border border-gray-200 rounded-md focus:ring-blue-500 focus:border-blue-500 text-gray-900" required>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold mb-1 text-gray-700">Status *</label>
                                <select name="status" id="status" class="text-xs w-full py-2 border border-gray-200 rounded-md focus:ring-blue-500 focus:border-blue-500 text-gray-900" required>
                                    <option value="Pending">Pending</option>
                                    <option value="Confirmed">Confirmed</option>
                                    <option value="Sailed">Sailed</option>
                                    <option value="Delayed">Delayed</option>
                                    <option value="Delivered">Delivered</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold mb-1 text-gray-700">Booking Reference</label>
                                <input type="text" name="booking_reference" id="bookingReference" class="w-full text-xs py-2 border border-gray-200 rounded-md focus:ring-blue-500 focus:border-blue-500 text-gray-900" required>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold mb-1 text-gray-700">Volume (CBM)</label>
                                <input type="number" name="volume" id="volume" class="w-full text-xs py-2 border border-gray-200 rounded-md focus:ring-blue-500 focus:border-blue-500 text-gray-900" required>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold mb-1 text-gray-700">VSL Date</label>
                                <input type="date" name="vsl_date" id="vslDate" class="w-full text-xs py-2 border border-gray-200 rounded-md focus:ring-blue-500 focus:border-blue-500 text-gray-900" required>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold mb-1 text-gray-700">HQ 20ft</label>
                                <input type="number" name="hq_20ft" id="hq20ft" class="w-full text-xs py-2 border border-gray-200 rounded-md focus:ring-blue-500 focus:border-blue-500 text-gray-900" required>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold mb-1 text-gray-700">Vol 40ft</label>
                                <input type="number" name="vol_40ft" id="vol40ft" class="w-full text-xs py-2 border border-gray-200 rounded-md focus:ring-blue-500 focus:border-blue-500 text-gray-900" required>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold mb-1 text-gray-700">CDA</label>
                                <input type="text" name="container_damage_assessment" id="cda" class="w-full text-xs py-2 border border-gray-200 rounded-md focus:ring-blue-500 focus:border-blue-500 text-gray-900" required>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold mb-1 text-gray-700">Port of Discharge</label>
                                <input type="text" name="port_of_discharge" id="pod" class="w-full text-xs py-2 border border-gray-200 rounded-md focus:ring-blue-500 focus:border-blue-500 text-gray-900" required>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold mb-1 text-gray-700">Remarks</label>
                            <textarea name="remarks" id="remarks" class="w-full border border-gray-200 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-gray-900 h-20"></textarea>
                        </div>

                        <div class="flex justify-between items-end pt-4">
                            <div>
                                <button type="button" id="deleteBtn" onclick="deleteBooking()" class="hidden text-xs font-bold px-3 py-2 border border-gray-300 text-red-600 rounded-md hover:bg-gray-50">Delete</button>
                            </div>
                            <div class="flex space-x-3">
                                <button type="button" onclick="closeModal()" class="text-xs font-bold px-3 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50">Cancel</button>
                                <button type="submit" id="saveBtn" class="bg-blue-600 text-xs text-white px-3 py-2 font-bold rounded-md hover:bg-blue-700">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteConfirm" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50">
        <div class="flex items-center justify-center h-full">
            <div class="bg-white rounded-2xl shadow-lg p-6 w-full max-w-sm text-center">
                <h3 class="text-lg font-bold text-gray-900 mb-2">Delete this booking?</h3>
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

    {{-- Since the modal HTML was not provided, I'm skipping it, but it should be placed outside the main content div. --}}

    <script>
        let currentBookingId = null;
        let showDateFilters = false;

        let filterState = {
            status: 'All',
            month: '',
            year: new Date().getFullYear().toString()
        };

        // --- FILTER LOGIC ---
        
        // Helper to update button styles for both desktop and mobile status filters
        function updateStatusButtonStyles(value, selector) {
            document.querySelectorAll(selector).forEach(btn => {
                if (btn.dataset.filterStatus === value) {
                    btn.classList.remove('text-gray-700', 'hover:bg-white');
                    btn.classList.add('bg-blue-600', 'text-white');
                } else {
                    btn.classList.add('text-gray-700', 'hover:bg-white');
                    btn.classList.remove('bg-blue-600', 'text-white');
                }
            });
        }

        function setFilter(type, value) {
            filterState[type] = value;
            
            if (type === 'status') {
                // Update desktop status buttons
                updateStatusButtonStyles(value, '.filter-btn-status');
                // Update mobile status buttons inside the dropdown
                updateStatusButtonStyles(value, '.filter-btn-status-mobile');
            }
        }

        function toggleDateFilters() {
            showDateFilters = !showDateFilters;
            const dateFilters = document.getElementById('dateFilters');
            const toggleBtn = document.getElementById('toggleFilterBtn');

            if (showDateFilters) {
                dateFilters.classList.remove('hidden');
                toggleBtn.classList.remove('text-gray-400', 'border-gray-300', 'hover:bg-gray-100');
                toggleBtn.classList.add('text-blue-600', 'border-blue-600', 'bg-blue-50'); // Highlight when active
                
                // Ensure mobile status buttons reflect current filterState.status when opening
                updateStatusButtonStyles(filterState.status, '.filter-btn-status-mobile');
            } else {
                dateFilters.classList.add('hidden');
                toggleBtn.classList.remove('text-blue-600', 'border-blue-600', 'bg-blue-50');
                toggleBtn.classList.add('text-gray-400', 'border-gray-300', 'hover:bg-gray-100');
            }
        }

        function resetDateFilters() {
            document.getElementById('filterMonth').value = '';
            document.getElementById('filterYear').value = new Date().getFullYear();
            filterState.month = '';
            filterState.year = new Date().getFullYear().toString();
        }

        function filterBookings() {
            const filterStatus = filterState.status;
            
            // Get values directly from the select elements
            const filterMonth = document.getElementById('filterMonth').value;
            const filterYear = document.getElementById('filterYear').value;

            filterState.month = filterMonth;
            filterState.year = filterYear;

            const items = document.querySelectorAll('.booking-item');

            items.forEach(item => {
                const itemStatus = item.dataset.status;
                const itemMonth = item.dataset.month;
                const itemYear = item.dataset.year;

                const statusMatch = filterStatus === 'All' || filterStatus === itemStatus;
                const monthMatch = filterMonth === '' || filterMonth === itemMonth;
                const yearMatch = String(filterYear) === itemYear;

                if (statusMatch && monthMatch && yearMatch) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        }

        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('dateFilters');
            const toggleBtn = document.getElementById('toggleFilterBtn');
            const dropdownContainer = toggleBtn.closest('.relative');

            if (showDateFilters && dropdownContainer && !dropdownContainer.contains(event.target)) {
                showDateFilters = false;
                dropdown.classList.add('hidden');
                toggleBtn.classList.remove('text-blue-600', 'border-blue-600', 'bg-blue-50');
                toggleBtn.classList.add('text-gray-400', 'border-gray-300', 'hover:bg-gray-100');
            }
        });


        function resetFilters() {
            // Full reset
            filterState = {
                status: 'All',
                month: '',
                year: new Date().getFullYear().toString()
            };

            // Reset UI for date filters
            resetDateFilters();
            showDateFilters = false;
            document.getElementById('dateFilters').classList.add('hidden');
            const toggleBtn = document.getElementById('toggleFilterBtn');
            toggleBtn.classList.remove('text-blue-600', 'border-blue-600', 'bg-blue-50');
            toggleBtn.classList.add('text-gray-400', 'border-gray-300', 'hover:bg-gray-100');


            // Reset UI for status filters (Desktop & Mobile)
            updateStatusButtonStyles('All', '.filter-btn-status');
            updateStatusButtonStyles('All', '.filter-btn-status-mobile');

            filterBookings();
        }

        // Initialize filters on page load
        document.addEventListener('DOMContentLoaded', function() {
            filterBookings();
        });
    
        // --- MODAL & CRUD FUNCTIONS (Unchanged from original script) ---

        function openCreateModal() {
            currentBookingId = null;
            document.getElementById('modalTitle').textContent = "Create New Booking";
            document.getElementById('bookingForm').action = "{{ route('bookings.store') }}";
            document.getElementById('formMethod').value = "POST";
            document.getElementById('deleteBtn').classList.add('hidden');
            document.getElementById('saveBtn').textContent = "Save";

            document.getElementById('bookingForm').reset();
            document.getElementById('bookingModal').classList.remove('hidden');
        }

        function openEditModal(id, shipperName, vesselName, status, bookingRef, volume, cda, vslDate, pod, vol40ft, hq20ft, remarks) {
            currentBookingId = id;
            document.getElementById('modalTitle').textContent = "Edit Booking";
            // NOTE: Ensure your Laravel routes are configured correctly for PUT/PATCH/DELETE
            document.getElementById('bookingForm').action = `/bookings/${id}`;
            document.getElementById('formMethod').value = "PUT";
            document.getElementById('deleteBtn').classList.remove('hidden');
            document.getElementById('saveBtn').textContent = "Save";

            document.getElementById('shipperName').value = shipperName;
            document.getElementById('vesselName').value = vesselName;
            document.getElementById('status').value = status;
            document.getElementById('bookingReference').value = bookingRef;
            document.getElementById('volume').value = volume;
            document.getElementById('cda').value = cda;
            document.getElementById('vslDate').value = vslDate;
            document.getElementById('pod').value = pod;
            document.getElementById('vol40ft').value = vol40ft;
            document.getElementById('hq20ft').value = hq20ft;
            document.getElementById('remarks').value = remarks;

            document.getElementById('bookingModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('bookingModal').classList.add('hidden');
        }

        function deleteBooking() {
            document.getElementById('deleteConfirm').classList.remove('hidden');
        }

        function cancelDelete() {
            document.getElementById('deleteConfirm').classList.add('hidden');
        }

        function confirmDelete() {
            document.getElementById('deleteConfirm').classList.add('hidden');

            const form = document.getElementById('bookingForm');
            form.action = `/bookings/${currentBookingId}`;
            document.getElementById('formMethod').value = "DELETE";
            form.submit();
        }
    </script>
</x-app-layout>