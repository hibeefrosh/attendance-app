<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

class AttendanceSession extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'course_id',
        'lecturer_id',
        'title',
        'session_date',
        'start_time',
        'end_time',
        'expires_at',
        'token',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'session_date' => 'date',
            'expires_at' => 'datetime',
        ];
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function lecturer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'lecturer_id');
    }

    public function attendanceRecords(): HasMany
    {
        return $this->hasMany(AttendanceRecord::class);
    }

    public function isExpired(): bool
    {
        return $this->expires_at->isPast() || in_array($this->status, ['expired', 'closed'], true);
    }

    public function isActive(): bool
    {
        return $this->status === 'active' && ! $this->expires_at->isPast();
    }

    public function markExpiredIfNeeded(): void
    {
        if ($this->expires_at->isPast() && ! in_array($this->status, ['expired', 'closed'], true)) {
            $this->update(['status' => 'expired']);
        }
    }

    public function qrPayload(): string
    {
        // QR encodes only the secure token — never database IDs
        return $this->token;
    }

    public function displayName(): string
    {
        return $this->title
            ?: ($this->course?->code.' — '.$this->session_date?->format('M d, Y'));
    }

    public function scopeToday($query)
    {
        return $query->whereDate('session_date', Carbon::today());
    }

    public function scopeOwnedBy($query, User $lecturer)
    {
        return $query->where('lecturer_id', $lecturer->id);
    }
}
