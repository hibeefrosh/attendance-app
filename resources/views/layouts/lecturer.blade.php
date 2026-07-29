<div class="lecturer-shell">
    <aside class="lecturer-sidebar d-none d-lg-flex">
        <a href="{{ route('dashboard') }}" class="brand">
            <div class="brand-mark"><i class="bi bi-grid-fill"></i></div>
            <div>
                <div class="fw-bold">Smart Attendance</div>
                <small class="opacity-75">Lecturer Panel</small>
            </div>
        </a>

        <nav class="nav flex-column px-1 flex-grow-1">
            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
            <a class="nav-link {{ request()->routeIs('courses.*') ? 'active' : '' }}" href="{{ route('courses.index') }}">
                <i class="bi bi-journal-bookmark"></i> Courses
            </a>
            <a class="nav-link {{ request()->routeIs('sessions.*') ? 'active' : '' }}" href="{{ route('sessions.index') }}">
                <i class="bi bi-calendar-event"></i> Sessions
            </a>
            <a class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}" href="{{ route('reports.index') }}">
                <i class="bi bi-people"></i> Attendance
            </a>
            <a class="nav-link {{ request()->routeIs('reports.index') && false ? 'active' : '' }}" href="{{ route('reports.index') }}">
                <i class="bi bi-bar-chart-line"></i> Reports
            </a>
            <a class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}" href="{{ route('profile.edit') }}">
                <i class="bi bi-gear"></i> Settings
            </a>
        </nav>

        <div class="sidebar-footer d-flex align-items-center gap-2">
            <div class="avatar avatar-sm">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
            <div class="overflow-hidden">
                <div class="fw-semibold small text-truncate">{{ auth()->user()->name }}</div>
                <div class="small opacity-75">Lecturer</div>
            </div>
        </div>
    </aside>

    <div class="lecturer-main">
        <header class="lecturer-topbar">
            <div class="d-flex align-items-center gap-2 flex-grow-1">
                <button class="btn btn-outline-secondary d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#lecturerMobileNav">
                    <i class="bi bi-list"></i>
                </button>
                <div>
                    <div class="fw-bold">@yield('page_title', 'Dashboard')</div>
                    <small class="text-muted">@yield('page_subtitle', 'Manage attendance sessions')</small>
                </div>
            </div>
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-light position-relative rounded-circle" type="button" style="width:42px;height:42px">
                    <i class="bi bi-bell"></i>
                    <span class="position-absolute top-0 end-0 translate-middle p-1 bg-danger border border-light rounded-circle"></span>
                </button>
                <div class="dropdown">
                    <button class="btn btn-light d-flex align-items-center gap-2 rounded-pill px-2" data-bs-toggle="dropdown">
                        <div class="avatar avatar-sm">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                        <span class="d-none d-md-inline small fw-semibold">{{ auth()->user()->name }}</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profile</a></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">@csrf
                                <button class="dropdown-item text-danger" type="submit">Logout</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </header>

        <main class="lecturer-content">
            @include('layouts.partials.alerts')
            @yield('content')
        </main>
    </div>
</div>

<div class="offcanvas offcanvas-start text-bg-dark" tabindex="-1" id="lecturerMobileNav" style="background:var(--sidebar)!important">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title">Menu</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body">
        <a class="nav-link text-white mb-2" href="{{ route('dashboard') }}">Dashboard</a>
        <a class="nav-link text-white mb-2" href="{{ route('courses.index') }}">Courses</a>
        <a class="nav-link text-white mb-2" href="{{ route('sessions.index') }}">Sessions</a>
        <a class="nav-link text-white mb-2" href="{{ route('reports.index') }}">Reports</a>
        <a class="nav-link text-white mb-2" href="{{ route('profile.edit') }}">Settings</a>
    </div>
</div>
