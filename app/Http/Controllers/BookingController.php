<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Event;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index()
    {
        
        $bookings = Booking::latest()->get();
        $totalBookings = Booking::count();
        
        return view('bookings.index', compact('bookings', 'totalBookings'));
    }

    public function create()
{
    return view('bookings.index');
}

    public function store(Request $request)
    {
        $validated = $request->validate([
            'shipper_name' => 'required|string|max:255',
            'vessel_name' => 'nullable|string|max:255',
            'status' => 'required|in:Pending,Confirmed,Sailed,Delivered',
            'booking_reference' => 'nullable|string|max:255',
            'teu' => 'nullable|integer',
            'volume' => 'nullable|integer',
            'container_damage_assessment' => 'nullable|string|max:255',
            'vsl_date' => 'nullable|date',
            'port_of_discharge' => 'nullable|string|max:255',
            'vol_40ft' => 'nullable|integer',
            'hq_20ft' => 'nullable|integer',
            'remarks' => 'nullable|string',
        ]);

        // Generate booking number
        $bookingNo = 'BK-' . date('Ymd') . '-' . str_pad(Booking::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);
        $validated['booking_no'] = $bookingNo;

        $booking = Booking::create($validated);

        // AUTO CREATE EVENT IN CALENDAR
        $this->createCalendarEvent($booking);

        return redirect()->route('bookings.index')->with('success', 'Booking created successfully!');
    }

    public function update(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'shipper_name' => 'required|string|max:255',
            'vessel_name' => 'nullable|string|max:255',
            'status' => 'required|in:Pending,Confirmed,Sailed,Delayed,Delivered',
            'booking_reference' => 'nullable|string|max:255',
            'teu' => 'nullable|integer',
            'volume' => 'nullable|integer',
            'container_damage_assessment' => 'nullable|string|max:255',
            'vsl_date' => 'nullable|date',
            'port_of_discharge' => 'nullable|string|max:255',
            'vol_40ft' => 'nullable|integer',
            'hq_20ft' => 'nullable|integer',
            'remarks' => 'nullable|string',
        ]);

        $booking->update($validated);

        // UPDATE CALENDAR EVENT
        $this->updateCalendarEvent($booking);

        return redirect()->route('bookings.index')->with('success', 'Booking updated successfully!');
    }

    public function destroy(Booking $booking)
    {
        // DELETE CALENDAR EVENT - cari berdasarkan booking_no
        Event::where('title', 'like', $booking->booking_no . '%')->delete();

        $booking->delete();

        return redirect()->route('bookings.index')->with('success', 'Booking deleted successfully!');
    }

    /**
     * AUTO CREATE CALENDAR EVENT WHEN BOOKING IS CREATED
     */
    private function createCalendarEvent(Booking $booking)
    {
        // Jika ada vsl_date, guna vsl_date. Kalau tidak, guna created_at
        $startDate = $booking->vsl_date ?? $booking->created_at->toDateString();
        $endDate = $startDate;

        // Color based on booking status
        $colorMap = [
            'Pending' => '#FFF0F5',      // Merah Lembut
            'Confirmed' => '#F0FFF4',    // Hijau Lembut
            'Sailed' => '#E6E6FA',       // Ungu Lembut
            'Delayed' => '#FFF0F5',
            'Delivered' => '#E6E6FA'     // Ungu Lembut
        ];

        $color = $colorMap[$booking->status] ?? '#E6E6FA';

        // Event title format: BK-XXXX - Shipper Name
        $eventTitle = "{$booking->booking_no} - {$booking->shipper_name}";

        // Create event
        Event::create([
            'title' => $eventTitle,
            'start' => $startDate,
            'end' => $endDate,
            'color' => $color,
        ]);
    }

    /**
     * UPDATE CALENDAR EVENT WHEN BOOKING IS UPDATED
     */
    private function updateCalendarEvent(Booking $booking)
    {
        // Cari event berdasarkan booking number di title
        $event = Event::where('title', 'like', $booking->booking_no . '%')->first();

        if ($event) {
            // Update event details
            $startDate = $booking->vsl_date ?? $booking->created_at->toDateString();
            $endDate = $startDate;

            $colorMap = [
                'Pending' => '#FFF0F5',
                'Confirmed' => '#F0FFF4',
                'Sailed' => '#E6E6FA',
                'Delivered' => '#E6E6FA'
            ];

            $color = $colorMap[$booking->status] ?? '#E6E6FA';
            $eventTitle = "{$booking->booking_no} - {$booking->shipper_name}";

            $event->update([
                'title' => $eventTitle,
                'start' => $startDate,
                'end' => $endDate,
                'color' => $color,
            ]);
        } else {
            // Kalau event tak ada, create baru
            $this->createCalendarEvent($booking);
        }
    }
}