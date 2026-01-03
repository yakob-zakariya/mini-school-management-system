<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Mark extends Model
{
    protected $fillable = [
        'enrollment_id',
        'marks',
        'total_marks',
        'exam_type',
        'exam_date',
        'remarks',
    ];

    protected function casts(): array
    {
        return [
            'marks' => 'decimal:2',
            'total_marks' => 'decimal:2',
            'exam_date' => 'date',
        ];
    }

    // Enrollment this mark belongs to
    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(Enrollment::class);
    }

    // Accessor for percentage
    public function getPercentageAttribute(): float
    {
        if ($this->total_marks > 0) {
            return round(($this->marks / $this->total_marks) * 100, 2);
        }
        return 0;
    }

    // Accessor for grade letter (optional)
    public function getGradeLetterAttribute(): string
    {
        $percentage = $this->percentage;

        return match (true) {
            $percentage >= 90 => 'A+',
            $percentage >= 80 => 'A',
            $percentage >= 70 => 'B',
            $percentage >= 60 => 'C',
            $percentage >= 50 => 'D',
            default => 'F',
        };
    }
}
