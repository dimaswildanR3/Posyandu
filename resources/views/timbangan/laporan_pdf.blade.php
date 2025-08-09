<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Penimbangan Gizi</title>
    <style>
@page {
    size: A4 landscape;
    margin: 10mm 15mm;
}

body {
    font-family: DejaVu Sans, sans-serif;
    font-size: 11px;
    margin: 0;
    color: #333;
}

table {
    width: 100%;
    border-collapse: collapse;
    table-layout: fixed;
    word-wrap: break-word;
}

th, td {
    border: 1px solid #000;
    padding: 4px 5px;
    vertical-align: top;
    text-align: center;
    overflow-wrap: break-word;
}

/* Atur lebar kolom, sesuaikan */
th:nth-child(1), td:nth-child(1) { width: 3%; }   /* No */
th:nth-child(2), td:nth-child(2) { width: 12%; }  /* Nama Balita */
th:nth-child(3), td:nth-child(3) { width: 6%; }   /* JK */
th:nth-child(4), td:nth-child(4) { width: 8%; }   /* Tgl Lahir */
th:nth-child(5), td:nth-child(5) { width: 10%; }  /* Nama Ibu */
th:nth-child(6), td:nth-child(6) { width: 5%; }   /* Usia */
th:nth-child(7), td:nth-child(7) { width: 5%; }   /* Berat */
th:nth-child(8), td:nth-child(8) { width: 5%; }   /* Tinggi */
th:nth-child(9), td:nth-child(9) { width: 6%; }   /* Median BB/U */
th:nth-child(10), td:nth-child(10) { width: 6%; } /* SD BB/U */
th:nth-child(11), td:nth-child(11) { width: 6%; } /* Z-Score BB/U */
th:nth-child(12), td:nth-child(12) { width: 7%; } /* Status Gizi */
th:nth-child(13), td:nth-child(13) { width: 6%; } /* Median TB/U */
th:nth-child(14), td:nth-child(14) { width: 6%; } /* SD TB/U */
th:nth-child(15), td:nth-child(15) { width: 6%; } /* Z-Score TB/U */
th:nth-child(16), td:nth-child(16) { width: 7%; } /* Keterangan */
th:nth-child(17), td:nth-child(17) { width: 10%; } /* Imunisasi Terakhir */
th:nth-child(18), td:nth-child(18) { width: 15%; } /* Alamat */
}

/* Optional: agar saat print otomatis landscape */
@media print {
    @page {
        size: A4 landscape;
    }
    body {
        margin: 5mm;
        font-size: 10px;
    }
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
            <th>Nama Balita</th>
            <th>JK</th>
            <th>Tgl Lahir</th>
            <th>Nama Ibu</th>
            <th>Usia (Bln)</th>
            <th>Berat (kg)</th>
            <th>Tinggi (cm)</th>
            <th>Median BB/U</th>
            <th>SD BB/U</th>
            <th>Z-Score BB/U</th>
            <th>Status Gizi BB/U</th>
            <th>Median TB/U</th>
            <th>SD TB/U</th>
            <th>Z-Score TB/U</th>
            <th>Keterangan</th>
            <th>Imunisasi Terakhir</th>
            <th>Alamat</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($penimbangans as $i => $item)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $item->balita->nama_balita ?? '-' }}</td>
            <td>{{ $item->balita->jenis_kelamin ?? '-' }}</td>
            <td>{{ \Carbon\Carbon::parse($item->balita->tgl_lahir)->format('d/m/Y') ?? '-' }}</td>
            <td>{{ $item->balita->orang_tua_id ?? '-' }}</td> <!-- Ganti sesuai nama relasi ibu -->
            <td>{{ $item->umur ?? '-' }}</td>
            <td>{{ number_format($item->bb ?? 0, 2) }}</td>
            <td>{{ number_format($item->tb ?? 0, 2) }}</td>
            <td>{{ number_format($item->median_bbu ?? 0, 2) }}</td>
            <td>{{ number_format($item->sd_bbu ?? 0, 2) }}</td>
            <td>{{ number_format($item->z_score ?? 0, 2) }}</td>
            <td>{{ $item->status_gizi ?? '-' }}</td>
            <td>{{ number_format($item->median_tbu ?? 0, 2) }}</td>
            <td>{{ number_format($item->sd_tbu ?? 0, 2) }}</td>
            <td>{{ number_format($item->z_score_stunting ?? 0, 2) }}</td>
            <td>{{ $item->status_stunting ?? '-' }}</td>
            <td>{{ optional($item->balita->imunisasis->first())->jenis_imunisasi ?? '-' }}</td>
            <td>{{ $item->balita->alamat ?? '-' }}</td>
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
