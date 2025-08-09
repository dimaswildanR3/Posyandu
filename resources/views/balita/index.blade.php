@extends('layouts.admin')

@section('content')
<style>
    .border-left-primary {
      border-left: 0.25rem solid #fd6bc5 !important;
    }
    .border-left-secondary {
      border-left: 0.25rem solid #858796 !important;
    }
    .border-left-success {
      border-left: 0.25rem solid #1cc88a !important;
    }
    .border-left-info {
      border-left: 0.25rem solid #36b9cc !important;
    }
    .border-left-warning {
      border-left: 0.25rem solid #f6c23e !important;
    }
    .border-left-danger {
      border-left: 0.25rem solid #e74a3b !important;
    }
    .border-left-light {
      border-left: 0.25rem solid #f8f9fc !important;
    }
    .border-left-dark {
      border-left: 0.25rem solid #5a5c69 !important;
    }
</style>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="/dashboard" style="color: #fd6bc5">Dashboard</a></li>
      <li class="breadcrumb-item active" aria-current="page">Data Anak</li>
    </ol>
</nav>

<div class="">
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    <div class="card border-left-primary shadow p-3 mb-5 bg-white rounded">
        {{-- Tombol Tambah Data hanya untuk selain ortu --}}
        @if(Auth::user()->role !== 'ortu')
        <div class="d-flex justify-content-lg-end mb-3">
            <a class="btn btn-outline-secondary" href="/balita/create">
                <span class="icon text">
                    <i class="fas fa-plus"></i>
                </span>Tambah Data
            </a>
        </div>
        @endif

        <div class="table-responsive">
            <table class="table table-hover" id="dataTable" width="100%" cellspacing="0">
                <thead style="background: #fd6bc5; color: white;">
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Nama Anak</th>
                        <th scope="col">Tempat Lahir</th>
                        <th scope="col">Tanggal Lahir</th>
                        <th scope="col">Jenis Kelamin</th>
                        <th scope="col">Nama Orang Tua</th>
                        @if(Auth::user()->role !== 'ortu')
                            <th scope="col">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach($balita as $key => $item)
                    <tr>
                        <th scope="row">{{ $key + $balita->firstItem() }}</th>
                        <td>{{ $item->nama_balita }}</td>
                        <td>{{ $item->tpt_lahir }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->tgl_lahir)->format('d F Y') }}</td>
                        <td>{{ $item->jenis_kelamin }}</td>
                        <td>{{ $item->orang_tua_id ?? '-' }}</td>
                        @if(Auth::user()->role !== 'ortu')
                        <td>
                            <form action="/balita/{{ $item->id }}" method="post" class="d-inline form-delete">
                                @csrf
                                @method('delete')
                                <button type="submit" class="btn btn-danger" title="Hapus Data">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                            <a href="/balita/{{ $item->id }}/edit" class="btn btn-primary" title="Edit Data">
                                <i class="fas fa-edit"></i>
                            </a>
                        </td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $balita->links() }}
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const deleteForms = document.querySelectorAll('.form-delete');

    deleteForms.forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault(); // cegah submit langsung

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
                    form.submit(); // submit form jika dikonfirmasi
                }
            });
        });
    });
});
</script>
@endsection
