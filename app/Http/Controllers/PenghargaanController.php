<?php

namespace App\Http\Controllers;

use App\Models\Penghargaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PenghargaanController extends Controller
{
    public function index()
    {
        $penghargaans = Penghargaan::latest()->get();
        return view('admin.penghargaan.index', compact('penghargaans'));
    }

    public function create()
    {
        return view('admin.penghargaan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('penghargaans', 'public');
        }

        $data['is_active'] = $request->has('is_active') ? true : false;

        Penghargaan::create($data);

        return redirect()->route('admin.penghargaan.index')->with('success', 'Penghargaan berhasil ditambahkan!');
    }

    public function edit(Penghargaan $penghargaan)
    {
        return view('admin.penghargaan.edit', compact('penghargaan'));
    }

    public function update(Request $request, Penghargaan $penghargaan)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            // Delete old image
            if ($penghargaan->image) {
                Storage::disk('public')->delete($penghargaan->image);
            }
            $data['image'] = $request->file('image')->store('penghargaans', 'public');
        }

        $data['is_active'] = $request->has('is_active') ? true : false;

        $penghargaan->update($data);

        return redirect()->route('admin.penghargaan.index')->with('success', 'Penghargaan berhasil diperbarui!');
    }

    public function destroy(Penghargaan $penghargaan)
    {
        if ($penghargaan->image) {
            Storage::disk('public')->delete($penghargaan->image);
        }
        $penghargaan->delete();

        return redirect()->route('admin.penghargaan.index')->with('success', 'Penghargaan berhasil dihapus!');
    }
}
