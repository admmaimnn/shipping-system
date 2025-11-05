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