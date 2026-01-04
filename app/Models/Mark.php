<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Mark extends Model
{
    protected $fillable = [
        'enrollment_id',
        'quiz_marks',
        'quiz_max',
        'assignment_marks',
        'assignment_max',
        'midterm_marks',
        'midterm_max',
        'final_marks',
        'final_max',
        'total_marks',
        'total_max',
        'percentage',
        'grade_letter',
    ];

    protected function casts(): array
    {
        return [
            'quiz_marks' => 'decimal:2',
            'quiz_max' => 'decimal:2',
            'assignment_marks' => 'decimal:2',
            'assignment_max' => 'decimal:2',
            'midterm_marks' => 'decimal:2',
            'midterm_max' => 'decimal:2',
            'final_marks' => 'decimal:2',
            'final_max' => 'decimal:2',
            'total_marks' => 'decimal:2',
            'total_max' => 'decimal:2',
            'percentage' => 'decimal:2',
        ];
    }

    // Enrollment this mark belongs to
    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(Enrollment::class);
    }


    protected static function booted()
    {
        static::saving(function ($mark) {
            // Calculate total
            $mark->total_marks =
                ($mark->quiz_marks ?? 0) +
                ($mark->assignment_marks ?? 0) +
                ($mark->midterm_marks ?? 0) +
                ($mark->final_marks ?? 0);

            // Calculate percentage
            if ($mark->total_max > 0) {
                $mark->percentage = ($mark->total_marks / $mark->total_max) * 100;
            }

            // Calculate grade letter
            $mark->grade_letter = match (true) {
                $mark->percentage >= 90 => 'A+',
                $mark->percentage >= 85 => 'A',
                $mark->percentage >= 80 => 'B+',
                $mark->percentage >= 75 => 'B',
                $mark->percentage >= 70 => 'C+',
                $mark->percentage >= 65 => 'C',
                $mark->percentage >= 60 => 'D',
                default => 'F',
            };
        });
    }
}
