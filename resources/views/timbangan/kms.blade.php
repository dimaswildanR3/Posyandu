@extends('layouts.admin')

@section('content')
<div class="container">
    <h4>Kartu Menuju Sehat - {{ $balita->nama_balita }}</h4>

    {{-- Ringkasan Kunjungan Terakhir --}}
    @php
        $last = $penimbangans->last();
    @endphp
    <div class="mb-4 p-3 rounded bg-light border">
        <p><strong>Kunjungan Terakhir:</strong> {{ $last->tanggal_timbang ?? '-' }}</p>
        <p><strong>Status Gizi Terakhir:</strong> {{ $last->status_gizi ?? '-' }}</p>
        <p><strong>Status Stunting Terakhir:</strong> {{ $last->status_stunting ?? '-' }}</p>
    </div>

    {{-- Form Filter Tanggal --}}
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

    {{-- Grafik --}}
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
                        tension: 0.3,
                        fill: false,
                        yAxisID: 'y',
                    },
                    {
                        label: 'Tinggi Badan (cm)',
                        data: @json($tinggi),
                        borderColor: 'blue',
                        backgroundColor: 'blue',
                        tension: 0.3,
                        fill: false,
                        yAxisID: 'y1',
                    }
                ]
            },
            options: {
                responsive: true,
                interaction: {
                    mode: 'nearest',
                    intersect: false
                },
                stacked: false,
                plugins: {
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                    },
                    title: {
                        display: true,
                        text: 'Grafik Berat dan Tinggi Badan Balita'
                    }
                },
                scales: {
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        title: { display: true, text: 'Berat Badan (kg)' },
                        beginAtZero: true,
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        title: { display: true, text: 'Tinggi Badan (cm)' },
                        grid: { drawOnChartArea: false },
                        beginAtZero: true,
                    }
                }
            }
        });
    </script>

    {{-- Tabel Data Penimbangan --}}
    <table class="table table-bordered mt-4 mb-5">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Umur (bulan)</th>
                <th>Berat Badan (kg)</th>
                <th>Tinggi Badan (cm)</th>
                <th>Status Gizi</th>
                <th>Status Stunting</th>
                <th>Catatan</th>
                <th>Acara</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($penimbangans as $p)
            <tr>
                <td>{{ \Carbon\Carbon::parse($p->tanggal_timbang)->format('d-m-Y') }}</td>
                <td>{{ $p->umur }}</td>
                <td>{{ number_format($p->bb, 2) }}</td>
                <td>{{ number_format($p->tb, 2) }}</td>
                <td>{{ $p->status_gizi }}</td>
                <td>{{ $p->status_stunting }}</td>
                <td>{{ $p->catatan ?? '-' }}</td>
                <td>{{ $p->acara_kegiatan ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <a href="{{ url('/penimbangan') }}" class="btn btn-secondary mb-3">← Kembali ke Penimbangan</a>

</div>
@endsection
