@extends('layouts.admin')

@section('content')
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.css" rel="stylesheet">
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js"></script>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="/dashboard" style="color: #fd6bc5">Dashboard</a></li>
      <li class="breadcrumb-item"><a href="/orangtua" style="color: #fd6bc5">Data Ibu</a></li>
      <li class="breadcrumb-item active" aria-current="page">Tambah Orang Tua</li>
    </ol>
</nav>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow p-3 mb-5 bg-white rounded">
            <form action="/orangtua" method="post" enctype="multipart/form-data">
                @csrf
                @method('post')

                {{-- Data Ibu --}}
                <h5 class="mb-3">Data Ibu</h5>
                <div class="form-group">
                    <label for="nama">Nama Ibu</label>
                    <input autocomplete="off" type="text" class="form-control @error('nama') is-invalid @enderror" name="nama" value="{{ old('nama') }}">
                    @error('nama')<div class="invalid-feedback">{{$message}}</div>@enderror
                </div>
                <div class="form-group mt-2">
                    <label for="tempat_lahir_ibu">Tempat Lahir Ibu</label>
                    <input autocomplete="off" type="text" class="form-control" name="tempat_lahir_ibu" value="{{ old('tempat_lahir_ibu') }}">
                </div>
                <div class="form-group mt-2">
                    <label for="tanggal_lahir_ibu">Tanggal Lahir Ibu</label>
                    <input autocomplete="off" type="text" class="form-control date" name="tanggal_lahir_ibu" value="{{ old('tanggal_lahir_ibu') }}">
                </div>
                <div class="form-group mt-2">
                    <label for="pendidikan">Pendidikan Ibu</label>
                    <input autocomplete="off" type="text" class="form-control @error('pendidikan') is-invalid @enderror" name="pendidikan" value="{{ old('pendidikan') }}">
                    @error('pendidikan')<div class="invalid-feedback">{{$message}}</div>@enderror
                </div>
                <div class="form-group mt-2">
                    <label for="pekerjaan">Pekerjaan Ibu</label>
                    <input autocomplete="off" type="text" class="form-control @error('pekerjaan') is-invalid @enderror" name="pekerjaan" value="{{ old('pekerjaan') }}">
                    @error('pekerjaan')<div class="invalid-feedback">{{$message}}</div>@enderror
                </div>

                {{-- Data Suami --}}
                <h5 class="mt-4 mb-3">Data Suami</h5>
                <div class="form-group">
                    <label for="nama_suami">Nama Suami</label>
                    <input autocomplete="off" type="text" class="form-control" name="nama_suami" value="{{ old('nama_suami') }}">
                </div>
                <div class="form-group mt-2">
                    <label for="tempat_lahir_suami">Tempat Lahir Suami</label>
                    <input autocomplete="off" type="text" class="form-control" name="tempat_lahir_suami" value="{{ old('tempat_lahir_suami') }}">
                </div>
                <div class="form-group mt-2">
                    <label for="tanggal_lahir_suami">Tanggal Lahir Suami</label>
                    <input autocomplete="off" type="text" class="form-control date" name="tanggal_lahir_suami" value="{{ old('tanggal_lahir_suami') }}">
                </div>
                <div class="form-group mt-2">
                    <label for="pendidikan_suami">Pendidikan Suami</label>
                    <input autocomplete="off" type="text" class="form-control" name="pendidikan_suami" value="{{ old('pendidikan_suami') }}">
                </div>
                <div class="form-group mt-2">
                    <label for="pekerjaan_suami">Pekerjaan Suami</label>
                    <input autocomplete="off" type="text" class="form-control" name="pekerjaan_suami" value="{{ old('pekerjaan_suami') }}">
                </div>

                {{-- Alamat dan Kontak --}}
                <h5 class="mt-4 mb-3">Alamat dan Kontak</h5>
                <div class="form-group">
                    <label for="alamat">Alamat</label>
                    <input autocomplete="off" type="text" class="form-control @error('alamat') is-invalid @enderror" name="alamat" value="{{ old('alamat') }}">
                    @error('alamat')<div class="invalid-feedback">{{$message}}</div>@enderror
                </div>
                <div class="form-group mt-2">
                    <label for="kota">Kota</label>
                    <input autocomplete="off" type="text" class="form-control" name="kota" value="{{ old('kota') }}">
                </div>
                <div class="form-group mt-2">
                    <label for="kecamatan">Kecamatan</label>
                    <input autocomplete="off" type="text" class="form-control" name="kecamatan" value="{{ old('kecamatan') }}">
                </div>
                <div class="form-group mt-2">
                    <label for="no_tlpn">No Telepon</label>
                    <input autocomplete="off" type="text" class="form-control" name="no_tlpn" value="{{ old('no_tlpn') }}">
                </div>

                <!-- {{-- Keterangan --}}
                <div class="form-group mt-2">
                    <label for="ket">Keterangan</label>
                    <textarea autocomplete="off" class="form-control @error('ket') is-invalid @enderror" name="ket">{{ old('ket') }}</textarea>
                    @error('ket')<div class="invalid-feedback">{{$message}}</div>@enderror
                </div> -->

                <button type="submit" class="btn btn-outline-success mt-3">Simpan</button>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    $('.date').datepicker({  
       format: 'dd-mm-yyyy',
       autoclose: true,
       todayHighlight: true
    });  
</script>
@endsection
