@extends('layouts.dashboard')

@section('title', 'Manajemen Penguji')
@section('judul', 'Manajemen Penguji')

@section('content')
    <div class="card shadow">
        <div class="card-header d-flex justify-content-between">
            <h5>Daftar Penguji</h5>
            <button class="btn btn-primary" data-toggle="modal" data-target="#modalTambahPenguji">
                <i class="fas fa-plus"></i> Tambah Penguji
            </button>
        </div>

        <div class="card-body">
            <table class="table table-bordered" id="datatable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($penguji as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->email }}</td>
                            <td>
                                <button class="btn btn-sm btn-warning" data-toggle="modal"
                                    data-target="#modalEditPenguji{{ $item->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>

                                <button class="btn btn-sm btn-danger" onclick="confirmDelete({{ $item->id }})">
                                    <i class="fas fa-trash"></i>
                                </button>

                                <form id="delete-form-{{ $item->id }}" action="{{ route('admin.penguji.destroy', $item->id) }}"
                                    method="POST" class="d-none">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>

                        <!-- MODAL EDIT -->
                        <div class="modal fade" id="modalEditPenguji{{ $item->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <form action="{{ route('admin.penguji.update', $item->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Penguji</h5>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>

                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label>Nama</label>
                                                <input type="text" name="name" class="form-control" value="{{ $item->name }}"
                                                    required>
                                            </div>

                                            <div class="form-group">
                                                <label>Email</label>
                                                <input type="email" name="email" class="form-control" value="{{ $item->email }}"
                                                    required>
                                            </div>

                                            <div class="form-group">
                                                <label>Password (Opsional)</label>
                                                <input type="password" name="password" class="form-control"
                                                    placeholder="Kosongkan jika tidak diganti">
                                            </div>
                                        </div>

                                        <div class="modal-footer">
                                            <button class="btn btn-warning">
                                                <i class="fas fa-save"></i> Update
                                            </button>
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                                Batal
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- MODAL TAMBAH -->
    <div class="modal fade" id="modalTambahPenguji" tabindex="-1">
        <div class="modal-dialog">
            <form action="{{ route('admin.penguji.store') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Penguji</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nama</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-primary">
                            <i class="fas fa-save"></i> Simpan
                        </button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            Batal
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'Yakin?',
                text: 'Data penguji akan dihapus',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`delete-form-${id}`).submit();
                }
            })
        }
    </script>
@endsection