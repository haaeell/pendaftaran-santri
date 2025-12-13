@extends('layouts.dashboard')

@section('title', 'Master Soal')
@section('judul', 'Master Kategori & Soal')

@section('content')

    {{-- =======================================================
    TABLE KATEGORI
======================================================= --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="fw-bold mb-0">Daftar Kategori Soal</h5>

            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambahKategori">
                <i class="fas fa-plus"></i> Tambah Kategori
            </button>
        </div>

        <div class="card-body">

            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="5%">#</th>
                        <th>Nama Kategori</th>
                        <th>Tipe</th>
                        <th>Bobot</th>
                        <th>Minimal Benar</th>
                        <th width="25%">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($kategori as $i => $kat)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $kat->nama_kategori }}</td>
                            <td>{{ ucfirst($kat->tipe_kriteria) }}</td>
                            <td>{{ $kat->bobot ?? '-' }}</td>
                            <td>{{ $kat->minimal_benar ?? '-' }}</td>

                            <td>
                                <button class="btn btn-sm btn-success" data-bs-toggle="modal"
                                    data-bs-target="#modalTambahSoal{{ $kat->id }}">
                                    <i class="fas fa-plus"></i> Soal
                                </button>

                                <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                    data-bs-target="#modalEditKategori{{ $kat->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                    data-bs-target="#modalDeleteKategori{{ $kat->id }}">
                                    <i class="fas fa-trash"></i>
                                </button>

                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>

    @foreach ($kategori as $kat)
        <div class="modal fade" id="modalDeleteKategori{{ $kat->id }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">

                    <form action="{{ route('admin.master-soal.kategori.delete', $kat->id) }}" method="POST">
                        @csrf
                        @method('DELETE')

                        <div class="modal-header">
                            <h5 class="modal-title text-danger">
                                <i class="fas fa-exclamation-triangle me-1"></i>
                                Konfirmasi Hapus Kategori
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            <p class="mb-1">
                                Yakin ingin menghapus kategori:
                                <strong>{{ $kat->nama_kategori }}</strong>?
                            </p>
                            <small class="text-danger">
                                ⚠ Semua soal dalam kategori ini akan ikut terhapus.
                            </small>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                Batal
                            </button>
                            <button type="submit" class="btn btn-danger">
                                Ya, Hapus
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>

        @foreach ($kat->soal as $soal)
            <div class="modal fade" id="modalDeleteSoal{{ $soal->id }}" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">

                        <form action="{{ route('admin.master-soal.soal.delete', $soal->id) }}" method="POST">
                            @csrf
                            @method('DELETE')

                            <div class="modal-header">
                                <h5 class="modal-title text-danger">
                                    <i class="fas fa-exclamation-triangle me-1"></i>
                                    Konfirmasi Hapus Soal
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body">
                                <p>
                                    Yakin ingin menghapus soal berikut?
                                </p>
                                <div class="alert alert-light border">
                                    {{ Str::limit($soal->pertanyaan, 100) }}
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    Batal
                                </button>
                                <button type="submit" class="btn btn-danger">
                                    Ya, Hapus
                                </button>
                            </div>

                        </form>

                    </div>
                </div>
            </div>
        @endforeach
    @endforeach

    {{-- =======================================================
    TABLE SOAL
======================================================= --}}
    <div class="card shadow-sm border-0">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="fw-bold mb-0">Daftar Soal</h5>

            <select id="filterKategori" class="form-select w-auto">
                <option value="">Semua Kategori</option>
                @foreach ($kategori as $kat)
                    <option value="{{ $kat->id }}">{{ $kat->nama_kategori }}</option>
                @endforeach
            </select>
        </div>

        <div class="card-body">

            <table class="table table-striped align-middle" id="tabelSoal">
                <thead class="table-light">
                    <tr>
                        <th width="5%">#</th>
                        <th>Kategori</th>
                        <th>Pertanyaan</th>
                        <th>Pilihan</th>
                        <th>Kunci</th>
                        <th width="15%">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($kategori as $kat)
                        @foreach ($kat->soal as $i => $soal)
                            <tr data-kategori="{{ $kat->id }}">
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $kat->nama_kategori }}</td>
                                <td>{{ $soal->pertanyaan }}</td>

                                <td>
                                    @foreach ($soal->pilihan as $key => $pil)
                                        <div><strong>{{ chr(65 + $key) }}.</strong> {{ $pil }}</div>
                                    @endforeach
                                </td>

                                <td>
                                    <span class="badge bg-success">{{ chr(65 + (int) $soal->jawaban) }}</span>
                                </td>

                                <td>
                                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                        data-bs-target="#modalEditSoal{{ $soal->id }}">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    <button class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                        data-bs-target="#modalDeleteSoal{{ $soal->id }}">
                                        <i class="fas fa-trash"></i>
                                    </button>

                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>


    {{-- =======================================================
    MODAL TAMBAH KATEGORI
======================================================= --}}
    <div class="modal fade" id="modalTambahKategori" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <form action="{{ route('admin.master-soal.kategori.store') }}" method="POST">
                    @csrf

                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Kategori Soal</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <label>Nama Kategori</label>
                        <input type="text" name="nama_kategori" class="form-control mb-2" required>

                        <label>Tipe Penilaian</label>
                        <select name="tipe_kriteria" class="form-control mb-2 tipe-kriteria" required>
                            <option value="">-- Pilih --</option>
                            <option value="threshold">Threshold</option>
                            <option value="benefit">Benefit</option>
                        </select>

                        <div class="field-threshold d-none">
                            <label>Minimal Benar</label>
                            <input type="number" name="minimal_benar" class="form-control mb-2">
                        </div>

                        <div class="field-benefit d-none">
                            <label>Bobot (Max 100)</label>
                            <input type="number" name="bobot" class="form-control mb-2" max="100">
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Batal
                        </button>

                        <button class="btn btn-primary">Simpan</button>
                    </div>

                </form>

            </div>
        </div>
    </div>


    {{-- =======================================================
    MODAL EDIT KATEGORI & MODAL TAMBAH SOAL
======================================================= --}}
    @foreach ($kategori as $kat)
        {{-- EDIT KATEGORI --}}
        <div class="modal fade" id="modalEditKategori{{ $kat->id }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">

                    <form action="{{ route('admin.master-soal.kategori.update', $kat->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="modal-header">
                            <h5 class="modal-title">Edit Kategori Soal</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            <label>Nama Kategori</label>
                            <input type="text" name="nama_kategori" value="{{ $kat->nama_kategori }}"
                                class="form-control mb-2" required>


                            <label>Tipe Penilaian</label>
                            <select name="tipe_kriteria" class="form-control mb-2 tipe-kriteria"
                                data-current="{{ $kat->tipe_kriteria }}">
                                <option value="threshold" {{ $kat->tipe_kriteria == 'threshold' ? 'selected' : '' }}>
                                    Threshold
                                </option>
                                <option value="benefit" {{ $kat->tipe_kriteria == 'benefit' ? 'selected' : '' }}>
                                    Benefit
                                </option>
                            </select>

                            <div class="field-threshold d-none">
                                <label>Minimal Benar</label>
                                <input type="number" name="minimal_benar" value="{{ $kat->minimal_benar }}"
                                    class="form-control mb-2">
                            </div>

                            <div class="field-benefit d-none">
                                <label>Bobot (Max 100)</label>
                                <input type="number" name="bobot" value="{{ $kat->bobot }}"
                                    class="form-control mb-2" max="100">
                            </div>

                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                Batal
                            </button>

                            <button class="btn btn-warning">Update</button>
                        </div>

                    </form>

                </div>
            </div>
        </div>


        {{-- TAMBAH SOAL --}}
        <div class="modal fade" id="modalTambahSoal{{ $kat->id }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">

                    <form action="{{ route('admin.master-soal.soal.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="kategori_id" value="{{ $kat->id }}">

                        <div class="modal-header">
                            <h5 class="modal-title">Tambah Soal — {{ $kat->nama_kategori }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">

                            <label>Pertanyaan</label>
                            <textarea name="pertanyaan" class="form-control mb-2" required></textarea>

                            <label>Pilihan Jawaban</label>
                            @for ($x = 0; $x < 4; $x++)
                                <input type="text" class="form-control mb-2" name="pilihan[{{ $x }}]"
                                    placeholder="Pilihan {{ chr(65 + $x) }}">
                            @endfor

                            <label>Kunci Jawaban</label>
                            <select name="jawaban" class="form-control">
                                <option value="">-- Pilih --</option>
                                @for ($x = 0; $x < 4; $x++)
                                    <option value="{{ $x }}">{{ chr(65 + $x) }}</option>
                                @endfor
                            </select>

                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                Batal
                            </button>

                            <button class="btn btn-primary">Simpan</button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    @endforeach


    {{-- =======================================================
    MODAL EDIT SOAL
======================================================= --}}
    @foreach ($kategori as $kat)
        @foreach ($kat->soal as $soal)
            <div class="modal fade" id="modalEditSoal{{ $soal->id }}" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">

                        <form action="{{ route('admin.master-soal.soal.update', $soal->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="modal-header">
                                <h5 class="modal-title">Edit Soal</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body">

                                <label>Pertanyaan</label>
                                <textarea name="pertanyaan" class="form-control mb-2">{{ $soal->pertanyaan }}</textarea>

                                <label>Pilihan Jawaban</label>
                                @foreach ($soal->pilihan as $key => $pil)
                                    <input type="text" name="pilihan[{{ $key }}]" class="form-control mb-2"
                                        value="{{ $pil }}">
                                @endforeach

                                <label>Kunci Jawaban</label>
                                <select name="jawaban" class="form-control">
                                    @foreach ($soal->pilihan as $key => $pil)
                                        <option value="{{ $key }}"
                                            {{ $soal->jawaban == $key ? 'selected' : '' }}>
                                            {{ chr(65 + $key) }}
                                        </option>
                                    @endforeach
                                </select>

                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    Batal
                                </button>

                                <button class="btn btn-warning">Update</button>
                            </div>

                        </form>

                    </div>
                </div>
            </div>
        @endforeach
    @endforeach


    {{-- =======================================================
    FILTER SCRIPT
======================================================= --}}
    <script>
        document.getElementById('filterKategori').addEventListener('change', function() {
            let val = this.value;
            document.querySelectorAll('#tabelSoal tbody tr').forEach(tr => {
                tr.style.display = (val === "" || tr.dataset.kategori === val) ? "" : "none";
            });
        });

        function handleTipe(select) {
            const modal = select.closest('.modal-content');
            const threshold = modal.querySelector('.field-threshold');
            const benefit = modal.querySelector('.field-benefit');

            threshold.classList.add('d-none');
            benefit.classList.add('d-none');

            if (select.value === 'threshold') {
                threshold.classList.remove('d-none');
            }

            if (select.value === 'benefit') {
                benefit.classList.remove('d-none');
            }
        }

        document.querySelectorAll('.tipe-kriteria').forEach(select => {
            select.addEventListener('change', function() {
                handleTipe(this);
            });

            if (select.dataset.current) {
                handleTipe(select);
            }
        });
    </script>

@endsection
