@extends('layouts.admin')

@section('content')
<style>
.border-left-primary {
  border-left: 0.25rem solid #ff7ec9 !important;
}
.border-left-secondary {
  border-left: 0.25rem solid #858796 !important;
}
.border-left-success {
  border-left: 0.25rem solid #1cc88a !important;
}
.border-left-info {
  border-left: 0.25rem solid #36b9cc !important;
}
.border-left-warning {
  border-left: 0.25rem solid #f6c23e !important;
}
.border-left-danger {
  border-left: 0.25rem solid #e74a3b !important;
}
.border-left-light {
  border-left: 0.25rem solid #f8f9fc !important;
}
.border-left-dark {
  border-left: 0.25rem solid #5a5c69 !important;
}
</style>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
    </ol>
</nav>

<!-- Content Row -->
<div class="row">

    <!-- Anak yang Terdata -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1" style="color: #ff7ec9">
                            Anak yang Terdata</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $jumlahBalita }} Anak</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-center">
                <a href="/balita" class="justify-content-center text-decoration-none">
                    Info lebih lanjut <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

</div>

<!-- Chart Row -->
<div class="card mt-4">
    <div class="card-header">
        <h6 class="m-0 font-weight-bold text-primary">Grafik Status Gizi dan Stunting per Bulan</h6>
    </div>
    <div class="card-body">
        <canvas id="giziStuntingChart" height="300"></canvas>
    </div>
</div>

<!-- Chart JS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const chartData = @json($chartData);

    new Chart(document.getElementById('giziStuntingChart'), {
        type: 'bar',
        data: {
            labels: chartData.labels,
            datasets: chartData.datasets
        },
        options: {
            indexAxis: 'y', // horizontal
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Status Gizi dan Stunting per Bulan'
                },
                legend: {
                    position: 'top',
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Jumlah Anak'
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: 'Kategori'
                    }
                }
            }
        }
    });
</script>

@endsection
