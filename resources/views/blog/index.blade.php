@extends('layouts.admin')

@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="/dashboard" style="color: #fd6bc5">Dashboard</a></li>
      <li class="breadcrumb-item active" aria-current="page">Data Jadwal Pelayanan</li>
    </ol>
</nav>

@if (session('status'))
<div class="alert alert-success">
    {{ session('status') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif

<div class="card shadow p-3 mb-5 bg-white rounded border-left-primary">
    <div class="card-header">
        Jadwal Pelayanan Posyandu
    </div>
    <div class="card-body">

        {{-- Tombol Tambah Data hanya untuk selain ortu --}}
        @if(Auth::user()->role !== 'ortu')
            <div class="d-flex justify-content-lg-end mb-3">
                <a class="btn btn-outline-secondary" href="{{ url('blog/create') }}">
                    <span class="icon text">
                        <i class="fas fa-plus"></i>
                    </span>
                    Tambah Data
                </a>
            </div>
        @endif

        <div class="m-4">
            <table class="table table-hover">
                <thead style="background: #fd6bc5">
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Dibuat Oleh</th>
                        <th scope="col">Tanggal Pelayanan</th>
                        <th scope="col">Nama Pelayanan/Kegiatan</th>
                        <th scope="col">Jam Pelayanan</th>
                        @if(Auth::user()->role !== 'ortu')
                            <th scope="col">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @php $i=1; @endphp
                    @foreach ($jadwal as $item) 
                    <tr>
                        <th scope="row">{{ $i++ }}</th>
                        <td>{{ $item->user->name }}</td>
                        <td>{{ date('d F Y', strtotime($item->tanggal_kegiatan)) }}</td>
                        <td>{{ $item->nama_kegiatan }}</td>
                        <td>
                            {{ $item->waktu }} 
                            @if($item->waktu_akhir)
                                - {{ $item->waktu_akhir }}
                            @endif
                        </td>
                        @if(Auth::user()->role !== 'ortu')
                            <td>
                                <form action="/blog/{{ $item->id }}" method="post" class="d-inline form-delete">
                                    @csrf
                                    @method('delete')
                                    <button type="submit" class="btn btn-danger"><i class="fas fa-trash-alt"></i></button>
                                </form>
                                <a href="/blog/{{ $item->id }}/edit" class="btn btn-primary">
                                    <i class="fas fa-edit"></i>
                                </a> 
                            </td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const deleteForms = document.querySelectorAll('.form-delete');

    deleteForms.forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            Swal.fire({
                title: 'Yakin ingin menghapus data ini?',
                text: "Data akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#aaa',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
});
</script>
@endpush

@endsection
