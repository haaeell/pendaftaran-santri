@extends('layouts.dashboard')

@section('title', 'Tahun Akademik')
@section('judul', 'Manajemen Tahun Akademik')

@section('content')

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="fw-bold mb-0">Daftar Tahun Akademik</h5>

            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
                <i class="fas fa-plus"></i> Tambah
            </button>
        </div>

        <div class="card-body">

            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Tahun</th>
                        <th>Status</th>
                        <th width="25%">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($data as $i => $t)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $t->tahun }}</td>

                            <td>
                                @if ($t->aktif)
                                    <span class="badge bg-success text-white">Aktif</span>
                                @else
                                    <span class="badge bg-secondary">Tidak Aktif</span>
                                @endif
                            </td>

                            <td>
                                {{-- Aktifkan --}}
                                @if (!$t->aktif)
                                    <form action="{{ route('admin.tahun.aktifkan', $t->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        <button class="btn btn-sm btn-success">
                                            <i class="fas fa-check"></i> Aktifkan
                                        </button>
                                    </form>
                                @endif

                                {{-- Edit --}}
                                <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                    data-bs-target="#modalEdit{{ $t->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>

                                {{-- Hapus --}}
                                <form action="{{ route('admin.tahun.delete', $t->id) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger" onclick="return confirm('Hapus tahun akademik?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>

                        {{-- MODAL EDIT --}}
                        <div class="modal fade" id="modalEdit{{ $t->id }}">
                            <div class="modal-dialog">
                                <form action="{{ route('admin.tahun.update', $t->id) }}" method="POST"
                                    class="modal-content">
                                    @csrf

                                    <div class="modal-header">
                                        <h5>Edit Tahun Akademik</h5>
                                        <button class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>

                                    <div class="modal-body">
                                        <label>Tahun Akademik</label>
                                        <input type="text" name="tahun" class="form-control"
                                            value="{{ $t->tahun }}" required>
                                    </div>

                                    <div class="modal-footer">
                                        <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <button class="btn btn-warning">Update</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>


    {{-- MODAL TAMBAH --}}
    <div class="modal fade" id="modalTambah">
        <div class="modal-dialog">
            <form action="{{ route('admin.tahun.store') }}" method="POST" class="modal-content">
                @csrf

                <div class="modal-header">
                    <h5>Tambah Tahun Akademik</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <label>Tahun Akademik</label>
                    <input type="text" name="tahun" class="form-control" placeholder="Contoh: 2025/2026" required>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-primary">Simpan</button>
                </div>

            </form>
        </div>
    </div>

@endsection
