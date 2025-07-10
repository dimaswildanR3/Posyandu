@extends('layouts.admin')

@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
        <li class="breadcrumb-item active">Data Petugas</li>
    </ol>
</nav>

@if (session('status'))
<div class="alert alert-success">{{ session('status') }}</div>
@endif

<div class="card shadow border-left-primary">
    <div class="card-header d-flex justify-content-between">
        <h5>Data Petugas</h5>
        <a href="{{ route('petugas.create') }}" class="btn btn-outline-primary btn-sm">+ Tambah</a>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-striped">
            <thead style="background: #fd6bc5; color: white;">
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Tempat/Tanggal Lahir</th>
                    <th>Jabatan</th>
                    <th>Lama Kerja</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($petugas as $key => $p)
                <tr>
                    <td>{{ $key + $petugas->firstItem() }}</td>
                    <td>{{ $p->nama_petugas }}</td>
                    <td>{{ $p->tempat_lahir }}, {{ \Carbon\Carbon::parse($p->tanggal_lahir)->translatedFormat('d F Y') }}</td>
                    <td>{{ $p->jabatan }}</td>
                    <td>{{ $p->lama_kerja }}</td>
                    <td>
                        <a href="{{ route('petugas.edit', $p->id) }}" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a>
                        <form action="{{ route('petugas.destroy', $p->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger"><i class="fas fa-trash-alt"></i></button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $petugas->links() }}
    </div>
</div>

@endsection
