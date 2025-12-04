<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            min-height: 100vh;
        }

        .login-card {
            max-width: 430px;
            margin: auto;
            margin-top: 8%;
            padding: 35px;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
            animation: fadeIn .4s ease;
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

        .input-icon {
            position: absolute;
            top: 38px;
            left: 12px;
            color: #6c757d;
            font-size: 18px;
        }

        .form-control {
            padding-left: 40px;
        }
    </style>
</head>

<body>
    <div class="login-card">
        <div class="text-center mb-4">
            <i class="bi bi-mortarboard-fill text-danger" style="font-size: 50px;"></i>
            <h3 class="fw-bold mt-2">Login Santri</h3>
            <small class="text-muted">Masuk menggunakan email Anda</small>
        </div>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email -->
            <div class="mb-3 position-relative">
                <label class="form-label">Email</label>
                <i class="bi bi-envelope input-icon"></i>
                <input type="email" class="form-control" name="email" required autofocus>
            </div>

            <!-- Password -->
            <div class="mb-3 position-relative">
                <label class="form-label">Password</label>
                <i class="bi bi-lock input-icon"></i>
                <input type="password" class="form-control" name="password" required>
            </div>

            <button class="btn btn-danger w-100 py-2">Masuk</button>

            <p class="text-center mt-3">Belum punya akun?
                <a href="{{ route('register') }}" class="text-decoration-none text-danger">Daftar</a>
            </p>
        </form>
    </div>
</body>

</html>
