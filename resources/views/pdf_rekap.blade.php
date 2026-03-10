<!DOCTYPE html>
<html>
<head>
    <title>Rekap Nilai Santri</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: center; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .header { text-align: center; margin-bottom: 10px; }
        .rank-1 { background-color: #fff9c4; font-weight: bold; } /* Highlight Juara 1 */
    </style>
</head>
<body>
<div class="header">
    <h2 style="margin: 0;">REKAPITULASI PERINGKAT NILAI SANTRI MMQ</h2>
    <p style="margin: 5px 0;">
        Berdasarkan Peringkat: 
        <strong>
            @if($kategori == 'rata_rata') Nilai Keseluruhan (Overall) 
            @else Nilai {{ ucfirst($kategori) }} 
            @endif
        </strong>
    </p>
</div>

    <table>
        <thead>
            <tr>
                <th>RANK</th>
                <th>NAMA SANTRI</th>
                <th>MUSYRIF</th>
                <th>TAJWID</th>
                <th>ADAB</th>
                <th>KELANCARAN</th>
                <th>FASHAHAH</th>
                <th>RATA-RATA</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data_rapor as $index => $r)
            <tr class="{{ $index == 0 ? 'rank-1' : '' }}">
                <td>#{{ $index + 1 }}</td>
                <td style="text-align: left;">{{ $r->nama_santri }}</td>
                <td>{{ $r->musyrif }}</td>
                <td>{{ $r->tajwid }}</td>
                <td>{{ $r->adab }}</td>
                <td>{{ $r->kelancaran }}</td>
                <td>{{ $r->fashahah }}</td>
                <td><strong>{{ number_format(($r->adab + $r->kelancaran + $r->tajwid + $r->fashahah) / 4, 1) }}</strong></td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>