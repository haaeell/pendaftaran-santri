@extends('layouts.dashboard')

@section('title', 'Status Seleksi')
@section('judul', 'Status Seleksi')

@section('content')

    @php
        // STATUS FINAL DARI CONTROLLER (SUDAH DIKUNCI)
        $status = $statusSeleksi ?? 'belum_diterima';

        $mapColor = [
            'belum_diterima' => 'secondary',
            'tidak_lolos_seleksi' => 'danger',
            'gugur' => 'danger',
            'lolos_seleksi' => 'success',
            'diterima' => 'success',
        ];

        $mapLabel = [
            'belum_diterima' => 'MENUNGGU PENGUMUMAN',
            'tidak_lolos_seleksi' => 'BELUM LOLOS',
            'gugur' => 'GUGUR',
            'lolos_seleksi' => 'LOLOS SELEKSI',
            'diterima' => 'DITERIMA',
        ];

        $color = $mapColor[$status] ?? 'dark';
        $label = $mapLabel[$status] ?? strtoupper($status);
    @endphp

    <div class="d-flex justify-content-center">
        <div class="card shadow-lg p-4" style="width: 1100px; max-width: 100%;">

            @if ($pengumuman)
                <div class="text-center mb-4">
                    <div class="p-2 text-white fw-bold bg-{{ $color }}" style="border-radius: 6px;">
                        {{ $label }}
                    </div>
                </div>
            @endif

            {{-- IDENTITAS SANTRI --}}
            <table class="table table-bordered mb-4">
                <tr>
                    <th width="35%">Nama Lengkap</th>
                    <td>{{ $user->name }}</td>
                </tr>
                <tr>
                    <th>Sekolah Tujuan</th>
                    <td>{{ $user->dataDiri->pendidikan_tujuan }}</td>
                </tr>
                <tr>
                    <th>ID Pendaftaran</th>
                    <td>{{ $user->registration_id }}</td>
                </tr>
            </table>

            {{-- JIKA BELUM ADA PENGUMUMAN --}}
            @if (!$pengumuman)
                <div class="alert alert-secondary text-center fw-bold">
                    TERUS PANTAU PENGUMUMAN UNTUK MELIHAT HASIL SELEKSI.
                </div>
            @else
                @if ($status === 'belum_diterima')
                    <div class="alert alert-secondary text-center fw-bold">
                        TERUS PANTAU PENGUMUMAN UNTUK MELIHAT HASIL SELEKSI.
                    </div>

                    {{-- TIDAK LOLOS / GUGUR --}}
                @elseif (in_array($status, ['tidak_lolos_seleksi', 'gugur']))
                    <div class="alert alert-danger text-center fw-bold">
                        ANDA TIDAK LOLOS SELEKSI.
                        <br>
                        @if ($status === 'gugur')
                            <small>Alasan: tidak mengikuti tes seleksi.</small>
                        @endif
                    </div>

                    {{-- LOLOS SELEKSI --}}
                @elseif ($status === 'lolos_seleksi')
                    <h5 class="fw-bold mb-3">Informasi Pembayaran Daftar Ulang</h5>

                    <table class="table table-bordered">
                        <tr>
                            <th width="40%">Biaya Daftar Ulang</th>
                            <td>
                                @if ($biayaDaftarUlang)
                                    Rp {{ number_format($biayaDaftarUlang->nominal, 0, ',', '.') }}
                                @else
                                    <span class="text-danger">Belum diatur admin</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Status Pembayaran</th>
                            <td>
                                @if (!$pembayaranDU)
                                    <span class="badge bg-danger">Belum Membayar</span>
                                @else
                                    <span
                                        class="badge
                                    {{ $pembayaranDU->status == 'menunggu'
                                        ? 'bg-warning text-dark'
                                        : ($pembayaranDU->status == 'diterima'
                                            ? 'bg-success'
                                            : 'bg-danger') }}">
                                        {{ strtoupper($pembayaranDU->status) }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                    </table>

                    {{-- REKENING --}}
                    <h6 class="fw-bold mt-4">Rekening Pembayaran</h6>
                    <table class="table table-striped">
                        @foreach ($rekening as $r)
                            <tr>
                                <td width="30%"><strong>{{ $r->bank }}</strong></td>
                                <td>
                                    {{ $r->nomor_rekening }}
                                    <br>
                                    <small>{{ $r->atas_nama }}</small>
                                </td>
                            </tr>
                        @endforeach
                    </table>

                    <h6 class="fw-bold mt-4 text-center">Pembayaran via QRIS</h6>

                    <div class="row justify-content-center  ">
                        @foreach ($qris as $q)
                            @if ($q->aktif)
                                <div class="col-md-4 text-center mb-3">
                                    <img src="{{ asset('storage/' . $q->image) }}" class="img-fluid rounded shadow"
                                        style="cursor:pointer" data-bs-toggle="modal"
                                        data-bs-target="#modalQris{{ $q->id }}">
                                    <div class="mt-2 fw-bold">{{ $q->nama }}</div>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    {{-- FORM UPLOAD --}}
                    @if (!$pembayaranDU || $pembayaranDU->status === 'ditolak')
                        <hr>
                        <form action="{{ route('santri.daftarulang.upload') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf

                            <div class="mb-3">
                                <label class="fw-bold">Pilih Rekening Tujuan</label>
                                <select name="rekening_id" class="form-control" required>
                                    <option value="">-- Pilih Rekening --</option>
                                    @foreach ($rekening as $r)
                                        <option value="{{ $r->id }}">
                                            {{ $r->bank }} - {{ $r->nomor_rekening }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <input type="hidden" name="nominal_bayar" value="{{ $biayaDaftarUlang->nominal }}">

                            <div class="mb-3">
                                <label class="fw-bold">Upload Bukti Pembayaran</label>
                                <input type="file" name="bukti_transfer" class="form-control" required>
                            </div>

                            <button class="btn btn-primary w-100">
                                Upload Bukti Pembayaran
                            </button>
                        </form>
                    @endif

                    {{-- DITERIMA --}}
                @elseif ($status === 'diterima')
                    <div class="alert alert-success text-center fw-bold fs-5">
                        SELAMAT!
                        <br>ANDA RESMI MENJADI SANTRI BARU.

                    </div>
                    @if (!empty($pengumuman->note))
                        <div class="card border-success shadow-sm">
                            <div class="card-header text-dark fw-bold">
                                📌 Catatan Penting dari Panitia
                            </div>
                            <div class="card-body">
                                <div class="fw-bold text-dark">
                                    {!! nl2br(e($pengumuman->note)) !!}
                                </div>
                            </div>
                        </div>
                    @endif
                @endif

            @endif

        </div>
    </div>

@endsection
