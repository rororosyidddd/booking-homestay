<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Property;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    public function index()
    {
        $properties = Property::where('user_id', auth()->id())
            ->withCount('rooms')
            ->latest()
            ->paginate(10);

        return view('owner.properties.index', compact('properties'));
    }

    public function create()
    {
        return view('owner.properties.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'description'    => 'nullable|string',
            'address'        => 'required|string',
            'city'           => 'required|string|max:100',
            'province'       => 'required|string|max:100',
            'postal_code'    => 'nullable|string|max:10',
            'type'           => 'required|in:hotel,villa,guest_house,kost,resort,apartment',
            'phone'          => 'nullable|string|max:20',
            'email'          => 'nullable|email',
            'check_in_time'  => 'required|string',
            'check_out_time' => 'required|string',
            'cover_image' => 'nullable|image|max:2048',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['status']  = 'pending';

        if ($request->hasFile('cover_image')) {
        $validated['cover_image'] = $request->file('cover_image')->store('properties', 'public');
}

        Property::create($validated);

        return redirect()->route('owner.properties.index')
            ->with('success', 'Properti berhasil ditambahkan, menunggu persetujuan admin.');
    }

    public function show(Property $property)
    {
        $this->authorizeOwner($property);

        $property->load(['rooms.images', 'rooms.facilities', 'reviews.user']);

        return view('owner.properties.show', compact('property'));
    }

    public function edit(Property $property)
    {
        $this->authorizeOwner($property);

        return view('owner.properties.edit', compact('property'));
    }

    public function update(Request $request, Property $property)
    {
        $this->authorizeOwner($property);

        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'description'    => 'nullable|string',
            'address'        => 'required|string',
            'city'           => 'required|string|max:100',
            'province'       => 'required|string|max:100',
            'postal_code'    => 'nullable|string|max:10',
            'type'           => 'required|in:hotel,villa,guest_house,kost,resort,apartment',
            'phone'          => 'nullable|string|max:20',
            'email'          => 'nullable|email',
            'check_in_time'  => 'required|string',
            'check_out_time' => 'required|string',
            'cover_image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('cover_image')) {
        // Hapus foto lama
        if ($property->cover_image) {
        \Illuminate\Support\Facades\Storage::disk('public')->delete($property->cover_image);
        }
        $validated['cover_image'] = $request->file('cover_image')->store('properties', 'public');
        }

        $property->update($validated);

        return redirect()->route('owner.properties.show', $property)
            ->with('success', 'Properti berhasil diupdate.');
    }

    public function destroy(Property $property)
    {
        $this->authorizeOwner($property);

        $property->delete();

        return redirect()->route('owner.properties.index')
            ->with('success', 'Properti berhasil dihapus.');
    }

    // Pastikan owner hanya bisa akses properti miliknya
    private function authorizeOwner(Property $property): void
    {
        abort_if($property->user_id !== auth()->id(), 403);
    }
}
