<div class="table-responsive">
    <table class="table table-bordered table-striped align-middle">
        <thead class="table-light">
            <tr>
                <th>#</th>
                <th>Santri</th>
                <th>Jenis</th>
                <th>Nominal</th>
                <th>Rekening Tujuan</th>
                <th>Bukti Pembayaran</th>
                <th>Status</th>
                <th style="width: 180px;">Aksi</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($list as $key => $p)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>
                        <strong>{{ $p->user->name }}</strong><br>
                        <small>{{ $p->user->email }}</small>
                    </td>

                    <td>{{ ucfirst($p->jenis) }}</td>

                    <td>Rp {{ number_format($p->nominal_bayar, 0, ',', '.') }}</td>

                    <td>{{ $p->rekening->bank }} - {{ $p->rekening->nomor_rekening }}</td>

                    <td>
                        @if ($p->bukti_transfer)
                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                data-bs-target="#previewBukti{{ $p->id }}">
                                Lihat Bukti
                            </button>
                        @else
                            <span class="text-muted">Belum ada</span>
                        @endif
                    </td>

                    <td>
                        @if ($p->status == 'menunggu')
                            <span class="badge bg-warning">Menunggu</span>
                        @elseif ($p->status == 'diterima')
                            <span class="badge bg-success">Diterima</span>
                        @else
                            <span class="badge bg-danger">Ditolak</span><br>
                            <small>{{ $p->catatan_admin }}</small>
                        @endif
                    </td>

                    <td>
                        @if ($p->status == 'menunggu')
                            {{-- Approve --}}
                            <form action="{{ route('admin.payment.approve', $p->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button class="btn btn-sm btn-success">
                                    <i class="fas fa-check"></i> Terima
                                </button>
                            </form>

                            {{-- Reject Button --}}
                            <button class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                data-bs-target="#rejectModal{{ $p->id }}">
                                <i class="fas fa-times"></i> Tolak
                            </button>
                        @else
                            <em class="text-muted">Sudah diverifikasi</em>
                        @endif
                    </td>
                </tr>

                {{-- MODAL PREVIEW --}}
                <div class="modal fade" id="previewBukti{{ $p->id }}" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Bukti Pembayaran</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body text-center">
                                <img src="{{ asset('storage/' . $p->bukti_transfer) }}"
                                    class="img-fluid rounded border" alt="bukti">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- MODAL REJECT --}}
                <div class="modal fade" id="rejectModal{{ $p->id }}" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <form action="{{ route('admin.payment.reject', $p->id) }}" method="POST"
                            class="modal-content">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title text-danger">Tolak Pembayaran</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <div class="modal-body">
                                <label class="form-label">Alasan Penolakan:</label>
                                <textarea name="catatan_admin" rows="3" class="form-control" required></textarea>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    Batal
                                </button>
                                <button type="submit" class="btn btn-danger">
                                    Tolak Pembayaran
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-4">
                        Tidak ada data pembayaran.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
