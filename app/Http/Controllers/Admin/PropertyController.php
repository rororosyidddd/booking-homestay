<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Property;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    public function index(Request $request)
    {
        $query = Property::with('owner')->withCount('rooms');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $properties = $query->latest()->paginate(15);

        return view('admin.properties.index', compact('properties'));
    }

    public function show(Property $property)
    {
        $property->load(['owner', 'rooms', 'reviews.user']);

        return view('admin.properties.show', compact('property'));
    }

    public function approve(Property $property)
    {
        $property->update(['status' => 'active']);

        return back()->with('success', 'Properti berhasil disetujui.');
    }

    public function reject(Property $property)
    {
        $property->update(['status' => 'inactive']);

        return back()->with('success', 'Properti berhasil ditolak.');
    }

    public function destroy(Property $property)
    {
        $property->delete();

        return redirect()->route('admin.properties.index')
            ->with('success', 'Properti berhasil dihapus.');
    }
}