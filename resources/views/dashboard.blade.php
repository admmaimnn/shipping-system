<x-app-layout>
    <div class="sm:p-3">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 p-7 bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <!-- Responsive Flex Container -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 sm:p-6">

                <!-- Left Section (Welcome) -->
                <div class="flex-1 w-full">
                    <div class="flex items-center gap-3 sm:mb-2">
                        <div class="space-y-1">
                            <p class="text-gray-500 text-sm font-medium tracking-wide">{{ __('Welcome Back,') }}</p>
                            <h1 class="sm:text-3xl font-bold bg-gradient-to-r from-gray-900 via-gray-700 to-gray-600 bg-clip-text text-transparent">
                                {{ Auth::user()->name }}
                            </h1>
                        </div>
                    </div>

                    <div class="space-y-2 hidden md:block">
                        <p class="text-gray-600 text-sm lg:text-xs">
                            {{ $totalTasks > 0 ? __('You have :count tasks for today.', ['count' => $totalTasks]) : __('No tasks scheduled for today.') }}
                        </p>

                        @if($totalTasks == 0)
                            <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-green-50 text-green-700 text-sm rounded-full font-medium transition-all duration-300 hover:bg-green-100">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>{{ __('All tasks cleared! Enjoy your day!') }}</span>
                            </div>
                        @elseif($totalTasks < 5)
                            <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-blue-50 text-blue-700 text-sm rounded-full font-medium transition-all duration-300 hover:bg-blue-100">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                        d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                                <span>{{ __("Almost there! You're doing great!") }}</span>
                            </div>
                        @elseif($totalTasks < 10)
                            <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-amber-50 text-amber-700 text-sm rounded-full font-medium transition-all duration-300 hover:bg-amber-100">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z" />
                                </svg>
                                <span>{{ __('Keep going! Stay focused and finish strong.') }}</span>
                            </div>
                        @else
                            <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-red-50 text-red-700 text-sm rounded-full font-medium transition-all duration-300 hover:bg-red-100">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>{{ __('Busy day ahead — stay organized and take breaks!') }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                
                <!-- Right Section - Desktop View -->
                <div class="hidden md:block w-full md:w-[250px]">
                    <div class="bg-white rounded-lg border border-round p-6 text-white">
                        <div class="text-start">
                            <p id="dateHeader" class="text-blue-500 text-xs font-bold mb-2"></p>
                            <p id="dateNumber" class="text-gray-900 text-xl font-bold"></p>

                            <div class="relative overflow-hidden mt-4" style="height: 100px;">
                                <a href="{{ route('calendar.index') }}">
                                    <div id="todayEventsContainer" class="absolute inset-0 overflow-y-auto transition-all duration-500 ease-in-out">
                                    </div>
                                    
                                    <div id="upcomingEventsContainer" class="absolute inset-0 overflow-y-auto transition-all duration-500 ease-in-out hidden">
                                    </div>
                                </a>
                            </div>
                        </div>         
                    </div>
                </div>

                <!-- Mobile Button -->
                <div class="md:hidden w-full mb-3">
                    <button id="mobileEventBtn" onclick="toggleMobileEventPopup()" 
                            class="w-full bg-white border border-gray-300 rounded-lg p-3 flex items-center justify-between hover:bg-gray-50">
                        <div class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span class="text-sm text-gray-700">Events</span>
                            <span id="mobileEventCount" class="text-xs text-gray-500">Loading...</span>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                        </svg>
                    </button>
                </div>
            </div>
        
            <div class="sm:p-3">
                <div class="sm:p-7 max-w-7xl mx-auto bg-white overflow-hidden sm:border sm:border-round sm:rounded-lg">
                    <div class="text-gray-900">
                        @livewire('shipment-table')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Event Popup -->
    <div id="mobileEventPopup" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden transition-opacity duration-300">
        <div id="mobileEventContent" class="absolute bottom-0 left-0 right-0 bg-white rounded-t-3xl shadow-2xl transform translate-y-full transition-transform duration-300 max-h-[80vh] overflow-hidden">
            <!-- Handle Bar -->
            <div class="flex justify-center pt-3 pb-2">
                <div class="w-12 h-1.5 bg-gray-300 rounded-full"></div>
            </div>
            
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p id="mobileDateHeader" class="text-blue-500 text-xs font-bold mb-1"></p>
                        <h3 class="text-xl font-bold text-gray-900">Events</h3>
                    </div>
                    <button onclick="toggleMobileEventPopup()" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Content -->
            <div class="overflow-y-auto px-6 py-4" style="max-height: calc(80vh - 120px);">
                <!-- Today's Events -->
                <div class="mb-6">
                    <h4 class="text-sm font-bold text-gray-700 mb-3 flex items-center gap-2">
                        <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                        Today's Events
                    </h4>
                    <div id="mobileTodayEvents"></div>
                </div>

                <!-- Upcoming Events -->
                <div id="mobileUpcomingSection" class="hidden">
                    <h4 class="text-sm font-bold text-gray-700 mb-3 flex items-center gap-2">
                        <span class="w-2 h-2 bg-purple-500 rounded-full"></span>
                        Upcoming Events
                    </h4>
                    <div id="mobileUpcomingEvents"></div>
                </div>

                <!-- View Calendar Button -->
                <a href="{{ route('calendar.index') }}" class="block mt-6 w-full bg-blue-500 text-white text-center py-3 rounded-lg font-semibold hover:bg-blue-600 transition-colors">
                    View Full Calendar
                </a>
            </div>
        </div>
    </div>

    <script>
        let currentView = 'today';
        let autoRotateInterval = null;
        let eventsData = { today: [], upcoming: [] };

        async function fetchEvents() {
            try {
                const response = await fetch('/events');
                if (!response.ok) throw new Error('Failed to fetch events');
                const events = await response.json();
                updateSidebar(events);
                updateMobileEventButton(events);
            } catch (error) {
                console.error('Error fetching events:', error);
                showNoEvents();
            }
        }

        function getDayNameShort(date) {
            const days = ['SUN','MON','TUE','WED','THU','FRI','SAT'];
            return days[date.getDay()];
        }

        function getDayName(dateString) {
            const date = new Date(dateString);
            const days = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];
            return days[date.getDay()];
        }

        function getMonthShort(month) {
            const months = ['JAN','FEB','MAR','APR','MAY','JUN','JUL','AUG','SEP','OCT','NOV','DEC'];
            return months[month];
        }

        function formatDate(dateString) {
            const date = new Date(dateString);
            const options = { day: 'numeric', month: 'short', year: 'numeric' };
            return date.toLocaleDateString('en-US', options);
        }

        function updateDateHeader() {
            const today = new Date();
            const dayName = getDayNameShort(today);
            const monthShort = getMonthShort(today.getMonth());
            const dateText = `${dayName} | ${today.getDate()} ${monthShort}. ${today.getFullYear()}`;
            
            document.getElementById('dateHeader').textContent = dateText;
            document.getElementById('dateNumber').textContent = today.getDate();
            document.getElementById('mobileDateHeader').textContent = dateText;
        }

        function updateMobileEventButton(events) {
            const today = new Date();
            today.setHours(0,0,0,0);
            const todayCount = events.filter(e=>{
                const d=new Date(e.start); d.setHours(0,0,0,0);
                return d.getTime()===today.getTime();
            }).length;
            
            const fourDays=new Date(today); fourDays.setDate(today.getDate()+4);
            const upcomingCount=events.filter(e=>{
                const d=new Date(e.start); d.setHours(0,0,0,0);
                return d>today&&d<=fourDays;
            }).length;

            const totalCount = todayCount + upcomingCount;
            const countText = totalCount === 0 ? 'No events' : 
                            todayCount > 0 ? `${todayCount} today` + (upcomingCount > 0 ? `, ${upcomingCount} upcoming` : '') :
                            `${upcomingCount} upcoming`;
            
            document.getElementById('mobileEventCount').textContent = countText;
        }

        function showNoEvents() {
            updateDateHeader();
            document.getElementById('todayEventsContainer').innerHTML = '<p class="text-gray-400 text-sm mt-4">No events today</p>';
            document.getElementById('upcomingEventsContainer').innerHTML = '';
            document.getElementById('mobileTodayEvents').innerHTML = '<p class="text-gray-400 text-sm">No events today</p>';
            document.getElementById('mobileUpcomingSection').classList.add('hidden');
            document.getElementById('mobileEventCount').textContent = 'No events';
            if (autoRotateInterval) clearInterval(autoRotateInterval);
        }

        function updateSidebar(allEvents) {
            updateDateHeader();
            const today = new Date();
            today.setHours(0,0,0,0);
            const todayEvents = allEvents.filter(e=>{
                const d=new Date(e.start); d.setHours(0,0,0,0);
                return d.getTime()===today.getTime();
            });
            const fourDays=new Date(today); fourDays.setDate(today.getDate()+4);
            const upcoming=allEvents.filter(e=>{
                const d=new Date(e.start); d.setHours(0,0,0,0);
                return d>today&&d<=fourDays;
            }).sort((a,b)=>new Date(a.start)-new Date(b.start));

            eventsData.today=todayEvents; eventsData.upcoming=upcoming;

            // Desktop view
            const todayC=document.getElementById('todayEventsContainer');
            const upcomingC=document.getElementById('upcomingEventsContainer');

            const todayHTML = todayEvents.length?todayEvents.map(event=>{
                const end=new Date(event.end);
                const daysUntil=Math.ceil((end-today)/(1000*60*60*24));
                return `<div class="border-l border-blue-400 pl-3 mb-2">
                    <div class="flex items-center gap-2 mb-1">
                        <p class="text-xs text-gray-600">${getDayName(event.end)}, ${formatDate(event.end)}</p>
                        <span class="text-xs text-gray-500">(${daysUntil} ${daysUntil===1?'day':'days'})</span>
                    </div>
                    <div class="p-2 rounded-md hover:shadow-sm transition-shadow cursor-pointer" 
                         style="background-color:${event.color||'#E6E6FA'}"
                         onclick="openEventDetail('${event.id}')">
                        <p class="text-xs font-semibold text-gray-800">${event.title}</p>
                    </div>
                </div>`;
            }).join(''):'<p class="text-gray-400 text-sm mt-4">No events today</p>';

            todayC.innerHTML = todayHTML;

            const upcomingHTML = upcoming.length?upcoming.map(event=>{
                const d=new Date(event.start);
                const daysUntil=Math.ceil((d-today)/(1000*60*60*24));
                return `<div class="border-l border-blue-400 pl-3 mb-2">
                    <div class="flex items-center gap-2 mb-1">
                        <p class="text-xs text-gray-600">${getDayName(event.start)}, ${formatDate(event.start)}</p>
                        <span class="text-xs text-gray-500">(${daysUntil} ${daysUntil===1?'day':'days'})</span>
                    </div>
                    <div class="p-2 rounded-md hover:shadow-sm transition-shadow cursor-pointer" 
                         style="background-color:${event.color||'#E6E6FA'}"
                         onclick="openEventDetail('${event.id}')">
                        <p class="text-xs font-semibold text-gray-800">${event.title}</p>
                    </div>
                </div>`;
            }).join(''):'';

            upcomingC.innerHTML = upcomingHTML;

            // Mobile view
            document.getElementById('mobileTodayEvents').innerHTML = todayHTML;
            
            if(upcoming.length > 0) {
                document.getElementById('mobileUpcomingEvents').innerHTML = upcomingHTML;
                document.getElementById('mobileUpcomingSection').classList.remove('hidden');
                startAutoRotate();
            } else {
                document.getElementById('mobileUpcomingSection').classList.add('hidden');
            }
        }

        function toggleMobileEventPopup() {
            const popup = document.getElementById('mobileEventPopup');
            const content = document.getElementById('mobileEventContent');
            
            if (popup.classList.contains('hidden')) {
                popup.classList.remove('hidden');
                setTimeout(() => {
                    content.style.transform = 'translateY(0)';
                }, 10);
            } else {
                content.style.transform = 'translateY(100%)';
                setTimeout(() => {
                    popup.classList.add('hidden');
                }, 300);
            }
        }

        function switchView() {
            const todayC=document.getElementById('todayEventsContainer');
            const upcomingC=document.getElementById('upcomingEventsContainer');
            if(currentView==='today'&&eventsData.upcoming.length>0){
                todayC.classList.add('hidden'); upcomingC.classList.remove('hidden');
                currentView='upcoming';
            }else if(currentView==='upcoming'){
                upcomingC.classList.add('hidden'); todayC.classList.remove('hidden');
                currentView='today';
            }
        }

        function startAutoRotate(){
            if(autoRotateInterval)clearInterval(autoRotateInterval);
            if(eventsData.upcoming.length>0){
                autoRotateInterval=setInterval(()=>switchView(),10000);
            }
        }

        function openEventDetail(id){
            fetch(`/events/${id}`).then(r=>r.json()).then(event=>{
                const modal=document.getElementById('eventModal');
                const title=document.getElementById('modalTitle');
                title.textContent="Edit Event";
                document.getElementById('eventId').value=event.id;
                document.getElementById('eventTitle').value=event.title;
                document.getElementById('eventColor').value=event.color;
                document.getElementById('eventStart').value=event.start;
                document.getElementById('eventEnd').value=event.end;
                modal.classList.remove('hidden');
            });
        }

        window.refreshEventSidebar=()=>fetchEvents();
        document.addEventListener('DOMContentLoaded',()=>fetchEvents());
        window.addEventListener('beforeunload',()=>{if(autoRotateInterval)clearInterval(autoRotateInterval)});

        // Close popup when clicking outside
        document.getElementById('mobileEventPopup')?.addEventListener('click', function(e) {
            if (e.target === this) {
                toggleMobileEventPopup();
            }
        });
    </script>
</x-app-layout>