<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;

class EventController extends Controller
{
    public function index()
    {
        return view('calendar');
    }

    public function getEvents()
    {
        $events = Event::all();
        return response()->json($events);
    }

    public function store(Request $request)
    {
        $event = Event::create([
        'title' => $request->title,
        'start' => $request->start,
        'end'   => $request->end,
        'color' => $request->color ?? '#3b82f6',
    ]);

    return response()->json(['message' => 'Event created successfully']);
    }

    public function update(Request $request, $id)
    {
        $event = Event::find($id);
        if ($event) {
            $event->update($request->all());
            return response()->json(['message' => 'Event updated successfully']);
        }
        return response()->json(['message' => 'Event not found'], 404);
    }

    public function destroy($id)
    {
        $event = Event::find($id);
        if ($event) {
            $event->delete();
            return response()->json(['message' => 'Event deleted successfully']);
        }
        return response()->json(['message' => 'Event not found'], 404);
    }
}
