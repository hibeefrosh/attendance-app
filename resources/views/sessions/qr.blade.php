<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>QR — {{ $session->course->code }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(160deg, #2A2660 0%, #4C44CF 100%);
            color: #fff;
            min-height: 100vh;
        }
        .qr-display {
            background: #fff;
            border-radius: 1.5rem;
            padding: 2rem;
            display: inline-block;
            box-shadow: 0 20px 50px rgba(0,0,0,.25);
        }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center text-center p-4">
    <div>
        <h1 class="display-6 fw-bold mb-2">{{ $session->course->code }}</h1>
        <p class="mb-1">{{ $session->course->title }}</p>
        <p class="opacity-75 mb-4">{{ $session->session_date->format('M d, Y') }} · Expires {{ $session->expires_at->format('H:i') }}</p>
        <div class="qr-display mb-4">{!! $qrSvg !!}</div>
        <p class="mb-0">Scan with the Smart Attendance student app</p>
        <a href="{{ route('sessions.show', $session) }}" class="btn btn-outline-light mt-4 btn-pill">Back to session</a>
    </div>
    <script>setTimeout(() => location.reload(), 60000);</script>
</body>
</html>
