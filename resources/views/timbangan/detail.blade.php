@extends('layouts.admin')

@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="/dashboard" style="color: #fd6bc5">Dashboard</a></li>
      <li class="breadcrumb-item"><a href="/penimbangan" style="color: #fd6bc5">Data Penimbangan Gizi</a></li>
      <li class="breadcrumb-item active" aria-current="page">Detail Data Penimbangan Gizi</li>
    </ol>
</nav>
<div class="card shadow p-3 mb-5 bg-white rounded border-left-primary">
    
  <div class="row">
    <div class="col">
        <h3>Nama Balita : {{ $balita->nama_balita }}</h3>

        @foreach ($dataPenimbangan as $item)
            <p>Tanggal: {{ \Carbon\Carbon::parse($item->tanggal_timbang)->format('d M Y') }}</p>
            <p>Berat Badan: {{ $item->bb }} kg</p>
            <p>Tinggi Badan: {{ $item->tb }} cm</p>
            <hr>
        @endforeach
    </div>
    <div class="col">
        <div class="panel">
            <div id="chartNilai"></div>
        </div>
    </div>
</div>

@endsection
@section('footer')
    
<script src="https://code.highcharts.com/modules/accessibility.js"></script>
<script>
    console.log("Tanggal:", @json($tanggal));
    console.log("Berat Badan:", @json($beratBadan));
    console.log("Tinggi Badan:", @json($tinggiBadan));

    Highcharts.chart('chartNilai', {
        chart: { type: 'line' },
        title: { text: 'Grafik Perkembangan Berat & Tinggi Badan' },
        xAxis: {
            categories: @json($tanggal),
            title: { text: 'Tanggal Penimbangan' }
        },
        yAxis: {
            title: { text: 'Nilai BB/TB' }
        },
        tooltip: {
            shared: true,
            crosshairs: true
        },
        series: [{
            name: 'Berat Badan (kg)',
            data: @json($beratBadan)
        }, {
            name: 'Tinggi Badan (cm)',
            data: @json($tinggiBadan)
        }]
    });
</script>


@endsection