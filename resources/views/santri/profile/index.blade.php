@extends('layouts.dashboard')

@section('title', 'Profil Saya')
@section('judul', 'Profil Pendaftar')

@section('content')
    <div class="row">
        <div class="col-md-8">

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="card shadow-sm border-0">
                <div class="card-header">
                    <strong>Informasi Akun</strong>
                </div>

                <div class="card-body">
                    <form action="{{ route('santri.profile.update') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">ID Pendaftaran</label>
                            <input type="text" class="form-control" value="{{ $user->registration_id }}" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}"
                                required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control"
                                value="{{ old('email', $user->email) }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">No. Telepon</label>
                            <input type="text" name="no_telp" class="form-control"
                                value="{{ old('no_telp', $user->no_telp) }}">
                        </div>

                        <hr>

                        <div class="mb-3">
                            <label class="form-label">Password Baru</label>
                            <input type="password" name="password" class="form-control">
                            <small class="text-muted">Kosongkan jika tidak ingin mengganti</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" class="form-control">
                        </div>

                        <div class="text-end">
                            <button class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
@endsection
