@extends('layouts.dashboard')

@section('title', 'Hasil Seleksi')
@section('judul', 'Hasil Seleksi')

@section('content')

    <div class="card shadow p-4">

        <h4 class="fw-bold mb-3">Hasil Tes Peserta</h4>

        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle text-center">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Nama Peserta</th>
                        <th>No. Pendaftaran</th>

                        @foreach ($kategori as $kat)
                            <th class="text-nowrap">
                                {{ $kat->nama_kategori }} <br>
                                <small class="text-muted">
                                    @if ($kat->tipe_kriteria === 'threshold')
                                        Threshold
                                    @else
                                        Bobot {{ $kat->bobot }}%
                                    @endif
                                </small>
                            </th>
                        @endforeach

                        <th class="text-nowrap">Nilai Akhir</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($santri as $i => $s)
                        @php
                            $hasilTes = $s->hasilTes->keyBy('kategori_id');
                            $status = $s->dataDiri->status_seleksi;
                        @endphp

                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td class="text-start">{{ $s->name }}</td>
                            <td>{{ $s->registration_id }}</td>

                            @foreach ($kategori as $kat)
                                @php
                                    $hasil = $hasilTes[$kat->id] ?? null;
                                @endphp

                                <td>
                                    @if ($kat->tipe_kriteria === 'threshold')
                                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                            data-bs-target="#threshold{{ $s->id }}{{ $kat->id }}">
                                            Set
                                        </button>

                                        <div class="mt-1">
                                            @if ($hasil && $hasil->lulus_threshold === true)
                                                <span class="badge bg-success">Lulus</span>
                                            @elseif ($hasil && $hasil->lulus_threshold === false)
                                                <span class="badge bg-danger">Tidak Lulus</span>
                                            @else
                                                <span class="badge bg-secondary">Belum</span>
                                            @endif
                                        </div>
                                    @elseif($kat->metode === 'gmeet')
                                        <button class="btn btn-sm btn-primary text-nowrap" data-bs-toggle="modal"
                                            data-bs-target="#nilaiGmeet{{ $s->id }}{{ $kat->id }}">
                                            Input Nilai
                                        </button>

                                        <div class="fw-bold mt-1">
                                            {{ $hasil->nilai ?? '-' }}
                                        </div>
                                    @else
                                        <span class="fw-bold">
                                            {{ $hasil->nilai ?? 0 }}
                                        </span>
                                    @endif
                                </td>
                            @endforeach

                            <td class="fw-bold">
                                {{ $s->dataDiri->nilai_akhir ?? 0 }}
                            </td>

                            @php
                            $belumLengkap = false;
                        
                            foreach ($kategori as $kat) {
                                $hasil = $hasilTes[$kat->id] ?? null;
                        
                                if ($kat->tipe_kriteria === 'threshold') {
                                    if (!$hasil || is_null($hasil->lulus_threshold)) {
                                        $belumLengkap = true;
                                        break;
                                    }
                                } else {
                                    if (!$hasil || is_null($hasil->nilai)) {
                                        $belumLengkap = true;
                                        break;
                                    }
                                }
                            }
                        @endphp
                        
                        <td>
                            @if ($belumLengkap)
                                <span class="badge bg-secondary">
                                    Belum Lengkap
                                </span>
                            @else
                                @php
                                    $badge = [
                                        'belum_diterima' => 'secondary',
                                        'lolos_seleksi' => 'success',
                                        'tidak_lolos_seleksi' => 'danger',
                                        'diterima' => 'success',
                                    ][$status];
                                @endphp
                        
                                <span class="badge bg-{{ $badge }}">
                                    {{ strtoupper(str_replace('_', ' ', $status)) }}
                                </span>
                            @endif
                        </td>                        
                        </tr>
                    @endforeach
                </tbody>
            </table>

            @foreach ($santri as $s)
                @foreach ($kategori as $kat)
                    @if ($kat->tipe_kriteria === 'threshold')
                        <div class="modal fade" id="threshold{{ $s->id }}{{ $kat->id }}">
                            <div class="modal-dialog modal-dialog-centered">
                                <form action="{{ route('admin.hasil.threshold.store') }}" method="POST"
                                    class="modal-content">
                                    @csrf

                                    <input type="hidden" name="user_id" value="{{ $s->id }}">
                                    <input type="hidden" name="kategori_id" value="{{ $kat->id }}">

                                    <div class="modal-header">
                                        <h5 class="modal-title">
                                            Threshold – {{ $s->name }}
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>

                                    <div class="modal-body">
                                        <label class="fw-bold mb-2">Hasil</label>
                                        <select name="lulus_threshold" class="form-control" required>
                                            <option value="">-- Pilih --</option>
                                            <option value="1">Lulus</option>
                                            <option value="0">Tidak Lulus</option>
                                        </select>
                                    </div>

                                    <div class="modal-footer">
                                        <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <button class="btn btn-primary">Simpan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif
                    @if ($kat->metode === 'gmeet')
                        <div class="modal fade" id="nilaiGmeet{{ $s->id }}{{ $kat->id }}">
                            <div class="modal-dialog modal-dialog-centered">
                                <form action="{{ route('admin.hasil.gmeet.store') }}" method="POST" class="modal-content">
                                    @csrf

                                    <input type="hidden" name="user_id" value="{{ $s->id }}">
                                    <input type="hidden" name="kategori_id" value="{{ $kat->id }}">

                                    <div class="modal-header">
                                        <h5 class="modal-title">
                                            Nilai Wawancara – {{ $s->name }}
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>

                                    <div class="modal-body">
                                        <label class="fw-bold mb-2">
                                            Nilai (0 – 100)
                                        </label>
                                        <input type="number" name="nilai" class="form-control" min="0"
                                            max="100" required>
                                    </div>

                                    <div class="modal-footer">
                                        <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <button class="btn btn-primary">Simpan Nilai</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif
                @endforeach
            @endforeach

        </div>

    </div>

@endsection
