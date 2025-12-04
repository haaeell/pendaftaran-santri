@extends('layouts.dashboard')

@section('title', 'Verifikasi Pembayaran')
@section('judul', 'Verifikasi Pembayaran Santri')

@section('content')

    <div class="card shadow-sm border-0">
        <div class="card-header bg-light fw-bold">
            Verifikasi Pembayaran Santri
        </div>

        <div class="card-body">

            {{-- ALERT --}}
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            {{-- TAB MENU --}}
            <ul class="nav nav-tabs mb-3" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#registrasi">
                        Pembayaran Registrasi
                    </button>
                </li>

                <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#daftarUlang">
                        Pembayaran Daftar Ulang
                    </button>
                </li>
            </ul>

            {{-- TAB CONTENT --}}
            <div class="tab-content">

                {{-- ================== TAB REGISTRASI ================== --}}
                <div class="tab-pane fade show active" id="registrasi">

                    @include('admin.pembayaran._table', [
                        'list' => $registrasi,
                    ])

                </div>


                {{-- ================== TAB DAFTAR ULANG ================== --}}
                <div class="tab-pane fade" id="daftarUlang">

                    @include('admin.pembayaran._table', [
                        'list' => $daftarUlang,
                    ])

                </div>

            </div>

        </div>
    </div>

@endsection
