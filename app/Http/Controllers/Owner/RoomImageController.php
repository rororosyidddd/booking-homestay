<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\RoomImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RoomImageController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'images'  => 'required|array',
            'images.*'=> 'image|max:2048',
        ]);

        $room = \App\Models\Room::with('property')->findOrFail($request->room_id);
        abort_if($room->property->user_id !== auth()->id(), 403);

        $hasPrimary = $room->images()->where('is_primary', true)->exists();

        foreach ($request->file('images') as $index => $image) {
            $path = $image->store('rooms', 'public');
            RoomImage::create([
                'room_id'    => $room->id,
                'path'       => $path,
                'is_primary' => !$hasPrimary && $index === 0,
                'sort_order' => $room->images()->count() + $index,
            ]);
        }

        return back()->with('success', 'Foto berhasil diupload.');
    }

    public function setPrimary(RoomImage $roomImage)
    {
        $room = $roomImage->room()->with('property')->first();
        abort_if($room->property->user_id !== auth()->id(), 403);

        // Reset semua primary
        $room->images()->update(['is_primary' => false]);

        // Set yang dipilih jadi primary
        $roomImage->update(['is_primary' => true]);

        return back()->with('success', 'Foto utama berhasil diubah.');
    }

    public function destroy(RoomImage $roomImage)
    {
        $room = $roomImage->room()->with('property')->first();
        abort_if($room->property->user_id !== auth()->id(), 403);

        Storage::disk('public')->delete($roomImage->path);
        $roomImage->delete();

        // Kalau foto yang dihapus adalah primary, set foto pertama jadi primary
        if ($roomImage->is_primary) {
            $room->images()->first()?->update(['is_primary' => true]);
        }

        return back()->with('success', 'Foto berhasil dihapus.');
    }
}