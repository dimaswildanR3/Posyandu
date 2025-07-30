<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Penimbangan Gizi</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            margin: 20px;
            color: #333;
        }
        h3 {
            text-align: center;
            border-bottom: 1px solid #ccc;
            padding-bottom: 5px;
            margin-bottom: 20px;
        }
        p {
            margin: 2px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th {
            background-color: #f0f0f0;
            font-weight: bold;
            text-align: center;
        }
        th, td {
            border: 1px solid #000;
            padding: 6px;
            vertical-align: top;
        }
        td {
            text-align: center;
        }
    </style>
</head>
<body>
    <h3>Laporan Penimbangan Gizi Balita</h3>

    <p><strong>Tahun:</strong> {{ $tahun ?? 'Semua' }}</p>
    <p><strong>Filter Status Gizi:</strong> {{ $statusGizi ?? 'Semua' }}</p>
    <p><strong>Filter Status Stunting:</strong> {{ $statusStunting ?? 'Semua' }}</p>

   @forelse ($data as $balitaId => $penimbangans)
    <h4 style="margin-top:30px;">Nama Balita: {{ $penimbangans->first()->balita->nama_balita ?? '-' }}</h4>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal Timbang</th>
                <th>BB (kg)</th>
                <th>TB (cm)</th>
                <th>Umur (bln)</th>
                <th>Z-Score</th>
                <th>Status Gizi</th>
                <th>Status Stunting</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($penimbangans as $i => $item)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal_timbang)->format('d-m-Y') }}</td>
                    <td>{{ $item->bb ?? '-' }}</td>
                    <td>{{ $item->tb ?? '-' }}</td>
                    <td>{{ $item->umur ?? '-' }}</td>
                    <td>{{ $item->z_score ?? '-' }}</td>
                    <td>{{ $item->status_gizi ?? '-' }}</td>
                    <td>{{ $item->status_stunting ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@empty
    <p style="text-align:center">Tidak ada data penimbangan ditemukan.</p>
@endforelse

</tbody>

    </table>
</body>
</html>
