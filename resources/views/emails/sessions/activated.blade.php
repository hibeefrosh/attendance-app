<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Confirmed</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #111827;">
    <p>Hello {{ $student->name }},</p>

    <p>
        Your attendance has been successfully recorded for
        <strong>{{ $session->course->code }} - {{ $session->course->title }}</strong>.
    </p>

    <p>
        <strong>Session:</strong> {{ $session->displayName() }}<br>
        <strong>Date:</strong> {{ $session->session_date->format('M d, Y') }}<br>
        <strong>Start:</strong> {{ \Illuminate\Support\Carbon::parse($session->start_time)->format('g:i A') }}<br>
        <strong>End:</strong> {{ \Illuminate\Support\Carbon::parse($session->end_time)->format('g:i A') }}
    </p>

    <p>Thank you for checking in.</p>

    <p>Regards,<br>{{ config('app.name') }}</p>
</body>
</html>
