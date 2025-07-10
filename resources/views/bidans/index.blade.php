@extends('layouts.admin')

@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/dashboard" style="color: #fd6bc5">Dashboard</a></li>
        <li class="breadcrumb-item active" aria-current="page">Data Bidan</li>
    </ol>
</nav>

@if(session('status'))
<div class="alert alert-success">{{ session('status') }}</div>
@endif

<div class="card shadow p-3 mb-5 bg-white rounded">
    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('bidans.create') }}" class="btn btn-outline-secondary"><i class="fas fa-plus"></i> Tambah Data</a>
    </div>

    <table class="table table-hover">
        <thead style="background: #fd6bc5; color: white;">
            <tr>
                <th>No</th>
                <th>Nama Lengkap</th>
                <th>Tempat/Tanggal Lahir</th>
                <th>No HP</th>
                <th>Pendidikan Terakhir</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bidans as $key => $bidan)
            <tr>
                <td>{{ $key + $bidans->firstItem() }}</td>
                <td>{{ $bidan->nama_lengkap }}</td>
                <td>{{ $bidan->tempat_lahir }}, {{ date('d F Y', strtotime($bidan->tanggal_lahir)) }}</td>
                <td>{{ $bidan->no_hp }}</td>
                <td>{{ $bidan->pendidikan_terakhir }}</td>
                <td>
                    <a href="{{ route('bidans.edit', $bidan->id) }}" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>
                    <form action="{{ route('bidans.destroy', $bidan->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $bidans->links() }}
</div>
@endsection
