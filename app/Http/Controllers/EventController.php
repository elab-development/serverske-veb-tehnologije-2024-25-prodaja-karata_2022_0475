<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::query();

        if ($request->has('location')) {
            $query->where('location', 'like', '%' . $request->location . '%');
        }

        if ($request->has('price_min')) {
            $query->where('price', '>=', $request->price_min);
        }

        if ($request->has('price_max')) {
            $query->where('price', '<=', $request->price_max);
        }

        if ($request->has('search')) {
           $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->has('sort')) {
            $sortField = $request->sort;
            $sortOrder = $request->get('order', 'asc');
            if (in_array($sortField, ['name', 'date', 'price'])) {
                $query->orderBy($sortField, $sortOrder);
            }
        }

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
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'category' => 'nullable|string',
            ]);

            $data = $request->all();

            // Upload fajla ako postoji
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $filename = Str::slug($request->name) . '_' . time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('events', $filename, 'public');
                $data['image'] = $path;
            }

            $event = Event::create($data);

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

        $request->validate([
            'name' => 'sometimes|required|string',
            'date' => 'sometimes|required|date',
            'location' => 'sometimes|required|string',
            'price' => 'sometimes|required|numeric',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'category' => 'nullable|string',
        ]);

        $data = $request->all();

        // Upload fajla ako postoji
        if ($request->hasFile('image')) {
            // Brisanje stare slike ako postoji
            if ($event->image && Storage::disk('public')->exists($event->image)) {
                Storage::disk('public')->delete($event->image);
            }

            $file = $request->file('image');
            $filename = Str::slug($request->name ?? $event->name) . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('events', $filename, 'public');
            $data['image'] = $path;
        }

        $event->update($data);

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

        // Brisanje slike iz storage-a ako postoji
        if ($event->image && Storage::disk('public')->exists($event->image)) {
            Storage::disk('public')->delete($event->image);
        }

        $event->delete();
        return response()->json(['message' => 'Event deleted'], 200);
    }
}
