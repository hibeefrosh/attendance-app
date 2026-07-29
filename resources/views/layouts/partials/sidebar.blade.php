<aside class="app-sidebar d-none d-lg-flex flex-column">
    <div class="sidebar-brand px-3 py-4">
        <a href="{{ route('dashboard') }}" class="text-decoration-none text-white d-flex align-items-center gap-2">
            <i class="bi bi-qr-code-scan fs-3"></i>
            <div>
                <div class="fw-bold">QR Attendance</div>
                <small class="opacity-75">{{ auth()->user()->isLecturer() ? 'Lecturer' : 'Student' }}</small>
            </div>
        </a>
    </div>

    <nav class="nav flex-column px-2 gap-1 flex-grow-1">
        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>
        <a class="nav-link {{ request()->routeIs('courses.*') ? 'active' : '' }}" href="{{ route('courses.index') }}">
            <i class="bi bi-journal-bookmark"></i> Courses
        </a>
        <a class="nav-link {{ request()->routeIs('sessions.*') ? 'active' : '' }}" href="{{ route('sessions.index') }}">
            <i class="bi bi-calendar-event"></i> Sessions
        </a>

        @if(auth()->user()->isLecturer())
            <a class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}" href="{{ route('reports.index') }}">
                <i class="bi bi-bar-chart-line"></i> Reports
            </a>
        @endif

        @if(auth()->user()->isStudent())
            <a class="nav-link {{ request()->routeIs('attendance.scan') ? 'active' : '' }}" href="{{ route('attendance.scan') }}">
                <i class="bi bi-camera"></i> Scan QR
            </a>
            <a class="nav-link {{ request()->routeIs('attendance.history') ? 'active' : '' }}" href="{{ route('attendance.history') }}">
                <i class="bi bi-clock-history"></i> My Attendance
            </a>
        @endif

        <a class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}" href="{{ route('profile.edit') }}">
            <i class="bi bi-person-gear"></i> Profile
        </a>
    </nav>
</aside>
