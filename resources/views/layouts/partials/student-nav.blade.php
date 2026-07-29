@php
    $navDark = $navDark ?? false;
    $isHome = request()->routeIs('dashboard');
    $isCourses = request()->routeIs('courses.*');
    $isScan = request()->routeIs('attendance.scan');
    $isHistory = request()->routeIs('attendance.history');
    $isProfile = request()->routeIs('profile.*');
@endphp
<nav class="bottom-nav {{ $navDark ? 'bottom-nav-dark' : '' }}">
    <a href="{{ route('dashboard') }}" class="{{ $isHome ? 'active' : '' }}">
        <i class="bi bi-house-door{{ $isHome ? '-fill' : '' }}"></i>
        <span>Home</span>
    </a>
    <a href="{{ route('courses.index') }}" class="{{ $isCourses ? 'active' : '' }}">
        <i class="bi bi-book{{ $isCourses ? '-fill' : '' }}"></i>
        <span>Courses</span>
    </a>
    <a href="{{ route('attendance.scan') }}" class="nav-scan {{ $isScan ? 'active' : '' }}" aria-label="Scan">
        <span class="nav-scan-btn"><i class="bi bi-qr-code-scan"></i></span>
        <span>Scan</span>
    </a>
    <a href="{{ route('attendance.history') }}" class="{{ $isHistory ? 'active' : '' }}">
        <i class="bi bi-clock-history"></i>
        <span>History</span>
    </a>
    <a href="{{ route('profile.edit') }}" class="{{ $isProfile ? 'active' : '' }}">
        <i class="bi bi-person{{ $isProfile ? '-fill' : '' }}"></i>
        <span>Profile</span>
    </a>
</nav>
