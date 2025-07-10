@extends('layouts.admin')

@section('content')
<div class="container">
    <h4>Kartu Menuju Sehat - {{ $balita->nama_balita }}</h4>

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

    <canvas id="chartKMS"></canvas>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('chartKMS');
        const chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($penimbangans->pluck('tanggal_timbang')) !!},
                datasets: [{
                    label: 'BB (kg)',
                    data: {!! json_encode($penimbangans->pluck('bb')) !!},
                    borderColor: 'black',
                    backgroundColor: 'black',
                    tension: 0.3
                }]
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

    <table class="table table-bordered mt-4">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Umur (bulan)</th>
                <th>BB</th>
                <th>TB</th>
                <th>Catatan</th>
                <th>Acara</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($penimbangans as $p)
            <tr>
                <td>{{ $p->tanggal_timbang }}</td>
                <td>
                    {{ \Carbon\Carbon::parse($balita->tgl_lahir)->diffInMonths(\Carbon\Carbon::parse($p->tanggal_timbang)) }}
                </td>
                <td>{{ $p->bb }}</td>
                <td>{{ $p->tb }}</td>
                <td>{{ $p->catatan }}</td>
                <td>{{ $p->acara_kegiatan }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
