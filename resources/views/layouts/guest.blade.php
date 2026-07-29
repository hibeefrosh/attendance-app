<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#4C44CF">
    <title>@yield('title', 'Smart Attendance')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    {{-- Critical fallback so the page never renders unstyled if app.css is delayed --}}
    <style>
        body { margin: 0; font-family: Inter, system-ui, -apple-system, sans-serif; background: #eceef5; }
        .mobile-auth { min-height: 100vh; max-width: 480px; margin: 0 auto; background: #fff; }
        .welcome-screen { min-height: 100vh; display: flex; flex-direction: column; padding: 2.25rem 1.5rem 2rem; background: #fff; }
        .welcome-logo-badge {
            width: 64px; height: 64px; border-radius: 18px; display: grid; place-items: center;
            color: #fff; font-size: 1.85rem; margin: 0 auto 1rem;
            background: linear-gradient(145deg, #7B74EA 0%, #4C44CF 100%);
            box-shadow: 0 12px 28px rgba(76, 68, 207, .28);
        }
        .welcome-login-link { display: block; text-align: center; margin-top: 1rem; font-weight: 700; color: #4C44CF; text-decoration: none; }
        .auth-screen { padding: 1.25rem 1.25rem 2rem; }
        .login-screen { padding: 1.1rem 1.5rem 2rem; min-height: 100vh; background: #fff; }
        .login-back {
            display: inline-flex; align-items: center; justify-content: center;
            width: 40px; height: 40px; color: #111827; font-size: 1.35rem; text-decoration: none; margin-bottom: .75rem;
        }
        .login-header { margin: .5rem 0 2rem; }
        .login-title { font-size: 2rem; font-weight: 800; color: #111827; margin: 0 0 .5rem; }
        .login-subtitle { color: #9ca3af; font-size: .95rem; margin: 0; }
        .login-label { font-weight: 700; font-size: .9rem; color: #1f2937; margin-bottom: .45rem; }
        .login-input {
            border-radius: 14px !important; padding: .95rem 1.05rem !important;
            border: 1.5px solid #e5e7eb !important; font-size: 1rem;
        }
        .password-field { position: relative; }
        .password-field .login-input { padding-right: 3rem !important; }
        .password-toggle {
            position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
            border: 0; background: transparent; color: #9ca3af; padding: .25rem; line-height: 1;
        }
        .forgot-link { color: #4C44CF; font-size: .9rem; font-weight: 600; text-decoration: none; }
        .btn-login { padding: 1rem 1.25rem !important; font-size: 1.05rem !important; }
        .login-footer { margin-top: 2rem; color: #6b7280; font-size: .95rem; }
        .login-footer a { color: #4C44CF; font-weight: 700; text-decoration: none; }
        .btn-brand {
            background: linear-gradient(135deg, #5b54e6, #4C44CF) !important;
            border: 0 !important; color: #fff !important; border-radius: 999px !important;
            font-weight: 700; padding: .9rem 1.25rem; width: 100%;
            box-shadow: 0 10px 24px rgba(76, 68, 207, .35);
        }
    </style>
    <link href="{{ asset('css/app.css') }}?v={{ @filemtime(public_path('css/app.css')) ?: time() }}" rel="stylesheet">
</head>
<body>
    <div class="mobile-auth">
        @yield('content')
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.querySelectorAll('.toggle-pass').forEach(btn => {
            btn.addEventListener('click', () => {
                const wrap = btn.closest('.password-field') || btn.closest('.input-group') || btn.parentElement;
                const input = wrap.querySelector('input');
                const icon = btn.querySelector('i');
                const show = input.type === 'password';
                input.type = show ? 'text' : 'password';
                icon.className = show ? 'bi bi-eye-slash' : 'bi bi-eye';
            });
        });
    </script>
</body>
</html>
