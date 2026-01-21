@extends('layouts.dashboard')

@section('title', $role === 'admin' ? 'Dashboard Admin' : 'Dashboard Santri')
@section('judul', $role === 'admin' ? 'Dashboard Admin' : 'Dashboard Santri')

@section('content')

    {{-- =========================================
    GLOBAL STYLE (Modern UI)
    ========================================= --}}
    <style>
        .page-title {
            font-size: 22px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 8px;
        }

        .sub-title {
            font-size: 14px;
            color: #64748b;
            margin-bottom: 25px;
        }

        .dash-card {
            border: none;
            border-radius: 20px;
            padding: 22px;
            background: #ffffff;
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.08);
            transition: .2s ease;
            position: relative;
        }

        .dash-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 28px rgba(0, 0, 0, 0.12);
        }

        .icon-box {
            width: 52px;
            height: 52px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: absolute;
            right: 20px;
            top: 20px;
            font-size: 25px;
            color: white;
        }

        .icon-blue {
            background: #3b82f6;
        }

        .icon-yellow {
            background: #f59e0b;
        }

        .icon-green {
            background: #10b981;
        }

        .icon-purple {
            background: #8b5cf6;
        }

        .dash-title {
            font-size: 13px;
            color: #94a3b8;
            font-weight: 600;
        }

        .dash-value {
            font-size: 32px;
            font-weight: 800;
            color: #0f172a;
            margin-top: 4px;
        }

        /* SANTRI LIST ITEM */
        .step-item {
            padding: 14px 18px;
            border-radius: 14px;
            background: #f8fafc;
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: 1px solid #e2e8f0;
        }

        .step-label {
            font-weight: 600;
            color: #334155;
        }

        .badge-modern {
            padding: 6px 12px;
            border-radius: 25px;
            font-size: 12px;
            font-weight: 600;
        }

        .bg-gray {
            background: #cbd5e1;
            color: #334155;
        }

        .bg-info2 {
            background: #e0f2fe;
            color: #0369a1;
        }

        .bg-warning2 {
            background: #fef3c7;
            color: #b45309;
        }

        .bg-success2 {
            background: #d1fae5;
            color: #065f46;
        }

        .bg-primary2 {
            background: #e0e7ff;
            color: #3730a3;
        }
    </style>


    {{-- =====================================
    DASHBOARD ADMIN
    ===================================== --}}
    @if ($role === 'admin')
        <div class="page-title">Selamat Datang, Admin</div>
        <div class="sub-title">Berikut ringkasan aktivitas pendaftaran & seleksi.</div>
        @if ($tahunAktif)
            <div class="sub-title mb-3">
                Tahun Akademik Aktif:
                <strong>{{ $tahunAktif->tahun }}</strong>
            </div>
        @else
            <div class="alert alert-warning border-0 shadow-sm mb-3">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                Tahun akademik belum diaktifkan
            </div>
        @endif
        <div class="row g-4">

            <div class="col-md-3">
                <div class="dash-card">
                    <div class="icon-box icon-blue"><i class="bi bi-people-fill"></i></div>
                    <div class="dash-title">Total Pendaftar</div>
                    <div class="dash-value">{{ $totalPendaftar ?? 0 }}</div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="dash-card">
                    <div class="icon-box icon-yellow"><i class="bi bi-hourglass-split"></i></div>
                    <div class="dash-title">Menunggu Verifikasi</div>
                    <div class="dash-value">{{ $menunggu ?? 0 }}</div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="dash-card">
                    <div class="icon-box icon-green"><i class="bi bi-check-circle-fill"></i></div>
                    <div class="dash-title">Pembayaran Diterima</div>
                    <div class="dash-value">{{ $terverifikasi ?? 0 }}</div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="dash-card">
                    <div class="icon-box icon-purple"><i class="bi bi-journal-text"></i></div>
                    <div class="dash-title">Total Soal</div>
                    <div class="dash-value">{{ $totalSoal ?? 0 }}</div>
                </div>
            </div>
        </div>
    @endif




    {{-- =====================================
    DASHBOARD SANTRI
    ===================================== --}}
    @if ($role === 'santri')
        @if ($pengumumanSudah)
            <div class="alert alert-info border-0 shadow-sm">
                <i class="bi bi-megaphone-fill me-2"></i>
                <strong>Pengumuman Seleksi</strong><br>
                Pengumuman hasil seleksi Tahun Akademik
                <strong>{{ $tahunAktif->tahun }}</strong>
                telah diumumkan.
                Pendaftaran santri baru sudah ditutup.
            </div>
        @endif

        @if ($role === 'santri' && count($notifications))
            <div class="card shadow-sm border-0 p-3 mb-4" style="border-radius: 18px;">
                <h6 class="fw-bold mb-2">
                    <i class="bi bi-bell-fill text-primary me-1"></i> Notifikasi
                </h6>

                @foreach ($notifications as $notif)
                    <div class="alert {{ $notif->read_at ? 'alert-secondary' : 'alert-info' }} mb-3 border-0 shadow-sm"
                        style="border-radius: 14px;">
                        <div class="d-flex align-items-start">
                            <div class="me-3 fs-4">
                                <i class="bi bi-calendar-check-fill mx-2"></i>
                            </div>

                            <div class="flex-grow-1">
                                <div class="fw-bold mb-1">
                                    {{ $notif->data['title'] }}

                                    @if (is_null($notif->read_at))
                                        <span class="badge bg-danger ms-2">Baru</span>
                                    @endif
                                </div>

                                <div class="text-white">
                                    {{ $notif->data['message'] }}
                                </div>

                                @if (isset($notif->data['waktu_mulai']))
                                    <div class="mt-2 p-2 rounded small">
                                        <i class="bi bi-clock-fill me-1"></i>
                                        <strong>Jadwal Tes:</strong><br>
                                        {{ \Carbon\Carbon::parse($notif->data['waktu_mulai'])->format('d M Y') }}<br>
                                        {{ \Carbon\Carbon::parse($notif->data['waktu_mulai'])->format('H:i') }}
                                        –
                                        {{ \Carbon\Carbon::parse($notif->data['waktu_selesai'])->format('H:i') }} WIB
                                    </div>
                                @endif

                                <div class="small text-end text-dark mt-2">
                                    <i class="bi bi-info-circle"></i>
                                    Dikirim {{ $notif->created_at->diffForHumans() }}
                                </div>
                            </div>

                        </div>
                    </div>
                @endforeach

            </div>
        @endif


        <div class="page-title">Halo, {{ Auth::user()->name }}</div>
        <div class="sub-title">Berikut progress pendaftaran Anda.</div>

        <div class="card shadow-sm border-0 p-4" style="border-radius: 18px;">

            <div class="step-item">
                <span class="step-label">Isi Data Diri</span>
                <span class="badge-modern {{ $dataDiri ? 'bg-success2' : 'bg-gray' }}">
                    {{ $dataDiri ? 'Selesai' : 'Belum' }}
                </span>
            </div>

            <div class="step-item">
                <span class="step-label">Upload Pembayaran</span>
                <span
                    class="badge-modern
                                {{ $pembayaran ? ($pembayaran->status == 'diterima' ? 'bg-success2' : 'bg-warning2') : 'bg-gray' }}">
                    {{ $pembayaran ? ucfirst($pembayaran->status) : 'Belum' }}
                </span>
            </div>

            <div class="step-item">
                <span class="step-label">Jadwal Tes</span>

                @if ($statusJadwalTes === 'belum')
                    <span class="badge-modern bg-gray">Belum Ada</span>
                @elseif ($statusJadwalTes === 'aktif')
                    <span class="badge-modern bg-primary2">Belum Mengikuti Tes</span>
                @elseif ($statusJadwalTes === 'selesai')
                    <span class="badge-modern bg-success2">Sudah Mengikuti Tes</span>
                @elseif ($statusJadwalTes === 'kadaluarsa')
                    <span class="badge-modern bg-warning2">Tidak Hadir</span>
                @endif
            </div>
        </div>
    @endif

    {{-- =====================================
    DASHBOARD PENGUJI
    ===================================== --}}
    @if ($role === 'penguji')

        <div class="page-title">Halo, {{ Auth::user()->name }}</div>
        <div class="sub-title">Ringkasan tugas seleksi yang harus Anda tangani.</div>

        <div class="row g-4 mb-4">

            <div class="col-md-4">
                <div class="dash-card">
                    <div class="icon-box icon-blue">
                        <i class="bi bi-calendar-check-fill"></i>
                    </div>
                    <div class="dash-title">Total Jadwal Tes</div>
                    <div class="dash-value">{{ $totalJadwalPenguji ?? 0 }}</div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="dash-card">
                    <div class="icon-box icon-yellow">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                    <div class="dash-title">Belum Dinilai</div>
                    <div class="dash-value">{{ $belumDinilai ?? 0 }}</div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="dash-card">
                    <div class="icon-box icon-green">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                    <div class="dash-title">Sudah Dinilai</div>
                    <div class="dash-value">{{ $sudahDinilai ?? 0 }}</div>
                </div>
            </div>

        </div>

        {{-- Jadwal Terdekat --}}
        <div class="card shadow-sm border-0 p-4" style="border-radius: 18px;">
            <h6 class="fw-bold mb-3">
                <i class="bi bi-clock-history text-primary me-1"></i>
                Jadwal Tes Terdekat
            </h6>

            @forelse ($jadwalTerdekat as $jadwal)
                <div class="step-item">
                    <div>
                        <div class="fw-bold">{{ $jadwal->user->name }}</div>
                        <div class="small text-muted">
                            {{ \Carbon\Carbon::parse($jadwal->waktu_mulai)->format('d M Y, H:i') }} –
                            {{ \Carbon\Carbon::parse($jadwal->waktu_selesai)->format('H:i') }} WIB
                        </div>
                    </div>

                    <span class="badge-modern
                                        {{ $jadwal->hasilSeleksi ? 'bg-success2' : 'bg-warning2' }}">
                        {{ $jadwal->hasilSeleksi ? 'Sudah Dinilai' : 'Belum Dinilai' }}
                    </span>
                </div>
            @empty
                <div class="alert alert-info border-0">
                    Belum ada jadwal tes untuk Anda.
                </div>
            @endforelse
        </div>

    @endif


@endsection