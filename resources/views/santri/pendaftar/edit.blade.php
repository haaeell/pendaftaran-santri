@extends('layouts.dashboard')

@section('title', 'Edit Data Diri')
@section('judul', 'Edit Data Diri Pendaftar')

@section('content')

    <div class="card shadow border-0 p-4">
        <h5 class="fw-bold mb-4">Form Data Diri Santri</h5>

        {{-- ERROR VALIDASI GLOBAL --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Periksa kembali input Anda:</strong>
                <ul class="mb-0">
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('santri.pendaftar.update') }}" enctype="multipart/form-data">
            @csrf

            <div class="row">

                {{-- ======================= DATA DIRI ======================= --}}

                {{-- Nama Lengkap --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Nama Lengkap</label>
                    <input type="text" name="nama_lengkap"
                        class="form-control @error('nama_lengkap') is-invalid @enderror"
                        value="{{ old('nama_lengkap', $data->nama_lengkap ?? $user->name) }}">
                    @error('nama_lengkap')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Kabupaten Lahir --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Kabupaten Lahir</label>
                    <input type="text" name="kabupaten_lahir"
                        class="form-control @error('kabupaten_lahir') is-invalid @enderror"
                        value="{{ old('kabupaten_lahir', $data->kabupaten_lahir ?? '') }}">
                    @error('kabupaten_lahir')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Tanggal Lahir --}}
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir"
                        class="form-control @error('tanggal_lahir') is-invalid @enderror"
                        value="{{ old('tanggal_lahir', $data->tanggal_lahir ?? '') }}">
                    @error('tanggal_lahir')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Jenis Kelamin --}}
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="form-control @error('jenis_kelamin') is-invalid @enderror">
                        <option value="">Pilih</option>
                        <option value="L"
                            {{ old('jenis_kelamin', $data->jenis_kelamin ?? '') == 'L' ? 'selected' : '' }}>Laki-Laki
                        </option>
                        <option value="P"
                            {{ old('jenis_kelamin', $data->jenis_kelamin ?? '') == 'P' ? 'selected' : '' }}>Perempuan
                        </option>
                    </select>
                    @error('jenis_kelamin')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- NISN --}}
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">NISN</label>
                    <input type="text" name="nisn" class="form-control @error('nisn') is-invalid @enderror"
                        value="{{ old('nisn', $data->nisn ?? '') }}">
                    @error('nisn')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Alamat --}}
                <div class="col-md-12 mb-3">
                    <label class="form-label fw-bold">Alamat Domisili</label>
                    <textarea name="alamat_domisili" class="form-control @error('alamat_domisili') is-invalid @enderror" rows="2">{{ old('alamat_domisili', $data->alamat_domisili ?? '') }}</textarea>
                    @error('alamat_domisili')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>



                {{-- ======================= USER (AUTO FILL) ======================= --}}

                {{-- Email --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Email</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                        value="{{ old('email', $data->email ?? $user->email) }}">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Nomor Telepon --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Nomor Telepon</label>
                    <input type="text" name="no_telp" class="form-control @error('no_telp') is-invalid @enderror"
                        value="{{ old('no_telp', $data->no_telp ?? $user->no_telp) }}">
                    @error('no_telp')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- NIK --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">NIK</label>
                    <input type="text" name="nik" class="form-control @error('nik') is-invalid @enderror"
                        value="{{ old('nik', $data->nik ?? $user->nik) }}">
                    @error('nik')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>



                {{-- ======================= FOTO ======================= --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Foto Diri</label>
                    <input type="file" name="foto_diri" class="form-control @error('foto_diri') is-invalid @enderror">
                    @error('foto_diri')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                    @if (!empty($data->foto_diri))
                        <img src="{{ asset('storage/' . $data->foto_diri) }}" class="img-thumbnail mt-2" width="120">
                    @endif
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Foto Kartu Keluarga</label>
                    <input type="file" name="foto_kk" class="form-control @error('foto_kk') is-invalid @enderror">
                    @error('foto_kk')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                    @if (!empty($data->foto_kk))
                        <img src="{{ asset('storage/' . $data->foto_kk) }}" class="img-thumbnail mt-2" width="120">
                    @endif
                </div>



                {{-- ======================= RIWAYAT AGAMA ======================= --}}
                <div class="col-12 mt-4">
                    <h6 class="fw-bold">Riwayat Pendidikan Agama</h6>
                </div>

                @foreach (['instansi_1', 'instansi_2', 'instansi_3'] as $instansi)
                    <div class="col-md-4 mb-3">
                        <label class="form-label">{{ ucfirst(str_replace('_', ' ', $instansi)) }}</label>
                        <input type="text" name="{{ $instansi }}"
                            class="form-control @error($instansi) is-invalid @enderror"
                            value="{{ old($instansi, $data->$instansi ?? '') }}">
                        @error($instansi)
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                @endforeach



                {{-- ======================= PRESTASI ======================= --}}
                <div class="col-12 mt-4">
                    <h6 class="fw-bold">Prestasi</h6>
                </div>

                @foreach (['prestasi_1', 'prestasi_2', 'prestasi_3'] as $prestasi)
                    <div class="col-md-4 mb-3">
                        <label class="form-label">{{ ucfirst(str_replace('_', ' ', $prestasi)) }}</label>
                        <input type="text" name="{{ $prestasi }}"
                            class="form-control @error($prestasi) is-invalid @enderror"
                            value="{{ old($prestasi, $data->$prestasi ?? '') }}">
                        @error($prestasi)
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                @endforeach



                {{-- ======================= DATA WALI ======================= --}}
                <div class="col-12 mt-4">
                    <h6 class="fw-bold">Data Wali</h6>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Hubungan Wali</label>
                    <input type="text" name="hubungan_wali"
                        class="form-control @error('hubungan_wali') is-invalid @enderror"
                        value="{{ old('hubungan_wali', $data->hubungan_wali ?? '') }}">
                    @error('hubungan_wali')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Nama Wali</label>
                    <input type="text" name="nama_wali" class="form-control @error('nama_wali') is-invalid @enderror"
                        value="{{ old('nama_wali', $data->nama_wali ?? '') }}">
                    @error('nama_wali')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">No. Telepon Wali</label>
                    <input type="text" name="no_telp_wali"
                        class="form-control @error('no_telp_wali') is-invalid @enderror"
                        value="{{ old('no_telp_wali', $data->no_telp_wali ?? '') }}">
                    @error('no_telp_wali')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>



                {{-- ======================= INFORMASI PSB ======================= --}}
                <div class="col-12 mt-4">
                    <h6 class="fw-bold">Informasi Mengenai PSB</h6>
                </div>

                @php
                    $info = [
                        'info_alumni' => 'Alumni',
                        'info_saudara' => 'Saudara',
                        'info_instagram' => 'Instagram',
                        'info_tiktok' => 'TikTok',
                        'info_youtube' => 'YouTube',
                        'info_facebook' => 'Facebook',
                        'info_lainnya' => 'Lainnya',
                    ];
                @endphp

                @foreach ($info as $field => $label)
                    <div class="col-md-4 mb-2 mx-4">
                        <label class="form-check-label">
                            <input type="checkbox" name="{{ $field }}" class="form-check-input"
                                {{ old($field, $data->$field ?? false) ? 'checked' : '' }}>
                            {{ $label }}
                        </label>
                    </div>
                @endforeach



                {{-- ======================= PENDIDIKAN TUJUAN ======================= --}}
                <div class="col-12 mt-4">
                    <h6 class="fw-bold">Pendidikan Tujuan</h6>
                </div>

                <div class="col-md-6 mb-3">
                    <select name="pendidikan_tujuan"
                        class="form-control @error('pendidikan_tujuan') is-invalid @enderror">

                        <option value="">Pilih</option>

                        @foreach (['SMP dalam Pesantren', 'SMA dalam Pesantren', 'Universitas luar Pesantren', 'Tidak Sekolah'] as $opt)
                            <option value="{{ $opt }}"
                                {{ old('pendidikan_tujuan', $data->pendidikan_tujuan ?? '') == $opt ? 'selected' : '' }}>
                                {{ $opt }}
                            </option>
                        @endforeach
                    </select>

                    @error('pendidikan_tujuan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

            </div>

            <button class="btn btn-success mt-3">
                <i class="fas fa-save"></i> Simpan Data
            </button>

        </form>
    </div>

@endsection
