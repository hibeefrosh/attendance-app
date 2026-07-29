<?php

namespace App\Providers;

use App\Models\AttendanceSession;
use App\Models\Course;
use App\Policies\AttendanceSessionPolicy;
use App\Policies\CoursePolicy;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Schema::defaultStringLength(191);
        Paginator::useBootstrapFive();

        // Keep asset/route URLs on the same host:port the browser is using
        // (avoids APP_URL=http://localhost breaking CSS when visiting :8000)
        if ($this->app->runningInConsole() === false && $root = request()->getSchemeAndHttpHost()) {
            URL::forceRootUrl($root);
        }

        Gate::policy(Course::class, CoursePolicy::class);
        Gate::policy(AttendanceSession::class, AttendanceSessionPolicy::class);

        // Bind {session} route parameter to AttendanceSession (not Laravel HTTP session)
        Route::bind('session', function (string $value) {
            return AttendanceSession::query()->whereKey($value)->firstOrFail();
        });
    }
}
