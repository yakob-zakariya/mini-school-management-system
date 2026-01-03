<?php

namespace Database\Seeders;

use App\Models\Enrollment;
use App\Models\Grade;
use App\Models\User;
use Illuminate\Database\Seeder;

class EnrollmentSeeder extends Seeder
{
    public function run(): void
    {
        $students = User::where('type', 'student')->get();
        $grades = Grade::with('subjects')->get();

        foreach ($students as $index => $student) {
            // Assign each student to a grade
            $grade = $grades[$index % $grades->count()];

            // Enroll student in all subjects of that grade
            foreach ($grade->subjects as $subject) {
                Enrollment::firstOrCreate([
                    'student_id' => $student->id,
                    'grade_id' => $grade->id,
                    'subject_id' => $subject->id,
                    'academic_year' => 2024,
                ]);
            }
        }
    }
}
