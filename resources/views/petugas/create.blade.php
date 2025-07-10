@extends('layouts.admin')

@section('content')
<form action="{{ route('petugas.store') }}" method="POST">
    @csrf
    <div class="card shadow mb-4">
        <div class="card-header">Tambah Petugas</div>
        <div class="card-body">
            <div class="form-group">
                <label>Nama Petugas</label>
                <input type="text" name="nama_petugas" class="form-control">
            </div>
            <div class="form-group">
                <label>Tempat Lahir</label>
                <input type="text" name="tempat_lahir" class="form-control">
            </div>
            <div class="form-group">
                <label>Tanggal Lahir</label>
                <input type="date" name="tanggal_lahir" class="form-control">
            </div>
            <div class="form-group">
                <label>No HP</label>
                <input type="text" name="no_hp" class="form-control">
            </div>
            <div class="form-group">
                <label>Jabatan</label>
                <select name="jabatan" class="form-control">
                    @foreach ($jabatans as $j)
                        <option value="{{ $j }}">{{ $j }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Pendidikan Terakhir</label>
                <input type="text" name="pendidikan" class="form-control">
            </div>
            <div class="form-group">
                <label>Lama Kerja</label>
                <input type="text" name="lama_kerja" class="form-control">
            </div>
            <div class="form-group">
                <label>Username</label>
                <select name="user_id" class="form-control">
                    @foreach ($users as $u)
                        <option value="{{ $u->id }}">{{ $u->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="card-footer">
            <button class="btn btn-success">Simpan</button>
        </div>
    </div>
</form>

@endsection