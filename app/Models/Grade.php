<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use  \Illuminate\Container\Attributes\DB;

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
            ->withPivot('teacher_id')
            ->withTimestamps();
    }

    // Enrollments in this grade
    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    // Students enrolled in this grade
    public function students()
    {
        return $this->hasMany(User::class, 'current_grade_id')->where('type', 'student');
    }

    // Get all subject-teacher assignments for this grade
    public function subjectTeachers()
    {
        return $this->belongsToMany(Subject::class, 'grade_subject')
            ->withPivot('teacher_id')
            ->withTimestamps()
            ->with('teacher'); // Eager load teacher
    }

    // Get teacher for a specific subject in this grade
    public function getTeacherForSubject($subjectId)
    {
        $pivot = DB::table('grade_subject')
            ->where('grade_id', $this->id)
            ->where('subject_id', $subjectId)
            ->first();

        return $pivot ? User::find($pivot->teacher_id) : null;
    }
}
