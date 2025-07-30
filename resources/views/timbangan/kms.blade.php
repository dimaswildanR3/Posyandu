@extends('layouts.admin')

@section('content')
<div class="container">
    <h4>Kartu Menuju Sehat - {{ $balita->nama_balita }}</h4>

    {{-- Ringkasan Kunjungan Terakhir --}}
        @php
            $last = $penimbangans->last();
            $lastStatusGizi = $last ? (
                $last->bb < 8 ? 'Gizi Buruk' : ($last->bb < 10 ? 'Gizi Kurang' : ($last->bb < 13 ? 'Gizi Baik' : 'Gizi Lebih'))
            ) : '-';

            $lastStunting = $last ? ($last->tb < 80 ? 'Stunting' : 'Normal') : '-';
        @endphp

        <div class="mb-4 p-3 rounded bg-light border">
            <p><strong>Kunjungan Terakhir:</strong> {{ $last->tanggal_timbang ?? '-' }}</p>
            <p><strong>Status Gizi Terakhir:</strong> {{ $lastStatusGizi }}</p>
            <p><strong>Status Stunting Terakhir:</strong> {{ $lastStunting }}</p>
        </div>


    <form action="{{ url('/kms/'.$balita->id) }}" method="GET" class="row g-3 mb-4">
        <div class="col-md-3">
            <label for="dari" class="form-label">Dari</label>
            <input type="date" name="dari" id="dari" class="form-control" value="{{ $dari ?? '' }}">
        </div>
        <div class="col-md-3">
            <label for="sampai" class="form-label">Sampai</label>
            <input type="date" name="sampai" id="sampai" class="form-control" value="{{ $sampai ?? '' }}">
        </div>
        <div class="col-md-2 align-self-end">
            <button type="submit" class="btn btn-primary">Filter</button>
            <a href="{{ url('/kms/'.$balita->id) }}" class="btn btn-secondary">Reset</a>
        </div>
    </form>

    <p><strong>Tanggal Kunjungan Terakhir:</strong> {{ $penimbangans->last()->tanggal_timbang ?? '-' }}</p>
    <p><strong>Status Gizi Terakhir:</strong> {{ $penimbangans->last()->catatan ?? '-' }}</p>

   <canvas id="chartKMS" style="max-height: 400px;"></canvas>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('chartKMS');
    const chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($labels),
            datasets: [
                {
                    label: 'Berat Badan (kg)',
                    data: @json($berat),
                    borderColor: 'black',
                    backgroundColor: 'black',
                    tension: 0.3
                },
                {
                    label: 'Tinggi Badan (cm)',
                    data: @json($tinggi),
                    borderColor: 'blue',
                    backgroundColor: 'blue',
                    tension: 0.3
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>


    <table class="table table-bordered mt-4 mb-5">
        <thead>
    <tr>
        <th>Tanggal</th>
        <th>Umur (bulan)</th>
        <th>BB</th>
        <th>TB</th>
        <th>Status Gizi</th>
        <th>Stunting</th>
        <th>Catatan</th>
        <th>Acara</th>
    </tr>
</thead>
<tbody>
    @foreach ($penimbangans as $p)
    @php
        $umur = \Carbon\Carbon::parse($balita->tgl_lahir)->diffInMonths(\Carbon\Carbon::parse($p->tanggal_timbang));

        // Penilaian sederhana gizi
        $statusGizi = match (true) {
            $p->bb < 8 => 'Gizi Buruk',
            $p->bb < 10 => 'Gizi Kurang',
            $p->bb < 13 => 'Gizi Baik',
            default => 'Gizi Lebih'
        };

        // Penilaian stunting sederhana
        $statusStunting = $p->tb < 80 ? 'Stunting' : 'Normal';
    @endphp
    <tr>
        <td>{{ $p->tanggal_timbang }}</td>
        <td>{{ $umur }}</td>
        <td>{{ $p->bb }}</td>
        <td>{{ $p->tb }}</td>
        <td>{{ $statusGizi }}</td>
        <td>{{ $statusStunting }}</td>
        <td>{{ $p->catatan }}</td>
        <td>{{ $p->acara_kegiatan }}</td>
    </tr>
    @endforeach
    </table>
    <br>
<a href="{{ url('/penimbangan') }}" class="btn btn-secondary mb-3">
    ← Kembali ke Penimbangan
</a>

    <div style="height: 60px;"></div>
</div>



</div>
@endsection
