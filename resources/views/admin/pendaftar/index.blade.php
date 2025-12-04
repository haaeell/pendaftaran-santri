@extends('layouts.dashboard')

@section('title', 'Data Pendaftar')
@section('judul', 'Data Pendaftar')

@section('content')

    <div class="card shadow-sm border-0">

        <div class="card-header">
            <h5 class="fw-bold mb-0">Data Pendaftar</h5>
        </div>

        <div class="card-body">

            {{-- ======================= TAB NAV ======================= --}}
            <ul class="nav nav-tabs mb-4" id="pendaftarTab" role="tablist">
                @php
                    $tabs = [
                        '' => 'Semua',
                        'belum_diterima' => 'Belum Diproses',
                        'lolos_seleksi' => 'Lolos Seleksi',
                        'tidak_lolos_seleksi' => 'Tidak Lolos',
                        'diterima' => 'Diterima',
                    ];
                @endphp

                @foreach ($tabs as $key => $label)
                    <li class="nav-item" role="presentation">
                        <a class="nav-link {{ $status === $key ? 'active' : '' }}"
                            href="{{ route('admin.pendaftar.index', ['status' => $key]) }}">
                            {{ $label }}
                        </a>
                    </li>
                @endforeach
            </ul>

            {{-- ======================= TABLE ======================= --}}
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">#</th>
                            <th>Nama</th>
                            <th>No. Pendaftaran</th>
                            <th>Pendidikan Tujuan</th>
                            <th>Status Seleksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($santri as $i => $s)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $s->name }}</td>
                                <td>{{ $s->registration_id }}</td>
                                <td>{{ $s->dataDiri->pendidikan_tujuan }}</td>
                                <td>
                                    @php
                                        $st = $s->dataDiri->status_seleksi;
                                        $badge = [
                                            'belum_diterima' => 'secondary',
                                            'lolos_seleksi' => 'success',
                                            'tidak_lolos_seleksi' => 'danger',
                                            'diterima' => 'primary',
                                        ][$st];
                                    @endphp

                                    <span class="badge bg-{{ $badge }}">
                                        {{ strtoupper(str_replace('_', ' ', $st)) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    Tidak ada data untuk kategori ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

        </div>
    </div>

@endsection
