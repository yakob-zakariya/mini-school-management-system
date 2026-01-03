<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Enrollment extends Model
{
    protected $fillable = [
        'student_id',
        'grade_id',
        'subject_id',
        'academic_year',
    ];

    protected function casts(): array
    {
        return [
            'academic_year' => 'integer',
        ];
    }

    // Student enrolled
    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    // Grade enrolled in
    public function grade(): BelongsTo
    {
        return $this->belongsTo(Grade::class);
    }

    // Subject enrolled in
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    // Marks for this enrollment
    public function marks(): HasMany
    {
        return $this->hasMany(Mark::class);
    }
}
