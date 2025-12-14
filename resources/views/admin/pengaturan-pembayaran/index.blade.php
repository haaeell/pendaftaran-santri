@extends('layouts.dashboard')

@section('title', 'Pengaturan Administrasi')
@section('judul', 'Pengaturan Administrasi')

@section('content')

    {{-- =========================================================
    ALERT
========================================================= --}}
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif


    {{-- =========================================================
    PENGATURAN PEMBAYARAN
========================================================= --}}
    <div class="card mb-4 shadow-sm border-0">
        <div class="card-header">
            <strong>Pengaturan Pembayaran</strong>
            <span class="text-muted">(Tahun Akademik {{ $tahunAktif->tahun }})</span>
        </div>

        <div class="card-body">
            @foreach (['registrasi', 'daftar_ulang'] as $jenis)
                <form class="row g-2 mb-3" method="POST"
                    action="{{ route('admin.pengaturan-pembayaran.update', $pengaturan[$jenis]->id) }}">
                    @csrf @method('PUT')

                    <div class="col-md-4">
                        <input type="text" class="form-control" value="{{ ucfirst(str_replace('_', ' ', $jenis)) }}"
                            readonly>
                    </div>

                    <div class="col-md-5">
                        <input type="number" name="nominal" class="form-control" value="{{ $pengaturan[$jenis]->nominal }}"
                            required>
                    </div>

                    <div class="col-md-3">
                        <button class="btn btn-primary w-100">Simpan</button>
                    </div>
                </form>
            @endforeach
        </div>
    </div>




    {{-- =========================================================
    REKENING PEMBAYARAN
========================================================= --}}
    <div class="card mb-4 shadow-sm border-0">
        <div class="card-header d-flex justify-content-between align-items-center">
            <strong>Rekening Pembayaran</strong>

            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahRekening">
                <i class="fas fa-plus"></i> Tambah
            </button>
        </div>

        <div class="card-body">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Bank</th>
                        <th>No Rekening</th>
                        <th>Atas Nama</th>
                        <th width="15%">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($rekening as $r)
                        <tr>
                            <td>{{ $r->bank }}</td>
                            <td>{{ $r->nomor_rekening }}</td>
                            <td>{{ $r->atas_nama }}</td>
                            <td>
                                <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                    data-bs-target="#modalEditRekening{{ $r->id }}">
                                    Edit
                                </button>

                                <button class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                    data-bs-target="#modalDeleteRekening{{ $r->id }}">
                                    Hapus
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
    </div>




    {{-- =========================================================
    TIMELINE SELEKSI
========================================================= --}}
    {{-- <div class="card shadow-sm border-0">
        <div class="card-header d-flex justify-content-between align-items-center">
            <strong>Timeline Seleksi</strong>

            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahTimeline">
                <i class="fas fa-plus"></i> Tambah Gelombang
            </button>
        </div>

        <div class="card-body">

            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Nama Gelombang</th>
                        <th>Mulai</th>
                        <th>Selesai</th>
                        <th>Status</th>
                        <th width="15%">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($timeline as $i => $t)
                        @php
                            $today = now()->format('Y-m-d');

                            $status =
                                $today < $t->mulai
                                    ? 'Akan Datang'
                                    : ($today > $t->selesai
                                        ? 'Berakhir'
                                        : 'Berlangsung');

                            $badge = [
                                'Akan Datang' => 'info',
                                'Berlangsung' => 'success',
                                'Berakhir' => 'secondary',
                            ][$status];
                        @endphp

                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $t->nama_gelombang }}</td>
                            <td>{{ $t->mulai->format('d M Y') }}</td>
                            <td>{{ $t->selesai->format('d M Y') }}</td>
                            <td>
                                <span class="badge bg-{{ $badge }}">{{ $status }}</span>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                    data-bs-target="#modalEditTimeline{{ $t->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>

                                <button class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                    data-bs-target="#modalDeleteTimeline{{ $t->id }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                Belum ada timeline seleksi.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

        </div>
    </div> --}}




    {{-- =========================================================
    MODAL: Tambah Rekening
========================================================= --}}
    <div class="modal fade" id="modalTambahRekening">
        <div class="modal-dialog modal-dialog-centered">
            <form class="modal-content" method="POST" action="{{ route('admin.pengaturan-pembayaran.rekening.store') }}">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">Tambah Rekening</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <label>Bank</label>
                    <input type="text" name="bank" class="form-control mb-2" required>

                    <label>Nomor Rekening</label>
                    <input type="text" name="nomor_rekening" class="form-control mb-2" required>

                    <label>Atas Nama</label>
                    <input type="text" name="atas_nama" class="form-control" required>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-primary">Simpan</button>
                </div>

            </form>
        </div>
    </div>


    {{-- =========================================================
    MODAL: Edit & Delete Rekening
========================================================= --}}
    @foreach ($rekening as $r)
        {{-- Edit --}}
        <div class="modal fade" id="modalEditRekening{{ $r->id }}">
            <div class="modal-dialog modal-dialog-centered">
                <form class="modal-content" method="POST"
                    action="{{ route('admin.pengaturan-pembayaran.rekening.update', $r->id) }}">
                    @csrf @method('PUT')

                    <div class="modal-header">
                        <h5 class="modal-title">Edit Rekening</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <label>Bank</label>
                        <input type="text" name="bank" class="form-control mb-2" value="{{ $r->bank }}"
                            required>

                        <label>Nomor Rekening</label>
                        <input type="text" name="nomor_rekening" class="form-control mb-2"
                            value="{{ $r->nomor_rekening }}" required>

                        <label>Atas Nama</label>
                        <input type="text" name="atas_nama" class="form-control" value="{{ $r->atas_nama }}" required>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button class="btn btn-warning">Update</button>
                    </div>

                </form>
            </div>
        </div>

        {{-- Delete --}}
        <div class="modal fade" id="modalDeleteRekening{{ $r->id }}">
            <div class="modal-dialog modal-dialog-centered">
                <form class="modal-content" method="POST"
                    action="{{ route('admin.pengaturan-pembayaran.rekening.delete', $r->id) }}">
                    @csrf @method('DELETE')

                    <div class="modal-header">
                        <h5 class="modal-title text-danger">Hapus Rekening</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        Yakin ingin menghapus rekening
                        <strong>{{ $r->bank }} — {{ $r->nomor_rekening }}</strong>?
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button class="btn btn-danger">Hapus</button>
                    </div>

                </form>
            </div>
        </div>
    @endforeach




    {{-- =========================================================
    MODAL TIMELINE: Tambah / Edit / Delete
========================================================= --}}
    {{-- Tambah --}}
    <div class="modal fade" id="modalTambahTimeline">
        <div class="modal-dialog modal-dialog-centered">
            <form class="modal-content" method="POST"
                action="{{ route('admin.pengaturan-pembayaran.timeline.store') }}">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">Tambah Gelombang Seleksi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <label>Nama Gelombang</label>
                    <input type="text" name="nama_gelombang" class="form-control mb-2" required>

                    <label>Tanggal Mulai</label>
                    <input type="date" name="mulai" class="form-control mb-2" required>

                    <label>Tanggal Selesai</label>
                    <input type="date" name="selesai" class="form-control" required>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>


    {{-- Edit + Delete --}}
    @foreach ($timeline as $t)
        {{-- Edit --}}
        <div class="modal fade" id="modalEditTimeline{{ $t->id }}">
            <div class="modal-dialog modal-dialog-centered">
                <form class="modal-content" method="POST"
                    action="{{ route('admin.pengaturan-pembayaran.timeline.update', $t->id) }}">
                    @csrf @method('PUT')

                    <div class="modal-header">
                        <h5 class="modal-title">Edit Gelombang</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">

                        <label>Nama Gelombang</label>
                        <input type="text" name="nama_gelombang" class="form-control mb-2"
                            value="{{ $t->nama_gelombang }}" required>

                        <label>Tanggal Mulai</label>
                        <input type="date" name="mulai" class="form-control mb-2"
                            value="{{ $t->mulai->toDateString() }}" required>

                        <label>Tanggal Selesai</label>
                        <input type="date" name="selesai" class="form-control"
                            value="{{ $t->selesai->toDateString() }}" required>

                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button class="btn btn-warning">Update</button>
                    </div>

                </form>
            </div>
        </div>


        {{-- Delete --}}
        <div class="modal fade" id="modalDeleteTimeline{{ $t->id }}">
            <div class="modal-dialog modal-dialog-centered">
                <form class="modal-content" method="POST"
                    action="{{ route('admin.pengaturan-pembayaran.timeline.delete', $t->id) }}">
                    @csrf @method('DELETE')

                    <div class="modal-header">
                        <h5 class="modal-title text-danger">Hapus Gelombang</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        Yakin ingin menghapus gelombang
                        <strong>{{ $t->nama_gelombang }}</strong>?
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button class="btn btn-danger">Hapus</button>
                    </div>

                </form>
            </div>
        </div>
    @endforeach


@endsection
