@extends('layouts.admin')

@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}" style="color: #fd6bc5">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ url('/penimbangan') }}" style="color: #fd6bc5">Data Penimbangan Gizi</a></li>
        <li class="breadcrumb-item active" aria-current="page">Tambah Data Penimbangan</li>
    </ol>
</nav>

<div class="card shadow p-3 mb-5 bg-white rounded border-left-primary">
    <div class="card-body">
        <h4 class="mb-4"><i class="fas fa-plus"></i> Tambah Data Penimbangan</h4>

        <form action="{{ route('penimbangan.store') }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('post')

            <div class="form-group">
                <label for="">Ditimbang Oleh</label>
                <p>{{ Auth::user()->name }}</p>
                <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
            </div>

            <div class="form-group">
                <label for="tanggal_timbang">Tanggal Penimbangan</label>
                <select name="tanggal_timbang" class="custom-select @error('tanggal_timbang') is-invalid @enderror">
                    @php $value = ''; @endphp
                    @foreach ($tanggalPelayanan as $option)
                        <option value="{{ $option->tanggal_kegiatan }}">
                            {{ $option->tanggal_kegiatan." - ".($value = $option->nama_kegiatan) }}
                        </option>
                    @endforeach
                </select>
                @error('tanggal_timbang')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <input type="hidden" name="acara_kegiatan" value="{{ $value }}">

            <div class="form-group">
                <label for="balita_id">Nama Balita</label>
                <select name="balita_id" class="custom-select @error('balita_id') is-invalid @enderror">
                    @foreach ($balita as $option)
                        <option value="{{ $option->id }}">{{ $option->nama_balita }}</option>
                    @endforeach
                </select>
                @error('balita_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="bb">Berat Badan (kg)</label>
                <input type="text" name="bb" id="bb" value="{{ old('bb') }}" class="form-control @error('bb') is-invalid @enderror" autocomplete="off">
                @error('bb')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="tb">Tinggi Badan (cm)</label>
                <input type="text" name="tb" id="tb" value="{{ old('tb') }}" class="form-control @error('tb') is-invalid @enderror" autocomplete="off">
                @error('tb')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="catatan">Catatan</label>
                <input type="text" name="catatan" id="catatan" value="{{ old('catatan') }}" class="form-control @error('catatan') is-invalid @enderror" autocomplete="off">
                @error('catatan')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('penimbangan.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
                <button type="submit" class="btn btn-outline-success">
                    <i class="fas fa-save"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
