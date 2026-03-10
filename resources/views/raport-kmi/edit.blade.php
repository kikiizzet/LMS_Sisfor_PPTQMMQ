@extends('layout')

@section('content')
<div class="container mx-auto p-6">
    <div class="flex items-center space-x-4 mb-6">
        <a href="{{ route('raport-kmi.index') }}" class="text-gray-600 hover:text-gray-900">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <h1 class="text-2xl font-bold">Edit Raport KMI</h1>
    </div>

    <form action="{{ route('raport-kmi.update', $raport->id) }}" method="POST" class="space-y-8">
        @csrf
        @method('PUT')
        
        <!-- Section 1: Data Santri -->
        <div class="bg-white p-6 rounded-xl shadow-md">
            <h2 class="text-lg font-semibold mb-4 border-b pb-2">Data Santri & Informasi Umum</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nama Santri</label>
                    <input type="text" name="nama_santri" value="{{ $raport->nama_santri }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">No. Induk</label>
                    <input type="text" name="no_induk" value="{{ $raport->no_induk }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Kelas</label>
                    <input type="text" name="kelas" value="{{ $raport->kelas }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Semester</label>
                    <select name="semester" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="Ganjil" {{ $raport->semester == 'Ganjil' ? 'selected' : '' }}>Ganjil</option>
                        <option value="Genap" {{ $raport->semester == 'Genap' ? 'selected' : '' }}>Genap</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tahun Pelajaran</label>
                    <input type="text" name="tahun_pelajaran" value="{{ $raport->tahun_pelajaran }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tempat, Tanggal Cetak</label>
                    <input type="text" name="tempat_tanggal_cetak" value="{{ $raport->tempat_tanggal_cetak }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
            </div>
        </div>

        <!-- Section 2: Nilai Mapel -->
        @php
            $subjectsA = [
                'nahwu' => 'Nahwu', 'mutholaah' => 'Mutholaah', 'shorof' => 'Shorof', 'mahfudzat' => 'Mahfudzat',
                'reading' => 'Reading', 'dictation' => 'Dictation', 'mufrodzat' => 'Mufrodzat', 'vocabularies' => 'Vocabularies',
                'akhlak_lil_banin' => 'Akhlak Lil Banin', 'durusuttafsir' => 'Durusuttafsir', 'ushul_fiqh' => 'Ushul Fiqh', 'arbain_nawawi' => 'Arba\'in Nawawi'
            ];
            $subjectsB = [
                'bahasa_inggris' => 'Bahasa Inggris', 'bahasa_arab' => 'Bahasa Arab', 'praktik_ibadah' => 'Praktik Ibadah'
            ];
        @endphp

        <div class="bg-white p-6 rounded-xl shadow-md overflow-x-auto">
            <h2 class="text-lg font-semibold mb-4 border-b pb-2">Nilai Mata Pelajaran</h2>
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="p-2 border">Mata Pelajaran</th>
                        <th class="p-2 border text-xs">KKM</th>
                        <th class="p-2 border text-xs" colspan="2">Pengetahuan (Angka/Huruf)</th>
                        <th class="p-2 border text-xs" colspan="2">Keterampilan (Angka/Huruf)</th>
                        <th class="p-2 border text-xs" colspan="2">Sikap (Angka/Huruf)</th>
                        <th class="p-2 border text-xs">Ketercapaian</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="bg-gray-100"><td colspan="9" class="p-2 font-bold">Kelompok A</td></tr>
                    @foreach($subjectsA as $key => $name)
                    @php $val = $raport->nilai_mapel[$key] ?? []; @endphp
                    <tr>
                        <td class="p-2 border font-medium">{{ $name }}</td>
                        <td class="p-2 border"><input type="number" name="nilai_mapel[{{ $key }}][kkm]" value="{{ $val['kkm'] ?? 65 }}" class="w-12 p-1 border rounded"></td>
                        <td class="p-2 border"><input type="number" name="nilai_mapel[{{ $key }}][p_a]" value="{{ $val['p_a'] ?? '' }}" class="w-12 p-1 border rounded"></td>
                        <td class="p-2 border"><input type="text" name="nilai_mapel[{{ $key }}][p_h]" value="{{ $val['p_h'] ?? '' }}" class="w-24 p-1 border rounded text-xs"></td>
                        <td class="p-2 border"><input type="number" name="nilai_mapel[{{ $key }}][k_a]" value="{{ $val['k_a'] ?? '' }}" class="w-12 p-1 border rounded"></td>
                        <td class="p-2 border"><input type="text" name="nilai_mapel[{{ $key }}][k_h]" value="{{ $val['k_h'] ?? '' }}" class="w-24 p-1 border rounded text-xs"></td>
                        <td class="p-2 border"><input type="number" name="nilai_mapel[{{ $key }}][s_a]" value="{{ $val['s_a'] ?? '' }}" class="w-12 p-1 border rounded"></td>
                        <td class="p-2 border"><input type="text" name="nilai_mapel[{{ $key }}][s_h]" value="{{ $val['s_h'] ?? '' }}" class="w-24 p-1 border rounded text-xs"></td>
                        <td class="p-2 border"><input type="text" name="nilai_mapel[{{ $key }}][ketercapaian]" value="{{ $val['ketercapaian'] ?? 'Melampaui' }}" class="w-24 p-1 border rounded text-xs"></td>
                    </tr>
                    @endforeach
                    <tr class="bg-gray-100"><td colspan="9" class="p-2 font-bold">Kelompok B</td></tr>
                    @foreach($subjectsB as $key => $name)
                    @php $val = $raport->nilai_mapel[$key] ?? []; @endphp
                    <tr>
                        <td class="p-2 border font-medium">{{ $name }}</td>
                        <td class="p-2 border"><input type="number" name="nilai_mapel[{{ $key }}][kkm]" value="{{ $val['kkm'] ?? 65 }}" class="w-12 p-1 border rounded"></td>
                        <td class="p-2 border"><input type="number" name="nilai_mapel[{{ $key }}][p_a]" value="{{ $val['p_a'] ?? '' }}" class="w-12 p-1 border rounded"></td>
                        <td class="p-2 border"><input type="text" name="nilai_mapel[{{ $key }}][p_h]" value="{{ $val['p_h'] ?? '' }}" class="w-24 p-1 border rounded text-xs"></td>
                        <td class="p-2 border"><input type="number" name="nilai_mapel[{{ $key }}][k_a]" value="{{ $val['k_a'] ?? '' }}" class="w-12 p-1 border rounded"></td>
                        <td class="p-2 border"><input type="text" name="nilai_mapel[{{ $key }}][k_h]" value="{{ $val['k_h'] ?? '' }}" class="w-24 p-1 border rounded text-xs"></td>
                        <td class="p-2 border"><input type="number" name="nilai_mapel[{{ $key }}][s_a]" value="{{ $val['s_a'] ?? '' }}" class="w-12 p-1 border rounded"></td>
                        <td class="p-2 border"><input type="text" name="nilai_mapel[{{ $key }}][s_h]" value="{{ $val['s_h'] ?? '' }}" class="w-24 p-1 border rounded text-xs"></td>
                        <td class="p-2 border"><input type="text" name="nilai_mapel[{{ $key }}][ketercapaian]" value="{{ $val['ketercapaian'] ?? 'Melampaui' }}" class="w-24 p-1 border rounded text-xs"></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Section 3: Ekskul & Absensi & Mental -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="bg-white p-6 rounded-xl shadow-md space-y-4">
                <h2 class="text-lg font-semibold border-b pb-2">Ekstrakurikuler</h2>
                @php $eks = $raport->ekstrakurikuler ?? []; @endphp
                @for($i = 0; $i < 3; $i++)
                <div class="flex space-x-2">
                    <input type="text" name="ekstrakurikuler[{{ $i }}][nama]" value="{{ $eks[$i]['nama'] ?? '' }}" placeholder="Nama Ekskul" class="w-2/3 p-2 border rounded">
                    <input type="number" name="ekstrakurikuler[{{ $i }}][nilai]" value="{{ $eks[$i]['nilai'] ?? '' }}" placeholder="Nilai" class="w-1/3 p-2 border rounded">
                </div>
                @endfor
                
                <h2 class="text-lg font-semibold border-b pb-2 mt-6">Ketidakhadiran</h2>
                <div class="grid grid-cols-3 gap-2">
                    <div>
                        <label class="text-xs">Sakit</label>
                        <input type="number" name="sakit" value="{{ $raport->sakit }}" class="w-full p-2 border rounded">
                    </div>
                    <div>
                        <label class="text-xs">Ijin</label>
                        <input type="number" name="izin" value="{{ $raport->izin }}" class="w-full p-2 border rounded">
                    </div>
                    <div>
                        <label class="text-xs">Ghoib</label>
                        <input type="number" name="ghoib" value="{{ $raport->ghoib }}" class="w-full p-2 border rounded">
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-md space-y-4">
                <h2 class="text-lg font-semibold border-b pb-2">Mental / Karakter</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs">Moral</label>
                        <input type="text" name="mental_moral" value="{{ $raport->mental_moral }}" class="w-full p-2 border rounded">
                    </div>
                    <div>
                        <label class="text-xs">Kedisiplinan</label>
                        <input type="text" name="mental_kedisiplinan" value="{{ $raport->mental_kedisiplinan }}" class="w-full p-2 border rounded">
                    </div>
                    <div>
                        <label class="text-xs">Kerajinan</label>
                        <input type="text" name="mental_kerajinan" value="{{ $raport->mental_kerajinan }}" class="w-full p-2 border rounded">
                    </div>
                    <div>
                        <label class="text-xs">Kebersihan</label>
                        <input type="text" name="mental_kebersihan" value="{{ $raport->mental_kebersihan }}" class="w-full p-2 border rounded">
                    </div>
                </div>
                
                <h2 class="text-lg font-semibold border-b pb-2 mt-6">Tanda Tangan & Catatan</h2>
                <div>
                    <label class="text-xs">Catatan Wali Kelas</label>
                    <textarea name="catatan_wali_kelas" class="w-full p-2 border rounded" rows="3">{{ $raport->catatan_wali_kelas }}</textarea>
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <input type="text" name="wali_kelas_nama" value="{{ $raport->wali_kelas_nama }}" placeholder="Nama Wali Kelas" required class="p-2 border rounded text-sm">
                    <input type="text" name="kepala_madrasah_nama" value="{{ $raport->kepala_madrasah_nama }}" placeholder="Nama Kepala Madrasah" required class="p-2 border rounded text-sm">
                </div>
            </div>
        </div>

        <div class="flex justify-end space-x-4 pb-12">
            <button type="submit" class="px-6 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700">Perbarui Raport</button>
        </div>
    </form>
</div>
@endsection
