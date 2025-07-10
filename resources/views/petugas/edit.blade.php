@extends('layouts.admin')

@section('content')
<form action="{{ route('petugas.update', $petugas->id) }}" method="POST">
    @csrf
    @method('PATCH')

    <div class="card shadow mb-4">
        <div class="card-header">Edit Data Petugas</div>
        <div class="card-body">
            <div class="form-group">
                <label>Nama Petugas</label>
                <input type="text" name="nama_petugas" class="form-control" value="{{ old('nama_petugas', $petugas->nama_petugas) }}">
            </div>

            <div class="form-group">
                <label>Tempat Lahir</label>
                <input type="text" name="tempat_lahir" class="form-control" value="{{ old('tempat_lahir', $petugas->tempat_lahir) }}">
            </div>

            <div class="form-group">
                <label>Tanggal Lahir</label>
                <input type="date" name="tanggal_lahir" class="form-control" value="{{ old('tanggal_lahir', $petugas->tanggal_lahir) }}">
            </div>

            <div class="form-group">
                <label>No HP</label>
                <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp', $petugas->no_hp) }}">
            </div>

            <div class="form-group">
                <label>Jabatan</label>
                <select name="jabatan" class="form-control">
                    @foreach ($jabatans as $jabatan)
                        <option value="{{ $jabatan }}" {{ $petugas->jabatan === $jabatan ? 'selected' : '' }}>{{ $jabatan }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Pendidikan Terakhir</label>
                <input type="text" name="pendidikan" class="form-control" value="{{ old('pendidikan', $petugas->pendidikan) }}">
            </div>

            <div class="form-group">
                <label>Lama Kerja</label>
                <input type="text" name="lama_kerja" class="form-control" value="{{ old('lama_kerja', $petugas->lama_kerja) }}">
            </div>

            <div class="form-group">
                <label>Username</label>
                <select name="user_id" class="form-control">
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}" {{ $petugas->user_id == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="card-footer">
            <button class="btn btn-success">Simpan Perubahan</button>
            <a href="{{ route('petugas.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </div>
</form>

@endsection