@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h4 class="mb-3"><i class="fas fa-chart-line"></i> Kartu Menuju Sehat - {{ $balita->nama_balita }}</h4>
        </div>
    </div>

    {{-- Info Balita --}}
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Informasi Balita</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless table-sm mb-0">
                        <tr><td><strong>Nama</strong></td><td>{{ $balita->nama_balita }}</td></tr>
                        <tr><td><strong>Tanggal Lahir</strong></td><td>{{ \Carbon\Carbon::parse($balita->tgl_lahir)->format('d-m-Y') }}</td></tr>
                        <tr><td><strong>Umur Saat Ini</strong></td><td>{{ \Carbon\Carbon::parse($balita->tgl_lahir)->diffInMonths() }} bulan</td></tr>
                        <tr><td><strong>Jenis Kelamin</strong></td><td>{{ $balita->jenis_kelamin ?? 'Laki-laki' }}</td></tr>
                    </table>
                </div>
            </div>
        </div>

        {{-- Ringkasan Status Terakhir --}}
        <div class="col-md-6">
            @php $last = $penimbangans->last(); @endphp
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Status Terakhir</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless table-sm mb-0 text-white">
                    <tr>
    <td style="color: black;"><strong>Tanggal Timbang</strong></td>
    <td style="color: black;">
        {{ $last ? \Carbon\Carbon::parse($last->tanggal_timbang)->format('d-m-Y') : '-' }}
    </td>
</tr>
<tr>
    <td style="color: black;"><strong>Berat Badan</strong></td>
    <td style="color: black;">
        {{ $last ? number_format($last->bb,1).' kg' : '-' }}
    </td>
</tr>
<tr>
    <td style="color: black;"><strong>Tinggi Badan</strong></td>
    <td style="color: black;">
        {{ $last ? number_format($last->tb,1).' cm' : '-' }}
    </td>
</tr>
<tr>
    <td style="color: black;"><strong>Status Gizi</strong></td>
    <!-- bagian Status Gizi tetap seperti semula atau sesuai style kamu -->

                            <td>
                                @if($last)
                                    @php
                                        $color = match ($last->status_gizi) {
                                            'Gizi Buruk' => '#e74a3b',
                                            'Gizi Kurang' => '#f6c23e',
                                            'Gizi Normal' => '#1cc88a',
                                            'Gizi Lebih' => '#000000',
                                            default => '#6c757d'
                                        };
                                    @endphp
                                    <span class="badge" style="background-color: {{ $color }}; color: {{ $last->status_gizi == 'Gizi Kurang' ? '#000' : '#fff' }};">
                                        {{ $last->status_gizi }}
                                    </span>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter Tanggal --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <form action="{{ url('/kms/'.$balita->id) }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label for="dari" class="form-label"><i class="fas fa-calendar"></i> Dari Tanggal</label>
                    <input type="date" name="dari" id="dari" class="form-control" value="{{ $dari ?? '' }}">
                </div>
                <div class="col-md-3">
                    <label for="sampai" class="form-label"><i class="fas fa-calendar"></i> Sampai Tanggal</label>
                    <input type="date" name="sampai" id="sampai" class="form-control" value="{{ $sampai ?? '' }}">
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> Filter</button>
                    <a href="{{ url('/kms/'.$balita->id) }}" class="btn btn-secondary"><i class="fas fa-refresh"></i> Reset</a>
                </div>
            </form>
        </div>
    </div>

    {{-- Grafik KMS --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0"><i class="fas fa-chart-area"></i> Grafik Pertumbuhan Berat Badan Menurut Umur</h5>
        </div>
        <div class="card-body" style="height: 450px;">
            <canvas id="chartKMS" style="height:100%; width:100%;"></canvas>
        </div>
        <div class="card-footer">
            {{-- Legenda warna zona --}}
            <div class="d-flex flex-wrap gap-3 justify-content-center">
                <div class="d-flex align-items-center gap-2">
                    <div style="width:20px; height:20px; background-color:#e74a3b;"></div><small>Gizi Buruk (&lt; 6 kg)</small>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <div style="width:20px; height:20px; background-color:#f6c23e;"></div><small>Gizi Kurang (6 – 7 kg)</small>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <div style="width:20px; height:20px; background-color:#1cc88a;"></div><small>Gizi Normal (7 – 11 kg)</small>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <div style="width:20px; height:20px; background-color:#000;"></div><small>Gizi Lebih (&gt; 11 kg)</small>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabel Riwayat Penimbangan --}}
    <div class="card shadow-sm">
        <div class="card-header bg-warning text-dark">
            <h5 class="mb-0"><i class="fas fa-table"></i> Riwayat Penimbangan</h5>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark text-center">
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Umur (bulan)</th>
                        <th>Berat Badan (kg)</th>
                        <th>Tinggi Badan (cm)</th>
                        <th>Status Gizi</th>
                        <th>Status Stunting</th>
                        <th>Catatan</th>
                        <th>Kegiatan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($penimbangans as $index => $p)
                    <tr class="text-center">
                        <td>{{ $index + 1 }}</td>
                        <td>{{ \Carbon\Carbon::parse($p->tanggal_timbang)->format('d-m-Y') }}</td>
                        <td>{{ $p->umur }}</td>
                        <td>{{ number_format($p->bb,1) }}</td>
                        <td>{{ number_format($p->tb,1) }}</td>
                        <td>
                            @php
                                $color = match ($p->status_gizi) {
                                    'Gizi Buruk' => '#e74a3b',
                                    'Gizi Kurang' => '#f6c23e',
                                    'Gizi Normal' => '#1cc88a',
                                    'Gizi Lebih' => '#000000',
                                    default => '#6c757d'
                                };
                            @endphp
                            <span class="badge" style="background-color: {{ $color }}; color: {{ $p->status_gizi == 'Gizi Kurang' ? '#000' : '#fff' }};">
                                {{ $p->status_gizi }}
                            </span>
                        </td>
                        <td>
                            <span class="badge badge-{{ $p->status_stunting == 'Normal' ? 'success' : 'danger' }}">
                                {{ $p->status_stunting }}
                            </span>
                        </td>
                        <td class="text-start">{{ $p->catatan ?? '-' }}</td>
                        <td class="text-start">{{ $p->acara_kegiatan ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted"><i class="fas fa-info-circle"></i> Belum ada data penimbangan</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Tombol Kembali --}}
    <div class="mt-4">
        <a href="{{ url('/penimbangan') }}" class="btn btn-secondary btn-lg">
            <i class="fas fa-arrow-left"></i> Kembali ke Penimbangan
        </a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const umur = @json($umur);
    const berat = @json($berat);
    const severelyUnderweight = @json($severelyUnderweight);
    const underweight = @json($underweight);
    const normal = @json($normal);
    const overweight = @json($overweight);

    const ctx = document.getElementById('chartKMS').getContext('2d');

new Chart(ctx, {
    type: 'line',
    data: {
        datasets: [
            // Garis Batas Bawah Gizi Buruk (merah)
            {
                label: 'Batas Bawah Gizi Buruk',
                data: severelyUnderweight,
                borderColor: 'rgba(231,74,59,0.8)',
                fill: false,
                pointRadius: 0,
                borderWidth: 1,
                order: 4
            },
            // Garis Batas Atas Gizi Buruk = Batas Bawah Gizi Kurang
            {
                label: 'Batas Atas Gizi Buruk',
                data: underweight,
                borderColor: 'rgba(246,194,62,0.8)',
                fill: '+1',  // fill area di bawahnya
                backgroundColor: 'rgba(231,74,59,0.3)', // merah transparan
                pointRadius: 0,
                borderWidth: 1,
                order: 3
            },
            // Garis Batas Atas Gizi Kurang = Batas Bawah Gizi Normal
            {
                label: 'Batas Atas Gizi Kurang',
                data: normal,
                borderColor: 'rgba(28,200,138,0.8)',
                fill: '+1', // fill area di bawahnya
                backgroundColor: 'rgba(246,194,62,0.3)', // kuning transparan
                pointRadius: 0,
                borderWidth: 1,
                order: 2
            },
            // Garis Batas Atas Gizi Normal = Batas Bawah Gizi Lebih
            {
                label: 'Batas Atas Gizi Normal',
                data: overweight,
                borderColor: 'rgba(0,0,0,0.8)',
                fill: '+1', // fill area di bawahnya
                backgroundColor: 'rgba(28,200,138,0.3)', // hijau transparan
                pointRadius: 0,
                borderWidth: 1,
                order: 1
            },
            // Berat Badan Bayi (data utama)
            {
                label: 'Berat Badan Bayi',
                data: umur.map((u,i) => ({ x: u, y: berat[i] })),
                borderColor: '#2c3e50',
                backgroundColor: '#2c3e50',
                pointRadius: 6,
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                borderWidth: 3,
                fill: false,
                order: 0,
                tension: 0.3
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        interaction: { mode: 'nearest', axis: 'x', intersect: false },
        scales: {
            x: {
                type: 'linear',
                title: { display: true, text: 'Umur (bulan)' },
                min: 0,
                max: Math.max(...umur, 36),
                ticks: { stepSize: 3 },
                grid: { drawOnChartArea: false }
            },
            y: {
                title: { display: true, text: 'Berat Badan (kg)' },
                min: 2,
                max: Math.max(...berat, 18),
                ticks: { stepSize: 1 }
            }
        }
    }
});

</script>

<style>
    .badge {
        font-size: 0.85rem;
        font-weight: 600;
    }
    .card {
        border-radius: 0.5rem;
        box-shadow: 0 0 10px rgb(0 0 0 / 0.1);
        border: none;
    }
    .table thead {
        background-color: #343a40 !important;
        color: white !important;
    }
</style>
@endsection
