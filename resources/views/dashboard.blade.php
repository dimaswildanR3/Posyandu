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
 @if(Auth::user()->role !== 'ortu')
<div class="row">
    <!-- Grafik Gizi -->
    <div class="col-md-6">
        <div class="card mt-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Grafik Gizi per Bulan</h6>
                <form method="GET" action="{{ url()->current() }}" class="form-inline">
                    <select name="gender_gizi" onchange="this.form.submit()" class="form-control form-control-sm">
                        <option value="" {{ (request('gender_gizi') == '') ? 'selected' : '' }}>Semua</option>
                        <option value="Laki-laki" {{ (request('gender_gizi') == 'Laki-laki') ? 'selected' : '' }}>Laki-laki</option>
                        <option value="Perempuan" {{ (request('gender_gizi') == 'Perempuan') ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </form>
            </div>
            <div class="card-body">
                <canvas id="chartGizi"></canvas>
            </div>
        </div>
    </div>

    <!-- Grafik Stunting -->
    <div class="col-md-6">
        <div class="card mt-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Grafik Stunting per Bulan</h6>
                <form method="GET" action="{{ url()->current() }}" class="form-inline">
                    <select name="gender_stunting" onchange="this.form.submit()" class="form-control form-control-sm">
                        <option value="" {{ (request('gender_stunting') == '') ? 'selected' : '' }}>Semua</option>
                        <option value="Laki-laki" {{ (request('gender_stunting') == 'Laki-laki') ? 'selected' : '' }}>Laki-laki</option>
                        <option value="Perempuan" {{ (request('gender_stunting') == 'Perempuan') ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </form>
            </div>
            <div class="card-body">
                <canvas id="chartStunting"></canvas>
            </div>
        </div>
    </div>
</div>
@endif
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const chartGizi = @json($chartGizi);
    const chartStunting = @json($chartStunting);

    new Chart(document.getElementById('chartGizi'), {
        type: 'bar',
        data: chartGizi,
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Grafik Status Gizi'
                }
            }
        }
    });

    new Chart(document.getElementById('chartStunting'), {
        type: 'bar',
        data: chartStunting,
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Grafik Balita Stunting'
                }
            }
        }
    });
</script>
@endsection
