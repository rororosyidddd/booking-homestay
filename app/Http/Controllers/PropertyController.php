<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    public function index(Request $request)
    {
        $query = Property::active()
            ->withCount('rooms')
            ->with('rooms');

        // Filter kota
        if ($request->filled('city')) {
            $query->where('city', 'like', '%' . $request->city . '%');
        }

        // Filter tipe
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $properties = $query->latest()->paginate(9)->withQueryString();

        return view('properties.index', compact('properties'));
    }

    public function show(Property $property)
    {
        abort_if($property->status !== 'active', 404);

        $property->load([
            'rooms.images',
            'rooms.facilities',
            'reviews.user',
        ]);

        return view('properties.show', compact('property'));
    }
}