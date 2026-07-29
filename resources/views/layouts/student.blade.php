@if(request()->routeIs('attendance.scan'))
    @yield('scan')
@elseif(request()->routeIs('attendance.success'))
    @yield('success')
@else
    <div class="student-shell">
        <main class="student-content">
            @include('layouts.partials.alerts')
            @yield('content')
        </main>
        @include('layouts.partials.student-nav')
    </div>
@endif
