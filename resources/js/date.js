function updateDateTime() {
        const now = new Date();

        // 1. Update Day Name (e.g., Wednesday,). Menggunakan 'en-US'
        const optionsDay = { weekday: 'long' };
        const dayName = now.toLocaleDateString('en-US', optionsDay); 
        const dayNameElement = document.getElementById('current-day-name');
        if (dayNameElement) {
            dayNameElement.textContent = dayName + ',';
        }

        // 2. Update Current Time 
        const optionsTime = { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true };
        const currentTime = now.toLocaleTimeString('en-US', optionsTime);
        const timeElement = document.getElementById('current-time');
        if (timeElement) {
            timeElement.textContent = currentTime;
        }
        
        // 3. Update Day Number, Month, and Year (COMBINED)
        // Format: Day Number (DD) Month (Long) Year (YYYY)
        const dayNumber = now.getDate();
        const optionsMonthYear = { month: 'long', year: 'numeric' };
        const monthYear = now.toLocaleDateString('en-US', optionsMonthYear);
        
        const fullDateString = `${dayNumber} ${monthYear}`; // e.g., "8 October 2025"
        
        const fullDateElement = document.getElementById('current-day-month-year');
        if (fullDateElement) {
            fullDateElement.textContent = fullDateString;
        } else {
            console.error('Element ID current-day-month-year not found.');
        }
    }

    // Panggil fungsi segera apabila DOM (struktur HTML) dimuatkan
    document.addEventListener('DOMContentLoaded', updateDateTime);
    
    // Panggil fungsi setiap 1000ms (1 saat) untuk kemas kini jam real-time
    setInterval(updateDateTime, 1000);