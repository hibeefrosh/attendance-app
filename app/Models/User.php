<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'role_id',
        'name',
        'email',
        'matric_number',
        'department',
        'level',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function taughtCourses(): HasMany
    {
        return $this->hasMany(Course::class, 'lecturer_id');
    }

    public function registeredCourses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'course_registrations', 'student_id', 'course_id')
            ->withPivot('registered_at')
            ->withTimestamps();
    }

    public function attendanceRecords(): HasMany
    {
        return $this->hasMany(AttendanceRecord::class, 'student_id');
    }

    public function createdSessions(): HasMany
    {
        return $this->hasMany(AttendanceSession::class, 'lecturer_id');
    }

    public function isLecturer(): bool
    {
        return $this->role?->slug === Role::LECTURER;
    }

    public function isStudent(): bool
    {
        return $this->role?->slug === Role::STUDENT;
    }

    public function hasRole(string $slug): bool
    {
        return $this->role?->slug === $slug;
    }
}
