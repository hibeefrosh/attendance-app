<header class="app-navbar border-bottom bg-white px-3 px-md-4 py-3 d-flex align-items-center justify-content-between">
    <div class="d-flex align-items-center gap-2">
        <button class="btn btn-outline-secondary d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar">
            <i class="bi bi-list"></i>
        </button>
        <div>
            <div class="fw-semibold">@yield('page_title', 'Dashboard')</div>
            <small class="text-muted">@yield('page_subtitle', 'Welcome back')</small>
        </div>
    </div>

    <div class="d-flex align-items-center gap-3">
        <div class="text-end d-none d-sm-block">
            <div class="fw-semibold small">{{ auth()->user()->name }}</div>
            <div class="text-muted" style="font-size: .75rem;">{{ auth()->user()->email }}</div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="btn btn-sm btn-outline-danger" type="submit">
                <i class="bi bi-box-arrow-right"></i> Logout
            </button>
        </form>
    </div>
</header>

<div class="offcanvas offcanvas-start" tabindex="-1" id="mobileSidebar">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title">Menu</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body p-0">
        <nav class="nav flex-column p-3 gap-1">
            <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
            <a class="nav-link" href="{{ route('courses.index') }}">Courses</a>
            <a class="nav-link" href="{{ route('sessions.index') }}">Sessions</a>
            @if(auth()->user()->isLecturer())
                <a class="nav-link" href="{{ route('reports.index') }}">Reports</a>
            @endif
            @if(auth()->user()->isStudent())
                <a class="nav-link" href="{{ route('attendance.scan') }}">Scan QR</a>
                <a class="nav-link" href="{{ route('attendance.history') }}">My Attendance</a>
            @endif
            <a class="nav-link" href="{{ route('profile.edit') }}">Profile</a>
        </nav>
    </div>
</div>
