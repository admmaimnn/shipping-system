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
</x-app-layout>