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
                                        @if ($hasil && $hasil->lulus_threshold)
                                            <span class="badge bg-success">Lulus</span>
                                        @else
                                            <span class="badge bg-danger">Tidak</span>
                                        @endif
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

                            <td>
                                @php
                                    $badge = [
                                        'belum_diterima' => 'secondary',
                                        'lolos_seleksi' => 'success',
                                        'tidak_lolos_seleksi' => 'danger',
                                        'diterima' => 'success',
                                    ][$status];
                                @endphp

                                <span class="badge bg-{{ $badge }} text-white">
                                    {{ strtoupper(str_replace('_', ' ', $status)) }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>

@endsection
