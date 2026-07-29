<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    public const LECTURER = 'lecturer';

    public const STUDENT = 'student';

    protected $fillable = [
        'name',
        'slug',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function isLecturer(): bool
    {
        return $this->slug === self::LECTURER;
    }

    public function isStudent(): bool
    {
        return $this->slug === self::STUDENT;
    }
}
