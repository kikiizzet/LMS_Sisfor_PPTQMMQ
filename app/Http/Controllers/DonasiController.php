<?php

namespace App\Http\Controllers;

use App\Models\Donasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DonasiController extends Controller
{
    public function index()
    {
        $donasis = Donasi::latest()->get();
        return view('admin.donasi.index', compact('donasis'));
    }

    public function create()
    {
        return view('admin.donasi.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('donasis', 'public');
        }

        $data['is_active'] = $request->has('is_active') ? true : false;

        Donasi::create($data);

        return redirect()->route('admin.donasi.index')->with('success', 'Poster donasi berhasil ditambahkan!');
    }

    public function edit(Donasi $donasi)
    {
        return view('admin.donasi.edit', compact('donasi'));
    }

    public function update(Request $request, Donasi $donasi)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            // Delete old image
            if ($donasi->image) {
                Storage::disk('public')->delete($donasi->image);
            }
            $data['image'] = $request->file('image')->store('donasis', 'public');
        }

        $data['is_active'] = $request->has('is_active') ? true : false;

        $donasi->update($data);

        return redirect()->route('admin.donasi.index')->with('success', 'Poster donasi berhasil diperbarui!');
    }

    public function destroy(Donasi $donasi)
    {
        if ($donasi->image) {
            Storage::disk('public')->delete($donasi->image);
        }
        $donasi->delete();

        return redirect()->route('admin.donasi.index')->with('success', 'Poster donasi berhasil dihapus!');
    }
}
