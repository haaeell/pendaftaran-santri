@extends('layouts.dashboard')

@section('title', 'Pengumuman Seleksi')
@section('judul', 'Pengumuman Seleksi')

@section('content')

    <div class="card shadow p-4">

        <h4 class="fw-bold mb-3">Pengumuman Hasil Seleksi</h4>

        {{-- ALERT --}}
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        {{-- STATUS PENGUMUMAN --}}
        <div class="alert {{ $pengumuman ? 'alert-success' : 'alert-warning' }}">
            Status Pengumuman:
            <strong>{{ $pengumuman ? 'Sudah Diumumkan' : 'Belum Diumumkan' }} untuk tahun akademik
                {{ $tahunAktif->tahun }}</strong>
        </div>

        {{-- TOMBOL UMUMKAN --}}
        @if (!$pengumuman)
            <form action="{{ route('admin.pengumuman.umumkan') }}" method="POST">
                @csrf
                <button class="btn btn-primary mb-4">
                    <i class="fas fa-bullhorn"></i> Umumkan Hasil Seleksi
                </button>
            </form>
        @endif

        {{-- TABEL DAFTAR HASIL --}}
        <div class="table-responsive mt-3">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>No. Pendaftaran</th>
                        <th>Status Saat Ini</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($santri as $i => $s)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $s->name }}</td>
                            <td>{{ $s->registration_id }}</td>

                            <td>
                                @php $st = $s->dataDiri->status_seleksi; @endphp

                                @if ($st == 'belum_diterima')
                                    <span class="badge bg-secondary  text-white">Belum Diproses</span>
                                @elseif ($st == 'lolos_seleksi')
                                    <span class="badge bg-success  text-white">Lolos Seleksi</span>
                                @elseif ($st == 'tidak_lolos_seleksi')
                                    <span class="badge bg-danger  text-white">Tidak Lolos</span>
                                @elseif ($st == 'diterima')
                                    <span class="badge bg-success text-white">Diterima</span>
                                @elseif ($st == 'gugur')
                                    <span class="badge bg-dark  text-white">Gugur</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>

    </div>

@endsection
