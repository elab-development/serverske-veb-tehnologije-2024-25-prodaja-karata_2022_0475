<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::all();
        return response()->json($events, 200);
    }

    // Kreiranje novog događaja (samo admin)
    public function store(Request $request)
    {

        if (!auth()->user() || !auth()->user()->isAdmin()) {
            return response()->json(['message' => 'Niste ovlašćeni!'], 403);
        }

        try {
            $request->validate([
                'name' => 'required|string',
                'date' => 'required|date',
                'location' => 'required|string',
                'price' => 'required|numeric',
                'description' => 'nullable|string',
            ]);

            $event = Event::create($request->all());

            return response()->json($event, 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Greška prilikom kreiranja eventa',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $event = Event::find($id);
        if (!$event) {
            return response()->json(['message' => 'Event not found'], 404);
        }
        return response()->json($event, 200);
    }

    public function update(Request $request, $id)
    {
        if (!auth()->user() || !auth()->user()->isAdmin()) {
            return response()->json(['message' => 'Niste ovlašćeni!'], 403);
        }

        $event = Event::find($id);
        if (!$event) {
            return response()->json(['message' => 'Event not found'], 404);
        }

        $event->update($request->all());
        return response()->json($event, 200);
    }

    public function destroy($id)
    {
        if (!auth()->user() || !auth()->user()->isAdmin()) {
            return response()->json(['message' => 'Niste ovlašćeni!'], 403);
        }

        $event = Event::find($id);
        if (!$event) {
            return response()->json(['message' => 'Event not found'], 404);
        }

        $event->delete();
        return response()->json(['message' => 'Event deleted'], 200);
    }
}
