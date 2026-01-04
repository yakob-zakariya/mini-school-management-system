<?php

namespace App\Observers;

use App\Models\User;
use App\Models\Enrollment;
use Illuminate\Support\Facades\DB;

class UserObserver
{
    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        // Check if current_grade_id was changed and user is a student
        if ($user->isDirty('current_grade_id') && $user->type === 'student') {
            $newGradeId = $user->current_grade_id;

            if ($newGradeId) {
                // Get all subjects for this grade from grade_subject pivot table
                $gradeSubjects = DB::table('grade_subject')
                    ->where('grade_id', $newGradeId)
                    ->get();

                // Get current academic year
                $academicYear = date('Y');

                // Create enrollments for each subject
                foreach ($gradeSubjects as $gradeSubject) {
                    Enrollment::firstOrCreate([
                        'student_id' => $user->id,
                        'subject_id' => $gradeSubject->subject_id,
                        'academic_year' => $academicYear,
                    ], [
                        'grade_id' => $newGradeId,
                    ]);
                }
            }
        }
    }
}
