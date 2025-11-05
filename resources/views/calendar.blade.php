<x-app-layout>
    <div class="sm:p-3">
        <div class="p-7 max-w-7xl mx-auto bg-white overflow-hidden shadow-sm sm:rounded-lg">
            
            {{-- PERBAIKAN: Gunakan flex-col di mobile, justify-between di desktop --}}
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 sm:mb-6 space-y-4 sm:space-y-0">
                
                {{-- BLOK 1: Navigasi Bulan --}}
                <div class="flex items-center gap-2 w-full sm:w-auto order-1">
                    <button id="prevBtn" class="p-2 border border-gray-300 rounded-lg text-gray-400 hover:bg-gray-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    <span id="calendarTitle" class="text-xl sm:text-2xl font-semibold text-gray-800 flex-grow text-center sm:text-left">---- ----</span>
                    <button id="nextBtn" class="p-2 border border-gray-300 rounded-lg text-gray-400 hover:bg-gray-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>

                

                {{-- BLOK 2: Pilihan View dan Add Button --}}
                <div class="flex items-center gap-2 w-full sm:w-auto order-2 justify-between sm:justify-end">
                    
                    {{-- Pilihan View: Gunakan flex-grow dan justify-around untuk lebar penuh di mobile --}}
                    <div class="flex bg-gray-100 p-1 rounded-lg text-xs font-semibold flex-grow sm:flex-grow-0 justify-around">
                        <button id="monthViewBtn" class="px-3 py-2 rounded-md text-white bg-blue-600 flex-1 sm:flex-none">Month</button>
                        <button id="weekViewBtn" class="px-3 py-2 rounded-md text-gray-500 hover:bg-gray-200 flex-1 sm:flex-none">Week</button>
                        <button id="dayViewBtn" class="px-3 py-2 rounded-md text-gray-500 hover:bg-gray-200 flex-1 sm:flex-none">Day</button>
                    </div>
                    
                    {{-- Add Event Button --}}
                    <button id="addEventBtn" class="p-2 border border-gray-300 rounded-lg text-gray-400 hover:bg-gray-100 flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                    </button>
                </div>
            </div>

            <div id="calendar" class="text-gray-400"></div>
        </div>

            <!-- Kalendar -->
            <div id="calendar" class="text-gray-400"></div>
        </div>

        <!-- MODAL EVENT -->
        <div id="eventModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-[9999]">
            <div class="flex items-center justify-center w-full h-full">
                <div class="bg-white rounded-3xl shadow-lg w-full max-w-xl relative overflow-hidden">
                    <div class="p-10">
                        <div class="flex justify-between items-start mb-6">
                            <div>
                                <h2 id="modalTitle" class="text-lg font-black text-gray-900">Add / Edit Event</h2>
                                <p class="text-xs text-gray-500">Plan your next big moment</p>
                            </div>
                            <button id="closeModal" class="text-gray-400 hover:text-gray-600 focus:outline-none p-1 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <form id="eventForm" class="space-y-4">
                            @csrf
                            <input type="hidden" id="eventId">

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold mb-1 text-gray-700">Event Title</label>
                                    <input type="text" id="eventTitle" placeholder="Contoh: Mesyuarat Projek" class="w-full text-xs py-2 border-gray-200 rounded-md focus:ring-blue-500 focus:border-blue-500 text-gray-900">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold mb-1 text-gray-700">Event Color</label>
                                    <select id="eventColor" class="text-xs w-full py-2 border-gray-200 rounded-md focus:ring-blue-500 focus:border-blue-500 text-gray-900">
                                        <option value="#FFF0F5">Danger</option>
                                        <option value="#F0FFF4">Success</option>
                                        <option value="#E6E6FA" selected>Primary</option>
                                        <option value="#FFF8DC">Warning</option>
                                    </select>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-semibold mb-1 text-gray-700">Start Date</label>
                                    <input type="date" id="eventStart" class="w-full text-xs py-2 border-gray-200 rounded-md focus:ring-blue-500 focus:border-blue-500 text-gray-900" required>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold mb-1 text-gray-700">End Date</label>
                                    <input type="date" id="eventEnd" class="w-full text-xs py-2 border-gray-200 rounded-md focus:ring-blue-500 focus:border-blue-500 text-gray-900" required>
                                </div>
                            </div>

                            <div class="flex justify-between items-end pt-4">
                                <button type="button" id="deleteEventBtn" class="hidden text-xs font-bold px-3 py-2 border border-gray-300 text-red-600 rounded-md hover:bg-gray-50">Delete</button>

                                <div class="flex space-x-3">
                                    <button type="button" id="cancelBtn" class="text-xs font-bold px-3 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50">Cancel</button>
                                    <button type="submit" id="saveEventBtn" class="bg-blue-600 text-xs text-white px-3 py-2 font-bold rounded-md hover:bg-blue-700">Create Event</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- MODAL DELETE CONFIRMATION -->
        <div id="deleteConfirm" class="hidden fixed inset-0 bg-black bg-opacity-50 z-[10000]">
            <div class="flex items-center justify-center h-full">
                <div class="bg-white rounded-2xl shadow-lg p-6 w-full max-w-sm text-center">
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Delete this event?</h3>
                    <p class="text-xs text-gray-500 mb-6">Are you sure you want to delete this event?</p>
                    <div class="flex justify-center space-x-4">
                        <button id="cancelDeleteBtn" class="px-3 py-2 text-xs font-bold text-gray-700 border border-gray-300 rounded-md hover:bg-gray-100">
                            Cancel
                        </button>
                        <button id="confirmDeleteBtn" class="bg-blue-600 text-xs text-white px-3 py-2 font-bold rounded-md hover:bg-blue-700">
                            Yes, Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const modal = document.getElementById('eventModal');
    const deleteConfirmModal = document.getElementById('deleteConfirm');
    const saveBtn = document.getElementById('saveEventBtn');
    const deleteBtn = document.getElementById('deleteEventBtn');
    const modalTitle = document.getElementById('modalTitle');
    const calendarTitleEl = document.getElementById('calendarTitle');

    // Butang navigasi dan view
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const addEventBtn = document.getElementById('addEventBtn');
    const monthViewBtn = document.getElementById('monthViewBtn');
    const weekViewBtn = document.getElementById('weekViewBtn');
    const dayViewBtn = document.getElementById('dayViewBtn');

    // Variable untuk simpan event ID yang akan dihapus
    let eventIdToDelete = null;

    // Functions untuk modal event
    const showModal = () => { modal.classList.remove('hidden'); };
    const hideModal = () => { 
        modal.classList.add('hidden'); 
        document.getElementById('eventForm').reset(); 
        deleteBtn.classList.add('hidden'); 
    };

    // Functions untuk delete confirmation modal
    const showDeleteConfirm = () => { 
        deleteConfirmModal.classList.remove('hidden'); 
    };
    
    const hideDeleteConfirm = () => { 
        deleteConfirmModal.classList.add('hidden'); 
        eventIdToDelete = null;
    };

    // Function untuk confirm delete
    const confirmDelete = () => {
        if (eventIdToDelete) {
            fetch(`/events/${eventIdToDelete}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': csrfToken }
            }).then(() => { 
                hideDeleteConfirm();
                hideModal(); 
                calendar.refetchEvents(); 
            });
        }
    };

    const updateTitle = () => { calendarTitleEl.textContent = calendar.view.title; }

    const updateViewButtons = (currentView) => {
        // Reset semua tombol view
        [monthViewBtn, weekViewBtn, dayViewBtn].forEach(btn => {
            btn.classList.remove('text-white', 'bg-blue-600');
            btn.classList.add('text-gray-500', 'hover:bg-gray-200');
        });

        // Set tombol aktif
        if (currentView === 'dayGridMonth') {
            monthViewBtn.classList.add('text-white', 'bg-blue-600');
            monthViewBtn.classList.remove('text-gray-500', 'hover:bg-gray-200');
        } else if (currentView === 'timeGridWeek') {
            weekViewBtn.classList.add('text-white', 'bg-blue-600');
            weekViewBtn.classList.remove('text-gray-500', 'hover:bg-gray-200');
        } else if (currentView === 'timeGridDay') {
            dayViewBtn.classList.add('text-white', 'bg-blue-600');
            dayViewBtn.classList.remove('text-gray-500', 'hover:bg-gray-200');
        }
    }

    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        height: '700px',
        headerToolbar: false,
        selectable: true,
        editable: true,
        events: '/events',

        datesSet: info => { updateTitle(); updateViewButtons(info.view.type); },

        dateClick: info => {
            modalTitle.textContent = "Add Event";
            saveBtn.textContent = "Add Event";
            deleteBtn.classList.add('hidden');

            document.getElementById('eventId').value = "";
            document.getElementById('eventTitle').value = "";
            document.getElementById('eventColor').value = "#E6E6FA";
            document.getElementById('eventStart').value = info.dateStr;
            document.getElementById('eventEnd').value = info.dateStr;
            showModal();
        },

        eventClick: info => {
            modalTitle.textContent = "Edit Event";
            saveBtn.textContent = "Update";
            deleteBtn.classList.remove('hidden');

            document.getElementById('eventId').value = info.event.id;
            document.getElementById('eventTitle').value = info.event.title;
            document.getElementById('eventColor').value = info.event.backgroundColor;
            document.getElementById('eventStart').value = info.event.startStr.substring(0,10);
            document.getElementById('eventEnd').value = (info.event.endStr ? info.event.endStr.substring(0,10) : info.event.startStr.substring(0,10));
            showModal();
        },

        eventContent: arg => ({
            html: `<div style="background-color: ${arg.event.backgroundColor}; padding:2px 4px; border-radius:4px;">
                    <span class="text-xs">${arg.event.title}</span>
                </div>`
        })
    });

    calendar.render();

    // Navigasi
    prevBtn.addEventListener('click', () => calendar.prev());
    nextBtn.addEventListener('click', () => calendar.next());
    monthViewBtn.addEventListener('click', () => calendar.changeView('dayGridMonth'));
    weekViewBtn.addEventListener('click', () => calendar.changeView('timeGridWeek'));
    dayViewBtn.addEventListener('click', () => calendar.changeView('timeGridDay'));
    
    addEventBtn.addEventListener('click', () => {
        modalTitle.textContent = "Add Event";
        saveBtn.textContent = "Add Event";
        deleteBtn.classList.add('hidden');
        document.getElementById('eventId').value = "";
        document.getElementById('eventTitle').value = "";
        document.getElementById('eventColor').value = "#E6E6FA";
        const today = new Date().toISOString().substring(0, 10);
        document.getElementById('eventStart').value = today;
        document.getElementById('eventEnd').value = today;
        showModal();
    });

    // Simpan atau kemas kini event
    document.getElementById('eventForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const id = document.getElementById('eventId').value;
        const title = document.getElementById('eventTitle').value;
        const start = document.getElementById('eventStart').value;
        const end = document.getElementById('eventEnd').value;
        const color = document.getElementById('eventColor').value;

        const method = id ? 'PUT' : 'POST';
        const url = id ? `/events/${id}` : '/events';

        fetch(url, {
            method: method,
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ title, start, end, color })
        }).then(() => { hideModal(); calendar.refetchEvents(); });
    });

    // Klik butang delete - buka confirmation modal (NO confirm() here!)
    deleteBtn.addEventListener('click', function() {
        eventIdToDelete = document.getElementById('eventId').value;
        showDeleteConfirm();
    });

    // Event listeners untuk delete confirmation modal
    document.getElementById('cancelDeleteBtn').addEventListener('click', hideDeleteConfirm);
    document.getElementById('confirmDeleteBtn').addEventListener('click', confirmDelete);

    // Event listeners untuk modal event
    document.getElementById('cancelBtn').addEventListener('click', hideModal);
    document.getElementById('closeModal').addEventListener('click', hideModal);
});
    </script>
    <style>
        /* Mengubah tampilan border tabel, membuat kalender terlihat lebih 'rata' */
.fc {
    --fc-border-color: #e5e7eb; /* gray-200 */
    --fc-page-bg-color: transparent;
    --fc-neutral-bg-color: #f9fafb; /* gray-50 */
    --fc-event-bg-color: transparent;
    --fc-event-border-color: transparent;
    --fc-event-text-color: #1f2937; /* gray-800 */
}

/* Menghilangkan border grid dan mengatur padding/margin */
.fc-scrollgrid, .fc-theme-standard td, .fc-theme-standard th {
    border-color: #e5e7eb !important; /* border tipis */
}
.fc-daygrid-body {
    border: none;
}

/* Mengatur header hari (SUN, MON, TUE,...) */
.fc-col-header-cell-cushion {
    padding-top: 1rem !important; /* Contoh: 1.5rem */
    padding-bottom: 1rem !important;
    text-transform: uppercase;
    font-size: 0.75rem; /* text-xs */
    font-weight: 900; /* font-medium */ /* gray-500 */
}

/* Mengatur angka tanggal di sudut sel */
.fc-daygrid-day-number {
    font-size: 0.875rem; /* text-sm */
    font-weight: 500; /* font-medium */
    padding: 0.5rem; /* p-2 */
    margin-top: 0.5rem; /* mt-2 */
    margin-right: 0.5rem; /* mr-2 */
}

/* Menyembunyikan tampilan header default FullCalendar karena kita membuat custom header */
.fc .fc-toolbar-chunk:first-child,
.fc .fc-toolbar-chunk:last-child {
    display: none;
}

/* Gaya untuk event (untuk menyamai tampilan di gambar) */
.fc-event-title-container {
    padding: 0.3rem 0.3rem; /* p-2 */
    margin-left: 1rem;
}

.fc-event {
    /* Warna latar belakang dan teks akan diatur di JS */
    color: white;
    border-radius: 0.375rem; /* rounded-md */
    margin-bottom: 0.25rem; /* mb-1 */
    font-size: 0.875rem; /* text-sm */
    font-weight: 500; /* font-medium */
    white-space: normal;
}

/* Menyembunyikan border tanggal saat ini (today) */
.fc-day-today {
    background-color: transparent !important;
}
    </style>
</x-app-layout>