<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Santri</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            height: 100vh;
        }

        .register-card {
            max-width: 760px;
            /* diperbesar untuk 2 kolom */
            margin: auto;
            margin-top: 3%;
            padding: 35px;
            border-radius: 15px;
            background: #ffffff;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            animation: fadeIn .5s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .brand-title {
            font-weight: 700;
            font-size: 28px;
        }

        .form-control {
            padding-left: 45px;
        }

        .input-icon {
            position: absolute;
            top: 37px;
            left: 12px;
            font-size: 18px;
            color: #6c757d;
        }
    </style>
</head>

<body>
    <div class="register-card">
        @if ($pendaftaranDitutup)
            {{-- ================== PENDAFTARAN DITUTUP ================== --}}
            <div class="text-center">
                <i class="bi bi-lock-fill text-danger" style="font-size: 60px;"></i>
                <h4 class="mt-3 fw-bold">Pendaftaran Ditutup</h4>

                <p class="text-muted mt-2">
                    Pendaftaran santri untuk tahun akademik ini telah ditutup.
                    <br>
                    Silakan mencoba kembali pada tahun akademik berikutnya.
                </p>

                <a href="{{ route('login') }}" class="btn btn-outline-danger mt-3">
                    <i class="bi bi-box-arrow-in-right"></i> Kembali ke Login
                </a>
            </div>
        @else
            <div class="text-center mb-4">
                <i class="bi bi-person-plus-fill text-danger" style="font-size: 50px;"></i>
                <h4 class="brand-title">Daftar Akun Santri</h4>
                <small class="text-muted">Lengkapi form di bawah</small>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show">
                    <strong>Terjadi kesalahan:</strong>
                    <ul class="mb-0 mt-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="row g-3">
                    <!-- Kolom KIRI -->
                    <div class="col-md-6">

                        <!-- Username -->
                        <div class="mb-3 position-relative">
                            <label class="form-label">Username</label>
                            <i class="bi bi-person input-icon"></i>
                            <input type="text" class="form-control" name="username" required>
                        </div>

                        <!-- Nama Lengkap -->
                        <div class="mb-3 position-relative">
                            <label class="form-label">Nama Lengkap</label>
                            <i class="bi bi-person-vcard input-icon"></i>
                            <input type="text" class="form-control" name="name" required>
                        </div>

                        <!-- Email -->
                        <div class="mb-3 position-relative">
                            <label class="form-label">Email</label>
                            <i class="bi bi-envelope input-icon"></i>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                    </div>

                    <!-- Kolom KANAN -->
                    <div class="col-md-6">

                        <!-- Nomor Telepon -->
                        <div class="mb-3 position-relative">
                            <label class="form-label">Nomor Telepon</label>
                            <i class="bi bi-telephone input-icon"></i>
                            <input type="text" class="form-control" name="no_telp" required>
                        </div>

                        <!-- NIK -->
                        <div class="mb-3 position-relative">
                            <label class="form-label">NIK</label>
                            <i class="bi bi-card-text input-icon"></i>
                            <input type="text" class="form-control" name="nik" required>
                        </div>

                        <!-- Password -->
                        <div class="mb-3 position-relative">
                            <label class="form-label">Password</label>
                            <i class="bi bi-lock input-icon"></i>
                            <input type="password" class="form-control" name="password" required>
                        </div>

                        <!-- Konfirmasi Password -->
                        <div class="mb-3 position-relative">
                            <label class="form-label">Konfirmasi Password</label>
                            <i class="bi bi-lock-fill input-icon"></i>
                            <input type="password" class="form-control" name="password_confirmation" required>
                        </div>

                    </div>
                </div>

                <button class="btn btn-danger w-100 py-2 mt-3">Daftar</button>

                <p class="text-center mt-3 mb-0">
                    Sudah punya akun?
                    <a href="{{ route('login') }}" class="text-decoration-none text-danger">Masuk</a>
                </p>
            </form>
        @endif
    </div>
</body>

</html>
