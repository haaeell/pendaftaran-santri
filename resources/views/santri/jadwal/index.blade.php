@extends('layouts.dashboard')

@section('title', 'Jadwal Seleksi')
@section('judul', 'Jadwal Seleksi')

@section('content')

    <div class="card shadow p-4">

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        {{-- ======================== JIKA SUDAH TEST ======================== --}}
        @if (isset($hasilTes) && $hasilTes->count() > 0)

            <h5 class="fw-bold mb-3">Data Diri Singkat</h5>

            <table class="table table-bordered">
                <tr>
                    <th>ID Pendaftaran</th>
                    <td>{{ $user->registration_id }}</td>
                </tr>
                <tr>
                    <th width="30%">Nama Lengkap</th>
                    <td>{{ $user->name }}</td>
                </tr>
                <tr>
                    <th>NISN</th>
                    <td>{{ $user->dataDiri->nisn ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Pendidikan Tujuan</th>
                    <td>{{ $user->dataDiri->pendidikan_tujuan ?? '-' }}</td>
                </tr>
            </table>

            <hr class="my-4">

            <h5 class="fw-bold mb-3">Riwayat Tes Seleksi</h5>

            <div class="alert alert-success">
                Tes telah diselesaikan.
            </div>

            @if ($jadwalTes && $jadwalTes->link_gmeet)
                <div class="alert alert-primary d-flex align-items-center justify-content-between">
                    <div>
                        <strong>Tes Wawancara via Google Meet</strong><br>
                        Silakan bergabung melalui link berikut sesuai jadwal.
                    </div>

                    <a href="{{ $jadwalTes->link_gmeet }}" target="_blank" class="btn btn-success">
                        <i class="fab fa-google me-1"></i> Join Google Meet
                    </a>
                </div>
            @endif


            @foreach ($hasilTes as $item)
                @php
                    $data = json_decode($item->jawaban, true);
                    $jawaban = $data['jawaban'] ?? [];
                    $kategoriNama = $item->kategori->nama_kategori;
                @endphp

                <div class="card mb-4">
                    <div class="card-header bg-light fw-bold">
                        {{ $kategoriNama }}
                    </div>

                    <div class="card-body">

                        {{-- Tabel Detail Jawaban --}}
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Pertanyaan</th>
                                    <th width="25%">Jawaban User</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($jawaban as $i => $row)
                                    @php
                                        $soal = \App\Models\Soal::find($row['soal_id']);
                                    @endphp
                                    <tr>
                                        <td>{{ $i + 1 }}</td>

                                        <td>{{ $soal->pertanyaan ?? '-' }}</td>

                                        <td>
                                            {{ $row['jawaban_user_text'] ?? '-' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            @endforeach


            <hr class="my-4">
        @else
            {{-- ======================== BELUM TEST :: TAMPIL NORMAL ======================== --}}

            <h5 class="fw-bold mb-3">Pembayaran Registrasi Seleksi</h5>

            @if ($pembayaran)
                @if ($pembayaran->status == 'menunggu')
                    <div class="alert alert-warning">
                        <strong>Bukti pembayaran telah diupload.</strong><br>Menunggu verifikasi admin.
                    </div>
                @elseif ($pembayaran->status == 'ditolak')
                    <div class="alert alert-danger">
                        <strong>Pembayaran ditolak.</strong><br>
                        Alasan: {{ $pembayaran->catatan_admin }}<br>
                        Silakan upload ulang bukti pembayaran.
                    </div>
                @elseif ($pembayaran->status == 'diterima')
                    <div class="alert alert-success"><strong>Pembayaran diterima.</strong></div>
                @endif
            @else
                <div class="alert alert-info">
                    Silakan melakukan pembayaran biaya registrasi untuk mengikuti tes seleksi.
                </div>
            @endif

            <table class="table table-bordered mt-3">
                <tr>
                    <th width="40%">Biaya Registrasi</th>
                    <td>Rp {{ number_format($biaya->nominal, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <th>Status Pembayaran</th>
                    <td>
                        @if (!$pembayaran)
                            <span class="badge bg-danger">Belum Dibayar</span>
                        @elseif ($pembayaran->status == 'menunggu')
                            <span class="badge bg-warning text-dark">Menunggu Verifikasi</span>
                        @elseif ($pembayaran->status == 'ditolak')
                            <span class="badge bg-danger">Ditolak</span>
                        @else
                            <span class="badge bg-success">Diterima</span>
                        @endif
                    </td>
                </tr>
            </table>

            @if (isset($timeline) && $timeline->count() > 0)
                <h5 class="fw-bold mb-3">Jadwal Seleksi</h5>

                <table class="table table-bordered mb-4">
                    <thead class="table-light">
                        <tr>
                            <th width="35%">Gelombang</th>
                            <th>Mulai</th>
                            <th>Selesai</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($timeline as $t)
                            <tr>
                                <td>{{ $t->nama_gelombang }}</td>
                                <td>{{ \Carbon\Carbon::parse($t->mulai)->format('d M Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($t->selesai)->format('d M Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

            @if (!$pembayaran || $pembayaran->status == 'ditolak')
                <h6 class="fw-bold mt-4">Rekening Pembayaran</h6>

                <table class="table table-bordered align-middle mt-2">
                    <thead class="table-light">
                        <tr>
                            <th width="20%">Bank</th>
                            <th width="35%">No Rekening</th>
                            <th width="25%">Atas Nama</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($rekening as $r)
                            <tr>
                                <td><strong>{{ $r->bank }}</strong></td>

                                {{-- NO REKENING + COPY --}}
                                <td>
                                    <span id="rek-{{ $r->id }}">{{ $r->nomor_rekening }}</span>
                                    <button type="button" class="btn btn-sm btn-outline-primary ms-2"
                                        onclick="copyRekening('{{ $r->id }}')">
                                        <i class="bi bi-clipboard"></i>
                                    </button>
                                </td>

                                <td>{{ $r->atas_nama }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <h6 class="fw-bold mt-4 text-center">Pembayaran via QRIS</h6>

                <div class="row justify-content-center  ">
                    @foreach ($qris as $q)
                        @if ($q->aktif)
                            <div class="col-md-4 text-center mb-3">
                                <img src="{{ asset('storage/' . $q->image) }}" class="img-fluid rounded shadow" style="cursor:pointer"
                                    data-bs-toggle="modal" data-bs-target="#modalQris{{ $q->id }}">
                                <div class="mt-2 fw-bold">{{ $q->nama }}</div>
                            </div>
                        @endif
                    @endforeach
                </div>

                <hr>
                <form action="{{ route('santri.jadwal.upload') }}" method="POST" enctype="multipart/form-data" class="mt-3">
                    @csrf

                    <div class="mb-3">
                        <label class="fw-bold">Pilih Rekening Tujuan</label>
                        <select name="rekening_id" class="form-control">
                            <option value="">-- Pilih Rekening --</option>
                            @foreach ($rekening as $r)
                                <option value="{{ $r->id }}">{{ $r->bank }} - {{ $r->nomor_rekening }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold">Upload Bukti Pembayaran</label>
                        <input type="file" name="bukti_transfer" class="form-control" accept="image/*" required>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-upload"></i> Upload Bukti Pembayaran
                    </button>
                </form>
            @endif


            @if ($pembayaran && $pembayaran->status == 'diterima' && $jadwalTes)
                <div class="alert alert-primary mt-4">
                    <strong>Jadwal Tes Anda:</strong><br>
                    {{ date('d M Y H:i', strtotime($jadwalTes->waktu_mulai)) }} –
                    {{ date('H:i', strtotime($jadwalTes->waktu_selesai)) }}
                </div>

                @php
                    $now = now();
                    $start = \Carbon\Carbon::parse($jadwalTes->waktu_mulai);
                    $end = \Carbon\Carbon::parse($jadwalTes->waktu_selesai);
                @endphp

                @if ($now->lt($start))
                    <div class="alert alert-warning">Tes belum dapat dimulai.</div>
                @elseif ($now->between($start, $end))
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#mulaiTesModal">
                        Mulai Tes
                    </button>

                    <div class="modal fade" id="mulaiTesModal">
                        <div class="modal-dialog modal-dialog-centered">
                            <form action="{{ route('santri.test.start') }}" method="POST" class="modal-content">
                                @csrf
                                <div class="modal-body">
                                    Pastikan anda siap untuk mengikuti tes. Tes tidak dapat diulang.
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
                                    <button class="btn btn-primary">Mulai</button>
                                </div>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="alert alert-danger">Waktu tes telah berakhir anda dinyatakan gugur.</div>
                @endif

            @endif

        @endif

    </div>

    <script>
        function copyRekening(id) {
            const text = document.getElementById('rek-' + id).innerText;

            navigator.clipboard.writeText(text).then(() => {
                alert('Nomor rekening berhasil disalin!');
            }).catch(() => {
                alert('Gagal menyalin nomor rekening');
            });
        }
    </script>
@endsection