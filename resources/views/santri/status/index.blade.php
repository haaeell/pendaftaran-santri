@extends('layouts.dashboard')

@section('title', 'Status Seleksi')
@section('judul', 'Status Seleksi')

@section('content')

    @php
        $status = $user->dataDiri->status_seleksi ?? 'belum_diterima';

        $color = [
            'belum_diterima' => 'secondary',
            'tidak_lolos_seleksi' => 'danger',
            'lolos_seleksi' => 'success',
            'diterima' => 'primary',
        ][$status];

        $label = [
            'belum_diterima' => 'MENUNGGU PENGUMUMAN',
            'tidak_lolos_seleksi' => 'BELUM LOLOS',
            'lolos_seleksi' => 'LOLOS SELEKSI',
            'diterima' => 'DITERIMA',
        ][$status];
    @endphp


    <div class="d-flex justify-content-center">
        <div class="card shadow-lg p-4" style="width: 1100px; max-width: 100%;">

            {{-- HEADER STATUS --}}
            <div class="text-center mb-4">
                <div class="p-2 text-white fw-bold bg-{{ $color }}" style="border-radius: 6px;">
                    {{ $label }}
                </div>
            </div>

            {{-- IDENTITAS SANTRI --}}
            <table class="table table-bordered">
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

            {{-- ===================== CASE STATUS ===================== --}}

            {{-- BELUM PENGUMUMAN --}}
            @if ($status === 'belum_diterima')
                <div class="alert alert-secondary text-center fw-bold">
                    TERUS PANTAU PENGUMUMAN UNTUK MELIHAT HASIL SELEKSI.
                </div>

                {{-- TIDAK LOLOS --}}
            @elseif ($status === 'tidak_lolos_seleksi')
                <div class="alert alert-danger text-center fw-bold">
                    ANDA BELUM LOLOS. TETAP SEMANGAT!
                </div>

                {{-- LOLOS SELEKSI -> TAMPILKAN DAFTAR ULANG --}}
            @elseif ($status === 'lolos_seleksi' || $status === 'diterima')
                <h5 class="fw-bold mt-4 mb-3">Informasi Pembayaran Daftar Ulang</h5>

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
                                <span class="badge bg-danger text-white">Belum Membayar</span>
                            @else
                                <span
                                    class="badge 
                                {{ $pembayaranDU->status == 'menunggu'
                                    ? 'bg-warning text-dark'
                                    : ($pembayaranDU->status == 'diterima'
                                        ? 'bg-success'
                                        : 'bg-danger') }}">
                                    {{ ucfirst($pembayaranDU->status) }}
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
                            <td>{{ $r->nomor_rekening }} <br> <small>{{ $r->atas_nama }}</small></td>
                        </tr>
                    @endforeach
                </table>

                {{-- FORM UPLOAD JIKA BELUM DIBAYAR ATAU DITOLAK --}}
                @if (!$pembayaranDU || $pembayaranDU->status == 'ditolak')

                    <hr>

                    <form action="{{ route('santri.daftarulang.upload') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label class="fw-bold">Pilih Rekening Tujuan</label>
                            <select name="rekening_id" class="form-control" required>
                                <option value="">-- Pilih Rekening --</option>
                                @foreach ($rekening as $r)
                                    <option value="{{ $r->id }}">{{ $r->bank }} - {{ $r->nomor_rekening }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="fw-bold">Upload Bukti Pembayaran</label>
                            <input type="file" name="bukti_transfer" class="form-control" required>
                        </div>

                        <button class="btn btn-primary w-100">Upload Bukti Pembayaran</button>
                    </form>

                @endif

                {{-- SUDAH DITERIMA --}}
            @elseif ($status === 'diterima')
                <div class="alert alert-primary text-center fw-bold fs-5">
                    SELAMAT! ANDA RESMI MENJADI SANTRI BARU.
                </div>
            @endif

        </div>
    </div>

@endsection
