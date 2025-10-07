<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::query();

        // Filtriranje po lokaciji
        if ($request->has('location')) {
            $query->where('location', 'like', '%' . $request->location . '%');
        }

        // Filtriranje po minimalnoj ceni
        if ($request->has('price_min')) {
            $query->where('price', '>=', $request->price_min);
        }

        // Filtriranje po maksimalnoj ceni
        if ($request->has('price_max')) {
            $query->where('price', '<=', $request->price_max);
        }

        // Pretraga po nazivu
        if ($request->has('search')) {
           $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Sortiranje (npr. /events?sort=date ili ?sort=price)
        if ($request->has('sort')) {
            $sortField = $request->sort;
            $sortOrder = $request->get('order', 'asc'); // default asc
            if (in_array($sortField, ['name', 'date', 'price'])) {
                $query->orderBy($sortField, $sortOrder);
            }
        }

        // Paginacija (default 10 po strani, ili ?per_page=5)
        $perPage = $request->get('per_page', 10);
        $events = $query->paginate($perPage);

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
