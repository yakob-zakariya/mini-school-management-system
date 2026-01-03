<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Grade extends Model
{
    protected $fillable = [
        'name',
        'section',
        'academic_year',
        'teacher_id',
    ];

    protected function casts(): array
    {
        return [
            'academic_year' => 'integer',
        ];
    }

    // Class teacher
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    // Subjects in this grade
    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class, 'grade_subject')
            ->withTimestamps();
    }

    // Enrollments in this grade
    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    // Students enrolled in this grade
    public function students(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'enrollments', 'grade_id', 'student_id')
            ->withTimestamps();
    }
}
