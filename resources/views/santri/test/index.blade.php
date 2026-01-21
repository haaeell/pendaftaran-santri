@extends('layouts.dashboard')

@section('title', 'Tes Seleksi')
@section('judul', 'Tes Seleksi')

@section('content')
    <style>
        #exam-timer {
            position: fixed;
            top: 90px;
            right: 20px;
            z-index: 999;
        }

        .timer-card {
            display: flex;
            align-items: center;
            gap: 12px;
            background: linear-gradient(135deg, #ffc107, #ff9800);
            color: #fff;
            padding: 14px 18px;
            border-radius: 14px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.25);
            min-width: 220px;
            transition: all 0.3s ease;
        }

        .timer-icon {
            font-size: 32px;
        }

        .timer-content small {
            display: block;
            font-size: 12px;
            opacity: 0.9;
            margin-bottom: 2px;
        }

        #countdown {
            font-size: 22px;
            font-weight: 800;
            letter-spacing: 1px;
        }

        /* WARNING (<= 5 menit) */
        .timer-danger {
            background: linear-gradient(135deg, #dc3545, #b02a37);
            animation: pulse 1s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.03);
            }

            100% {
                transform: scale(1);
            }
        }

        /* Mobile */
        @media (max-width: 768px) {
            #exam-timer {
                top: auto;
                bottom: 20px;
                right: 50%;
                transform: translateX(50%);
            }
        }
    </style>

    <div class="card shadow p-4">

        <h4 class="fw-bold mb-3">Tes Seleksi Santri Baru</h4>

        @if ($jadwal)
            <div id="exam-timer">
                <div class="timer-card">
                    <div class="timer-icon">
                        ⏳
                    </div>
                    <div class="timer-content">
                        <small>Sisa Waktu Tes</small>
                        <div id="countdown">00:00:00</div>
                    </div>
                </div>
            </div>
        @endif


        <div class="alert alert-info border-0 shadow-sm">
            <h6 class="fw-bold mb-2">
                <i class="bi bi-exclamation-triangle-fill text-primary me-1"></i>
                Perhatian Penting!
            </h6>

            <ul class="mb-0 ps-3">
                <li>
                    <strong>Waktu tes yang berjalan mencakup seluruh rangkaian seleksi, termasuk:</strong>
                    <ul>
                        <li>Pengerjaan soal tertulis</li>
                        <li>Tes lanjutan (wawancara, praktik, membaca, menulis, atau metode lain)</li>
                    </ul>
                </li>

                <li>
                    <strong>Jangan menghabiskan seluruh waktu hanya untuk mengerjakan soal tertulis.</strong>
                </li>

                <li>
                    Pastikan Anda mengatur waktu dengan baik agar dapat mengikuti seluruh tahapan tes.
                </li>
            </ul>
        </div>


        <form action="{{ route('santri.test.submit') }}" method="POST">
            @csrf

            @foreach ($kategori as $kat)
                <div class="mt-4">
                    <h5 class="fw-bold">
                        {{ $kat->nama_kategori }}

                        @if ($kat->metode === 'gmeet')
                            <span class="badge bg-danger text-white ms-2">
                                Wawancara Google Meet
                            </span>
                        @endif
                    </h5>
                </div>

                {{-- ===================== JIKA GMEET ===================== --}}
                @if ($kat->metode === 'gmeet')
                    <div class="alert alert-info">
                        <strong>Informasi Penting:</strong><br>
                        Kategori ini <b>tidak memiliki soal tertulis</b>.<br>
                        Setelah tes tertulis selesai, Anda akan mengikuti
                        <b>wawancara melalui Google Meet</b> sesuai jadwal yang ditentukan admin.
                    </div>

                    {{-- kirim penanda ke backend --}}
                    <input type="hidden" name="gmeet_kategori[]" value="{{ $kat->id }}">

                    {{-- ===================== JIKA PG ===================== --}}
                @else
                    @foreach ($kat->soal as $index => $soal)
                        <div class="card mb-3">
                            <div class="card-body">

                                <p class="fw-bold">
                                    {{ $index + 1 }}. {{ $soal->pertanyaan }}
                                </p>

                                @foreach ($soal->pilihan as $opt => $value)
                                    <div class="form-check mb-1">
                                        <input type="radio" class="form-check-input" name="jawaban[{{ $soal->id }}]" value="{{ $opt }}"
                                            required>
                                        <label class="form-check-label">
                                            {{ $value }}
                                        </label>
                                    </div>
                                @endforeach

                            </div>
                        </div>
                    @endforeach
                @endif
            @endforeach


            <button class="btn btn-success mt-3">
                <i class="fas fa-check-circle"></i> Selesaikan Tes
            </button>

        </form>

    </div>

    @if ($jadwal)
        <script>
            const endTime = new Date("{{ $jadwal->waktu_selesai->format('Y-m-d H:i:s') }}").getTime();
            const countdownEl = document.getElementById('countdown');
            const timerCard = document.querySelector('.timer-card');
            const form = document.querySelector('form');

            const timer = setInterval(() => {
                const now = new Date().getTime();
                const distance = endTime - now;

                if (distance <= 0) {
                    clearInterval(timer);
                    countdownEl.innerHTML = "00:00:00";
                    alert("⏰ Waktu tes telah habis. Jawaban akan otomatis dikirim.");
                    form.submit();
                    return;
                }

                const hours = Math.floor(distance / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                countdownEl.innerHTML =
                    `${hours.toString().padStart(2, '0')}:` +
                    `${minutes.toString().padStart(2, '0')}:` +
                    `${seconds.toString().padStart(2, '0')}`;

                // WARNING jika sisa <= 5 menit
                if (distance <= 5 * 60 * 1000) {
                    timerCard.classList.add('timer-danger');
                }

            }, 1000);
        </script>
    @endif


@endsection