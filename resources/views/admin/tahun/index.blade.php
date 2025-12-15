@extends('layouts.dashboard')

@section('title', 'Tahun Akademik')
@section('judul', 'Manajemen Tahun Akademik')

@section('content')

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="fw-bold mb-0">Daftar Tahun Akademik</h5>

            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
                <i class="fas fa-plus"></i> Tambah
            </button>
        </div>

        <div class="card-body">
            <table class="table table-bordered align-middle">
                <thead class="table-light text-center">
                    <tr>
                        <th width="5%">#</th>
                        <th>Tahun</th>
                        <th>Status</th>
                        <th width="20%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $i => $t)
                        <tr>
                            <td class="text-center">{{ $i + 1 }}</td>
                            <td>{{ $t->tahun }}</td>
                            <td class="text-center">
                                @if ($t->aktif)
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-secondary">Tidak Aktif</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if (!$t->aktif)
                                    <form action="{{ route('admin.tahun.aktifkan', $t->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        <button class="btn btn-sm btn-success">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                @endif

                                <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                    data-bs-target="#editModal{{ $t->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>

                                <button class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                    data-bs-target="#deleteModal{{ $t->id }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- ================= MODAL TAMBAH ================= --}}
    <div class="modal fade" id="modalTambah" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <form action="{{ route('admin.tahun.store') }}" method="POST" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Tahun Akademik</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <label class="form-label">Tahun Akademik</label>
                    <input type="text" name="tahun" class="form-control" placeholder="Contoh: 2025/2026" required>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ================= MODAL EDIT & DELETE ================= --}}
    @foreach ($data as $t)
        {{-- EDIT --}}
        <div class="modal fade" id="editModal{{ $t->id }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <form action="{{ route('admin.tahun.update', $t->id) }}" method="POST" class="modal-content">
                    @csrf
                    @method('PUT')

                    <div class="modal-header">
                        <h5 class="modal-title">Edit Tahun Akademik</h5>
                        <button class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <label class="form-label">Tahun Akademik</label>
                        <input type="text" name="tahun" class="form-control" value="{{ $t->tahun }}" required>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button class="btn btn-warning">Update</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- DELETE --}}
        <div class="modal fade" id="deleteModal{{ $t->id }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <form action="{{ route('admin.tahun.delete', $t->id) }}" method="POST" class="modal-content">
                    @csrf
                    @method('DELETE')

                    <div class="modal-header">
                        <h5 class="modal-title text-danger">Hapus Tahun Akademik</h5>
                        <button class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <p>Yakin ingin menghapus tahun akademik:</p>
                        <strong>{{ $t->tahun }}</strong>
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
