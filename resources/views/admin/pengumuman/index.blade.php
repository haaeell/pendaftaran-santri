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

        <div class="alert {{ $pengumuman ? 'alert-success' : 'alert-warning' }}">
            Status Pengumuman:
            <strong>{{ $pengumuman ? 'Sudah Diumumkan' : 'Belum Diumumkan' }} untuk tahun akademik
                {{ $tahunAktif->tahun }}</strong>
        </div>

        @if (!$pengumuman)
            <div>
                <button class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#modalUmumkan">
                    <i class="fas fa-bullhorn"></i> Umumkan Hasil Seleksi
                </button>
            </div>
        @endif
        {{-- MODAL UMUMKAN + CATATAN --}}
        <div class="modal fade" id="modalUmumkan" tabindex="-1">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">

                    <form action="{{ route('admin.pengumuman.umumkan') }}" method="POST">
                        @csrf

                        <div class="modal-header">
                            <h5 class="modal-title fw-bold">
                                <i class="bi bi-megaphone-fill"></i> Umumkan Hasil Seleksi
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">

                            <div class="alert alert-info">
                                Catatan ini akan ditampilkan kepada <strong>pendaftar yang DITERIMA</strong>
                                sebagai instruksi lanjutan.
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Catatan / Instruksi</label>
                                <textarea name="note" rows="5" class="form-control" style="height: auto;">
                            1. Melakukan daftar ulang pada tanggal ...
                            2. Membawa berkas asli
                            3. Pembayaran tahap selanjutnya ...
                            </textarea>
                            </div>


                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                Batal
                            </button>
                            <button class="btn btn-primary">
                                <i class="bi bi-bullhorn"></i> Umumkan Sekarang
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>

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
