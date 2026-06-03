<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\Room;
use App\Models\RoomFacility;
use App\Models\RoomImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RoomController extends Controller
{
    public function index(Property $property)
    {
        abort_if($property->user_id !== auth()->id(), 403);

        $rooms = $property->rooms()->with(['images', 'facilities'])->get();

        return view('owner.rooms.index', compact('property', 'rooms'));
    }

    public function create(Property $property)
    {
        abort_if($property->user_id !== auth()->id(), 403);

        return view('owner.rooms.create', compact('property'));
    }

    public function store(Request $request, Property $property)
    {
        abort_if($property->user_id !== auth()->id(), 403);

        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'description'    => 'nullable|string',
            'capacity'       => 'required|integer|min:1',
            'price_per_night'=> 'required|numeric|min:0',
            'weekend_price'  => 'nullable|numeric|min:0',
            'total_rooms'    => 'required|integer|min:1',
            'bed_type'       => 'nullable|string|max:100',
            'size_sqm'       => 'nullable|integer|min:1',
            'facilities'     => 'nullable|array',
            'facilities.*'   => 'string|max:100',
            'images'         => 'nullable|array',
            'images.*'       => 'image|max:2048',
        ]);

        $room = $property->rooms()->create($validated);

        // Simpan fasilitas
        if ($request->filled('facilities')) {
            foreach ($request->facilities as $facility) {
                RoomFacility::create(['room_id' => $room->id, 'name' => $facility]);
            }
        }

        // Simpan gambar
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('rooms', 'public');
                RoomImage::create([
                    'room_id'    => $room->id,
                    'path'       => $path,
                    'is_primary' => $index === 0,
                    'sort_order' => $index,
                ]);
            }
        }

        return redirect()->route('owner.properties.rooms.index', $property)
            ->with('success', 'Kamar berhasil ditambahkan.');
    }

    public function edit(Property $property, Room $room)
    {
        abort_if($property->user_id !== auth()->id(), 403);
        abort_if($room->property_id !== $property->id, 403);

        $room->load(['images', 'facilities']);

        return view('owner.rooms.edit', compact('property', 'room'));
    }

    public function update(Request $request, Property $property, Room $room)
    {
        abort_if($property->user_id !== auth()->id(), 403);
        abort_if($room->property_id !== $property->id, 403);

        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'description'    => 'nullable|string',
            'capacity'       => 'required|integer|min:1',
            'price_per_night'=> 'required|numeric|min:0',
            'weekend_price'  => 'nullable|numeric|min:0',
            'total_rooms'    => 'required|integer|min:1',
            'bed_type'       => 'nullable|string|max:100',
            'size_sqm'       => 'nullable|integer|min:1',
            'status'         => 'required|in:available,unavailable',
        ]);

        $room->update($validated);

        // Update fasilitas
        if ($request->has('facilities')) {
            $room->facilities()->delete();
            foreach ($request->facilities as $facility) {
                RoomFacility::create(['room_id' => $room->id, 'name' => $facility]);
            }
        }

        return redirect()->route('owner.properties.rooms.index', $property)
            ->with('success', 'Kamar berhasil diupdate.');
    }

    public function destroy(Property $property, Room $room)
    {
        abort_if($property->user_id !== auth()->id(), 403);
        abort_if($room->property_id !== $property->id, 403);

        // Hapus gambar dari storage
        foreach ($room->images as $image) {
            Storage::disk('public')->delete($image->path);
        }

        $room->delete();

        return redirect()->route('owner.properties.rooms.index', $property)
            ->with('success', 'Kamar berhasil dihapus.');
    }
}
