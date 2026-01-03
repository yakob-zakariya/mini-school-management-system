<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subject extends Model
{
    protected $fillable = [
        'name',
        'code',
        'description',
        'teacher_id',
    ];

    // Teacher assigned to this subject
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    // Grades that have this subject
    public function grades(): BelongsToMany
    {
        return $this->belongsToMany(Grade::class, 'grade_subject')
            ->withTimestamps();
    }

    // Enrollments for this subject
    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }
}
