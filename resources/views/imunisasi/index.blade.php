@extends('layouts.admin')

@section('content')
{{-- Bootstrap --}}
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<div class="container">
    <h4 class="mb-3">Data Imunisasi</h4>

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    {{-- Tombol Tambah --}}
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createModal">
        + Tambah Imunisasi
    </button>

    {{-- Tabel --}}
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Nama Balita</th>
                <th>Jenis Imunisasi</th>
                <th>Tanggal</th>
                <th>Keterangan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($imunisasis as $imunisasi)
                <tr>
                    <td>{{ $imunisasi->balita->nama_balita }}</td>
                    <td>{{ $imunisasi->jenis_imunisasi }}</td>
                    <td>{{ \Carbon\Carbon::parse($imunisasi->tanggal_imunisasi)->format('d-m-Y') }}</td>
                    <td>{{ $imunisasi->keterangan ?? '-' }}</td>
                    <td>
                        <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#showModal{{ $imunisasi->id }}">Lihat</button>
                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal{{ $imunisasi->id }}">Edit</button>
                        <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $imunisasi->id }}">Hapus</button>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center">Belum ada data</td></tr>
            @endforelse
        </tbody>
    </table>

    {{ $imunisasis->links() }}

    {{-- Semua modal dipindah ke sini, setelah tabel --}}
    @foreach ($imunisasis as $imunisasi)
        {{-- Modal Show --}}
        <div class="modal fade" id="showModal{{ $imunisasi->id }}" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Detail Imunisasi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p><strong>Nama Balita:</strong> {{ $imunisasi->balita->nama_balita }}</p>
                        <p><strong>Jenis Imunisasi:</strong> {{ $imunisasi->jenis_imunisasi }}</p>
                        <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($imunisasi->tanggal_imunisasi)->format('d-m-Y') }}</p>
                        <p><strong>Keterangan:</strong> {{ $imunisasi->keterangan ?? '-' }}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal Edit --}}
        <div class="modal fade" id="editModal{{ $imunisasi->id }}" tabindex="-1">
            <div class="modal-dialog">
                <form action="{{ route('imunisasi.update', $imunisasi->id) }}" method="POST" class="modal-content">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Imunisasi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Balita</label>
                            <select name="balita_id" class="form-control" required>
                                @foreach ($balitas as $balita)
                                    <option value="{{ $balita->id }}" {{ $imunisasi->balita_id == $balita->id ? 'selected' : '' }}>
                                        {{ $balita->nama_balita }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Jenis Imunisasi</label>
                            <input type="text" name="jenis_imunisasi" class="form-control" value="{{ $imunisasi->jenis_imunisasi }}" required>
                        </div>
                        <div class="mb-3">
                            <label>Tanggal Imunisasi</label>
                            <input type="date" name="tanggal_imunisasi" class="form-control" value="{{ $imunisasi->tanggal_imunisasi }}" required>
                        </div>
                        <div class="mb-3">
                            <label>Keterangan</label>
                            <textarea name="keterangan" class="form-control">{{ $imunisasi->keterangan }}</textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-warning">Simpan Perubahan</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Modal Delete --}}
        <div class="modal fade" id="deleteModal{{ $imunisasi->id }}" tabindex="-1">
            <div class="modal-dialog">
                <form action="{{ route('imunisasi.destroy', $imunisasi->id) }}" method="POST" class="modal-content">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header">
                        <h5 class="modal-title">Hapus Data</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        Yakin ingin menghapus imunisasi <strong>{{ $imunisasi->jenis_imunisasi }}</strong> untuk <strong>{{ $imunisasi->balita->nama_balita }}</strong>?
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    @endforeach

    {{-- Modal Create --}}
    <div class="modal fade" id="createModal" tabindex="-1">
        <div class="modal-dialog">
            <form action="{{ route('imunisasi.store') }}" method="POST" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Imunisasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Balita</label>
                        <select name="balita_id" class="form-control" required>
                            <option value="">-- Pilih Balita --</option>
                            @foreach ($balitas as $balita)
                                <option value="{{ $balita->id }}">{{ $balita->nama_balita }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Jenis Imunisasi</label>
                        <input type="text" name="jenis_imunisasi" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Tanggal Imunisasi</label>
                        <input type="date" name="tanggal_imunisasi" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Keterangan</label>
                        <textarea name="keterangan" class="form-control"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Simpan</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Script untuk reset form dan fokus input --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Reset form saat modal ditutup (Edit dan Create)
    const modals = document.querySelectorAll('.modal');
    modals.forEach(modal => {
        modal.addEventListener('hidden.bs.modal', function () {
            const forms = modal.querySelectorAll('form');
            forms.forEach(form => form.reset());
        });
    });

    // Fokus input pertama saat modal edit dibuka
    const editButtons = document.querySelectorAll('button[data-bs-target^="#editModal"]');
    editButtons.forEach(button => {
        button.addEventListener('click', function() {
            const targetModalId = button.getAttribute('data-bs-target');
            const modal = document.querySelector(targetModalId);
            if (modal) {
                modal.addEventListener('shown.bs.modal', () => {
                    const firstInput = modal.querySelector('input, select, textarea');
                    if (firstInput) firstInput.focus();
                }, { once: true });
            }
        });
    });
});
</script>
@endsection
