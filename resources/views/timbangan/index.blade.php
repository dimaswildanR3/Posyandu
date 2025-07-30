@extends('layouts.admin')

@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="/dashboard" style="color: #fd6bc5">Dashboard</a></li>
      <li class="breadcrumb-item active" aria-current="page">Data Penimbangan Gizi</li>
    </ol>
</nav>

<div class="card shadow p-3 mb-5 bg-white rounded border-left-primary">
    <div class="row">
        <div class="col-md-3">
        <form action="/penimbangan" method="post" enctype="multipart/form-data">
            @csrf
            @method('post')
            <div class="form-group">
                <label for="">Ditimbang Oleh</label>
                <p>{{Auth::user()->name}}</p>
                <input type="number" name="user_id" value="{{ Auth::user()->id }}" hidden>
            </div>
            <div class="form-group">
                <label for="inlineFormCustomSelect">Tanggal Penimbangan</label>
                <select name="tanggal_timbang" class="custom-select mr-sm-2 @error('tanggal_timbang') is-invalid @enderror" id="inlineFormCustomSelect">
                    @php
                        $value = '';
                    @endphp
                    @foreach ($tanggalPelayanan as $option)
                        <option value="{{$option->tanggal_kegiatan ?? null}}">
                            {{$option->tanggal_kegiatan." - ".$value = $option->nama_kegiatan ?? null}}
                        
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group" hidden>
                <label for="catatan">Catatan</label>
                <input autocomplete="off" type="text" class="form-control @error('acara_kegiatan') is-invalid @enderror" name="acara_kegiatan"  id="acara_kegiatan" value="{{ $value }}">
                @error('acara_kegiatan')
                <div class="invalid-feedback">
                    {{$message}}
                </div>
                @enderror
            </div>
            <div class="form-group">
                <label for="inlineFormCustomSelect">Nama Balita</label>
                <select name="balita_id" class="custom-select mr-sm-2 @error('balita_id') is-invalid @enderror" id="inlineFormCustomSelect">
                    @foreach ($balita as $option)
                        <option value="{{$option->id ?? null}}">{{$option->nama_balita ?? null}}</option>
                    @endforeach
                </select>
            </div>
            @error('bb')
            <div class="invalid-feedback">
                {{$message}}
            </div>
            @enderror
            <div class="form-group">
                <label for="bb">Berat Badan</label>
                <input autocomplete="off" type="text" class="form-control @error('bb') is-invalid @enderror" name="bb"  id="bb" value="{{ old('bb') }}">
                @error('bb')
                <div class="invalid-feedback">
                    {{$message}}
                </div>
                @enderror
            </div>
            <div class="form-group">
                <label for="tb">Tinggi Badan</label>
                <input autocomplete="off" type="text" class="form-control @error('tb') is-invalid @enderror" name="tb"  id="tb" value="{{ old('tb') }}">
                @error('tb')
                <div class="invalid-feedback">
                    {{$message}}
                </div>
                @enderror
            </div>
            <div class="form-group">
                <label for="catatan">Catatan</label>
                <input autocomplete="off" type="text" class="form-control @error('catatan') is-invalid @enderror" name="catatan"  id="catatan" value="{{ old('tb') }}">
                @error('catatan')
                <div class="invalid-feedback">
                    {{$message}}
                </div>
                @enderror
            </div>
            <button type="submit" class="btn btn-outline-success">Simpan</button>
        </form>
        </div>
        <div class="col">
            <div class="panel">
                <div id="chartNilai1">ssss</div>
            </div>
        </div>
    </div>
</div>
<div class="">
    @if (session('status'))
        <div class="alert alert-success">
                {{ session('status') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
        </div>
    @endif
</div>
<div class="row g-3">
    <!-- KMS Card (Kiri) -->
    <div class="col-md-6">
        <div class="card shadow-sm border-left-primary h-100">
            <div class="card-body">
                <h5 class="mb-3"><i class="fas fa-child"></i> Lihat Kartu Menuju Sehat (KMS)</h5>
                <form class="row g-3 align-items-end">
                    <div class="col-md-8">
                        <label for="selectBalita" class="form-label">Nama Balita</label>
                        <select name="balita_id" id="selectBalita" class="form-select @error('balita_id') is-invalid @enderror">
                            @foreach ($balita as $option)
                                <option value="{{ $option->id }}">{{ $option->nama_balita }}</option>
                            @endforeach
                        </select>
                        @error('balita_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <a href="#" id="lihatKMS" class="btn btn-info w-100 mt-4" target="_blank">
                            <i class="fas fa-file-alt"></i> Lihat KMS
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Filter Cetak Card (Kanan) -->
    <div class="col-md-6">
        <div class="card shadow-sm border-left-primary h-100">
            <div class="card-body">
                <h5 class="mb-3"><i class="fas fa-filter"></i> Filter Cetak Laporan</h5>
                <form action="{{ url('/penimbangan/cetak') }}" method="get" target="_blank">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-6">
                            <label for="tahun" class="form-label">Pilih Tahun</label>
                            <select name="tahun" class="form-select">
                                <option value="">Semua Tahun</option>
                                @for ($i = now()->year; $i >= 2018; $i--)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-6">
                           <label for="status" class="form-label">Filter Status Gizi / Stunting</label>
<select name="status" class="form-select">
    <option value="">Semua Status</option>
    <optgroup label="Status Gizi">
        <option value="Normal">Normal</option>
        <option value="Gizi Kurang">Gizi Kurang</option>
        <option value="Gizi Buruk">Gizi Buruk</option>
        <option value="Risiko Gizi Lebih">Gizi Lebih</option>
    </optgroup>
    <optgroup label="Status Stunting">
        <option value="Sangat Pendek">Sangat Pendek</option>
        <option value="Pendek">Pendek</option>
        <option value="Normal (TB)">Normal</option>
        <option value="Tinggi">Tinggi</option>
    </optgroup>
</select>
                        </div>
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-success w-100 mt-2">
                                <i class="fas fa-file-pdf"></i> Cetak PDF
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<br><br>
<div style="padding-bottom:200px">
    <div class="card shadow p-3 mb-5 bg-white rounded border-left-primary">
        <div class="table-responsive">
            <div class="col-6">
                <form action="{{url('/filter/periodeTimbang')}}" method="get">
                    <div class="row">
                        <div class="col">
                            <div class="input-group">
                            <input class="form-control dateselect" type="text" name="dari" id="" autocomplete="off" value="{{$dari}}">
                                <div class="input-group-append">
                                    <span class="input-group-text" id="basic-addon2"><i class="fas fa-calendar"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="input-group">
                                <input class="form-control dateselect" type="text" name="sampai" id="" autocomplete="off"value="{{$sampai}}">
                                <div class="input-group-append">
                                    <span class="input-group-text" id="basic-addon2"><i class="fas fa-calendar"></i></span>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-outline-secondary" ><i class="fas fa-search"></i></button>
                    </div>
                </form>
            </div>
            <br>
        <table class="table table-hover" id="dataTable" width="100%" cellspacing="0">
            <thead style="background: #fd6bc5">
              <tr>
                <th scope="col">No</th>
                <th scope="col">Tanggal Penimbangan</th>
                <th scope="col">Nama Balita</th>
                <th scope="col">Berat Badan</th>
                <th scope="col">Tinggi Badan</th>
                <th scope="col">Ditimbang Oleh</th>
                <th scope="col">Nama Kegiatan</th>
                <th scope="col">Catatan</th>
                <th scope="col">Umur (bln)</th>
<th scope="col">Status Gizi</th>
<th scope="col">Z-Score</th>
<th scope="col">Status Stunting</th>

                <th scope="col">Aksi</th>
              </tr>
            </thead>
            <tbody>
                @foreach($timbangan as $key => $item)
                <tr class="clickable-row" data-href="/penimbangan/{{$item->id}}">
                <th scope="row" >{{ $key + $timbangan->firstItem()}}</th>
                    <td>{{date('d F Y',strtotime($item->tanggal_timbang))}}</td>
                    <td >{{$item->balita->nama_balita}}</td>
                    <td>{{$item->bb}} kg</td>
                    <td>{{$item->tb}} cm</td>
                    <td>{{$item->user->name}}</td>
                    <td>{{$item->acara_kegiatan}}</td>
                    <td>{{$item->catatan}}</td>
                    <td>{{ $item->umur }} bln</td>
<td>{{ $item->status_gizi }}</td>
<td>{{ $item->z_score }}</td>
<td>{{ $item->status_stunting }}</td>


                    <td>
                    <form action="{{ route('penimbangan.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-danger">
        <i class="fas fa-trash-alt"></i>
    </button>
</form>



                        <a href="/penimbangan/{{$item->id}}/edit" class="btn btn-primary" ><i class="fas fa-edit"></i></a> 
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        </div>
        
    </div>
    <!-- Setelah tabel dan pagination -->


</div>


@endsection

@section('footer')
<script>
    $(document).ready(function(){
        $('#selectBalita').on('change', function(){
            let balitaId = $(this).val();
            $('#lihatKMS').attr('href', '/kms/' + balitaId);
        });

        // Set default href saat halaman pertama kali dibuka
        let defaultId = $('#selectBalita').val();
        $('#lihatKMS').attr('href', '/kms/' + defaultId);
    });
</script>

<script src="https://code.highcharts.com/highcharts.js"></script>
<script>
    Highcharts.chart('chartNilai1', {
    chart: {
        type: 'bar'
    },
    title: {
        text: 'Jenis Kelamin'
    },
    subtitle: {
        text: 'Source: Posyandu Seruni'
    },
    xAxis: {
        categories: ['Jenis Kelamin', 'Umur'],
        title: {
            text: null
        }
    },
    yAxis: {
        min: 0,
        title: {
            text: 'Population (Balita)',
            align: 'high'
        },
        labels: {
            overflow: 'justify'
        }
    },
    tooltip: {
        valueSuffix: 'Balita'
    },
    plotOptions: {
        bar: {
            dataLabels: {
                enabled: true
            }
        }
    },
    legend: {
        layout: 'vertical',
        align: 'right',
        verticalAlign: 'top',
        x: -40,
        y: 80,
        floating: true,
        borderWidth: 1,
        backgroundColor:
            Highcharts.defaultOptions.legend.backgroundColor || '#FFFFFF',
        shadow: true
    },
    credits: {
        enabled: false
    },
    series: [{
        name: 'Laki Laki',
        // data: [107, 31, 635, 203, 2]
        data: {!! json_encode($laki) !!}
    }, {
        name: 'Perempuan',
        // data: [133, 156, 947, 408, 6]
        data: {!! json_encode($perem) !!}
    }]
});
            
  </script>
<script>

    //clickable Row
    jQuery(document).ready(function($) {
    $(".clickable-row").click(function() {
        window.location = $(this).data("href");
    });
    });
    $(".btn-danger").click(function(event){
    event.stopPropagation();
});

    //Source Code Chart
    Highcharts.chart('chartNilai', {
    chart: {
        type: 'line'
    },
    title: {
        text: 'Grafik Data Balita'
    },
    subtitle: {
        text: 'Sumber: posyanduseruni3.com'
    },
    xAxis: {
        categories: {!!json_encode($chart)!!},
        crosshair: true
    },
    yAxis: {
        min: 0,
        title: {
            text: 'BB/TB (kg/cm)'
        }
    },
    tooltip: {
        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
            '<td style="padding:0"><b>{point.y:.1f} </b></td></tr>',
        footerFormat: '</table>',
        shared: true,
        useHTML: true
    },
    plotOptions: {
        column: {
            pointPadding: 0.2,
            borderWidth: 0
        }
    },
    series: [{
        name: 'Berat Badan',
        data: {!!json_encode($beratBadan)!!}

    }, {
        name: 'Tinggi Badan',
        data: {!!json_encode($tinggiBadan)!!}

    },]
});
              
</script>
@endsection