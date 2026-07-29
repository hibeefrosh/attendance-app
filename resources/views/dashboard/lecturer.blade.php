@extends('layouts.app')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')
@section('page_subtitle', 'Overview of today’s attendance activity')

@section('content')
<div class="row g-3 mb-4">
    <div class="col-md-6 col-xl-3">
        <div class="card stat-card h-100">
            <div class="card-body d-flex justify-content-between align-items-start">
                <div>
                    <div class="text-muted small mb-1">Total Courses</div>
                    <div class="fs-3 fw-bold">{{ $stats['courses'] }}</div>
                </div>
                <div class="icon-box icon-purple"><i class="bi bi-journal-bookmark-fill"></i></div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card stat-card h-100">
            <div class="card-body d-flex justify-content-between align-items-start">
                <div>
                    <div class="text-muted small mb-1">Today’s Sessions</div>
                    <div class="fs-3 fw-bold">{{ $stats['today_sessions'] }}</div>
                </div>
                <div class="icon-box icon-green"><i class="bi bi-calendar-check-fill"></i></div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card stat-card h-100">
            <div class="card-body d-flex justify-content-between align-items-start">
                <div>
                    <div class="text-muted small mb-1">Total Students</div>
                    <div class="fs-3 fw-bold">{{ $stats['students'] }}</div>
                </div>
                <div class="icon-box icon-blue"><i class="bi bi-people-fill"></i></div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card stat-card h-100">
            <div class="card-body d-flex justify-content-between align-items-start">
                <div>
                    <div class="text-muted small mb-1">Attendance Rate</div>
                    <div class="fs-3 fw-bold">{{ $stats['attendance_percentage'] }}%</div>
                </div>
                <div class="icon-box icon-orange"><i class="bi bi-graph-up-arrow"></i></div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-xl-7">
        <div class="card sa-card h-100">
            <div class="card-header bg-white border-0 pt-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Today’s Sessions</h5>
                <a href="{{ route('sessions.create') }}" class="btn btn-sm btn-primary btn-pill">New Session</a>
            </div>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Session</th>
                            <th>Course</th>
                            <th>Time</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($todaySessions as $session)
                            <tr>
                                <td class="fw-semibold">{{ $session->title ?: 'Lecture Session' }}</td>
                                <td>{{ $session->course->code }}</td>
                                <td>{{ substr($session->start_time, 0, 5) }} – {{ substr($session->end_time, 0, 5) }}</td>
                                <td>
                                    <span class="badge-pill {{ $session->status === 'active' ? 'badge-active' : 'badge-upcoming' }}">
                                        {{ $session->status === 'active' ? 'Active' : ucfirst($session->status) }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    @if($session->status === 'active')
                                        <button type="button"
                                            class="btn btn-sm btn-outline-primary btn-pill view-qr-btn"
                                            data-bs-toggle="modal"
                                            data-bs-target="#qrModal"
                                            data-qr-url="{{ route('sessions.qr-image', $session) }}"
                                            data-session-title="{{ $session->course->code }} — {{ $session->title ?: 'Session' }}">
                                            View QR
                                        </button>
                                    @else
                                        <a href="{{ route('sessions.show', $session) }}" class="btn btn-sm btn-light btn-pill">Open</a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-muted p-4">No sessions scheduled for today.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-xl-5">
        <div class="card sa-card h-100">
            <div class="card-header bg-white border-0 pt-3">
                <h5 class="mb-0 fw-bold">Attendance Overview</h5>
            </div>
            <div class="card-body d-flex flex-column align-items-center">
                <div style="width:220px;height:220px;position:relative">
                    <canvas id="donutChart"></canvas>
                    <div class="position-absolute top-50 start-50 translate-middle text-center">
                        <div class="fs-3 fw-bold">{{ $donut['average'] }}%</div>
                        <div class="text-muted small">Average</div>
                    </div>
                </div>
                <div class="d-flex gap-3 mt-3 small">
                    <span><span class="d-inline-block rounded-circle me-1" style="width:10px;height:10px;background:#4C44CF"></span> Present</span>
                    <span><span class="d-inline-block rounded-circle me-1" style="width:10px;height:10px;background:#e5e7eb"></span> Absent</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-xl-7">
        <div class="card sa-card h-100">
            <div class="card-header bg-white border-0 pt-3">
                <h5 class="mb-0 fw-bold">Recent Attendance</h5>
            </div>
            <div class="card-body">
                @forelse($recentAttendance as $record)
                    <div class="d-flex align-items-center gap-3 py-2 border-bottom">
                        <div class="avatar avatar-sm">{{ strtoupper(substr($record->student->name, 0, 1)) }}</div>
                        <div class="flex-grow-1">
                            <div class="fw-semibold">{{ $record->student->name }}</div>
                            <div class="text-muted small">{{ $record->course->code }} · {{ $record->checked_in_at->format('g:i A') }}</div>
                        </div>
                        <span class="badge-pill badge-present">Present</span>
                    </div>
                @empty
                    <p class="text-muted mb-0">No recent check-ins.</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="col-xl-5">
        <div class="card sa-card h-100">
            <div class="card-header bg-white border-0 pt-3">
                <h5 class="mb-0 fw-bold">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-6">
                        <a href="{{ route('sessions.create') }}" class="quick-action">
                            <div class="qa-icon"><i class="bi bi-plus-lg"></i></div>
                            <div class="fw-semibold">Create Session</div>
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('courses.create') }}" class="quick-action">
                            <div class="qa-icon"><i class="bi bi-journal-plus"></i></div>
                            <div class="fw-semibold">Add Course</div>
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ route('reports.index') }}" class="quick-action">
                            <div class="qa-icon"><i class="bi bi-bar-chart"></i></div>
                            <div class="fw-semibold">View Reports</div>
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="{{ $firstCourse ? route('courses.students', $firstCourse) : route('courses.index') }}" class="quick-action">
                            <div class="qa-icon"><i class="bi bi-people"></i></div>
                            <div class="fw-semibold">Manage Students</div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card sa-card">
    <div class="card-header bg-white border-0 pt-3">
        <h5 class="mb-0 fw-bold">Attendance Trend</h5>
    </div>
    <div class="card-body">
        <canvas id="trendChart" height="90"></canvas>
    </div>
</div>

{{-- QR Modal --}}
<div class="modal fade" id="qrModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0" style="border-radius:20px">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold" id="qrModalTitle">Session QR</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center pb-4">
                <div class="qr-frame mb-3">
                    <img id="qrModalImage" src="" alt="QR Code" style="width:260px;height:260px">
                </div>
                <p class="text-muted small mb-0">Students scan this code to mark attendance.</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
new Chart(document.getElementById('donutChart'), {
    type: 'doughnut',
    data: {
        labels: ['Present', 'Absent'],
        datasets: [{
            data: [@json($donut['present']), @json($donut['absent'])],
            backgroundColor: ['#4C44CF', '#e5e7eb'],
            borderWidth: 0,
            cutout: '72%'
        }]
    },
    options: {
        plugins: { legend: { display: false } },
        maintainAspectRatio: false
    }
});

new Chart(document.getElementById('trendChart'), {
    type: 'line',
    data: {
        labels: @json($trendLabels),
        datasets: [{
            label: 'Attendance %',
            data: @json($trendData),
            borderColor: '#4C44CF',
            backgroundColor: 'rgba(76,68,207,.12)',
            fill: true,
            tension: .4,
            pointRadius: 4,
            pointBackgroundColor: '#4C44CF'
        }]
    },
    options: {
        scales: { y: { beginAtZero: true, max: 100 } },
        plugins: { legend: { display: false } }
    }
});

document.querySelectorAll('.view-qr-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        document.getElementById('qrModalTitle').textContent = btn.dataset.sessionTitle;
        document.getElementById('qrModalImage').src = btn.dataset.qrUrl;
    });
});
</script>
@endpush
