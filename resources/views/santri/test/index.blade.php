@extends('layouts.dashboard')

@section('title', 'Tes Seleksi')
@section('judul', 'Tes Seleksi')

@section('content')

    <div class="card shadow p-4">

        <h4 class="fw-bold mb-3">Tes Seleksi Santri Baru</h4>

        <div class="alert alert-info">
            Jawablah semua soal dengan teliti. Waktu berjalan terus setelah Anda mulai tes.
        </div>

        <form action="{{ route('santri.test.submit') }}" method="POST">
            @csrf

            @foreach ($kategori as $kat)
                <div class="mt-4">
                    <h5 class="fw-bold">{{ $kat->nama_kategori }}</h5>
                </div>

                @foreach ($kat->soal as $index => $soal)
                    <div class="card mb-3">
                        <div class="card-body">

                            <p class="fw-bold">
                                {{ $index + 1 }}. {{ $soal->pertanyaan }}
                            </p>

                            @php $pilihan = $soal->pilihan; @endphp

                            @foreach ($pilihan as $opt => $value)
                                <div class="form-check mb-1">
                                    <input type="radio" class="form-check-input" name="jawaban[{{ $soal->id }}]"
                                        value="{{ $opt }}" required>
                                    <label class="form-check-label">{{ $value }}</label>
                                </div>
                            @endforeach

                        </div>
                    </div>
                @endforeach
            @endforeach

            <button class="btn btn-success mt-3">
                <i class="fas fa-check-circle"></i> Selesaikan Tes
            </button>

        </form>

    </div>

@endsection
