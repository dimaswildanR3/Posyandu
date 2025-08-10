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
@if(Auth::user()->role !== 'ortu')
<div class="card shadow p-3 mb-5 bg-white rounded border-left-primary">
  <div class="card-header">
    Jadwal Pelayanan Posyandu
  </div>
  <div class="card-body">
    <div class="col-md-3">
    <form action="/blog" method="post" enctype="multipart/form-data">
        @csrf
        @method('post')
        <div class="form-group">
          <label for="">Jadwal Dibuat Oleh</label>
          <p>{{Auth::user()->name}}</p>
          <input type="number" name="user_id" value="{{ Auth::user()->id }}" hidden>
        </div>
        <div class="form-group">
            <label for="tanggal_kegiatan">Tanggal Pelayanan</label>
            <div class="input-group mb-3">
                <input autocomplete="off" class="dateselect form-control @error('tanggal_kegiatan') is-invalid @enderror" name="tanggal_kegiatan" type="text" placeholder="Tahun-Bulan-Tanggal" >
                <div class="input-group-append">
                    <span class="input-group-text" id="basic-addon2"><i class="fas fa-calendar"></i></span>
                </div>
                @error('tanggal_kegiatan')
                <div class="invalid-feedback">
                    {{$message}}
                </div>
                @enderror
            </div>
        </div>
        <div class="form-group">
    <label for="bidan_id">Nama Bidan</label>
    <select name="bidan_id" id="bidan_id" class="form-control @error('bidan_id') is-invalid @enderror">
        <option value="">-- Pilih Bidan --</option>
        @foreach($bidans as $bidan)
            <option value="{{ $bidan->id }}" data-nama="{{ $bidan->nama_lengkap }}">{{ $bidan->nama_lengkap }}</option>
        @endforeach
    </select>
    <input type="hidden" name="nama_kegiatan" id="nama_kegiatan" value="">
    @error('bidan_id')
    <div class="invalid-feedback">
        {{ $message }}
    </div>
    @enderror
</div>

<div class="form-group">
    <label for="waktu">Jam Pelayanan Mulai</label>
    <input autocomplete="off" placeholder="09:10" type="time" class="form-control @error('waktu') is-invalid @enderror" name="waktu" id="waktu" value="{{ old('waktu') }}">

    @error('waktu')
    <div class="invalid-feedback">
        {{ $message }}
    </div>
    @enderror
</div>

<div class="form-group">
    <label for="waktu_akhir">Jam Pelayanan Akhir (opsional)</label>
    <input autocomplete="off" placeholder="10:30" type="time" class="form-control @error('waktu_akhir') is-invalid @enderror" name="waktu_akhir" id="waktu_akhir" value="{{ old('waktu_akhir') }}">

    @error('waktu_akhir')
    <div class="invalid-feedback">
        {{ $message }}
    </div>
    @enderror
</div>

        <button type="submit" class="btn btn-outline-success">Simpan</button>
    </form>
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
          @php
              $i=1;
          @endphp
          @foreach ($jadwal as $item) 
          <tr>
          <th scope="row">{{$i++}}</th>
          <td>{{$item->user->name}}</td>
          <td>{{date('d F Y',strtotime($item->tanggal_kegiatan))}}</td>
            <td>{{$item->nama_kegiatan}}</td>
            <td>
    {{ $item->waktu }} 
    @if($item->waktu_akhir)
        - {{ $item->waktu_akhir }}
    @endif
</td>

            @if(Auth::user()->role !== 'ortu')
            <td>
              <form action="/blog/{{$item->id}}" method="post" class="d-inline form-delete">
              @csrf
                @method('delete')
                <button type="submit" class="btn btn-danger" ><i class="fas fa-trash-alt"></i></button>
              </form>
              {{-- <a href="/blog/{{$item->id}}" class="btn btn-primary" ><i class="fas fa-search"></i></a>  --}}
              <a href="/blog/{{$item->id}}/edit" class="btn btn-primary" ><i class="fas fa-edit"></i></a> 
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
        const bidanSelect = document.getElementById('bidan_id');
        const namaKegiatanInput = document.getElementById('nama_kegiatan');

        bidanSelect.addEventListener('change', function () {
            const selectedOption = this.options[this.selectedIndex];
            const nama = selectedOption.getAttribute('data-nama');
            namaKegiatanInput.value = nama || '';
        });
    });
</script>
@endpush

@endsection

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
