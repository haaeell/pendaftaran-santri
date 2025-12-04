@extends('layouts.dashboard')

@section('title', 'Hasil Seleksi')
@section('judul', 'Hasil Seleksi')

@section('content')

    <div class="card shadow p-4">

        <h4 class="fw-bold mb-3">Hasil Tes Peserta</h4>

        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Nama Peserta</th>
                        <th>No. Pendaftaran</th>

                        {{-- LOOPING KATEGORI MENJADI TH --}}
                        @foreach ($kategori as $kat)
                            <th>{{ $kat->nama_kategori }}</th>
                        @endforeach

                        <th>Hasil Akhir</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($santri as $i => $s)
                        @php
                            $hasilTes = $s->hasilTes->keyBy('kategori_id');
                            $semuaLulus = true;
                        @endphp

                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $s->name }}</td>
                            <td>{{ $s->id }}</td>

                            {{-- LOOP SETIAP KATEGORI UNTUK MENAMPILKAN NILAI --}}
                            @foreach ($kategori as $kat)
                                @php
                                    $hasil = $hasilTes[$kat->id] ?? null;
                                    $nilai = $hasil->nilai ?? 0;
                                    $lulus = $nilai >= 75;

                                    if (!$lulus) {
                                        $semuaLulus = false;
                                    }
                                @endphp

                                <td>
                                    <span class=" fw-bold {{ $lulus ? 'text-success' : 'text-danger' }}">
                                        {{ $nilai }}
                                    </span>
                                </td>
                            @endforeach

                            {{-- HASIL AKHIR --}}
                            <td>
                                @if ($semuaLulus)
                                    <span class="badge text-white bg-success">Lolos Seleksi</span>
                                @else
                                    <span class="badge text-white bg-danger">Tidak Lolos</span>
                                @endif
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>

@endsection
