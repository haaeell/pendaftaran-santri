@extends('layouts.dashboard')

@section('title', 'Data Pendaftar')
@section('judul', 'Data Pendaftar')

@section('content')

    <div class="card shadow-sm border-0">

        <div class="card-header">
            <h5 class="fw-bold mb-0">Data Pendaftar</h5>
        </div>

        <div class="card-body">

            {{-- ======================= TAB NAV ======================= --}}
            <ul class="nav nav-tabs mb-4" id="pendaftarTab" role="tablist">
                @php
                    $tabs = [
                        '' => 'Semua',
                        'belum_diterima' => 'Belum Diproses',
                        'lolos_seleksi' => 'Lolos Seleksi',
                        'tidak_lolos_seleksi' => 'Tidak Lolos',
                        'diterima' => 'Diterima',
                    ];
                @endphp

                @foreach ($tabs as $key => $label)
                    <li class="nav-item" role="presentation">
                        <a class="nav-link {{ $status === $key ? 'active' : '' }}"
                            href="{{ route('admin.pendaftar.index', ['status' => $key]) }}">
                            {{ $label }}
                        </a>
                    </li>
                @endforeach
            </ul>

            {{-- ======================= TABLE ======================= --}}
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">#</th>
                            <th>Nama</th>
                            <th>No. Pendaftaran</th>
                            <th>Pendidikan Tujuan</th>
                            <th>Status Seleksi</th>
                            <th width="10%">Aksi</th>

                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($santri as $i => $s)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $s->name }}</td>
                                <td>{{ $s->registration_id }}</td>
                                <td>{{ $s->dataDiri->pendidikan_tujuan }}</td>
                                <td>
                                    @php
                                        $st = $s->dataDiri->status_seleksi;
                                        $badge = [
                                            'belum_diterima' => 'secondary',
                                            'lolos_seleksi' => 'success',
                                            'tidak_lolos_seleksi' => 'danger',
                                            'diterima' => 'primary',
                                        ][$st];
                                    @endphp

                                    <span class="badge bg-{{ $badge }} text-white">
                                        {{ strtoupper(str_replace('_', ' ', $st)) }}
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-info" data-bs-toggle="modal"
                                        data-bs-target="#modalDetail{{ $s->id }}">
                                        <i class="fas fa-eye"></i> Detail
                                    </button>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    Tidak ada data untuk kategori ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

            @foreach ($santri as $s)
                <div class="modal fade" id="modalDetail{{ $s->id }}" tabindex="-1">
                    <div class="modal-dialog modal-lg modal-dialog-scrollable">
                        <div class="modal-content">

                            <div class="modal-header">
                                <h5 class="modal-title">
                                    Detail Data Pendaftar
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body">
                                @php $d = $s->dataDiri; @endphp

                                @if (!$d)
                                    <div class="alert alert-warning">
                                        Data diri belum lengkap.
                                    </div>
                                @else
                                    {{-- ================= DATA AKUN ================= --}}
                                    <h6 class="fw-bold border-bottom pb-1 mb-2">Data Akun</h6>
                                    <table class="table table-sm table-borderless">
                                        <tr>
                                            <td width="35%">Nama</td>
                                            <td>: {{ $s->name }}</td>
                                        </tr>
                                        <tr>
                                            <td>Email</td>
                                            <td>: {{ $s->email }}</td>
                                        </tr>
                                        <tr>
                                            <td>No. Telp</td>
                                            <td>: {{ $s->no_telp }}</td>
                                        </tr>
                                        <tr>
                                            <td>No. Pendaftaran</td>
                                            <td>: {{ $s->registration_id }}</td>
                                        </tr>
                                    </table>

                                    {{-- ================= DATA PRIBADI ================= --}}
                                    <h6 class="fw-bold border-bottom pb-1 mt-3 mb-2">Data Pribadi</h6>
                                    <table class="table table-sm table-borderless">
                                        <tr>
                                            <td width="35%">Nama Lengkap</td>
                                            <td>: {{ $d->nama_lengkap }}</td>
                                        </tr>
                                        <tr>
                                            <td>Tempat Lahir</td>
                                            <td>: {{ $d->kabupaten_lahir }}</td>
                                        </tr>
                                        <tr>
                                            <td>Tanggal Lahir</td>
                                            <td>: {{ optional($d->tanggal_lahir)->format('d M Y') }}</td>
                                        </tr>
                                        <tr>
                                            <td>Jenis Kelamin</td>
                                            <td>: {{ ucfirst($d->jenis_kelamin) }}</td>
                                        </tr>
                                        <tr>
                                            <td>Alamat Domisili</td>
                                            <td>: {{ $d->alamat_domisili }}</td>
                                        </tr>
                                        <tr>
                                            <td>NIK</td>
                                            <td>: {{ $d->nik }}</td>
                                        </tr>
                                        <tr>
                                            <td>NISN</td>
                                            <td>: {{ $d->nisn }}</td>
                                        </tr>
                                    </table>

                                    {{-- ================= DATA WALI ================= --}}
                                    <h6 class="fw-bold border-bottom pb-1 mt-3 mb-2">Data Wali</h6>
                                    <table class="table table-sm table-borderless">
                                        <tr>
                                            <td width="35%">Nama Wali</td>
                                            <td>: {{ $d->nama_wali }}</td>
                                        </tr>
                                        <tr>
                                            <td>Hubungan</td>
                                            <td>: {{ $d->hubungan_wali }}</td>
                                        </tr>
                                        <tr>
                                            <td>No. Telp Wali</td>
                                            <td>: {{ $d->no_telp_wali }}</td>
                                        </tr>
                                        <tr>
                                            <td>Penghasilan</td>
                                            <td>: {{ $d->rata_rata_penghasilan }}</td>
                                        </tr>
                                    </table>

                                    {{-- ================= RIWAYAT & INFO ================= --}}
                                    <h6 class="fw-bold border-bottom pb-1 mt-3 mb-2">Informasi Tambahan</h6>
                                    <table class="table table-sm table-borderless">
                                        <tr>
                                            <td width="35%">Prestasi</td>
                                            <td>:
                                                {{ collect([$d->prestasi_1, $d->prestasi_2, $d->prestasi_3])->filter()->implode(', ') ?:'-' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Penyakit Khusus</td>
                                            <td>: {{ $d->penyakit_khusus ?: '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td>Pendidikan Tujuan</td>
                                            <td>: {{ $d->pendidikan_tujuan }}</td>
                                        </tr>
                                        <tr>
                                            <td>Status Seleksi</td>
                                            <td>
                                                <span class="badge bg-primary  text-white">
                                                    {{ strtoupper(str_replace('_', ' ', $d->status_seleksi)) }}
                                                </span>
                                            </td>
                                        </tr>
                                    </table>

                                    {{-- ================= FILE ================= --}}
                                    <h6 class="fw-bold border-bottom pb-1 mt-3 mb-2">Berkas</h6>
                                    <div class="row">
                                        @if ($d->foto_diri)
                                            <div class="col-md-6 mb-2">
                                                <label class="fw-semibold">Foto Diri</label><br>
                                                <img src="{{ asset('storage/' . $d->foto_diri) }}"
                                                    class="img-fluid rounded border">
                                            </div>
                                        @endif

                                        @if ($d->foto_kk)
                                            <div class="col-md-6 mb-2">
                                                <label class="fw-semibold">Foto KK</label><br>
                                                <img src="{{ asset('storage/' . $d->foto_kk) }}"
                                                    class="img-fluid rounded border">
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    Tutup
                                </button>
                            </div>

                        </div>
                    </div>
                </div>
            @endforeach

        </div>
    </div>

@endsection
