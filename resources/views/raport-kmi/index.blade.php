@extends('layout')

@section('content')
<div class="container mx-auto p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Daftar Raport KMI</h1>
        <a href="{{ route('raport-kmi.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
            + Tambah Raport Baru
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg overflow-hidden transition-colors duration-3000">
        <table class="w-full text-left">
            <thead class="bg-gray-50 dark:bg-slate-700 border-b dark:border-slate-600">
                <tr>
                    <th class="px-6 py-4 font-semibold text-gray-700 dark:text-gray-200">Nama Santri</th>
                    <th class="px-6 py-4 font-semibold text-gray-700 dark:text-gray-200">Kelas</th>
                    <th class="px-6 py-4 font-semibold text-gray-700 dark:text-gray-200">Semester</th>
                    <th class="px-6 py-4 font-semibold text-gray-700 dark:text-gray-200">Tahun Pelajaran</th>
                    <th class="px-6 py-4 font-semibold text-gray-700 dark:text-gray-200 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y dark:divide-slate-600">
                @forelse($raport_kmis as $raport)
                <tr class="hover:bg-gray-50 dark:hover:bg-slate-700 transition">
                    <td class="px-6 py-4 font-bold text-gray-800 dark:text-white">{{ $raport->nama_santri }}</td>
                    <td class="px-6 py-4 dark:text-gray-300">{{ $raport->kelas }}</td>
                    <td class="px-6 py-4 dark:text-gray-300">{{ $raport->semester }}</td>
                    <td class="px-6 py-4 dark:text-gray-300">{{ $raport->tahun_pelajaran }}</td>
                    <td class="px-6 py-4">
                        <div class="flex justify-center space-x-2">
                            <a href="{{ route('raport-kmi.cetak', $raport->id) }}" target="_blank" class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600 text-sm">
                                Cetak
                            </a>
                            <a href="{{ route('raport-kmi.edit', $raport->id) }}" class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600 text-sm">
                                Edit
                            </a>
                            <form action="{{ route('raport-kmi.destroy', $raport->id) }}" method="POST" onsubmit="return confirm('Hapus data ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 text-sm">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">Belum ada data raport KMI.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
