@extends('layouts.admin')

@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/dashboard" style="color: #fd6bc5">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('blog.index') }}">Data Jadwal Pelayanan</a></li>
        <li class="breadcrumb-item active" aria-current="page">Tambah Jadwal</li>
    </ol>
</nav>

<div class="card shadow p-3 mb-5 bg-white rounded border-left-primary">
    <div class="card-header">Tambah Jadwal Pelayanan</div>
    <div class="card-body">
        <form action="{{ route('blog.store') }}" method="post">
            @csrf

            <div class="form-group">
                <label>Jadwal Dibuat Oleh</label>
                <p>{{ Auth::user()->name }}</p>
                <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
            </div>

            <div class="form-group">
                <label for="tanggal_kegiatan">Tanggal Pelayanan</label>
                <input type="date" class="form-control @error('tanggal_kegiatan') is-invalid @enderror" name="tanggal_kegiatan" value="{{ old('tanggal_kegiatan') }}">
                @error('tanggal_kegiatan') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label for="bidan_id">Nama Bidan</label>
                <select name="bidan_id" id="bidan_id" class="form-control @error('bidan_id') is-invalid @enderror">
                    <option value="">-- Pilih Bidan --</option>
                    @foreach($bidans as $bidan)
                        <option value="{{ $bidan->id }}" data-nama="{{ $bidan->nama_lengkap }}">{{ $bidan->nama_lengkap }}</option>
                    @endforeach
                </select>
                <input type="hidden" name="nama_kegiatan" id="nama_kegiatan">
                @error('bidan_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label for="waktu">Jam Pelayanan Mulai</label>
                <input type="time" class="form-control @error('waktu') is-invalid @enderror" name="waktu" value="{{ old('waktu') }}">
                @error('waktu') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label for="waktu_akhir">Jam Pelayanan Akhir (opsional)</label>
                <input type="time" class="form-control @error('waktu_akhir') is-invalid @enderror" name="waktu_akhir" value="{{ old('waktu_akhir') }}">
                @error('waktu_akhir') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <button type="submit" class="btn btn-outline-success">Simpan</button>
            <a href="{{ route('blog.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const bidanSelect = document.getElementById('bidan_id');
    const namaKegiatanInput = document.getElementById('nama_kegiatan');

    bidanSelect.addEventListener('change', function () {
        const selectedOption = this.options[this.selectedIndex];
        namaKegiatanInput.value = selectedOption.getAttribute('data-nama') || '';
    });
});
</script>
@endpush
@endsection
