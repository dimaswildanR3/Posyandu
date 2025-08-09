@extends('layouts.admin')

@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/dashboard" style="color: #ff7ec9">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="/akun" style="color: #ff7ec9">Data Akun User</a></li>
        <li class="breadcrumb-item active" aria-current="page">Daftar Akun</li>
    </ol>
</nav>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header" style="background: #ff7ec9; color: white">Daftar Akun</div>

                <div class="card-body">
                    <form method="POST" action="/akun">
                        @csrf

                        {{-- Nama --}}
                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">Nama</label>
                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                                    name="name" value="{{ old('name') }}" required autofocus>
                                @error('name')<span class="invalid-feedback"><strong>{{ $message }}</strong></span>@enderror
                            </div>
                        </div>

                        {{-- Email --}}
                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">Email</label>
                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                    name="email" value="{{ old('email') }}" required>
                                @error('email')<span class="invalid-feedback"><strong>{{ $message }}</strong></span>@enderror
                            </div>
                        </div>

                        {{-- Password --}}
                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>
                            <div class="col-md-6">
                                <input id="password" type="password"
                                    class="form-control @error('password') is-invalid @enderror" name="password" required>
                                @error('password')<span class="invalid-feedback"><strong>{{ $message }}</strong></span>@enderror
                            </div>
                        </div>

                        {{-- Konfirmasi Password --}}
                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">Konfirmasi Password</label>
                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control"
                                    name="password_confirmation" required>
                            </div>
                        </div>

                        {{-- Role --}}
                        <div class="form-group row">
                            <label for="role" class="col-md-4 col-form-label text-md-right">Role</label>
                            <div class="col-md-6">
                                <select id="role" name="role" class="form-control @error('role') is-invalid @enderror" required>
                                    <option value="">-- Pilih Role --</option>
                                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="ortu" {{ old('role') == 'ortu' ? 'selected' : '' }}>Orangtua</option>
                                </select>
                                @error('role')<span class="invalid-feedback"><strong>{{ $message }}</strong></span>@enderror
                            </div>
                        </div>

                        {{-- Pilih Orang Tua --}}
                        <div class="form-group row" id="orangtua-container" style="display: none;">
                            <label for="orangtua_id" class="col-md-4 col-form-label text-md-right">Pilih Orang Tua</label>
                            <div class="col-md-6">
                                <select id="orangtua_id" name="orangtua_id" class="form-control @error('orangtua_id') is-invalid @enderror">
                                    <option value="">-- Pilih Orang Tua --</option>
                                    @foreach($orangtuaList as $ortu)
                                        <option value="{{ $ortu->id }}" {{ old('orangtua_id') == $ortu->id ? 'selected' : '' }}>
                                            {{ $ortu->nama_balita }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="invalid-feedback d-none" id="orangtua-error">
                                    <strong>Field ini wajib diisi jika Role adalah Orangtua.</strong>
                                </span>
                                @error('orangtua_id')<span class="invalid-feedback"><strong>{{ $message }}</strong></span>@enderror
                            </div>
                        </div>

                        <!-- {{-- Username --}}
                        <div class="form-group row">
                            <label for="username" class="col-md-4 col-form-label text-md-right">Username</label>
                            <div class="col-md-6">
                                <input id="username" type="text" class="form-control @error('username') is-invalid @enderror"
                                    name="username" value="{{ old('username') }}" required>
                                @error('username')<span class="invalid-feedback"><strong>{{ $message }}</strong></span>@enderror
                            </div>
                        </div> -->

                        {{-- Tombol --}}
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">Daftar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- JS --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const roleSelect = document.getElementById('role');
        const orangtuaContainer = document.getElementById('orangtua-container');
        const orangtuaSelect = document.getElementById('orangtua_id');
        const orangtuaError = document.getElementById('orangtua-error');
        const form = document.querySelector('form');

        function toggleOrangtuaField() {
            if (roleSelect.value === 'ortu') {
                orangtuaContainer.style.display = 'flex';
            } else {
                orangtuaContainer.style.display = 'none';
                orangtuaSelect.value = '';
                orangtuaError.classList.add('d-none');
                orangtuaSelect.classList.remove('is-invalid');
            }
        }

        roleSelect.addEventListener('change', toggleOrangtuaField);
        toggleOrangtuaField();

        form.addEventListener('submit', function(e) {
            if (roleSelect.value === 'ortu' && !orangtuaSelect.value) {
                e.preventDefault();
                orangtuaError.classList.remove('d-none');
                orangtuaSelect.classList.add('is-invalid');
            }
        });
    });
</script>
@endsection
