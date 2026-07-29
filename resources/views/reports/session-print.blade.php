<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Attendance — {{ $session->course->code }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { padding: 2rem; }
        @media print { .no-print { display: none !important; } }
    </style>
</head>
<body>
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h2 class="mb-1">{{ config('app.name') }}</h2>
            <h4>{{ $session->course->code }} — {{ $session->course->title }}</h4>
            <p class="mb-0 text-muted">
                {{ $session->session_date->format('l, F j, Y') }}
                · {{ substr($session->start_time, 0, 5) }}–{{ substr($session->end_time, 0, 5) }}
            </p>
        </div>
        <button class="btn btn-primary no-print" onclick="window.print()">Print / Save as PDF</button>
    </div>

    <p>
        <strong>Present:</strong> {{ $presentIds->count() }} /
        <strong>Enrolled:</strong> {{ $enrolled->count() }}
    </p>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Matric Number</th>
                <th>Full Name</th>
                <th>Status</th>
                <th>Check-in Time</th>
            </tr>
        </thead>
        <tbody>
            @foreach($enrolled as $i => $student)
                @php $present = $presentIds->contains($student->id); @endphp
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $student->matric_number }}</td>
                    <td>{{ $student->name }}</td>
                    <td>{{ $present ? 'Present' : 'Absent' }}</td>
                    <td>
                        {{ $present ? $session->attendanceRecords->firstWhere('student_id', $student->id)?->checked_in_at?->format('H:i:s') : '—' }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
