@extends('layouts.dashboard')

@section('title', 'Jadwal Tes Santri')
@section('judul', 'Pengaturan Jadwal Tes')

@section('content')

    <div class="card shadow-sm p-4">

        <h5 class="fw-bold mb-3">Daftar Santri dengan Pembayaran Disetujui</h5>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="table-responsive mt-3">
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Santri</th>
                        <th>No. Pendaftaran</th>
                        <th>Pendidikan Tujuan</th>
                        <th>Jadwal Tes</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($santri as $key => $p)
                        @php $user = $p->user; @endphp

                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->dataDiri->pendidikan_tujuan ?? '-' }}</td>

                            <td>
                                @if (isset($jadwal[$user->id]))
                                    {{ date('d M Y H:i', strtotime($jadwal[$user->id]->waktu_mulai)) }}
                                    -
                                    {{ date('H:i', strtotime($jadwal[$user->id]->waktu_selesai)) }}
                                @else
                                    <span class="text-muted">Belum dijadwalkan</span>
                                @endif
                            </td>

                            <td>
                                @if (isset($jadwal[$user->id]))
                                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#modalEdit{{ $user->id }}">
                                        Edit
                                    </button>
                                @else
                                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#modalBuat{{ $user->id }}">
                                        Buat Jadwal
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
    </div>


    {{-- ========================================================= --}}
    {{--  KUMPULAN MODAL DITARUH DI BAWAH (TIDAK DI DALAM TABLE)   --}}
    {{-- ========================================================= --}}

    @foreach ($santri as $p)
        @php $user = $p->user; @endphp

        {{-- ========== MODAL BUAT JADWAL ========== --}}
        <div class="modal fade" id="modalBuat{{ $user->id }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <form action="{{ route('admin.jadwal.store') }}" method="POST" class="modal-content">
                    @csrf
                    <input type="hidden" name="user_id" value="{{ $user->id }}">

                    <div class="modal-header">
                        <h5 class="modal-title">Buat Jadwal Tes – {{ $user->name }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <label class="form-label">Waktu Mulai</label>
                        <input type="datetime-local" name="waktu_mulai" class="form-control mb-3" required>

                        <label class="form-label">Waktu Selesai</label>
                        <input type="datetime-local" name="waktu_selesai" class="form-control" required>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button class="btn btn-primary">Simpan</button>
                    </div>

                </form>
            </div>
        </div>


        {{-- ========== MODAL EDIT JADWAL ========== --}}
        @if (isset($jadwal[$user->id]))
            <div class="modal fade" id="modalEdit{{ $user->id }}" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <form action="{{ route('admin.jadwal.update', $jadwal[$user->id]->id) }}" method="POST"
                        class="modal-content">
                        @csrf

                        <div class="modal-header">
                            <h5 class="modal-title">Edit Jadwal Tes – {{ $user->name }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            <label class="form-label">Waktu Mulai</label>
                            <input type="datetime-local" name="waktu_mulai" class="form-control mb-3"
                                value="{{ date('Y-m-d\TH:i', strtotime($jadwal[$user->id]->waktu_mulai)) }}" required>

                            <label class="form-label">Waktu Selesai</label>
                            <input type="datetime-local" name="waktu_selesai" class="form-control"
                                value="{{ date('Y-m-d\TH:i', strtotime($jadwal[$user->id]->waktu_selesai)) }}" required>
                        </div>

                        <div class="modal-footer">
                            <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button class="btn btn-warning">Update</button>
                        </div>

                    </form>
                </div>
            </div>
        @endif
    @endforeach

@endsection
