<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Musyrif;

class MusyrifController extends Controller
{
    /**
     * Store a newly created musyrif in database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:musyrifs,nama'
        ]);

        $musyrif = Musyrif::create([
            'nama' => $request->nama
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Musyrif berhasil ditambahkan',
            'data' => $musyrif
        ]);
    }

    /**
     * Update the specified musyrif in database.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:musyrifs,nama,' . $id
        ]);

        $musyrif = Musyrif::findOrFail($id);
        $musyrif->update([
            'nama' => $request->nama
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Musyrif berhasil diupdate',
            'data' => $musyrif
        ]);
    }

    /**
     * Remove the specified musyrif from database.
     */
    public function destroy($id)
    {
        $musyrif = Musyrif::findOrFail($id);
        $musyrif->delete();

        return response()->json([
            'success' => true,
            'message' => 'Musyrif berhasil dihapus'
        ]);
    }
}
