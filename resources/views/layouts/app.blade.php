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
    <style>
        :root { --primary: #4C44CF; --bg: #F8F9FB; --sidebar: #2A2660; }
        body { margin: 0; font-family: Inter, system-ui, -apple-system, sans-serif; background: var(--bg); }
        .btn-brand {
            background: linear-gradient(135deg, #5b54e6, #4C44CF) !important;
            border: 0 !important;
            color: #fff !important;
            border-radius: 999px !important;
            font-weight: 700;
        }
        .student-shell { max-width: 480px; margin: 0 auto; min-height: 100vh; background: var(--bg); }
    </style>
    <link href="{{ asset('css/app.css') }}?v={{ @filemtime(public_path('css/app.css')) ?: time() }}" rel="stylesheet">
    @stack('styles')
</head>
<body>
@if(auth()->check() && auth()->user()->isLecturer())
    @include('layouts.lecturer')
@elseif(auth()->check() && auth()->user()->isStudent())
    @include('layouts.student')
@else
    @yield('content')
@endif

<div class="toast-container position-fixed bottom-0 end-0 p-3" id="toastContainer" style="z-index:3000"></div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('js/app.js') }}?v={{ @filemtime(public_path('js/app.js')) ?: time() }}"></script>
@stack('scripts')
</body>
</html>
