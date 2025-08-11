@extends('layouts.admin')

@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="/dashboard" style="color: #fd6bc5">Dashboard</a></li>
      <li class="breadcrumb-item active" aria-current="page">Filter Cetak Laporan Penimbangan</li>
    </ol>
</nav>

<div class="card shadow-sm border-left-primary">
    <div class="card-body">
        <h5 class="mb-3"><i class="fas fa-filter"></i> Filter Cetak Laporan</h5>
        <form action="{{ url('/penimbangan/cetak') }}" method="get" target="_blank">
            <div class="row g-3 align-items-end">
                <div class="col-md-6">
                    <label for="tahun" class="form-label">Pilih Tahun</label>
                    <select name="tahun" class="form-select">
                        <option value="">Semua Tahun</option>
                        @for ($i = now()->year; $i >= 2018; $i--)
                            <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-6">
                   <label for="status" class="form-label">Filter Status Gizi / Stunting</label>
                   <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <optgroup label="Status Gizi">
                            <option value="Gizi Normal / Baik">Normal</option>
                            <option value="Gizi Kurang">Gizi Kurang</option>
                            <option value="Gizi Buruk">Gizi Buruk</option>
                            <option value="Risiko Gizi Lebih">Gizi Lebih</option>
                        </optgroup>
                        <optgroup label="Status Stunting">
                            <option value="Stunting Berat">Stunting Berat</option>
                            <option value="Risiko Stunting">Risiko Stunting</option>
                            <option value="Normal">Normal</option>
                            <option value="Tinggi">Tinggi</option>
                        </optgroup>
                   </select>
                </div>
                <div class="col-md-12">
                    <button type="submit" class="btn btn-success w-100 mt-2">
                        <i class="fas fa-file-pdf"></i> Cetak PDF
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
