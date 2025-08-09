@extends('layouts.admin')

@section('content')
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.css" rel="stylesheet">
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js"></script>

<div class="">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/dashboard" style="color: #fd6bc5">Dashboard</a></li>
          <li class="breadcrumb-item"><a href="/balita" style="color: #fd6bc5">Data Anak</a></li>
          <li class="breadcrumb-item active" aria-current="page">Edit Data Anak</li>
        </ol>
    </nav>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow p-3 mb-5 bg-white rounded">
                <form action="/balita/{{$balita->id}}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('patch')
                    
                    {{-- NIK Anak --}}
                    <div class="form-group">
                        <label for="nik_anak">NIK Anak</label>
                        <input type="text" class="form-control @error('nik_anak') is-invalid @enderror" name="nik_anak" id="nik_anak" value="{{ old('nik_anak', $balita->nik_anak) }}">
                        @error('nik_anak')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Nama Anak --}}
                    <div class="form-group">
                        <label for="nama_balita">Nama Anak</label>
                        <input type="text" class="form-control @error('nama_balita') is-invalid @enderror" name="nama_balita" id="nama_balita" value="{{ old('nama_balita', $balita->nama_balita) }}">
                        @error('nama_balita')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Tempat & Tanggal Lahir --}}
                    <div class="row">
                        <div class="col-4">
                            <div class="form-group">
                                <label for="tpt_lahir">Tempat Lahir</label>
                                <input type="text" class="form-control @error('tpt_lahir') is-invalid @enderror" name="tpt_lahir" id="tpt_lhr" value="{{ old('tpt_lahir', $balita->tpt_lahir) }}">
                                @error('tpt_lahir')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-8">
                            <div class="form-group">
                                <label for="tgl_lahir">Tanggal Lahir</label>
                                <input type="text" class="form-control date @error('tgl_lahir') is-invalid @enderror" name="tgl_lahir" id="tgl_lahir" value="{{ old('tgl_lahir', $balita->tgl_lahir) }}">
                                @error('tgl_lahir')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Jenis Kelamin --}}
                    <div class="form-group">
                        <label for="jenis_kelamin">Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="custom-select mr-sm-2 @error('jenis_kelamin') is-invalid @enderror" id="jenis_kelamin">
                            <option value="Laki-Laki" {{ (old('jenis_kelamin', $balita->jenis_kelamin) == 'Laki-Laki') ? 'selected' : '' }}>Laki-Laki</option>
                            <option value="Perempuan" {{ (old('jenis_kelamin', $balita->jenis_kelamin) == 'Perempuan') ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('jenis_kelamin')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Nama Orang Tua (input manual) --}}
                    <div class="form-group">
                        <label for="orang_tua_id">Nama Orang Tua</label>
                        <input autocomplete="off" type="text" class="form-control @error('orang_tua_id') is-invalid @enderror" 
                               name="orang_tua_id" id="orang_tua_id" value="{{ old('orang_tua_id', $balita->orang_tua_id) }}">
                        @error('orang_tua_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Alamat --}}
                    <div class="form-group">
                        <label for="alamat">Alamat Anak</label>
                        <textarea autocomplete="off" class="form-control @error('alamat') is-invalid @enderror" name="alamat" id="alamat" rows="3">{{ old('alamat', $balita->alamat) }}</textarea>
                        @error('alamat')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-outline-success">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $('.date').datepicker({  
       format: 'yyyy-mm-dd'
    });  
</script>

@endsection
