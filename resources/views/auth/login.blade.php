<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Rapor Praktis</title>
    <meta name="description" content="Pintu masuk portal Sistem Informasi Laporan Hasil Belajar (Rapor) Santri Pondok Pesantren Tahfidzul Qur'an Makkah Madinatul Qur'an Pacitan.">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="Login - Sistem Rapor MMQ Digital">
    <meta property="og:description" content="Pintu masuk portal Sistem Informasi Laporan Hasil Belajar (Rapor) Santri Pondok Pesantren Tahfidzul Qur'an Makkah Madinatul Qur'an Pacitan.">
    <meta property="og:image" content="{{ asset('images/logoo.png') }}">
    <meta property="og:site_name" content="MMQ Digital">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="Login - Sistem Rapor MMQ Digital">
    <meta property="twitter:description" content="Pintu masuk portal Sistem Informasi Laporan Hasil Belajar (Rapor) Santri Pondok Pesantren Tahfidzul Qur'an Makkah Madinatul Qur'an Pacitan.">
    <meta property="twitter:image" content="{{ asset('images/logoo.png') }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: radial-gradient(circle at top right, #047857, #064e3b, #022c22);
            background-image: radial-gradient(circle at top right, rgba(4, 120, 87, 0.8), rgba(6, 78, 59, 0.95), rgba(2, 44, 34, 0.98)), url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='80' height='80' viewBox='0 0 80 80'%3E%3Cg fill='%23ffffff' fill-opacity='0.015'%3E%3Cpath d='M0 0h80v80H0z'/%3E%3Cpath d='M40 0l40 40-40 40L0 40z'/%3E%3C/g%3E%3C/svg%3E");
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #e2e8f0;
        }

        .login-card {
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 24px;
            background: rgba(15, 23, 42, 0.5); /* Dark Glass */
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.6);
            padding: 2rem;
        }

        .form-control {
            border-radius: 12px;
            padding: 12px 16px;
            border: 1.5px solid #2d3748;
            background: rgba(30, 41, 59, 0.8);
            color: #f1f5f9;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            background: rgba(30, 41, 59, 0.95);
            color: #ffffff;
            border-color: #10b981;
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.15);
        }

        .form-control::placeholder {
            color: #64748b;
        }

        .btn-login {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            border: none;
            border-radius: 12px;
            padding: 14px;
            font-weight: 700;
            color: white;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.2);
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(16, 185, 129, 0.4);
            color: white;
        }

        .form-check-input {
            background-color: #1e293b;
            border-color: #334155;
        }

        .form-check-input:checked {
            background-color: #3b82f6;
            border-color: #3b82f6;
        }

        .form-label {
            color: #94a3b8;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .brand-logo {
            filter: drop-shadow(0 0 8px rgba(255, 255, 255, 0.2));
            margin-bottom: 1.5rem;
        }
        
        .welcome-title {
            color: #ffffff;
            font-weight: 800;
            letter-spacing: -0.025em;
        }

        .welcome-subtitle {
            color: #94a3b8;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">
            <div class="text-center mb-5">
                <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="#fbbf24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="brand-logo mb-3" style="filter: drop-shadow(0 4px 12px rgba(251, 191, 36, 0.45));">
                    <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                </svg>
                <h3 class="welcome-title mb-1">Selamat Datang</h3>
                <p class="welcome-subtitle small">Masuk untuk mengakses Sistem Rapor</p>
            </div>

            <div class="card login-card">
                <div class="card-body p-2">
                    @if (session('status'))
                        <div class="alert alert-success small rounded-3 mb-4 border-0" style="background: rgba(22, 163, 74, 0.2); color: #4ade80;">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="email" class="form-label fw-bold">Email</label>
                            <input id="email" type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required autofocus placeholder="nama@pesanren.com">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label fw-bold">Password</label>
                            <input id="password" type="password" name="password" class="form-control @error('password') is-invalid @enderror" required placeholder="••••••••">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                                <label class="form-check-label small text-muted" for="remember_me" style="color: #94a3b8 !important;">Ingat saya</label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-login w-100 mb-3">
                            MASUK DASHBOARD
                        </button>
                    </form>
                </div>
            </div>
            
            <div class="text-center mt-4 text-muted small" style="opacity: 0.6;">
                &copy; {{ date('Y') }} Sistem Rapor Praktis
            </div>
        </div>
    </div>
</div>

</body>
</html>