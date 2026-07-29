<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AttendanceSessionController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : view('welcome');
})->name('welcome');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');

    Route::middleware('role:lecturer')->group(function () {
        Route::get('/courses/create', [CourseController::class, 'create'])->name('courses.create');
        Route::post('/courses', [CourseController::class, 'store'])->name('courses.store');
        Route::get('/courses/{course}/edit', [CourseController::class, 'edit'])->name('courses.edit');
        Route::put('/courses/{course}', [CourseController::class, 'update'])->name('courses.update');
        Route::delete('/courses/{course}', [CourseController::class, 'destroy'])->name('courses.destroy');
        Route::get('/courses/{course}/students', [CourseController::class, 'students'])->name('courses.students');
        Route::post('/courses/{course}/students', [CourseController::class, 'assignStudents'])->name('courses.students.assign');
        Route::delete('/courses/{course}/students/{student}', [CourseController::class, 'removeStudent'])->name('courses.students.remove');

        Route::get('/sessions/create', [AttendanceSessionController::class, 'create'])->name('sessions.create');
        Route::post('/sessions', [AttendanceSessionController::class, 'store'])->name('sessions.store');
        Route::post('/sessions/{session}/activate', [AttendanceSessionController::class, 'activate'])->name('sessions.activate');
        Route::post('/sessions/{session}/close', [AttendanceSessionController::class, 'close'])->name('sessions.close');
        Route::delete('/sessions/{session}', [AttendanceSessionController::class, 'destroy'])->name('sessions.destroy');
        Route::get('/sessions/{session}/qr', [AttendanceSessionController::class, 'qr'])->name('sessions.qr');
        Route::get('/sessions/{session}/qr-image', [AttendanceSessionController::class, 'qrImage'])->name('sessions.qr-image');
        Route::get('/sessions/{session}/attendance', [AttendanceController::class, 'sessionList'])->name('sessions.attendance');

        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/export', [ReportController::class, 'exportCsv'])->name('reports.export');
        Route::get('/reports/sessions/{session}/print', [ReportController::class, 'sessionExport'])->name('reports.session.print');
    });

    Route::get('/courses/{course}', [CourseController::class, 'show'])->name('courses.show');

    Route::get('/sessions', [AttendanceSessionController::class, 'index'])->name('sessions.index');
    Route::get('/sessions/{session}', [AttendanceSessionController::class, 'show'])->name('sessions.show');

    Route::middleware('role:student')->group(function () {
        Route::get('/scan', [AttendanceController::class, 'scan'])->name('attendance.scan');
        Route::post('/attendance/mark', [AttendanceController::class, 'mark'])->name('attendance.mark');
        Route::get('/attendance/success/{record}', [AttendanceController::class, 'success'])->name('attendance.success');
        Route::get('/attendance/history', [AttendanceController::class, 'history'])->name('attendance.history');
    });
});
