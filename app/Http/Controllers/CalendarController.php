<?php

namespace App\Http\Controllers;

use App\Models\CalendarEvent;
use App\Models\Tutor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CalendarController extends Controller
{
    private function tutor()
    {
        return Tutor::where('tutor_id', Auth::id())->firstOrFail();
    }

    public function index()
    {
        return view('calendar.index');
    }

    public function events(Request $request)
    {
        $tutor = $this->tutor();
        $events = CalendarEvent::where('tutor_id', $tutor->tutor_id)
            ->when($request->start, fn($q) => $q->where('event_date', '>=', $request->start))
            ->when($request->end,   fn($q) => $q->where('event_date', '<=', $request->end))
            ->get()
            ->map(fn($e) => [
                'id'    => $e->id,
                'title' => $e->title,
                'start' => $e->event_date->format('Y-m-d') . ($e->start_time ? 'T' . $e->start_time : ''),
                'end'   => $e->event_date->format('Y-m-d') . ($e->end_time   ? 'T' . $e->end_time   : ''),
                'extendedProps' => ['notes' => $e->notes],
            ]);

        return response()->json($events);
    }

    public function store(Request $request)
    {
        $tutor = $this->tutor();
        $validated = $request->validate([
            'title'      => 'required|string|max:255',
            'event_date' => 'required|date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time'   => 'nullable|date_format:H:i|after:start_time',
            'notes'      => 'nullable|string',
        ]);

        $event = CalendarEvent::create(array_merge($validated, ['tutor_id' => $tutor->tutor_id]));

        return response()->json($event, 201);
    }

    public function update(Request $request, CalendarEvent $event)
    {
        $tutor = $this->tutor();
        abort_if($event->tutor_id !== $tutor->tutor_id, 403);

        $validated = $request->validate([
            'title'      => 'sometimes|string|max:255',
            'event_date' => 'sometimes|date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time'   => 'nullable|date_format:H:i',
            'notes'      => 'nullable|string',
        ]);

        $event->update($validated);

        return response()->json($event);
    }

    public function destroy(CalendarEvent $event)
    {
        $tutor = $this->tutor();
        abort_if($event->tutor_id !== $tutor->tutor_id, 403);
        $event->delete();

        return response()->json(['deleted' => true]);
    }
}
