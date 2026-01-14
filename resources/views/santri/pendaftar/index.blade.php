@extends('layouts.dashboard')

@section('title', 'Data Diri Pendaftar')
@section('judul', 'Data Diri Pendaftar')

@section('content')

    <div class="card shadow border-0 p-4">
        <h5 class="fw-bold mb-3">Data Diri Anda</h5>

        @if (!$data)
            <div class="alert alert-warning">
                Anda belum mengisi data diri. Silakan isi data diri terlebih dahulu.
            </div>

            <a href="{{ route('santri.pendaftar.edit') }}" class="btn btn-primary">
                <i class="fas fa-user-edit"></i> Isi Data Diri
            </a>
        @else
            {{-- ========================= TABEL DATA DIRI ========================= --}}
            <table class="table table-bordered table-striped">
                <tbody>

                    <tr>
                        <th>ID Pendaftaran</th>
                        <td>{{ $data->user->registration_id }}</td>
                    </tr>
                    <tr>
                        <th width="30%">Nama Lengkap</th>
                        <td>{{ $data->nama_lengkap }}</td>
                    </tr>

                    <tr>
                        <th>Email</th>
                        <td>{{ $user->email }}</td>
                    </tr>

                    <tr>
                        <th>No. Telepon</th>
                        <td>{{ $user->no_telp }}</td>
                    </tr>

                    <tr>
                        <th>NIK</th>
                        <td>{{ $user->nik }}</td>
                    </tr>

                    <tr>
                        <th>Alamat Domisili</th>
                        <td>{{ $data->alamat_domisili }}</td>
                    </tr>

                    <tr>
                        <th>Kabupaten Lahir</th>
                        <td>{{ $data->kabupaten_lahir }}</td>
                    </tr>

                    <tr>
                        <th>Tanggal Lahir</th>
                        <td>{{ $data->tanggal_lahir }}</td>
                    </tr>

                    <tr>
                        <th>Jenis Kelamin</th>
                        <td>{{ $data->jenis_kelamin == 'L' ? 'Laki-Laki' : 'Perempuan' }}</td>
                    </tr>

                    <tr class="table-light">
                        <th colspan="2" class="fw-bold text-center">Riwayat Pendidikan</th>
                    </tr>

                    <tr>
                        <th>Instansi 1</th>
                        <td>{{ $data->instansi_1 }}</td>
                    </tr>

                    <tr>
                        <th>Instansi 2</th>
                        <td>{{ $data->instansi_2 }}</td>
                    </tr>

                    <tr>
                        <th>Instansi 3</th>
                        <td>{{ $data->instansi_3 }}</td>
                    </tr>

                    <tr class="table-light">
                        <th colspan="2" class="fw-bold text-center">Prestasi</th>
                    </tr>

                    <tr>
                        <th>Prestasi 1</th>
                        <td>{{ $data->prestasi_1 }}</td>
                    </tr>

                    <tr>
                        <th>Prestasi 2</th>
                        <td>{{ $data->prestasi_2 }}</td>
                    </tr>

                    <tr>
                        <th>Prestasi 3</th>
                        <td>{{ $data->prestasi_3 }}</td>
                    </tr>

                    <tr class="table-light">
                        <th colspan="2" class="fw-bold text-center">Data Wali</th>
                    </tr>

                    <tr>
                        <th>Nama Wali</th>
                        <td>{{ $data->nama_wali }}</td>
                    </tr>

                    <tr>
                        <th>No. Telepon Wali</th>
                        <td>{{ $data->no_telp_wali }}</td>
                    </tr>

                    <tr class="table-light">
                        <th colspan="2" class="fw-bold text-center">Pendidikan Tujuan</th>
                    </tr>

                    <tr>
                        <th>Pendidikan Tujuan</th>
                        <td>{{ $data->pendidikan_tujuan }}</td>
                    </tr>

                    <tr class="table-light">
                        <th colspan="2" class="fw-bold text-center">Foto Dokumen</th>
                    </tr>

                    <tr>
                        <th>Foto Diri</th>
                        <td>
                            @if ($data->foto_diri)
                                <img src="{{ asset('storage/' . $data->foto_diri) }}" width="120" class="img-thumbnail">
                            @else
                                <span class="text-muted">Belum diupload</span>
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <th>Foto Kartu Keluarga</th>
                        <td>
                            @if ($data->foto_kk)
                                <img src="{{ asset('storage/' . $data->foto_kk) }}" width="120" class="img-thumbnail">
                            @else
                                <span class="text-muted">Belum diupload</span>
                            @endif
                        </td>
                    </tr>

                </tbody>
            </table>

            <a href="{{ route('santri.pendaftar.edit') }}" class="btn btn-warning mt-3">
                <i class="fas fa-edit"></i> Edit Data Diri
            </a>

        @endif
    </div>

@endsection
